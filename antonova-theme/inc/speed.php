<?php
/**
 * Faster pages on the phone (ADR-174 of the AI SEO Agent, from PageSpeed's findings of 2026-10-18).
 *
 * The theme's photos are 1680-1920 px wide and 300-480 KB each, while a phone shows most of them 170-400 px wide. Resized WebP copies
 * (480, 960 and 1440 px, named by a hash of the original's path) lie in assets/resized/ with a manifest; every theme <img> that has copies
 * and no srcset of its own gets a srcset (the copies plus the original) and, when it has none, a sizes attribute — the browser then loads
 * the copy that fits the screen. The original stays the src, so nothing changes where a browser ignores srcset, and an enlarged photo
 * still shows the original. The page HTML is rewritten once, after the SEO overrides; nothing in the Media Library is touched.
 */

if (!defined('ABSPATH')) {
    exit;
}

function antonova_speed_manifest() {
    static $manifest = null;
    if ($manifest === null) {
        $path = get_template_directory() . '/assets/resized/manifest.json';
        $data = is_readable($path) ? json_decode((string) file_get_contents($path), true) : null;
        $manifest = is_array($data) ? $data : array();
    }
    return $manifest;
}

function antonova_speed_srcset($src) {
    $theme_uri = trailingslashit(get_template_directory_uri());
    $parts = preg_split('~[?#]~', (string) $src, 2);
    $clean = $parts[0];
    if (0 !== strpos($clean, $theme_uri)) {
        return '';
    }
    $relative = rawurldecode(substr($clean, strlen($theme_uri)));
    $manifest = antonova_speed_manifest();
    if (!isset($manifest[$relative]['h'], $manifest[$relative]['w'], $manifest[$relative]['v']) || !is_array($manifest[$relative]['v'])) {
        return '';
    }
    $entry = $manifest[$relative];
    $hash = preg_replace('~[^0-9a-f]~', '', (string) $entry['h']);
    $sources = array();
    foreach ($entry['v'] as $width) {
        $width = (int) $width;
        if ($width > 0 && is_readable(get_template_directory() . '/assets/resized/' . $hash . '-' . $width . '.webp')) {
            $sources[] = $theme_uri . 'assets/resized/' . $hash . '-' . $width . '.webp ' . $width . 'w';
        }
    }
    if (count($sources) === 0) {
        return '';
    }
    $sources[] = $src . ' ' . (int) $entry['w'] . 'w';
    return implode(', ', $sources);
}

function antonova_speed_rewrite_images($html) {
    if (!is_string($html) || false === stripos($html, '<img') || count(antonova_speed_manifest()) === 0) {
        return $html;
    }
    $result = preg_replace_callback('~<img\b[^>]*>~i', function ($match) {
        $tag = $match[0];
        if (preg_match('~\ssrcset\s*=~i', $tag) || !preg_match('~\ssrc\s*=\s*(["\'])([^"\']+)\1~i', $tag, $src)) {
            return $tag;
        }
        $srcset = antonova_speed_srcset(html_entity_decode($src[2], ENT_QUOTES, 'UTF-8'));
        if ('' === $srcset) {
            return $tag;
        }
        $added = ' srcset="' . esc_attr($srcset) . '"';
        if (!preg_match('~\ssizes\s*=~i', $tag)) {
            $hero = preg_match('~\sclass\s*=\s*(["\'])[^"\']*hero~i', $tag);
            $added .= ' sizes="' . ($hero ? '100vw' : '(max-width: 700px) 100vw, 1200px') . '"';
        }
        return preg_replace('~^<img\b~i', '<img' . $added, $tag, 1);
    }, $html);
    return is_string($result) ? $result : $html;
}

add_action('template_redirect', function () {
    if (is_admin() || is_feed() || is_robots() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }
    ob_start('antonova_speed_rewrite_images');
}, 1);

/**
 * The stylesheet and the font faces are written into the page head instead of two separate files the browser has to fetch before it may draw
 * anything (PageSpeed: 0.8-2.5 s on a slow phone connection). Relative url(...) paths are made absolute, since the CSS no longer lives in the
 * theme folder. Returns false when a file cannot be read, and the theme then links the files as before.
 */
function antonova_speed_inline_css() {
    $style_path = get_stylesheet_directory() . '/style.css';
    $fonts_path = get_template_directory() . '/assets/fonts/fonts.css';
    if (!is_readable($style_path) || !is_readable($fonts_path)) {
        return false;
    }
    $absolute = function ($css, $base) {
        return preg_replace_callback('~url\(\s*([\'"]?)(?!data:|https?:|/|#)([^\'")]+)\1\s*\)~i', function ($m) use ($base) {
            return 'url(' . $m[1] . $base . $m[2] . $m[1] . ')';
        }, $css);
    };
    $fonts = $absolute((string) file_get_contents($fonts_path), trailingslashit(get_template_directory_uri()) . 'assets/fonts/');
    $style = $absolute((string) file_get_contents($style_path), trailingslashit(get_stylesheet_directory_uri()));
    if (!is_string($fonts) || !is_string($style) || '' === trim($style)) {
        return false;
    }
    return $fonts . "\n" . $style;
}
