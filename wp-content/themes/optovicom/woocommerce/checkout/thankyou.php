<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

    <?php if ($order) : ?>

        <?php if ($order->has_status('failed')) : ?>

        <div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
          <p><?php _e('Уууупс, что-то пошло не так.' . '</br>' . 'Оплата не прошла, попробуйте еще раз...', 'woocommerce'); ?></p>
        </div>

        <div
            class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions link-wrapper">
          <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
             class="button cart"><?php _e('Вернуться в корзину', 'woocommerce'); ?></a>
        </div>

        <?php else : ?>

        <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
          <p><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Спасибо за заказ!' . '</br>' . 'С Вами приятно иметь дело :)', 'woocommerce'), $order); ?></p>
        </div>

        <div
            class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received-actions link-wrapper">
          <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"
             class="button cart"><?php _e('Вернуться в магазин', 'woocommerce'); ?></a>
        </div>

        <?php endif; ?>

    <?php else : ?>

      <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
        <p><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Спасибо за заказ!' . '</br>' . 'С Вами приятно иметь дело :)', 'woocommerce'), null); ?></p>
      </div>

      <div
          class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received-actions link-wrapper">
        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"
           class="button cart"><?php _e('Вернуться в магазин', 'woocommerce'); ?></a>
      </div>

    <?php endif; ?>

</div>
