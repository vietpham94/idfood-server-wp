<?php
/**
 * Home page custom product carousel
 */
function create_product_carousel_shortcode($args)
{

    $product_args = array(
        'limit' => $args['limit'] ? $args['limit'] : 16,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (isset($args["featured"])) {
        $product_args['featured'] = $args["featured"];
    }

    if (isset($_GET['category']) && !isset($product_args['featured'])) {
        $product_args['category'] = $_GET['category'];
    }

    $products = wc_get_products($product_args);

    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('#<?=  $args['id'] ?>').slick({
                dots: <?= $args["dots"] ?? true ?>,
                infinite: true,
                slidesToShow: <?= $args["columns"] ? $args["columns"] : 4 ?>,
                rows: <?= $args["rows"] ? $args["rows"] : 1 ?>,
                slidesToScroll: <?= $args["slidesToScroll"] ?? 4 ?>,
                autoplay: <?= $args["autoplay"] ?? false ?>,
                autoplaySpeed: <?= $args["autoplaySpeed"] ?? 2000 ?>,
                prevArrow: '<a href="#" class="prev-arrow"><img src="<?= get_template_directory_uri(); ?>/functions/shortcodes/icons/prev-arrow.png" /></a>',
                nextArrow: '<a href="#" class="next-arrow"><img src="<?= get_template_directory_uri(); ?>/functions/shortcodes/icons/next-arrow.png" /></a>',
            });
        });
    </script>
    <div class="product-carousel" id="<?= $args['id'] ?>">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <?php
                $tems = get_field('danh_sach_tem', $product->id);
                $attachment_ids[0] = get_post_thumbnail_id($product->id);
                $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full');
                ?>
                <a href="<?= $product->get_permalink() ?>" title="<?= $product->name; ?>">
                    <div class="product-image" style="background-image:url(<?= $attachment[0]; ?>);">
                        <?php if (isset($tems) && !empty($tems)) : ?>
                            <img src="<?= $tems[0]['image'] ?>" class="tem-chung-nhan"/>
                        <?php endif; ?>
                    </div>
                </a>

                <table>
                    <tr>
                        <td>
                            <a href="<?= $product->get_permalink() ?>" title="<?= $product->name; ?>">
                                <p class="product-name"><?= $product->name; ?></p>
                                <p class="product-price">
                                    <?= number_format($product->price, 0, '.', ',') ?>
                                    <?php $unit_of_measure = get_post_meta($product->id, '_woo_uom_input', true); ?>
                                    <?= !empty($unit_of_measure) ? (get_woocommerce_currency_symbol() . '/' . get_post_meta($product->id, '_woo_uom_input', true)) : get_woocommerce_currency_symbol(); ?>
                                </p>
                            </a>
                        </td>
                        <td>
                            <a href="/?add-to-cart=<?= $product->id ?>" class="adding-to-cart-btn" title="Mua ngay">
                                <i class="fas fa-cart-plus"></i>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

// Ex: [product_carousel_shortcode limit=16 featured=true columns=4 rows=1 slidesToScroll=4 autoplay=false]
add_shortcode('product_carousel_shortcode', 'create_product_carousel_shortcode');