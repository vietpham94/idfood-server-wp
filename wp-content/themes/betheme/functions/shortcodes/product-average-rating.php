<?php
/**
 * Product average rating
 */
function create_product_average_rating_shortcode($args)
{
    global $product;
    ?>
    <p class="product-average-rating"><?= $product->get_average_rating(); ?></p>
    <?php
}

add_shortcode('product_average_rating_shortcode', 'create_product_average_rating_shortcode');