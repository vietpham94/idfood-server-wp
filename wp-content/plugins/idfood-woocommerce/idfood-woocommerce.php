<?php
/**
 * Plugin Name: WooCommerce for IDFOOD
 * Plugin URI: https://woocommerce.com/
 * Description: An eCommerce toolkit that helps you sell anything. Beautifully.
 * Version: 6.1.0
 * Author: Automattic
 * Author URI: https://woocommerce.com
 * Text Domain: idfood-wc
 * Domain Path: /i18n/languages/
 * Requires at least: 5.6
 * Requires PHP: 7.0
 *
 * @package WooCommerce
 */

class WC_REST_Custom_Controller
{
    /**
     * You can extend this class with
     * WP_REST_Controller / WC_REST_Controller / WC_REST_Products_V2_Controller / WC_REST_CRUD_Controller etc.
     * Found in packages/woocommerce-rest-api/src/Controllers/
     */
    protected $namespace = 'wc/v3';

    protected $rest_base = 'my-orders';

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_orders'),
            )
        );
    }

    public function get_orders(WP_REST_Request $request)
    {
        if (get_current_user_id() == 0) {
            return new WP_Error(
                'woocommerce_rest_cannot_view',
                'Xin lỗi, xảy ra lỗi xác thực thông tin người dùng. Vui lòng kiểm tra thông tin và đăng nhập lại.',
                array(
                    'status' => 401,
                )
            );
        }

        $args = array(
            'post_type' => 'shop_order',
            'post_status' => ($request->get_param('status') && $request->get_param('status') != 'any') ? [$request->get_param('status')] : array_keys(wc_get_order_statuses()),
            'posts_per_page' => 10
        );

        if (!empty($request->get_param('page'))) {
            $offset = ($request->get_param('page') - 1) * 10;
            $args['offset'] = $offset;
        }

        if (!empty($request->get_param('customer'))) {
            $args['customer_id'] = $request->get_param('customer');
        } else {
            $args['meta_key'] = 'handler_user_id';
            $args['meta_value'] = get_current_user_id();
        }

        if (!empty($request->get_param('after')) && !empty($request->get_param('before'))) {
            $args['date_query'] = array(
                'after' => date('Y-m-d', strtotime($request->get_param('after'))),
                'before' => date('Y-m-d', strtotime($request->get_param('before')))
            );
        }

        if (!empty($request->get_param('after')) && empty($request->get_param('before'))) {
            $args['date_query'] = array(
                'after' => date('Y-m-d', strtotime($request->get_param('after')))
            );
        }

        if (empty($request->get_param('after')) && !empty($request->get_param('before'))) {
            $args['date_query'] = array(
                'before' => date('Y-m-d', strtotime($request->get_param('before')))
            );
        }

        if (!empty($request->get_param('search'))) {
            if (is_numeric($request->get_param('search'))) {
                $args['p'] = $request->get_param('search');
            } else if (!empty(get_search_product_ids($request->get_param('search')))) {
                $product_ids = get_search_product_ids($request->get_param('search'));
            } else if (empty(get_search_product_ids($request->get_param('search')))) {
                return array();
            }
        }

        $loop = wc_get_orders($args);

        $orders = array();
        foreach ($loop as $itemLoop) {
            $order = wc_get_order($itemLoop->get_id());
            $orderData = $order->get_data();
            $orderData['line_items'] = [];

            if (!empty($product_ids)) {
                $flagFilterProduct = false;
            } else {
                $flagFilterProduct = true;
            }

            foreach ($order->get_items() as $item_key => $item) {
                $product = $item->get_product();
                $imageLink = wp_get_attachment_thumb_url($product->get_image_id());
                $productData = $item->get_data();
                $productData['image_link'] = $imageLink;
                $productData['price'] = $product->get_price();
                $productData['product_id'] = $item->get_product_id();
                $productData['_woo_uom_input'] = $product->get_meta('_woo_uom_input');
                $orderData['line_items'][] = $productData;
                if (!empty($product_ids) && in_array($item->get_product_id(), $product_ids)) {
                    $flagFilterProduct = true;
                }
            }
            if ($flagFilterProduct) {
                $orders[] = $orderData;
            }
        }

        return $orders;
    }
}

function get_search_product_ids($search)
{
    if (empty($search)) {
        return null;
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        's' => $search
    );

    $products = new WP_Query($args);
    if (sizeof($products->posts) == 0) {
        return null;
    }

    $productIds = array();
    foreach ($products->posts as $product) {
        $productIds[] = $product->ID;
    }

    return $productIds;
}

add_filter('woocommerce_rest_api_get_rest_namespaces', 'wc_custom_api');
function wc_custom_api($controllers): array
{
    $controllers['wc/v3']['custom'] = 'WC_REST_Custom_Controller';
    return $controllers;
}