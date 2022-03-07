<?php
/**
 * Template Name: Products
 *
 * @package Betheme
 * @author Muffin Group
 * @link https://muffingroup.com
 */
function products_head()
{
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= get_template_directory_uri() . '/css/products.css' ?>">
    <?php
}

add_action('wp_head', 'products_head');

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
$product_args = array(
    'limit' => 16,
    'page' => $paged,
    'orderby' => 'date',
    'order' => 'DESC'
);
if (isset($_GET['categories']) && !empty($_GET['categories'])) {
    $checked_categories = $_GET['categories'];
    $product_args['category'] = $_GET['categories'];
}

$products = wc_get_products($product_args);

function get_root_product_categories()
{
    $all_root_categories_args = array(
        'taxonomy' => 'product_cat',
        'parent' => 0,
        'show_count' => 0,
        'pad_counts' => 0,
        'hierarchical' => 0,
        'title_li' => '',
        'hide_empty' => 0,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    return get_categories($all_root_categories_args);
}

function get_children_product_categorie($parent)
{
    $categories_children_args = array(
        'type' => 'post',
        'child_of' => $parent->term_id,
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => 0,
        'hierarchical' => 1,
        'taxonomy' => 'product_cat',
    );
    return get_categories($categories_children_args);
}

?>
    <div id="Content" class="container-fluid">
        <div class="content_wrapper clearfix">

            <div class="sections_group">

                <div class="section">
                    <div class="section_wrapper clearfix">
                        <div class="column one products">
                            <div class="row">
                                <!--  Categories filter  -->
                                <div class="col-lg-3 pr-4 hide-md">
                                    <div class="row">
                                        <div class="col-12 product-categories">
                                            <form method="GET" action="/dac-san-vung-mien" id="selectCategories">
                                                <?php
                                                $all_root_categories = get_root_product_categories();
                                                if (isset($all_root_categories)):
                                                    foreach ($all_root_categories as $key0 => $root_category):
                                                        ?>
                                                        <fieldset>
                                                            <legend><?= $root_category->name ?></legend>
                                                            <?php
                                                            $all_children_categories = get_children_product_categorie($root_category);
                                                            foreach ($all_children_categories as $key1 => $child_category):
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-1 m-auto">
                                                                        <input type="checkbox"
                                                                               value="<?= $child_category->slug ?>"
                                                                               id="cb<?= $key0 . $key1 ?>"
                                                                               name="categories[]"
                                                                               onclick="filter();"
                                                                            <?php if (isset($checked_categories) && in_array($child_category->slug, $checked_categories)) {
                                                                                echo 'checked';
                                                                            } ?>>
                                                                        <label for="cb<?= $key0 . $key1; ?>"></label>
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <span class="category-title"><?= $child_category->name ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </fieldset>
                                                    <?php endforeach;
                                                endif;
                                                ?>
                                            </form>
                                            <script type="application/javascript">
                                                function filter() {
                                                    document.getElementById("selectCategories").submit();
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <!--  Products result  -->
                                <div class="col-md-12 col-lg-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="page-title"><?= get_the_title() ?></h2>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <?php foreach ($products as $product): ?>
                                            <div class="col-6 col-md-4 col-lg-3 product-carousel">
                                                <div class="product-item">
                                                    <?php
                                                    $tems = get_field('danh_sach_tem', $product->id);
                                                    $attachment_ids[0] = get_post_thumbnail_id($product->id);
                                                    $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full');
                                                    ?>
                                                    <a href="<?= $product->get_permalink() ?>"
                                                       title="<?= $product->name; ?>">
                                                        <div class="product-image"
                                                             style="background-image:url(<?= $attachment[0]; ?>);">
                                                            <?php if (isset($tems) && !empty($tems)) : ?>
                                                                <img src="<?= $tems[0]['image'] ?>" class="tem-chung-nhan"/>
                                                            <?php endif; ?>
                                                        </div>
                                                    </a>

                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <a href="<?= $product->get_permalink() ?>"
                                                                   title="<?= $product->name; ?>">
                                                                    <p class="product-name"><?= $product->name; ?></p>
                                                                    <p class="product-price">
                                                                        <?= number_format($product->price, 0, '.', ',') ?>
                                                                        <?php $unit_of_measure = get_post_meta($product->id, '_woo_uom_input', true); ?>
                                                                        <?= !empty($unit_of_measure) ? (get_woocommerce_currency_symbol() . '/' . get_post_meta($product->id, '_woo_uom_input', true)) : get_woocommerce_currency_symbol(); ?>
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="/?add-to-cart=<?= $product->id ?>"
                                                                   class="adding-to-cart-btn" title="Mua ngay">
                                                                    <i class="fas fa-shopping-bag"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <?php
                                            $products_query_args = array(
                                                'post_type' => 'product',
                                                'posts_per_page' => 16,
                                                'paged' => $paged
                                            );

                                            if (isset($_GET['categories']) && !empty($_GET['categories'])) {
                                                $tax_query = array('relation' => 'OR');
                                                foreach ($_GET['categories'] as $selected_cat) {
                                                    $tax_query[] =  array(
                                                        'taxonomy' => 'product_cat',
                                                        'field' => 'slug',
                                                        'terms' => $selected_cat
                                                    );
                                                }
                                                $products_query_args['tax_query'] = $tax_query;
                                            }

                                            $products_query = new WP_Query($products_query_args);

                                            echo mfn_pagination($products_query);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

<?php get_footer();

