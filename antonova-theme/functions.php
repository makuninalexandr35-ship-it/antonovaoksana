<?php
if (!defined('ABSPATH')) {
    exit;
}

function antonova_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'antonova_theme_setup');

function antonova_hide_php_version_header() {
    if (function_exists('header_remove')) {
        header_remove('X-Powered-By');
    }
}

function antonova_send_security_headers() {
    antonova_hide_php_version_header();

    if (is_admin()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}
add_action('send_headers', 'antonova_send_security_headers', 999);

function antonova_hide_php_version_from_rest_response($served) {
    antonova_hide_php_version_header();
    return $served;
}
add_filter('rest_pre_serve_request', 'antonova_hide_php_version_from_rest_response', 999);

function antonova_theme_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $style_path = get_stylesheet_directory() . '/style.css';
    $script_path = get_template_directory() . '/script.js';
    $style_version = file_exists($style_path) ? (string) filemtime($style_path) : $theme_version;
    $script_version = file_exists($script_path) ? (string) filemtime($script_path) : $theme_version;

    wp_enqueue_style(
        'antonova-local-fonts',
        get_template_directory_uri() . '/assets/fonts/fonts.css',
        array(),
        $theme_version
    );

    wp_enqueue_style(
        'antonova-style',
        get_stylesheet_uri(),
        array('antonova-local-fonts'),
        $style_version
    );

    wp_enqueue_script(
        'antonova-script',
        get_template_directory_uri() . '/script.js',
        array(),
        $script_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'antonova_theme_assets', 20);

/**
 * Create the two cake landing pages once. Their content remains editable in
 * WordPress afterwards through the regular Pages screen.
 */
function antonova_seed_cake_landing_pages() {
    if (get_option('antonova_cake_landing_pages_seeded')) {
        return;
    }

    $pages = array(
        array(
            'slug' => 'birthday-cakes',
            'title' => 'Торты на день рождения',
            'content' => '<p>Торт на день рождения — это маленькая история о человеке и его празднике. Подберём размер, начинку и оформление по вашей идее, фотографии или любимым цветам.</p><h2>Торт, который запомнится</h2><p>Создаю детские и взрослые торты с индивидуальным дизайном: для семейного праздника, юбилея или камерного вечера. Обсудим детали заранее, чтобы десерт подошёл по вкусу и настроению.</p><p><strong>Самовывоз в Коптево и доставка по Москве и области.</strong></p>',
        ),
        array(
            'slug' => 'wedding-cakes',
            'title' => 'Свадебные торты',
            'content' => '<p>Свадебный торт создаётся для вашей истории: от лёгкого минимализма до сложного декора с цветами, фактурами и личными деталями пары.</p><h2>Главный десерт вашего дня</h2><p>Помогу подобрать размер под количество гостей, начинку и оформление. Можно прислать референсы или рассказать о стилистике свадьбы — вместе найдём решение, которое будет гармонично смотреться на празднике.</p><p><strong>Самовывоз в Коптево и доставка по Москве и области.</strong></p>',
        ),
    );

    foreach ($pages as $page) {
        if (get_page_by_path($page['slug'])) {
            continue;
        }

        wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_name' => $page['slug'],
            'post_title' => $page['title'],
            'post_content' => $page['content'],
            'meta_input' => array('_wp_page_template' => 'page-cake-landing.php'),
        ));
    }

    update_option('antonova_cake_landing_pages_seeded', '1');
}
add_action('init', 'antonova_seed_cake_landing_pages', 20);

/** Create the editable handmade-chocolate landing page once. */
function antonova_seed_handmade_chocolate_page() {
    $page = get_page_by_path('handmade-chocolate');

    if (!$page) {
        $content = <<<'HTML'
<h2>Авторский шоколад ручной работы</h2>
<p>Шоколад ручной работы — это возможность выбрать сладкий подарок или заказать шоколадные изделия для особого случая. Формат, внешний вид и детали обсуждаются заранее, чтобы заказ соответствовал вашему поводу и идее.</p>
<p>Авторский шоколад подойдёт для поздравления, семейного праздника, небольшого знака внимания или индивидуального подарка. Каждый заказ согласовывается отдельно — от выбранного варианта до оформления.</p>
<h2>Какой шоколад можно заказать</h2>
<h3>Шоколад на заказ для вашего повода</h3>
<p>Вариант шоколадного заказа подбирается по задаче: для подарка, праздничного стола или личного поздравления. Перед оформлением можно обсудить количество изделий, формат набора и внешний вид, который будет уместен для конкретного случая.</p>
<h3>Подарочные наборы</h3>
<p>Подарочный набор шоколада собирается после согласования идеи. Это удобный формат, когда нужен шоколадный подарок для близкого человека, коллеги или небольшого события.</p>
<h2>Шоколад ручной работы в подарок</h2>
<p>Подарочный шоколад помогает выразить внимание без лишних слов. Шоколад ручной работы в подарок можно заказать к празднику, дню рождения или просто как тёплый знак внимания. Если нужен индивидуальный вариант, заранее обсудим, каким должен быть шоколадный подарок и как его лучше оформить.</p>
<h2>Индивидуальное оформление</h2>
<p>Возможность индивидуального оформления зависит от выбранного изделия и задачи. Детали заказа согласовываются заранее: внешний вид, набор, упаковка, цвет, надпись или тематика — только те элементы, которые действительно подходят выбранному варианту.</p>
<h2>Примеры шоколада ручной работы</h2>
<p>Здесь будут размещены реальные фотографии готовых шоколадных работ. Они помогут выбрать подходящий вариант и обсудить вашу идею без шаблонных решений.</p>
<h2>Стоимость шоколада ручной работы</h2>
<p>Стоимость зависит от выбранного изделия, количества, оформления и других параметров заказа. Точная стоимость рассчитывается после согласования деталей.</p>
<h2>Получение и доставка шоколада</h2>
<p>Доступны самовывоз в Коптево и доставка по Москве и области. Условия получения шоколада ручной работы с доставкой в Москве уточняются при согласовании заказа.</p>
<h2>Заказать шоколад ручной работы в Москве</h2>
<p>Чтобы заказать шоколад ручной работы, напишите Оксане и расскажите, для какого случая нужен заказ. Затем согласуем вариант изделия и оформление, рассчитаем стоимость и остальные условия. Так шоколад на заказ в Москве будет соответствовать вашему поводу, а не случайному готовому набору.</p>
HTML;

        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_name' => 'handmade-chocolate',
            'post_title' => 'Шоколад ручной работы на заказ в Москве',
            'post_content' => $content,
            'meta_input' => array('_wp_page_template' => 'page-handmade-chocolate.php'),
        ));

        if (!$page_id || is_wp_error($page_id)) {
            return;
        }

        $page = get_post($page_id);
    }

    if ($page && !get_option('antonova_handmade_chocolate_seo_seeded')) {
        update_post_meta($page->ID, '_yoast_wpseo_focuskw', 'шоколад ручной работы на заказ в Москве');
        update_post_meta($page->ID, '_yoast_wpseo_title', 'Шоколад ручной работы на заказ в Москве | Оксана Антонова');
        update_post_meta($page->ID, '_yoast_wpseo_metadesc', 'Шоколад ручной работы на заказ в Москве. Авторские шоколадные изделия и подарочные наборы с индивидуальным оформлением. Узнайте варианты и оформите заказ.');
        update_post_meta($page->ID, '_yoast_wpseo_canonical', home_url('/handmade-chocolate/'));
        update_option('antonova_handmade_chocolate_seo_seeded', '1');
    }
}
add_action('init', 'antonova_seed_handmade_chocolate_page', 23);

