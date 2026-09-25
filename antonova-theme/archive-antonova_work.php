<?php get_header(); ?>

<main class="works-catalogue-page">
  <section class="works-catalogue-hero">
    <p class="eyebrow">Каталог работ</p>
    <h1>Сладкие шедевры для ваших торжеств</h1>
    <p>Выберите категорию или откройте работу, чтобы посмотреть детали.</p>
  </section>

  <?php $catalogue_works = antonova_get_catalogue_works(); ?>
  <?php if ($catalogue_works->have_posts()) : ?>
    <div class="work-filters work-filters-catalogue" aria-label="Категории работ">
      <button class="work-filter is-active" type="button" data-work-filter="all">Все</button>
      <?php foreach (get_terms(array('taxonomy' => 'antonova_work_category', 'hide_empty' => false)) as $category) : ?>
        <button class="work-filter" type="button" data-work-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
      <?php endforeach; ?>
    </div>
    <div class="work-grid work-grid-catalogue" data-work-grid>
      <?php while ($catalogue_works->have_posts()) : $catalogue_works->the_post(); ?>
        <?php $work_image = antonova_work_image_url(get_the_ID()); ?>
        <?php if ($work_image) : ?>
          <article class="work-card" data-work-card data-categories="<?php echo esc_attr(implode(' ', antonova_work_categories(get_the_ID()))); ?>">
            <a href="<?php echo esc_url($work_image); ?>" data-work-lightbox data-work-image="<?php echo esc_url($work_image); ?>" data-work-title="<?php echo esc_attr(get_the_title()); ?>" aria-label="Увеличить: <?php echo esc_attr(get_the_title()); ?>">
              <img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
              <span class="work-card-caption"><b><?php the_title(); ?></b><span>Увеличить фото →</span></span>
            </a>
          </article>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
    <button class="works-more" type="button" data-works-more hidden>Показать ещё работы <span aria-hidden="true">↻</span></button>
    <p class="works-exit-order"><a class="button" href="<?php echo esc_url(antonova_content('antonova_whatsapp_url', 'https://wa.me/79647281844')); ?>" target="_blank" rel="noopener">Выйти и заказать</a></p>
    <?php wp_reset_postdata(); ?>
  <?php else : ?>
    <p class="works-empty">Работы скоро появятся в каталоге.</p>
  <?php endif; ?>
</main>

<div class="work-lightbox" data-work-lightbox-dialog hidden role="dialog" aria-modal="true" aria-label="Увеличенное фото работы">
  <button class="work-lightbox-backdrop" type="button" data-work-lightbox-close aria-label="Закрыть увеличенное фото"></button>
  <div class="work-lightbox-content" role="document">
    <button class="work-lightbox-close" type="button" data-work-lightbox-close aria-label="Закрыть">×</button>
    <div class="work-lightbox-viewer" data-work-lightbox-viewer>
      <div class="work-lightbox-source" data-work-lightbox-source>
        <img src="" alt="" data-work-lightbox-image>
        <span class="work-lightbox-lens" data-work-lightbox-lens hidden aria-hidden="true"></span>
      </div>
      <div class="work-lightbox-zoom" data-work-lightbox-zoom hidden aria-hidden="true"></div>
    </div>
    <p data-work-lightbox-title></p>
  </div>
</div>

<?php get_footer(); ?>
