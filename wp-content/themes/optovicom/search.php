<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */

get_header(); ?>

    <section id="primary" class="content-area">
        <section id="main" class="site-main" role="main">

            <?php
            if (have_posts()) : ?>

                <header class="page__title-wrapper">
                    <h1 class="page__title"><?php printf(esc_html__('Результаты поиска для: %s', THEME_TD), '<br><strong>' . get_search_query() . '</s>'); ?></h1>
                </header>

                <?php
                /* Start the Loop */
                while (have_posts()) : the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('template-parts/content', 'search');

                endwhile;

                the_posts_navigation();

            else :

                get_template_part('template-parts/content', 'none');

            endif; ?>

        </section>
    </section>

<?php get_footer();
