<?php
/*
 * Template Name: About Page
 * Description: Full About page with mission, values, team, fact-check policy, and contact form.
 */
get_header();
?>

<div class="container">
<div class="page-hero">
    <h1><?php _e('About The Signal', 'the-signal'); ?></h1>
    <p><?php echo esc_html(get_theme_mod('signal_about_mission', 'We are an independent newsroom committed to original reporting, rigorous fact-checking, and journalism that gives people the context they need to understand the world.')); ?></p>
</div>
</div>

<div class="page-body">

    <?php while (have_posts()): the_post(); ?>
    <?php if (get_the_content()): the_content(); else: ?>

    <h2 id="mission"><?php _e('Our Mission', 'the-signal'); ?></h2>
    <p><?php _e('The Signal was founded on a simple conviction: the information crisis is not a crisis of quantity. There is more news, more content, and more data available than at any point in human history. The crisis is one of context — of signal vs. noise.', 'the-signal'); ?></p>
    <p><?php _e('We exist to provide the context that turns information into understanding. Every story we publish is designed to answer not just what happened, but why it matters and what comes next.', 'the-signal'); ?></p>

    <div class="values-grid">
        <div class="value-card">
            <h3>🔍 <?php _e('Verification First', 'the-signal'); ?></h3>
            <p><?php _e('We publish nothing we cannot verify. When sources conflict, we say so. When evidence is incomplete, we say that too.', 'the-signal'); ?></p>
        </div>
        <div class="value-card">
            <h3>🌐 <?php _e('Global Perspective', 'the-signal'); ?></h3>
            <p><?php _e("We cover the world from correspondents embedded in it — not from a single city's assumptions about what matters.", 'the-signal'); ?></p>
        </div>
        <div class="value-card">
            <h3>📊 <?php _e('Data-Driven', 'the-signal'); ?></h3>
            <p><?php _e('Our coverage is anchored in evidence. We show our work, link our sources, and update our stories when we learn new information.', 'the-signal'); ?></p>
        </div>
        <div class="value-card">
            <h3>🔒 <?php _e('Editorial Independence', 'the-signal'); ?></h3>
            <p><?php _e('We accept no advertiser influence over our coverage. Our revenue model is built on reader trust, not clicks.', 'the-signal'); ?></p>
        </div>
    </div>

    <h2 id="factcheck"><?php _e('Fact Check Policy', 'the-signal'); ?></h2>
    <p><?php _e('The Signal fact-checks claims circulating in public discourse — from politicians, institutions, media outlets, and viral social media posts. Our verdicts follow a consistent, transparent methodology.', 'the-signal'); ?></p>

    <div class="verdict-explainer">
        <div class="ve-card"><div class="ve-dot" style="background:#e84040"></div><div><div class="ve-label" style="color:#e84040"><?php _e('False', 'the-signal'); ?></div><div class="ve-desc"><?php _e('The claim is factually incorrect based on available evidence.', 'the-signal'); ?></div></div></div>
        <div class="ve-card"><div class="ve-dot" style="background:#e8a832"></div><div><div class="ve-label" style="color:#e8a832"><?php _e('Misleading', 'the-signal'); ?></div><div class="ve-desc"><?php _e('Technically accurate but missing context that changes the meaning.', 'the-signal'); ?></div></div></div>
        <div class="ve-card"><div class="ve-dot" style="background:#3ab0c8"></div><div><div class="ve-label" style="color:#3ab0c8"><?php _e('Partially True', 'the-signal'); ?></div><div class="ve-desc"><?php _e('Some elements are accurate; others are incomplete or incorrect.', 'the-signal'); ?></div></div></div>
        <div class="ve-card"><div class="ve-dot" style="background:#2dc483"></div><div><div class="ve-label" style="color:#2dc483"><?php _e('Verified', 'the-signal'); ?></div><div class="ve-desc"><?php _e('The claim is accurate and supported by multiple independent sources.', 'the-signal'); ?></div></div></div>
    </div>

    <h2 id="editorial"><?php _e('Editorial Standards', 'the-signal'); ?></h2>
    <p><?php _e('All Signal journalism follows these core principles:', 'the-signal'); ?></p>
    <ul>
        <li><?php _e('Every factual claim must be supported by at least two independent sources unless a single authoritative source is cited by name.', 'the-signal'); ?></li>
        <li><?php _e('Anonymous sources are used sparingly and only when the information is of genuine public interest and the source faces verifiable risk.', 'the-signal'); ?></li>
        <li><?php _e('We correct errors promptly and transparently. Corrections appear at the top of the relevant article.', 'the-signal'); ?></li>
        <li><?php _e('Reporters and editors disclose conflicts of interest. Reporters do not cover subjects in which they have a financial stake.', 'the-signal'); ?></li>
    </ul>

    <h2 id="corrections"><?php _e('Corrections Policy', 'the-signal'); ?></h2>
    <p><?php _e('We correct factual errors as quickly as possible. Minor corrections (typos, spelling) are made silently. Substantive corrections are noted at the top of the article with a description of what was changed and when.', 'the-signal'); ?></p>
    <p><?php _e('To report an error, use the contact form below and select "Correction Request."', 'the-signal'); ?></p>

    <h2 id="contact"><?php _e('Contact', 'the-signal'); ?></h2>
    <div class="contact-form">
        <?php if (function_exists('wpcf7_enqueue_scripts')): ?>
            <?php echo do_shortcode('[contact-form-7 id="contact" title="Contact Form"]'); ?>
        <?php else: ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('signal_contact', 'contact_nonce'); ?>
            <input type="hidden" name="action" value="signal_contact_submit">
            <label><?php _e('Name', 'the-signal'); ?></label>
            <input type="text" name="contact_name" required placeholder="<?php esc_attr_e('Your full name', 'the-signal'); ?>">
            <label><?php _e('Email', 'the-signal'); ?></label>
            <input type="email" name="contact_email" required placeholder="<?php esc_attr_e('your@email.com', 'the-signal'); ?>">
            <label><?php _e('Subject', 'the-signal'); ?></label>
            <select name="contact_subject">
                <option><?php _e('General Enquiry', 'the-signal'); ?></option>
                <option><?php _e('Correction Request', 'the-signal'); ?></option>
                <option><?php _e('Press / Media', 'the-signal'); ?></option>
                <option><?php _e('Tip / Story Idea', 'the-signal'); ?></option>
                <option><?php _e('Partnership', 'the-signal'); ?></option>
            </select>
            <label><?php _e('Message', 'the-signal'); ?></label>
            <textarea name="contact_message" required placeholder="<?php esc_attr_e('Your message…', 'the-signal'); ?>"></textarea>
            <button type="submit" class="contact-submit"><?php _e('Send Message →', 'the-signal'); ?></button>
        </form>
        <?php endif; ?>
    </div>

    <?php endif; endwhile; ?>

</div>

<?php get_footer(); ?>
