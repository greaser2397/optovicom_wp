<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/**
 * Text domain definition
 */
defined('THEME_TD') ? THEME_TD : define('THEME_TD', 'optovicom');

# Load modules
$theme_includes = [
    '/lib/helpers.php',
    '/lib/cleanup.php',                        # Clean up default theme includes
    '/lib/enqueue-scripts.php',                # Enqueue styles and scripts
    '/lib/protocol-relative-theme-assets.php', # Protocol (http/https) relative assets path
    '/lib/framework.php',                      # Css framework related stuff (content width, nav walker class, comments, pagination, etc.)
    '/lib/theme-support.php',                  # Theme support options
    '/lib/template-tags.php',                  # Custom template tags
    '/lib/menu-areas.php',                     # Menu areas
    '/lib/widget-areas.php',                   # Widget areas
    '/lib/customizer.php',                     # Theme customizer
    '/lib/vc_shortcodes.php',                  # Visual Composer shortcodes
    '/lib/jetpack.php',                        # Jetpack compatibility file
    '/lib/acf_field_groups_type.php',          # ACF Field Groups Organizer
];

foreach ($theme_includes as $file) {
    if (!$filepath = locate_template($file)) {
        continue;
        trigger_error(sprintf(__('Error locating %s for inclusion', THEME_TD), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);

# Theme the TinyMCE editor (Copy post/page text styles in this file)
add_editor_style('assets/dist/css/custom-editor-style.css');


/**
 * Custom CSS for the login page
 */
add_action('login_head', function () {
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri(THEME_TD) . 'assets/dist/css/wp-login.css"/>';
});


/**
 * Add body class for active sidebar
 * @param $classes array
 *
 * @return array
 */
add_filter('body_class', function ($classes) {
    if (is_active_sidebar('sidebar')) {
        # add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }

    # return the $classes array
    return $classes;
});

/**
 * Remove the version number of WP
 * Warning - this info is also available in the readme.html file in your root directory - delete this file!
 */
remove_action('wp_head', 'wp_generator');


/**
 * Obscure login screen error messages
 */
add_filter('login_errors', function () {
    return '<strong>Error</strong>: wrong username or password';
});


/**
 * Disable the theme / plugin text editor in Admin
 */
define('DISALLOW_FILE_EDIT', true);

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Options',
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-generic',
    ));
}

/**
 * Enable WooCommerce default gallery features disabled by default.
 */
add_action('after_setup_theme', function () {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});

/**
 * Change woocommerce default breadcrumbs appearance
 */
add_filter('woocommerce_breadcrumb_defaults', function () {
    return [
        'delimiter'   => '<span> / </span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '<span>',
        'after'       => '</span>',
    ];
});

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', function ($currency_symbol, $currency) {
    switch ($currency) {
        case 'RUB':
            $currency_symbol = 'руб';
            break;
    }
    return $currency_symbol;
}, 10, 2);

remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

function optovicom_widget_shopping_cart_button_view_cart()
{
    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="button wc-forward">' . esc_html__('Открыть корзину', 'woocommerce') . '</a>';
}

add_action('woocommerce_widget_shopping_cart_buttons', 'optovicom_widget_shopping_cart_button_view_cart', 10);


function optovicom_widget_shopping_cart_proceed_to_checkout()
{
    echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="button checkout wc-forward">' . esc_html__('Купить', 'woocommerce') . '</a>';
}

add_action('woocommerce_widget_shopping_cart_buttons', 'optovicom_widget_shopping_cart_proceed_to_checkout', 20);

function optovicom_cart_count_with_text()
{
    $count = WC()->cart->get_cart_contents_count();
    $text = '';

    switch (true) {
        case ($count == 1) :
            $text = esc_html__(get_field('cart_items_text_singular', 'option'), 'woocommerce');
            break;
        case ($count > 1 && $count < 5) :
            $text = esc_html__(get_field('cart_items_text_plural', 'option'), 'woocommerce');
            break;
        case ($count >= 5) :
            $text = esc_html__(get_field('cart_items_text_plural_2', 'option'), 'woocommerce');
            break;
    }

    $output = '<span class="cart-totals-text">' . $count . ' ' . $text . '</span>';

    if ($count != 0) {
        return $output;
    } else {
        return null;
    }
}

