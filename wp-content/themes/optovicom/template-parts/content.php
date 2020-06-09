<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-entry'); ?>>
    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

    <div class="entry-content">
        <?php the_excerpt(); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="site-button"><?= __('Читать далее', THEME_TD) ?></a>
</article>
