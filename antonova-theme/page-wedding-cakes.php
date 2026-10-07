<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    ?>
    <main>
        <section class="chocolate-hero">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Свадебные торты</p>
                <h1><?php the_title(); ?></h1>
                <p>Создаю <a href="<?php echo esc_url(home_url('/works/%d1%81%d0%b2%d0%b0%d0%b4%d0%b5%d0%b1%d0%bd%d1%8b%d0%b9-%d1%82%d0%be%d1%80%d1%82-%d1%81-%d1%86%d0%b2%d0%b5%d1%82%d0%b0%d0%bc%d0%b8/')); ?>">свадебные торты</a> в Москве по индивидуальному дизайну. Обсудим размер, начинку, цвет, декор и оформление торта с учётом стиля вашей свадьбы. Доступны самовывоз в Коптево и доставка по Москве и области.</p>
                <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать свадебный торт</a>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Свадебные торты</span></nav>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
            <div class="chocolate-page-actions">
                <a class="button" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить заказ</a>
                <a class="text-link" href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">Торты на день рождения <span aria-hidden="true">→</span></a>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
