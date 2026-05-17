<?php get_header();

$cat      = get_queried_object();
$cat_slug = $cat ? $cat->slug : '';
$cat_name = $cat ? $cat->name : '';
$cat_desc = $cat ? $cat->description : '';
$sec_cls  = signal_get_section_class($cat_slug);
$cat_color_map = [
    'geopolitics' => '#3ab0c8', 'fact-check' => '#e87070',
    'sports' => '#e8a832', 'media' => '#c47de8', 'tech' => '#2dc483',
];
$cat_color = get_term_meta($cat->term_id, 'signal_cat_color', true)
          ?: ($cat_color_map[$cat_slug] ?? '#c8392b');
?>

<style>
.cat-hero::before { background: radial-gradient(ellipse at 20% 50%, <?php echo esc_attr($cat_color); ?>22, transparent 60%) !important; }
.cat-eyebrow { color: <?php echo esc_attr($cat_color); ?> !important; }
.cat-eyebrow::before { background: <?php echo esc_attr($cat_color); ?> !important; }
.sort-opt.active { border-color: <?php echo esc_attr($cat_color); ?> !important; color: <?php echo esc_attr($cat_color); ?> !important; }
</style>

<!-- CATEGORY HERO -->
<div class="cat-hero">
    <div class="cat-hero-inner">
        <div class="cat-eyebrow"><?php echo esc_html($cat_name); ?></div>
        <h1><?php echo esc_html($cat_name); ?></h1>
        <?php if ($cat_desc): ?><p class="cat-desc"><?php echo esc_html($cat_desc); ?></p><?php endif; ?>
        <div class="cat-stats">
            <div class="cat-stat"><strong><?php echo $cat->count; ?></strong><?php _e('Articles', 'the-signal'); ?></div>
        </div>
    </div>
</div>

<?php signal_breadcrumbs(); ?>

<div class="container">
<div class="main-grid">
<main class="content-area" role="main">

    <!-- FEATURED (sticky or latest) -->
    <?php
    $featured = new WP_Query([
        'posts_per_page' => 1,
        'cat'            => $cat->term_id,
        'meta_key'       => '_is_featured',
        'meta_value'     => '1',
        'post_status'    => 'publish',
    ]);
    if (!$featured->have_posts()) {
        $featured = new WP_Query(['posts_per_page' => 1, 'cat' => $cat->term_id, 'post_status' => 'publish']);
    }
    if ($featured->have_posts()): $featured->the_post();
        $fslug = $cat_slug; $fchip = signal_get_cat_class($fslug);
    ?>
    <a href="<?php the_permalink(); ?>">
    <div class="reveal" style="border:1px solid var(--border);border-radius:6px;overflow:hidden;margin-bottom:32px;cursor:pointer;transition:border-color var(--T)">
        <div style="height:260px;position:relative;overflow:hidden">
            <?php if (has_post_thumbnail()): ?>
            <div style="position:absolute;inset:0;background-size:cover;background-position:center;background-image:url('<?php the_post_thumbnail_url('signal-hero'); ?>');transition:transform .5s ease"></div>
            <?php else: ?>
            <div style="position:absolute;inset:0;<?php echo esc_attr(signal_get_thumb_class($fslug) === 'thumb-bg-geo' ? 'background:linear-gradient(135deg,#1a2535,#0e1e2e)' : 'background:var(--surface2)'); ?>"></div>
            <?php endif; ?>
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7),transparent 60%)"></div>
            <div style="position:absolute;bottom:16px;left:16px;font-family:'IBM Plex Mono',monospace;font-size:.6rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;background:<?php echo esc_attr($cat_color); ?>;color:#fff;padding:3px 10px;border-radius:3px;z-index:2"><?php echo esc_html($cat_name); ?></div>
        </div>
        <div style="padding:24px">
            <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--text);line-height:1.25;margin-bottom:10px"><?php the_title(); ?></div>
            <div style="font-size:.92rem;color:var(--muted);line-height:1.6;margin-bottom:14px"><?php echo signal_excerpt(28); ?></div>
            <div style="display:flex;align-items:center;gap:16px;font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--muted)">
                <span>By <?php the_author(); ?></span><span>·</span><span><?php echo esc_html(signal_reading_time()); ?></span>
            </div>
        </div>
    </div>
    </a>
    <?php wp_reset_postdata(); endif; ?>

    <!-- SORT BAR -->
    <div class="cat-sort-bar">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--muted)"><?php _e('Sort by:', 'the-signal'); ?></span>
        <div class="sort-opts">
            <div class="sort-opt active"><?php _e('Latest', 'the-signal'); ?></div>
            <div class="sort-opt"><?php _e('Most Read', 'the-signal'); ?></div>
            <div class="sort-opt"><?php _e("Editors' Pick", 'the-signal'); ?></div>
        </div>
    </div>

    <!-- ARTICLE LIST -->
    <div class="article-list reveal">
    <?php if (have_posts()): while (have_posts()): the_post(); signal_article_row(); endwhile; endif; ?>
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '←', 'next_text' => '→']); ?>
    </div>

</main>

<?php get_sidebar(); ?>

</div><!-- /main-grid -->
</div><!-- /container -->

<?php get_footer(); ?>
