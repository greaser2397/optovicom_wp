<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

$footer = [
  'logo'    => get_header_image() ? '<img src="' . get_header_image() . '" class="footer__logo" alt="' . get_bloginfo('name') . '">' : get_bloginfo('name'),
  'content' => get_field('footer_content', 'option'),
  'titles'  => [
    'menu'   => get_field('footer_menu_column_title', 'option'),
    'social' => get_field('footer_social_column_title', 'option'),
  ],
  'menu'    => get_field('footer_menu', 'option'),
  'social'  => get_field('footer_social', 'option')
]; ?>

</div><!-- #content -->

<footer id="footer-container" class="footer site-footer" role="contentinfo">
  <div class="footer-inner container">
    <div class="footer-top">

      <?php if ($footer['logo'] || $footer['content']) : ?>
        <div class="column column-content">
          <a href="<?= home_url('/'); ?>" class="footer__logo-wrapper"><?= $footer['logo']; ?></a>

          <?php if ($footer['content']) : ?>
            <div class="footer-content"><?= $footer['content']; ?></div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

      <div class="column column-menu">

        <?php foreach ($footer['menu'] as $menu_item) : ?>
          <p class="title"><?= $menu_item['title']; ?></p>
          <p class="text"><?= $menu_item['text']; ?></p>
        <?php endforeach; ?>

      </div>

      <?php if ($footer['titles']['social'] || !empty($footer['social'])) : ?>
        <div class="column column-social">

          <?php if ($footer['titles']['social']) : ?>
            <p class="column__title"><?= $footer['titles']['social']; ?></p>
          <?php endif; ?>

          <?php if (!empty($footer['social'])) : ?>
            <div class="footer-social-wrapper">
              <ul class="footer-social">

                <?php foreach ($footer['social'] as $s_item) : ?>

                  <?php if ($s_item['link'] === 'tel:+380931426969') {
                    continue;
                  } ?>

                  <li class="social__item">
                    <a rel="nofollow" href="<?= $s_item['link']; ?>" target="_blank"><?= $s_item['fa_icon']; ?></a>
                  </li>

                <?php endforeach; ?>

              </ul>
            </div>
          <?php endif; ?>

        </div>
      <?php endif; ?>

    </div>
    <div class="footer-bottom">
      <p class="copyright">
        &copy; <?= get_bloginfo('name') . ' ' . date('Y') . ' ' . __('все права защищены.', THEME_TD); ?></p>

      <?php if (has_nav_menu('footer_menu')) : ?>
        <nav class="footer-nav footer-menu-wrapper">
          <?php wp_nav_menu([
            'theme_location' => 'footer_menu',
            'menu_id'        => 'footer-sub-menu',
            'menu_class'     => 'footer-sub-menu',
            'container'      => false,
            'walker'         => new optovicom_navwalker()
          ]); ?>
        </nav>
      <?php endif; ?>

    </div>
  </div>
</footer>
<?php wp_footer(); ?>

<div class="viber-social">
  <a class="viber-pc" href="viber://chat?number=+380931426969"></a>
  <a class="viber-phone" href="viber://add?number=380931426969"></a>
</div>
<script src="<?= get_template_directory_uri() ?>/assets/scripts/golden-footer.js"></script>
</body>
</html>
