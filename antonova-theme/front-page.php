<?php get_header(); ?>
<?php $price_cards = antonova_get_product_price_cards(); ?>

  <main id="top">
    <section class="hero home-hero">
      <picture>
        <source media="(max-width: 700px)" srcset="<?php echo esc_url(antonova_theme_asset('assets/hero/' . rawurlencode('Дизайн без названия (76).webp'))); ?>">
        <img class="hero-image" src="<?php echo esc_url(antonova_theme_asset('assets/hero/' . rawurlencode('Дизайн без названия (74).webp'))); ?>" width="1920" height="1080" decoding="async" alt="Торт с малиной на бордовом фоне" fetchpriority="high" loading="eager">
      </picture>
      <div class="hero-shade"></div>
      <div class="hero-content reveal">
        <h1 class="home-hero-title"><span>Торты и авторские</span> <span>десерты на заказ</span> <span>в Москве</span></h1>
        <p class="lead home-hero-lead"><span>Индивидуальные вкусы, оформление и внимание к каждой детали.</span><span>От идеи и референса до десерта, который станет частью вашего</span><span>праздника.</span></p>
        <div class="actions">
          <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать в MAX</a>
        </div>
        <ul class="hero-facts" aria-label="Краткая информация">
          <li><span class="fact-icon">♨</span><span><b>Торты</b><?php echo esc_html($price_cards[0]['price'] ?? 'от 3000 ₽/кг'); ?></span></li>
          <li><span class="fact-icon">◇</span><span><b>10 вариантов</b>начинок</span></li>
          <li><span class="fact-icon">□</span><span><b>Оптимальный заказ</b>за 2 недели</span></li>
        </ul>
      </div>
    </section>

    <section id="products" class="section products reveal">
      <div class="section-heading split-heading">
        <div><p class="eyebrow">Что можно заказать</p><h2>Десерты для праздника, подарка<br>или просто особенного дня</h2><p>Выберите готовое направление или расскажите свою идею.<br>Оформление и детали обсудим индивидуально.</p></div>
      </div>
      <div class="product-grid">
        <?php foreach (antonova_get_products() as $product) : ?>
          <?php $product_srcset = antonova_responsive_image_srcset($product['image']); ?>
          <article>
            <img src="<?php echo esc_url($product['image']); ?>"<?php if ($product_srcset) : ?> srcset="<?php echo esc_attr($product_srcset); ?>"<?php endif; ?> alt="<?php echo esc_attr(str_replace("\n", " ", $product['name'])); ?>" loading="lazy" decoding="async" sizes="(max-width: 700px) 170px, (max-width: 1100px) 25vw, 14vw">
            <div>
              <h3><?php echo nl2br(esc_html($product['name'])); ?></h3>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <section class="popular-directions" aria-labelledby="popular-directions-title">
        <h3 id="popular-directions-title">Популярные направления</h3>
        <nav class="popular-directions-grid" aria-label="Популярные направления">
          <a class="popular-direction-card" href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero4.webp" width="1680" height="945" alt="Торт на день рождения" loading="lazy">
            <span class="popular-direction-card-label">Торты на день рождения</span>
            <span class="popular-direction-card-arrow" aria-hidden="true">↗</span>
          </a>
          <a class="popular-direction-card" href="<?php echo esc_url(home_url('/wedding-cakes/')); ?>">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero5-ezgif.com-jpg-to-webp-converter.webp" width="1680" height="945" alt="Свадебный торт на заказ" loading="lazy">
            <span class="popular-direction-card-label">Свадебные торты на заказ</span>
            <span class="popular-direction-card-arrow" aria-hidden="true">↗</span>
          </a>
          <a class="popular-direction-card" href="<?php echo esc_url(home_url('/handmade-chocolate/')); ?>">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero/Hero3.webp" width="1680" height="945" alt="Шоколад ручной работы" loading="lazy">
            <span class="popular-direction-card-label">Шоколад ручной работы</span>
            <span class="popular-direction-card-arrow" aria-hidden="true">↗</span>
          </a>
          <a class="popular-direction-card" href="<?php echo esc_url(home_url('/postcard-cakes/')); ?>">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/works/<?php echo rawurlencode('Торт с цветами.webp'); ?>" alt="Торт-открытка с цветочным оформлением" loading="lazy">
            <span class="popular-direction-card-label">Торты-открытки</span>
            <span class="popular-direction-card-arrow" aria-hidden="true">↗</span>
          </a>
        </nav>
      </section>
    </section>

    <section class="custom-cake reveal" aria-labelledby="custom-cake-title">
      <?php $classic_card_image = antonova_home_category_cover('Классические торты', antonova_theme_asset('assets/optimized/c6097e4cd88b-2268.webp')); ?>
      <?php $thematic_card_image = antonova_home_category_cover('Тематические торты', antonova_theme_asset('assets/works/' . rawurlencode('ezgif.com-jpg-to-webp-converter (15).webp'))); ?>
      <div class="custom-copy">
        <p class="eyebrow">Торты на заказ</p>
        <h2 id="custom-cake-title">Торты с классическим и тематическим оформлением</h2>
        <p class="custom-lead">Создаю индивидуальные торты на заказ в Москве — с классическим декором или тематическим оформлением. Выберите понравившуюся работу из моего портфолио или пришлите фотографию, эскиз или референс. Мы обсудим начинку, размер и детали оформления.</p>
        <div class="custom-cake-grid">
          <article class="custom-cake-card">
            <div class="custom-cake-card-image"><img src="<?php echo esc_url($classic_card_image['image']); ?>" width="<?php echo esc_attr($classic_card_image['width']); ?>" height="<?php echo esc_attr($classic_card_image['height']); ?>" decoding="async" loading="lazy" sizes="(max-width: 700px) calc(100vw - 60px), 480px" alt="Классический торт"></div>
            <h3>Классические торты</h3>
            <p>Праздничные торты с цветами, ягодами, кремовым декором, цифрами и поздравительными надписями. Лаконичное или более нарядное оформление на ваш выбор.</p>
            <a class="custom-cake-card-action" href="<?php echo esc_url(home_url('/?work_category=classic_cakes#works')); ?>" data-work-category-link="classic_cakes">Посмотреть классические торты</a>
          </article>
          <article class="custom-cake-card">
            <div class="custom-cake-card-image"><img src="<?php echo esc_url($thematic_card_image['image']); ?>" width="<?php echo esc_attr($thematic_card_image['width']); ?>" height="<?php echo esc_attr($thematic_card_image['height']); ?>" decoding="async" loading="lazy" sizes="(max-width: 700px) calc(100vw - 60px), 480px" alt="Тематический торт в виде корабля"></div>
            <h3>Тематические торты</h3>
            <p class="custom-cake-card-copy-thematic"><span>Торты по увлечениям и интересам: спорт, путешествия,</span><span>любимые персонажи, необычные формы и праздничные сюжеты.</span><span>Оформление можно обсудить по вашей идее.</span></p>
            <a class="custom-cake-card-action" href="<?php echo esc_url(home_url('/?work_category=thematic_cakes#works')); ?>" data-work-category-link="thematic_cakes">Посмотреть тематические торты</a>
          </article>
        </div>
        <p class="custom-cake-promise">Ваш торт может быть именно таким, каким вы его представляете.</p>
        <a class="button custom-cake-max" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Обсудить идею в MAX</a>
      </div>
    </section>

    <section id="flavors" class="section flavors-section reveal">
      <div class="section-heading flavors-heading"><div><p class="eyebrow">10 вкусов на выбор</p><h2><?php echo esc_html(antonova_content('antonova_flavors_heading', 'Какой будет ваш торт?')); ?></h2></div><p class="flavors-prompt"><b>Не знаете, что выбрать?</b><br>Расскажите, какие вкусы вам нравятся — поможем определиться.</p></div>
      <div class="flavor-grid">
        <?php foreach (antonova_get_flavors() as $flavor) : ?>
          <?php $flavor_image = antonova_optimized_media_url($flavor['image']); ?>
          <article>
            <img src="<?php echo esc_url($flavor_image); ?>" alt="<?php echo esc_attr(str_replace("\n", " ", $flavor['name'])); ?>" loading="lazy">
            <p><?php echo nl2br(esc_html($flavor['name'])); ?><?php if (!empty($flavor['subtitle'])) : ?><br><small><?php echo esc_html($flavor['subtitle']); ?></small><?php endif; ?><?php if (!empty($flavor['price'])) : ?><br><small><?php echo esc_html($flavor['price']); ?></small><?php endif; ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="weight-band reveal">
      <div><p class="eyebrow">Простой ориентир</p><h2>Сколько торта заказать?</h2><p>Рассчитывайте примерно по <b>150–200 г</b> на одного гостя.</p></div>
      <div class="weight-list"><div><strong>10</strong><span>гостей</span><b>1,5–2 кг</b></div><div><strong>15</strong><span>гостей</span><b>2,25–3 кг</b></div><div><strong>20</strong><span>гостей</span><b>3–4 кг</b></div></div>
    </section>

    <section id="works" class="section works reveal">
      <div class="section-heading works-heading"><p class="eyebrow">Мои работы</p><h2 id="works-heading" class="works-title" tabindex="-1">Фото тортов и авторских десертов</h2><p class="works-intro">Здесь представлены мои заказы: классические и тематические торты, <a href="<?php echo esc_url(home_url('/wedding-cakes/')); ?>">свадебные торты</a>, <a href="<?php echo esc_url(home_url('/handmade-chocolate/')); ?>">шоколад ручной работы</a>, <a href="<?php echo esc_url(home_url('/birthday-cakes/')); ?>">торты на день рождения</a> и другие десерты.</p></div>
      <?php $theme_works = antonova_home_file_based_works(); ?>
      <?php if (!empty($theme_works)) : ?>
        <?php
        $antonova_requested_work_category = isset($_GET['work_category']) ? sanitize_key(wp_unslash($_GET['work_category'])) : '';
        $antonova_work_filter_names = array('classic_cakes', 'thematic_cakes', 'wedding_cakes', 'birthday_cakes', 'cake_cards', 'chocolate_desserts');
        if (!in_array($antonova_requested_work_category, $antonova_work_filter_names, true)) {
            $antonova_requested_work_category = '';
        }
        ?>
        <?php if ($antonova_requested_work_category) : ?>
          <noscript><style>#works [data-work-card]{display:none}#works [data-work-card][data-categories~="<?php echo esc_attr($antonova_requested_work_category); ?>"]{display:block}</style></noscript>
        <?php endif; ?>
        <nav class="work-filters home-work-filters" aria-label="Фильтры фотогалереи">
          <a class="work-filter<?php echo '' === $antonova_requested_work_category ? ' is-active' : ''; ?>" href="<?php echo esc_url(home_url('/#works')); ?>" role="button" data-work-filter="all" aria-pressed="<?php echo '' === $antonova_requested_work_category ? 'true' : 'false'; ?>">Все работы</a>
          <?php
          $antonova_work_filters = array(
              'classic_cakes' => 'Классические торты',
              'thematic_cakes' => 'Тематические торты',
              'wedding_cakes' => 'Свадебные торты',
              'birthday_cakes' => 'Торты на день рождения',
              'cake_cards' => 'Торты-открытки',
              'chocolate_desserts' => 'Шоколад и десерты',
          );
          foreach ($antonova_work_filters as $antonova_filter_slug => $antonova_filter_label) :
              $antonova_filter_url = add_query_arg('work_category', $antonova_filter_slug, home_url('/')) . '#works';
              $antonova_filter_active = $antonova_requested_work_category === $antonova_filter_slug;
          ?>
            <a class="work-filter<?php echo $antonova_filter_active ? ' is-active' : ''; ?>" href="<?php echo esc_url($antonova_filter_url); ?>" role="button" data-work-filter="<?php echo esc_attr($antonova_filter_slug); ?>" aria-pressed="<?php echo $antonova_filter_active ? 'true' : 'false'; ?>"><?php echo esc_html($antonova_filter_label); ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="work-grid" data-work-grid>
          <?php foreach ($theme_works as $work) : ?>
            <article class="work-card" data-work-card data-categories="<?php echo esc_attr($work['category']); ?>">
              <a href="<?php echo esc_url($work['image']); ?>" data-work-lightbox data-work-image="<?php echo esc_url($work['image']); ?>" data-work-title="<?php echo esc_attr($work['title']); ?>" aria-label="Увеличить: <?php echo esc_attr($work['title']); ?>">
                <img src="<?php echo esc_url($work['image']); ?>" width="<?php echo esc_attr($work['width']); ?>" height="<?php echo esc_attr($work['height']); ?>" alt="<?php echo esc_attr($work['alt']); ?>" loading="lazy" decoding="async" sizes="(max-width: 700px) calc(50vw - 23px), (max-width: 900px) 25vw, 16vw">
                <span class="work-card-caption"><b><?php echo esc_html($work['title']); ?></b><span>Увеличить фото →</span></span>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
        <button class="works-more" type="button" data-works-more hidden>Показать ещё работы <span aria-hidden="true">↻</span></button>
      <?php else : ?>
        <div class="gallery">
          <?php foreach (antonova_get_gallery() as $work) : ?>
            <figure><img src="<?php echo esc_url($work['image']); ?>" alt="<?php echo esc_attr($work['alt']); ?>" loading="lazy"></figure>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <div class="work-lightbox" data-work-lightbox-dialog hidden role="dialog" aria-modal="true" aria-label="Увеличенное фото работы">
      <button class="work-lightbox-backdrop" type="button" data-work-lightbox-close aria-label="Закрыть увеличенное фото"></button>
      <div class="work-lightbox-content" role="document">
        <button class="work-lightbox-close" type="button" data-work-lightbox-close aria-label="Закрыть">×</button>
        <div class="work-lightbox-viewer" data-work-lightbox-viewer>
          <button class="work-lightbox-arrow work-lightbox-previous" type="button" data-work-lightbox-previous aria-label="Предыдущая фотография">←</button>
          <div class="work-lightbox-source" data-work-lightbox-source>
            <img src="" alt="Увеличенное фото работы" data-work-lightbox-image>
            <span class="work-lightbox-lens" data-work-lightbox-lens hidden aria-hidden="true"></span>
          </div>
          <div class="work-lightbox-zoom" data-work-lightbox-zoom hidden aria-hidden="true"></div>
          <button class="work-lightbox-arrow work-lightbox-next" type="button" data-work-lightbox-next aria-label="Следующая фотография">→</button>
        </div>
        <p data-work-lightbox-title></p>
      </div>
    </div>

    <section id="price" class="price-section reveal">
      <p class="eyebrow">Стоимость продукции</p>
      <div class="product-price-grid">
        <?php foreach ($price_cards as $i => $card) : ?>
          <?php $price = $card['price']; $price_parts = array(); $has_price_parts = preg_match('/^(от\s*)?([\d\s]+)(.*)$/u', $price, $price_parts); ?>
          <article class="product-price-card">
            <div class="product-price-card-title">
              <span class="product-price-icon"><?php echo antonova_product_price_icon($i); ?></span>
              <h3><?php echo nl2br(esc_html($card['name'])); ?></h3>
            </div>
            <p class="product-price-value">
              <?php if ($has_price_parts) : ?>
                <span class="product-price-prefix"><?php echo esc_html(trim($price_parts[1] ?? '')); ?></span>
                <strong><?php echo esc_html(trim($price_parts[2])); ?></strong>
                <span class="product-price-unit"><?php echo esc_html(trim($price_parts[3])); ?></span>
              <?php else : ?>
                <strong><?php echo esc_html($price); ?></strong>
              <?php endif; ?>
            </p>
          </article>
        <?php endforeach; ?>
      </div>
      <a class="button button-light price-order-button" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Рассчитать заказ</a>
    </section>

    <section id="order" class="section order reveal">
      <p class="eyebrow">Как заказать</p><h2>Четыре шага до вашего десерта</h2>
      <ol class="steps"><li><span>01</span><b>Напишите в MAX</b><p>Расскажите, что хотите заказать и к какой дате.</p></li><li><span>02</span><b>Обсудим детали</b><p>Количество гостей, вес, начинку и оформление.</p></li><li><span>03</span><b>Согласуем стоимость</b><p>Рассчитаем декор и при необходимости доставку.</p></li><li><span>04</span><b>Получите заказ</b><p>Самовывозом или удобной курьерской доставкой.</p></li></ol>
    </section>

    <?php $about_image = antonova_optimized_media_url(antonova_content('antonova_about_image', '')); ?>
    <section id="about" class="section about-section reveal<?php echo $about_image ? ' has-about-image' : ''; ?>">
      <div class="about-mobile-heading">
        <p class="eyebrow">Кондитер — по любви</p>
        <h2>Оксана Антонова</h2>
      </div>
      <div class="about-copy">
        <p class="eyebrow">Кондитер — по любви</p>
        <h2>Оксана Антонова</h2>
        <p>Меня зовут Оксана Антонова, и уже 3 года я создаю десерты, которыми с удовольствием радую не только родных и близких, но и своих заказчиков.</p>
        <p>А началось всё совершенно неожиданно — <b>со свадьбы моего крестника.</b></p>
        <p>Я искала торт к этому важному дню, выбирала варианты… а в какой-то момент решила: <b>почему бы не попробовать сделать его самой?</b> Так появился мой первый торт. Результат настолько меня вдохновил, что я захотела узнать о кондитерском искусстве больше — и постепенно этот удивительный мир стал важной частью моей жизни.</p>
        <p>Сегодня я продолжаю учиться, пробовать и осваивать разные направления. Я пока не хочу ограничивать себя чем-то одним — мне интересно открывать новые техники, идеи и возможности.</p>
        <p><b>Классические рецепты? Могу, умею, практикую.</b> Но особенно мне нравится создавать что-то необычное и интересное — десерты, которые хочется сначала рассматривать, а уже потом пробовать.</p>
        <p>Наверное, именно поэтому кондитерское дело так меня увлекает: здесь всегда есть место для нового, для творчества и для того самого приятного момента, когда идея постепенно превращается в готовый десерт.</p>
        <p>Я продолжаю развиваться, экспериментировать и с удовольствием создавать сладости для ваших особенных моментов. <span class="about-heart" aria-label="сердце">♥</span></p>
        <p class="about-closing"><b>Буду рада создать что-нибудь вкусное и для вас.</b></p>
      </div>
      <?php if ($about_image) : ?>
        <figure class="about-photo"><img src="<?php echo esc_url($about_image); ?>" alt="Оксана Антонова" loading="lazy"></figure>
      <?php endif; ?>
    </section>

    <section id="reviews" class="review-section reveal" aria-label="Отзывы">
      <p>Ваш отзыв — лучшая благодарность за мою работу</p>
      <div class="review-links">
        <a class="review-yandex-link" href="https://yandex.ru/profile/134953366795?intent=reviews" target="_blank" rel="noopener noreferrer"><span class="review-yandex-label">Оставить отзыв на Яндекс Картах <span aria-hidden="true">↗</span></span><span class="review-yandex-rating" aria-label="Рейтинг 5 из 5 на основе 2 отзывов"><span aria-hidden="true">★</span> 5,0 · 2 отзыва</span></a>
      </div>
    </section>

    <section class="telegram-cta reveal">
      <p class="eyebrow">Готовы оформить заказ?</p><h2>Расскажите, какой<br>десерт вы хотите</h2><p>Напишите напрямую в MAX. Чтобы быстрее рассчитать заказ, укажите дату, количество гостей, желаемый вес и начинку. Если есть идея оформления — прикрепите фотографию.</p><a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo')); ?>" target="_blank" rel="noopener noreferrer"><img class="max-icon" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/max-logo.svg" alt="" aria-hidden="true">Заказать в MAX</a><a class="phone" href="tel:<?php echo esc_attr(antonova_content('antonova_phone_tel', '+79647281844')); ?>"><?php echo esc_html(antonova_content('antonova_phone_display', '+7 964 728-18-44')); ?></a>
    </section>

    <section id="faq" class="section faq reveal">
      <p class="eyebrow">FAQ</p>
      <h2><?php echo esc_html(antonova_content('antonova_faq_heading', 'Возможно, вы хотели спросить')); ?></h2>
      <div class="faq-list">
        <?php foreach (antonova_get_faq() as $item) : ?>
          <details><summary><?php echo esc_html($item['question']); ?><span>+</span></summary><p><?php echo esc_html($item['answer']); ?></p></details>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

<?php get_footer(); ?>
