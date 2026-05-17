<?php get_header(); while (have_posts()): the_post(); ?>
<div class="container">
<div class="main-grid">
<main class="content-area">
  <div class="article-body" style="padding-top:40px">
    <h1 style="font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;color:var(--white);margin-bottom:1.5em"><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
</main>
<?php get_sidebar(); ?>
</div></div>
<?php endwhile; get_footer(); ?>
