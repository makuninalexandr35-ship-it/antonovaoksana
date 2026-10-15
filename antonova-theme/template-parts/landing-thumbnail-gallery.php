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
$show_thumbnails = !array_key_exists('show_thumbnails', $gallery) || (bool) $gallery['show_thumbnails'];
$show_details = !empty($gallery['show_details']);
$has_multiple_items = count($items) > 1;
$gallery_classes = 'landing-thumbnail-gallery';
if (!empty($gallery['class'])) {
    $gallery_classes .= ' ' . sanitize_html_class($gallery['class']);
}
?>
<section class="<?php echo esc_attr($gallery_classes); ?>" aria-label="<?php echo esc_attr($gallery['label']); ?>" data-thumbnail-gallery>
    <?php if (!empty($items)) : ?>
        <div class="landing-thumbnail-gallery-stage">
            <?php if ($has_multiple_items) : ?>
                <button class="landing-thumbnail-gallery-arrow landing-thumbnail-gallery-arrow-previous" type="button" data-thumbnail-previous aria-label="Предыдущая фотография">
                    <span aria-hidden="true">←</span>
                </button>
            <?php endif; ?>

            <div class="landing-thumbnail-gallery-panels">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $panel_id = $gallery['id'] . '-photo-' . ($index + 1);
                    $choice_id = $gallery['id'] . '-choice-' . ($index + 1);
                    $previous_index = (0 === $index) ? count($items) - 1 : $index - 1;
                    $next_index = (count($items) - 1 === $index) ? 0 : $index + 1;
                    $previous_choice_id = $gallery['id'] . '-choice-' . ($previous_index + 1);
                    $next_choice_id = $gallery['id'] . '-choice-' . ($next_index + 1);
                    ?>
                    <input class="landing-thumbnail-gallery-choice screen-reader-text" type="radio" name="<?php echo esc_attr($gallery['id'] . '-selection'); ?>" id="<?php echo esc_attr($choice_id); ?>" aria-label="<?php echo esc_attr('Показать: ' . $item['caption']); ?>" <?php checked(0, $index); ?>>
                    <figure class="landing-thumbnail-gallery-panel<?php echo 0 === $index ? ' is-active' : ''; ?>" id="<?php echo esc_attr($panel_id); ?>" data-thumbnail-panel>
                        <?php if ($has_multiple_items) : ?>
                            <label class="landing-thumbnail-gallery-html-arrow" for="<?php echo esc_attr($previous_choice_id); ?>" aria-label="Предыдущая фотография">
                                <span aria-hidden="true">←</span>
                            </label>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($item['src']); ?>" data-thumbnail-open aria-label="Увеличить: <?php echo esc_attr($item['caption']); ?>">
                            <img src="<?php echo esc_url($item['src']); ?>" alt="<?php echo esc_attr($item['alt']); ?>" loading="lazy" decoding="async">
                        </a>
                        <?php if ($has_multiple_items) : ?>
                            <label class="landing-thumbnail-gallery-html-arrow" for="<?php echo esc_attr($next_choice_id); ?>" aria-label="Следующая фотография">
                                <span aria-hidden="true">→</span>
                            </label>
                        <?php endif; ?>
                        <?php if ($show_details) : ?>
                            <figcaption class="landing-thumbnail-gallery-caption">
                                <strong><?php echo esc_html($item['caption']); ?></strong>
                                <?php if (!empty($item['description'])) : ?><span><?php echo esc_html($item['description']); ?></span><?php endif; ?>
                                <?php if (!empty($item['price'])) : ?><span class="landing-thumbnail-gallery-price"><?php echo esc_html($item['price']); ?></span><?php endif; ?>
                            </figcaption>
                        <?php else : ?>
                            <figcaption class="screen-reader-text"><?php echo esc_html($item['caption']); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>

            <?php if ($has_multiple_items) : ?>
                <button class="landing-thumbnail-gallery-arrow landing-thumbnail-gallery-arrow-next" type="button" data-thumbnail-next aria-label="Следующая фотография">
                    <span aria-hidden="true">→</span>
                </button>
            <?php endif; ?>
        </div>

        <?php if ($show_thumbnails && $has_multiple_items) : ?>
            <nav class="landing-thumbnail-gallery-thumbnails" aria-label="Выбрать фотографию">
                <?php foreach ($items as $index => $item) : ?>
                    <?php $panel_id = $gallery['id'] . '-photo-' . ($index + 1); ?>
                    <?php $choice_id = $gallery['id'] . '-choice-' . ($index + 1); ?>
                    <label class="landing-thumbnail-gallery-thumbnail<?php echo 0 === $index ? ' is-active' : ''; ?>" for="<?php echo esc_attr($choice_id); ?>" data-thumbnail-trigger data-thumbnail-target="<?php echo esc_attr($panel_id); ?>" aria-controls="<?php echo esc_attr($panel_id); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>">
                        <img src="<?php echo esc_url($item['src']); ?>" alt="<?php echo esc_attr('Миниатюра: ' . $item['alt']); ?>" loading="lazy" decoding="async">
                        <span class="screen-reader-text"><?php echo esc_html('Показать: ' . $item['caption']); ?></span>
                    </label>
                <?php endforeach; ?>
            </nav>
        <?php elseif (!$show_thumbnails) : ?>
            <nav class="screen-reader-text" aria-label="Выбрать фотографию">
                <?php foreach ($items as $index => $item) : ?>
                    <?php $panel_id = $gallery['id'] . '-photo-' . ($index + 1); ?>
                    <?php $choice_id = $gallery['id'] . '-choice-' . ($index + 1); ?>
                    <label for="<?php echo esc_attr($choice_id); ?>" data-thumbnail-trigger data-thumbnail-target="<?php echo esc_attr($panel_id); ?>" aria-controls="<?php echo esc_attr($panel_id); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"><?php echo esc_html('Показать: ' . $item['caption']); ?></label>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <div class="landing-thumbnail-gallery-lightbox" data-thumbnail-dialog hidden role="dialog" aria-modal="true" aria-label="Увеличенная фотография">
            <button class="landing-thumbnail-gallery-lightbox-backdrop" type="button" data-thumbnail-dialog-close aria-label="Закрыть просмотр"></button>
            <div class="landing-thumbnail-gallery-lightbox-content" role="document">
                <?php if ($has_multiple_items) : ?><button class="landing-thumbnail-gallery-lightbox-arrow" type="button" data-thumbnail-dialog-previous aria-label="Предыдущая фотография">←</button><?php endif; ?>
                <figure>
                    <img data-thumbnail-dialog-image src="" alt="">
                    <figcaption data-thumbnail-dialog-caption></figcaption>
                </figure>
                <?php if ($has_multiple_items) : ?><button class="landing-thumbnail-gallery-lightbox-arrow" type="button" data-thumbnail-dialog-next aria-label="Следующая фотография">→</button><?php endif; ?>
                <button class="landing-thumbnail-gallery-lightbox-close" type="button" data-thumbnail-dialog-close aria-label="Закрыть просмотр">×</button>
            </div>
        </div>
    <?php else : ?>
        <div class="landing-thumbnail-gallery-empty" role="status">
            <p><?php echo esc_html(isset($gallery['empty_message']) ? $gallery['empty_message'] : 'Фотографии будут добавлены скоро.'); ?></p>
        </div>
    <?php endif; ?>
</section>
