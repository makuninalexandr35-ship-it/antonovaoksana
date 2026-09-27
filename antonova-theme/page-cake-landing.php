<?php
/*
Template Name: Страница торта
*/

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    ?>
    <main>
        <section class="hero cake-page-hero<?php echo is_page('wedding-cakes') ? ' cake-page-hero-wedding' : ''; ?>">
            <img class="hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-2268.webp" alt="<?php echo esc_attr(get_the_title()); ?>" width="2268" height="4032" decoding="async" fetchpriority="high">
            <div class="hero-shade"></div>
            <div class="hero-content">
                <p class="eyebrow">Авторские торты на заказ</p>
                <h1><?php the_title(); ?></h1>
                <p class="lead">Индивидуальный дизайн, любимые начинки и внимание к каждой детали вашего праздника.</p>
                <div class="actions">
                    <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Заказать в Telegram</a>
                    <a class="button button-light" href="https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo" target="_blank" rel="noopener noreferrer"><span class="telegram-icon">➤</span> Заказать в MAX</a>
                </div>
            </div>
        </section>

        <article class="cake-page-content<?php echo is_page('wedding-cakes') ? ' cake-page-content-wedding' : ''; ?>">
            <p class="page-back-link"><a href="<?php echo esc_url(home_url('/')); ?>#products">← Вернуться к продукции</a></p>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
