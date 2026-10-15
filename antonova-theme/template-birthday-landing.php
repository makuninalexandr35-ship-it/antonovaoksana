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
    $birthday_max_url = antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo');
    $birthday_phone = '+79647281844';
    $birthday_gallery = antonova_landing_gallery_data('birthday');
    $birthday_gallery['show_details'] = true;
    $birthday_gallery['class'] = 'birthday-portfolio-gallery';
    ?>
    <main>
        <section class="chocolate-hero">
            <img class="chocolate-hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero4.webp" width="1680" height="945" decoding="async" alt="Торт с ежевикой на праздничном столе" fetchpriority="high">
            <div class="chocolate-hero-copy">
                <p class="eyebrow">Торты на день рождения</p>
                <h1 class="mobile-hero-title"><span>Торты на день</span><br><span>рождения на</span><br class="mobile-line-break"> <span>заказ</span><br class="desktop-line-break"> <span>в Москве</span></h1>
                <div class="birthday-hero-intro">
                    <p>Меня зовут Оксана Антонова. Я создаю торты для детей и взрослых с оформлением по вашим пожеланиям: с любимыми персонажами, цветами, цифрами, тематическими украшениями и необычными поздравлениями.</p>
                    <p>Вы можете выбрать дизайн из моего портфолио или прислать фотографию понравившегося торта. Я помогу подобрать начинку, рассчитать вес по количеству гостей и согласовать детали оформления.</p>
                    <p>Заказы принимаю в Москве. Готовый торт можно забрать самостоятельно в Коптево или заказать доставку по Москве и Московской области.</p>
                </div>
                <div class="wedding-cta-group wedding-hero-actions">
                    <a class="button button-light" href="<?php echo esc_url($birthday_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить заказ в MAX</a>
                    <a class="button button-secondary" href="tel:<?php echo esc_attr($birthday_phone); ?>" aria-label="Позвонить Оксане Антоновой">Позвонить</a>
                </div>
            </div>
        </section>

        <article class="chocolate-page-content">
            <nav class="page-breadcrumbs" aria-label="Хлебные крошки"><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span aria-hidden="true">→</span><span>Торты на день рождения</span></nav>
            <div class="page-content birthday-page-copy">
                <section class="birthday-copy-section" aria-labelledby="birthday-portfolio-title">
                    <h2 id="birthday-portfolio-title">Фото тортов на день рождения — мои работы</h2>
                    <p>В моём портфолио представлены торты для детских праздников, взрослых дней рождения и юбилеев. Среди них — работы с фигурками, цветами, ягодами, поздравительными надписями и оформлением на определённый возраст.</p>
                    <p>Посмотрите фотографии, чтобы выбрать подходящий стиль. Например, для тематического праздника можно взять за основу оформление с любимым персонажем или увлечением, а для семейного торжества — торт с именем, возрастом или коротким поздравлением.</p>
                    <p>Каждую идею мы обсуждаем индивидуально. Можно изменить цветовую гамму, подобрать другие декоративные детали или предложить собственный вариант. Возможность изготовления выбранного оформления зависит от конструкции и особенностей заказа.</p>
                    <?php get_template_part('template-parts/landing-thumbnail-gallery', null, array('gallery' => $birthday_gallery)); ?>
                    <p class="birthday-gallery-note">Цены под фотографиями указаны как предварительный ориентир за торт. Точную стоимость рассчитываю индивидуально с учётом веса, начинки и оформления.</p>
                    <p class="birthday-copy-action"><a class="button" href="<?php echo esc_url($birthday_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить оформление торта</a></p>
                </section>

                <section class="birthday-copy-section birthday-seo-accordion" aria-labelledby="birthday-children-title">
                    <h2 id="birthday-children-title"><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-children-content">Детские торты на день рождения<span aria-hidden="true">+</span></button></h2>
                    <div class="birthday-accordion-content" id="birthday-children-content" data-birthday-accordion-content>
                        <p>Для детского дня рождения можно заказать торт с любимым персонажем, игровыми элементами, именем ребёнка или цифрами с возрастом. Оформление подбираем с учётом интересов именинника и тематики праздника.</p>

                        <h3>Торты для девочек и мальчиков</h3>
                        <p>Если вы выбираете торт на день рождения девочке или мальчику, расскажите, чем увлекается ребёнок. Это могут быть игры, конструкторы, мультфильмы, спорт, любимые цвета или персонажи.</p>
                        <p>В моём портфолио есть детские торты с фигурками, мишками и яркими тематическими украшениями. Имя, возраст и поздравительную надпись можно согласовать индивидуально.</p>

                        <h3>Торты для детей и подростков</h3>
                        <p>Для ребёнка или подростка можно выбрать яркий тематический торт, лаконичное оформление с цифрами или необычную поздравительную надпись.</p>
                        <p>Пришлите фотографию понравившегося оформления или расскажите об увлечениях именинника. Я помогу подобрать дизайн и подходящий вес по количеству гостей.</p>
                    </div>
                </section>

                <section class="birthday-copy-section birthday-seo-accordion" aria-labelledby="birthday-adults-title">
                    <h2 id="birthday-adults-title"><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-adults-content">Торты на день рождения для взрослых<span aria-hidden="true">+</span></button></h2>
                    <div class="birthday-accordion-content" id="birthday-adults-content" data-birthday-accordion-content>
                        <p>Для взрослого именинника можно выбрать лаконичный торт с персональной надписью, яркое тематическое оформление, цветочный декор или вариант с ягодами и цифрами.</p>

                        <h3>Торты для мужчин</h3>
                        <p>Если вы выбираете торт мужу на день рождения, папе, парню или любимому мужчине, можно подобрать оформление по его увлечениям.</p>
                        <p>Это может быть торт с именем, возрастом, спортивной тематикой, любимым хобби или прикольным поздравлением.</p>
                        <p>В портфолио есть именной торт с тематическими декоративными элементами. Пожелания к цвету, начинке и надписи согласуем индивидуально.</p>

                        <h3>Торты для женщин</h3>
                        <p>Для мамы, жены, девушки или бабушки можно заказать торт с цветами, ягодами, поздравительной надписью или лаконичным оформлением.</p>
                        <p>В моих работах представлены разные варианты цветового и декоративного оформления. Можно выбрать понравившуюся идею или прислать собственный пример.</p>
                    </div>
                </section>

                <section class="birthday-copy-section birthday-seo-accordion" aria-labelledby="birthday-anniversary-title">
                    <h2 id="birthday-anniversary-title"><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-anniversary-content">Торты на юбилей и круглую дату<span aria-hidden="true">+</span></button></h2>
                    <div class="birthday-accordion-content" id="birthday-anniversary-content" data-birthday-accordion-content>
                        <p>Для юбилея можно заказать торт с цифрами, поздравительной надписью, цветами, ягодами или тематическими украшениями. Оформление и размер согласуем с учётом количества гостей.</p>

                        <h3>Торт на 50 лет</h3>
                        <p>В моём портфолио есть несколько вариантов тортов на 50 лет: оформление в зелёной гамме с золотистыми цифрами и прямоугольный юбилейный торт с ягодами.</p>
                        <p>Можно выбрать понравившийся вариант или предложить собственное оформление для мужчины или женщины.</p>

                        <h3>Торты с цифрами и поздравлениями</h3>
                        <p>Возраст можно выделить декоративными цифрами, добавить имя именинника или короткую поздравительную фразу.</p>
                        <p>Среди реальных работ есть торты на разные даты с цветочным оформлением, ягодами и шуточными надписями.</p>
                    </div>
                </section>

                <section class="birthday-copy-section" aria-labelledby="birthday-design-title">
                    <h2 id="birthday-design-title">Оформление торта на день рождения по вашей идее</h2>
                    <p>Оформление можно подобрать по возрасту, увлечениям и пожеланиям именинника. В моём портфолио есть тематические, одноярусные и двухъярусные торты, работы с поздравительными надписями, цифрами и фотографиями. Можно выбрать готовую идею или прислать фотографию понравившегося торта, чтобы обсудить похожий дизайн.</p>

                    <section class="birthday-sub-accordion">
                        <h3><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-funny-content">Торты с прикольными надписями<span aria-hidden="true">+</span></button></h3>
                        <div class="birthday-accordion-content" id="birthday-funny-content" data-birthday-accordion-content>
                            <p>Необычная надпись может стать главным элементом оформления торта на день рождения. Это может быть семейная шутка, смешное поздравление, имя или фраза, понятная имениннику.</p>
                            <p>В моём портфолио есть торт с юмористической надписью. Можно предложить собственный текст на русском или английском языке, например Happy Birthday.</p>
                            <p>Перед изготовлением мы согласуем надпись, её цвет и расположение.</p>
                        </div>
                    </section>

                    <section class="birthday-sub-accordion">
                        <h3><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-simple-content">Одноярусные торты и лаконичное оформление<span aria-hidden="true">+</span></button></h3>
                        <div class="birthday-accordion-content" id="birthday-simple-content" data-birthday-accordion-content>
                            <p>Одноярусный торт на день рождения можно оформить в спокойных или ярких оттенках, дополнить небольшой надписью, ягодами или отдельным декоративным элементом.</p>
                            <p>Например, в моём портфолио есть зелёный одноярусный торт с фактурным покрытием и золотистым поздравлением.</p>
                            <p>Вес, высоту и детали оформления подбираем индивидуально.</p>
                        </div>
                    </section>

                    <section class="birthday-sub-accordion">
                        <h3><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-themed-content">Тематические торты<span aria-hidden="true">+</span></button></h3>
                        <div class="birthday-accordion-content" id="birthday-themed-content" data-birthday-accordion-content>
                            <p>Тематический торт можно оформить с учётом увлечений именинника: игр, спорта, любимых персонажей или других интересов.</p>
                            <p>В портфолио представлены работы с деталями конструктора, фигурками и необычными декоративными композициями.</p>
                            <p>Вы можете прислать пример оформления или рассказать о своей идее. Я уточню, какие элементы можно выполнить для выбранного размера торта.</p>
                        </div>
                    </section>

                    <section class="birthday-sub-accordion">
                        <h3><button class="birthday-accordion-button" type="button" data-birthday-accordion-button aria-expanded="true" aria-controls="birthday-photo-content">Торт с фотографией на день рождения<span aria-hidden="true">+</span></button></h3>
                        <div class="birthday-accordion-content" id="birthday-photo-content" data-birthday-accordion-content>
                            <p>Можно заказать торт с фотографией именинника, семейным снимком или другим изображением, если выбранный вариант оформления технически возможно выполнить.</p>
                            <p>В моём портфолио есть тематический торт для рыбака, в оформлении которого использована фотография.</p>
                            <p>Пришлите изображение и расскажите о пожеланиях к торту. Мы обсудим размер и расположение фотографии, дополнительный декор, начинку и стоимость.</p>
                            <p>Фотографию лучше отправить заранее, чтобы согласовать возможность её использования до изготовления.</p>
                        </div>
                    </section>
                </section>

                <section class="birthday-copy-section" aria-labelledby="birthday-price-title">
                    <h2 id="birthday-price-title">Сколько стоит торт на день рождения на заказ</h2>
                    <p>Стоимость тортов начинается от <strong>3 000 ₽ за килограмм</strong>. Декоративное оформление рассчитывается отдельно.</p>
                    <p>Итоговая цена зависит от веса, выбранной начинки, сложности оформления и конструкции. Например, небольшой поздравительный декор и композиция с несколькими объёмными фигурками требуют разного объёма работы.</p>
                    <p>Для предварительного расчёта можно ориентироваться на следующие условия:</p>
                    <div class="birthday-table-wrap" role="region" aria-label="Расчёт стоимости торта" tabindex="0">
                        <table class="birthday-info-table">
                            <thead><tr><th scope="col">Что входит в расчёт</th><th scope="col">Стоимость</th></tr></thead>
                            <tbody>
                                <tr><td>Базовая стоимость торта</td><td>От 3 000 ₽/кг</td></tr>
                                <tr><td>Декоративное оформление</td><td>Рассчитывается отдельно</td></tr>
                                <tr><td>Доставка</td><td>По согласованию</td></tr>
                                <tr><td>Итоговая стоимость заказа</td><td>После выбора веса и оформления</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p>Например, при весе 2 кг базовый расчёт составляет от 6 000 ₽ без оформления и доставки. Это арифметический ориентир, а возможность изготовления торта конкретного веса необходимо уточнить при заказе.</p>
                    <p>Чтобы узнать точную стоимость, сообщите дату праздника, примерное количество гостей, понравившуюся начинку и пожелания к дизайну. Если есть фотография оформления, приложите её к сообщению.</p>
                    <p>Я уточню детали и рассчитаю стоимость до подтверждения заказа.</p>
                    <div class="birthday-cta-group">
                        <a class="button" href="<?php echo esc_url($birthday_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Рассчитать стоимость торта</a>
                        <a class="button button-secondary" href="tel:<?php echo esc_attr($birthday_phone); ?>">Позвонить и уточнить стоимость</a>
                    </div>
                </section>

                <section class="birthday-copy-section" aria-labelledby="birthday-weight-title">
                    <h2 id="birthday-weight-title">Сколько килограммов торта нужно на день рождения</h2>
                    <p>Для предварительного расчёта веса я рекомендую ориентироваться на <strong>150–200 г торта на одного гостя</strong>.</p>
                    <div class="birthday-table-wrap" role="region" aria-label="Расчёт веса торта по количеству гостей" tabindex="0">
                        <table class="birthday-info-table">
                            <thead><tr><th scope="col">Количество гостей</th><th scope="col">Расчётный вес десерта</th></tr></thead>
                            <tbody>
                                <tr><td>5 человек</td><td>0,75–1 кг</td></tr>
                                <tr><td>10 человек</td><td>1,5–2 кг</td></tr>
                                <tr><td>15 человек</td><td>2,25–3 кг</td></tr>
                                <tr><td>20 человек</td><td>3–4 кг</td></tr>
                                <tr><td>25 человек</td><td>3,75–5 кг</td></tr>
                                <tr><td>30 человек</td><td>4,5–6 кг</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p>Эти цифры помогают определить количество десерта для гостей. Они не означают, что каждый указанный вес доступен для изготовления на заказ.</p>
                    <p>Минимальный вес и возможность выполнения конкретной конструкции я уточняю индивидуально.</p>
                    <p>При расчёте также важно учитывать, будут ли на празднике другие десерты, насколько большими планируются порции и какую форму торта вы хотите.</p>
                    <p>Если вы сообщите количество гостей и свои пожелания, я помогу подобрать подходящий вариант.</p>
                </section>

                <section class="birthday-copy-section" aria-labelledby="birthday-flavors-title">
                    <h2 id="birthday-flavors-title">Начинки для тортов на день рождения</h2>
                    <p>В моём ассортименте десять вариантов вкусов — от классического Медовика и Наполеона до сочетаний с шоколадом, ягодами и фруктовыми нотками.</p>
                    <p>Вот несколько вариантов, которые можно рассмотреть для праздничного торта.</p>
                    <div class="birthday-flavors-grid">
                        <article><h3>Медовик классический</h3><p>Тонкие коржи и нежный крем. Вариант для любителей классического медовика.</p></article>
                        <article><h3>Наполеон</h3><p>Хрустящие коржи с нежным кремом — сочетание разных текстур в знакомом десерте.</p></article>
                        <article><h3>Шоколадный с вишнёвым конфи</h3><p>Шоколадные коржи и вишнёвое конфи для тех, кто предпочитает шоколадно-ягодные сочетания.</p></article>
                        <article><h3>Ваниль — клубника</h3><p>Ванильный крем и клубничные нотки. Вариант с мягким сливочно-ягодным вкусом.</p></article>
                        <article><h3>Рафаэлло</h3><p>Кокосовый крем и миндальные нотки.</p></article>
                    </div>
                    <p>В ассортименте также представлены Медовик авторский, Шпинатный с лимонным курдом, Марс, Шоколадный с дубайской начинкой и Груша — дорблю.</p>
                    <p>Если вы не знаете, какую начинку выбрать, расскажите, какие вкусы нравятся имениннику. Я помогу сориентироваться в доступных вариантах.</p>
                    <p class="birthday-text-link"><a href="<?php echo esc_url(home_url('/#flavors')); ?>">Посмотреть все начинки</a></p>
                </section>

                <section class="birthday-copy-section birthday-order-steps" aria-labelledby="birthday-order-title">
                    <h2 id="birthday-order-title">Как заказать торт на день рождения и оформить доставку</h2>
                    <p>Чтобы заказать торт, напишите мне в <a href="<?php echo esc_url($birthday_max_url); ?>" target="_blank" rel="noopener noreferrer">MAX</a> или позвоните. Для предварительного расчёта понадобятся дата праздника, количество гостей и пожелания к оформлению.</p>
                    <ol>
                        <li><strong>Сообщите дату.</strong> Я уточню, возможно ли принять заказ на нужный день.</li>
                        <li><strong>Расскажите, для кого торт.</strong> Укажите возраст именинника и желаемую тематику, если она есть.</li>
                        <li><strong>Определим вес.</strong> Сообщите количество гостей, а я помогу рассчитать подходящий размер.</li>
                        <li><strong>Выберем начинку.</strong> Посмотрите доступные вкусы и расскажите о своих предпочтениях.</li>
                        <li><strong>Согласуем оформление и стоимость.</strong> Пришлите фотографию или описание идеи. Мы обсудим декоративные элементы, итоговую цену и условия получения.</li>
                    </ol>

                    <h3>За сколько дней лучше заказывать торт</h3>
                    <p>Рекомендую обращаться примерно за две недели до праздника. Минимальный рекомендуемый срок оформления заказа — одна неделя.</p>
                    <p>Если праздник уже скоро, можно уточнить возможность срочного изготовления. При наличии свободного времени я могу принять заказ за 2–3 дня с доплатой 20%.</p>
                    <p>Это сроки обращения и согласования заказа, а не обещание изготовить любой торт за фиксированное количество дней. Возможность выполнения зависит от загрузки и сложности оформления.</p>

                    <h3>Самовывоз и доставка</h3>
                    <p>Готовый торт можно забрать самостоятельно в районе Коптево в Москве.</p>
                    <p>Также возможна курьерская доставка по Москве и Московской области. Укажите адрес и желаемое время получения — мы согласуем возможность доставки и её стоимость.</p>
                    <div class="birthday-cta-group">
                        <a class="button" href="<?php echo esc_url($birthday_max_url); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать торт в MAX</a>
                        <a class="button button-secondary" href="tel:<?php echo esc_attr($birthday_phone); ?>">Позвонить</a>
                    </div>
                </section>

                <?php echo antonova_landing_faq_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo antonova_landing_page_cross_links_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </article>
    </main>
<?php endwhile; ?>

<?php get_footer(); ?>
