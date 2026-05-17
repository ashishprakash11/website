<?php
if (post_password_required()) {
    echo '<p>' . __('This post is password protected. Enter the password to view comments.', 'the-signal') . '</p>';
    return;
}
?>

<?php if (have_comments()): ?>
<div class="comments-title">
    <?php printf(_n('%s Comment', '%s Comments', get_comments_number(), 'the-signal'), number_format_i18n(get_comments_number())); ?>
</div>

<ol class="comment-list" style="list-style:none">
<?php
wp_list_comments([
    'style'       => 'ol',
    'short_ping'  => true,
    'callback'    => 'signal_comment_template',
    'avatar_size' => 40,
]);
?>
</ol>

<?php the_comments_pagination(['prev_text' => '←', 'next_text' => '→']); ?>
<?php endif; ?>

<?php if (comments_open()): ?>
<div class="comment-form-wrap">
    <h3><?php _e('Join the Discussion', 'the-signal'); ?></h3>
    <?php
    comment_form([
        'title_reply'          => '',
        'title_reply_before'   => '',
        'title_reply_after'    => '',
        'submit_button'        => '<button type="submit" class="comment-submit">%4$s</button>',
        'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
        'comment_field'        => '<p><label for="comment">' . __('Your Comment', 'the-signal') . '</label><textarea id="comment" name="comment" class="c-textarea" placeholder="' . esc_attr__('Share your thoughts…', 'the-signal') . '" required></textarea></p>',
        'label_submit'         => __('Post Comment', 'the-signal'),
        'class_submit'         => 'comment-submit',
        'fields' => [
            'author' => '<p><label for="author">' . __('Name', 'the-signal') . '</label><input id="author" name="author" type="text" required></p>',
            'email'  => '<p><label for="email">' . __('Email', 'the-signal') . '</label><input id="email" name="email" type="email" required></p>',
            'url'    => '',
            'cookies'=> '',
        ],
    ]);
    ?>
</div>
<?php endif; ?>

<?php
function signal_comment_template($comment, $args, $depth) {
    $avatar_url = get_avatar_url($comment->comment_author_email, ['size' => 80]);
    $initial    = strtoupper(substr($comment->comment_author, 0, 1));
    $colors     = ['linear-gradient(135deg,#3ab0c8,#2dc483)', 'linear-gradient(135deg,#c47de8,#c8392b)', 'linear-gradient(135deg,#e8a832,#c8392b)'];
    $color      = $colors[crc32($comment->comment_author) % count($colors)];
    ?>
    <li <?php comment_class('comment-item'); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-item">
            <div class="comment-avatar" style="background:<?php echo esc_attr($color); ?>">
                <?php if ($avatar_url): ?>
                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($comment->comment_author); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                <?php else: ?>
                    <?php echo esc_html($initial); ?>
                <?php endif; ?>
            </div>
            <div style="flex:1">
                <div class="comment-meta">
                    <span class="comment-author"><?php echo esc_html($comment->comment_author); ?></span>
                    <span class="comment-date"><?php echo human_time_diff(get_comment_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                </div>
                <div class="comment-text"><?php comment_text(); ?></div>
                <?php if ($args['max_depth'] && comment_reply_link(['depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '<div class="comment-reply-link">', 'after' => '</div>'])): endif; ?>
                <?php comment_reply_link(array_merge($args, ['depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '<div class="comment-reply-link">', 'after' => '</div>'])); ?>
            </div>
        </div>
    <?php
}
?>
