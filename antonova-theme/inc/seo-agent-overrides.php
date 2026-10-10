<?php
/**
 * SEO overrides of the AI SEO Agent, applied by the theme (ADR-086).
 *
 * The site is changed only through code: the agent writes seo/overrides.json in this repository, the deploy workflow copies it into the theme as
 * seo/overrides.json, and this file applies it to the rendered page exactly like the SEO Agent Connector plugin 1.4.0 did (same fields, same rules):
 * title, meta description, canonical, image alt texts, internal link fixes and additions, one JSON-LD block, the text of the one H1, owner text sections.
 * It never edits posts, options or files; removing an entry from the file restores the page. While the connector plugin is active this file does nothing,
 * so the two can never apply the same change twice.
 */

if (!defined('ABSPATH')) {
    exit;
}

function antonova_seo_file($name) {
    $path = get_template_directory() . '/seo/' . $name;
    if (!is_readable($path)) {
        return null;
    }
    $data = json_decode((string) file_get_contents($path), true);
    return is_array($data) ? $data : null;
}

function antonova_seo_path_key($url) {
    $path = wp_parse_url($url, PHP_URL_PATH);
    $path = is_string($path) ? $path : '/';
    $path = '/' . trim(strtolower($path), '/');
    return $path === '/' ? '/' : $path . '/';
}

// The IndexNow key file /<key>.txt (the key comes from seo/config.json, the repository's seo-agent.config.json).
add_action('init', function () {
    if (function_exists('seo_agent_connector_render')) {
        return;
    }
    $config = antonova_seo_file('config.json');
    $key = is_array($config) && isset($config['indexnow_key']) ? $config['indexnow_key'] : '';
    if (!is_string($key) || !preg_match('/^[A-Za-z0-9-]{8,128}$/', $key)) {
        return;
    }
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    if (wp_parse_url($request_uri, PHP_URL_PATH) === '/' . $key . '.txt') {
        header('Content-Type: text/plain; charset=utf-8');
        header('X-Robots-Tag: noindex');
        echo $key;
        exit;
    }
}, 0);

add_action('template_redirect', function () {
    if (function_exists('seo_agent_connector_render')) {
        return;
    }
    if (is_admin() || is_feed() || is_robots() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }
    $all = antonova_seo_file('overrides.json');
    if (!is_array($all) || count($all) === 0) {
        return;
    }
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $key = antonova_seo_path_key(home_url($request_uri));
    if ($key === '/__seo_agent_previous__/' || !isset($all[$key]) || !is_array($all[$key])) {
        return;
    }
    $overrides = $all[$key];
    ob_start(function ($html) use ($overrides) {
        return antonova_seo_render($html, $overrides);
    });
}, 0);

function antonova_seo_is_list($value) {
    return is_array($value) && ($value === array() || array_keys($value) === range(0, count($value) - 1));
}
function antonova_seo_len($text) {
    return function_exists('mb_strlen') ? mb_strlen($text) : strlen($text);
}

/**
 * Text of one owner block: {items: [{h: heading, p: [paragraph, ...]}], where: end|before|after, anchor: words of a paragraph}.
 * Plain text only (no angle brackets), single spaces, bounded sizes. Returns null when valid, otherwise a reason.
 */
