<?php get_header(); ?>
<div class="container">
<div style="padding:40px 0 24px">
  <h1 style="font-family:'DM Serif Display',serif;font-size:2.5rem;color:var(--white)"><?php the_archive_title(); ?></h1>
  <?php the_archive_description('<p style="color:var(--muted);margin-top:8px">','</p>'); ?>
</div>
<div class="main-grid">
<main class="content-area">
  <div class="article-list">
    <?php if (have_posts()): while (have_posts()): the_post(); signal_article_row(); endwhile; endif; ?>
  </div>
  <div class="pagination"><?php the_posts_pagination(['prev_text'=>'←','next_text'=>'→']); ?></div>
</main>
<?php get_sidebar(); ?>
</div></div>
<?php get_footer(); ?>
