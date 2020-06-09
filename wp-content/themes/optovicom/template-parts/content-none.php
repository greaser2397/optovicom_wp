<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <div class="page__title-wrapper">
            <h1 class="page__title"><?php esc_html_e('Ничего не найдено', THEME_TD); ?></h1>
        </div>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) : ?>

            <p><?php printf(wp_kses(__('Готовы опубликовать свой первый пост ? <a href="%1$s">Начните здесь</a>.', THEME_TD), array('a' => array('href' => array()))), esc_url(admin_url('post-new.php'))); ?></p>

        <?php elseif (is_search()) : ?>

            <p><?php esc_html_e('Просим прощения, но Ваш запрос не совпал ни с одним релультатом поиска. Пожалуйста, попробуйте еще раз используя другие ключевые слова.', THEME_TD); ?></p>
            <?php
            get_search_form();

        else : ?>

            <p><?php esc_html_e('Скорее всего, мы не можем найти то, что Вы ищете. Возможно Вам поможет поиск.', THEME_TD); ?></p>
            <?php
            get_search_form();

        endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->
