<?php
/**
 * Reusable progressive-enhancement thumbnail gallery for the three landing pages.
 *
 * @var array $args
 */

if (!defined('ABSPATH')) {
    exit;
}

$gallery = isset($args['gallery']) && is_array($args['gallery']) ? $args['gallery'] : array();
if (empty($gallery['id']) || empty($gallery['label'])) {
    return;
}

$items = !empty($gallery['items']) && is_array($gallery['items']) ? $gallery['items'] : array();
?>
<section class="landing-thumbnail-gallery" aria-label="<?php echo esc_attr($gallery['label']); ?>" data-thumbnail-gallery>
    <?php if (!empty($items)) : ?>
        <div class="landing-thumbnail-gallery-stage">
            <button class="landing-thumbnail-gallery-arrow landing-thumbnail-gallery-arrow-previous" type="button" data-thumbnail-previous aria-label="Предыдущая фотография">
                <span aria-hidden="true">←</span>
            </button>

            <div class="landing-thumbnail-gallery-panels">
                <?php foreach ($items as $index => $item) : ?>
                    <?php $panel_id = $gallery['id'] . '-photo-' . ($index + 1); ?>
                    <figure class="landing-thumbnail-gallery-panel<?php echo 0 === $index ? ' is-active' : ''; ?>" id="<?php echo esc_attr($panel_id); ?>" data-thumbnail-panel>
                        <a href="<?php echo esc_url($item['src']); ?>" data-thumbnail-open aria-label="Увеличить: <?php echo esc_attr($item['caption']); ?>">
                            <img src="<?php echo esc_url($item['src']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy" decoding="async">
                        </a>
                        <figcaption class="screen-reader-text"><?php echo esc_html($item['caption']); ?></figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>

            <button class="landing-thumbnail-gallery-arrow landing-thumbnail-gallery-arrow-next" type="button" data-thumbnail-next aria-label="Следующая фотография">
                <span aria-hidden="true">→</span>
            </button>
        </div>

        <nav class="landing-thumbnail-gallery-thumbnails" aria-label="Выбрать фотографию">
            <?php foreach ($items as $index => $item) : ?>
                <?php $panel_id = $gallery['id'] . '-photo-' . ($index + 1); ?>
                <a class="landing-thumbnail-gallery-thumbnail<?php echo 0 === $index ? ' is-active' : ''; ?>" href="#<?php echo esc_attr($panel_id); ?>" data-thumbnail-trigger data-thumbnail-target="<?php echo esc_attr($panel_id); ?>" aria-controls="<?php echo esc_attr($panel_id); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>">
                    <img src="<?php echo esc_url($item['src']); ?>" alt="<?php echo esc_attr('Миниатюра: ' . $item['alt']); ?>" loading="lazy" decoding="async">
                    <span class="screen-reader-text"><?php echo esc_html('Показать: ' . $item['caption']); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="landing-thumbnail-gallery-lightbox" data-thumbnail-dialog hidden role="dialog" aria-modal="true" aria-label="Увеличенная фотография">
            <button class="landing-thumbnail-gallery-lightbox-backdrop" type="button" data-thumbnail-dialog-close aria-label="Закрыть просмотр"></button>
            <div class="landing-thumbnail-gallery-lightbox-content" role="document">
                <button class="landing-thumbnail-gallery-lightbox-arrow" type="button" data-thumbnail-dialog-previous aria-label="Предыдущая фотография">←</button>
                <figure>
                    <img data-thumbnail-dialog-image src="" alt="">
                    <figcaption data-thumbnail-dialog-caption></figcaption>
                </figure>
                <button class="landing-thumbnail-gallery-lightbox-arrow" type="button" data-thumbnail-dialog-next aria-label="Следующая фотография">→</button>
                <button class="landing-thumbnail-gallery-lightbox-close" type="button" data-thumbnail-dialog-close aria-label="Закрыть просмотр">×</button>
            </div>
        </div>
    <?php else : ?>
        <div class="landing-thumbnail-gallery-empty" role="status">
            <p><?php echo esc_html(isset($gallery['empty_message']) ? $gallery['empty_message'] : 'Фотографии будут добавлены скоро.'); ?></p>
        </div>
    <?php endif; ?>
</section>
