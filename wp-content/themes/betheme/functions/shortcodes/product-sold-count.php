<?php
/**
 * Product sold count
 */
function create_product_sold_count_shortcode($args)
{
    global $product;
    $units_sold = $product->get_total_sales();
    if (isset($units_sold)) {
        ?>
        <p class="product-sold-count"><?= sprintf( __( 'đã bán %s', 'woocommerce' ), $units_sold ); ?></p>
    <?php
    }
}

add_shortcode('product_sold_count_shortcode', 'create_product_sold_count_shortcode');