function antonova_seo_check_section($block) {
    if (!is_array($block) || !isset($block['items']) || !is_array($block['items']) || !isset($block['where']) || !is_string($block['where'])) {
        return 'BLOCK_SHAPE_INVALID';
    }
    $keys = array_keys($block);
    sort($keys);
    if ($keys !== array('anchor', 'items', 'where') && $keys !== array('items', 'where')) {
        return 'BLOCK_KEYS_INVALID';
    }
    if (!in_array($block['where'], array('end', 'before', 'after'), true)) {
        return 'PLACEMENT_INVALID';
    }
    if ($block['where'] === 'end' && isset($block['anchor'])) {
        return 'ANCHOR_NOT_ALLOWED_AT_THE_END';
    }
    if ($block['where'] !== 'end') {
        $anchor = isset($block['anchor']) ? $block['anchor'] : null;
        if (!is_string($anchor) || antonova_seo_len($anchor) < 8 || antonova_seo_len($anchor) > 120 || strpbrk($anchor, '<>') !== false) {
            return 'ANCHOR_INVALID';
        }
    }
    $count = count($block['items']);
    if ($count < 1 || $count > 8 || !antonova_seo_is_list($block['items'])) {
        return 'ITEMS_MUST_BE_1_TO_8';
    }
    $total = 0;
    foreach ($block['items'] as $item) {
        if (!is_array($item) || !isset($item['h']) || !is_string($item['h']) || !isset($item['p']) || !is_array($item['p']) || count($item) !== 2) {
            return 'ITEM_SHAPE_INVALID';
        }
        $h = $item['h'];
        if (antonova_seo_len($h) < 3 || antonova_seo_len($h) > 120 || strpbrk($h, '<>') !== false || $h !== trim(preg_replace('/\s+/u', ' ', $h))) {
            return 'HEADING_INVALID';
        }
        $total += antonova_seo_len($h);
        if (count($item['p']) < 1 || count($item['p']) > 6 || !antonova_seo_is_list($item['p'])) {
            return 'PARAGRAPHS_MUST_BE_1_TO_6';
        }
        foreach ($item['p'] as $p) {
            if (!is_string($p) || antonova_seo_len($p) < 20 || antonova_seo_len($p) > 1200 || strpbrk($p, '<>') !== false || $p !== trim(preg_replace('/\s+/u', ' ', $p))) {
                return 'PARAGRAPH_INVALID';
            }
            $total += antonova_seo_len($p);
        }
    }
    if ($total > 8000) {
        return 'TEXT_TOO_LONG';
    }
    return null;
}
/**
 * Replaces the first tag matching $pattern with $tag, or inserts $tag before </head>.
 * Callbacks are used so that characters such as "$" in a value are never read as
 * replacement references.
 */
function antonova_seo_put_head_tag($html, $pattern, $tag) {
    $count = 0;
    $result = preg_replace_callback($pattern, function () use ($tag) {
        return $tag;
    }, $html, 1, $count);
    if (!is_string($result)) {
        return $html;
    }
    if ($count > 0) {
        return $result;
    }
    $at = stripos($html, '</head>');
    return $at === false ? $html : substr($html, 0, $at) . $tag . "\n" . substr($html, $at);
}

/**
 * Turns the first free occurrence of each anchor phrase into a link. A phrase is linked only inside a paragraph
 * and never inside another link, a heading, a button or the navigation, so no existing markup is nested or broken.
 * Removing the override restores the page exactly.
 */
function antonova_seo_add_links($html, $links) {
    $pending = array();
    foreach ($links as $anchor => $url) {
        if (is_string($anchor) && $anchor !== '' && is_string($url) && $url !== '') {
            $pending[$anchor] = $url;
        }
    }
    if (count($pending) === 0) {
        return $html;
    }
    $tokens = preg_split('~(<!--.*?-->|<script\b.*?</script>|<style\b.*?</style>|<[^>]*>)~is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    if (!is_array($tokens)) {
        return $html;
    }
    $skip_tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'button', 'nav', 'select', 'textarea', 'option', 'label', 'figcaption', 'title');
    $in_a = 0;
    $in_p = 0;
    $in_skip = 0;
    foreach ($tokens as $index => $token) {
        if ($token === '') {
            continue;
        }
        if ($token[0] === '<') {
            if (preg_match('~^<(/?)([a-zA-Z][a-zA-Z0-9]*)~', $token, $tag)) {
                $name = strtolower($tag[2]);
                $closing = $tag[1] === '/';
                if ($name === 'a') {
                    $in_a = $closing ? max(0, $in_a - 1) : $in_a + 1;
                } elseif ($name === 'p') {
                    $in_p = $closing ? max(0, $in_p - 1) : $in_p + 1;
                } elseif (in_array($name, $skip_tags, true)) {
                    $in_skip = $closing ? max(0, $in_skip - 1) : $in_skip + 1;
                }
            }
            continue;
        }
        if ($in_p < 1 || $in_a > 0 || $in_skip > 0) {
            continue;
        }
        foreach ($pending as $anchor => $url) {
            $count = 0;
            $replaced = preg_replace_callback('~(?<![\p{L}\p{N}])' . preg_quote($anchor, '~') . '(?![\p{L}\p{N}])~iu', function ($match) use ($url) {
                return '<a href="' . esc_url($url) . '">' . $match[0] . '</a>';
            }, $token, 1, $count);
            if (is_string($replaced) && $count > 0) {
                $tokens[$index] = $replaced;
                unset($pending[$anchor]);
                break;
            }
        }
        if (count($pending) === 0) {
            break;
        }
    }
    return implode('', $tokens);
}

