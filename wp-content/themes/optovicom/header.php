<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

$g_tag = [
  'head' => get_field('g_tag_head', 'option'),
  'body' => get_field('g_tag_body', 'option'),
]; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <?= $g_tag['head']; ?>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta class="site-url" data-src="<?= home_url(); ?>">
  <meta class="tpl-url" data-src="<?= get_template_directory_uri(); ?>">
  <meta class="ajax-url" data-src="<?= admin_url('admin-ajax.php'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?= $g_tag['body']; ?>
<?php
# Load theme preloader chunk
get_template_part('template-parts/content', 'preloader');

$header_logo = get_header_image() ?
  '<img src="' . get_header_image() . '" alt="' . get_bloginfo('name') . '"/>' : get_bloginfo('name');
$phones = get_field('header_phone', 'option');
$header = [
  'logo'   => get_header_image()
    ? '<img src="' . get_header_image() . '" alt="' . get_bloginfo('name') . '"/>' : get_bloginfo('name'),
  'social' => get_field('header_social', 'options'),
  'phones' => get_field('header_phone', 'options')
]; ?>

<header class="header">
  <div class="header-top">
    <div class="header-top-inner container">
      <button class="panel__toggle"><i class="fas fa-chevron-down"></i></button>

      <?php if (!empty($header['social'])) : ?>
        <ul class="header-social">
          <?php foreach ($header['social'] as $s_item) : ?>

            <?php if ($s_item['link'] === 'tel:+380931426969') {
              continue;
            } ?>

            <li class="social__item">
              <a rel="nofollow" href="<?= $s_item['link']; ?>" target="_blank"><?= $s_item['fa_icon']; ?></a>
            </li>

          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if (has_nav_menu('primary')) : ?>
        <div class="header-menu-wrapper">
          <?php wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'header-menu menu',
            'container'      => false,
            'walker'         => new optovicom_navwalker()
          ]); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($header['phones'])) : ?>
        <select name="phones" id="phones" class="header-phones" title="<?= __('Телефоны', THEME_TD); ?>"
                onchange="window.location.href = 'tel:' + this.value">

          <?php foreach ($header['phones'] as $phone) : ?>
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
      <a href="<?= esc_url(home_url('/')); ?>" class="header-logo"><?= $header['logo']; ?></a>
    </div>

    <?php get_template_part('template-parts/header/panel'); ?>
  </div>

  <?php get_template_part('template-parts/header/product-cats'); ?>

</header>
<div id="content" class="site-content">
