<aside class="widget-area">

    <?php if (is_active_sidebar('sidebar-main')): ?>
        <?php dynamic_sidebar('sidebar-main'); ?>
    <?php else: ?>

    <!-- CONTINUE READING -->
    <?php
    $bookmarked = []; // In real usage, this comes from JS/localStorage — sidebar shows recent
    $recent = new WP_Query(['posts_per_page' => 2, 'post_status' => 'publish', 'orderby' => 'rand']);
    if ($recent->have_posts()):
    ?>
    <div class="widget-section">
        <div class="widget-title"><?php _e('Continue Reading', 'the-signal'); ?></div>
        <?php while ($recent->have_posts()): $recent->the_post(); ?>
        <a href="<?php the_permalink(); ?>">
            <div class="continue-reading-card">
                <div class="cr-title"><?php the_title(); ?></div>
                <div class="pb-wrap"><div class="pb" style="width:<?php echo rand(10,80); ?>%"></div></div>
                <div class="pb-label"><?php echo esc_html(signal_reading_time()); ?></div>
            </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <!-- TRENDING -->
    <?php
    $trending = new WP_Query(['posts_per_page' => 5, 'post_status' => 'publish', 'meta_key' => 'post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC']);
    if (!$trending->have_posts()) $trending = new WP_Query(['posts_per_page' => 5, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'DESC']);
    if ($trending->have_posts()):
    ?>
    <div class="widget-section">
        <div class="widget-title"><?php _e('Trending Now', 'the-signal'); ?></div>
        <?php $i = 1; while ($trending->have_posts()): $trending->the_post();
            $cat = signal_primary_cat();
            $chip = $cat ? signal_get_cat_class($cat->slug) : 'chip-default';
        ?>
        <a href="<?php the_permalink(); ?>" style="display:block">
            <div class="trending-item">
                <div class="trending-num">0<?php echo $i; ?></div>
                <div>
                    <div class="trending-title"><?php the_title(); ?></div>
                    <?php if ($cat): ?><div style="margin-top:4px"><span class="chip <?php echo esc_attr($chip); ?>"><?php echo esc_html($cat->name); ?></span></div><?php endif; ?>
                </div>
            </div>
        </a>
        <?php $i++; endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <!-- NEWSLETTER -->
    <div class="widget-section">
        <?php signal_newsletter_widget(); ?>
    </div>

    <!-- TOPICS -->
    <div class="widget-section">
        <div class="widget-title"><?php _e('Follow Topics', 'the-signal'); ?></div>
        <div class="topics-cloud">
            <?php
            $all_tags = get_tags(['number' => 12, 'orderby' => 'count', 'order' => 'DESC']);
            if ($all_tags):
                foreach ($all_tags as $tag): ?>
                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                    <div class="topic-pill" data-topic="<?php echo esc_attr($tag->name); ?>"><?php echo esc_html($tag->name); ?></div>
                </a>
                <?php endforeach;
            else:
                $default_topics = ['India', 'AI Policy', 'NATO', 'Cricket', 'BRICS', 'OpenAI', 'Gaza', 'SpaceX', 'Semiconductors', 'Football', 'Media', 'Arctic'];
                foreach ($default_topics as $t): ?>
                <div class="topic-pill" data-topic="<?php echo esc_attr($t); ?>"><?php echo esc_html($t); ?></div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>

    <!-- READING STREAK -->
    <div class="widget-section">
        <div class="widget-title"><?php _e('Your Reading Streak', 'the-signal'); ?></div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:18px">
            <div class="streak-calendar" id="streakCal"></div>
            <div class="streak-message" id="streakMsg">🔥 <?php _e('Keep reading daily!', 'the-signal'); ?></div>
        </div>
    </div>

    <?php endif; /* end sidebar-main check */ ?>

</aside>
