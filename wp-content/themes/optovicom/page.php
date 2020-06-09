<?php

if (is_user_logged_in() && is_page(get_page_by_path('register')->ID)) {
    wp_redirect(get_the_permalink(get_option('woocommerce_myaccount_page_id')));
}

get_header(); ?>

    <div id="primary" class="content-area">
        <section id="main" class="site-main" role="main">
            <?php if (function_exists('bcn_display')) : ?>
                <div class="breadcrumbs-wrapper">
                    <?php bcn_display(); ?>
                </div>
            <?php endif; ?>
            <?php while (have_posts()) : the_post();

                get_template_part('template-parts/content', 'page');

            endwhile; ?>

        </section>
    </div>

<?php get_footer();
