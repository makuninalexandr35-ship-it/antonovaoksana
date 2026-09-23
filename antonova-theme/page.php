<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main style="max-width:900px;margin:0 auto;padding:60px 24px;">

    <p style="margin-bottom:30px;">
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

<?php wp_footer(); ?>
</body>
</html>