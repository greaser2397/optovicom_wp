<?php
/**
 * Enqueue all styles and scripts.
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style}
 */
if (!function_exists('optovicom_scripts')) :
    function optovicom_scripts()
    {
        global $wp_query;

        // Enqueue the main Stylesheet.
        wp_enqueue_style('main-stylesheet', asset_path('styles/main.css'), false, null, 'all');

        // Deregister the jquery version bundled with WordPress.
        wp_deregister_script('jquery');

        // CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
        wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '2.2.4', false);

        // CDN hosted slick-carousel with theme-styles
        wp_enqueue_script('slick-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js');
        wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
        wp_enqueue_style('slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');

        // Select-2
        wp_enqueue_script('select-2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js');
        wp_enqueue_style('select-2-styles', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');

        wp_register_script('loadMore', get_template_directory_uri() . '/assets/scripts/modules/loadMore.js', array('jquery'), null, false);
        wp_register_script('main-javascript', asset_path('scripts/main.js'), array('jquery'), null, true);

        $globalVars = array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
            'posts' => json_encode($wp_query->query_vars),
            'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
            'max_page' => $wp_query->max_num_pages
        );
        wp_localize_script('loadMore', 'globalVars', $globalVars);
        wp_localize_script('main-javascript', 'globalVars', $globalVars);

        // Enqueue the main JS file.
        wp_enqueue_script('main-javascript');
        wp_enqueue_script('loadMore');

        // Comments reply script
        if (is_singular() && comments_open()):
            wp_enqueue_script("comment-reply");
        endif;
    }

    add_action('wp_enqueue_scripts', 'optovicom_scripts');
endif;

if (file_exists(get_template_directory() . '/feedback/feedback.php')) :
    require_once get_template_directory() . '/feedback/feedback.php';
endif;