add_image_size('medium_cropped', 500, 500, true);
add_image_size('single_product', 800, 800, true);

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_after_shop_loop_item_add_to_cart', 'woocommerce_template_loop_add_to_cart', 10);

function optovicom_template_loop_product_title()
{
    echo '<a href="' . get_the_permalink() . '"><div class="woocommerce-loop-product__title">' . get_the_title() . '</div></a>';
}

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'optovicom_template_loop_product_title', 10);

function optovicom_woo_my_account_order()
{
    $myorder = array(
        'edit-account'    => __('Личная информация', 'woocommerce'),
        'dashboard'       => __('Dashboard', 'woocommerce'),
        'orders'          => __('Мои заказы', 'woocommerce'),
        'downloads'       => __('Downloads', 'woocommerce'),
        'edit-address'    => __('Адреса доставки', 'woocommerce'),
        'customer-logout' => __('Logout', 'woocommerce'),
    );
    return $myorder;
}

add_filter('woocommerce_account_menu_items', 'optovicom_woo_my_account_order');

add_filter('woocommerce_account_menu_items', 'optovicom_remove_my_account_items');
function optovicom_remove_my_account_items($menu_links)
{
    unset($menu_links['dashboard']); # Remove Dashboard link
    unset($menu_links['downloads']); # Remove Downloads link
    return $menu_links;
}

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {

    ob_start(); ?>

  <span class="cart-contents-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>

    <?php $fragments['span.cart-contents-count'] = ob_get_clean();

    return $fragments;

});

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {

    ob_start(); ?>

  <div class="mini-cart-container">
      <?php woocommerce_mini_cart(); ?>
  </div>

    <?php $fragments['div.mini-cart-container'] = ob_get_clean();

    return $fragments;

});

add_action('woocommerce_before_customer_login_form', 'optovicom_load_registration_form', 2);

function optovicom_load_registration_form()
{
    if (isset($_GET['action']) == 'register') {
        wc_get_template('myaccount/form-register.php');
    }
}

function optovicom_remove_breadcrumb_on_single()
{
    if (is_product() && is_single()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }
}

add_action('woocommerce_before_main_content', 'optovicom_remove_breadcrumb_on_single');

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action('woocommerce_output_product_data_tabs', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_output_related_products', 'woocommerce_output_related_products', 20);

function optovicom_change_single_product_details_order()
{
    /**
     * Product Summary Box.
     *
     * @see woocommerce_template_single_title()
     * @see woocommerce_template_single_rating()
     * @see woocommerce_template_single_price()
     * @see woocommerce_template_single_excerpt()
     * @see woocommerce_template_single_meta()
     * @see woocommerce_template_single_sharing()
     */
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
}

add_action('init', 'optovicom_change_single_product_details_order');


add_filter('woocommerce_checkout_fields', 'optovicom_remove_checkout_fields');
function optovicom_remove_checkout_fields($fields)
{

    # Billing fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_2']);

    # Shipping fields
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_postcode']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_address_2']);

    unset($fields['order']['order_comments']);

    return $fields;
}

/**
 * Inject additional billing fields into checkout form
 */
add_filter('woocommerce_billing_fields', function ($fields) {

    $fields['billing_comments'] = array(
        'label'    => esc_html__('Примечание к заказу', 'woocommerce'),
        'type'     => 'textarea',
        'class'    => array('form-row-wide row-textarea'),
        'priority' => 28,
        'required' => false,
    );

    $fields['billing_city']['label'] = esc_html__('Город', 'woocommerce');

    return $fields;
});

/**
 * Inject additional shipping fields into checkout form
 */
add_filter('woocommerce_shipping_fields', function ($fields) {
    $fields['shipping_phone'] = array(
        'label'    => esc_html__('Phone', 'woocommerce'),
        'type'     => 'tel',
        'class'    => array('form-row-wide'),
        'priority' => 22,
        'required' => true,
    );

    $fields['shipping_email'] = array(
        'label'    => esc_html__('Email', 'woocommerce'),
        'type'     => 'email',
        'class'    => array('form-row-wide'),
        'priority' => 23,
        'required' => true,
    );

    $fields['shipping_comments'] = array(
        'label'    => esc_html__('Примечание к заказу', 'woocommerce'),
        'type'     => 'textarea',
        'class'    => array('form-row-wide row-textarea'),
        'priority' => 28,
        'required' => false,
    );

    $fields['shipping_city']['label'] = esc_html__('Город', 'woocommerce');

    return $fields;
});

