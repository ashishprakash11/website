<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">

            <!-- BRAND COL -->
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">THE <span><?php bloginfo('name'); ?></span></a>
                <p><?php echo esc_html(get_bloginfo('description') ?: 'Independent journalism built on truth, context, and clarity.'); ?></p>
                <div class="footer-social">
                    <a href="#" title="X / Twitter">𝕏</a>
                    <a href="#" title="Instagram">📸</a>
                    <a href="#" title="WhatsApp">💬</a>
                    <a href="<?php echo esc_url(get_feed_link()); ?>" title="RSS">📡</a>
                </div>
            </div>

            <!-- SECTIONS COL -->
            <div class="footer-col">
                <h4><?php _e('Sections', 'the-signal'); ?></h4>
                <ul>
                    <?php
                    $footer_cats = ['fact-check' => 'Fact Check', 'geopolitics' => 'Geopolitics', 'sports' => 'Sports', 'media' => 'Media', 'tech' => 'Tech'];
                    foreach ($footer_cats as $slug => $label):
                        $cat = get_category_by_slug($slug);
                        if ($cat): ?>
                        <li><a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($label); ?></a></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>

            <!-- COMPANY COL -->
            <div class="footer-col">
                <h4><?php _e('Company', 'the-signal'); ?></h4>
                <ul>
                    <?php
                    $company_pages = ['about' => 'About', 'careers' => 'Careers', 'advertise' => 'Advertise'];
                    foreach ($company_pages as $slug => $label):
                        $pg = get_page_by_path($slug);
                        if ($pg): ?>
                        <li><a href="<?php echo esc_url(get_permalink($pg->ID)); ?>"><?php echo esc_html($label); ?></a></li>
                    <?php else: ?>
                        <li><a href="#"><?php echo esc_html($label); ?></a></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>

            <!-- STANDARDS COL -->
            <div class="footer-col">
                <h4><?php _e('Standards', 'the-signal'); ?></h4>
                <ul>
                    <?php
                    $std_pages = ['fact-check-policy' => 'Fact Check Policy', 'editorial-standards' => 'Editorial Standards', 'corrections' => 'Corrections', 'contact' => 'Contact'];
                    foreach ($std_pages as $slug => $label):
                        $pg = get_page_by_path($slug);
                        if ($pg): ?>
                        <li><a href="<?php echo esc_url(get_permalink($pg->ID)); ?>"><?php echo esc_html($label); ?></a></li>
                    <?php else: ?>
                        <li><a href="#"><?php echo esc_html($label); ?></a></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="footer-copy">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?> · <?php _e('Independent journalism', 'the-signal'); ?></div>
            <div class="footer-legal">
                <a href="#"><?php _e('Privacy Policy', 'the-signal'); ?></a>
                <a href="#"><?php _e('Terms of Use', 'the-signal'); ?></a>
                <a href="#"><?php _e('Cookie Settings', 'the-signal'); ?></a>
            </div>
        </div>

        <?php if (is_active_sidebar('sidebar-footer')): ?>
        <div class="footer-widgets">
            <?php dynamic_sidebar('sidebar-footer'); ?>
        </div>
        <?php endif; ?>

    </div>
</footer>

<!-- BACK TO TOP -->
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e('Back to top', 'the-signal'); ?>">↑</button>

<?php wp_footer(); ?>
</body>
</html>
