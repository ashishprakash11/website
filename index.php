<?php get_header(); ?>

<div class="container">
<div class="main-grid">
<main class="content-area" role="main">

<?php
/* ── HERO SECTION ── */
$hero_query = new WP_Query(['posts_per_page' => 4, 'post_status' => 'publish', 'meta_key' => '_is_featured', 'meta_value' => '1']);
if (!$hero_query->have_posts()) {
    $hero_query = new WP_Query(['posts_per_page' => 4, 'post_status' => 'publish']);
}

if ($hero_query->have_posts()):
    $hero_query->the_post();
    $hero_cat   = signal_primary_cat();
    $hero_slug  = $hero_cat ? $hero_cat->slug : '';
    $hero_name  = $hero_cat ? $hero_cat->name : '';
    $hero_chip  = signal_get_cat_class($hero_slug);
    $hero_hcls  = 'cat-' . $hero_slug;
    $has_img    = has_post_thumbnail();
    $hero_posts = [];
    while ($hero_query->have_posts()) { $hero_query->the_post(); $hero_posts[] = get_the_ID(); }
    wp_reset_postdata();
    $hero_query->rewind_posts();
    $hero_query->the_post(); // back to first
?>
<div class="hero-grid reveal">
    <!-- MAIN HERO -->
    <a href="<?php the_permalink(); ?>" class="hero-main">
        <?php if ($has_img): ?>
        <div class="hero-main-bg" style="background-image:url('<?php the_post_thumbnail_url('signal-hero'); ?>')"></div>
        <?php else: ?>
        <div class="hero-main-bg no-img"></div>
        <?php endif; ?>
        <div style="position:absolute;inset:0;overflow:hidden;opacity:.08;z-index:0">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="hg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,.5)" stroke-width=".5"/></pattern></defs><rect width="100%" height="100%" fill="url(#hg)"/></svg>
        </div>
        <div class="hero-main-content">
            <div class="hero-cat-label"><?php echo $hero_name ? esc_html($hero_name) : __('Cover Story', 'the-signal'); ?></div>
            <h1><?php the_title(); ?></h1>
            <div class="hero-excerpt"><?php echo signal_excerpt(30); ?></div>
            <div class="hero-meta">
                <span class="hero-author"><?php _e('By', 'the-signal'); ?> <?php the_author(); ?></span>
                <span class="read-time"><?php echo esc_html(signal_reading_time()); ?></span>
            </div>
        </div>
    </a>

    <!-- HERO STACK (next 3 posts) -->
    <div class="hero-stack">
    <?php
    foreach (array_slice($hero_posts, 0, 3) as $pid):
        $sc   = signal_primary_cat($pid);
        $sslug = $sc ? $sc->slug : '';
        $sname = $sc ? $sc->name : '';
        $schip = signal_get_cat_class($sslug);
        $shcls = 'cat-' . $sslug;
    ?>
    <a class="hero-card <?php echo esc_attr($shcls); ?>" href="<?php echo esc_url(get_permalink($pid)); ?>">
        <span class="chip <?php echo esc_attr($schip); ?>"><?php echo esc_html($sname); ?></span>
        <h3><?php echo esc_html(get_the_title($pid)); ?></h3>
        <p><?php echo wp_trim_words(get_the_excerpt($pid), 15); ?></p>
        <div class="article-footer" style="margin-top:8px"><span><?php echo esc_html(signal_reading_time($pid)); ?></span></div>
    </a>
    <?php endforeach; ?>
    </div>
</div>
<?php wp_reset_postdata(); endif; ?>

<?php
/* ── CATEGORY SECTIONS ── */
$sections = [
    'fact-check'  => ['title' => 'Fact Check',       'class' => 's-fact', 'count' => 4, 'fact_check' => true],
    'geopolitics' => ['title' => 'Geopolitics',       'class' => 's-geo',  'count' => 3],
    'sports'      => ['title' => 'Sports',            'class' => 's-sport','count' => 2],
    'tech'        => ['title' => 'Tech &amp; Science','class' => 's-tech', 'count' => 2],
    'media'       => ['title' => 'Media &amp; Culture','class' => 's-media','count' => 2],
];

foreach ($sections as $slug => $cfg):
    $cat = get_category_by_slug($slug);
    if (!$cat) continue;

    $q = new WP_Query([
        'posts_per_page' => $cfg['count'],
        'cat'            => $cat->term_id,
        'post_status'    => 'publish',
    ]);
    if (!$q->have_posts()) continue;
?>

<div class="reveal">
    <div class="section-header">
        <div class="section-title <?php echo esc_attr($cfg['class']); ?>"><?php echo $cfg['title']; ?></div>
        <a class="see-all" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php _e('See all →', 'the-signal'); ?></a>
    </div>

    <?php if (!empty($cfg['fact_check'])): ?>
    <!-- FACT CHECK STRIPS -->
    <?php while ($q->have_posts()): $q->the_post();
        $verdict   = get_post_meta(get_the_ID(), '_fc_verdict', true);
        $claim     = get_post_meta(get_the_ID(), '_fc_claim', true);
        $fc_source = get_post_meta(get_the_ID(), '_fc_source', true);
        $v_class   = 'v-' . ($verdict ?: 'false');
        $v_labels  = ['false' => 'False', 'misleading' => 'Misleading', 'partially-true' => 'Partially True', 'verified' => 'Verified'];
        $v_label   = $v_labels[$verdict] ?? 'False';
    ?>
    <a href="<?php the_permalink(); ?>" class="factcheck-strip">
        <div class="verdict <?php echo esc_attr($v_class); ?>">
            <span class="verdict-dot" style="background:currentColor"></span>
            <?php echo esc_html($v_label); ?>
        </div>
        <div class="fc-claim"><?php echo $claim ? esc_html('"' . $claim . '"') : esc_html(get_the_title()); ?></div>
        <?php if ($fc_source): ?><div class="fc-source"><?php echo esc_html($fc_source); ?></div><?php endif; ?>
    </a>
    <?php endwhile; wp_reset_postdata(); ?>

    <?php else: ?>
    <!-- ARTICLE ROWS -->
    <div class="article-list">
    <?php while ($q->have_posts()): $q->the_post(); signal_article_row(); endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>
</div>

<div class="divider" style="height:1px;background:var(--border);margin:36px 0"></div>

<?php endforeach; ?>

<?php if (have_posts()): ?>
<!-- PAGINATION -->
<div class="pagination">
    <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '←', 'next_text' => '→']); ?>
</div>
<?php endif; ?>

</main>

<?php get_sidebar(); ?>

</div><!-- /main-grid -->
</div><!-- /container -->

<?php get_footer(); ?>
