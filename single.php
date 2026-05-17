<?php get_header(); ?>

<?php while (have_posts()): the_post();
    $cat      = signal_primary_cat();
    $cat_slug = $cat ? $cat->slug : '';
    $cat_name = $cat ? $cat->name : '';
    $chip_cls = signal_get_cat_class($cat_slug);
    $author   = get_the_author();
    $avatar   = get_avatar_url(get_the_author_meta('email'), ['size' => 144]);
    $author_bio = get_the_author_meta('description');
    $read_time  = signal_reading_time();
    $post_id    = get_the_ID();
    $verdict    = get_post_meta($post_id, '_fc_verdict', true);
    $fc_claim   = get_post_meta($post_id, '_fc_claim', true);
?>

<?php signal_breadcrumbs(); ?>

<!-- ARTICLE HEADER -->
<div class="article-header reveal">
    <div class="art-cat" style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
        <?php if ($cat_name): ?>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
            <span class="chip <?php echo esc_attr($chip_cls); ?>"><?php echo esc_html($cat_name); ?></span>
        </a>
        <?php endif; ?>
        <?php if ($verdict): ?>
        <span class="chip chip-fact"><?php echo esc_html(ucfirst($verdict)); ?></span>
        <?php endif; ?>
        <span style="font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--accent)"><?php echo esc_html($read_time); ?></span>
    </div>

    <h1><?php the_title(); ?></h1>

    <?php if (has_excerpt()): ?>
    <div class="article-deck"><?php the_excerpt(); ?></div>
    <?php endif; ?>

    <!-- BYLINE -->
    <div class="article-byline">
        <div class="author-avatar">
            <?php if ($avatar): ?>
                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>">
            <?php else: ?>
                <?php echo esc_html(substr($author, 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div>
            <div class="byline-name"><?php echo esc_html($author); ?></div>
            <div class="byline-meta">
                <?php echo esc_html(get_the_date()); ?>
                <?php if (get_the_modified_date() !== get_the_date()): ?>
                 · <?php _e('Updated', 'the-signal'); ?> <?php echo esc_html(human_time_diff(get_the_modified_time('U'), current_time('timestamp')) . ' ago'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="share-strip">
            <button class="share-btn" onclick="signalShareX()" title="Share on X">𝕏</button>
            <button class="share-btn" onclick="signalShareWA()" title="WhatsApp">💬</button>
            <button class="share-btn" onclick="signalCopyLink(this)" title="Copy link">🔗</button>
            <button class="bookmark-btn" id="bookmarkBtn" data-bookmark="<?php echo $post_id; ?>"
                onclick="signalToggleBookmark(this, '<?php echo $post_id; ?>')">🔖 <?php _e('Save', 'the-signal'); ?></button>
        </div>
    </div>
</div>

<!-- ARTICLE LAYOUT: body + TOC sidebar -->
<div class="article-layout">

    <!-- BODY -->
    <div class="article-body reveal" id="articleBody">

        <!-- HERO IMAGE -->
        <?php if (has_post_thumbnail()): ?>
        <div class="hero-feat-img" style="background-image:url('<?php the_post_thumbnail_url('signal-hero'); ?>')"></div>
        <div class="img-caption"><?php echo esc_html(get_post(get_post_thumbnail_id())->post_excerpt ?: get_the_title() . ' — ' . get_bloginfo('name')); ?></div>
        <?php else: ?>
        <div class="hero-feat-img no-img"></div>
        <div class="img-caption"><?php echo esc_html(get_bloginfo('name')); ?></div>
        <?php endif; ?>

        <!-- FACT CHECK VERDICT BOX -->
        <?php if ($fc_claim && $verdict):
            $v_labels = ['false' => 'False', 'misleading' => 'Misleading', 'partially-true' => 'Partially True', 'verified' => 'Verified'];
            $v_label = $v_labels[$verdict] ?? ucfirst($verdict);
            $v_class = 'v-' . $verdict;
        ?>
        <div style="background:var(--surface);border:1px solid var(--border);border-left:3px solid currentColor;border-radius:0 6px 6px 0;padding:20px 24px;margin-bottom:2em">
            <div class="verdict <?php echo esc_attr($v_class); ?>" style="font-size:.72rem;margin-bottom:8px">
                <span class="verdict-dot" style="background:currentColor"></span>
                <?php _e('Verdict:', 'the-signal'); ?> <?php echo esc_html($v_label); ?>
            </div>
            <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--white)">"<?php echo esc_html($fc_claim); ?>"</div>
        </div>
        <?php endif; ?>

        <!-- POST CONTENT -->
        <div class="entry-content">
            <?php the_content(); ?>
        </div>

        <!-- TAGS -->
        <?php $tags = get_the_tags(); if ($tags): ?>
        <div class="article-tags-footer">
            <span style="font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--muted);align-self:center"><?php _e('Tagged:', 'the-signal'); ?></span>
            <?php foreach ($tags as $tag): ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                <div class="tag-pill"><?php echo esc_html($tag->name); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

    <!-- TOC SIDEBAR -->
    <aside class="article-aside">
        <div class="toc-box">
            <div class="toc-box-title"><?php _e('In This Article', 'the-signal'); ?></div>
            <?php
            // Auto-generate TOC from h2 headings in content
            $content = get_the_content();
            preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches);
            if (!empty($matches[1])):
                foreach ($matches[1] as $heading):
                    $clean = strip_tags($heading);
                    $anchor = sanitize_title($clean);
            ?>
            <a class="toc-link" href="#<?php echo esc_attr($anchor); ?>"><?php echo esc_html($clean); ?></a>
            <?php endforeach; endif; ?>
        </div>

        <div class="reading-progress-widget">
            <div style="font-weight:600;color:var(--text);margin-bottom:4px">📖 <?php _e('Reading Progress', 'the-signal'); ?></div>
            <div class="rpw-bar-wrap"><div class="rpw-bar" id="rpwBar"></div></div>
            <div id="rpwPct"><?php _e('0% complete', 'the-signal'); ?></div>
        </div>

        <br>
        <?php signal_newsletter_widget(); ?>
    </aside>

</div>

<!-- AUTHOR CARD -->
<?php if ($author_bio): ?>
<div class="author-card reveal">
    <div class="author-card-inner">
        <div class="ac-avatar">
            <?php if ($avatar): ?>
                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>">
            <?php else: ?>
                <?php echo esc_html(substr($author, 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div>
            <div class="ac-name"><?php echo esc_html($author); ?></div>
            <div class="ac-role"><?php echo esc_html(get_the_author_meta('user_title') ?: __('Contributor', 'the-signal')); ?></div>
            <div class="ac-bio"><?php echo esc_html($author_bio); ?></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- RELATED POSTS -->
<?php
$related = new WP_Query([
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'post__not_in'        => [$post_id],
    'category__in'        => $cat ? [$cat->term_id] : [],
    'ignore_sticky_posts' => 1,
]);
if ($related->have_posts()):
?>
<div class="related-section reveal">
    <div class="section-header" style="padding:0 0 16px;margin:0 0 24px;border-bottom:1px solid var(--border)">
        <div class="section-title <?php echo signal_get_section_class($cat_slug); ?>"><?php _e('More To Read', 'the-signal'); ?></div>
    </div>
    <div class="related-grid">
    <?php while ($related->have_posts()): $related->the_post();
        $rc   = signal_primary_cat();
        $rslug = $rc ? $rc->slug : '';
        $rchip = signal_get_cat_class($rslug);
    ?>
    <a href="<?php the_permalink(); ?>">
        <div class="related-card">
            <div class="related-card-img <?php echo esc_attr(signal_get_thumb_class($rslug)); ?>"
                 <?php if (has_post_thumbnail()): ?>
                 style="background-image:url('<?php the_post_thumbnail_url('signal-related'); ?>')"
                 <?php endif; ?>>
                 <div style="width:100%;height:100%;background-image:repeating-linear-gradient(45deg,transparent,transparent 8px,rgba(255,255,255,.03) 8px,rgba(255,255,255,.03) 9px)"></div>
            </div>
            <div class="related-card-body">
                <?php if ($rc): ?><span class="chip <?php echo esc_attr($rchip); ?>" style="display:inline-block;margin-bottom:8px"><?php echo esc_html($rc->name); ?></span><?php endif; ?>
                <div class="related-card-title"><?php the_title(); ?></div>
                <div class="related-card-foot"><?php the_author(); ?> · <?php echo esc_html(signal_reading_time()); ?></div>
            </div>
        </div>
    </a>
    <?php endwhile; wp_reset_postdata(); ?>
    </div>
</div>
<?php endif; ?>

<!-- COMMENTS -->
<div class="comments-wrap reveal">
    <?php comments_template('/comments.php'); ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