/**
 * Where an owner block goes: before or after the first paragraph that contains the anchor words, or after the last paragraph of the
 * main content area (<main>, else <article>, else the body). Returns a byte offset in $html, or null when no place is found
 * (then nothing is inserted and the agent's own check rolls the change back).
 */
function antonova_seo_section_position($html, $where, $anchor) {
    $start = 0;
    $end = strlen($html);
    foreach (array('main', 'article', 'body') as $tag) {
        $open = stripos($html, '<' . $tag);
        $close = strripos($html, '</' . $tag . '>');
        if ($open !== false && $close !== false && $close > $open) {
            $start = $open;
            $end = $close;
            break;
        }
    }
    $region = substr($html, $start, $end - $start);
    $found = @preg_match_all('~<p\b[^>]*>(.*?)</p>~is', $region, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
    if (!$found) {
        return null;
    }
    if ($where === 'end') {
        $last = $matches[count($matches) - 1][0];
        return $start + $last[1] + strlen($last[0]);
    }
    $needle = trim(preg_replace('/\s+/u', ' ', $anchor));
    foreach ($matches as $m) {
        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(wp_strip_all_tags($m[1][0]), ENT_QUOTES, 'UTF-8')));
        $hit = function_exists('mb_stripos') ? mb_stripos($text, $needle) : stripos($text, $needle);
        if ($hit !== false) {
            return $where === 'before' ? $start + $m[0][1] : $start + $m[0][1] + strlen($m[0][0]);
        }
    }
    return null;
}

function antonova_seo_add_sections($html, $sections) {
    foreach ($sections as $id => $block) {
        if (!is_string($id) || !is_array($block) || strpos($html, 'data-seo-agent-section="' . $id . '"') !== false) {
            continue;
        }
        if (antonova_seo_check_section($block) !== null) {
            continue;
        }
        $markup = '<section class="seo-agent-section" data-seo-agent-section="' . esc_attr($id) . '">';
        foreach ($block['items'] as $item) {
            $markup .= '<h2>' . esc_html($item['h']) . '</h2>';
            foreach ($item['p'] as $paragraph) {
                $markup .= '<p>' . esc_html($paragraph) . '</p>';
            }
        }
        $markup .= '</section>';
        $position = antonova_seo_section_position($html, $block['where'], isset($block['anchor']) ? $block['anchor'] : '');
        if ($position === null) {
            continue;
        }
        $html = substr($html, 0, $position) . $markup . substr($html, $position);
    }
    return $html;
}

/**
 * Replaces the text of the page's H1, only when the page has exactly one H1 (an output-level override cannot insert a heading, and with several
 * headings the first one may be a card title). The tag and its attributes stay; the inner markup is replaced by the escaped text.
 */