/**
 * Sort checkout form fields in custom order
 */
add_filter('woocommerce_checkout_fields', function ($fields) {

    # Billing
    $fields['billing']['billing_phone']['priority'] = 22;
    $fields['billing']['billing_email']['priority'] = 23;
    $fields['billing']['billing_address_1']['priority'] = 26;
    $fields['billing']['billing_comments']['priority'] = 120;
    $fields['billing']['billing_last_name']['required'] = false;

    # Shipping
    $fields['shipping']['shipping_address_1']['priority'] = 26;
    $fields['shipping']['shipping_comments']['priority'] = 120;
    $fields['shipping']['shipping_last_name']['required'] = false;

    return $fields;
});

add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10, 3);
function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email)
{
    global $woocommerce;
    extract($_POST);
    if (strcmp($password, $password2) !== 0) {
        return new WP_Error('registration-error', __('Passwords do not match.', 'woocommerce'));
    }
    return $reg_errors;
}

add_action('woocommerce_register_form', 'wc_register_form_password_repeat');
function wc_register_form_password_repeat()
{
    ?>
  <p class="form-row form-row-wide confirm_password">
    <label for="reg_password2"><?php _e('Подтвердите пароль', 'woocommerce'); ?> <span
          class="required">*</span></label>
    <input type="password" class="input-text" name="password2" id="reg_password2"
           value="<?php if (!empty($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" required/>
  </p>
    <?php
}

function optovicom_wc_register_form_shortcode($atts, $content)
{
    $output = '';

    $output .= '<div class="woocommerce">';

    ob_start();
    get_template_part('woocommerce/myaccount/form-register');

    $output .= ob_get_clean();
    $output .= '</div>';


    return $output;
}

add_shortcode('woocommerce_register_form', 'optovicom_wc_register_form_shortcode');

function optovicom_load_more_ajax_callback()
{
    $args = json_decode(stripslashes($_POST['query']), true);
    $args['paged'] = $_POST['page'] + 1;
    $args['post_status'] = 'publish';
    query_posts($args);

    if (have_posts()) :
        while (have_posts()): the_post();
            get_template_part('woocommerce/content-product');
        endwhile;
    endif;
    die;
}

add_action('wp_ajax_optovicom_load_more_ajax_callback', 'optovicom_load_more_ajax_callback');
add_action('wp_ajax_nopriv_optovicom_load_more_ajax_callback', 'optovicom_load_more_ajax_callback');

add_filter('woocommerce_save_account_details_required_fields', 'optovicom_unset_account_required_fields');
function optovicom_unset_account_required_fields($required_fields)
{
    unset($required_fields['account_display_name']);
    return $required_fields;
}


function optovicom_get_edit_user_id()
{
    return isset($_GET['user_id']) ? (int)$_GET['user_id'] : get_current_user_id();
}

function optovicom_get_userdata($user_id, $key)
{
    if (!optovicom_is_userdata($key)) {
        return get_user_meta($user_id, $key, true);
    }

    $userdata = get_userdata($user_id);

    if (!$userdata || !isset($userdata->{$key})) {
        return '';
    }

    return $userdata->{$key};
}

function optovicom_get_account_fields()
{
    $months_array = ['Январь', 'Ферваль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', ' Октябрь', 'Ноябрь', 'Декабрь'];
    $current_year = date('Y');

    $select_days = new stdClass();
    $select_months = new stdClass();
    $select_years = new stdClass();


    for ($i = 1; $i < 32; $i++) {
        $select_days->$i = $i;
    }

    for ($i = 1; $i <= 12; $i++) {
        $select_months->$i = $months_array[$i - 1];
    }

    for ($i = $current_year; $i > ($current_year - 60); $i--) {
        $select_years->$i = $i;
    }


    $fields = array(
        'user-birth-day'   => array(
            'type'    => 'select',
            'class'   => array('form-row', 'form-row-composite'),
            'label'   => __('День рождения', 'woocommerce'),
            'options' => $select_days
        ),
        'user-birth-month' => array(
            'type'    => 'select',
            'class'   => array('form-row', 'form-row-composite'),
            'options' => $select_months
        ),
        'user-birth-year'  => array(
            'type'    => 'select',
            'class'   => array('form-row', 'form-row-composite'),
            'options' => $select_years
        ),
    );

    return apply_filters('optovicom_account_fields', $fields);
}

