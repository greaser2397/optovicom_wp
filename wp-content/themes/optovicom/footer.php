<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

$logo = get_header_image() ? '<img src="' . get_header_image() . '" class="footer__logo" alt="' . get_bloginfo('name') . '">' : get_bloginfo('name');
$content = get_field('footer_content', 'option');
$col1_title = get_field('footer_menu_column_title', 'option');
$col2_title = get_field('footer_social_column_title', 'option'); ?>

</div><!-- #content -->

<footer id="footer-container" class="footer site-footer" role="contentinfo">
    <div class="footer-inner container">
        <div class="footer-top">
            <?php if ($logo || $content) : ?>
                <div class="column column-content">
                    <a href="<?= home_url('/'); ?>" class="footer__logo-wrapper"><?= $logo; ?></a>
                    <?php if ($content) : ?>
                        <div class="footer-content"><?= $content; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="column column-menu">
                <div class="golden-footer-title">
                    Телефон:
                </div>
                <div class="golden-footer-text">
                    +380731426969
                </div>
                <div class="golden-footer-title">
                    Почта:
                </div>
                <div class="golden-footer-text">
                    info@optovicom.com
                </div>
                <div class="golden-footer-title">
                    Адрес:
                </div>
                <div class="golden-footer-text">
                    Украина, Одесская область, Одесса, Промрынок 7
                </div>
            </div>



            <?php if ($col2_title || have_rows('footer_social', 'option')) : ?>
                <div class="column column-social">
                    <?php if ($col2_title) : ?>
                        <p class="column__title"><?= $col2_title; ?></p>
                    <?php endif; ?>

                    <?php if (have_rows('footer_social', 'option')) : ?>
                        <div class="footer-social-wrapper">
                            <ul class="footer-social">
                                <?php while (have_rows('footer_social', 'option')) : the_row();
                                    $icon = get_sub_field('fa_icon');
                                    $link = get_sub_field('link'); ?>
                                    <?php if ($link === 'tel:+380931426969') :?>
                                    <?php continue; ?>
                                    <?php endif; ?>
                                    <li class="social__item">
                                        <a rel="nofollow" href="<?= $link; ?>" target="_blank"><?= $icon; ?></a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="footer-bottom">
            <p class="copyright">
                &copy; <?= get_bloginfo('name') . ' ' . date('Y') . ' все права защищены.'; ?></p>
            <?php if (has_nav_menu('footer_menu')) : ?>
                <nav class="footer-nav footer-menu-wrapper">
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_menu',
                        'menu_id' => 'footer-sub-menu',
                        'menu_class' => 'footer-sub-menu',
                        'container' => false,
                        'walker' => new optovicom_navwalker()]); ?>
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
<script type="text/javascript" src="//optovicom.com/wp-content/themes/optovicom/assets/scripts/golden-footer.js"></script>
</body>
</html>
