<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

// prev & next post -------------------

$single_post_nav = array(
    'hide-header' => false,
    'hide-sticky' => false,
);

$opts_single_post_nav = mfn_opts_get('prev-next-nav');
if (is_array($opts_single_post_nav)) {

    if (isset($opts_single_post_nav['hide-header'])) {
        $single_post_nav['hide-header'] = true;
    }
    if (isset($opts_single_post_nav['hide-sticky'])) {
        $single_post_nav['hide-sticky'] = true;
    }

}

$post_prev = get_adjacent_post(false, '', true);
$post_next = get_adjacent_post(false, '', false);
$shop_page_id = wc_get_page_id('shop');


// post classes -----------------------

$classes = array();

if (mfn_opts_get('share') == 'hide-mobile') {
    $classes[] = 'no-share-mobile';
} elseif (!mfn_opts_get('share')) {
    $classes[] = 'no-share';
}

if (mfn_opts_get('share-style')) {
    $classes[] = 'share-' . mfn_opts_get('share-style');
}

$single_product_style = mfn_opts_get('shop-product-style');
$classes[] = $single_product_style;
$classes[] = 'container-fluid';

// translate
$translate['all'] = mfn_opts_get('translate') ? mfn_opts_get('translate-all', 'Show all') : __('Show all', 'betheme');

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class($classes, $product); ?>>

    <?php
    // single post navigation | sticky
    if (!$single_post_nav['hide-sticky']) {
        echo mfn_post_navigation_sticky($post_prev, 'prev', 'icon-left-open-big');
        echo mfn_post_navigation_sticky($post_next, 'next', 'icon-right-open-big');
    }
    ?>

    <?php
    // single post navigation | header
    if (!$single_post_nav['hide-header']) {
        echo mfn_post_navigation_header($post_prev, $post_next, $shop_page_id, $translate);
    }
    ?>

    <?php
    global $product;
    $gallery_image_ids = $product->get_gallery_image_ids();
    $attachment_image = wp_get_attachment_image_url($product->get_image_id(), 'single-post-thumbnail');
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    function vn_to_str($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ', '', $str);
        return $str;
    }

    function formatSearchAddressGoogle($str)
    {
        $str = str_replace(' ', '+', $str);
        return $str;
    }

    $PublicIP = get_client_ip();
    if ($PublicIP == "127.0.0.1") {
        $PublicIP = "171.229.211.236";
    }
    $json = file_get_contents("https://ipinfo.io/$PublicIP?token=3ee493ed007168");
    $json = json_decode($json, true);
    $city = $json['city'];
    $cityVn = $city;
    $customerCity = strtoupper(vn_to_str($city));

    //$cac_nha_cung_cap = get_field('cac_nha_cung_cap', get_the_ID(), true);
    $providers_number_str = get_post_meta(get_the_ID(), 'cac_nha_cung_cap');
    if (!empty($providers_number_str)) {
        $providers_number_num = intval($providers_number_str[0]);
    } else {
        $providers_number_num = 0;
    }
    $cac_nha_cung_cap = [];
    if ($providers_number_num > 0) {
        for ($i = 0; $i < $providers_number_num; $i++) {
            $key_cc = 'cac_nha_cung_cap_' . $i . '_nha_cung_cap';
            $cac_nha_cung_cap[] = array('nha_cung_cap' => get_post_meta(get_the_ID(), $key_cc)[0]);
        }
    }

    $cac_nha_cung_cap_khu_vuc = array();
    foreach ($cac_nha_cung_cap as $item) {
        $provider = new WC_Customer($item['nha_cung_cap']);
        if ($customerCity == strtoupper(vn_to_str($provider->get_city()))) {
            $cac_nha_cung_cap_khu_vuc[] = $provider;
            $cityVn = $provider->get_city();
        }
    }
    ?>

    <div class="product_wrapper clearfix">
        <div class="row">
            <!-- Product gallery images -->
            <div class="col-12 col-lg-6 col-xl-5">
                <?php if (!empty($attachment_image)): ?>
                    <div id="viewProduct" class="view-product border" style="background-image: url(<?php echo $attachment_image; ?>)"
                         title="<?php get_the_title(); ?>">
                        <a href="<?php echo $attachment_image; ?>" rel="lightbox" data-type="image"
                           style="padding: 45% 0;">
                            <img src="<?php echo $attachment_image; ?>" alt="<?php get_the_title(); ?>"
                                 style="visibility: hidden"/>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="row product-gallery-nav">
                    <?php if (!empty($attachment_image)): ?>
                        <div class="col-3 cursor-pointer image-item mt-3">
                            <img src="<?php echo $attachment_image; ?>" alt="<?php get_the_title(); ?>"
                                 class="active border" data-img-index="0"
                                 title="<?php get_the_title(); ?>"/>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($gallery_image_ids)): ?>
                        <?php foreach ($gallery_image_ids as $key => $gallery_image_id): ?>
                            <div class="col-3 cursor-pointer image-item mt-3">
                                <img src="<?php echo wp_get_attachment_url($gallery_image_id); ?>"
                                     alt="<?php get_the_title(); ?>" class="border" data-img-index="<?= $key + 1; ?>" title="<?php get_the_title(); ?>"/>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product data & action -->
            <div class="col-12 col-lg-6 col-xl-7">
                <div class="row product-data-action">
                    <!-- product name-->
                    <div class="col-12">
                        <h1 class="product_title"><?php the_title() ?></h1>
                    </div>

                    <!-- meta data -->
                    <div class="col-12">
                        <p class="average-rating pr-2"><?php echo $product->get_average_rating(); ?></p>
                        <div class="star-rating" role="img">
                            <?php $average_rating_percent = ($product->get_average_rating() / 5) * 100; ?>
                            <span style="width:<?php echo $average_rating_percent ?>%;"></span>
                        </div>
                        <p class="slap">|</p>
                        <p class="product-review-count"><a
                                    href="#comment-list"><?php echo $product->get_review_count(); ?> đánh giá</a></p>
                        <p class="slap">|</p>
                        <p class="product-sold-count"> đã bán
                            <?php echo get_post_meta($product->get_id(), 'total_sales', true); ?>
                        </p>
                        <div class="icons d-inline-flex">
                            <p class="heading-title d-inline-block mb-0">Chia sẻ: &nbsp;&nbsp;&nbsp;</p>
                            <a target="popup" class="facebook d-inline-flex"
                               href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>">
                                <i class="fab fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a target="popup" class="linkedin d-inline-flex"
                               href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_the_permalink(); ?>">
                                <i class="fab fa-linkedin" aria-hidden="true" style="color: #007ab9;"></i>
                            </a>
                            <a target="popup" class="pinterest d-inline-flex"
                               href="https://www.pinterest.com/pin/create/button/?url=<?php echo get_the_permalink(); ?>&media=<?php echo $attachment_image; ?>">
                                <i class="fab fa-pinterest" aria-hidden="true" style="color: #bd081c;"></i>
                            </a>
                        </div>
                        <hr class="mt-2"/>
                    </div>

                    <!-- product price -->
                    <div class="col-12">
                        <h2 class="heading-title mb-0">Giá bán</h2>
                        <p class="price">
                            <?php echo $product->get_price_html(); ?>
                        </p>
                    </div>

                    <!-- Add to Cart    -->
                    <div class="col-12">
                        <h2 class="heading-title">Số lượng</h2>
                        <form class="cart" action="<?php echo get_the_permalink(); ?>" method="post"
                              enctype="multipart/form-data">
                            <div class="quantity">
                                <input type="number" step="1" min="1"
                                       max="<?php echo get_post_meta(get_the_ID(), '_stock', true) ?>"
                                       name="quantity" value="1" inputmode="numeric" autocomplete="off">
                            </div>
                            <button type="submit" name="add-to-cart" value="<?php echo get_the_ID(); ?>"
                                    class="single-pay-now-button button ml-0 ml-md-2">Mua ngay
                            </button>
                        </form>
                    </div>

                    <!-- Buy directly / Register to distribute  -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-6 buy-in-location mb-3 mb-md-0">
                                <h4 class="title">Bạn muốn mua hàng trực tiếp?</h4>
                                <p class="number-location mb-1">
                                    Có tất cả <?= sizeof($cac_nha_cung_cap_khu_vuc); ?> điểm bán lẻ tại khu
                                    vực <?= $cityVn ?>.
                                </p>
                                <a href="#diem-ban-gan-day">Xem chi tiết</a>
                            </div>
                            <div class="col-12 col-md-6 border-md-left">
                                <?php echo do_shortcode('[elementor-template id="1452"]'); ?>
                            </div>
                        </div>
                        <hr class="d-none d-md-block"/>
                    </div>

                    <!-- Quality standards   -->
                    <div class="col-12 mt-3 mt-md-0 quality-standards">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <p class="heading-title mr-3">Tiêu chuẩn chất lượng</p>
                                <?php $danh_sach_tem = get_field('danh_sach_tem', get_the_ID()); ?>
                                <?php if (!empty($danh_sach_tem)): ?>
                                    <?php foreach ($danh_sach_tem as $tem): ?>
                                        <img src="<?php echo $tem['image']; ?>"
                                             class="attachment-medium size-medium p-1" alt="" loading="lazy"/>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6 border-md-left htx">
                                <?php $htx = get_field('htx', get_the_ID()); ?>
                                <?php if (!empty($htx["link"])): ?>
                                    <?php if (empty($htx["title"])) {
                                        $htx_page = get_page_by_path($htx["link"]);
                                    } ?>
                                    <p class="heading-title mb-1 mt-4 mt-xl-0 mr-2 mr-md-0">Sản phẩm của</p>
                                    <a href="<?php echo $htx["link"]; ?>" target="_blank">
                                        <i class="premium-title-icon far fa-hand-point-right" aria-hidden="true"></i>
                                        <span class="premium-title-text"><?= empty($htx['title']) ? $htx_page->post_title : $htx['title']; ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php $link_truy_xuat_nguon_goc = get_field('link_truy_xuat_nguon_goc', get_the_ID()); ?>
                                <a class="button mt-3" target="_blank"
                                   href="<?= empty($link_truy_xuat_nguon_goc) ? '#' : $link_truy_xuat_nguon_goc; ?>">
                                    <i aria-hidden="true" class="fas fa-qrcode"></i>
                                    <span>Tra cứu nguồn gốc sản phẩm</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 product-summary">
            <div class="col-12 col-lg-9">
                <div class="product-content">
                    <h2 class="title">Giới thiệu sản phẩm</h2>
                    <div class="summary-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="show-more-content button">Xem thêm</a>
                    </div>
                </div>

                <div class="product-location mt-4" id="diem-ban-gan-day">
                    <h2 class="title">Điểm bán lẻ tại khu
                        vực <?= $cityVn; ?>(<?= sizeof($cac_nha_cung_cap_khu_vuc); ?>)</h2>
                    <?php foreach ($cac_nha_cung_cap_khu_vuc as $diem_ban_le): ?>
                        <div class="location">
                            <p class="location-name mb-1"><?= $diem_ban_le->display_name; ?></p>
                            <p class="location-position mb-1 d-none">Khoảng cách: 1,5km</p>
                            <p class="location-address">
                                <?= $diem_ban_le->address_1; ?><br/>
                                Điện thoại:
                                <?php echo $diem_ban_le->billing['phone']; ?>
                            </p>
                            <a class="button buy-now d-none">Mua hàng</a>
                            <a class="button direct" target="_blank"
                               href="https://www.google.co.uk/maps/place/<?= formatSearchAddressGoogle($diem_ban_le->address_1); ?>">Chỉ
                                đường</a>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                    <?php if (sizeof($cac_nha_cung_cap_khu_vuc) > 2): ?>
                        <div class="text-center mt-3">
                            <a href="#" class="show-more-location button">Xem thêm</a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
                    <div class=" comment-form-area mt-4">
                        <h2 class="title">Viết đánh giá</h2>
                        <div id="reviews" class="woocommerce-Reviews">
                            <div id="review_form_wrapper">
                                <div id="review_form">
                                    <?php
                                    $commenter = wp_get_current_commenter();
                                    $comment_form = array(
                                        'title_reply' => have_comments() ? __('Add a review', 'woocommerce') : sprintf(__('Đánh giá của bạn rất hữu ích với cộng đồng. Đánh giá ngay thôi nào.', 'woocommerce'), get_the_title()),
                                        'fields' => array(
                                            'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__('Name', 'woocommerce') . '&nbsp;    </label> ' .
                                                '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '"     size="30" aria-required="true" required /></p>',
                                            'email' => '<p class="comment-form-email"><label for="email">' . esc_html__('Email', 'woocommerce') . '&nbsp;</label> ' .
                                                '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-required="true" required /></p>',
                                        ),
                                        'label_submit' => __('Submit', 'woocommerce'),
                                        'logged_in_as' => '',
                                        'comment_field' => '',
                                    );

                                    if ($account_page_url = wc_get_page_permalink('myaccount')) {
                                        $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf(__('Bạn cần phải <a href="%s">đăng nhập</a> để có thể bình luận.', 'woocommerce'), esc_url($account_page_url)) . '</p>';
                                    }

                                    if (get_option('woocommerce_enable_review_rating') === 'yes') {
                                        $comment_form['comment_field'] = '
                                        <div class="comment-form-rating">
                                            <label for="rating">' . esc_html__('Điểm đánh giá', 'woocommerce') . '<span style="color: red;">*</span></label>
                                            <select name="rating" id="rating" aria-required="true" required>
                                                <option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>
                                                <option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>
                                                <option value="4">' . esc_html__('Good', 'woocommerce') . '</option>
                                                <option value="3">' . esc_html__('Average', 'woocommerce') . '</option>
                                                <option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>
                                                <option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>
                                            </select>
                                        </div>';
                                    }

                                    $comment_form['comment_field'] .= '
                                        <p class="comment-form-comment">
                                            <textarea id="comment" name="comment" cols="45" rows="8" required placeholder="Nội dung đánh giá(*)"></textarea>
                                        </p>';

                                    comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="comment-list" class="comment-list mt-4">
                    <?php
                    $comments = get_comments('post_id=' . $product->get_id());
                    $number_comments = get_comments_number(get_the_ID());
                    ?>
                    <h2 class="title pb-3">Đánh giá sản phẩm (<?php echo $product->get_review_count(); ?>
                        )</h2>
                    <?php if (sizeof($comments) == 0): ?>
                        <p style="font-style: italic;">
                            Hãy đặt mua sản phẩm ngay và trở thành người đầu tiên đánh giá sản phẩm này!
                        </p>
                    <?php else: foreach ($comments as $comment):
                        $userAvartar = get_avatar_url($comment->comment_author_email);
                        $user = get_user_by_email($comment->comment_author_email);
                        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                        ?>
                        <div class="row comment-item mb-2 mb-lg-0">
                            <div class="col-2 avatar m-auto">
                                <img style="width: auto; height: 100%; border-radius: 50%; padding: 10%;"
                                     alt=""
                                     src="<?php echo $userAvartar; ?>" srcset="<?php echo $userAvartar; ?>"
                                     class="avatar avatar-26 photo" height="26" width="26" loading="lazy">
                            </div>
                            <div class="col-10 comment-data m-auto">
                                <p class="name mb-0 font-weight-bold"><?php echo $user->display_name; ?></p>
                                <div class="rating d-inline-flex m-auto">
                                    <p class="stars selected d-inline-flex m-auto pr-3">
                                            <span class="d-inline-flex">
                                                <a class="star-1<?= $rating == '1' ? ' active' : ''; ?>" href="#"
                                                   style="color: #f2643d;">1</a>
                                                <a class="star-2<?= $rating == '2' ? ' active' : ''; ?>" href="#"
                                                   style="color: #f2643d;">2</a>
                                                <a class="star-3<?= $rating == '3' ? ' active' : ''; ?>" href="#"
                                                   style="color: #f2643d;">3</a>
                                                <a class="star-4<?= $rating == '4' ? ' active' : ''; ?>" href="#"
                                                   style="color: #f2643d;">4</a>
                                                <a class="star-5<?= $rating == '5' ? ' active' : ''; ?>" href="#"
                                                   style="color: #f2643d;">5</a>
                                            </span>
                                    </p>
                                    <p class="date d-inline-flex m-auto"><?php echo date_format(date_create($comment->comment_date), "d/m/Y"); ?></p>
                                </div>
                                <p class="comment-content mb-0"><?php echo $comment->comment_content; ?></p>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                    <?php if ($product->get_review_count() > 5): ?>
                        <div class="view-all-comments text-center">
                            <a class="button show-all-comments">Xem thêm</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-lg-3 d-none d-lg-block related-product-area">
                <h3 class="related-product-title">CÓ THỂ BẠN QUAN TÂM</h3>
                <?php echo do_shortcode('[recent_products per_page="10" columns="1" orderby="rand" order="rand"]'); ?>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {

            $(".view-product").mousemove(function (e) {
                let lw = $(this).width();
                let lh = $(this).height();

                let offset = $(this).offset();
                let relX = e.pageX - offset.left;
                let relY = e.pageY - offset.top;

                if (relX < 0) {
                    relX = 0
                }

                if (relX > lw) {
                    relX = lw
                }

                if (relY < 0) {
                    relY = 0
                }

                if (relY > lh) {
                    relY = lh
                }

                let zx = -(0.5 * relX);
                let zy = -(0.5 * relY);

                $(this).css('background-position', (zx) + "px " + (zy) + "px");
            });

            $(".view-product").mouseout(function (e) {
                $(this).css('background-position', '');
            });

            $(".image-item img").click(function (e) {
                let imgUrl = $(this).attr('src');
                $(".view-product img").attr('src', imgUrl);
                $(".view-product a").attr('href', imgUrl);
                $(".view-product").css('background-image', 'url(' + imgUrl + ')');
                $(".image-item img").removeClass("active");
                $(this).addClass("active");
            });

            $(".show-more-content").click(function (e) {
                e.preventDefault();
                $(".summary-content").toggleClass('show');
                if ($(".summary-content").hasClass('show')) {
                    $(this).html('Thu gọn');
                } else {
                    $(this).html('Xem thêm');
                }
            });

            let viewProductHeight = $(".view-product img").width();
            $(".view-product").css("max-height", viewProductHeight + "px");
            $(".view-product").css("min-height", viewProductHeight + "px");

            let imageItemWidth = $(".image-item").width();
            $(".image-item").css("max-height", imageItemWidth + "px");
            $(".image-item").css("min-height", imageItemWidth + "px");
        });
    </script>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
