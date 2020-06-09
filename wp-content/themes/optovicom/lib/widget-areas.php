<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function optovicom_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', THEME_TD),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', THEME_TD),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<div class="widget-title">',
        'after_title' => '</div>',
    ));
}
add_action('widgets_init', 'optovicom_widgets_init');