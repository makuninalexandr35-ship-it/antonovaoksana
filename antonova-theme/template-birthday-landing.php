<?php
/*
Template Name: Торты на день рождения — лендинг
Template Post Type: page
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
            <img class="chocolate-hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero4.webp" width="1680" height="945" decoding="async" alt="Торт с ежевикой на праздничном столе" fetchpriority="high">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Торты на день рождения</p>
                <h1 class="mobile-hero-title"><span>Торты на день</span><br><span>рождения на</span><br class="mobile-line-break"> <span>заказ</span><br class="desktop-line-break"> <span>в Москве</span></h1>
                <p>Детские и взрослые торты с индивидуальным оформлением для вашего праздника. Доступны самовывоз в Коптево и доставка по Москве и области.</p>
                <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать торт</a>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Торты на день рождения</span></nav>
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
