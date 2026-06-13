<?php
$keyword    = get_search_query();
$post_count = $GLOBALS['wp_query']->found_posts;
get_header();
?>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo home_url('/'); ?>">首頁</a>
    <span class="sep">›</span>
    <span>搜尋結果</span>
  </div>
</div>

<div class="cat-page-header">
  <div class="eyebrow">Search Results</div>
  <h1>搜尋：<span><?php echo esc_html($keyword); ?></span></h1>
  <?php if ($post_count > 0): ?>
    <p>共找到 <strong><?php echo absint($post_count); ?></strong> 個相關產品</p>
  <?php else: ?>
    <p>找不到相關產品，請嘗試其他關鍵字</p>
  <?php endif; ?>
</div>

<div class="section">
  <?php if (have_posts()): ?>
  <div class="product-grid" id="product-grid">
    <?php while (have_posts()): the_post();
      $pid     = get_the_ID();
      $img_url = get_the_post_thumbnail_url($pid, 'medium');
      // 冇精選圖片唔再 skip，placeholder fallback
      $title   = get_the_title();
      $name_en = get_post_meta($pid, 'name_en', true);
      $spec    = get_post_meta($pid, 'spec', true);
      $abv     = get_post_meta($pid, 'abv', true);
      $source  = get_post_meta($pid, 'source', true);
      $slug    = get_post_field('post_name', $pid);
      $country = get_post_meta($pid, 'origin_country', true);
    ?>
    <a href="<?php the_permalink(); ?>" class="prod-card">
      <?php if ($source === '代理正貨'): ?>
      <div class="badge-agency-sm" style="margin:8px 16px 0;">代理正貨</div>
      <?php endif; ?>
      <div class="prod-card-img">
        <img src="<?php echo esc_url($img_url); ?>"
             alt="<?php echo esc_attr($title); ?>"
             loading="lazy"
             onerror="this.parentElement.innerHTML='<div style=&quot;font-size:2.8rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;&quot;>&#127870;</div>'">
        <?php $flag = mth_country_flag($country); if ($flag): ?><span class="prod-flag"><?php echo esc_html($flag); ?></span><?php endif; ?>
      </div>
      <div class="prod-card-body">
        <h4><?php echo esc_html($title); ?></h4>
        <?php if ($name_en): ?><div class="en-name" translate="no"><?php echo esc_html($name_en); ?></div><?php endif; ?>
        <div class="prod-card-meta">
          <?php if ($spec): ?><span class="spec"><?php echo esc_html($spec); ?></span><?php endif; ?>
          <?php if ($abv): ?><span class="abv"><?php echo esc_html($abv); ?>%</span><?php endif; ?>
        </div>
      </div>
    </a>
    <?php endwhile; ?>
  </div>

  <?php if ($GLOBALS['wp_query']->max_num_pages > 1): ?>
  <div class="pagination" style="text-align:center;padding:40px 0;">
    <?php echo paginate_links(['prev_text'=>'&laquo; 上一頁','next_text'=>'下一頁 &raquo;']); ?>
  </div>
  <?php endif; ?>

  <?php else: ?>
  <div style="text-align:center;padding:80px 20px;">
    <div style="font-size:3rem;margin-bottom:20px;">&#128269;</div>
    <h2 style="color:#1C1C1C;margin-bottom:12px;">找不到相關產品</h2>
    <p style="color:#666;margin-bottom:32px;">請嘗試其他關鍵字，或瀏覽以下產品分類</p>
    <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;">
      <?php
      $cats = ['whisky'=>'威士忌','cognac'=>'干邑/拔蘭地','japan'=>'日本產品',
               'korea'=>'韓國/亞洲飲品','wine'=>'葡萄酒/香檳','liqueur-gin-rum'=>'力嬌/琴酒/冧酒',
               'chinese'=>'中國白酒','beer-beverages'=>'啤酒/飲料'];
      foreach ($cats as $s => $n):
      ?>
      <a href="<?php echo esc_url(home_url('/product-category/' . $s . '/')); ?>"
         style="padding:10px 20px;background:#1C1C1C;color:#D4AF37;text-decoration:none;border-radius:4px;font-size:0.9rem;">
        <?php echo esc_html($n); ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
