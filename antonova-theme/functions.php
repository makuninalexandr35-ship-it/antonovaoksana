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

function antonova_send_security_headers() {
    if (is_admin()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}
add_action('send_headers', 'antonova_send_security_headers');

function antonova_theme_assets() {
    wp_enqueue_style(
        'antonova-local-fonts',
        get_template_directory_uri() . '/assets/fonts/fonts.css',
        array(),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'antonova-style',
        get_stylesheet_uri(),
        array('antonova-local-fonts'),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'antonova-script',
        get_template_directory_uri() . '/script.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'antonova_theme_assets', 20);

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

    return get_post_meta($work_id, '_antonova_work_legacy_image', true);
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
 * Keep SEO output in one place: Yoast. The templates do not print metadata.
 */
function antonova_yoast_title($title) {
    if (is_front_page()) {
        return 'Торты на заказ в Москве — авторские десерты | Oksana Antonova';
    }

    if (is_page()) {
        return single_post_title('', false) . ' | Oksana Antonova';
    }

    return $title;
}
add_filter('wpseo_title', 'antonova_yoast_title');

function antonova_yoast_front_page_description($description) {
    if (is_front_page()) {
        return 'Авторские торты и десерты на заказ в Москве. Свадебные, праздничные и тематические торты от Oksana Antonova.';
    }

    return $description;
}
add_filter('wpseo_metadesc', 'antonova_yoast_front_page_description');

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

function antonova_content($key, $default) {
    $value = get_option($key, '');
    return $value !== '' ? $value : $default;
}

function antonova_default_products() {
    return array(
        array('name' => 'Торты', 'price' => 'от 3000 ₽/кг', 'image' => antonova_theme_asset('assets/optimized/38a4229c5821-960.webp')),
        array('name' => 'Шоколад', 'price' => 'от 2000 ₽', 'image' => antonova_theme_asset('assets/optimized/7cff2bb5e784-960.webp')),
        array('name' => 'Конфеты', 'price' => 'от 150 ₽', 'image' => antonova_theme_asset('assets/optimized/4127335644ff-720.webp')),
        array('name' => 'Пирожные', 'price' => 'от 200 ₽', 'image' => antonova_theme_asset('assets/optimized/f96267f0396d-1086.webp')),
        array('name' => 'Орешки', 'price' => 'от 100 ₽', 'image' => antonova_theme_asset('assets/optimized/f015a856bc41-1600.webp')),
        array('name' => "Фрукты\nв шоколаде", 'price' => 'от 1500 ₽', 'image' => antonova_theme_asset('assets/optimized/981461dca556-1183.webp')),
        array('name' => 'Зефир', 'price' => 'от 150 ₽', 'image' => antonova_theme_asset('assets/optimized/a73d6dbb6d7f-720.webp')),
    );
}

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
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M12 42c5-14 17-23 32-23 5 0 9 1 12 3-6 13-18 22-33 22-5 0-8-1-11-2zM13 42c5 4 12 6 19 6 10 0 19-4 26-11M23 31c4 1 7 4 9 8M33 24c4 2 7 5 9 10M43 21c3 3 5 6 6 10M17 38c2 2 5 4 8 5"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M9 42c1-12 9-24 21-31 8 10 11 22 7 31-5 12-18 17-26 9-2-2-3-5-2-9zM17 46c6-9 10-18 13-29M16 33l12 5M22 23l9 5M37 26c7-5 14-6 20-3 0 9-2 17-8 22-4 4-9 5-13 3M42 42c2-6 6-11 11-15"/></svg>',
        '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M32 18c10-1 18 6 16 16-2 12-9 21-16 25-7-4-14-13-16-25-2-10 6-17 16-16zM32 18c-4-6-9-7-14-5M32 18c3-7 9-8 14-6M27 14l-3-6M37 13l4-5M18 35c5 2 8 6 14 6s10-4 14-6M19 43c5 2 8 6 13 6s9-4 13-6M25 29h.01M32 26h.01M39 29h.01M28 39h.01M36 40h.01"/></svg>',
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
                'sanitize_callback' => in_array($field, array('antonova_telegram_url', 'antonova_whatsapp_url', 'antonova_instagram_url', 'antonova_vk_candy_url', 'antonova_vk_oksana_url', 'antonova_threads_url', 'antonova_about_image'), true) ? 'esc_url_raw' : 'sanitize_text_field',
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
