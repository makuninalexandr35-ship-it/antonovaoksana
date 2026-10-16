<?php
/*
Template Name: Торты-открытки
Template Post Type: page
*/

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main>
  <section class="hero postcard-cakes-hero">
    <div class="hero-content">
      <h1>Торт открытка на заказ в Москве</h1>
    </div>
  </section>

  <article class="chocolate-page-content postcard-cakes-page-content">
    <nav class="page-breadcrumbs" aria-label="Хлебные крошки">
      <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
      <span aria-hidden="true">→</span>
      <span aria-current="page">Торты-открытки</span>
    </nav>

    <nav class="postcard-cakes-links" aria-label="Другие направления">
      <a href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">Торты на день рождения</a>
      <a href="<?php echo esc_url(home_url('/wedding-cakes/')); ?>">Свадебные торты</a>
      <a href="<?php echo esc_url(home_url('/?work_category=classic_cakes#works')); ?>">Классические торты</a>
      <a href="<?php echo esc_url(home_url('/?work_category=thematic_cakes#works')); ?>">Тематические торты</a>
      <a href="<?php echo esc_url(home_url('/handmade-chocolate/')); ?>">Шоколад ручной работы</a>
    </nav>
  </article>
</main>
<?php get_footer(); ?>
