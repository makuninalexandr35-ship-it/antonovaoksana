<?php
/*
Template Name: Торты-открытки
Template Post Type: page
*/

if (!defined('ABSPATH')) { exit; }

$max_url = antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo');
$phone_tel = antonova_content('antonova_phone_tel', '+79647281844');
$postcard_gallery = antonova_landing_gallery_data('postcard');
$hero_image = array();
foreach ($postcard_gallery['items'] as $gallery_item) {
    if ('Торт-открытка с розовыми георгинами.webp' === $gallery_item['file']) { $hero_image = $gallery_item; break; }
}
if (empty($hero_image) && !empty($postcard_gallery['items'])) { $hero_image = $postcard_gallery['items'][0]; }

if (!function_exists('antonova_postcard_accordion_html')) {
    function antonova_postcard_accordion_html($id, $title, $paragraphs) {
        ?>
        <section class="postcard-accordion">
            <h3><button type="button" data-postcard-accordion-button aria-expanded="true" aria-controls="<?php echo esc_attr($id); ?>"><span><?php echo esc_html($title); ?></span><span aria-hidden="true">+</span></button></h3>
            <div id="<?php echo esc_attr($id); ?>" class="postcard-accordion-content">
                <?php foreach ($paragraphs as $paragraph) : ?><p><?php echo wp_kses_post($paragraph); ?></p><?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}

$image_topics = array(
    array('Цветы, букеты и красивые иллюстрации', array(
        'Для оформления можно выбрать изображение цветов, букета или красивую иллюстрацию.',
        'В моём портфолио есть работы с сиренью, георгинами и другими цветочными сюжетами.',
        'При желании можно подобрать другую цветовую гамму или прислать собственное изображение.',
    )),
    array('Поздравительные надписи и праздничные сюжеты', array(
        'На торт можно перенести поздравление, имя, памятную дату или тематическую картинку.',
        'Это может быть оформление ко дню рождения, Новому году, юбилею или другому празднику.',
        'Если изображение содержит текст, заранее согласуем его размер и расположение, чтобы надпись оставалась читаемой.',
    )),
    array('Рисунок или картинка по вашему образцу', array(
        'Вы можете прислать собственный рисунок, иллюстрацию или подходящую картинку.',
        'Мы уточним, подходит ли её качество для выбранного размера торта, и согласуем расположение основных элементов.',
        'Если изображение содержит очень мелкие детали, это лучше обсудить заранее.',
    )),
    array('Фотографии людей и персональные изображения', array(
        'Можно заказать торт-открытку с фотографией человека, семейным снимком, памятным кадром или другим персональным изображением.',
        'Пришлите фотографию в хорошем качестве. Мы обсудим её размещение и размер, чтобы при переносе на шоколад сохранились основные детали.',
    )),
);

$flavors = array(
    array('Наполеон', 'Хрустящие коржи, нежный крем.'),
    array('Шоколадный с вишнёвым конфи', 'Шоколадные коржи, вишнёвое конфи.'),
    array('Шпинатный с лимонным курдом', 'Шпинатные коржи, лимонный курд.'),
    array('Рафаэлло', 'Кокосовый крем, миндальная нежность.'),
    array('Марс', 'Шоколадный крем, карамельная нежность.'),
    array('Ваниль — клубника', 'Ванильный крем, клубничная свежесть.'),
    array('Шоколадный с дубайской начинкой', 'Шоколадный крем, фисташковый хруст.'),
    array('Груша — дорблю', 'Груша и пикантный дорблю.'),
);

get_header();
?>
<main>
    <section class="chocolate-hero postcard-detail-hero">
        <?php if (!empty($hero_image)) : ?>
            <img class="chocolate-hero-image postcard-detail-hero-image" src="<?php echo esc_url($hero_image['src']); ?>" alt="<?php echo esc_attr($hero_image['alt']); ?>"<?php if (!empty($hero_image['width']) && !empty($hero_image['height'])) : ?> width="<?php echo esc_attr($hero_image['width']); ?>" height="<?php echo esc_attr($hero_image['height']); ?>"<?php endif; ?> decoding="async" fetchpriority="high" loading="eager">
        <?php endif; ?>
        <div class="chocolate-hero-copy postcard-detail-hero-copy">
            <p class="eyebrow">Торты-открытки</p>
            <h1 class="mobile-hero-title">Торты-открытки на заказ в Москве</h1>
            <div class="postcard-hero-intro">
                <p>Создаю торты-открытки с цветочными рисунками, праздничными сюжетами и изображениями по вашей идее. Вы можете выбрать готовое оформление или прислать собственную картинку, фотографию либо поздравительную надпись.</p>
                <p>Мы обсудим оформление, начинку, вес и стоимость торта.</p>
            </div>
            <div class="postcard-cta-group postcard-hero-actions">
                <a class="button button-light" href="<?php echo esc_url($max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать в MAX</a>
                <a class="button button-secondary" href="tel:<?php echo esc_attr(ltrim($phone_tel, 'tel:')); ?>">Позвонить</a>
            </div>
            <ul class="postcard-hero-facts" aria-label="Краткая информация о заказе"><li>От 1 кг</li><li>Картинка входит в стоимость</li><li>Оформление по вашему изображению</li></ul>
        </div>
    </section>

    <article class="chocolate-page-content postcard-cakes-page-content">
        <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span aria-current="page">Торты-открытки</span></nav>
        <div class="page-content postcard-page-copy">
            <section class="postcard-copy-section" aria-labelledby="postcard-portfolio-title">
                <h2 id="postcard-portfolio-title">Фото тортов-открыток</h2>
                <p>В моём портфолио представлены торты-открытки с цветочными изображениями и новогодним сюжетом. Выберите понравившееся оформление или предложите собственную картинку.</p>
                <?php echo antonova_landing_thumbnail_gallery_html('postcard'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>

            <aside class="landing-page-links postcard-other-links" aria-labelledby="postcard-other-title">
                <h2 id="postcard-other-title">Другие направления</h2>
                <p>Помимо тортов-открыток, я готовлю праздничные торты и шоколад ручной работы. Посмотрите другие направления моего ассортимента.</p>
                <div class="landing-page-links-actions"><a href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">Торты на день рождения</a><a href="<?php echo esc_url(home_url('/wedding-cakes/')); ?>">Свадебные торты</a><a href="<?php echo esc_url(home_url('/handmade-chocolate/')); ?>">Шоколад ручной работы</a><a href="<?php echo esc_url(home_url('/?work_category=classic_cakes#works')); ?>">Классические торты</a><a href="<?php echo esc_url(home_url('/?work_category=thematic_cakes#works')); ?>">Тематические торты</a></div>
            </aside>

            <section class="postcard-copy-section postcard-price-section" aria-labelledby="postcard-price-title">
                <h2 id="postcard-price-title">Сколько стоит торт-открытка</h2>
                <p class="postcard-price-highlight">Предварительная стоимость торта-открытки — <strong>3 000–5 000 ₽ за торт</strong>. Минимальный вес — <strong>от 1 кг</strong>.</p>
                <p>Перенос выбранной картинки на шоколад входит в указанную стоимость. Отдельно оплачивать само изображение не нужно.</p>
                <p>Итоговую цену согласуем перед изготовлением с учётом веса, начинки и размера торта. Для более тяжёлых заказов стоимость может быть выше предварительного диапазона.</p>
                <p>Чтобы узнать точную стоимость, сообщите дату праздника, количество гостей и пожелания к оформлению.</p>
                <div class="postcard-copy-action"><a class="button" href="<?php echo esc_url($max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Узнать стоимость в MAX</a></div>
            </section>

            <section class="postcard-copy-section" aria-labelledby="postcard-process-title">
                <h2 id="postcard-process-title">Как создаётся картинка на торте-открытке</h2>
                <p>Для оформления торта-открытки я использую шокотрансферную бумагу — специальный материал для переноса пищевого изображения на шоколад.</p>
                <p>После переноса технологическую основу снимают, а рисунок остаётся на шоколадной поверхности торта.</p>
                <p>Вы можете прислать собственную картинку, рисунок, фотографию человека или поздравительную надпись. Перед изготовлением мы согласуем размер и расположение изображения, чтобы оно хорошо смотрелось на готовом торте.</p>
            </section>

            <section class="postcard-copy-section" aria-labelledby="postcard-images-title">
                <h2 id="postcard-images-title">Какие изображения можно нанести на торт</h2>
                <p>На торт можно перенести практически любую подходящую картинку. Выберите готовое оформление или предложите своё изображение — главное, чтобы оно подходило по качеству и размеру.</p>
                <div class="postcard-accordion-list"><?php foreach ($image_topics as $index => $topic) { antonova_postcard_accordion_html('postcard-image-topic-' . ($index + 1), $topic[0], $topic[1]); } ?></div>
            </section>

            <section class="postcard-copy-section" aria-labelledby="postcard-flavors-title">
                <h2 id="postcard-flavors-title">Начинки для тортов-открыток</h2>
                <p>Для тортов-открыток доступны восемь начинок из моего ассортимента. Вы можете выбрать знакомый вкус или более необычное сочетание.</p>
                <p>Медовые начинки для этого вида тортов не используются.</p>
                <p>Если пока не определились, расскажите, какие вкусы предпочитаете, — помогу выбрать подходящий вариант.</p>
                <div class="postcard-flavors-grid"><?php foreach ($flavors as $flavor) : ?><article><h3><?php echo esc_html($flavor[0]); ?></h3><p><?php echo esc_html($flavor[1]); ?></p></article><?php endforeach; ?></div>
            </section>

            <section class="postcard-copy-section" aria-labelledby="postcard-weight-title">
                <h2 id="postcard-weight-title">Сколько весит торт-открытка и на сколько человек его хватит</h2>
                <p>Минимальный вес торта-открытки — <strong>1 кг</strong>.</p>
                <p>Для предварительного расчёта можно ориентироваться на 150–200 г торта на одного человека. Количество порций зависит от праздника и того, будут ли другие десерты.</p>
                <div class="postcard-table-wrap"><table class="postcard-info-table"><caption class="screen-reader-text">Ориентировочный вес торта-открытки по количеству гостей</caption><thead><tr><th scope="col">Количество гостей</th><th scope="col">Ориентировочный вес</th></tr></thead><tbody><tr><td>5 человек</td><td>От 1 кг</td></tr><tr><td>10 человек</td><td>1,5–2 кг</td></tr><tr><td>15 человек</td><td>2,25–3 кг</td></tr><tr><td>20 человек</td><td>3–4 кг</td></tr></tbody></table></div>
                <p>Это ориентировочный расчёт. Возможность изготовления торта определённого веса, его размер и окончательную стоимость согласуем перед заказом.</p>
            </section>

            <section class="postcard-copy-section" aria-labelledby="postcard-order-title">
                <h2 id="postcard-order-title">Как заказать торт-открытку в Москве</h2>
                <ol class="postcard-order-steps"><li><strong>Выберите дату и изображение.</strong> Напишите мне в MAX, к какой дате нужен торт. Можно выбрать работу из портфолио или прислать собственную картинку.</li><li><strong>Подберём начинку и вес.</strong> Обсудим количество гостей, подходящий вес и начинку из восьми доступных вариантов.</li><li><strong>Согласуем оформление и стоимость.</strong> Уточним размер и расположение изображения на шоколаде, после чего я рассчитаю окончательную стоимость заказа.</li><li><strong>Получите готовый торт.</strong> Готовый торт можно забрать самостоятельно в районе Коптево или согласовать курьерскую доставку.</li></ol>
                <h3>За сколько дней лучше заказывать торт-открытку</h3>
                <p>Рекомендую обращаться примерно за две недели до праздника. Минимальный рекомендуемый срок оформления заказа — одна неделя.</p>
                <p>Если торт нужен раньше, напишите мне. При наличии свободного времени возможен срочный заказ за 2–3 дня с доплатой 20%.</p>
                <p>Возможность изготовления к конкретной дате зависит от текущей загрузки и деталей заказа.</p>
                <h3>Самовывоз и доставка</h3>
                <p><strong>Самовывоз — район Коптево, Москва.</strong></p>
                <p><strong>Курьерская доставка — по Москве и Московской области, стоимость рассчитывается индивидуально.</strong></p>
                <p>Точное место и время получения заказа согласуем при оформлении.</p>
            </section>

            <section class="postcard-copy-section postcard-faq" aria-labelledby="postcard-faq-title">
                <h2 id="postcard-faq-title">Частые вопросы о тортах-открытках</h2>
                <div class="postcard-accordion-list"><?php foreach (antonova_postcard_cakes_faq() as $index => $faq_item) { antonova_postcard_accordion_html('postcard-faq-' . ($index + 1), $faq_item['question'], array($faq_item['answer'])); } ?></div>
            </section>

            <section class="postcard-final-cta" aria-labelledby="postcard-final-title">
                <h2 id="postcard-final-title">Закажите торт-открытку со своей картинкой</h2>
                <p>Выберите оформление из моего портфолио или пришлите собственное изображение — фотографию, рисунок или поздравление.</p>
                <p>Напишите, к какой дате нужен торт и сколько гостей вы ожидаете. Мы подберём начинку, согласуем размер изображения и рассчитаем стоимость.</p>
                <div class="postcard-cta-group"><a class="button" href="<?php echo esc_url($max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить заказ в MAX</a><a class="button button-secondary" href="tel:<?php echo esc_attr(ltrim($phone_tel, 'tel:')); ?>">Позвонить</a></div>
            </section>
        </div>
    </article>
</main>
<?php get_footer(); ?>
