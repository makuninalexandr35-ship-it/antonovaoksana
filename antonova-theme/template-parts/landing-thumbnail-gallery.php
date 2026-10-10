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
        <div class="landing-thumbnail-gallery-panels">
            <?php foreach ($items as $index => $item) : ?>
                <?php $panel_id = $gallery['id'] . '-photo-' . ($index + 1); ?>
                <figure class="landing-thumbnail-gallery-panel<?php echo 0 === $index ? ' is-active' : ''; ?>" id="<?php echo esc_attr($panel_id); ?>" data-thumbnail-panel>
                    <img src="<?php echo esc_url($item['src']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy" decoding="async">
                    <figcaption class="screen-reader-text"><?php echo esc_html($item['caption']); ?></figcaption>
                </figure>
            <?php endforeach; ?>
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
    <?php else : ?>
        <div class="landing-thumbnail-gallery-empty" role="status">
            <p><?php echo esc_html(isset($gallery['empty_message']) ? $gallery['empty_message'] : 'Фотографии будут добавлены скоро.'); ?></p>
        </div>
    <?php endif; ?>
</section>
