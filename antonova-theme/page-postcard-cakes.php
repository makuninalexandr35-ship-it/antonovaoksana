<?php
/*
Template Name: Торты-открытки
Template Post Type: page
*/

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$price_cards = antonova_get_product_price_cards();
?>
<main>
  <section class="hero postcard-cakes-hero">
    <img class="hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero1.webp" width="1680" height="945" decoding="async" alt="Шоколадная плитка с цветочным декором в подарочной коробке" fetchpriority="high" loading="eager">
    <div class="hero-shade"></div>
    <div class="hero-content reveal">
      <h1 class="home-hero-title"><span>Торты и</span> <span>авторские десерты</span> <span>на заказ в Москве</span></h1>
      <p class="lead home-hero-lead"><span>Индивидуальные вкусы, оформление и внимание к каждой детали.</span><span>От идеи и референса до десерта, который станет частью вашего</span><span>праздника.</span></p>
      <div class="actions">
        <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать в MAX</a>
      </div>
      <ul class="hero-facts" aria-label="Краткая информация">
        <li><span class="fact-icon">♨</span><span><b>Торты</b><?php echo esc_html($price_cards[0]['price'] ?? 'от 3000 ₽/кг'); ?></span></li>
        <li><span class="fact-icon">◇</span><span><b>10 вариантов</b>начинок</span></li>
        <li><span class="fact-icon">□</span><span><b>Оптимальный заказ</b>за 2 недели</span></li>
      </ul>
    </div>
  </section>

  <article class="chocolate-page-content postcard-cakes-page-content">
    <nav class="page-breadcrumbs" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
      <span aria-hidden="true">→</span>
      <span aria-current="page">Торты-открытки</span>
    </nav>

    <aside class="landing-page-links postcard-cakes-links" aria-label="Другие направления">
      <h2>Другие направления</h2>
      <p>Кроме тортов-открыток, я создаю десерты для других праздников и подарков.</p>
      <div class="landing-page-links-actions">
      <a href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">Торты на день рождения</a>
      <a href="<?php echo esc_url(home_url('/wedding-cakes/')); ?>">Свадебные торты</a>
      <a href="<?php echo esc_url(home_url('/handmade-chocolate/')); ?>">Шоколад ручной работы</a>
      <a href="<?php echo esc_url(home_url('/?work_category=classic_cakes#works')); ?>">Классические торты</a>
      <a href="<?php echo esc_url(home_url('/?work_category=thematic_cakes#works')); ?>">Тематические торты</a>
      </div>
    </aside>
  </article>
</main>
<?php get_footer(); ?>
