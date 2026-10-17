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
    $style_version = file_exists($style_path) ? $theme_version . '-' . (string) filemtime($style_path) : $theme_version;
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

/** Create the postcard-cake landing page once. Its body stays intentionally empty until copy is approved. */
function antonova_seed_postcard_cakes_page() {
    $page = get_page_by_path('postcard-cakes');

    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_name' => 'postcard-cakes',
            'post_title' => 'Торт открытка на заказ в Москве',
            'post_content' => '',
            'meta_input' => array('_wp_page_template' => 'page-postcard-cakes.php'),
        ));

        if (!$page_id || is_wp_error($page_id)) {
            return;
        }

        $page = get_post($page_id);
    }

    if ($page && !get_option('antonova_postcard_cakes_seo_seeded')) {
        update_post_meta($page->ID, '_yoast_wpseo_focuskw', 'торт открытка на заказ в Москве');
        update_post_meta($page->ID, '_yoast_wpseo_title', 'Торт-открытка на заказ в Москве | Оксана Антонова');
        update_post_meta($page->ID, '_yoast_wpseo_metadesc', 'Торт-открытка: красивый торт-открытка для праздников и любимых людей. Заказ в Москве.');
        update_post_meta($page->ID, '_yoast_wpseo_canonical', home_url('/postcard-cakes/'));
        update_option('antonova_postcard_cakes_seo_seeded', '1');
    }
}
add_action('init', 'antonova_seed_postcard_cakes_page', 24);

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
    update_post_meta($page->ID, '_yoast_wpseo_title', 'Свадебные торты на заказ в Москве — фото работ и цены');
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
            array('url' => '/postcard-cakes/', 'label' => 'Торты-открытки'),
            array('url' => '/handmade-chocolate/', 'label' => 'Шоколад ручной работы'),
            array('url' => '/?work_category=classic_cakes#works', 'label' => 'Классические торты'),
            array('url' => '/?work_category=thematic_cakes#works', 'label' => 'Тематические торты'),
        ),
        'wedding-cakes' => array(
            array('url' => '/birthday-cakes/', 'label' => 'Торты на день рождения на заказ'),
            array('url' => '/postcard-cakes/', 'label' => 'Торты-открытки'),
            array('url' => '/handmade-chocolate/', 'label' => 'Шоколад ручной работы'),
            array('url' => '/?work_category=classic_cakes#works', 'label' => 'Классические торты'),
            array('url' => '/?work_category=thematic_cakes#works', 'label' => 'Тематические торты'),
        ),
        'handmade-chocolate' => array(
            array('url' => '/birthday-cakes/', 'label' => 'Торты на день рождения на заказ'),
            array('url' => '/wedding-cakes/', 'label' => 'Свадебные торты на заказ в Москве'),
            array('url' => '/postcard-cakes/', 'label' => 'Торты-открытки'),
            array('url' => '/?work_category=classic_cakes#works', 'label' => 'Классические торты'),
            array('url' => '/?work_category=thematic_cakes#works', 'label' => 'Тематические торты'),
        ),
        'postcard-cakes' => array(
            array('url' => '/birthday-cakes/', 'label' => 'Торты на день рождения на заказ'),
            array('url' => '/wedding-cakes/', 'label' => 'Свадебные торты на заказ в Москве'),
            array('url' => '/handmade-chocolate/', 'label' => 'Шоколад ручной работы'),
            array('url' => '/?work_category=classic_cakes#works', 'label' => 'Классические торты'),
            array('url' => '/?work_category=thematic_cakes#works', 'label' => 'Тематические торты'),
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

/**
 * Render page-specific questions before the cross-links on each search landing.
 * Native details elements keep every answer in the initial HTML without JS.
 */
function antonova_landing_faq_html() {
    $faq_pages = array(
        'birthday-cakes' => array(
            'title' => 'Частые вопросы о тортах на день рождения',
            'items' => array(
                array(
                    'question' => 'Можно ли сделать торт с любимым персонажем ребёнка?',
                    'answer' => 'Пришлите изображение персонажа или пример понравившегося оформления. Я уточню, какие детали можно выполнить и как лучше разместить их на торте.',
                ),
                array(
                    'question' => 'Можно ли изготовить торт по фотографии из интернета?',
                    'answer' => 'Да, фотографию можно использовать как референс. Мы обсудим желаемые элементы и возможность их изготовления. Точное повторение каждой детали не гарантируется.',
                ),
                array(
                    'question' => 'Можно ли заказать торт с фотографией именинника?',
                    'answer' => 'Да, вы можете прислать фотографию именинника или другое изображение, которое хотите использовать в оформлении. Я уточню возможность нанесения фотографии, подходящий размер и стоимость декора. Итоговый вариант согласуем до изготовления торта.',
                ),
                array(
                    'question' => 'Можно ли добавить смешную надпись?',
                    'answer' => 'Да. Вы можете предложить собственную фразу или семейную шутку. Главное — согласовать текст и его размещение до изготовления.',
                ),
                array(
                    'question' => 'Можно ли заказать торт без сложного декора?',
                    'answer' => 'Да. Можно выбрать однотонное покрытие, небольшую поздравительную надпись или один декоративный акцент.',
                ),
                array(
                    'question' => 'Сколько торта нужно на 15 человек?',
                    'answer' => 'Ориентировочно 2,25–3 кг. Это расчёт количества десерта, а не подтверждение минимального веса заказа.',
                ),
                array(
                    'question' => 'Что делать, если нужен срочный заказ?',
                    'answer' => 'Напишите дату и покажите желаемый дизайн. Я проверю наличие свободного времени. При заказе за 2–3 дня предусмотрена доплата 20%.',
                ),
                array(
                    'question' => 'Поможете выбрать начинку?',
                    'answer' => 'Да. Расскажите, какие вкусы предпочитаете, и я предложу варианты из действующего ассортимента.',
                ),
                array(
                    'question' => 'Можно ли доставить торт к месту праздника?',
                    'answer' => 'Доставка по Москве и Московской области возможна. Сообщите адрес, дату и желаемое время получения, чтобы согласовать условия.',
                ),
            ),
        ),
        'wedding-cakes' => array(
            'title' => 'Частые вопросы о свадебных тортах',
            'items' => array(
                array(
                    'question' => 'У нас небольшая свадьба. Обязательно делать торт в несколько ярусов?',
                    'answer' => 'Нет, совсем необязательно. Красивый свадебный торт может быть и одноярусным. Главное, чтобы его хватило гостям и он подходил к вашему празднику. Подберём размер и оформление без лишнего объёма.',
                ),
                array(
                    'question' => 'Можно подобрать оформление торта под цвета нашей свадьбы?',
                    'answer' => 'Да, конечно. Если у вас уже есть свадебная палитра, фотографии букета или декора зала, присылайте. Подберём оттенки и украшения, чтобы торт красиво сочетался с общим оформлением.',
                ),
                array(
                    'question' => 'Хотим свадебный торт в несколько ярусов. Можно выбрать разные начинки?',
                    'answer' => 'Расскажите, какие вкусы вам нравятся. Я посмотрю, какие варианты можно сочетать с выбранным размером и конструкцией торта. Все детали обсудим до заказа.',
                ),
                array(
                    'question' => 'Можно добавить на торт наши инициалы или дату свадьбы?',
                    'answer' => 'Да, такое оформление можно обсудить. Например, сделать небольшую надпись, добавить инициалы или подобрать декор, который будет связан именно с вашей парой. Покажите, какой стиль вам нравится.',
                ),
                array(
                    'question' => 'Свадьба будет в ресторане за городом. Можно привезти торт туда?',
                    'answer' => 'Доставка по Москве и Московской области возможна. Напишите адрес ресторана и дату свадьбы. Я уточню возможность доставки, время и стоимость, чтобы вы заранее понимали все условия.',
                ),
                array(
                    'question' => 'Мы пока только выбираем торт, свадьба через несколько месяцев. Уже можно написать?',
                    'answer' => 'Конечно, и даже лучше обсудить всё заранее. Можно спокойно выбрать начинку, определиться с размером и оформлением. А когда основные детали будут согласованы, уточним стоимость и дату заказа.',
                ),
                array(
                    'question' => 'За сколько дней лучше заказать свадебный торт?',
                    'answer' => 'Лучше обратиться примерно за две недели до свадьбы, чтобы успеть согласовать оформление, начинку и доставку. Если времени осталось меньше, напишите мне — я уточню возможность выполнения заказа к нужной дате.',
                ),
            ),
        ),
        'handmade-chocolate' => array(
            'title' => 'Частые вопросы о шоколаде ручной работы',
            'items' => array(
                array(
                    'question' => 'Хочу подарить шоколад, но не знаю, какой лучше выбрать. Поможете?',
                    'answer' => 'Конечно! Расскажите, кому выбираете подарок и по какому поводу. Можно подобрать что-то небольшое, просто как знак внимания, или собрать более праздничный набор. Покажу варианты, и вместе определимся.',
                ),
                array(
                    'question' => 'Можно заказать несколько одинаковых наборов для коллег?',
                    'answer' => 'Напишите, сколько наборов вам нужно и к какой дате. Я посмотрю, какие варианты смогу подготовить в нужном количестве. Оформление тоже можно обсудить, чтобы подарки были в одном стиле.',
                ),
                array(
                    'question' => 'А можно выбрать цвет шоколада или сделать надпись?',
                    'answer' => 'Можно обсудить разные варианты оформления. Всё зависит от того, какое именно шоколадное изделие вы выберете. Если есть картинка или идея, присылайте — посмотрим, что получится сделать.',
                ),
                array(
                    'question' => 'Обязательно покупать целый набор или можно одну шоколадку?',
                    'answer' => 'У меня есть шоколадные изделия поштучно, стоимость начинается от 950 рублей за штуку. Напишите, какой вариант вам понравился, и я подскажу, что можно заказать отдельно.',
                ),
                array(
                    'question' => 'А шоколад будет в подарочной упаковке?',
                    'answer' => 'Упаковку обсуждаем при заказе. Она зависит от самого шоколада, количества изделий и выбранного оформления. Если готовите подарок, обязательно скажите об этом — подберём подходящий вариант.',
                ),
                array(
                    'question' => 'А если у человека аллергия на орехи, можно такой шоколад?',
                    'answer' => 'Обязательно предупредите меня об аллергии до оформления заказа. Я уточню состав выбранных изделий. В таком случае важно учитывать не только ингредиенты, но и возможные следы аллергенов, поэтому обещать их полное отсутствие без проверки я не буду.',
                ),
                array(
                    'question' => 'Хочу купить шоколад заранее. Как его правильно хранить до праздника?',
                    'answer' => 'Шоколад не любит жару, солнечный свет и резкие перепады температуры. Условия хранения и срок годности я уточню для выбранного изделия, потому что состав у шоколадных сладостей может отличаться.',
                ),
            ),
        ),
    );

    foreach ($faq_pages as $slug => $faq) {
        if (!is_page($slug)) {
            continue;
        }

        $items = '';
        foreach ($faq['items'] as $item) {
            $items .= '<details><summary><span class="landing-faq-question">' . esc_html($item['question']) . '</span><span class="landing-faq-icon" aria-hidden="true">+</span></summary><div class="landing-faq-answer"><p>' . esc_html($item['answer']) . '</p></div></details>';
        }

        return '<section class="landing-faq" aria-labelledby="landing-faq-title"><h2 id="landing-faq-title">' . esc_html($faq['title']) . '</h2><div class="landing-faq-list">' . $items . '</div></section>';
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
        header('X-Robots-Tag: noindex, follow', true);
        wp_safe_redirect(home_url('/#works'), 301);
        exit;
    }
}
add_action('template_redirect', 'antonova_redirect_removed_work_catalogue', 1);

/** Keep removed work URLs out of search if their redirect is ever disabled. */
function antonova_noindex_removed_work_catalogue($robots) {
    if (is_post_type_archive('antonova_work') || is_tax('antonova_work_category') || is_singular('antonova_work')) {
        unset($robots['index']);
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }

    return $robots;
}
add_filter('wp_robots', 'antonova_noindex_removed_work_catalogue', 99);

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

/**
 * Homepage portfolio filters are editorial groupings, independent from the
 * WordPress catalogue taxonomy. This keeps the existing 56 works and their
 * URLs intact while giving visitors clear, service-oriented choices.
 */
function antonova_home_work_categories($title) {
    $title = trim(wp_strip_all_tags((string) $title));
    $thematic = array(
        'Авторский тематический торт',
        'Тематический торт для любителя тенниса',
        'Торт в виде корабля',
        'Торт-карусель',
        'Торт с футбольными мячами',
        'Авторский праздничный торт',
        'Торт с тыквой',
        'Тематический торт для рыбака с фотографией',
        'Торт для любимых',
        'Яркий торт с ягодами и шоколадом',
        'Торт с печеньем и драже',
        'Белый двухъярусный торт с золотом',
    );
    $birthday = array(
        'Торт с мишкой и макарунами',
        'Торт с поздравлением ко дню рождения',
        'Черничный юбилейный торт на 50 лет',
        'Розовый торт на 12 лет',
        'Торт «Без паники, ты не старенький»',
        'Белый торт с розами на 30 лет',
        'Торт с ежевикой на 51 год',
        'Зелёный двухъярусный торт на день рождения',
        'Торт с клубникой и фигурками',
        'Торт на 50 лет',
    );
    $cake_card = array(
        'Новогодний торт с Дедом Морозом',
        'Торт с цветами',
        'Набор тортов с цветами',
        'Подарочный десерт Oksana Antonova',
    );
    $wedding = array(
        'Свадебный торт с цветами',
        'Розовый свадебный торт с цветами',
        'Белый трехъярусный свадебный торт с лебедями',
        'Белый трехъярусный свадебный торт с кружевом',
    );

    if (in_array($title, $thematic, true)) {
        return array('thematic_cakes');
    }
    if (in_array($title, $birthday, true)) {
        return array('birthday_cakes');
    }
    if (in_array($title, $cake_card, true)) {
        return array('cake_cards');
    }
    if (in_array($title, $wedding, true)) {
        return array('wedding_cakes');
    }
    if (preg_match('/шоколад|конфет|макарун|безе|десерт|кекс|чизкейк|печень|ёлка|пончиков/ui', $title)) {
        return array('chocolate_desserts');
    }
    if (preg_match('/торт/ui', $title)) {
        return array('classic_cakes');
    }

    // Works without a confirmed narrow category remain available under "Все
    // работы"; they are deliberately not guessed into a themed selection.
    return array();
}

/** Uses clearer captions for the approved thematic selection on the homepage. */
function antonova_home_work_display_title($title) {
    $title = trim(wp_strip_all_tags((string) $title));
    $titles = array(
        'Авторский тематический торт' => 'Торт в виде пня',
        'Тематический торт для любителя тенниса' => 'Торт по тематике настольного тенниса',
        'Торт с футбольными мячами' => 'Футбольный торт',
        'Торт с мишкой и макарунами' => 'Торт с мишкой',
        'Торт с цветочным декором' => 'Торт с малиновым декором',
    );

    return isset($titles[$title]) ? $titles[$title] : $title;
}

/**
 * Selected existing works from the specialised galleries that also belong in
 * the homepage portfolio. They remain server-rendered and use their original
 * files; no duplicate uploads or new records are created.
 */
function antonova_home_additional_works() {
    return array(
        array(
            'image' => antonova_theme_asset('assets/landing-galleries/wedding/wedding-swans.webp'),
            'title' => 'Белый трехъярусный свадебный торт с лебедями',
            'alt' => 'Белый трехъярусный свадебный торт с лебедями',
        ),
        array(
            'image' => antonova_theme_asset('assets/landing-galleries/wedding/wedding-lace.jpg'),
            'title' => 'Белый трехъярусный свадебный торт с кружевом',
            'alt' => 'Белый трехъярусный свадебный торт с кружевом',
        ),
        array(
            'image' => antonova_theme_asset('assets/optimized/cake-for-loved-ones.webp'),
            'title' => 'Торт для любимых',
            'alt' => 'Торт для любимых',
        ),
        array(
            'image' => antonova_theme_asset('assets/landing-galleries/birthday/' . rawurlencode('Торт на 50 лет.webp')),
            'title' => 'Торт на 50 лет',
            'alt' => 'Торт на 50 лет с золотистыми свечами и зелёным декором',
        ),
        array(
            'image' => antonova_theme_asset('assets/optimized/pumpkin-cake.webp'),
            'title' => 'Торт с тыквой',
            'alt' => 'Тематический торт с тыквой',
        ),
        array(
            'image' => antonova_theme_asset('assets/landing-galleries/birthday/' . rawurlencode('Торт на день рождения с фотографией.webp')),
            'title' => 'Тематический торт для рыбака с фотографией',
            'alt' => 'Тематический торт для рыбака с фотографией',
        ),
    );
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
        'title' => 'Торты на заказ в Москве — авторские десерты | Оксана Антонова',
        'description' => 'Торты и авторские десерты на заказ в Москве от Оксаны Антоновой. Классические и тематические торты, шоколад и домашние сладости. Фото, цены, самовывоз в Коптево и доставка по Москве и области.',
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
        $context['title'] = 'Торты на день рождения на заказ в Москве — фото и цены | Оксана Антонова';
        $context['description'] = 'Торты на день рождения на заказ в Москве от Оксаны Антоновой. Детские, тематические и юбилейные торты по фото. От 3 000 ₽/кг, декор отдельно. Доставка по Москве и области.';
        $context['url'] = get_permalink();
        return $context;
    }

    if (is_page('wedding-cakes')) {
        $context['title'] = 'Свадебные торты на заказ в Москве — фото работ и цены';
        $context['description'] = 'Свадебные торты на заказ в Москве от кондитера Оксаны Антоновой. Одноярусные и многоярусные торты, индивидуальный дизайн, выбор начинок. Фото работ, расчёт стоимости и доставка по Москве и области.';
        $context['url'] = get_permalink();
        return $context;
    }

    if (is_page('handmade-chocolate')) {
        $context['title'] = 'Шоколад ручной работы на заказ в Москве — фото и цены | Оксана Антонова';
        $context['description'] = 'Шоколад ручной работы на заказ в Москве от Оксаны Антоновой. Авторские плитки, подарочные наборы и фигурные изделия. Фото работ, цены от 950 ₽/шт., индивидуальное оформление и доставка по Москве и области.';
        $context['url'] = home_url('/handmade-chocolate/');
        return $context;
    }

    if (is_page('postcard-cakes')) {
        $context['title'] = 'Торт-открытка на заказ в Москве | Оксана Антонова';
        $context['description'] = 'Торт-открытка: красивый торт-открытка для праздников и любимых людей. Заказ в Москве.';
        $context['url'] = home_url('/postcard-cakes/');
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

/** Compact, server-rendered chocolate landing content. */
function antonova_chocolate_cta_html($max_url, $phone_url, $placement) {
    $max_label = 'price' === $placement ? 'Рассчитать стоимость шоколада' : 'Заказать шоколад в MAX';
    $phone_label = 'price' === $placement ? 'Позвонить и уточнить стоимость' : 'Позвонить';
    return '<div class="chocolate-cta-group"><a class="button" href="' . esc_url($max_url) . '" target="_blank" rel="noopener noreferrer" data-chocolate-max="' . esc_attr($placement) . '">' . esc_html($max_label) . '</a><a class="button button-secondary" href="' . esc_url($phone_url) . '" data-chocolate-phone="' . esc_attr($placement) . '">' . esc_html($phone_label) . '</a></div>';
}

function antonova_chocolate_price_table_html() {
    $rows = array(array('Отдельное шоколадное изделие', 'От 950 ₽/шт.'), array('Подарочный набор', 'По составу и количеству'), array('Тематическая композиция', 'Индивидуальный расчёт'), array('Персональное оформление', 'По согласованию'), array('Подарочная упаковка', 'По выбранному варианту'), array('Доставка', 'По адресу и условиям получения'));
    $html = '<div class="chocolate-table-wrap"><table class="chocolate-price-table"><thead><tr><th>Вариант заказа</th><th>Стоимость</th></tr></thead><tbody>';
    foreach ($rows as $row) { $html .= '<tr><td>' . esc_html($row[0]) . '</td><td>' . esc_html($row[1]) . '</td></tr>'; }
    return $html . '</tbody></table></div>';
}

function antonova_chocolate_accordion_html($id, $title, $body) {
    return '<section class="chocolate-sub-accordion"><h3><button type="button" data-chocolate-accordion-button aria-expanded="true" aria-controls="' . esc_attr($id) . '">' . esc_html($title) . '<span aria-hidden="true">+</span></button></h3><div id="' . esc_attr($id) . '" data-chocolate-accordion-content>' . $body . '</div></section>';
}

function antonova_chocolate_topic_sections_html() {
    $sections = array(
        array('Какой шоколад ручной работы можно заказать', 'В портфолио есть шоколадные плитки, подарочные наборы и фигурные изделия. Подробнее о каждом формате можно узнать ниже.', array(array('Шоколадные плитки ручной работы', 'В портфолио есть шоколадные плитки с изображениями павлина, цветов, зимних сюжетов, рельефными звёздами и золотистыми декоративными элементами. Некоторые плитки представлены в индивидуальных подарочных коробках с фирменной лентой. Если вам понравилась конкретная работа, пришлите её фотографию: обсудим состав, доступное оформление, упаковку и стоимость.'), array('Подарочные наборы шоколада', 'Подарочный набор позволяет объединить несколько изделий в одной коробке. Среди моих работ есть наборы с плитками, небольшими фигурными шоколадными изделиями и конфетами. Состав и количество элементов согласовываются индивидуально.'), array('Фигурные и тематические изделия', 'Фигурный шоколад может стать самостоятельным тематическим подарком. В портфолио представлены новогодняя шоколадная ёлка и шахматная композиция с объёмными фигурами и золотистым декором. Возможность повторения оформления, размеры, состав, комплектность и стоимость уточняются при заказе.'))),
        array('Шоколад ручной работы в подарок', 'Шоколад можно выбрать для поздравления близкого человека, Нового года, Дня учителя и других праздников. В портфолио есть подарочные изделия с тематическим оформлением.', array(array('Шоколадные подарки для близких', 'Для мамы, папы, любимого человека или другого близкого можно подобрать набор с персональным поздравлением. В портфолио есть набор «Любимый папа» — шоколадное ассорти в круглой красной коробке с поздравительными табличками и фигурками рук взрослого и ребёнка. Возможность повторения состава и оформления уточняется при заказе.'), array('Новогодний шоколад ручной работы', 'К Новому году можно выбрать шоколадные плитки с зимними изображениями или фигурные праздничные изделия. В моих работах есть шоколадная ёлка, плитки с Дедом Морозом, Снегурочкой и другими зимними сюжетами.'), array('Шоколад ко Дню учителя', 'В портфолио есть подарочные шоколадные изделия с оформлением ко Дню учителя: шоколадные кольца и небольшие изделия в коробках с поздравительной лентой «С Днём учителя».'))),
        array('Индивидуальное оформление шоколада', 'Вы можете выбрать вариант из портфолио или предложить собственную идею. Возможность выполнения рисунка, поздравления или декоративных элементов согласуем заранее.', array(array('Рисунки и декоративные элементы', 'Для оформления можно использовать цветочные мотивы, праздничные изображения, рельефные звёзды и другие элементы, представленные в моих работах. Пришлите фотографию понравившегося варианта — я уточню возможность выполнения выбранного рисунка или декора.'), array('Персональные надписи и тематические композиции', 'Шоколадный подарок можно дополнить коротким поздравлением или тематическими элементами, связанными с увлечением получателя. Перед изготовлением согласуем возможности оформления, размеры и стоимость.'), array('Подарочная упаковка', 'Шоколадные плитки и наборы можно оформить в подарочную коробку. В портфолио есть индивидуальные коробки для плиток и наборы с фирменными лентами. Подходящий размер упаковки и её стоимость зависят от количества и вида изделий.'))),
    );
    $html = '';
    foreach ($sections as $section_index => $section) { $html .= '<section class="chocolate-copy-section"><h2>' . esc_html($section[0]) . '</h2><p>' . esc_html($section[1]) . '</p>'; foreach ($section[2] as $item_index => $item) { $html .= antonova_chocolate_accordion_html('chocolate-topic-' . $section_index . '-' . $item_index, $item[0], '<p>' . esc_html($item[1]) . '</p>'); } $html .= '</section>'; }
    return $html;
}

function antonova_chocolate_faq_html() {
    $items = array(array('Можно ли заказать одну шоколадную плитку, а не целый набор?', 'Да, отдельные шоколадные изделия доступны поштучно. Сообщите, какая работа понравилась, и я уточню её наличие в доступном ассортименте и стоимость заказа.'), array('Какие подарочные наборы можно заказать?', 'В портфолио есть коробки с плитками, небольшими шоколадными изделиями и тематическими композициями. Состав, количество и упаковку согласуем индивидуально.'), array('Можно ли выбрать рисунок или добавить поздравление?', 'Можно обсудить индивидуальное оформление. Пришлите изображение или текст поздравления, а я уточню возможность его выполнения.'), array('Сколько хранится шоколад ручной работы?', 'Срок годности зависит от состава и особенностей изделия. При оформлении заказа я сообщу срок годности и условия хранения конкретного шоколада.'), array('Как хранить шоколад до праздника?', 'Избегайте нагревания, солнечного света, влаги и резких перепадов температуры. Точные условия зависят от выбранного изделия.'), array('Можно ли заказать новогодний шоколад или несколько одинаковых подарков?', 'Возможность такого заказа уточняется индивидуально. Сообщите количество, дату и желаемое оформление.'), array('Что делать, если у получателя аллергия на орехи?', 'Обязательно предупредите меня до заказа. Я уточню состав изделия и возможные следы аллергенов. Без проверки гарантировать их полное отсутствие нельзя.'), array('Есть ли доставка по Москве и Московской области?', 'Да, доставка возможна. Условия, стоимость и время получения согласуем по адресу заказа.'));
    $html = '<section class="landing-faq"><h2>Частые вопросы о шоколаде ручной работы</h2><div class="landing-faq-list">';
    foreach ($items as $item) { $html .= '<details><summary><span class="landing-faq-question">' . esc_html($item[0]) . '</span><span class="landing-faq-icon" aria-hidden="true">+</span></summary><div class="landing-faq-answer"><p>' . esc_html($item[1]) . '</p></div></details>'; }
    return $html . '</div></section>';
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
    if (is_page('wedding-cakes')) {
        return antonova_theme_asset('assets/landing-galleries/wedding/wedding-swans-social-preview.jpg');
    }

    if (is_page('birthday-cakes')) {
        return antonova_theme_asset('assets/landing-galleries/birthday/birthday-cake-social-preview.jpg');
    }

    if (is_page('handmade-chocolate')) {
        $page_id = get_queried_object_id();
        $featured_image = $page_id ? get_the_post_thumbnail_url($page_id, 'full') : '';
        if ($featured_image) {
            return $featured_image;
        }

        // This page is file-based, so it may deliberately have no WordPress
        // featured image. Keep a stable, relevant social preview in that case.
        return antonova_theme_asset(
            'assets/landing-galleries/chocolate/' . rawurlencode('#U0415#U043b#U043e#U0447#U043a#U0430.jpg')
        );
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
        $data['name'] = 'Торты на заказ в Москве — авторские десерты | Оксана Антонова';
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
    $is_square_landing_preview = is_page('wedding-cakes') || is_page('birthday-cakes');
    echo '<meta property="og:image:width" content="' . ($is_square_landing_preview ? '1254' : '675') . '">' . "\n";
    echo '<meta property="og:image:height" content="' . ($is_square_landing_preview ? '1254' : '1200') . '">' . "\n";
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
    $meta_title = is_front_page() ? $context['title'] : antonova_seo_limit($context['title'], 60);
    $meta_description = is_front_page() ? $context['description'] : antonova_seo_limit($context['description'], 160);
    echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($context['url']) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($context['type']) . '">' . "\n";
    echo '<meta property="og:locale" content="ru_RU">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($meta_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($meta_description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($context['url']) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($meta_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($meta_description) . '">' . "\n";
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    }
}
add_action('wp_head', 'antonova_render_seo_meta', 2);

/** Render FAQPage schema from the same code-defined questions shown on the homepage. */
function antonova_render_faq_schema() {
    if (!is_front_page()) {
        return;
    }

    $main_entity = array();
    foreach (antonova_get_faq() as $item) {
        $question = sanitize_text_field($item['question'] ?? '');
        $answer = sanitize_textarea_field($item['answer'] ?? '');
        if ('' === $question || '' === $answer) {
            continue;
        }

        $main_entity[] = array(
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $answer,
            ),
        );
    }

    if (empty($main_entity)) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        '@id' => home_url('/#faq'),
        'url' => home_url('/'),
        'mainEntity' => $main_entity,
    );

    echo '<script type="application/ld+json" id="antonova-faq-schema">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>' . "\n";
}
add_action('wp_head', 'antonova_render_faq_schema', 31);

