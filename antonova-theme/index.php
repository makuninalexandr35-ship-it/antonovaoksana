<?php get_header(); ?>

<main class="page-layout">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    <?php endwhile; ?>

    <?php the_posts_navigation(); ?>
  <?php else : ?>
    <h1>Материалы сайта</h1>
    <p>По вашему запросу пока ничего не найдено.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
