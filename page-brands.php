<?php get_header(); ?>

<style>
.brands-body { background: #FFFFFF; min-height: 400px; padding-bottom: 60px; }
.brands-grid {
    background: #FFFFFF;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 24px 20px;
}
.brand-card {
    width: 100%;
    height: 160px;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 24px;
}
.brand-card img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.brand-card-name {
    font-size: 0.85rem;
    color: #444;
    text-align: center;
    font-weight: 500;
    padding: 4px;
    line-height: 1.3;
}
.brands-hero {
    text-align: center;
    padding: 60px 20px 30px;
    background: #1C1C1C;
    color: #fff;
}
.brands-hero h1 { color: #D4AF37; margin-bottom: 8px; }
.brands-hero p { color: #ccc; }
.brands-empty {
    text-align: center;
    padding: 80px 20px;
    color: #888;
    font-size: 1rem;
}
@media (max-width: 1100px) {
    .brands-grid { grid-template-columns: repeat(3, 1fr); padding: 32px 20px 16px; }
}
@media (max-width: 760px) {
    .brands-grid { grid-template-columns: repeat(3, 1fr); gap: 0; padding: 16px 12px 12px; }
    .brand-card { height: 100px; padding: 12px 14px; }
    .brand-card-name { font-size: 0.72rem; }
}
</style>

<div class="brands-hero">
  <h1><?php echo esc_html(mth_text('brands_title', '代理品牌')); ?></h1>
  <p><?php echo esc_html(mth_text('brands_subtitle', 'Our Brands · 明德行代理及進口產品品牌')); ?></p>
</div>

<div class="brands-body">
  <div class="brands-grid">
    <?php
    $brand_query = new WP_Query([
      'post_type'      => 'mth_brand',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'title',
      'order'          => 'ASC',
    ]);

    if ($brand_query->have_posts()):
      while ($brand_query->have_posts()): $brand_query->the_post();
        $brand_name = get_the_title();
        $logo_url   = get_the_post_thumbnail_url(get_the_ID(), 'medium');
    ?>
      <div class="brand-card">
        <?php if ($logo_url): ?>
          <img src="<?php echo esc_url($logo_url); ?>"
               alt="<?php echo esc_attr($brand_name); ?>"
               loading="lazy"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <div class="brand-card-name" style="display:none"><?php echo esc_html($brand_name); ?></div>
        <?php else: ?>
          <div class="brand-card-name"><?php echo esc_html($brand_name); ?></div>
        <?php endif; ?>
      </div>
    <?php
      endwhile;
      wp_reset_postdata();
    else: ?>
      <div class="brands-empty"><?php echo esc_html(mth_text('brands_empty', '品牌資料載入中，請稍後再試。')); ?></div>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
