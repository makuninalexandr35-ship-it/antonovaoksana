<?php
/*
Template Name: Свадебные торты — лендинг
*/

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    ?>
    <main>
        <section class="chocolate-hero">
            <img class="chocolate-hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/Hero2.webp" width="1680" height="945" decoding="async" alt="Свадебный торт с белыми цветами и лебедями" fetchpriority="high">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Свадебные торты</p>
                <h1>Свадебные торты<br>на заказ в Москве</h1>
                <p>Создаю свадебные торты в Москве по индивидуальному дизайну. Обсудим размер, начинку, цвет, декор и оформление торта с учётом стиля вашей свадьбы. Доступны самовывоз в Коптево и доставка по Москве и области.</p>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Свадебные торты</span></nav>
            <div class="page-content">
                <?php the_content(); ?>
                <?php echo antonova_landing_page_cross_links_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <div class="chocolate-page-actions">
                <a class="button" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить заказ</a>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
