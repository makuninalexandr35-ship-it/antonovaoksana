<?php
if (!defined('ABSPATH')) {
    exit;
}

$antonova_is_front_page = is_front_page();
$antonova_home = home_url('/');
$antonova_section = static function ($fragment) use ($antonova_is_front_page, $antonova_home) {
    return $antonova_is_front_page ? '#' . $fragment : $antonova_home . '#' . $fragment;
};
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="theme-color" content="#3b101d">
  <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.ico" sizes="16x16 32x32 48x48">
  <link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/favicon-48.png" sizes="48x48">
  <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/apple-touch-icon.png" sizes="180x180">
  <noscript><style>.reveal{opacity:1;transform:none}</style></noscript>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <header class="site-header<?php echo $antonova_is_front_page ? '' : ' site-header-inner'; ?>" aria-label="Основная навигация">
    <a class="brand" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/Sertifikat.jpg" target="_blank" rel="noopener" aria-label="Открыть сертификат"><span class="brand-logo-wrap"><img class="brand-logo brand-logo-header" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-360.webp 360w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp 720w" sizes="(max-width: 700px) 135px, 210px" width="720" height="240" decoding="async" alt="Oksana Antonova — Art of Desserts"></span></a>
    <nav aria-label="Разделы сайта">
      <a href="<?php echo esc_url($antonova_section('products')); ?>">Продукция</a><a href="<?php echo esc_url($antonova_section('flavors')); ?>">Начинки</a><a href="<?php echo esc_url($antonova_section('works')); ?>">Работы</a><a href="<?php echo esc_url($antonova_section('price')); ?>">Цены</a><a href="<?php echo esc_url($antonova_section('order')); ?>">Как заказать</a><a href="<?php echo esc_url($antonova_section('faq')); ?>">FAQ</a>
    </nav>
    <a class="button button-header" href="<?php echo esc_url(antonova_content('antonova_whatsapp_url', 'https://wa.me/79647281844')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Заказать в WhatsApp</a>
  </header>
