<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php the_field('g_tag_head', 'option'); ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta class="site-url" data-src="<?= home_url(); ?>">
    <meta class="tpl-url" data-src="<?= get_template_directory_uri(); ?>">
    <meta class="ajax-url" data-src="<?= admin_url('admin-ajax.php'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
the_field('g_tag_body', 'option');
get_template_part('template-parts/content', 'preloader');
$header_logo = get_header_image() ?
    '<img src="' . get_header_image() . '" alt="' . get_bloginfo('name') . '"/>' : get_bloginfo('name');
$phones = get_field('header_phone', 'option'); ?>

<header class="header">
    <div class="header-top">
        <div class="header-top-inner container">
            <button class="panel__toggle"><i class="fas fa-chevron-down"></i></button>
            <?php if (have_rows('header_social', 'option')) : ?>
                <ul class="header-social">
                    <?php while (have_rows('header_social', 'option')) : the_row();
                        $link = get_sub_field('link');
                        $icon = get_sub_field('fa_icon'); ?>
                        <?php if ($link === 'tel:+380931426969') :?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <li class="social__item">
                            <a rel="nofollow" href="<?= $link; ?>" target="_blank"><?= $icon; ?></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

            <?php if (has_nav_menu('primary')) : ?>
                <div class="header-menu-wrapper">
                    <?php wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'menu_class' => 'header-menu menu',
                        'container' => false,
                        'walker' => new optovicom_navwalker()]); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($phones)) : ?>
                <select name="phones" id="phones" onchange="window.location.href = 'tel:' + this.value"
                        class="header-phones" title="<?= __('Телефоны', THEME_TD); ?>">
                    <?php foreach ($phones as $phone) : ?>
                        <option value="<?= $phone['number']; ?>"><?= $phone['number']; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    </div>
    <div class="header-bottom container">

        <div class="header-search">
            <?php get_search_form(); ?>
        </div>

        <div class="header-logo-wrapper">
            <a href="<?= esc_url(home_url('/')); ?>" class="header-logo"><?= $header_logo; ?></a>
        </div>

        <div class="header-panel">
            <?php
            $cart_icon = '<i class="fas fa-shopping-cart"></i>';
            $user_icon = is_user_logged_in() ? '<i class="fas fa-user"></i>' : '<i class="fas fa-user-ninja"></i>';
            $title = is_user_logged_in() ? 'My Account' : 'Login / Register'; ?>

            <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) : ?>
                <?php $count = WC()->cart->get_cart_contents_count(); ?>
                <div class="cart-wrapper">
                    <button class="cart-contents" title="<?php _e('View your shopping cart'); ?>">
                        <?= $cart_icon; ?>
                        <span class="cart-contents-count"><?= esc_html($count); ?></span>
                    </button>
                    <div class="mini-cart-container">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="account-menu">
                <button title="<?php _e($title, THEME_TD); ?>" class="header__account"><?= $user_icon; ?></button>
                <div class="account-menu-dropdown-wrapper">
                    <span class="border"></span>
                    <ul class="account-menu-dropdown">
                        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                            <?php if ($endpoint !== 'customer-logout') : ?>
                                <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                                    <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                                </li>
                            <?php else: ?>
                                <?php if (is_user_logged_in()) : ?>
                                    <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                                            <strong class="welcome-user"><?= esc_html__('Привет', 'woocommerce') . ', ' . wp_get_current_user()->display_name; ?></strong>
                                            <span><?= esc_html($label) ?></span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                                        <a href="<?= get_the_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><?= esc_html__('Log in', 'woocommerce'); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn-close-account"></button>
                </div>
            </div>
        </div>

    </div>
    <div class="header-categories container">
        <?php
        $prod_cats = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => 0,
        ));
        if (!empty($prod_cats)) : ?>
            <ul class="prod-categories">
                <?php foreach ($prod_cats as $cat) :
                    $su_menu_class = !empty(get_term_children($cat->term_id, 'product_cat')) ? ' term-has-children' : '';
                    $class = get_queried_object()->term_id == $cat->term_id ? ' current-category' : '';
                    $p_class = get_queried_object()->parent == $cat->term_id ? ' current-category-parent' : ''; ?>
                    <li class="prod-category category-<?= $cat->term_id . $class . $p_class . $su_menu_class; ?>">
                        <a href="<?= get_term_link($cat); ?>"><?= $cat->name; ?></a>
                        <?php $child_cats = get_terms(array('taxonomy' => 'product_cat', 'parent' => $cat->term_id));
                        if (!empty($child_cats)) : ?>
                            <span class="expand-chevron"></span>
                            <ul class="sub-categories">
                                <?php foreach ($child_cats as $child_cat) :
                                    $child_class = get_queried_object()->term_id == $child_cat->term_id ? ' current-category' : ''; ?>
                                    <li class="prod-category category-<?= $child_cat->term_id . $child_class; ?>">
                                        <a href="<?= get_term_link($child_cat); ?>"><?= $child_cat->name; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</header>
<div id="content" class="site-content">