function optovicom_is_userdata($key)
{
    $userdata = array(
        'user_pass',
        'user_login',
        'user_nicename',
        'user_url',
        'user_email',
        'display_name',
        'nickname',
        'first_name',
        'last_name',
        'description',
        'rich_editing',
        'user_registered',
        'role',
        'jabber',
        'aim',
        'yim',
        'show_admin_bar_front',
    );

    return in_array($key, $userdata);
}

function optovicom_is_field_visible($field_args)
{
    $visible = true;
    $action = filter_input(INPUT_POST, 'action');

    if (is_admin() && !empty($field_args['hide_in_admin'])) {
        $visible = false;

    } elseif ((is_account_page() || $action === 'save_account_details') && is_user_logged_in() && !empty($field_args['hide_in_account'])) {
        $visible = false;

    } elseif ((is_account_page() || $action === 'save_account_details') && !is_user_logged_in() && !empty($field_args['hide_in_registration'])) {
        $visible = false;

    } elseif (is_checkout() && !empty($field_args['hide_in_checkout'])) {
        $visible = false;
    }

    return $visible;
}

function optovicom_save_account_fields($customer_id)
{
    $fields = optovicom_get_account_fields();
    $sanitized_data = array();

    foreach ($fields as $key => $field_args) {
        if (!optovicom_is_field_visible($field_args)) {
            continue;
        }

        $sanitize = isset($field_args['sanitize']) ? $field_args['sanitize'] : 'wc_clean';
        $value = isset($_POST[$key]) ? call_user_func($sanitize, $_POST[$key]) : '';

        if (optovicom_is_userdata($key)) {
            $sanitized_data[$key] = $value;
            continue;
        }

        update_user_meta($customer_id, $key, $value);
    }

    if (!empty($sanitized_data)) {
        $sanitized_data['ID'] = $customer_id;
        wp_update_user($sanitized_data);
    }
}

add_action('woocommerce_created_customer', 'optovicom_save_account_fields'); # register/checkout
add_action('personal_options_update', 'optovicom_save_account_fields'); # edit own account admin
add_action('edit_user_profile_update', 'optovicom_save_account_fields'); # edit other account admin
add_action('woocommerce_save_account_details', 'optovicom_save_account_fields'); # edit WC account

function optovicom_print_user_frontend_fields()
{
    $fields = optovicom_get_account_fields();
    $is_user_logged_in = is_user_logged_in();

    foreach ($fields as $key => $field_args) {
        $value = null;

        if ($is_user_logged_in && !empty($field_args['hide_in_account'])) {
            continue;
        }

        if (!$is_user_logged_in && !empty($field_args['hide_in_registration'])) {
            continue;
        }

        if ($is_user_logged_in) {
            $user_id = optovicom_get_edit_user_id();
            $value = optovicom_get_userdata($user_id, $key);
        }

        $value = isset($field_args['value']) ? $field_args['value'] : $value;

        woocommerce_form_field($key, $field_args, $value);
    }
}

add_action('woocommerce_edit_account_form', 'optovicom_print_user_frontend_fields', 10); # my account

function optovicom_get_account_orders_columns()
{
    $columns = apply_filters(
        'optovicom_get_account_orders_columns', array(
            'order-number' => __('Products', 'woocommerce'),
            'order-date'   => __('Date', 'woocommerce'),
        )
    );

    return apply_filters('woocommerce_my_account_my_orders_columns', $columns);
}

/**
 * Include only 'Product' post type into WP search
 */
add_filter('pre_get_posts', function ($query) {
    if ($query->is_search) {
        $query->set('post_type', 'product');
    }

    return $query;
});

