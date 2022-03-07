<?php
/**
 * Home page custom post type carousel
 */
function create_custom_post_type_carousel_shortcode($args)
{

    $post_tpe_args = array(
        'post_type'=> $args['post_type'],
        'numberposts' => $args['limit'] ? $args['limit'] : 16
    );

    $posts = get_posts($post_tpe_args);

    ?>
    <div class="images-carousel" id="<?=  $args['id'] ?>">
        <?php foreach ($posts as $post): ?>
            <div class="image-item">
                <a href="<?= get_the_permalink($post) ?>" title="<?= $post->post_title; ?>">
                    <img src="<?= get_the_post_thumbnail_url($post, 'full') ?>" />
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            if ($("#<?=  $args['id'] ?>").length > 0) {
                $("#<?=  $args['id'] ?>").slick({
                    dots: <?= $args["dots"] ? $args["dots"] : true ?>,
                    infinite: true,
                    slidesToShow: <?= $args["columns"] ? $args["columns"] : 4 ?>,
                    rows: <?= $args["rows"] ? $args["rows"] : 1 ?>,
                    autoplay: <?= $args["autoplay"] ? $args["autoplay"] : true ?>,
                    prevArrow: '<a href="#" class="prev-arrow"><img src="<?= get_template_directory_uri(); ?>/functions/shortcodes/icons/prev-arrow.png" /></a>',
                    nextArrow: '<a href="#" class="next-arrow"><img src="<?= get_template_directory_uri(); ?>/functions/shortcodes/icons/next-arrow.png" /></a>',
                });
            }
        });
    </script>
    <?php
}

// Ex: [custom_post_type_carousel_shortcode post_type=nha-cung-cap limit=16 columns=4 rows=1 slidesToScroll=4 autoplay=false]
add_shortcode('custom_post_type_carousel_shortcode', 'create_custom_post_type_carousel_shortcode');