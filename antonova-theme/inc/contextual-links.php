<?php
/**
 * Server-rendered contextual links for the site's key services.
 *
 * The links are added only to ordinary paragraph and list-item text. Existing
 * links, controls, headings and navigation are deliberately left untouched.
 */

if (!defined('ABSPATH')) {
    exit;
}

function antonova_contextual_link_rules() {
    // This commercial landing has an exact, approved link map in its template.
    // Keep its copy untouched and avoid turning words inside paragraphs into
    // additional links or button-shaped elements.
    if (is_page('postcard-cakes')) {
        return array();
    }

    $space = '(?:\s|&nbsp;|&#160;|\x{00A0})+';

    $rules = array(
        'max' => array(
            'pattern'  => '~(?<![\p{L}\p{N}])MAX(?![\p{L}\p{N}])~iu',
            'url'      => antonova_content('antonova_max_url', 'https://max.ru/u/f9LHodD0cOJCnOckQGqCXk8bnyb-OeJWCbBh9WJDCGh-HjAEgUn4_vPfQGo'),
            'external' => true,
        ),
        'wedding_cake' => array(
            'pattern'  => '~(?<![\p{L}\p{N}])свадебный' . $space . 'торт(?![\p{L}\p{N}])~iu',
            'url'      => home_url('/wedding-cakes/'),
            'external' => false,
        ),
        'wedding_cakes' => array(
            'pattern'  => '~(?<![\p{L}\p{N}])торты' . $space . 'на' . $space . 'свадьбу(?![\p{L}\p{N}])~iu',
            'url'      => home_url('/wedding-cakes/'),
            'external' => false,
        ),
        'birthday_cake' => array(
            'pattern'  => '~(?<![\p{L}\p{N}])торт' . $space . 'на' . $space . 'день' . $space . 'рождения(?![\p{L}\p{N}])~iu',
            'url'      => home_url('/birthday-cakes/'),
            'external' => false,
        ),
        'chocolate' => array(
            'pattern'  => '~(?<![\p{L}\p{N}])шоколад(?![\p{L}\p{N}])~iu',
            'url'      => home_url('/handmade-chocolate/'),
            'external' => false,
        ),
    );

    // Do not create links from a landing page back to itself.
    if (is_page('wedding-cakes')) {
        unset($rules['wedding_cake'], $rules['wedding_cakes']);
    }
    if (is_page('birthday-cakes')) {
        unset($rules['birthday_cake']);
    }
    if (is_page('handmade-chocolate')) {
        unset($rules['chocolate']);
    }

    return array_values($rules);
}

function antonova_add_contextual_links($html) {
    if (!is_string($html) || stripos($html, '</body>') === false) {
        return $html;
    }

    $tokens = preg_split(
        '~(<!--.*?-->|<script\b.*?</script>|<style\b.*?</style>|<[^>]*>)~is',
        $html,
        -1,
        PREG_SPLIT_DELIM_CAPTURE
    );
    if (!is_array($tokens)) {
        return $html;
    }

    $eligible_tags = array('p', 'li');
    $skip_tags = array('a', 'button', 'nav', 'summary', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'label', 'option', 'select', 'textarea', 'title', 'code', 'pre');
    $eligible_depth = 0;
    $skip_depth = 0;
    $rules = antonova_contextual_link_rules();

    foreach ($tokens as $index => $token) {
        if ($token === '') {
            continue;
        }

        if ($token[0] === '<') {
            if (preg_match('~^<(/?)([a-zA-Z][a-zA-Z0-9]*)\b~', $token, $tag)) {
                $name = strtolower($tag[2]);
                $closing = $tag[1] === '/';

                if (in_array($name, $eligible_tags, true)) {
                    $eligible_depth = $closing ? max(0, $eligible_depth - 1) : $eligible_depth + 1;
                }
                if (in_array($name, $skip_tags, true)) {
                    $skip_depth = $closing ? max(0, $skip_depth - 1) : $skip_depth + 1;
                }
            }
            continue;
        }

        if ($eligible_depth < 1 || $skip_depth > 0) {
            continue;
        }

        foreach ($rules as $rule) {
            $replacement = preg_replace_callback(
                $rule['pattern'],
                static function ($match) use ($rule) {
                    $attributes = ' class="contextual-anchor" href="' . esc_url($rule['url']) . '"';
                    if ($rule['external']) {
                        $attributes .= ' target="_blank" rel="noopener noreferrer"';
                    }
                    return '<a' . $attributes . '>' . $match[0] . '</a>';
                },
                $tokens[$index]
            );
            if (is_string($replacement)) {
                $tokens[$index] = $replacement;
            }
        }
    }

    return implode('', $tokens);
}

/**
 * Start before the SEO Agent output buffer so agent-added text is processed as
 * well. This outer buffer receives the final HTML after inner buffers finish.
 */
add_action('template_redirect', function () {
    if (is_admin() || is_feed() || is_robots() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    ob_start('antonova_add_contextual_links');
}, -1);
