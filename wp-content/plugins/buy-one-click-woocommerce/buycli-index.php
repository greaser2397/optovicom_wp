<?php

/*
 *  Plugin Name: Buy one click WooCommerce
 *  Plugin URI: http://zixn.ru/plagin-zakazat-v-odin-klik-dlya-woocommerce.html
 *  Description: Buy in one click for WooCommerce. The best plugin that adds to your online store purchase button in one click
 *  Version: 1.9
 *  Author: Djo
 *  Author URI: http://zixn.ru
 * Text Domain: coderun-oneclickwoo
 * Domain Path: /languages
 */

/*  Copyright 2019  Djo  (email: izm@zixn.ru)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

__('Buy one click WooCommerce');
__('Buy in one click for WooCommerce. The best plugin that adds to your online store purchase button in one click');

if (!defined('ABSPATH')) {
    exit;
}
add_action('wp_loaded', 'buy_plugin_init_core', 100);

/**
 * Инициализация всего плагина
 */
function buy_plugin_init_core() {
    
     load_plugin_textdomain(
            'coderun-oneclickwoo',false,dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
    
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/core-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/hook-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/function-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/javascript-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/smsc-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/shortcode-class.php');
    if (file_exists(WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/variation-class.php')) {
        require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/variation-class.php');
    }
//require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/lib/smtp.php');


    if (class_exists('BuyCore')) {
        $bcore = new BuyCore();
        $bjava = new BuyJavaScript();
        $bshort = new BuyShortcode();
//    $bsmsc = new BuySMSC();
        register_deactivation_hook(__FILE__, array($bcore, 'deactivationPlugin'));
    }
    if (class_exists('BuyHookPlugin')) {
        BuyHookPlugin::load();
    }
}