/** The existing wedding page keeps its WordPress content but uses the shared landing layout. */
function antonova_wedding_cakes_template($template) {
    if (!is_page('wedding-cakes')) {
        return $template;
    }

    $wedding_template = get_template_directory() . '/page-wedding-cakes.php';
    return file_exists($wedding_template) ? $wedding_template : $template;
}
add_filter('template_include', 'antonova_wedding_cakes_template', 99);

/** Move the original Russian URLs to the short English URLs once. */
function antonova_migrate_cake_page_slugs() {
    if (get_option('antonova_cake_page_slugs_v2')) {
        return;
    }

    $slugs = array(
        'torty-na-den-rozhdeniya' => 'birthday-cakes',
        'svadebnye-torty' => 'wedding-cakes',
    );

    foreach ($slugs as $old_slug => $new_slug) {
        $page = get_page_by_path($old_slug);
        if ($page) {
            wp_update_post(array('ID' => $page->ID, 'post_name' => $new_slug));
        }
    }

    update_option('antonova_cake_page_slugs_v2', '1');
}
add_action('init', 'antonova_migrate_cake_page_slugs', 21);

/** Keep old links working after the URL change. */
function antonova_redirect_legacy_cake_page_urls() {
    $request_path = isset($_SERVER['REQUEST_URI']) ? wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH) : '';
    $redirects = array(
        '/torty-na-den-rozhdeniya/' => '/birthday-cakes/',
        '/svadebnye-torty/' => '/wedding-cakes/',
    );

    if (isset($redirects[$request_path])) {
        wp_safe_redirect(home_url($redirects[$request_path]), 301);
        exit;
    }
}
add_action('template_redirect', 'antonova_redirect_legacy_cake_page_urls', 1);

/** Set the requested Yoast fields once, while keeping them editable in WordPress. */
function antonova_seed_wedding_cake_seo() {
    if (get_option('antonova_wedding_cake_seo_seeded')) {
        return;
    }

    $page = get_page_by_path('wedding-cakes');
    if (!$page) {
        return;
    }

    update_post_meta($page->ID, '_yoast_wpseo_focuskw', 'свадебный торт на заказ в Москве');
    update_post_meta($page->ID, '_yoast_wpseo_title', 'Свадебный торт на заказ в Москве | Oksana Antonova');
    update_post_meta($page->ID, '_yoast_wpseo_metadesc', 'Свадебный торт на заказ в Москве по индивидуальному дизайну. Выбор начинки и декора, самовывоз в Коптево и доставка по Москве и области.');

    update_option('antonova_wedding_cake_seo_seeded', '1');
}
add_action('init', 'antonova_seed_wedding_cake_seo', 22);

/**
 * Link the three landing pages to one another.
 *
 * The links are rendered by the page templates after WordPress content, so
 * third-party SEO blocks cannot be inserted between the article and links.
 */
function antonova_landing_page_cross_links_html() {
    $page_links = array(
        'birthday-cakes' => array(
            array('url' => '/wedding-cakes/', 'label' => 'Свадебные торты на заказ в Москве'),
            array('url' => '/handmade-chocolate/', 'label' => 'Шоколад ручной работы'),
        ),
        'wedding-cakes' => array(
            array('url' => '/birthday-cakes/', 'label' => 'Торты на день рождения на заказ'),
            array('url' => '/handmade-chocolate/', 'label' => 'Шоколад ручной работы'),
        ),
        'handmade-chocolate' => array(
            array('url' => '/birthday-cakes/', 'label' => 'Торты на день рождения на заказ'),
            array('url' => '/wedding-cakes/', 'label' => 'Свадебные торты на заказ в Москве'),
        ),
    );

    foreach ($page_links as $slug => $links) {
        if (!is_page($slug)) {
            continue;
        }

        $items = '';
        foreach ($links as $link) {
            $items .= '<a href="' . esc_url(home_url($link['url'])) . '">' . esc_html($link['label']) . '</a>';
        }

        return '<aside class="landing-page-links" aria-label="Другие направления"><h2 class="landing-page-links-title">Другие направления</h2><div class="landing-page-links-actions">' . $items . '</div></aside>';
    }

    return '';
}

/** Hide the duplicated wedding introduction: it is shown in the hero instead. */
function antonova_remove_wedding_hero_duplicate($content) {
    if (is_admin() || !is_main_query() || !in_the_loop() || !is_page('wedding-cakes')) {
        return $content;
    }

    $pattern = '#^\s*<h2[^>]*>\s*Свадебные торты на заказ в Москве\s*</h2>\s*<p[^>]*>\s*Создаю свадебные торты в Москве по индивидуальному дизайну\. Обсудим размер, начинку, цвет, декор и оформление торта с учётом стиля вашей свадьбы\. Доступны самовывоз в Коптево и доставка по Москве и области\.\s*</p>\s*#u';

    return preg_replace($pattern, '', $content, 1);
}
add_filter('the_content', 'antonova_remove_wedding_hero_duplicate', 10);

/** Use the current order messenger in the editable text of the cake landings. */
function antonova_replace_landing_telegram_with_max($content) {
    if (is_admin() || !is_main_query() || !in_the_loop() || !is_page(array('wedding-cakes', 'birthday-cakes'))) {
        return $content;
    }

    return str_ireplace(array('Telegram', 'Телеграм'), 'MAX', $content);
}
add_filter('the_content', 'antonova_replace_landing_telegram_with_max', 20);

/**
 * The gallery on the front page keeps using the saved work photos, but the
 * separate catalogue and its detail pages are no longer public destinations.
 */
function antonova_redirect_removed_work_catalogue() {
    if (is_post_type_archive('antonova_work') || is_tax('antonova_work_category') || is_singular('antonova_work')) {
        wp_safe_redirect(home_url('/#works'), 301);
        exit;
    }
}
add_action('template_redirect', 'antonova_redirect_removed_work_catalogue', 1);

function antonova_exclude_removed_work_catalogue_from_yoast_sitemap($excluded, $object_type) {
    return in_array($object_type, array('antonova_work', 'antonova_work_category'), true) ? true : $excluded;
}
add_filter('wpseo_sitemap_exclude_post_type', 'antonova_exclude_removed_work_catalogue_from_yoast_sitemap', 10, 2);
add_filter('wpseo_sitemap_exclude_taxonomy', 'antonova_exclude_removed_work_catalogue_from_yoast_sitemap', 10, 2);

/**
 * Catalogue of finished works. Content is managed in WordPress, while the
 * catalogue interface stays in the theme and is deployed through GitHub.
 */
