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
        <section class="chocolate-hero wedding-hero">
            <img class="chocolate-hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero5-ezgif.com-jpg-to-webp-converter.webp" width="1680" height="945" decoding="async" alt="Многоярусный свадебный торт с лиловыми цветами" fetchpriority="high">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Свадебные&nbsp;торты</p>
                <h1 class="mobile-hero-title"><span>Свадебные</span><span class="desktop-space">&nbsp;</span><br class="mobile-line-break"><span>торты</span> <br class="desktop-line-break"><span>на заказ</span><span class="desktop-space">&nbsp;</span><br class="mobile-line-break"><span>в Москве</span></h1>
                <p>Создаю <span>свадебные&nbsp;торты</span> в Москве по индивидуальному дизайну. Обсудим размер, начинку, цвет, декор и оформление торта с учётом стиля вашей свадьбы. Доступны самовывоз в Коптево и доставка по Москве и области.</p>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Свадебные торты</span></nav>
            <div class="page-content wedding-page-copy">
                <?php
                $wedding_gallery = antonova_landing_gallery_data('wedding');
                $wedding_items = array();
                foreach ($wedding_gallery['items'] as $wedding_item) {
                    $wedding_items[$wedding_item['file']] = $wedding_item;
                }
                $one_tier_gallery = array(
                    'id' => 'wedding-one-tier-gallery',
                    'label' => 'Одноярусные свадебные торты',
                    'items' => isset($wedding_items['wedding-rings.webp']) ? array($wedding_items['wedding-rings.webp']) : array(),
                    'show_details' => true,
                    'show_thumbnails' => false,
                    'class' => 'wedding-tier-gallery',
                );
                $multi_tier_items = array();
                foreach (array('wedding-swans.webp', 'wedding-pink-flowers.webp', 'wedding-lace.jpg') as $wedding_filename) {
                    if (isset($wedding_items[$wedding_filename])) {
                        $multi_tier_items[] = $wedding_items[$wedding_filename];
                    }
                }
                $multi_tier_gallery = array(
                    'id' => 'wedding-multi-tier-gallery',
                    'label' => 'Трехъярусные свадебные торты',
                    'items' => $multi_tier_items,
                    'show_details' => true,
                    'show_thumbnails' => false,
                    'class' => 'wedding-tier-gallery',
                );
                $wedding_max_url = antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo');
                ?>

                <section class="wedding-copy-section" aria-labelledby="wedding-portfolio-title">
                    <h2 id="wedding-portfolio-title">Свадебные торты на заказ — фотографии моих работ</h2>
                    <p>У каждой свадьбы своя атмосфера, и торт может стать её красивым продолжением. Для одного торжества подойдёт сдержанное белое оформление, для другого — цветочная композиция, символичные украшения или выразительная многоярусная конструкция.</p>
                    <p>В моём портфолио представлены свадебные торты с цветами, декоративными лебедями, кольцами и кружевными элементами. Вы можете посмотреть фотографии выполненных работ, выбрать понравившиеся детали и предложить свою идею.</p>
                    <p>Необязательно повторять оформление целиком. Мы можем обсудить сочетание оттенков, форму, фактуру покрытия и украшения, которые будут соответствовать вашей свадебной концепции.</p>

                    <div class="wedding-tier-grid" aria-label="Примеры одноярусных и трехъярусных свадебных тортов">
                        <section class="wedding-tier-column" aria-labelledby="one-tier-gallery-title">
                            <h3 id="one-tier-gallery-title">Одноярусные свадебные торты</h3>
                            <?php get_template_part('template-parts/landing-thumbnail-gallery', null, array('gallery' => $one_tier_gallery)); ?>
                        </section>
                        <section class="wedding-tier-column" aria-labelledby="multi-tier-gallery-title">
                            <h3 id="multi-tier-gallery-title">Трехъярусные свадебные торты</h3>
                            <?php get_template_part('template-parts/landing-thumbnail-gallery', null, array('gallery' => $multi_tier_gallery)); ?>
                        </section>
                    </div>

                    <p class="wedding-copy-action"><a class="button" href="<?php echo esc_url($wedding_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить оформление моего торта</a></p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="one-tier-title">
                    <h2 id="one-tier-title">Одноярусные свадебные торты</h2>
                    <p>Одноярусный свадебный торт — вариант для небольшой свадьбы, семейного торжества или камерного праздника, когда не требуется большая многоярусная конструкция.</p>
                    <p>Один ярус не ограничивает выбор оформления. Такой торт может быть белым, с нежными цветовыми акцентами, объёмными украшениями, надписью или символичными свадебными деталями.</p>
                    <p>В моём портфолио есть белый одноярусный свадебный торт с декоративными кольцами. Его оформление построено на сочетании белых объёмных элементов и золотистых акцентов.</p>
                    <p>Если вам ближе современный минимализм, можно обсудить более сдержанное оформление: спокойную цветовую гамму, фактурную поверхность и небольшое количество акцентных деталей.</p>
                    <p>Вес одноярусного торта подбирается отдельно. Он зависит от количества гостей, выбранной начинки и особенностей конструкции. Один ярус не означает один фиксированный размер или вес.</p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="multi-tier-title">
                    <h2 id="multi-tier-title">Двухъярусные и трехъярусные свадебные торты</h2>
                    <p>Многоярусный свадебный торт позволяет объединить несколько уровней в одну композицию. Такой формат выбирают не только с учётом количества гостей, но и тогда, когда хотят сделать торт заметной частью оформления праздничного зала.</p>
                    <p>В моих работах представлены многоярусные свадебные композиции с декоративными цветами, лебедями, кружевными элементами и фигурными опорами. Розовый торт с цветами выполнен в трехъярусном формате.</p>
                    <p>При выборе многоярусного торта важно учитывать не только его внешний вид, но и общую массу, размеры отдельных ярусов, начинку и устойчивость конструкции.</p>
                    <p>Для сложных многоярусных композиций заранее обсуждаем условия перевозки и место получения заказа. Это особенно важно, если торт требуется доставить непосредственно в ресторан или свадебный зал.</p>
                    <p>Вы можете прислать фотографию понравившейся конструкции. Я уточню возможность изготовления, подходящий размер и варианты оформления.</p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="wedding-decoration-title">
                    <h2 id="wedding-decoration-title">Оформление и декор свадебного торта</h2>
                    <p>Оформление свадебного торта можно связать с цветовой палитрой торжества, букетом невесты, приглашениями или украшением банкетного зала. Важно, чтобы цвет, форма и детали сочетались между собой, а выбранный декор соответствовал размеру и конструкции торта.</p>

                    <h3>Белые свадебные торты</h3>
                    <p><a class="contextual-anchor" href="<?php echo esc_url($wedding_items['wedding-rings.webp']['src']); ?>">Белый свадебный торт</a> — один из вариантов классического одноярусного оформления. Светлая поверхность позволяет подчеркнуть рельеф, объёмные элементы, кружевные узоры и небольшие цветовые акценты.</p>

                    <h3>Свадебные торты с цветами</h3>
                    <p>Трехъярусный <a class="contextual-anchor" href="<?php echo esc_url($wedding_items['wedding-pink-flowers.webp']['src']); ?>">свадебный торт с цветами</a> показывает, как цветочная композиция помогает сделать оформление более мягким и выразительным. При заказе мы можем обсудить расположение цветочных элементов, их размер и цветовую гамму. Материал декоративных цветов и возможность выполнения конкретной композиции согласовываются отдельно.</p>

                    <h3>Свадебные торты с лебедями и кольцами</h3>
                    <p>Белый трехъярусный <a class="contextual-anchor" href="<?php echo esc_url($wedding_items['wedding-swans.webp']['src']); ?>">свадебный торт с лебедями</a> и одноярусный торт с кольцами показывают два варианта символичного свадебного оформления. По желанию можно обсудить дополнительные детали, связанные с вашей парой, например инициалы или дату свадьбы.</p>

                    <h3>Свадебные торты с кружевом</h3>
                    <p>Белый трехъярусный <a class="contextual-anchor" href="<?php echo esc_url($wedding_items['wedding-lace.jpg']['src']); ?>">свадебный торт с кружевом</a> показывает вариант детализированного оформления с выразительной фактурой. Возможность использования конкретной техники и деталей уточняется при согласовании заказа.</p>

                    <h3>Создадим оформление по вашей идее</h3>
                    <p>Если вы ищете идеи свадебного торта, начните с фотографий, которые вам нравятся. Можно также прислать изображение букета невесты, приглашений или оформления зала. Я помогу определить, какие цвета и элементы можно объединить в одном дизайне.</p>
                    <p class="wedding-copy-action"><a class="button" href="<?php echo esc_url($wedding_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить дизайн свадебного торта</a></p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="wedding-price-title">
                    <h2 id="wedding-price-title">Сколько стоит свадебный торт на заказ</h2>
                    <p>Стоимость свадебного торта зависит от веса, выбранной начинки, количества ярусов, сложности конструкции и декоративного оформления.</p>
                    <p>На сайте указана стоимость тортов <strong>от 3 000 ₽ за килограмм</strong>. Это общий ценовой ориентир для тортов. Итоговую стоимость свадебного заказа я рассчитываю индивидуально после согласования деталей.</p>
                    <p>Декор рассчитывается отдельно. Дополнительные элементы, сложные украшения и особенности многоярусной конструкции могут влиять на общую цену. Возможность и стоимость доставки также обсудим заранее.</p>
                    <p>Для расчёта понадобятся дата свадьбы, примерное количество гостей, пожелания к начинке и оформлению. Если у вас есть фотография понравившегося торта, приложите её к сообщению.</p>
                    <p class="wedding-copy-action"><a class="button" href="<?php echo esc_url($wedding_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Узнать стоимость свадебного торта</a></p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="wedding-weight-title">
                    <h2 id="wedding-weight-title">Сколько килограммов свадебного торта нужно на свадьбу</h2>
                    <p>Обычно я рекомендую ориентироваться на <strong>150–200 граммов торта на одного человека</strong>. Это примерный расчёт, который помогает определить необходимый общий вес.</p>
                    <div class="wedding-weight-table-wrap">
                        <table class="wedding-weight-table">
                            <caption class="screen-reader-text">Ориентировочный вес свадебного торта по количеству гостей</caption>
                            <thead><tr><th scope="col">Количество гостей</th><th scope="col">Ориентировочный вес</th></tr></thead>
                            <tbody>
                                <tr><td>10 человек</td><td>1,5–2 кг</td></tr>
                                <tr><td>20 человек</td><td>3–4 кг</td></tr>
                                <tr><td>30 человек</td><td>4,5–6 кг</td></tr>
                                <tr><td>40 человек</td><td>6–8 кг</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p>Свадебный торт на 2 кг — это ориентир примерно на 10–13 порций, на 3 кг — на 15–20 порций, на 4 кг — на 20–26 порций. Количество порций приблизительное и зависит от их фактического размера.</p>
                    <p>При расчёте стоит учитывать отдельный сладкий стол, другие десерты и размер порций. Общий вес не всегда определяет внешний размер торта: на диаметр и высоту влияют начинка, количество ярусов и конструкция.</p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="wedding-flavors-title">
                    <h2 id="wedding-flavors-title">Начинки и вкусы свадебных тортов</h2>
                    <p>Внешний вид свадебного торта важен, но начинка заслуживает не меньшего внимания. В ассортименте представлены десять вариантов вкусов — от знакомых классических сочетаний до более необычных.</p>
                    <div class="wedding-flavors-grid">
                        <article><h3>Медовик классический</h3><p>Тонкие медовые коржи с нежным кремом.</p></article>
                        <article><h3>Медовик авторский</h3><p>Авторский вариант медовика. Особенности состава можно уточнить при выборе начинки.</p></article>
                        <article><h3>Наполеон</h3><p>Сочетание хрустящих слоёных коржей и нежного крема.</p></article>
                        <article><h3>Шоколадный с вишнёвым конфи</h3><p>Шоколадные коржи с выразительным вкусом какао и фруктовым акцентом.</p></article>
                        <article><h3>Шпинатный с лимонным курдом</h3><p>Шпинатные коржи в сочетании со свежими цитрусовыми оттенками.</p></article>
                        <article><h3>Рафаэлло</h3><p>Нежный кокосовый крем и миндальные нотки.</p></article>
                        <article><h3>Марс</h3><p>Шоколадный крем и карамельное сочетание.</p></article>
                        <article><h3>Ваниль — клубника</h3><p>Мягкое сочетание сливочной нежности и ягодной свежести.</p></article>
                        <article><h3>Шоколадный с дубайской начинкой</h3><p>Шоколадный крем с фисташковым хрустящим компонентом.</p></article>
                        <article><h3>Груша — дорблю</h3><p>Сочетание фруктовой сладости груши и пикантного сыра дорблю.</p></article>
                    </div>
                    <h3>Как выбрать начинку для свадебного торта</h3>
                    <p>Ориентируйтесь на любимые шоколадные, ягодные, сливочные, фруктовые или более необычные сочетания. Для многоярусного оформления возможность использования разных начинок в отдельных ярусах нужно согласовать заранее.</p>
                    <p>Посмотреть все варианты можно в разделе <a class="contextual-anchor" href="<?php echo esc_url(home_url('/#flavors')); ?>">начинки на главной странице</a>.</p>
                    <p class="wedding-copy-action"><a class="button" href="<?php echo esc_url($wedding_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить начинку свадебного торта</a></p>
                </section>

                <section class="wedding-copy-section" aria-labelledby="wedding-process-title">
                    <h2 id="wedding-process-title">Как создаётся свадебный торт по индивидуальному заказу</h2>
                    <p>Меня зовут Оксана Антонова. Моё знакомство с кондитерским искусством началось со свадьбы крестника. Первый свадебный торт стал началом моего увлечения кондитерским делом.</p>
                    <p>Работу над заказом мы начинаем с обсуждения ваших пожеланий. Вы рассказываете, каким представляете десерт, а я помогаю подобрать начинку, размер и оформление с учётом возможностей изготовления.</p>
                    <p>Можно прийти со своей идеей, фотографией или эскизом. Необязательно заранее знать все технические детали — их мы обсудим вместе. Подробнее обо мне можно прочитать в разделе <a class="contextual-anchor" href="<?php echo esc_url(home_url('/#about')); ?>">об Оксане Антоновой</a>.</p>
                </section>

                <section class="wedding-copy-section wedding-order-steps" aria-labelledby="wedding-order-title">
                    <h2 id="wedding-order-title">Как заказать свадебный торт и оформить доставку</h2>
                    <p>Чтобы заказать свадебный торт, напишите мне в MAX. Мы последовательно обсудим основные детали, чтобы вы заранее понимали, каким будет торт и сколько он будет стоить.</p>
                    <ol>
                        <li><strong>Сообщите дату свадьбы.</strong> Я уточню возможность выполнения заказа к этой дате.</li>
                        <li><strong>Укажите количество гостей.</strong> Это поможет рассчитать необходимый вес и размер торта.</li>
                        <li><strong>Выберите начинку.</strong> Можно заранее посмотреть доступные вкусы или рассказать о предпочтениях.</li>
                        <li><strong>Расскажите об оформлении.</strong> Пришлите фотографию, эскиз или описание идеи.</li>
                        <li><strong>Согласуйте стоимость.</strong> После обсуждения я рассчитаю изготовление, оформление и доставку.</li>
                        <li><strong>Получите готовый торт.</strong> После подтверждения заказа согласуем способ получения и удобное время.</li>
                    </ol>
                    <h3>Самовывоз и доставка</h3>
                    <p>Забрать готовый торт самостоятельно можно в Москве, в районе Коптево. Также возможна доставка по Москве и Московской области.</p>
                    <p>Если торт необходим к определённому времени в ресторане или загородном свадебном комплексе, укажите адрес при оформлении заказа. Возможность, время и стоимость доставки согласуем заранее.</p>
                    <p class="wedding-copy-action"><a class="button" href="<?php echo esc_url($wedding_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать свадебный торт в MAX</a></p>
                </section>

                <?php echo antonova_landing_faq_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo antonova_landing_page_cross_links_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <div class="chocolate-page-actions">
                <a class="button" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить заказ</a>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
