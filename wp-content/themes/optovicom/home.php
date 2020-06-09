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

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">

            <?php if (function_exists('bcn_display')) : ?>
                <div class="breadcrumbs-wrapper">
                    <?php bcn_display(); ?>
                </div>
            <?php endif; ?>

            <?php if (have_posts()) : ?>
                <header class="page__title-wrapper">
                    <h1 class="page__title"><?= get_the_title(get_option('page_for_posts')); ?></h1>
                </header>

                <?php while (have_posts()) : the_post();

                    get_template_part('template-parts/content', get_post_format());

                endwhile;

                the_posts_navigation();

            else :

                get_template_part('template-parts/content', 'none');

            endif; ?>

        </section>
    </div>

<?php get_footer();
