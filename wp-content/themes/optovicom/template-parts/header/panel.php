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
                  <strong
                      class="welcome-user"><?= esc_html__('Привет', 'woocommerce') . ', ' . wp_get_current_user()->display_name; ?></strong>
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
