<?php
/**
 * The template for displaying a page used to list latest posts.
 *
 * * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

  <div id="primary" class="content-area">
    <section id="main" class="site-main" role="main">
        <?php get_template_part('template-parts/breadcrumbs'); ?>

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
