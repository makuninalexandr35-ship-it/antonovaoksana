<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
  <?php $work_id = get_the_ID(); ?>
  <?php $work_image = antonova_work_image_url($work_id, 'full'); ?>
  <?php $work_price = get_post_meta($work_id, '_antonova_work_price', true); ?>
  <main class="work-single">
    <p class="page-back-link"><a href="<?php echo esc_url(get_post_type_archive_link('antonova_work')); ?>">← Все работы</a></p>
    <article class="work-single-layout">
      <?php if ($work_image) : ?>
        <div class="work-single-image"><img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title() ?: 'Авторская работа'); ?>"></div>
      <?php endif; ?>
      <div class="work-single-copy">
        <?php $categories = get_the_terms($work_id, 'antonova_work_category'); ?>
        <?php if ($categories && !is_wp_error($categories)) : ?>
          <?php $category_url = get_term_link($categories[0]); ?>
          <?php if (!is_wp_error($category_url)) : ?><p class="eyebrow"><a href="<?php echo esc_url($category_url); ?>"><?php echo esc_html($categories[0]->name); ?></a></p><?php endif; ?>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($work_price) : ?><p class="work-single-price"><?php echo esc_html($work_price); ?></p><?php endif; ?>
        <div class="work-single-description"><?php the_content(); ?></div>
        <a class="button" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать похожий</a>
      </div>
    </article>

    <?php if ($categories && !is_wp_error($categories)) : ?>
      <?php $related_works = new WP_Query(array('post_type' => 'antonova_work', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => array($work_id), 'orderby' => 'date', 'order' => 'DESC', 'tax_query' => array(array('taxonomy' => 'antonova_work_category', 'field' => 'term_id', 'terms' => $categories[0]->term_id)))); ?>
      <?php if ($related_works->have_posts()) : ?>
        <section class="related-works" aria-labelledby="related-works-title">
          <p class="eyebrow">Ещё в этой категории</p>
          <h2 id="related-works-title">Похожие работы</h2>
          <div class="related-work-links">
            <?php while ($related_works->have_posts()) : $related_works->the_post(); ?>
              <a href="<?php the_permalink(); ?>"><span><?php the_title(); ?></span><small>Смотреть работу →</small></a>
            <?php endwhile; ?>
          </div>
        </section>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    <?php endif; ?>
  </main>
<?php endwhile; ?>

<?php get_footer(); ?>
