<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php get_header(); ?>

<main class="page-layout">
    <p class="page-back-link">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            ← Вернуться на главную
        </a>
    </p>

    <?php
    while (have_posts()) :
        the_post();
        ?>

        <article>
            <h1><?php the_title(); ?></h1>

            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>
