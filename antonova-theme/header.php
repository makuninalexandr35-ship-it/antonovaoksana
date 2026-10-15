<?php
if (!defined('ABSPATH')) {
    exit;
}

$antonova_is_front_page = is_front_page();
$antonova_home = home_url('/');
$antonova_section = static function ($fragment) use ($antonova_is_front_page, $antonova_home) {
    return $antonova_is_front_page ? '#' . $fragment : $antonova_home . '#' . $fragment;
};
$antonova_category_pages = array(
    'birthday-cakes' => array('label' => 'Торты на день рождения', 'url' => '/birthday-cakes/'),
    'wedding-cakes' => array('label' => 'Свадебные торты', 'url' => '/wedding-cakes/'),
    'handmade-chocolate' => array('label' => 'Шоколад ручной работы', 'url' => '/handmade-chocolate/'),
);
$antonova_current_category = null;
foreach (array_keys($antonova_category_pages) as $antonova_category_slug) {
    if (is_page($antonova_category_slug)) {
        $antonova_current_category = $antonova_category_slug;
        break;
    }
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="theme-color" content="#3b101d">
  <script>document.documentElement.classList.add('js');</script>
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/favicon.svg">
  <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.ico" sizes="16x16 32x32 48x48">
  <link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/favicon-48.png" sizes="48x48">
  <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/apple-touch-icon.png" sizes="180x180">
  <noscript><style>.reveal{opacity:1;transform:none}</style></noscript>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <header class="site-header<?php echo $antonova_is_front_page ? '' : ' site-header-inner'; ?>" aria-label="Основная навигация">
    <a class="brand" href="<?php echo esc_url($antonova_home); ?>" aria-label="На главную"><span class="brand-logo-wrap"><img class="brand-logo brand-logo-header" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-360.webp 360w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp 720w" sizes="(max-width: 700px) 135px, 210px" width="720" height="240" decoding="async" alt="Oksana Antonova — Art of Desserts"></span></a>
    <nav id="site-navigation" class="site-navigation" aria-label="Разделы сайта">
      <details class="nav-categories">
        <summary>Категории <span aria-hidden="true">⌄</span></summary>
        <div class="nav-categories-menu">
          <?php if (null !== $antonova_current_category) : ?>
            <a href="<?php echo esc_url($antonova_home); ?>">Главная</a>
          <?php endif; ?>
          <?php foreach ($antonova_category_pages as $antonova_category_slug => $antonova_category_page) : ?>
            <?php if ($antonova_category_slug === $antonova_current_category) { continue; } ?>
            <a href="<?php echo esc_url(home_url($antonova_category_page['url'])); ?>"><?php echo esc_html($antonova_category_page['label']); ?></a>
          <?php endforeach; ?>
        </div>
      </details>
      <a href="<?php echo esc_url($antonova_section('products')); ?>">Продукция</a><a href="<?php echo esc_url($antonova_section('flavors')); ?>">Начинки</a><a href="<?php echo esc_url($antonova_section('works')); ?>">Работы</a><a href="<?php echo esc_url($antonova_section('price')); ?>">Цены</a><a href="<?php echo esc_url($antonova_section('order')); ?>">Как заказать</a><a href="<?php echo esc_url($antonova_section('about')); ?>">О себе</a><a href="<?php echo esc_url($antonova_section('faq')); ?>">FAQ</a>
    </nav>
    <button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false" aria-label="Открыть меню"><span></span><span></span><span></span></button>
    <a class="button button-header" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать в MAX</a>
  </header>
