<?php
/**
 * The Signal — functions.php
 */

defined('ABSPATH') || exit;

/* ─────────────────────────────────────────
   THEME SETUP
───────────────────────────────────────── */
function signal_setup() {
    load_theme_textdomain('the-signal', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Post formats
    add_theme_support('post-formats', ['aside', 'gallery', 'link', 'quote', 'status', 'video']);

    // Navigation menus
    register_nav_menus([
        'primary'    => __('Primary Navigation', 'the-signal'),
        'footer-one' => __('Footer Column 1', 'the-signal'),
        'footer-two' => __('Footer Column 2', 'the-signal'),
    ]);

    // Image sizes
    add_image_size('signal-hero',    1200, 600, true);
    add_image_size('signal-card',    600,  400, true);
    add_image_size('signal-thumb',   200,  160, true);
    add_image_size('signal-related', 600,  280, true);
}
add_action('after_setup_theme', 'signal_setup');

/* ─────────────────────────────────────────
   ENQUEUE SCRIPTS & STYLES
───────────────────────────────────────── */
function signal_scripts() {
    // Google Fonts
    wp_enqueue_style('signal-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=DM+Serif+Display:ital@0;1&family=IBM+Plex+Mono:wght@400;600&family=Literata:ital,opsz,wght@0,7..72,300;0,7..72,400;0,7..72,600;1,7..72,300;1,7..72,400&display=swap',
        [], null
    );

    // Main stylesheet
    wp_enqueue_style('signal-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        ['signal-fonts'], '1.0.0'
    );

    // WordPress style.css (required for theme recognition)
    wp_enqueue_style('signal-style', get_stylesheet_uri(), ['signal-theme'], '1.0.0');

    // Main JS
    wp_enqueue_script('signal-js',
        get_template_directory_uri() . '/assets/js/theme.js',
        [], '1.0.0', true
    );

    // Comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'signal_scripts');

/* ─────────────────────────────────────────
   CONTENT WIDTH
───────────────────────────────────────── */
if (!isset($content_width)) {
    $content_width = 800;
}

/* ─────────────────────────────────────────
   WIDGETS / SIDEBARS
───────────────────────────────────────── */
function signal_widgets_init() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'the-signal'),
        'id'            => 'sidebar-main',
        'description'   => __('Widgets in the right sidebar.', 'the-signal'),
        'before_widget' => '<div id="%1$s" class="widget-section %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget-title">',
        'after_title'   => '</div>',
    ]);

    register_sidebar([
        'name'          => __('Footer Widget Area', 'the-signal'),
        'id'            => 'sidebar-footer',
        'description'   => __('Widgets in the footer.', 'the-signal'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'signal_widgets_init');

/* ─────────────────────────────────────────
   CUSTOM CATEGORIES (COLOURS)
───────────────────────────────────────── */
function signal_get_cat_class($cat_slug) {
    $map = [
        'geopolitics' => 'chip-geo',
        'fact-check'  => 'chip-fact',
        'sports'      => 'chip-sport',
        'tech'        => 'chip-tech',
        'media'       => 'chip-media',
    ];
    return $map[$cat_slug] ?? 'chip-default';
}

function signal_get_thumb_class($cat_slug) {
    $map = [
        'geopolitics' => 'thumb-bg-geo',
        'fact-check'  => 'thumb-bg-fact',
        'sports'      => 'thumb-bg-sport',
        'tech'        => 'thumb-bg-tech',
        'media'       => 'thumb-bg-media',
    ];
    return $map[$cat_slug] ?? 'thumb-bg-default';
}

function signal_get_section_class($cat_slug) {
    $map = [
        'geopolitics' => 's-geo',
        'fact-check'  => 's-fact',
        'sports'      => 's-sport',
        'tech'        => 's-tech',
        'media'       => 's-media',
    ];
    return $map[$cat_slug] ?? 's-all';
}

function signal_primary_cat($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $cats = get_the_category($post_id);
    return $cats ? $cats[0] : null;
}

/* ─────────────────────────────────────────
   READING TIME
───────────────────────────────────────── */
function signal_reading_time($post_id = null) {
    $content = get_post_field('post_content', $post_id ?: get_the_ID());
    $words   = str_word_count(strip_tags($content));
    $mins    = max(1, ceil($words / 200));
    return $mins . ' min read';
}

/* ─────────────────────────────────────────
   TICKER ITEMS (custom option or latest posts)
───────────────────────────────────────── */
function signal_ticker_items() {
    $custom = get_option('signal_ticker_text', '');
    if ($custom) {
        $items = array_filter(array_map('trim', explode('|', $custom)));
    } else {
        $items = [];
        $q = new WP_Query(['posts_per_page' => 8, 'post_status' => 'publish']);
        while ($q->have_posts()) {
            $q->the_post();
            $items[] = get_the_title();
        }
        wp_reset_postdata();
    }
    if (empty($items)) $items = ['Welcome to The Signal — Truth. Context. Clarity.'];
    return array_merge($items, $items); // duplicate for seamless loop
}

/* ─────────────────────────────────────────
   EXCERPT
───────────────────────────────────────── */
function signal_excerpt($length = 20) {
    $excerpt = get_the_excerpt();
    if (!$excerpt) {
        $excerpt = wp_trim_words(get_the_content(), $length);
    }
    return esc_html($excerpt);
}

add_filter('excerpt_length', function() { return 25; });
add_filter('excerpt_more',   function() { return '…'; });

/* ─────────────────────────────────────────
   BREADCRUMBS
───────────────────────────────────────── */
function signal_breadcrumbs() {
    echo '<div class="breadcrumb">';
    echo '<a href="' . home_url() . '">' . __('Home', 'the-signal') . '</a>';
    echo '<span class="bc-sep">/</span>';

    if (is_single()) {
        $cat = signal_primary_cat();
        if ($cat) {
            echo '<a href="' . get_category_link($cat->term_id) . '">' . esc_html($cat->name) . '</a>';
            echo '<span class="bc-sep">/</span>';
        }
        echo '<span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_category()) {
        echo '<span>' . single_cat_title('', false) . '</span>';
    } elseif (is_page()) {
        echo '<span>' . get_the_title() . '</span>';
    } elseif (is_search()) {
        echo '<span>' . __('Search Results', 'the-signal') . '</span>';
    } elseif (is_archive()) {
        echo '<span>' . get_the_archive_title() . '</span>';
    }
    echo '</div>';
}

/* ─────────────────────────────────────────
   CUSTOMIZER OPTIONS
───────────────────────────────────────── */
function signal_customizer($wp_customize) {
    // ── GENERAL ──
    $wp_customize->add_section('signal_general', [
        'title'    => __('The Signal: General', 'the-signal'),
        'priority' => 30,
    ]);

    // Tagline
    $wp_customize->add_setting('signal_tagline', ['default' => 'Truth. Context. Clarity.', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('signal_tagline', ['label' => __('Header Tagline', 'the-signal'), 'section' => 'signal_general', 'type' => 'text']);

    // Ticker
    $wp_customize->add_setting('signal_ticker_text', ['default' => '', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('signal_ticker_text', ['label' => __('Breaking Ticker (separate with |)', 'the-signal'), 'section' => 'signal_general', 'type' => 'textarea']);

    // Newsletter heading
    $wp_customize->add_setting('signal_nl_heading', ['default' => 'The Daily Signal', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('signal_nl_heading', ['label' => __('Newsletter Widget Heading', 'the-signal'), 'section' => 'signal_general', 'type' => 'text']);

    $wp_customize->add_setting('signal_nl_desc', ['default' => 'One briefing. Five stories. The world, in context — delivered by 7 AM.', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('signal_nl_desc', ['label' => __('Newsletter Widget Description', 'the-signal'), 'section' => 'signal_general', 'type' => 'textarea']);

    // ── ABOUT ──
    $wp_customize->add_section('signal_about', [
        'title'    => __('The Signal: About Page', 'the-signal'),
        'priority' => 40,
    ]);
    $wp_customize->add_setting('signal_about_mission', ['default' => '', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('signal_about_mission', ['label' => __('Mission Statement', 'the-signal'), 'section' => 'signal_about', 'type' => 'textarea']);
}
add_action('customize_register', 'signal_customizer');

/* ─────────────────────────────────────────
   CATEGORY METADATA (color / description)
───────────────────────────────────────── */
// Store reading progress via AJAX (optional — JS handles it client-side via localStorage)

/* ─────────────────────────────────────────
   FACT-CHECK POST META
───────────────────────────────────────── */
function signal_fact_check_metabox() {
    add_meta_box('signal_fact_check', __('Fact Check Details', 'the-signal'), 'signal_fact_check_cb', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'signal_fact_check_metabox');

function signal_fact_check_cb($post) {
    wp_nonce_field('signal_fc_nonce', 'signal_fc_nonce_field');
    $verdict = get_post_meta($post->ID, '_fc_verdict', true);
    $claim   = get_post_meta($post->ID, '_fc_claim', true);
    $source  = get_post_meta($post->ID, '_fc_source', true);
    ?>
    <p><label><strong><?php _e('Verdict', 'the-signal'); ?></strong></label><br>
    <select name="signal_fc_verdict" style="width:100%;margin-top:4px">
        <option value="">— Select —</option>
        <?php foreach (['false'=>'False','misleading'=>'Misleading','partially-true'=>'Partially True','verified'=>'Verified'] as $v => $l): ?>
        <option value="<?php echo $v; ?>" <?php selected($verdict, $v); ?>><?php echo $l; ?></option>
        <?php endforeach; ?>
    </select></p>
    <p><label><strong><?php _e('Claim', 'the-signal'); ?></strong></label><br>
    <textarea name="signal_fc_claim" style="width:100%;margin-top:4px;height:60px"><?php echo esc_textarea($claim); ?></textarea></p>
    <p><label><strong><?php _e('Source Note', 'the-signal'); ?></strong></label><br>
    <input type="text" name="signal_fc_source" value="<?php echo esc_attr($source); ?>" style="width:100%;margin-top:4px"></p>
    <?php
}

function signal_save_fact_check($post_id) {
    if (!isset($_POST['signal_fc_nonce_field']) || !wp_verify_nonce($_POST['signal_fc_nonce_field'], 'signal_fc_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    foreach (['signal_fc_verdict' => '_fc_verdict', 'signal_fc_claim' => '_fc_claim', 'signal_fc_source' => '_fc_source'] as $k => $meta) {
        if (isset($_POST[$k])) update_post_meta($post_id, $meta, sanitize_text_field($_POST[$k]));
    }
}
add_action('save_post', 'signal_save_fact_check');

/* ─────────────────────────────────────────
   HELPER: RENDER ARTICLE ROW
───────────────────────────────────────── */
function signal_article_row($post_id = null) {
    if ($post_id) $post = get_post($post_id);
    setup_postdata($post ?? $GLOBALS['post']);

    $cat       = signal_primary_cat($post_id);
    $cat_slug  = $cat ? $cat->slug : 'default';
    $cat_name  = $cat ? $cat->name : '';
    $chip_cls  = signal_get_cat_class($cat_slug);
    $thumb_cls = signal_get_thumb_class($cat_slug);
    $link      = get_permalink($post_id);
    $time      = signal_reading_time($post_id);
    $author    = get_the_author_meta('display_name', get_post_field('post_author', $post_id ?: get_the_ID()));
    $ago       = human_time_diff(get_post_time('U', false, $post_id), current_time('timestamp')) . ' ago';
    ?>
    <a class="article-row" href="<?php echo esc_url($link); ?>">
        <div>
            <div class="article-meta-row">
                <?php if ($cat_name): ?><span class="chip <?php echo esc_attr($chip_cls); ?>"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
                <span class="article-time"><?php echo esc_html($ago); ?></span>
            </div>
            <div class="article-title"><?php echo esc_html(get_the_title($post_id)); ?></div>
            <div class="article-excerpt"><?php echo signal_excerpt(22); ?></div>
            <div class="article-footer">
                <span>By <?php echo esc_html($author); ?></span>
                <span>·</span>
                <span><?php echo esc_html($time); ?></span>
            </div>
        </div>
        <div class="article-thumb">
            <?php if (has_post_thumbnail($post_id)): ?>
                <div class="thumb-inner" style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url($post_id, 'signal-thumb')); ?>')"></div>
            <?php else: ?>
                <div class="thumb-inner <?php echo esc_attr($thumb_cls); ?>"><div class="thumb-placeholder"></div></div>
            <?php endif; ?>
        </div>
    </a>
    <?php
    if ($post_id) wp_reset_postdata();
}

/* ─────────────────────────────────────────
   HELPER: NEWSLETTER WIDGET
───────────────────────────────────────── */
function signal_newsletter_widget() {
    $heading = get_theme_mod('signal_nl_heading', 'The Daily Signal');
    $desc    = get_theme_mod('signal_nl_desc', 'One briefing. Five stories. The world, in context — delivered by 7 AM.');
    ?>
    <div class="newsletter-widget">
        <h3><?php echo esc_html($heading); ?></h3>
        <p><?php echo esc_html($desc); ?></p>
        <form class="nl-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <?php wp_nonce_field('signal_newsletter', 'nl_nonce'); ?>
            <input type="hidden" name="action" value="signal_newsletter_subscribe">
            <input type="email" name="nl_email" placeholder="<?php esc_attr_e('your@email.com', 'the-signal'); ?>" required>
            <button type="submit"><?php _e('Subscribe Free →', 'the-signal'); ?></button>
        </form>
    </div>
    <?php
}

/* Newsletter handler */
add_action('admin_post_signal_newsletter_subscribe', 'signal_nl_subscribe');
add_action('admin_post_nopriv_signal_newsletter_subscribe', 'signal_nl_subscribe');
function signal_nl_subscribe() {
    check_admin_referer('signal_newsletter', 'nl_nonce');
    $email = sanitize_email($_POST['nl_email'] ?? '');
    if (is_email($email)) {
        $subscribers = get_option('signal_newsletter_subscribers', []);
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            update_option('signal_newsletter_subscribers', $subscribers);
        }
    }
    wp_redirect(add_query_arg('subscribed', '1', wp_get_referer()));
    exit;
}

/* ─────────────────────────────────────────
   ADMIN: NEWSLETTER SUBSCRIBERS PAGE
───────────────────────────────────────── */
function signal_admin_menu() {
    add_menu_page(__('The Signal', 'the-signal'), __('The Signal', 'the-signal'), 'manage_options', 'signal-settings', 'signal_settings_page', 'dashicons-megaphone', 4);
    add_submenu_page('signal-settings', __('Subscribers', 'the-signal'), __('Subscribers', 'the-signal'), 'manage_options', 'signal-subscribers', 'signal_subscribers_page');
    add_submenu_page('signal-settings', __('Ticker', 'the-signal'), __('Ticker Text', 'the-signal'), 'manage_options', 'signal-ticker', 'signal_ticker_page');
}
add_action('admin_menu', 'signal_admin_menu');

function signal_settings_page() { echo '<div class="wrap"><h1>' . __('The Signal — Settings', 'the-signal') . '</h1><p>' . __('Use the Customizer for theme options. Use sub-pages for subscribers and ticker.', 'the-signal') . '</p><a href="' . admin_url('customize.php') . '" class="button button-primary">' . __('Open Customizer', 'the-signal') . '</a></div>'; }

function signal_subscribers_page() {
    $subs = get_option('signal_newsletter_subscribers', []);
    echo '<div class="wrap"><h1>' . __('Newsletter Subscribers', 'the-signal') . '</h1><p>' . count($subs) . ' ' . __('subscribers', 'the-signal') . '</p>';
    if ($subs) {
        echo '<table class="widefat"><thead><tr><th>#</th><th>Email</th></tr></thead><tbody>';
        foreach ($subs as $i => $e) echo '<tr><td>' . ($i+1) . '</td><td>' . esc_html($e) . '</td></tr>';
        echo '</tbody></table>';
    }
    echo '</div>';
}

function signal_ticker_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('signal_save_ticker')) {
        update_option('signal_ticker_text', sanitize_textarea_field($_POST['ticker_text'] ?? ''));
        echo '<div class="notice notice-success"><p>' . __('Saved!', 'the-signal') . '</p></div>';
    }
    $val = get_option('signal_ticker_text', '');
    echo '<div class="wrap"><h1>' . __('Breaking Ticker', 'the-signal') . '</h1><form method="post">';
    wp_nonce_field('signal_save_ticker');
    echo '<p>' . __('Separate items with a pipe <code>|</code>. Leave blank to use latest post titles.', 'the-signal') . '</p>';
    echo '<textarea name="ticker_text" style="width:100%;height:120px">' . esc_textarea($val) . '</textarea><br><br>';
    echo '<input type="submit" value="' . __('Save', 'the-signal') . '" class="button button-primary"></form></div>';
}

/* ─────────────────────────────────────────
   CATEGORY COLORS (term meta)
───────────────────────────────────────── */
function signal_category_meta_fields($term) {
    $color = get_term_meta($term->term_id, 'signal_cat_color', true) ?: '#c8392b';
    ?>
    <tr class="form-field">
        <th scope="row"><label><?php _e('Category Accent Color', 'the-signal'); ?></label></th>
        <td><input type="color" name="signal_cat_color" value="<?php echo esc_attr($color); ?>"></td>
    </tr>
    <?php
}
add_action('category_edit_form_fields', 'signal_category_meta_fields');
add_action('edited_category', function($term_id) {
    if (isset($_POST['signal_cat_color'])) {
        update_term_meta($term_id, 'signal_cat_color', sanitize_hex_color($_POST['signal_cat_color']));
    }
});

/* ─────────────────────────────────────────
   DISABLE GUTENBERG FOR EASIER EDITING (optional — remove if you want blocks)
───────────────────────────────────────── */
// Uncomment to disable block editor:
// add_filter('use_block_editor_for_post', '__return_false');

/* ─────────────────────────────────────────
   SECURITY HARDENING
───────────────────────────────────────── */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
add_filter('the_generator', '__return_empty_string');
