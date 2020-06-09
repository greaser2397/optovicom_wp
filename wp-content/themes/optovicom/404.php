<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">

            <section class="error-404 not-found">
                <div class="page__title-wrapper">
                    <h1 class="page__title">
                        <?= esc_html__('Упс!', THEME_TD); ?>
                        <br>
                        <?= esc_html__('Страница не найдена.', THEME_TD); ?>
                    </h1>
                </div>

                <div class="page-content not-found-content">
                    <p class="not-found-notification"><?php esc_html_e('Ничего не найдено по данному адресу. Воспользуйтесь поиском, чтобы найти нужную страницу.', THEME_TD); ?></p>

                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </section>

        </section>
    </div>

<?php get_footer();
