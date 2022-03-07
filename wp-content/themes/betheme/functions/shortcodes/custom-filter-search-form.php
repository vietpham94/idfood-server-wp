<?php
/**
 * Home page custome search form
 */
function create_search_form_shortcode()
{
    $root_category = get_term_by('slug', 'phan-loai', 'product_cat');
    $args = array(
        'type' => 'post',
        'child_of' => $root_category->term_id,
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => 0,
        'hierarchical' => 1,
        'taxonomy' => 'product_cat',
    );
    $all_categories = get_categories($args);

    $args_tag = array(
        'number' => 3,
        'orderby' => 'count',
        'order' => 'DESC'
    );

    $product_tags = get_terms('product_tag', $args_tag);
    ?>
    <form class="elementor-search-form" role="search" action="/" method="get">
        <div class="elementor-search-form__container">
            <select name="product_cat">
                <?php foreach ($all_categories as $product_cat): ?>
                    <option value="<?= $product_cat->slug; ?>"
                        <?= ($_GET["product_cat"] == $product_cat->slug) ? "selected" : ""; ?>>
                        <?= $product_cat->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input placeholder="Từ khóa tìm kiếm" class="elementor-search-form__input" type="search" name="s"
                   title="Search" value="<?= $_GET["s"] ?? ''; ?>">
            <input type="hidden" name="post_type" value="product"/>
            <button class="elementor-search-form__submit" type="submit" title="Search" aria-label="Search">
                <i class="fa fa-search" aria-hidden="true"></i>
                <span class="elementor-screen-only">Search</span>
            </button>
        </div>
    </form>
    <div class="popular-tags">
        <p><?= __("Xu hướng:"); ?>
            <?php foreach ($product_tags as $tag): ?>&nbsp;
                <a href="<?= get_term_link($tag); ?>">#<?= $tag->name; ?></a>&nbsp;
            <?php endforeach; ?>
        </p>
    </div>
    <?php
}

add_shortcode('search_form_shortcode', 'create_search_form_shortcode');