function antonova_seo_replace_h1($html, $text) {
    $count = @preg_match_all('~<h1\b[^>]*>.*?</h1>~is', $html, $found);
    if ($count !== 1) {
        return $html;
    }
    $result = preg_replace_callback('~(<h1\b[^>]*>).*?(</h1>)~is', function ($match) use ($text) {
        return $match[1] . esc_html($text) . $match[2];
    }, $html, 1);
    return is_string($result) ? $result : $html;
}

function antonova_seo_render($html, $overrides) {
    if (!is_string($html) || stripos($html, '</head>') === false) {
        return $html;
    }
    if (!empty($overrides['title']) && is_string($overrides['title'])) {
        $html = antonova_seo_put_head_tag($html, '~<title\b[^>]*>.*?</title>~is', '<title>' . esc_html($overrides['title']) . '</title>');
    }
    if (!empty($overrides['meta_description']) && is_string($overrides['meta_description'])) {
        $html = antonova_seo_put_head_tag($html, '~<meta\s+name=(["\'])description\1[^>]*>~i',
            '<meta name="description" content="' . esc_attr($overrides['meta_description']) . '" />');
    }
    if (!empty($overrides['canonical_url']) && is_string($overrides['canonical_url'])) {
        $html = antonova_seo_put_head_tag($html, '~<link\s+rel=(["\'])canonical\1[^>]*>~i',
            '<link rel="canonical" href="' . esc_url($overrides['canonical_url']) . '" />');
    }
    if (!empty($overrides['alt_text']) && is_array($overrides['alt_text'])) {
        $alts = $overrides['alt_text'];
        $result = preg_replace_callback('~<img\b[^>]*>~i', function ($match) use ($alts) {
            $img = $match[0];
            if (!preg_match('~\bsrc=(["\'])(.*?)\1~i', $img, $src)) {
                return $img;
            }
            $key = html_entity_decode($src[2], ENT_QUOTES);
            if (!isset($alts[$key]) || !is_string($alts[$key])) {
                return $img;
            }
            // Only images without a meaningful alt are touched.
            if (preg_match('~\balt=(["\'])\s*\S.*?\1~is', $img)) {
                return $img;
            }
            $without_alt = preg_replace('~\salt=(["\']).*?\1~is', '', $img);
            $without_alt = is_string($without_alt) ? $without_alt : $img;
            return '<img alt="' . esc_attr($alts[$key]) . '"' . substr($without_alt, 4);
        }, $html);
        $html = is_string($result) ? $result : $html;
    }
    if (!empty($overrides['schema']) && is_array($overrides['schema'])) {
        $json = wp_json_encode($overrides['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        if (is_string($json) && $json !== '') {
            $html = antonova_seo_put_head_tag($html, '~<script\b[^>]*\bid=(["\'])seo-agent-ld\1[^>]*>.*?</script>~is',
                '<script type="application/ld+json" id="seo-agent-ld">' . $json . '</script>');
        }
    }
    if (!empty($overrides['links_added']) && is_array($overrides['links_added'])) {
        $html = antonova_seo_add_links($html, $overrides['links_added']);
    }
    if (!empty($overrides['h1']) && is_string($overrides['h1'])) {
        $html = antonova_seo_replace_h1($html, $overrides['h1']);
    }
    if (!empty($overrides['sections']) && is_array($overrides['sections'])) {
        $html = antonova_seo_add_sections($html, $overrides['sections']);
    }
    if (!empty($overrides['links']) && is_array($overrides['links'])) {
        foreach ($overrides['links'] as $from => $to) {
            if (!is_string($from) || !is_string($to) || $to === '') {
                continue;
            }
            // The page may use the absolute or the root-relative form of the same link.
            foreach (array_unique(array($from, wp_make_link_relative($from))) as $variant) {
                $html = str_replace(
                    array('href="' . esc_url($variant) . '"', "href='" . esc_url($variant) . "'"),
                    array('href="' . esc_url($to) . '"', "href='" . esc_url($to) . "'"),
                    $html
                );
            }
        }
    }
    return $html;
}
