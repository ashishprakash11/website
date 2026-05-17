<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="dark">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- READING PROGRESS BAR (single posts only) -->
<?php if (is_single()): ?>
<div class="rpb" id="readingProgressBar"></div>
<?php endif; ?>

<!-- BREAKING TICKER -->
<div class="signal-ticker">
    <div class="ticker-label">⚡ <?php _e('Live', 'the-signal'); ?></div>
    <div class="ticker-inner">
        <div class="ticker-track">
            <?php foreach (signal_ticker_items() as $item): ?>
                <span><?php echo esc_html($item); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- SITE HEADER -->
<header class="site-header">
    <div class="header-inner">

        <!-- LOGO -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                THE <span><?php bloginfo('name'); ?></span>
            <?php endif; ?>
        </a>

        <!-- CONTROLS -->
        <div class="header-controls">
            <div class="streak-pill" id="streakPill">🔥 0 day streak</div>
            <a href="<?php echo esc_url(home_url('/?s=')); ?>" class="icon-btn" title="<?php esc_attr_e('Search', 'the-signal'); ?>">🔍</a>
            <button class="icon-btn" id="themeToggleBtn" onclick="signalToggleTheme()" title="<?php esc_attr_e('Toggle theme', 'the-signal'); ?>">☾</button>
            <button class="icon-btn hamburger" onclick="signalOpenMenu()" title="<?php esc_attr_e('Menu', 'the-signal'); ?>">☰</button>
            <div class="hdr-meta" style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;color:var(--muted);letter-spacing:.08em;text-align:right;line-height:1.5;display:none;" id="hdrMeta">
                <div id="liveDate"><?php echo esc_html(date_i18n('D, d M Y')); ?></div>
                <div style="color:var(--accent)"><?php echo esc_html(get_theme_mod('signal_tagline', 'Truth · Context · Clarity')); ?></div>
            </div>
        </div>

    </div>

    <!-- PRIMARY NAV -->
    <nav class="main-nav" id="mainNav" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'the-signal'); ?>">

        <a class="nav-item<?php echo is_front_page() ? ' current-menu-item' : ''; ?>" href="<?php echo esc_url(home_url('/')); ?>">
            <span class="nav-dot" style="background:#e87070"></span>
            <?php _e('All', 'the-signal'); ?>
        </a>

        <?php
        $nav_cats = [
            'fact-check'  => ['label' => 'Fact Check',  'color' => '#e87070'],
            'geopolitics' => ['label' => 'Geopolitics', 'color' => '#3ab0c8'],
            'sports'      => ['label' => 'Sports',      'color' => '#e8a832'],
            'media'       => ['label' => 'Media',        'color' => '#c47de8'],
            'tech'        => ['label' => 'Tech',         'color' => '#2dc483'],
        ];
        foreach ($nav_cats as $slug => $info):
            $cat = get_category_by_slug($slug);
            if (!$cat) continue;
            $active = (is_category($slug)) ? ' current-cat' : '';
        ?>
        <a class="nav-item<?php echo $active; ?>" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
            <span class="nav-dot" style="background:<?php echo esc_attr($info['color']); ?>"></span>
            <?php echo esc_html($info['label']); ?>
        </a>
        <?php endforeach; ?>

        <!-- Custom nav items from Appearance > Menus -->
        <?php
        wp_nav_menu([
            'theme_location'  => 'primary',
            'container'       => false,
            'items_wrap'      => '%3$s',
            'fallback_cb'     => false,
            'walker'          => new Signal_Nav_Walker(),
        ]);
        ?>
    </nav>
</header>

<!-- MOBILE MENU -->
<div class="mob-menu" id="mobileMenu" role="dialog" aria-label="<?php esc_attr_e('Mobile Menu', 'the-signal'); ?>">
    <button class="mob-close" onclick="signalCloseMenu()">✕</button>
    <a href="<?php echo esc_url(home_url('/')); ?>"><span class="nav-dot" style="background:#e87070;width:10px;height:10px;display:inline-block;border-radius:50%;"></span> <?php _e('All Stories', 'the-signal'); ?></a>
    <?php foreach ($nav_cats as $slug => $info):
        $cat = get_category_by_slug($slug);
        if (!$cat) continue; ?>
    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><span class="nav-dot" style="background:<?php echo esc_attr($info['color']); ?>;width:10px;height:10px;display:inline-block;border-radius:50%;"></span> <?php echo esc_html($info['label']); ?></a>
    <?php endforeach; ?>
    <a href="<?php echo esc_url(home_url('/?s=')); ?>">🔍 <?php _e('Search', 'the-signal'); ?></a>
    <?php
    $about_page = get_page_by_path('about');
    if ($about_page): ?>
    <a href="<?php echo esc_url(get_permalink($about_page->ID)); ?>"><?php _e('About The Signal', 'the-signal'); ?></a>
    <?php endif; ?>
</div>

<?php

/* ── SIMPLE NAV WALKER ── */
if (!class_exists('Signal_Nav_Walker')):
class Signal_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $active = in_array('current-menu-item', $item->classes) ? ' current-menu-item' : '';
        $output .= '<a class="nav-item' . $active . '" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
}
endif;
