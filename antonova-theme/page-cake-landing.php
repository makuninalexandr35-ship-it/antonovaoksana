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
    $antonova_is_wedding_page = is_page('wedding-cakes');
    ?>
    <main>
        <section class="hero cake-page-hero<?php echo $antonova_is_wedding_page ? ' cake-page-hero-wedding' : ''; ?>">
            <img class="hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-2268.webp" alt="<?php echo esc_attr(get_the_title()); ?>" width="2268" height="4032" decoding="async" fetchpriority="high">
            <div class="hero-shade"></div>
            <div class="hero-content">
                <p class="eyebrow"><?php echo $antonova_is_wedding_page ? 'Свадебные торты' : 'Авторские торты на заказ'; ?></p>
                <h1><?php the_title(); ?></h1>
                <?php if ($antonova_is_wedding_page) : ?>
                    <p class="lead">Создаю <a href="<?php echo esc_url(home_url('/works/%d1%81%d0%b2%d0%b0%d0%b4%d0%b5%d0%b1%d0%bd%d1%8b%d0%b9-%d1%82%d0%be%d1%80%d1%82-%d1%81-%d1%86%d0%b2%d0%b5%d1%82%d0%b0%d0%bc%d0%b8/')); ?>">свадебные торты</a> в Москве по индивидуальному дизайну. Обсудим размер, начинку, цвет, декор и оформление торта с учётом стиля вашей свадьбы. Доступны самовывоз в Коптево и доставка по Москве и области.</p>
                <?php else : ?>
                    <p class="lead">Индивидуальный дизайн, любимые начинки и внимание к каждой детали вашего праздника.</p>
                <?php endif; ?>
                <div class="actions">
                    <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true"><?php echo $antonova_is_wedding_page ? 'Обсудить свадебный торт' : 'Заказать в MAX'; ?></a>
                </div>
            </div>
        </section>

        <article class="cake-page-content<?php echo $antonova_is_wedding_page ? ' cake-page-content-wedding' : ''; ?>">
            <p class="page-back-link"><a href="<?php echo esc_url(home_url('/')); ?>#products">← Вернуться к продукции</a></p>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
