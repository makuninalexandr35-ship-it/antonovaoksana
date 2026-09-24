<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
  <?php $work_image = antonova_work_image_url(get_the_ID(), 'full'); ?>
  <?php $work_price = get_post_meta(get_the_ID(), '_antonova_work_price', true); ?>
  <main class="work-single">
    <p class="page-back-link"><a href="<?php echo esc_url(get_post_type_archive_link('antonova_work')); ?>">← Все работы</a></p>
    <article class="work-single-layout">
      <?php if ($work_image) : ?>
        <div class="work-single-image"><img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></div>
      <?php endif; ?>
      <div class="work-single-copy">
        <?php $categories = get_the_terms(get_the_ID(), 'antonova_work_category'); ?>
        <?php if ($categories && !is_wp_error($categories)) : ?><p class="eyebrow"><?php echo esc_html($categories[0]->name); ?></p><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($work_price) : ?><p class="work-single-price"><?php echo esc_html($work_price); ?></p><?php endif; ?>
        <div class="work-single-description"><?php the_content(); ?></div>
        <a class="button" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Заказать похожий</a>
      </div>
    </article>
  </main>
<?php endwhile; ?>

<?php get_footer(); ?>
