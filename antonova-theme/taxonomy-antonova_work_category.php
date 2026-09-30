<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
$current_category = get_queried_object();
?>

<main class="works-catalogue-page">
  <section class="works-catalogue-hero">
    <p class="eyebrow">Каталог работ</p>
    <h1><?php echo esc_html($current_category->name); ?></h1>
    <p>Выберите работу, чтобы посмотреть детали и увеличить фотографию.</p>
  </section>

  <?php if (have_posts()) : ?>
    <div class="work-grid work-grid-catalogue" data-work-grid>
      <?php while (have_posts()) : the_post(); ?>
        <?php $work_image = antonova_work_image_url(get_the_ID()); ?>
        <?php if ($work_image) : ?>
          <article class="work-card">
            <a href="<?php echo esc_url($work_image); ?>" data-work-lightbox data-work-image="<?php echo esc_url($work_image); ?>" data-work-title="<?php echo esc_attr(get_the_title()); ?>" aria-label="Увеличить: <?php echo esc_attr(get_the_title()); ?>">
              <img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title() ?: 'Авторская работа'); ?>" loading="lazy">
              <span class="work-card-caption"><b><?php the_title(); ?></b><span>Увеличить фото →</span></span>
            </a>
          </article>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p class="works-empty">Работы этой категории скоро появятся в каталоге.</p>
  <?php endif; ?>

  <p class="works-exit-order"><a class="button" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener">Заказать</a></p>
</main>

<div class="work-lightbox" data-work-lightbox-dialog hidden role="dialog" aria-modal="true" aria-label="Увеличенное фото работы">
  <button class="work-lightbox-backdrop" type="button" data-work-lightbox-close aria-label="Закрыть увеличенное фото"></button>
  <div class="work-lightbox-content" role="document">
    <button class="work-lightbox-close" type="button" data-work-lightbox-close aria-label="Закрыть">×</button>
    <div class="work-lightbox-viewer" data-work-lightbox-viewer>
      <div class="work-lightbox-source" data-work-lightbox-source>
        <img src="" alt="Увеличенное фото работы" data-work-lightbox-image>
        <span class="work-lightbox-lens" data-work-lightbox-lens hidden aria-hidden="true"></span>
      </div>
      <div class="work-lightbox-zoom" data-work-lightbox-zoom hidden aria-hidden="true"></div>
    </div>
    <p data-work-lightbox-title></p>
  </div>
</div>

<?php get_footer(); ?>
