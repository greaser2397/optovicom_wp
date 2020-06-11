<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart');

$cart_label = optovicom_cart_count_with_text() != null ? ', ' . optovicom_cart_count_with_text() : '' ?>

<div class="woocommerce-mini-cart-wrapper">

  <div class="woocommerce-mini-cart-header">
    <span class="border"></span>
    <strong class="cart-label">
        <?= esc_html__('Cart', 'woocommerce') . $cart_label; ?>
    </strong>
    <button class="btn-close-cart"></button>
  </div>

    <?php if (!WC()->cart->is_empty()) : ?>

      <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
          <?php
          do_action('woocommerce_before_mini_cart_contents');

          foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

              if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                  $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                  $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                  $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                  $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                  ?>
                <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
                    <?php
                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                        '<a href="%s" class="remove remove_from_cart_button trash" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                        __('Remove this item', 'woocommerce'),
                        esc_attr($product_id),
                        esc_attr($cart_item_key),
                        esc_attr($_product->get_sku())
                    ), $cart_item_key);
                    ?>
                    <?php if (empty($product_permalink)) : ?>
                        <?php echo $thumbnail; ?>
                    <?php else : ?>
                      <a class="item__thumbnail-wrapper" href="<?php echo esc_url($product_permalink); ?>">
                          <?php echo $thumbnail; ?>
                      </a>
                    <?php endif; ?>
                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>

                  <div class="item-info-wrapper">
                    <span class="price"><?= $_product->get_price_html(); ?></span>
                    <a href="<?= esc_url($product_permalink); ?>" class="item__title"><?= $product_name; ?></a>
                    <span
                        class="quantity"><?= esc_html__(get_field('cart_quantity_text', 'option'), 'woocommerce') . ' ' . $cart_item['quantity']; ?></span>
                  </div>
                </li>
                  <?php
              }
          }

          do_action('woocommerce_mini_cart_contents');
          ?>
      </ul>

      <div class="woocommerce-mini-cart-footer mini-cart-footer">
        <p class="total__label"><?php _e('Сумма', 'woocommerce'); ?></p>
        <p class="woocommerce-mini-cart__total total"><?php echo WC()->cart->get_cart_subtotal(); ?></p>
      </div>

        <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

      <div class="woocommerce-mini-cart__buttons buttons">
          <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
      </div>

    <?php else : ?>

      <p class="woocommerce-mini-cart__empty-message empty-cart"><?php _e('No products in the cart.', 'woocommerce'); ?></p>

    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_mini_cart'); ?>
