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

        <?php get_template_part('template-parts/breadcrumbs');

        while (have_posts()) : the_post();
            get_template_part('template-parts/content-single', get_post_format());

            echo get_the_post_navigation(array(
                'prev_text'          => __('Предыдущий пост', THEME_TD),
                'next_text'          => __('Следующий пост', THEME_TD),
                'in_same_term'       => false,
                'excluded_terms'     => '',
                'screen_reader_text' => __('Post navigation', THEME_TD),
            ));
        endwhile; ?>

    </section>
  </div>

<?php get_footer();
