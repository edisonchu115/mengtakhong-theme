<?php
get_header();
?>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo home_url('/'); ?>">首頁</a>
    <span class="sep">›</span>
    <span>所有產品</span>
  </div>
</div>

<div class="cat-page-header">
  <div class="eyebrow">All Products</div>
  <h1>所有<span>產品</span></h1>
  <p>明德行國際有限公司全線洋酒及飲品</p>
</div>

<div class="section">
  <div class="search-bar-wrap">
    <input id="cat-search" type="text" placeholder="搜尋產品...">
  </div>
  <div class="search-count" id="search-count"></div>

  <div class="product-grid" id="product-grid">
    <?php
    $query = new WP_Query([
      'post_type'      => 'mth_product',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'title',
      'order'          => 'ASC',
    ]);

    if ($query->have_posts()):
      while ($query->have_posts()): $query->the_post();
        $name_zh = get_post_meta(get_the_ID(), 'name_zh', true) ?: get_the_title();
        $name_en = get_post_meta(get_the_ID(), 'name_en', true);
        $spec    = get_post_meta(get_the_ID(), 'spec', true);
        $abv     = get_post_meta(get_the_ID(), 'abv', true);
        $source  = get_post_meta(get_the_ID(), 'source', true);
        $country = get_post_meta(get_the_ID(), 'origin_country', true);
        $slug    = get_post_field('post_name', get_the_ID());
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium')
                   ?: content_url('/uploads/products/' . $slug . '.jpg');
        $search_str = strtolower($name_zh . ' ' . $name_en . ' ' . $spec);
        ?>
        <a href="<?php the_permalink(); ?>" class="prod-card" data-search="<?php echo esc_attr($search_str); ?>">
          <?php if($source === '代理正貨'): ?>
          <div class="badge-agency-sm" style="margin:8px 16px 0;">代理正貨</div>
          <?php endif; ?>
          <div class="prod-card-img">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($name_zh); ?>"
                 loading="lazy"
                 onerror="this.style.display='none';this.parentElement.style.background='#f5f5f5';">
            <?php $flag = mth_country_flag($country); if ($flag): ?><span class="prod-flag"><?php echo esc_html($flag); ?></span><?php endif; ?>
          </div>
          <div class="prod-card-body">
            <h4><?php echo esc_html($name_zh); ?></h4>
            <?php if($name_en): ?><div class="en-name" translate="no"><?php echo esc_html($name_en); ?></div><?php endif; ?>
            <div class="prod-card-meta">
              <?php if($spec): ?><span class="spec"><?php echo esc_html($spec); ?></span><?php endif; ?>
              <?php if($abv): ?><span class="abv"><?php echo esc_html($abv); ?>%</span><?php endif; ?>
            </div>
          </div>
        </a>
        <?php
      endwhile;
      wp_reset_postdata();
    else: ?>
      <div class="no-results" style="display:block">未找到產品</div>
    <?php endif; ?>
  </div>
  <div class="no-results" id="no-results">找不到相關產品</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var input = document.getElementById('cat-search');
  var cards = document.querySelectorAll('.prod-card');
  var counter = document.getElementById('search-count');
  var noRes = document.getElementById('no-results');
  counter.textContent = '顯示 ' + cards.length + ' 個產品';
  if (!input) return;
  input.addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    var visible = 0;
    cards.forEach(function(c) {
      var show = q === '' || (c.getAttribute('data-search') || '').includes(q);
      c.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    counter.textContent = '顯示 ' + visible + ' 個產品';
    noRes.style.display = visible === 0 ? 'block' : 'none';
  });
});
</script>

<?php get_footer(); ?>
