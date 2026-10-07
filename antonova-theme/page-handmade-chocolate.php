<?php
/*
Template Name: Шоколад ручной работы
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
            <img class="chocolate-hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/Hero3.webp" width="1680" height="945" decoding="async" alt="Подарочный набор шоколада ручной работы" fetchpriority="high">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Авторские сладости на заказ</p>
                <h1>Шоколад ручной работы<br>на заказ<br>в Москве</h1>
                <p>Шоколад ручной работы — это индивидуальный сладкий подарок или шоколадные изделия для особого случая. Заказ и оформление обсуждаются заранее, чтобы авторский шоколад подошёл именно вашему поводу.</p>
                <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать шоколад</a>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Шоколад ручной работы</span></nav>
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
