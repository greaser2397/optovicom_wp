<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

$account_title = is_user_logged_in() ? false : esc_html__('Log in');
$title = is_account_page() ? $account_title : get_the_title(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ($title) : ?>
        <div class="page__title-wrapper">
            <h1 class="page__title"><?= $title; ?></h1>
        </div>
    <?php endif; ?>

    <div class="page-content">
        <?php the_content(); ?>
    </div>
</article>