function optovicom_instagram_feed($acc_name)
{
    require __DIR__ . '/vendor/autoload.php';
    $instagram = new \InstagramScraper\Instagram();
    $i = 0;
    $o = '';
    $search_results = $instagram->searchAccountsByUsername($acc_name);

    $o .= '<div class="section-instagram">';

    foreach ($search_results as $account) {
        $i++;

        if ($account['username'] == $acc_name) {
            $i = 0;
            $posts = $instagram->getMedias($acc_name, 6);

            $o .= '<h2 class="section__title">Мы в Instagram</h2>';

            if ($posts && count($posts) > 0) {
                $o .= '<div class="instagram-products">';

                foreach ($posts as $post) {
                    $o .= '<a id="inst-product-' . $post->getId() . '" class="inst-product" href="' . $post->getLink() . '" target="_blank">';
                    $o .= '<img src="' . $post->getImageHighResolutionUrl() . '" alt="">';
                    $o .= '</a>';
                }

                $o .= '</div>';
            }

            break;

        } elseif ($i == count($search_results)) {
            $o .= '<h3 class="section__title">Пользователя с таким никнеймом не существует</h3>';
        }
    }

    $o .= '</div>';

    return $o;
}

function filter_post_link($permalink, $post)
{
    if ($post->post_type != 'post')
        return $permalink;
    return 'blog' . $permalink;
}

add_filter('pre_post_link', 'filter_post_link', 10, 2);

/**
 * Prepend default wordpress post URLs with blog/
 */
add_action('generate_rewrite_rules', function ($wp_rewrite) {
    $wp_rewrite->rules = array(
            'blog/([^/]+)/?$'                                                => 'index.php?name=$matches[1]',
            'blog/[^/]+/attachment/([^/]+)/?$'                               => 'index.php?attachment=$matches[1]',
            'blog/[^/]+/attachment/([^/]+)/trackback/?$'                     => 'index.php?attachment=$matches[1]&tb=1',
            'blog/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
            'blog/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$'      => 'index.php?attachment=$matches[1]&feed=$matches[2]',
            'blog/[^/]+/attachment/([^/]+)/comment-pagea-([0-9]{1,})/?$'     => 'index.php?attachment=$matches[1]&cpage=$matches[2]',
            'blog/([^/]+)/trackback/?$'                                      => 'index.php?name=$matches[1]&tb=1',
            'blog/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$'                  => 'index.php?name=$matches[1]&feed=$matches[2]',
            'blog/([^/]+)/(feed|rdf|rss|rss2|atom)/?$'                       => 'index.php?name=$matches[1]&feed=$matches[2]',
            'blog/([^/]+)/page/?([0-9]{1,})/?$'                              => 'index.php?name=$matches[1]&paged=$matches[2]',
            'blog/([^/]+)/comment-page-([0-9]{1,})/?$'                       => 'index.php?name=$matches[1]&cpage=$matches[2]',
            'blog/([^/]+)(/[0-9]+)?/?$'                                      => 'index.php?name=$matches[1]&page=$matches[2]',
            'blog/[^/]+/([^/]+)/?$'                                          => 'index.php?attachment=$matches[1]',
            'blog/[^/]+/([^/]+)/trackback/?$'                                => 'index.php?attachment=$matches[1]&tb=1',
            'blog/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$'            => 'index.php?attachment=$matches[1]&feed=$matches[2]',
            'blog/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$'                 => 'index.php?attachment=$matches[1]&feed=$matches[2]',
            'blog/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$'                 => 'index.php?attachment=$matches[1]&cpage=$matches[2]',
        ) + $wp_rewrite->rules;
});

function optovicom_product_category_base_same_shop_base($flash = false)
{
    $terms = get_terms(array(
        'taxonomy'   => 'product_cat',
        'post_type'  => 'product',
        'hide_empty' => false,
    ));
    if ($terms && !is_wp_error($terms)) {
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term) {
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'product_cat'));
            add_rewrite_rule($baseterm . '?$', 'index.php?product_cat=' . $term_slug, 'top');
            add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]', 'top');
            add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]', 'top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}

add_filter('init', 'optovicom_product_category_base_same_shop_base');
add_action('create_term', 'optovicom_product_cat_same_shop_edit_success', 10, 2);
function optovicom_product_cat_same_shop_edit_success($term_id, $taxonomy)
{
    optovicom_product_category_base_same_shop_base(true);
}

add_filter('wpseo_prev_rel_link', '__return_empty_string');
add_filter('wpseo_next_rel_link', '__return_empty_string');
remove_action('woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30);

/* Remove product meta */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

/* Remove order details from order view */
remove_action('woocommerce_view_order', 'woocommerce_order_details_table', 10);