function antonova_register_work_catalogue() {
    register_post_type('antonova_work', array(
        'labels' => array(
            'name' => 'Работы',
            'singular_name' => 'Работа',
            'add_new' => 'Добавить работу',
            'add_new_item' => 'Добавить работу',
            'edit_item' => 'Редактировать работу',
            'new_item' => 'Новая работа',
            'view_item' => 'Посмотреть работу',
            'search_items' => 'Найти работы',
            'not_found' => 'Работ пока нет',
            'menu_name' => 'Работы',
        ),
        'public' => true,
        'has_archive' => 'works',
        'rewrite' => array('slug' => 'works'),
        'menu_icon' => 'dashicons-format-gallery',
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'show_in_rest' => true,
    ));

    register_taxonomy('antonova_work_category', array('antonova_work'), array(
        'labels' => array(
            'name' => 'Категории работ',
            'singular_name' => 'Категория работы',
            'search_items' => 'Найти категории',
            'all_items' => 'Все категории',
            'edit_item' => 'Редактировать категорию',
            'add_new_item' => 'Добавить категорию',
            'menu_name' => 'Категории',
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'work-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'antonova_register_work_catalogue');

function antonova_work_catalogue_default_categories() {
    return array(
        'cakes' => 'Торты',
        'kids' => 'Детские',
        'chocolate' => 'Шоколад',
        'candy' => 'Конфеты',
        'pastries' => 'Пирожные',
        'nuts' => 'Орешки',
        'other-desserts' => 'Другие десерты',
    );
}

function antonova_ensure_work_categories() {
    foreach (antonova_work_catalogue_default_categories() as $slug => $name) {
        if (!term_exists($slug, 'antonova_work_category')) {
            wp_insert_term($name, 'antonova_work_category', array('slug' => $slug));
        }
    }
}
add_action('init', 'antonova_ensure_work_categories', 20);

function antonova_work_catalogue_rewrite_rules() {
    if (get_option('antonova_work_catalogue_rewrite_version') !== '1') {
        flush_rewrite_rules(false);
        update_option('antonova_work_catalogue_rewrite_version', '1');
    }
}
add_action('init', 'antonova_work_catalogue_rewrite_rules', 99);

function antonova_work_price_meta_box() {
    add_meta_box(
        'antonova-work-price',
        'Цена',
        'antonova_render_work_price_meta_box',
        'antonova_work',
        'side'
    );
}
add_action('add_meta_boxes_antonova_work', 'antonova_work_price_meta_box');

function antonova_render_work_price_meta_box($post) {
    wp_nonce_field('antonova_save_work_price', 'antonova_work_price_nonce');
    $price = get_post_meta($post->ID, '_antonova_work_price', true);
    ?>
    <p><label for="antonova-work-price">Например: от 3 000 ₽/кг</label></p>
    <input class="widefat" id="antonova-work-price" name="antonova_work_price" type="text" value="<?php echo esc_attr($price); ?>">
    <p class="description">Поле необязательное. Укажите ориентир, если он нужен.</p>
    <?php
}

function antonova_save_work_price($post_id) {
    if (!isset($_POST['antonova_work_price_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['antonova_work_price_nonce'])), 'antonova_save_work_price')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $price = isset($_POST['antonova_work_price']) ? sanitize_text_field(wp_unslash($_POST['antonova_work_price'])) : '';
    if ($price === '') {
        delete_post_meta($post_id, '_antonova_work_price');
        return;
    }
    update_post_meta($post_id, '_antonova_work_price', $price);
}
add_action('save_post_antonova_work', 'antonova_save_work_price');

function antonova_work_image_url($work_id, $size = 'large') {
    $thumbnail = get_the_post_thumbnail_url($work_id, $size);
    if ($thumbnail) {
        return $thumbnail;
    }

    $legacy_image = get_post_meta($work_id, '_antonova_work_legacy_image', true);
    if (strpos($legacy_image, '08727a6e38cd-960.webp') !== false) {
        return antonova_theme_asset('assets/red-cake.jpg');
    }

    return $legacy_image;
}

function antonova_work_categories($work_id) {
    return wp_get_post_terms($work_id, 'antonova_work_category', array('fields' => 'slugs'));
}

function antonova_migrate_legacy_gallery_to_works() {
    if (get_option('antonova_work_catalogue_seeded')) {
        return;
    }

    $gallery = antonova_get_gallery();
    if (empty($gallery)) {
        return;
    }

    $category_map = array('cakes', 'cakes', 'cakes', 'kids', 'other-desserts', 'other-desserts', 'cakes', 'pastries', 'cakes', 'cakes', 'cakes', 'cakes');
    $created = 0;

    foreach ($gallery as $index => $work) {
        $title = !empty($work['alt']) ? $work['alt'] : 'Работа ' . ($index + 1);
        $work_id = wp_insert_post(array(
            'post_type' => 'antonova_work',
            'post_status' => 'publish',
            'post_title' => $title,
        ));

        if (is_wp_error($work_id) || !$work_id) {
            continue;
        }

        update_post_meta($work_id, '_antonova_work_legacy_image', esc_url_raw($work['image']));
        wp_set_object_terms($work_id, $category_map[$index] ?? 'other-desserts', 'antonova_work_category');
        $created++;
    }

    if ($created > 0) {
        update_option('antonova_work_catalogue_seeded', '1');
    }
}
add_action('admin_init', 'antonova_migrate_legacy_gallery_to_works');

function antonova_get_catalogue_works($limit = -1) {
    return new WP_Query(array(
        'post_type' => 'antonova_work',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
}

/**
 * Technical SEO defaults. The site is WordPress (not Astro), so the
 * equivalent functionality is provided by the theme and WordPress core.
 */
function antonova_seo_limit($value, $limit) {
    $value = trim(wp_strip_all_tags((string) $value));
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $limit);
    }
    return substr($value, 0, $limit);
}

function antonova_seo_context() {
    $context = array(
        'title' => 'Торты на заказ в Москве | Oksana Antonova',
        'description' => 'Авторские торты на заказ в Москве: детские, свадебные, праздничные и ко дню рождения. Самовывоз в Коптево, доставка по Москве и области.',
        'url' => home_url('/'),
        'type' => 'website',
    );

    if (is_front_page()) {
        return $context;
    }

    if (is_post_type_archive('antonova_work')) {
        $context['title'] = 'Каталог работ: торты и десерты | Oksana Antonova';
        $context['description'] = 'Каталог авторских тортов и десертов Оксаны Антоновой: примеры оформления, идеи для праздника и готовые работы.';
        $context['url'] = get_post_type_archive_link('antonova_work');
        return $context;
    }

    if (is_tax('antonova_work_category')) {
        $term = get_queried_object();
        $categories = array(
            'cakes' => array(
                'title' => 'Каталог тортов на заказ в Москве | Oksana Antonova',
                'description' => 'Каталог тортов на заказ в Москве: праздничные, тематические и авторские торты Оксаны Антоновой.',
            ),
            'kids' => array(
                'title' => 'Детские торты на заказ в Москве | Oksana Antonova',
                'description' => 'Детские торты на заказ в Москве: индивидуальный дизайн, любимые персонажи и начинки для праздника.',
            ),
            'other-desserts' => array(
                'title' => 'Авторские десерты на заказ в Москве | Oksana Antonova',
                'description' => 'Авторские десерты на заказ в Москве: сладкие подарки и угощения для праздника с индивидуальным оформлением.',
            ),
            'pastries' => array(
                'title' => 'Пирожные на заказ в Москве | Oksana Antonova',
                'description' => 'Пирожные на заказ в Москве: авторские десерты для подарка, праздника и уютного чаепития.',
            ),
            'chocolate' => array(
                'title' => 'Шоколад ручной работы в Москве | Oksana Antonova',
                'description' => 'Шоколад ручной работы в Москве: авторские сладости и подарки с красивым оформлением.',
            ),
            'candy' => array(
                'title' => 'Конфеты ручной работы в Москве | Oksana Antonova',
                'description' => 'Конфеты ручной работы в Москве: авторские сладости для подарка и особого случая.',
            ),
            'nuts' => array(
                'title' => 'Орешки со сгущёнкой на заказ в Москве | Oksana Antonova',
                'description' => 'Орешки со сгущёнкой на заказ в Москве: домашний десерт для подарка и праздника.',
            ),
        );
        $category = $categories[$term->slug] ?? array(
            'title' => antonova_seo_limit($term->name . ' | Oksana Antonova', 60),
            'description' => antonova_seo_limit('Каталог работ категории «' . $term->name . '» от домашней кондитерской Оксаны Антоновой в Москве.', 160),
        );

        $context['title'] = $category['title'];
        $context['description'] = $category['description'];
        $context['url'] = get_term_link($term);
        return $context;
    }

    if (is_singular('antonova_work')) {
        $work_title = get_the_title();
        $context['title'] = antonova_seo_limit($work_title . ' | Oksana Antonova', 60);
        $context['description'] = 'Авторская работа «' . $work_title . '» — торт или десерт ручной работы от Оксаны Антоновой в Москве.';
        $context['url'] = get_permalink();
        $context['type'] = 'article';
        return $context;
    }

    if (is_page('birthday-cakes')) {
        $context['title'] = 'Торты на день рождения в Москве | Oksana Antonova';
        $context['description'] = 'Торт на день рождения в Москве по индивидуальному дизайну. Выбор начинки и оформления, самовывоз в Коптево и доставка по Москве и области.';
        $context['url'] = get_permalink();
        return $context;
    }

    if (is_page('wedding-cakes')) {
        $context['title'] = 'Свадебный торт на заказ в Москве | Oksana Antonova';
        $context['description'] = 'Свадебный торт на заказ в Москве по индивидуальному дизайну. Выбор начинки и декора, самовывоз в Коптево и доставка по Москве и области.';
        $context['url'] = get_permalink();
        return $context;
    }

    if (is_page('handmade-chocolate')) {
        $context['title'] = 'Шоколад ручной работы на заказ в Москве | Оксана Антонова';
        $context['description'] = 'Шоколад ручной работы на заказ в Москве. Авторские шоколадные изделия и подарочные наборы с индивидуальным оформлением. Узнайте варианты и оформите заказ.';
        $context['url'] = home_url('/handmade-chocolate/');
        return $context;
    }

    if (is_page()) {
        $page_title = get_the_title();
        $context['title'] = antonova_seo_limit($page_title . ' | Oksana Antonova', 60);
        $context['description'] = antonova_seo_limit('Страница «' . $page_title . '» сайта домашней кондитерской Оксаны Антоновой: торты и авторские десерты на заказ в Москве.', 160);
        $context['url'] = get_permalink();
        return $context;
    }

    if (is_404()) {
        $context['title'] = 'Страница не найдена | Oksana Antonova';
        $context['description'] = 'Запрошенная страница не найдена. Перейдите на главную и выберите авторские торты и десерты на заказ.';
        return $context;
    }

    if (is_search()) {
        $context['title'] = 'Результаты поиска | Oksana Antonova';
        $context['description'] = 'Результаты поиска по сайту домашней кондитерской Оксаны Антоновой.';
        return $context;
    }

    if (is_singular()) {
        $singular_title = get_the_title();
        $context['title'] = antonova_seo_limit($singular_title . ' | Oksana Antonova', 60);
        $context['description'] = antonova_seo_limit(wp_trim_words(get_the_excerpt(), 28, '…'), 160);
        $context['url'] = get_permalink();
    }

    return $context;
}

function antonova_document_title($title) {
    $context = antonova_seo_context();
    return $context['title'];
}
add_filter('pre_get_document_title', 'antonova_document_title', 20);

function antonova_yoast_title($title) {
    return antonova_seo_context()['title'];
}
add_filter('wpseo_title', 'antonova_yoast_title');

function antonova_yoast_description($description) {
    return antonova_seo_context()['description'];
}
add_filter('wpseo_metadesc', 'antonova_yoast_description');

function antonova_yoast_opengraph_title($title) {
    return antonova_seo_context()['title'];
}
add_filter('wpseo_opengraph_title', 'antonova_yoast_opengraph_title');

function antonova_yoast_opengraph_description($description) {
    return antonova_seo_context()['description'];
}
add_filter('wpseo_opengraph_desc', 'antonova_yoast_opengraph_description');

function antonova_social_image_url() {
    if (is_page('handmade-chocolate')) {
        $page_id = get_queried_object_id();
        $featured_image = $page_id ? get_the_post_thumbnail_url($page_id, 'full') : '';
        return $featured_image ? $featured_image : '';
    }

    return antonova_theme_asset('assets/og-cake.jpg');
}

function antonova_yoast_opengraph_image($image) {
    return antonova_social_image_url();
}
add_filter('wpseo_opengraph_image', 'antonova_yoast_opengraph_image');

function antonova_yoast_opengraph_type($type) {
    return antonova_seo_context()['type'];
}
add_filter('wpseo_opengraph_type', 'antonova_yoast_opengraph_type');

/** Keep key landing-page Schema readable even when Yoast uses a generic page type. */
function antonova_yoast_schema_webpage($data) {
    if (is_front_page()) {
        $data['@type'] = 'WebPage';
        $data['name'] = 'Торты на заказ в Москве | Oksana Antonova';
    }

    if (is_page('handmade-chocolate')) {
        $context = antonova_seo_context();
        $data['@type'] = 'WebPage';
        $data['name'] = 'Шоколад ручной работы на заказ в Москве';
        $data['description'] = $context['description'];
        $data['url'] = $context['url'];
    }

    return $data;
}
add_filter('wpseo_schema_webpage', 'antonova_yoast_schema_webpage', 20);

function antonova_yoast_schema_website($data) {
    $data['name'] = 'Oksana Antonova';
    $data['description'] = 'Авторские торты на заказ в Москве для свадеб, дней рождения и других праздников.';

    return $data;
}
add_filter('wpseo_schema_website', 'antonova_yoast_schema_website', 20);

/**
 * Yoast can have its Open Graph module disabled. Render a stable preview image
 * from the theme so social networks always receive a valid image URL.
 */
function antonova_render_social_image_meta() {
    if (is_admin()) {
        return;
    }

    $image = antonova_social_image_url();
    if (!$image) {
        return;
    }
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta property="og:image:secure_url" content="' . esc_url($image) . '">' . "\n";
    echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
    echo '<meta property="og:image:width" content="675">' . "\n";
    echo '<meta property="og:image:height" content="1200">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
}
add_action('wp_head', 'antonova_render_social_image_meta', 99);

function antonova_render_seo_meta() {
    // Yoast already renders these tags when active; avoid duplicate metadata.
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }

    $context = antonova_seo_context();
    $image = antonova_social_image_url();
    echo '<meta name="description" content="' . esc_attr(antonova_seo_limit($context['description'], 160)) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($context['url']) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($context['type']) . '">' . "\n";
    echo '<meta property="og:locale" content="ru_RU">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr(antonova_seo_limit($context['title'], 60)) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(antonova_seo_limit($context['description'], 160)) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($context['url']) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr(antonova_seo_limit($context['title'], 60)) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(antonova_seo_limit($context['description'], 160)) . '">' . "\n";
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    }
}
add_action('wp_head', 'antonova_render_seo_meta', 2);

/** The breadcrumb schema mirrors the visible trail on the chocolate page. */
function antonova_render_handmade_chocolate_breadcrumb_schema() {
    if (!is_page('handmade-chocolate')) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array('@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => home_url('/')),
            array('@type' => 'ListItem', 'position' => 2, 'name' => 'Шоколад ручной работы', 'item' => home_url('/handmade-chocolate/')),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'antonova_render_handmade_chocolate_breadcrumb_schema', 30);

function antonova_enable_core_sitemap($enabled) {
    return true;
}
add_filter('wp_sitemaps_enabled', 'antonova_enable_core_sitemap');

function antonova_add_sitemap_to_robots($output, $public) {
    if (!$public) {
        return $output;
    }

    // Yoast publishes sitemap_index.xml itself; do not add a second, unused
    // WordPress core sitemap URL when that plugin is active.
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return $output;
    }

    $sitemap_line = 'Sitemap: ' . esc_url_raw(home_url('/wp-sitemap.xml'));
    if (strpos($output, 'Sitemap:') === false) {
        $output = rtrim($output) . "\n\n" . $sitemap_line . "\n";
    }
    return $output;
}
add_filter('robots_txt', 'antonova_add_sitemap_to_robots', 99, 2);

function antonova_remove_unused_core_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'antonova_remove_unused_core_styles', 100);

function antonova_theme_asset($path) {
    return trailingslashit(get_template_directory_uri()) . ltrim($path, '/');
}

/**
 * Human-readable captions for the photos supplied in assets/works.
 * Keep the technical file name as the array key; it makes replacing a photo
 * possible without changing its public URL or the gallery markup.
 */
function antonova_theme_work_captions() {
    return array(
        '1-ezgif.com-jpg-to-webp-converter.webp' => 'Подарочный набор шоколадных пончиков',
        '12-ezgif.com-jpg-to-webp-converter.webp' => 'Розовый торт на 12 лет',
        '123-ezgif.com-jpg-to-webp-converter.webp' => 'Набор шоколада с фисташками',
        '19-ezgif.com-jpg-to-webp-converter.webp' => 'Торт «Без паники, ты не старенький»',
        '2-ezgif.com-jpg-to-webp-converter (1).webp' => 'Подарочные наборы шоколада с цветочным декором',
        '2-ezgif.com-jpg-to-webp-converter (2).webp' => 'Десертная тарелка с розовыми безе',
        '2-ezgif.com-jpg-to-webp-converter (3).webp' => 'Розовый торт с бантиками',
        '2-ezgif.com-jpg-to-webp-converter (4).webp' => 'Набор авторского шоколада и конфет',
        '2-ezgif.com-jpg-to-webp-converter (5).webp' => 'Шоколадный торт с карамелью',
        '2-ezgif.com-jpg-to-webp-converter (6).webp' => 'Набор шоколадных пончиков',
        '2-ezgif.com-jpg-to-webp-converter.webp' => 'Разноцветные макаруны',
        '233-ezgif.com-jpg-to-webp-converter.webp' => 'Медовик со свежими ягодами',
        '3-ezgif.com-jpg-to-webp-converter (1).webp' => 'Подарочный набор шоколадных конфет',
        '3-ezgif.com-jpg-to-webp-converter.webp' => 'Набор шоколадных плиток с орехами и ягодами',
        '30-ezgif.com-jpg-to-webp-converter.webp' => 'Белый торт с розами на 30 лет',
        '4-ezgif.com-jpg-to-webp-converter.webp' => 'Золотой набор шоколадных конфет',
        '5-ezgif.com-jpg-to-webp-converter.webp' => 'Набор геометрического шоколада',
        '51-ezgif.com-jpg-to-webp-converter.webp' => 'Торт с ежевикой на 51 год',
        'ezgif.com-jpg-to-webp-converter (1).webp' => 'Торт с грушами и виноградом',
        'ezgif.com-jpg-to-webp-converter (10).webp' => 'Безе с малиновой начинкой',
        'ezgif.com-jpg-to-webp-converter (11).webp' => 'Яркий торт с ягодами и шоколадом',
        'ezgif.com-jpg-to-webp-converter (12).webp' => 'Прямоугольный торт со свежими ягодами и фруктами',
        'ezgif.com-jpg-to-webp-converter (13).webp' => 'Подарочный набор шоколада с зимним декором',
        'ezgif.com-jpg-to-webp-converter (14).webp' => 'Лимонный торт',
        'ezgif.com-jpg-to-webp-converter (15).webp' => 'Торт в виде корабля',
        'ezgif.com-jpg-to-webp-converter (16).webp' => 'Торт-карусель',
        'ezgif.com-jpg-to-webp-converter (17).webp' => 'Торт с футбольными мячами',
        'ezgif.com-jpg-to-webp-converter (18).webp' => 'Черничный торт со свечами',
        'ezgif.com-jpg-to-webp-converter (19).webp' => 'Чёрно-золотой торт с геометрическим узором',
        'ezgif.com-jpg-to-webp-converter (2).webp' => 'Зелёный двухъярусный торт на день рождения',
        'ezgif.com-jpg-to-webp-converter (20).webp' => 'Чизкейк с клубничным покрытием',
        'ezgif.com-jpg-to-webp-converter (21).webp' => 'Набор шоколадных плиток и конфет',
        'ezgif.com-jpg-to-webp-converter (22).webp' => 'Шоколадная ёлка',
        'ezgif.com-jpg-to-webp-converter (3).webp' => 'Розовые безе',
        'ezgif.com-jpg-to-webp-converter (4).webp' => 'Торт с клубникой и фигурками',
        'ezgif.com-jpg-to-webp-converter (5).webp' => 'Шоколадные конфеты с декором',
        'ezgif.com-jpg-to-webp-converter (6).webp' => 'Торт с печеньем и драже',
        'ezgif.com-jpg-to-webp-converter (7).webp' => 'Белый двухъярусный торт с золотом',
        'ezgif.com-jpg-to-webp-converter (8).webp' => 'Подарочная коробка с печеньем',
        'ezgif.com-jpg-to-webp-converter (9).webp' => 'Торт с мишкой и макарунами',
        'ezgif.com-jpg-to-webp-converter.webp' => 'Десертная тарелка с макарунами и безе',
        'svadebniy-ezgif.com-jpg-to-webp-converter.webp' => 'Розовый свадебный торт с цветами',
    );
}

/**
 * Photos placed in assets/works are automatically included in the front-page
 * portfolio. Approved captions provide names and alt text for the current
 * collection; descriptive file names remain a safe fallback for later photos.
 */
function antonova_get_theme_works() {
    $directory = trailingslashit(get_template_directory()) . 'assets/works';
    $files = glob($directory . '/*.{webp,WEBP,jpg,JPG,jpeg,JPEG,png,PNG,avif,AVIF}', GLOB_BRACE);

    if (empty($files)) {
        return array();
    }

    usort($files, function ($left, $right) {
        return filemtime($right) <=> filemtime($left);
    });

    $works = array();
    $captions = antonova_theme_work_captions();
    foreach ($files as $file) {
        $filename = wp_basename($file);
        $title = isset($captions[$filename]) ? $captions[$filename] : trim(preg_replace('/[-_]+/u', ' ', pathinfo($filename, PATHINFO_FILENAME)));
        $title = $title !== '' ? $title : 'Авторская работа';

        $works[] = array(
            'image' => antonova_theme_asset('assets/works/' . rawurlencode($filename)),
            'title' => $title,
            'alt' => $title,
            'categories' => 'other-desserts',
        );
    }

    return $works;
}

function antonova_content($key, $default) {
    $value = get_option($key, '');
    return $value !== '' ? $value : $default;
}

function antonova_default_products() {
    return array(
        array('name' => 'Торты', 'price' => 'от 3000 ₽/кг', 'image' => antonova_theme_asset('assets/optimized/38a4229c5821-960.webp')),
        array('name' => 'Шоколад ручной работы', 'price' => 'от 2000 ₽', 'image' => antonova_theme_asset('assets/optimized/7cff2bb5e784-960.webp')),
        array('name' => 'Шоколадные конфеты ручной работы', 'price' => 'от 150 ₽', 'image' => antonova_theme_asset('assets/optimized/4127335644ff-720.webp')),
        array('name' => 'Ассорти домашних пирожных', 'price' => 'от 200 ₽', 'image' => antonova_theme_asset('assets/optimized/f96267f0396d-1086.webp')),
        array('name' => 'Печенье орешки с начинкой', 'price' => 'от 100 ₽', 'image' => antonova_theme_asset('assets/optimized/f015a856bc41-1600.webp')),
        array('name' => 'Свежие фрукты в шоколадной глазури', 'price' => 'от 1500 ₽', 'image' => antonova_theme_asset('assets/optimized/981461dca556-1183.webp')),
        array('name' => 'Зефир', 'price' => 'от 150 ₽', 'image' => antonova_theme_asset('assets/optimized/a73d6dbb6d7f-720.webp')),
    );
}

/** Apply the approved product-card names without replacing photos or prices. */
function antonova_migrate_product_names_v3() {
    if (get_option('antonova_product_names_version') === '3') {
        return;
    }

    $products = get_option('antonova_products', array());
    if (!is_array($products)) {
        $products = array();
    }

    $names = array(
        1 => 'Шоколад ручной работы',
        2 => 'Шоколадные конфеты ручной работы',
        3 => 'Ассорти домашних пирожных',
        4 => 'Печенье орешки с начинкой',
        5 => 'Свежие фрукты в шоколадной глазури',
    );

    foreach ($names as $index => $name) {
        if (!isset($products[$index]) || !is_array($products[$index])) {
            $products[$index] = array();
        }
        $products[$index]['name'] = $name;
    }

    update_option('antonova_products', $products);
    update_option('antonova_product_names_version', '3');
}
add_action('init', 'antonova_migrate_product_names_v3', 20);

function antonova_default_product_prices() {
    return array(
        array('price' => 'от 3000 ₽/кг'),
        array('price' => 'от 950 ₽/шт'),
        array('price' => 'от 150 ₽/шт'),
        array('price' => 'от 200 ₽/шт'),
        array('price' => 'от 100 ₽/шт'),
        array('price' => 'от 150 ₽/набор'),
        array('price' => 'от 150 ₽/шт'),
    );
}

function antonova_migrate_product_prices_v2() {
    if (get_option('antonova_product_prices_version') === '2') {
        return;
    }

    update_option('antonova_product_prices', antonova_default_product_prices());
    update_option('antonova_product_prices_version', '2');
}
add_action('init', 'antonova_migrate_product_prices_v2');

function antonova_default_flavors() {
    return array(
        array('name' => "Медовик\nклассический", 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/cd528ac478c6-1122.webp')),
        array('name' => 'Медовик авторский', 'subtitle' => 'Красный бархат, апельсиновый, тархун-алоэ', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/0196b7589d20-1122.webp')),
        array('name' => 'Наполеон', 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/e79c5604082f-1122.webp')),
        array('name' => "Шоколадный\nс вишнёвым конфи", 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/dd43c1d61dc7-1122.webp')),
        array('name' => "Шпинатный\nс лимонным курдом", 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/54fccadc43c4-1122.webp')),
        array('name' => 'Рафаэлло', 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/473c6a3b3d19-1122.webp')),
        array('name' => 'Марс', 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/0b67946f94f7-1122.webp')),
        array('name' => 'Ваниль — клубника', 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/a298af3bbaf0-1122.webp')),
        array('name' => "Шоколадный\nс дубайской начинкой", 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/4c3df46db0ec-1122.webp')),
        array('name' => 'Груша — дорблю', 'subtitle' => '', 'price' => '', 'image' => antonova_theme_asset('assets/optimized/7521e197859c-1122.webp')),
    );
}

function antonova_default_gallery() {
    return array(
        array('image' => antonova_theme_asset('assets/optimized/85089eed029c-720.webp'), 'alt' => 'Авторский праздничный торт'),
        array('image' => antonova_theme_asset('assets/optimized/08727a6e38cd-960.webp'), 'alt' => 'Красный праздничный торт'),
        array('image' => antonova_theme_asset('assets/optimized/060af1414a76-1600.webp'), 'alt' => 'Торт с поздравлением ко дню рождения'),
        array('image' => antonova_theme_asset('assets/optimized/e0a556f500ee-1600.webp'), 'alt' => 'Тематический торт для любителя тенниса'),
        array('image' => antonova_theme_asset('assets/optimized/a799d1b9b18f-1600.webp'), 'alt' => 'Авторский тематический торт'),
        array('image' => antonova_theme_asset('assets/optimized/5a7eacef9035-720.webp'), 'alt' => 'Подарочный десерт Oksana Antonova'),
        array('image' => antonova_theme_asset('assets/optimized/02e90c97cd4e-1122.webp'), 'alt' => 'Свадебный торт с цветами'),
        array('image' => antonova_theme_asset('assets/optimized/cfb0d97d17f2-1086.webp'), 'alt' => 'Кексы с праздничным декором'),
        array('image' => antonova_theme_asset('assets/optimized/cae76f854bbf-720.webp'), 'alt' => 'Праздничный торт с индивидуальным оформлением'),
        array('image' => antonova_theme_asset('assets/optimized/c6097e4cd88b-2268.webp'), 'alt' => 'Торт с цветочным декором'),
        array('image' => antonova_theme_asset('assets/optimized/b972278f5f09-720.webp'), 'alt' => 'Новогодний торт с Дедом Морозом'),
        array('image' => antonova_theme_asset('assets/optimized/ab91fc3a0fa7-1600.webp'), 'alt' => 'Черничный юбилейный торт на 50 лет'),
    );
}

function antonova_default_price_points() {
    return array(
        array('title' => 'Декор — отдельно', 'text' => 'Рассчитаем после согласования идеи'),
        array('title' => 'Срочный заказ — +20%', 'text' => 'За 2–3 дня, при наличии свободного времени'),
        array('title' => 'Доставка, самовывоз или курьер', 'text' => 'Доставка рассчитывается отдельно'),
    );
}

function antonova_default_faq() {
    return array(
        array('question' => 'За сколько нужно делать заказ?', 'answer' => 'Лучше оформить заказ за 2 недели. Минимальный рекомендуемый срок — одна неделя.'),
        array('question' => 'Можно заказать срочно?', 'answer' => 'Да, если есть свободное время. Заказ за 2–3 дня рассчитывается с доплатой 20%.'),
        array('question' => 'Сколько торта нужно на человека?', 'answer' => 'Ориентировочно 150–200 г на одного гостя.'),
        array('question' => 'Можно сделать оформление по моей фотографии?', 'answer' => 'Конечно. Пришлите фотографию или референс, и мы обсудим желаемое оформление.'),
        array('question' => 'Сколько стоит торт и есть ли доставка?', 'answer' => 'Стоимость — 3000 ₽ за килограмм, декор рассчитывается отдельно. Возможны курьерская доставка и самовывоз.'),
    );
}

function antonova_merge_rows($saved, $defaults) {
    if (!is_array($saved)) {
        return $defaults;
    }

    $result = array();
    foreach ($defaults as $index => $default_row) {
        $saved_row = isset($saved[$index]) && is_array($saved[$index]) ? $saved[$index] : array();
        $row = array();
        foreach ($default_row as $key => $default_value) {
            $value = isset($saved_row[$key]) ? $saved_row[$key] : '';
            $row[$key] = $value !== '' ? $value : $default_value;
        }
        $result[] = $row;
    }
    return $result;
}

function antonova_get_products() {
    return antonova_merge_rows(get_option('antonova_products', array()), antonova_default_products());
}
function antonova_get_product_prices() {
    return antonova_merge_rows(get_option('antonova_product_prices', array()), antonova_default_product_prices());
}
function antonova_get_product_price_cards() {
    $products = antonova_get_products();
    $prices = antonova_get_product_prices();
    $cards = array();

    foreach ($prices as $index => $price) {
        $cards[] = array(
            'name' => $products[$index]['name'] ?? '',
            'price' => $price['price'],
        );
    }

    return $cards;
}
function antonova_product_price_icon($index) {
    $icons = array(
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M13 31h38v21H13zM13 40h38M18 31c0-7 6-12 14-12s14 5 14 12M27 19c0-5 3-9 8-11M34 9c3 0 5 2 5 5M10 53h44"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><g transform="rotate(29 32 32)"><rect x="17" y="7" width="30" height="50" rx="2"/><path d="M27 7v50M37 7v50M17 23h30M17 40h30"/></g></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M21 24 9 18l3 12-6 7 15 4M43 24l12-6-3 12 6 7-15 4M21 24c5-5 17-5 22 0v17c-5 5-17 5-22 0zM25 28c4 3 10 3 14 0M25 37c4 3 10 3 14 0"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M19 31h26l-3 23H22zM21 40h22M17 29c-3-7 2-13 9-12-1-7 8-10 12-4 6-2 11 3 9 9 5 3 5 10-2 11-7 2-19 2-27 0-5-2-5-8-1-11zM25 24c3 2 10 2 14 0M31 18c2 3 3 7 2 11"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M9 42c1-12 9-24 21-31 8 10 11 22 7 31-5 12-18 17-26 9-2-2-3-5-2-9zM17 46c6-9 10-18 13-29M16 33l12 5M22 23l9 5M37 26c7-5 14-6 20-3 0 9-2 17-8 22-4 4-9 5-13 3M42 42c2-6 6-11 11-15"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M32 18c-9 0-15 5-15 14 0 13 7 23 15 29 8-6 15-16 15-29 0-9-6-14-15-14zM32 18c-5-6-10-7-15-4M32 18c4-7 10-8 15-4M26 14l-3-6M37 14l4-6M18 39c5 3 9 4 14 4s10-1 14-4M25 27h.01M33 25h.01M40 29h.01"/><path style="fill:currentColor;stroke:none" d="M19 40c4 2 8 3 13 3s10-1 13-3c-2 8-7 15-13 20-6-5-11-12-13-20z"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M32 7c7 7 7 13 1 18 10-2 16 3 14 10 8 2 10 9 6 15-5 8-37 8-42 0-4-6-2-13 6-15-2-7 4-12 14-10-6-5-6-11 1-18zM17 35c7 6 23 6 30 0M12 47c10 6 30 6 40 0"/></svg>',
    );

    return $icons[$index] ?? '';
}
function antonova_get_flavors() {
    return antonova_merge_rows(get_option('antonova_flavors', array()), antonova_default_flavors());
}
function antonova_get_gallery() {
    return antonova_merge_rows(get_option('antonova_gallery', array()), antonova_default_gallery());
}
function antonova_get_price_points() {
    return antonova_merge_rows(get_option('antonova_price_points', array()), antonova_default_price_points());
}
function antonova_get_faq() {
    return antonova_merge_rows(get_option('antonova_faq', array()), antonova_default_faq());
}

function antonova_sanitize_products($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array(
            'name' => sanitize_textarea_field($row['name'] ?? ''),
            'image' => esc_url_raw($row['image'] ?? ''),
        );
    }
    return $clean;
}
function antonova_sanitize_product_prices($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array('price' => sanitize_text_field($row['price'] ?? ''));
    }
    return $clean;
}
function antonova_sanitize_flavors($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array(
            'name' => sanitize_textarea_field($row['name'] ?? ''),
            'subtitle' => sanitize_text_field($row['subtitle'] ?? ''),
            'price' => sanitize_text_field($row['price'] ?? ''),
            'image' => esc_url_raw($row['image'] ?? ''),
        );
    }
    return $clean;
}
function antonova_sanitize_gallery($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array(
            'image' => esc_url_raw($row['image'] ?? ''),
            'alt' => sanitize_text_field($row['alt'] ?? ''),
        );
    }
    return $clean;
}
function antonova_sanitize_price_points($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array(
            'title' => sanitize_text_field($row['title'] ?? ''),
            'text' => sanitize_text_field($row['text'] ?? ''),
        );
    }
    return $clean;
}
function antonova_sanitize_faq($rows) {
    $clean = array();
    foreach ((array) $rows as $row) {
        $clean[] = array(
            'question' => sanitize_text_field($row['question'] ?? ''),
            'answer' => sanitize_textarea_field($row['answer'] ?? ''),
        );
    }
    return $clean;
}

function antonova_register_content_settings() {
    $text_fields = array(
        'antonova_city',
        'antonova_h1',
        'antonova_lead',
        'antonova_telegram_url',
        'antonova_whatsapp_url',
        'antonova_max_url',
        'antonova_phone_display',
        'antonova_phone_tel',
        'antonova_instagram_url',
        'antonova_vk_candy_url',
        'antonova_vk_oksana_url',
        'antonova_threads_url',
        'antonova_products_heading',
        'antonova_flavors_heading',
        'antonova_works_heading',
        'antonova_faq_heading',
        'antonova_about_image',
    );

    foreach ($text_fields as $field) {
        register_setting(
            'antonova_content_group',
            $field,
            array(
                'type' => 'string',
                'sanitize_callback' => in_array($field, array('antonova_telegram_url', 'antonova_whatsapp_url', 'antonova_max_url', 'antonova_instagram_url', 'antonova_vk_candy_url', 'antonova_vk_oksana_url', 'antonova_threads_url', 'antonova_about_image'), true) ? 'esc_url_raw' : 'sanitize_text_field',
                'default' => '',
            )
        );
    }

    register_setting('antonova_content_group', 'antonova_products', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_products', 'default' => array()));
    register_setting('antonova_content_group', 'antonova_product_prices', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_product_prices', 'default' => array()));
    register_setting('antonova_content_group', 'antonova_flavors', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_flavors', 'default' => array()));
    register_setting('antonova_content_group', 'antonova_gallery', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_gallery', 'default' => array()));
    register_setting('antonova_content_group', 'antonova_price_points', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_price_points', 'default' => array()));
    register_setting('antonova_content_group', 'antonova_faq', array('type' => 'array', 'sanitize_callback' => 'antonova_sanitize_faq', 'default' => array()));
}
add_action('admin_init', 'antonova_register_content_settings');

function antonova_add_content_page() {
    add_menu_page(
        'Antonova — контент сайта',
        'Antonova',
        'manage_options',
        'antonova-content',
        'antonova_render_content_page',
        'dashicons-edit-page',
        3
    );
}
add_action('admin_menu', 'antonova_add_content_page');

function antonova_admin_assets($hook) {
    if ($hook !== 'toplevel_page_antonova-content') {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'antonova_admin_assets');

function antonova_render_media_field($name, $value, $preview_alt = '') {
    ?>
    <div class="antonova-media-field">
        <input class="regular-text antonova-media-url" type="url" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
        <button type="button" class="button antonova-media-button">Выбрать / загрузить фото</button>
        <div class="antonova-media-preview-wrap">
            <img class="antonova-media-preview" src="<?php echo esc_url($value); ?>" alt="<?php echo esc_attr($preview_alt); ?>">
        </div>
    </div>
    <?php
}

function antonova_render_content_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $general_fields = array(
        'antonova_city' => array('Город / строка над заголовком', 'Домашняя кондитерская · Москва', 'text'),
        'antonova_h1' => array('Главный заголовок', 'Торты и авторские десерты на заказ в Москве', 'text'),
        'antonova_lead' => array('Подзаголовок', 'Индивидуальные вкусы, оформление и внимание к каждой детали. От идеи и референса — до десерта, который станет частью вашего праздника.', 'text'),
        'antonova_telegram_url' => array('Ссылка Telegram', 'https://t.me/antonovaov', 'url'),
        'antonova_whatsapp_url' => array('Ссылка WhatsApp', 'https://wa.me/79647281844', 'url'),
        'antonova_max_url' => array('Ссылка MAX', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo', 'url'),
        'antonova_phone_display' => array('Телефон — как показывать', '+7 964 728-18-44', 'text'),
        'antonova_phone_tel' => array('Телефон — для ссылки tel:', '+79647281844', 'text'),
        'antonova_instagram_url' => array('Instagram', 'https://www.instagram.com/_____antonova_____', 'url'),
        'antonova_vk_candy_url' => array('VK — Candy Chef', 'https://vk.ru/candy_chef_aov', 'url'),
        'antonova_vk_oksana_url' => array('VK — Oksana', 'https://vk.com/aov_antonovaoksana', 'url'),
        'antonova_threads_url' => array('Threads', 'https://www.threads.com/@_antonova_', 'url'),
    );

    $section_fields = array(
        'antonova_products_heading' => array('Заголовок блока «Продукция»', 'Десерты для праздника, подарка или просто особенного дня'),
        'antonova_flavors_heading' => array('Заголовок блока «Начинки»', 'Какой будет ваш торт?'),
        'antonova_works_heading' => array('Заголовок блока «Работы»', 'Сладкие шедевры для ваших торжеств'),
        'antonova_faq_heading' => array('Заголовок блока FAQ', 'Возможно, вы хотели спросить'),
    );

    $about_image = antonova_content('antonova_about_image', '');

    $products = antonova_get_products();
    $product_prices = antonova_get_product_prices();
    $flavors = antonova_get_flavors();
    $gallery = antonova_get_gallery();
    $faq = antonova_get_faq();
    ?>
    <div class="wrap antonova-admin">
        <h1>Antonova — контент сайта</h1>
        <p>Здесь можно менять контент без редактирования кода. Дизайн темы остаётся прежним.</p>

        <form method="post" action="options.php">
            <?php settings_fields('antonova_content_group'); ?>

            <details open>
                <summary>Основное и социальные сети</summary>
                <div class="antonova-section">
                    <table class="form-table" role="presentation">
                        <?php foreach ($general_fields as $key => $meta) :
                            $value = antonova_content($key, $meta[1]);
                        ?>
                            <tr>
                                <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($meta[0]); ?></label></th>
                                <td><input class="regular-text" type="<?php echo esc_attr($meta[2]); ?>" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($section_fields as $key => $meta) :
                            $value = antonova_content($key, $meta[1]);
                        ?>
                            <tr>
                                <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($meta[0]); ?></label></th>
                                <td><input class="regular-text" type="text" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </details>

            <details>
                <summary>О себе</summary>
                <div class="antonova-section">
                    <p>Загрузите портрет или фотографию для блока «О себе». Если поле оставить пустым, на сайте останется только текст блока.</p>
                    <label>Фотография Оксаны</label>
                    <?php antonova_render_media_field('antonova_about_image', $about_image, 'Оксана Антонова'); ?>
                </div>
            </details>

            <details>
                <summary>Продукция</summary>
                <div class="antonova-section antonova-grid">
                    <?php foreach ($products as $i => $item) : ?>
                        <div class="antonova-card">
                            <h3>Карточка <?php echo esc_html($i + 1); ?></h3>
                            <label>Название
                                <textarea name="antonova_products[<?php echo esc_attr($i); ?>][name]" rows="2"><?php echo esc_textarea($item['name']); ?></textarea>
                            </label>
                            <label>Фото</label>
                            <?php antonova_render_media_field('antonova_products[' . $i . '][image]', $item['image'], $item['name']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>

            <details>
                <summary>Стоимость продукции</summary>
                <div class="antonova-section antonova-grid">
                    <?php foreach ($product_prices as $i => $item) : ?>
                        <div class="antonova-card">
                            <h3><?php echo nl2br(esc_html($products[$i]['name'] ?? 'Продукция')); ?></h3>
                            <label>Цена
                                <input type="text" name="antonova_product_prices[<?php echo esc_attr($i); ?>][price]" value="<?php echo esc_attr($item['price']); ?>" placeholder="Например: от 3000 ₽/кг">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>

            <details>
                <summary>Начинки</summary>
                <div class="antonova-section antonova-grid">
                    <?php foreach ($flavors as $i => $item) : ?>
                        <div class="antonova-card">
                            <h3>Начинка <?php echo esc_html($i + 1); ?></h3>
                            <label>Название
                                <textarea name="antonova_flavors[<?php echo esc_attr($i); ?>][name]" rows="2"><?php echo esc_textarea($item['name']); ?></textarea>
                            </label>
                            <label>Дополнительная строка
                                <input type="text" name="antonova_flavors[<?php echo esc_attr($i); ?>][subtitle]" value="<?php echo esc_attr($item['subtitle']); ?>">
                            </label>
                            <label>Цена
                                <input type="text" name="antonova_flavors[<?php echo esc_attr($i); ?>][price]" value="<?php echo esc_attr($item['price']); ?>" placeholder="Например: 3000 ₽/кг">
                            </label>
                            <label>Фото</label>
                            <?php antonova_render_media_field('antonova_flavors[' . $i . '][image]', $item['image'], $item['name']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>

            <details>
                <summary>Галерея работ</summary>
                <div class="antonova-section antonova-grid">
                    <?php foreach ($gallery as $i => $item) : ?>
                        <div class="antonova-card">
                            <h3>Фото <?php echo esc_html($i + 1); ?></h3>
                            <label>Описание изображения
                                <input type="text" name="antonova_gallery[<?php echo esc_attr($i); ?>][alt]" value="<?php echo esc_attr($item['alt']); ?>">
                            </label>
                            <label>Фото</label>
                            <?php antonova_render_media_field('antonova_gallery[' . $i . '][image]', $item['image'], $item['alt']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>

            <details>
                <summary>FAQ</summary>
                <div class="antonova-section antonova-grid">
                    <?php foreach ($faq as $i => $item) : ?>
                        <div class="antonova-card">
                            <h3>Вопрос <?php echo esc_html($i + 1); ?></h3>
                            <label>Вопрос
                                <input type="text" name="antonova_faq[<?php echo esc_attr($i); ?>][question]" value="<?php echo esc_attr($item['question']); ?>">
                            </label>
                            <label>Ответ
                                <textarea name="antonova_faq[<?php echo esc_attr($i); ?>][answer]" rows="4"><?php echo esc_textarea($item['answer']); ?></textarea>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>

            <?php submit_button('Сохранить изменения'); ?>
        </form>
    </div>

    <style>
        .antonova-admin details{background:#fff;border:1px solid #dcdcde;border-radius:8px;margin:14px 0}
        .antonova-admin summary{cursor:pointer;font-size:17px;font-weight:600;padding:16px 18px}
        .antonova-section{padding:0 18px 18px}
        .antonova-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px}
        .antonova-card{border:1px solid #dcdcde;border-radius:8px;padding:14px;background:#f9f9f9}
        .antonova-card h3{margin-top:0}
        .antonova-card label{display:block;font-weight:600;margin:10px 0}
        .antonova-card input,.antonova-card textarea{display:block;width:100%;margin-top:5px;font-weight:400}
        .antonova-media-field .antonova-media-url{width:100%}
        .antonova-media-button{margin-top:8px!important}
        .antonova-media-preview-wrap{margin-top:10px;width:100%;aspect-ratio:16/9;background:#eee;border-radius:6px;overflow:hidden}
        .antonova-media-preview{width:100%;height:100%;object-fit:cover}
    </style>

    <script>
    document.addEventListener('click', function(event) {
        const button = event.target.closest('.antonova-media-button');
        if (!button) return;

        event.preventDefault();
        const field = button.closest('.antonova-media-field');
        const input = field.querySelector('.antonova-media-url');
        const preview = field.querySelector('.antonova-media-preview');

        const frame = wp.media({
            title: 'Выберите изображение',
            button: { text: 'Использовать изображение' },
            multiple: false
        });

        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            input.value = attachment.url;
            preview.src = attachment.url;
        });

        frame.open();
    });
    </script>
    <?php
}
