<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header();

$inst_acc = get_field('instagram_account', 'option');
$slider_type = get_field('slider_type') ?: null;
$banner_slides = get_field('banner_slider') ?: null;
?>
<div id="primary" class="content-area">
    <section id="main" class="site-main woocommerce" role="main">

        <?php
        $args = array(
            'post_type' => 'product',
            'stock' => 1,
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $slider_loop = new WP_Query($args);
        if ($slider_loop->have_posts() || !empty($banner_slides)) : ?>
            <div class="banner-slider-wrapper">
                <div class="banner-slider">
                    <?php if ($slider_type === 'banner') :
                        if (!empty($banner_slides)) :
                            foreach ($banner_slides as $slide) :
                                $image = $slide['image'];
                                $link = $slide['page_link']; ?>
                                <div class="banner-slide">
                                    <?php if ($link) echo '<a href="' . $link . '" class="slide__link">' ?>
                                    <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>">
                                    <?php if ($link) echo '</a>' ?>
                                </div>
                            <?php endforeach;
                        endif;
                    else : ?>
                        <?php while ($slider_loop->have_posts()) : $slider_loop->the_post();
                            global $product; ?>
                            <div class="banner-slide">
                                <img src="<?= get_the_post_thumbnail_url($slider_loop->post->ID); ?>" alt="">
                                <div class="slide-content">
                                    <p class="product__name"><span><?= get_the_title(); ?></span></p>
                                    <?php if ($product->get_price_html()) : ?>
                                        <p class="product__price"><?= $product->get_price_html(); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php the_permalink(); ?>" class="r-more">
                                        <?= __('Подробнее', 'optovicom') ?></a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
                <div class="preview-slider">
                    <?php if ($slider_type === 'banner') :
                        if (!empty($banner_slides)) :
                            foreach ($banner_slides as $slide) :
                                $image = $slide['image']; ?>
                                <div class="preview-slide"
                                    <?php if ($image) echo ' style="background-image: url(' . $image['url'] . '"'; ?>>
                                </div>
                            <?php endforeach;
                        endif;
                    else : ?>
                        <?php while ($slider_loop->have_posts()) : $slider_loop->the_post();
                            global $product; ?>
                            <div class="preview-slide" <?php if (has_post_thumbnail($slider_loop->post->ID)) echo ' style="background-image: url(' .
                                get_the_post_thumbnail_url($slider_loop->post->ID, 'full') . '"'; ?>>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php $advantages = get_field('advantages_section') ?: null;
        if (!empty($advantages)) : ?>
            <div class="advantages-section">
                <p class="section__title"><?= get_field('advantages_section_title'); ?></p>
                <?php foreach ($advantages as $advantage) :
                    $icon = $advantage['image'];
                    $title = $advantage['title'];
                    $desc = $advantage['text']; ?>
                    <div class="advantage-item">
                        <img src="<?= $icon['url']; ?>" alt="<?= $desc; ?>">
                        <p class="title"><?= $title; ?></p>
                        <p class="desc"><?= $desc; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php
        $args2 = array(
            'post_type' => 'product',
            'stock' => 1,
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $loop = new WP_Query($args2);
        if ($loop->have_posts()) : ?>
            <div class="new-products-wrapper">
                <p class="section__title"><?= __('Новинки', 'optovicom'); ?></p>
                <div class="new-products">
                    <?php while ($loop->have_posts()) : $loop->the_post();
                        global $product; ?>
                        <div id="product-<?php the_ID(); ?>" class="product product-<?php the_ID(); ?>">
                            <a href="<?php the_permalink(); ?>" class="product__thumbnail"
                               title="<?php the_title(); ?>">
                                <?php if ($product->is_on_sale()) : ?>
                                    <span class="sale">Скидка</span>
                                <?php endif; ?>
                                <?php if (has_post_thumbnail($loop->post->ID)) : ?>
                                    <?php echo get_the_post_thumbnail($loop->post->ID, 'medium_cropped'); ?>
                                <?php endif; ?>
                            </a>

                            <div class="product-content">
                                <p class="product__name"><?php the_title(); ?></p>
                                <span class="price"><?= $product->get_price_html(); ?></span>
                            </div>

                            <?php woocommerce_template_loop_add_to_cart($loop->post, $product); ?>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (get_the_content()) : ?>
            <div class="page-content-wrap">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

    </section>
</div>

<?php get_footer(); ?>
