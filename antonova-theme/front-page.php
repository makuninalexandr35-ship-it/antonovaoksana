<?php get_header(); ?>
<?php $price_cards = antonova_get_product_price_cards(); ?>

  <main id="top">
    <section class="hero">
      <img class="hero-image" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-2268.webp" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-400.webp 400w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-800.webp 800w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/c6097e4cd88b-2268.webp 2268w" sizes="100vw" width="2268" height="4032" decoding="async" alt="Авторский торт домашней кондитерской" fetchpriority="high" loading="eager">
      <div class="hero-shade"></div>
      <div class="hero-content reveal">
        <p class="eyebrow"><?php echo esc_html(antonova_content('antonova_city', 'Домашняя кондитерская · Москва')); ?></p>
        <h1><?php echo esc_html(antonova_content('antonova_h1', 'Торты и авторские десерты на заказ в Москве')); ?></h1>
        <p class="lead"><?php echo esc_html(antonova_content('antonova_lead', 'Индивидуальные вкусы, оформление и внимание к каждой детали. От идеи и референса — до десерта, который станет частью вашего праздника.')); ?></p>
        <div class="actions">
          <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Заказать в Telegram</a>
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
        <div><p class="eyebrow">Что можно заказать</p><h2><?php echo esc_html(antonova_content('antonova_products_heading', 'Десерты для праздника, подарка или просто особенного дня')); ?></h2><p>Выберите готовое направление или расскажите свою идею —<br>оформление и детали обсудим индивидуально.</p></div>
        <a class="text-link" href="<?php echo esc_url(get_post_type_archive_link('antonova_work')); ?>">Смотреть все работы <span>→</span></a>
      </div>
      <div class="product-grid">
        <?php foreach (antonova_get_products() as $product) : ?>
          <article>
            <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr(str_replace("\n", " ", $product['name'])); ?>" loading="lazy">
            <div>
              <h3><?php echo nl2br(esc_html($product['name'])); ?></h3>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="custom-cake reveal">
      <div class="custom-copy">
        <p class="eyebrow">Индивидуальные торты</p>
        <h2>Ваш торт может быть именно таким, каким вы его представляете</h2>
        <p class="custom-lead">Покажите фотографию, рисунок или референс. Выберите любимый вкус — оформление и детали заказа обсудим индивидуально.</p>
        <ul class="benefits">
          <li><span>◇</span><b>Индивидуальный дизайн</b><small>По вашим идеям и референсам</small></li>
          <li><span>♡</span><b>Начинка на ваш вкус</b><small>10 вариантов вкусов</small></li>
          <li><span>♙</span><b>Размер под количество гостей</b><small>Ориентируемся на 150–200 г</small></li>
        </ul>
        <a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Обсудить идею в Telegram</a>
      </div>
      <div class="custom-photo">
        <div class="custom-photo-media"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/323e56724d61-852.webp" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/323e56724d61-400.webp 400w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/323e56724d61-800.webp 800w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/323e56724d61-852.webp 852w" sizes="(max-width: 700px) 100vw, 52vw" width="852" height="1280" decoding="async" alt="Белый многоярусный торт с декором в виде лебедей" loading="lazy"></div>
        <span>Индивидуальный декор<br>для вашей истории <b aria-hidden="true">♡</b></span>
      </div>
    </section>

    <section id="flavors" class="section flavors-section reveal">
      <div class="section-heading flavors-heading"><div><p class="eyebrow">10 вкусов на выбор</p><h2><?php echo esc_html(antonova_content('antonova_flavors_heading', 'Какой будет ваш торт?')); ?></h2></div><p class="flavors-prompt"><b>Не знаете, что выбрать?</b><br>Расскажите, какие вкусы вам нравятся — поможем определиться.</p></div>
      <div class="flavor-grid">
        <?php foreach (antonova_get_flavors() as $flavor) : ?>
          <article>
            <img src="<?php echo esc_url($flavor['image']); ?>" alt="<?php echo esc_attr(str_replace("\n", " ", $flavor['name'])); ?>" loading="lazy">
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
      <div class="section-heading split-heading"><div><p class="eyebrow">Наши работы</p><h2><?php echo esc_html(antonova_content('antonova_works_heading', 'Сладкие шедевры для ваших торжеств')); ?></h2></div><p class="works-intro">Каждый заказ — отдельная история,<br>созданная вручную.</p></div>
      <?php $catalogue_works = antonova_get_catalogue_works(); ?>
      <?php if ($catalogue_works->have_posts()) : ?>
        <div class="work-filters" aria-label="Категории работ">
          <button class="work-filter is-active" type="button" data-work-filter="all">Все</button>
          <?php foreach (get_terms(array('taxonomy' => 'antonova_work_category', 'hide_empty' => false)) as $category) : ?>
            <button class="work-filter" type="button" data-work-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
          <?php endforeach; ?>
        </div>
        <div class="work-grid" data-work-grid>
          <?php while ($catalogue_works->have_posts()) : $catalogue_works->the_post(); ?>
            <?php $work_image = antonova_work_image_url(get_the_ID()); ?>
            <?php if ($work_image) : ?>
              <article class="work-card" data-work-card data-categories="<?php echo esc_attr(implode(' ', antonova_work_categories(get_the_ID()))); ?>">
                <a href="<?php echo esc_url($work_image); ?>" data-work-lightbox data-work-image="<?php echo esc_url($work_image); ?>" data-work-title="<?php echo esc_attr(get_the_title()); ?>" aria-label="Увеличить: <?php echo esc_attr(get_the_title()); ?>">
                  <img src="<?php echo esc_url($work_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                  <span class="work-card-caption"><b><?php the_title(); ?></b><span>Увеличить фото →</span></span>
                </a>
              </article>
            <?php endif; ?>
          <?php endwhile; ?>
        </div>
        <button class="works-more" type="button" data-works-more hidden>Показать ещё работы <span aria-hidden="true">↻</span></button>
        <p class="works-catalogue-link"><a href="<?php echo esc_url(get_post_type_archive_link('antonova_work')); ?>">Открыть весь каталог →</a></p>
        <?php wp_reset_postdata(); ?>
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
          <div class="work-lightbox-source" data-work-lightbox-source>
            <img src="" alt="" data-work-lightbox-image>
            <span class="work-lightbox-lens" data-work-lightbox-lens hidden aria-hidden="true"></span>
          </div>
          <div class="work-lightbox-zoom" data-work-lightbox-zoom hidden aria-hidden="true"></div>
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
      <a class="button button-light price-order-button" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener">Рассчитать заказ</a>
    </section>

    <section id="order" class="section order reveal">
      <p class="eyebrow">Как заказать</p><h2>Четыре шага до вашего десерта</h2>
      <ol class="steps"><li><span>01</span><b>Напишите в Telegram</b><p>Расскажите, что хотите заказать и к какой дате.</p></li><li><span>02</span><b>Обсудим детали</b><p>Количество гостей, вес, начинку и оформление.</p></li><li><span>03</span><b>Согласуем стоимость</b><p>Рассчитаем декор и при необходимости доставку.</p></li><li><span>04</span><b>Получите заказ</b><p>Самовывозом или удобной курьерской доставкой.</p></li></ol>
    </section>

    <section class="telegram-cta reveal">
      <p class="eyebrow">Готовы оформить заказ?</p><h2>Расскажите, какой<br>десерт вы хотите</h2><p>Напишите напрямую в Telegram. Чтобы быстрее рассчитать заказ, укажите дату, количество гостей, желаемый вес и начинку. Если есть идея оформления — прикрепите фотографию.</p><a class="button button-light" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span class="telegram-icon">➤</span> Заказать в Telegram</a><a class="phone" href="tel:<?php echo esc_attr(antonova_content('antonova_phone_tel', '+79647281844')); ?>"><?php echo esc_html(antonova_content('antonova_phone_display', '+7 964 728-18-44')); ?></a>
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
