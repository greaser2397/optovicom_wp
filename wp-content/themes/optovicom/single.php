<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">
            <?php if (function_exists('bcn_display')) : ?>
                <div class="breadcrumbs-wrapper">
                    <?php bcn_display(); ?>
                </div>
            <?php endif; ?>
            <?php while (have_posts()) : the_post();

                get_template_part('template-parts/content-single', get_post_format());

                echo get_the_post_navigation(array(
                    'prev_text' => __('Предыдущий пост', 'optovicom'),
                    'next_text' => __('Следующий пост', 'optovicom'),
                    'in_same_term' => false,
                    'excluded_terms' => '',
                    'screen_reader_text' => __('Post navigation'),
                ));

            endwhile; ?>

        </section>
    </div>

<?php get_footer();
