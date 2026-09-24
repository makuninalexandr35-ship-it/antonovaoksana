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
            <button type="button" data-work-lightbox data-work-image="<?php echo esc_url($work_image); ?>" data-work-title="<?php echo esc_attr(get_the_title()); ?>" aria-label="Увеличить: <?php echo esc_attr(get_the_title()); ?>">
              <img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
              <span class="work-card-caption"><b><?php the_title(); ?></b><span>Увеличить фото →</span></span>
            </button>
          </article>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
    <button class="works-more" type="button" data-works-more hidden>Показать ещё работы <span aria-hidden="true">↻</span></button>
    <?php wp_reset_postdata(); ?>
  <?php else : ?>
    <p class="works-empty">Работы скоро появятся в каталоге.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
