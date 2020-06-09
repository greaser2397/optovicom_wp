<?php

namespace Coderun\BuyOneClick;

if (!defined('ABSPATH')) {
    exit;
}

class Order {

    protected static $_instance = null;

    /**
     * Singletone
     * @return Order
     */
    public static function getInstance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Создаёт заказ в WooCommerce
     * @param array $params массив параметров аналогичный $default_params
     */
    public function set_order($params) {

        $default_params = array(
            'first_name' => '',
            'last_name' => '',
            'company' => '',
            'email' => '',
            'phone' => '',
            'address_1' => '',
            'address_2' => '',
            'city' => '',
            'state' => '',
            'postcode' => '',
            'country' => '',
            'order_status' => 'processing', //Статус заказа который будет установлен
            'message_notes_order' => __('Quick order form', 'coderun-oneclickwoo'), //Сообщение в заказе
            'qty' => 1,
            'product_id' => 0, //ИД товара Woo или ИД вариации
        );

        $params = wp_parse_args($params, $default_params);

        $product = wc_get_product($params['product_id']);

        $order = wc_create_order(); //создаём новый заказ

        $product_params = array(
            'name' => $product->get_name(),
            'tax_class' => $product->get_tax_class(),
            'product_id' => $product->is_type('variation') ? $product->get_parent_id() : $product->get_id(),
            'variation_id' => $params['product_id'],
            'variation' => $product->is_type('variation') ? $product->get_attributes() : array(),
            'subtotal' => wc_get_price_excluding_tax($product, array('qty' => $params['qty'])),
            'total' => wc_get_price_excluding_tax($product, array('qty' => $params['qty'])),
            'quantity' => $params['qty'],
        );


        $order->add_product($product, $params['qty'], $product_params);

        $order->set_billing_first_name($params['first_name']);
        $order->set_billing_last_name($params['last_name']);
        $order->set_billing_company($params['company']);
        $order->set_billing_email($params['email']);
        $order->set_billing_phone($params['phone']);
        $order->set_billing_address_1($params['address_1']);
        $order->set_billing_address_2($params['address_2']);
        $order->set_billing_city($params['city']);
        $order->set_billing_state($params['state']);
        $order->set_billing_postcode($params['postcode']);
        $order->set_billing_country($params['country']);

        $order->set_shipping_first_name($params['first_name']);
        $order->set_shipping_last_name($params['last_name']);
        $order->set_shipping_company($params['company']);
        $order->set_shipping_address_1($params['address_1']);
        $order->set_shipping_address_2($params['address_2']);
        $order->set_shipping_city($params['city']);
        $order->set_shipping_state($params['state']);
        $order->set_shipping_postcode($params['postcode']);
        $order->set_shipping_country($params['country']);
        
        $order->set_customer_id(get_current_user_id());

        $order->update_status($params['order_status'], $params['message_notes_order']);
    
        $order->calculate_totals();

        return $order->get_id();
    }

    protected function __construct() {
        
    }

    public function __clone() {
        throw new \Exception('Forbiden instance __clone');
    }

    public function __wakeup() {
        throw new \Exception('Forbiden instance __wakeup');
    }

}
