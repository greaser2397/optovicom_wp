<?php
/**
 * The template for displaying archive pages.
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

                <header class="page-header">
                    <?php
                    the_post_thumbnail();
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="taxonomy-description">', '</div>');
                    ?>
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
