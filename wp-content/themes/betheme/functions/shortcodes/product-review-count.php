<?php
/**
 * Product review count
 */
function create_product_review_count_shortcode($args)
{
    global $product;
    ?>
    <p class="product-review-count"><?= sprintf(__('%s đánh giá', 'woocommerce'), $product->get_review_count()); ?></p>
    <?php
}

add_shortcode('product_review_count_shortcode', 'create_product_review_count_shortcode');