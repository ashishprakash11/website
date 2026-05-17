<?php get_header(); ?>
<div class="container">
<div class="search-hero">
  <h1><?php _e('Search', 'the-signal'); ?></h1>
  <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form-wrap">
    <input class="search-input" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search stories, topics, authors…', 'the-signal'); ?>">
    <button class="search-submit" type="submit"><?php _e('Search', 'the-signal'); ?></button>
  </form>
  <?php if (get_search_query()): ?>
  <div class="search-info"><?php printf(__('%d results for "%s"', 'the-signal'), $wp_query->found_posts, get_search_query()); ?></div>
  <?php endif; ?>
</div>
<div class="main-grid" style="padding-top:40px">
<main class="content-area">
  <?php if (have_posts()): ?>
  <div class="article-list">
    <?php while (have_posts()): the_post(); signal_article_row(); endwhile; ?>
  </div>
  <div class="pagination"><?php the_posts_pagination(['prev_text'=>'←','next_text'=>'→']); ?></div>
  <?php else: ?>
  <div class="no-results"><h2><?php _e('Nothing found', 'the-signal'); ?></h2><p><?php _e('Try different search terms.', 'the-signal'); ?></p></div>
  <?php endif; ?>
</main>
<?php get_sidebar(); ?>
</div></div>
<?php get_footer(); ?>
