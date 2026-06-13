<?php
// Fallback template — redirects to front page
get_header();
?>
<div class="section">
  <?php if (have_posts()): ?>
    <div class="product-grid">
      <?php while (have_posts()): the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="prod-card">
          <div class="prod-card-body">
            <h4><?php the_title(); ?></h4>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;color:#aaa;padding:60px 0;">暫無內容</p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
