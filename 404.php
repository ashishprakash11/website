<?php get_header(); ?>
<div class="container">
<div class="error-page">
  <div>
    <span class="error-code">404</span>
    <h1><?php _e('Page not found', 'the-signal'); ?></h1>
    <p><?php _e('The story you are looking for may have been moved or removed.', 'the-signal'); ?></p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">← <?php _e('Back to The Signal', 'the-signal'); ?></a>
  </div>
</div>
</div>
<?php get_footer(); ?>