/**
 * The page currently has no visible FAQ content. Keeping its questions in one
 * function lets a future editor add matching on-page FAQ and valid FAQPage
 * schema together, without publishing hidden or invented structured data.
 */
function antonova_postcard_cakes_faq() {
    return apply_filters('antonova_postcard_cakes_faq', array());
}

/** Render server-side breadcrumb and future FAQ schema for /postcard-cakes/. */
function antonova_render_postcard_cakes_schema() {
    if (!is_page('postcard-cakes')) {
        return;
    }

    $url = home_url('/postcard-cakes/');
    $graph = array(
        array(
            '@type' => 'BreadcrumbList',
            '@id' => $url . '#breadcrumb',
            'itemListElement' => array(
                array('@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => home_url('/')),
                array('@type' => 'ListItem', 'position' => 2, 'name' => 'Торты-открытки', 'item' => $url),
            ),
        ),
    );

    $main_entity = array();
    foreach (antonova_postcard_cakes_faq() as $item) {
        $question = sanitize_text_field($item['question'] ?? '');
        $answer = sanitize_textarea_field($item['answer'] ?? '');
        if ('' === $question || '' === $answer) {
            continue;
        }
        $main_entity[] = array(
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => array('@type' => 'Answer', 'text' => $answer),
        );
    }

    if (!empty($main_entity)) {
        $graph[] = array(
            '@type' => 'FAQPage',
            '@id' => $url . '#faq',
            'mainEntity' => $main_entity,
        );
    }

    $schema = array('@context' => 'https://schema.org', '@graph' => $graph);
    echo '<script type="application/ld+json" id="antonova-postcard-cakes-schema">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>' . "\n";
}
add_action('wp_head', 'antonova_render_postcard_cakes_schema', 32);

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
 * Read image files directly from a theme folder. The homepage portfolio is
 * deliberately code-driven and does not depend on Media Library records or
 * client-side JavaScript, so every image is present in the initial HTML.
 */
function antonova_theme_folder_images($relative_directory) {
    $relative_directory = trim(str_replace('\\', '/', (string) $relative_directory), '/');
    if ('' === $relative_directory || false !== strpos($relative_directory, '..')) {
        return array();
    }

    $directory = trailingslashit(get_template_directory()) . str_replace('/', DIRECTORY_SEPARATOR, $relative_directory);
    if (!is_dir($directory)) {
        return array();
    }

    $files = glob($directory . '/*.{webp,WEBP,jpg,JPG,jpeg,JPEG,png,PNG,avif,AVIF}', GLOB_BRACE);
    if (empty($files)) {
        return array();
    }

    usort($files, static function ($left, $right) {
        return strnatcasecmp(wp_basename($left), wp_basename($right));
    });

    $images = array();
    foreach ($files as $file) {
        $filename = wp_basename($file);
        $relative_file = $relative_directory . '/' . $filename;
        $encoded_file = implode('/', array_map('rawurlencode', explode('/', $relative_file)));
        $dimensions = wp_getimagesize($file);
        $title = trim(pathinfo($filename, PATHINFO_FILENAME));

        $images[] = array(
            'image' => antonova_theme_asset($encoded_file),
            'title' => '' !== $title ? $title : 'Авторская работа',
            'alt' => '' !== $title ? $title : 'Авторская работа',
            'filename' => $filename,
            'width' => !empty($dimensions[0]) ? (int) $dimensions[0] : 1200,
            'height' => !empty($dimensions[1]) ? (int) $dimensions[1] : 1600,
        );
    }

    return $images;
}

/** Return the reviewed titles, alt text and lightbox descriptions for homepage works. */
function antonova_home_work_metadata() {
    static $metadata = null;

    if (null !== $metadata) {
        return $metadata;
    }

    $metadata_file = get_template_directory() . '/inc/homepage-work-metadata.php';
    $metadata = is_file($metadata_file) ? require $metadata_file : array();

    return is_array($metadata) ? $metadata : array();
}

/** Return every homepage work from its category folder as one portfolio. */
function antonova_home_file_based_works() {
    $categories = array(
        'classic_cakes' => 'Классические торты',
        'thematic_cakes' => 'Тематические торты',
        'wedding_cakes' => 'Свадебные торты',
        'birthday_cakes' => 'Торты на день рождения',
        'cake_cards' => 'Торты-открытки',
        'chocolate_desserts' => 'Шоколад и десерты',
    );

    $metadata = antonova_home_work_metadata();
    $works = array();
    foreach ($categories as $category => $folder) {
        $images = antonova_theme_folder_images('assets/homepage-all-works/' . $folder);
        foreach ($images as $image) {
            $work_metadata = isset($metadata[$image['filename']]) && is_array($metadata[$image['filename']])
                ? $metadata[$image['filename']]
                : array();

            if (!empty($work_metadata['title'])) {
                $image['title'] = $work_metadata['title'];
            }
            if (!empty($work_metadata['alt'])) {
                $image['alt'] = $work_metadata['alt'];
            }
            $image['description'] = !empty($work_metadata['description']) ? $work_metadata['description'] : '';
            $image['order'] = isset($work_metadata['order']) ? (int) $work_metadata['order'] : PHP_INT_MAX;
            $image['category'] = $category;
            $works[] = $image;
        }
    }

    usort($works, static function ($left, $right) {
        if ($left['order'] !== $right['order']) {
            return $left['order'] <=> $right['order'];
        }
        return strnatcasecmp($left['filename'], $right['filename']);
    });

    return $works;
}

/** Return the first image from a code-managed homepage cover folder. */
function antonova_home_category_cover($folder, $fallback) {
    $images = antonova_theme_folder_images('assets/homepage-category-covers/' . $folder);
    return !empty($images) ? $images[0] : array(
        'image' => $fallback,
        'width' => 1200,
        'height' => 1800,
    );
}

/**
 * Build a responsive srcset for Media Library images and optimized theme
 * variants that share the "name-width.ext" file convention.
 */
function antonova_responsive_image_srcset($url) {
    static $cache = array();

    if (!is_string($url) || '' === $url) {
        return '';
    }
    if (isset($cache[$url])) {
        return $cache[$url];
    }

    $attachment_id = attachment_url_to_postid($url);
    if ($attachment_id) {
        $srcset = wp_get_attachment_image_srcset($attachment_id, 'full');
        $cache[$url] = is_string($srcset) ? $srcset : '';
        return $cache[$url];
    }

    $theme_uri = trailingslashit(get_template_directory_uri());
    if (0 !== strpos($url, $theme_uri)) {
        $cache[$url] = '';
        return '';
    }

    $relative_path = rawurldecode(substr($url, strlen($theme_uri)));
    $local_path = trailingslashit(get_template_directory()) . ltrim($relative_path, '/');
    $path_info = pathinfo($local_path);
    if (empty($path_info['filename']) || empty($path_info['extension']) || !preg_match('/^(.+)-\d+$/', $path_info['filename'], $matches)) {
        $cache[$url] = '';
        return '';
    }

    $pattern = trailingslashit($path_info['dirname']) . $matches[1] . '-[0-9]*.' . $path_info['extension'];
    $sources = array();
    foreach ((array) glob($pattern) as $candidate) {
        $dimensions = wp_getimagesize($candidate);
        if (empty($dimensions[0])) {
            continue;
        }

        $candidate_relative = str_replace('\\', '/', substr($candidate, strlen(trailingslashit(get_template_directory()))));
        $candidate_url = $theme_uri . implode('/', array_map('rawurlencode', explode('/', $candidate_relative)));
        $sources[(int) $dimensions[0]] = esc_url_raw($candidate_url) . ' ' . (int) $dimensions[0] . 'w';
    }

    if (count($sources) < 2) {
        $cache[$url] = '';
        return '';
    }

    ksort($sources, SORT_NUMERIC);
    $cache[$url] = implode(', ', $sources);
    return $cache[$url];
}

/**
 * Serve optimized theme copies for selected legacy Media Library images.
 * The replacement happens while rendering HTML, so images remain available
 * when JavaScript is disabled.
 */
function antonova_optimized_media_url($url) {
    if (!is_string($url) || '' === $url) {
        return $url;
    }

    $path = wp_parse_url($url, PHP_URL_PATH);
    $filename = is_string($path) ? rawurldecode(wp_basename($path)) : '';
    $replacements = array(
        'Изображение-ChatGPT-27-сент.-2026-г.-21_45_36.png' => 'assets/optimized/wp-medovik-classic.webp',
        'Изображение-ChatGPT-27-сент.-2026-г.-21_43_14.png' => 'assets/optimized/wp-medovik-author.webp',
        'Изображение-ChatGPT-27-сент.-2026-г.-18_34_09.png' => 'assets/optimized/wp-oksana-about.webp',
    );

    return isset($replacements[$filename]) ? antonova_theme_asset($replacements[$filename]) : $url;
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
        'Набор тортов с цветами.webp' => 'Набор тортов с цветами',
        'Торт с цветами.webp' => 'Торт с цветами',
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

/**
 * Landing-page galleries are file based: adding an approved image to the
 * relevant folder does not depend on a page builder or a third-party slider.
 * Every image is rendered in the page HTML for search engines and no-JS users.
 */
function antonova_landing_gallery_data($gallery_key) {
    $galleries = array(
        'wedding' => array(
            'id' => 'wedding-cakes-gallery',
            'label' => 'Фотографии свадебных тортов',
            'items' => array(
                array(
                    'file' => 'wedding-swans.webp',
                    'alt' => 'Белый трехъярусный свадебный торт с лебедями и рельефным цветочным декором',
                    'caption' => 'Белый трехъярусный свадебный торт с лебедями',
                    'description' => 'Белая композиция с рельефным растительным узором, фигурками лебедей и крупным декоративным цветком между уровнями.',
                    'price' => 'от 3 000 ₽/кг',
                ),
                array(
                    'file' => 'wedding-pink-flowers.webp',
                    'alt' => 'Розовый трехъярусный свадебный торт с объёмными цветами и фактурным покрытием',
                    'caption' => 'Розовый трехъярусный свадебный торт с цветами',
                    'description' => 'Торт в нежно-розовой гамме с объёмными цветочными композициями разных оттенков и фактурным покрытием.',
                    'price' => 'от 3 000 ₽/кг',
                ),
                array(
                    'file' => 'wedding-rings.webp',
                    'alt' => 'Белый одноярусный свадебный торт с золотистыми кольцами и вафельным декором',
                    'caption' => 'Белый одноярусный свадебный торт с кольцами',
                    'description' => 'Светлое оформление с объёмными белыми элементами и золотистыми кольцами.',
                    'price' => 'от 3 000 ₽/кг',
                ),
                array(
                    'file' => 'wedding-lace.jpg',
                    'alt' => 'Белый трехъярусный свадебный торт с кружевным декором и фигурной опорой',
                    'caption' => 'Белый трехъярусный свадебный торт с кружевом',
                    'description' => 'Белый торт с объёмным ажурным орнаментом и изогнутой опорой между уровнями.',
                    'price' => 'от 3 000 ₽/кг',
                ),
            ),
        ),
        'birthday' => array(
            'id' => 'birthday-cakes-gallery',
            'label' => 'Фотографии тортов на день рождения',
            'items' => array(
                array(
                    'file' => '12-ezgif.com-jpg-to-webp-converter.webp',
                    'alt' => 'Торт на 12 лет с белым мишкой, золотистыми цифрами и розовым декором',
                    'caption' => 'Торт на 12 лет с мишкой',
                    'description' => 'Белый торт с розовым декором, золотистыми цифрами 12 и фигуркой мишки.',
                    'price' => 'от 3 900 ₽ за торт',
                ),
                array(
                    'file' => '19-ezgif.com-jpg-to-webp-converter.webp',
                    'alt' => 'Белый торт на 19 лет с шуточной надписью и красными сердечками',
                    'caption' => 'Торт на 19 лет с шуточной надписью',
                    'description' => 'Белый торт с необычным поздравлением и красными сердечками.',
                    'price' => 'от 3 200 ₽ за торт',
                ),
                array(
                    'file' => '30-ezgif.com-jpg-to-webp-converter.webp',
                    'alt' => 'Торт на 30 лет с белыми розами и золотистой надписью Hello 30',
                    'caption' => 'Торт на 30 лет с белыми розами',
                    'description' => 'Светлый торт с объёмными розами и надписью Hello 30.',
                    'price' => 'от 3 800 ₽ за торт',
                ),
                array(
                    'file' => '51-ezgif.com-jpg-to-webp-converter.webp',
                    'alt' => 'Торт на 51 год с ежевикой, голубикой и золотистыми цифрами',
                    'caption' => 'Торт на 51 год с ягодами',
                    'description' => 'Белый торт с ягодами, цифрами 51 и поздравлением.',
                    'price' => 'от 3 600 ₽ за торт',
                ),
                array(
                    'file' => 'ezgif.com-jpg-to-webp-converter (2).webp',
                    'alt' => 'Зелёный двухъярусный торт с золотистым декором и надписью Happy Birthday',
                    'caption' => 'Зелёный двухъярусный торт',
                    'description' => 'Зелёная двухъярусная композиция с золотистыми элементами.',
                    'price' => 'от 5 000 ₽ за торт',
                ),
                array(
                    'file' => 'ezgif.com-jpg-to-webp-converter (9).webp',
                    'alt' => 'Праздничный торт с розовым мишкой, цветными шарами и надписью',
                    'caption' => 'Торт с мишкой',
                    'description' => 'Торт с розовым мишкой и разноцветными украшениями.',
                    'price' => 'от 3 500 ₽ за торт',
                ),
                array(
                    'file' => 'Зеленый торт на день рождения.webp',
                    'alt' => 'Зелёный одноярусный торт с фактурным покрытием и золотистым топпером',
                    'caption' => 'Зелёный одноярусный торт',
                    'description' => 'Фактурное зелёное оформление с золотистым поздравлением.',
                    'price' => 'от 3 000 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на 50 лет.webp',
                    'alt' => 'Торт на 50 лет с золотистыми свечами и зелёным декором',
                    'caption' => 'Торт на 50 лет',
                    'description' => 'Светло-зелёный юбилейный торт с золотистыми цифрами.',
                    'price' => 'от 4 000 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на день рождения именной.webp',
                    'alt' => 'Двухъярусный торт для Никиты с картами, фишками и футбольным мячом',
                    'caption' => 'Именной торт для Никиты',
                    'description' => 'Красно-белый торт с игровой и спортивной тематикой.',
                    'price' => 'от 4 800 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на день рождения с Карлсоном.webp',
                    'alt' => 'Белый торт с Карлсоном, клубникой и поздравительными надписями',
                    'caption' => 'Торт с Карлсоном',
                    'description' => 'Белый торт с клубникой, Карлсоном и поздравлениями.',
                    'price' => 'от 4 200 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на день рождения с лего.webp',
                    'alt' => 'Детский торт с разноцветными деталями конструктора и фигурками',
                    'caption' => 'Торт в стиле конструктора',
                    'description' => 'Детский торт с разноцветными деталями и фигурками.',
                    'price' => 'от 4 300 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на день рождения с фотографией.webp',
                    'alt' => 'Тематический торт для рыбака с фотографией и зелёным декором',
                    'caption' => 'Тематический торт для рыбака с фотографией',
                    'description' => 'Зелёный тематический торт с фотографией, рыбацкими деталями и оформлением в виде леса.',
                    'price' => 'от 4 500 ₽ за торт',
                ),
                array(
                    'file' => 'Торт на юбилей с черникой.webp',
                    'alt' => 'Прямоугольный торт на 50 лет с ежевикой и золотистыми цифрами',
                    'caption' => 'Торт на 50 лет с ежевикой',
                    'description' => 'Прямоугольный белый торт с ежевикой и цифрами 50.',
                    'price' => 'от 4 100 ₽ за торт',
                ),
            ),
            'empty_message' => 'Фотографии тортов на день рождения будут добавлены скоро.',
        ),
        'chocolate' => array(
            'id' => 'handmade-chocolate-gallery',
            'label' => 'Фотографии шоколада ручной работы',
            'show_details' => true,
            'items' => array(),
            'empty_message' => 'Фотографии шоколада ручной работы будут добавлены скоро.',
        ),
    );

    if (!isset($galleries[$gallery_key])) {
        return array();
    }

    $gallery = $galleries[$gallery_key];
    $gallery_item_meta = array(
        'chocolate' => array(
            '#U0415#U043b#U043e#U0447#U043a#U0430.jpg' => array('Шоколадная новогодняя ёлка', 'Фигурная шоколадная ёлка с праздничными украшениями'),
            '123-ezgif.com-jpg-to-webp-converter.webp' => array('Шоколадные плитки с цветочными рисунками', 'Шоколадные плитки с павлином, цветами и декоративной клеткой'),
            '2-ezgif.com-jpg-to-webp-converter (1).webp' => array('Шоколад с рельефом и добавками', 'Подарочные коробки с рельефным шоколадом и декоративными добавками'),
            '2-ezgif.com-jpg-to-webp-converter (4).webp' => array('Шоколадный десерт с прослойкой', 'Разрезанные шоколадные десерты со светлой основой и прослойкой'),
            '2-ezgif.com-jpg-to-webp-converter (5).webp' => array('Шоколадный подарок ко Дню учителя', 'Подарочная коробка с шоколадными кольцами и лентой С Днём учителя'),
            '2-ezgif.com-jpg-to-webp-converter (6).webp' => array('Шоколадный набор ко Дню учителя', 'Подарочный набор шоколадных изделий ко Дню учителя'),
            '3-ezgif.com-jpg-to-webp-converter (1).webp' => array('Шоколад со звёздами и фруктовым декором', 'Шоколадные плитки с золотистыми звёздами и фруктовым декором'),
            '3-ezgif.com-jpg-to-webp-converter.webp' => array('Плитка с золотистым рельефом', 'Шоколадная плитка с объёмным золотистым рельефом'),
            '4-ezgif.com-jpg-to-webp-converter.webp' => array('Шоколадная плитка с фисташками', 'Шоколадная плитка с фисташками и зелёной начинкой'),
            'ezgif.com-jpg-to-webp-converter (13).webp' => array('Новогодние шоколадные плитки', 'Подарочные шоколадные плитки с Дедом Морозом и зимними сюжетами'),
            'ezgif.com-jpg-to-webp-converter (21).webp' => array('Подарочные наборы с рельефным декором', 'Подарочные наборы с золотистым рельефным шоколадом и красным декором'),
            'Шоколадка с павлином.webp' => array('Шоколадная плитка с павлином', 'Подарочная шоколадная плитка с павлином и цветами'),
            'Шоколад ручной работы со звездами.webp' => array('Шоколадные плитки со звёздами', 'Шоколадные плитки с объёмными золотистыми звёздами'),
            'Шоколад лучший папа.webp' => array('Подарочный набор «Любимый папа»', 'Подарочный шоколадный набор Любимый папа в красной коробке с фирменной лентой'),
            'Набор шоколадных конфет.webp' => array('Набор «Любимый папа» — содержимое', 'Шоколадный набор Любимый папа с фигурками рук и поздравительной надписью'),
            'Шоколадные конфеты шахматная доска.webp' => array('Шахматная композиция', 'Шахматная композиция со светлыми и коричневыми фигурами и золотистым декором'),
        ),
    );
    $gallery_dir = trailingslashit(get_template_directory()) . 'assets/landing-galleries/' . $gallery_key;
    $files = glob($gallery_dir . '/*.{jpg,jpeg,png,webp,avif,JPG,JPEG,PNG,WEBP,AVIF}', GLOB_BRACE);
    $known_files = array();

    foreach ($gallery['items'] as $item) {
        $known_files[$item['file']] = true;
    }

    if (is_array($files)) {
        natcasesort($files);
        foreach ($files as $path) {
            $filename = wp_basename($path);
            // Social previews are intentionally outside visitor-facing galleries.
            if (preg_match('/-social-preview\\.jpe?g$/i', $filename)) {
                continue;
            }
            if (isset($known_files[$filename])) {
                continue;
            }

            $name = pathinfo($filename, PATHINFO_FILENAME);
            $name = preg_replace('/[-_]+/u', ' ', $name);
            $name = preg_replace('/\s+/u', ' ', trim($name));
            $has_human_name = !preg_match('/^(?:\d+|ezgif|converter|#u)/iu', $name);
            $fallback = array(
                'birthday' => 'Авторский торт на день рождения',
                'chocolate' => 'Шоколад ручной работы',
                'wedding' => 'Авторский свадебный торт',
            );
            $description = $has_human_name ? $name : $fallback[$gallery_key];

            $item_meta = isset($gallery_item_meta[$gallery_key][$filename]) ? $gallery_item_meta[$gallery_key][$filename] : array($description, $description);
            $gallery['items'][] = array(
                'file' => $filename,
                'alt' => $item_meta[1],
                'caption' => $item_meta[0],
            );
        }
    }

    foreach ($gallery['items'] as $index => $item) {
        $gallery['items'][$index]['src'] = antonova_theme_asset(
            'assets/landing-galleries/' . $gallery_key . '/' . rawurlencode($item['file'])
        );
    }

    return $gallery;
}

/** Render the reusable gallery in the original server HTML. */
function antonova_landing_thumbnail_gallery_html($gallery_key) {
    $gallery = antonova_landing_gallery_data($gallery_key);
    if (empty($gallery)) {
        return '';
    }

    ob_start();
    get_template_part('template-parts/landing-thumbnail-gallery', null, array('gallery' => $gallery));
    return ob_get_clean();
}

/** Videos placed in this folder are rendered directly in the page HTML. */
function antonova_handmade_chocolate_video_items() {
    $directory = trailingslashit(get_template_directory()) . 'assets/videos/handmade-chocolate';
    $files = glob($directory . '/*.{mp4,webm,ogv,ogg,mov,MP4,WEBM,OGV,OGG,MOV}', GLOB_BRACE);
    if (!is_array($files)) {
        return array();
    }

    $mime_types = array(
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogv' => 'video/ogg',
        'ogg' => 'video/ogg',
        'mov' => 'video/quicktime',
    );
    $format_priority = array(
        'mp4' => 1,
        'webm' => 2,
        'ogv' => 3,
        'ogg' => 3,
        'mov' => 4,
    );
    $preferred_files = array();

    // Keep the uploaded original, but render only the best browser-ready copy
    // when two files share a name (for example IMG_3132.MOV + IMG_3132.mp4).
    foreach ($files as $path) {
        $filename = wp_basename($path);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $basename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
        if (!isset($mime_types[$extension])) {
            continue;
        }

        if (
            !isset($preferred_files[$basename])
            || $format_priority[$extension] < $preferred_files[$basename]['priority']
        ) {
            $preferred_files[$basename] = array(
                'path' => $path,
                'priority' => $format_priority[$extension],
            );
        }
    }

    ksort($preferred_files, SORT_NATURAL | SORT_FLAG_CASE);
    $items = array();

    foreach (array_values($preferred_files) as $index => $preferred_file) {
        $path = $preferred_file['path'];
        $filename = wp_basename($path);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $items[] = array(
            'src' => antonova_theme_asset('assets/videos/handmade-chocolate/' . rawurlencode($filename)),
            'type' => $mime_types[$extension],
            'title' => 'Создание шоколада ручной работы — видео ' . ($index + 1),
        );
    }

    return $items;
}

/** HTML-first video carousel: every source exists in the response without JavaScript. */
function antonova_handmade_chocolate_video_slider_html() {
    $videos = antonova_handmade_chocolate_video_items();
    if (empty($videos)) {
        return '<div class="chocolate-video-slot" data-chocolate-video-slot role="region" aria-label="Место для видео о создании шоколада"><p>Видео будет добавлено здесь.</p></div>';
    }

    ob_start();
    ?>
    <div class="chocolate-video-slider" data-chocolate-video-slider aria-label="Видео о создании шоколада ручной работы">
        <div class="chocolate-video-panels">
            <?php foreach ($videos as $index => $video) : ?>
                <?php
                $choice_id = 'chocolate-video-choice-' . ($index + 1);
                $previous_index = (0 === $index) ? count($videos) - 1 : $index - 1;
                $next_index = (count($videos) - 1 === $index) ? 0 : $index + 1;
                ?>
                <input class="chocolate-video-choice screen-reader-text" type="radio" name="chocolate-video-selection" id="<?php echo esc_attr($choice_id); ?>" aria-label="<?php echo esc_attr('Показать: ' . $video['title']); ?>" <?php checked(0, $index); ?>>
                <figure class="chocolate-video-panel" data-chocolate-video-panel>
                    <label class="chocolate-video-arrow" for="<?php echo esc_attr('chocolate-video-choice-' . ($previous_index + 1)); ?>" aria-label="Предыдущее видео"><span aria-hidden="true">←</span></label>
                    <video controls preload="metadata" playsinline aria-label="<?php echo esc_attr($video['title']); ?>">
                        <source src="<?php echo esc_url($video['src']); ?>" type="<?php echo esc_attr($video['type']); ?>">
                        Ваш браузер не поддерживает воспроизведение видео.
                    </video>
                    <label class="chocolate-video-arrow" for="<?php echo esc_attr('chocolate-video-choice-' . ($next_index + 1)); ?>" aria-label="Следующее видео"><span aria-hidden="true">→</span></label>
                    <figcaption class="screen-reader-text"><?php echo esc_html($video['title']); ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/** Introductory copy and carousel for the handmade-chocolate videos. */
function antonova_handmade_chocolate_video_intro_html() {
    return '<section class="chocolate-video-section" aria-labelledby="chocolate-video-title">'
        . '<h2 id="chocolate-video-title">Шоколад ручной работы — с любовью к каждой детали</h2>'
        . '<p>Хотите увидеть, как создаются шоколадные сладости ручной работы? Загляните за кулисы домашней кондитерской! В этом видео мы покажем процесс приготовления шоколадных изделий своими руками — от работы с шоколадом до создания аппетитных лакомств.</p>'
        . '<p>Каждая шоколадная сладость — это немного творчества, вдохновения и любви к своему делу.</p>'
        . '<p class="chocolate-video-callout"><strong>▶ Смотрите видео и узнайте, как рождаются шоколадные вкусности!</strong></p>'
        . antonova_handmade_chocolate_video_slider_html()
        . '</section>';
}

/**
 * Place each gallery beneath its introductory text, before the next section.
 * It happens in PHP rather than JavaScript, so robots and no-JS visitors receive
 * every photo in the initial response.
 */
function antonova_insert_landing_thumbnail_gallery($content) {
    if (is_admin() || !is_main_query() || !in_the_loop() || false !== strpos($content, 'data-thumbnail-gallery')) {
        return $content;
    }

    $placements = array(
        'wedding-cakes' => array(
            'gallery' => 'wedding',
            'pattern' => '#(<h2\\b[^>]*>\\s*Свадебный торт по вашему дизайну\\s*</h2>\\s*<p\\b[^>]*>.*?</p>)#isu',
        ),
        'birthday-cakes' => array(
            'gallery' => 'birthday',
            'pattern' => '#(<h2\\b[^>]*>\\s*Торты для детей и взрослых\\s*</h2>\\s*<p\\b[^>]*>.*?</p>)#isu',
        ),
        'handmade-chocolate' => array(
            'gallery' => 'chocolate',
            'pattern' => '#(<h2\\b[^>]*>\\s*Авторский шоколад ручной работы\\s*</h2>\\s*<p\\b[^>]*>.*?</p>\\s*<p\\b[^>]*>.*?</p>)#isu',
        ),
    );

    foreach ($placements as $page_slug => $placement) {
        if (!is_page($page_slug)) {
            continue;
        }

        $gallery = antonova_landing_thumbnail_gallery_html($placement['gallery']);
        if (!$gallery) {
            return $content;
        }

        $inserted_content = $gallery;
        if ('handmade-chocolate' === $page_slug) {
            $inserted_content .= antonova_handmade_chocolate_video_intro_html();
        }

        return preg_replace($placement['pattern'], '$1' . $inserted_content, $content, 1);
    }

    return $content;
}
add_filter('the_content', 'antonova_insert_landing_thumbnail_gallery', 25);

function antonova_content($key, $default) {
    $value = get_option($key, '');
    return $value !== '' ? $value : $default;
}

/** Keep the editable homepage lead in sync with the approved three-line copy. */
function antonova_migrate_home_hero_lead_v2() {
    if (get_option('antonova_home_hero_lead_version') === '2') {
        return;
    }

    update_option('antonova_lead', 'Индивидуальные вкусы, оформление и внимание к каждой детали. От идеи и референса до десерта, который станет частью вашего праздника.');
    update_option('antonova_home_hero_lead_version', '2');
}
add_action('init', 'antonova_migrate_home_hero_lead_v2', 20);

function antonova_migrate_home_hero_h1_v3() {
    if (get_option('antonova_home_hero_h1_version') === '3') {
        return;
    }

    update_option('antonova_h1', 'Торты на заказ в Москве');
    update_option('antonova_home_hero_h1_version', '3');
}
add_action('init', 'antonova_migrate_home_hero_h1_v3', 20);

function antonova_homepage_product_image_files() {
    return array(
        '01 Торты.webp',
        '02 Шоколад ручной работы.webp',
        '03 Шоколадные конфеты ручной работы.webp',
        '04 Ассорти домашних пирожных.webp',
        '05 Печенье орешки с начинкой.webp',
        '06 Свежие фрукты в шоколадной глазури.webp',
        '07 Зефир.webp',
    );
}

function antonova_homepage_product_image($filename) {
    static $urls = array();
    if (isset($urls[$filename])) {
        return $urls[$filename];
    }

    $url = antonova_theme_asset('assets/homepage-products/' . rawurlencode($filename));
    $file = trailingslashit(get_template_directory()) . 'assets/homepage-products/' . $filename;
    if (is_file($file)) {
        $url = add_query_arg('v', substr(hash_file('sha256', $file), 0, 12), $url);
    }

    $urls[$filename] = $url;
    return $url;
}

function antonova_default_products() {
    $images = antonova_homepage_product_image_files();

    return array(
        array('name' => 'Торты', 'price' => 'от 3000 ₽/кг', 'image' => antonova_homepage_product_image($images[0])),
        array('name' => 'Шоколад ручной работы', 'price' => 'от 2000 ₽', 'image' => antonova_homepage_product_image($images[1])),
        array('name' => 'Шоколадные конфеты ручной работы', 'price' => 'от 150 ₽', 'image' => antonova_homepage_product_image($images[2])),
        array('name' => 'Ассорти домашних пирожных', 'price' => 'от 200 ₽', 'image' => antonova_homepage_product_image($images[3])),
        array('name' => 'Печенье орешки с начинкой', 'price' => 'от 100 ₽', 'image' => antonova_homepage_product_image($images[4])),
        array('name' => 'Свежие фрукты в шоколадной глазури', 'price' => 'от 1500 ₽', 'image' => antonova_homepage_product_image($images[5])),
        array('name' => 'Зефир', 'price' => 'от 150 ₽', 'image' => antonova_homepage_product_image($images[6])),
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
        array('question' => 'Сколько стоит торт и есть ли доставка?', 'answer' => 'Стоимость торта — от 3000 ₽ за килограмм, декор рассчитывается отдельно. Возможны курьерская доставка и самовывоз.'),
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
    $products = antonova_merge_rows(get_option('antonova_products', array()), antonova_default_products());
    foreach (antonova_homepage_product_image_files() as $index => $filename) {
        if (isset($products[$index])) {
            $products[$index]['image'] = antonova_homepage_product_image($filename);
        }
    }
    return $products;
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
    return antonova_default_faq();
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
        'antonova_h1' => array('Главный заголовок', 'Торты на заказ в Москве', 'text'),
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

// Server-rendered contextual links in ordinary page copy.
if (file_exists(get_template_directory() . '/inc/contextual-links.php')) {
    require_once get_template_directory() . '/inc/contextual-links.php';
}

// SEO overrides of the AI SEO Agent: the site is changed only through code (seo/overrides.json in the repository, ADR-086).
if (file_exists(get_template_directory() . '/inc/seo-agent-overrides.php')) {
    require_once get_template_directory() . '/inc/seo-agent-overrides.php';
}
