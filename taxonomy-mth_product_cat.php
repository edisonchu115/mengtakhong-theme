<?php
$term = get_queried_object();
if (!$term || !isset($term->slug)) {
    wp_redirect(home_url('/'));
    exit;
}
$cat_slug = $term->slug;
$cat_name = $term->name;
$count = absint($term->count);

$cat_info = [
  'whisky'  => ['en'=>'Whisky',                  'desc'=>'單一麥芽、調和威士忌系列'],
  'cognac'  => ['en'=>'Cognac & Brandy',          'desc'=>'干邑、拔蘭地系列'],
  'japan'   => ['en'=>'Japanese Products',        'desc'=>'日本威士忌、清酒、燒酎系列'],
  'korea'   => ['en'=>'Korean & Asian Beverages', 'desc'=>'韓國燒酒、亞洲飲品系列'],
  'wine'    => ['en'=>'Wine & Champagne',         'desc'=>'葡萄酒、香檳系列'],
  'spirits' => ['en'=>'Spirits & Liqueurs',       'desc'=>'烈酒、力嬌酒系列'],
  'chinese' => ['en'=>'Chinese Baijiu',           'desc'=>'中國白酒系列'],
  'beer'    => ['en'=>'Beer & Beverages',         'desc'=>'啤酒、飲料系列'],
];
$info = $cat_info[$cat_slug] ?? ['en'=>$cat_name, 'desc'=>''];

get_header();
?>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo home_url('/'); ?>">首頁</a>
    <span class="sep">›</span>
    <span><?php echo esc_html($cat_name); ?></span>
  </div>
</div>

<div class="cat-page-header">
  <div class="eyebrow" translate="no"><?php echo esc_html($info['en']); ?></div>
  <h1><?php echo esc_html($cat_name); ?></h1>
  <p><?php echo esc_html($info['desc']); ?></p>
  <div class="cat-count-badge"><?php echo esc_html($count); ?> 款產品</div>
</div>

<div class="section cat-section-layout">
  <!-- 篩選背景遮罩（手機版） -->
  <div class="cat-filter-backdrop" id="cat-filter-backdrop" aria-hidden="true"></div>

  <!-- 篩選 Sidebar -->
  <aside id="cat-filter" class="cat-filter">
    <div class="cat-filter-header">
      <span>篩選</span>
      <button id="cat-filter-clear" class="cat-filter-clear">清除</button>
      <button id="cat-filter-close" class="cat-filter-close" aria-label="關閉篩選">✕</button>
    </div>
    <?php
    // ── 統計呢個分類嘅產品（國家 / 品牌 / 種類）
    $available_countries = array();
    $available_brands    = array();
    $available_types     = array();

    // 用通用 helper — 任何分類都用該分類嘅 brand/type map
    $cat_brand_map = mth_brand_map($cat_slug);
    $cat_type_map  = mth_type_map($cat_slug);
    $has_brand     = !empty($cat_brand_map);
    $has_type      = !empty($cat_type_map);

    $cq = new WP_Query(array(
      'post_type' => 'mth_product', 'posts_per_page' => -1, 'fields' => 'ids',
      'post_status' => 'publish',
      'tax_query' => array(array('taxonomy' => 'mth_product_cat', 'field' => 'slug', 'terms' => $cat_slug)),
    ));
    foreach ($cq->posts as $pid) {
      $c = get_post_meta($pid, 'origin_country', true);
      if ($c) $available_countries[$c] = isset($available_countries[$c]) ? $available_countries[$c] + 1 : 1;

      if ($has_brand) {
        $b = mth_get_product_brand($pid);
        $available_brands[$b] = isset($available_brands[$b]) ? $available_brands[$b] + 1 : 1;
      }
      if ($has_type) {
        foreach (mth_get_product_types($pid) as $t) {
          $available_types[$t] = isset($available_types[$t]) ? $available_types[$t] + 1 : 1;
        }
      }
    }
    wp_reset_postdata();

    // 排序：由多到少
    arsort($available_brands);
    arsort($available_types);
    ?>

    <?php if ($has_type && !empty($available_types)): ?>
    <div class="cat-filter-group">
      <div class="cat-filter-label">種類</div>
      <label><input type="radio" name="filter-type" value="" checked> 全部</label>
      <?php foreach ($available_types as $key => $cnt):
        if (!isset($cat_type_map[$key])) continue; ?>
        <label>
          <input type="radio" name="filter-type" value="<?php echo esc_attr($key); ?>">
          <?php echo esc_html($cat_type_map[$key]['label']); ?>
          <span class="cnt"><?php echo (int) $cnt; ?></span>
        </label>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($has_brand && !empty($available_brands)): ?>
    <div class="cat-filter-group">
      <div class="cat-filter-label">品牌</div>
      <label><input type="radio" name="filter-brand" value="" checked> 全部</label>
      <?php foreach ($available_brands as $key => $cnt):
        if ($key === 'other' || !isset($cat_brand_map[$key])) continue; ?>
        <label>
          <input type="radio" name="filter-brand" value="<?php echo esc_attr($key); ?>">
          <?php echo esc_html($cat_brand_map[$key]['label']); ?>
          <span class="cnt"><?php echo (int) $cnt; ?></span>
        </label>
      <?php endforeach; ?>
      <?php if (isset($available_brands['other'])): ?>
        <label>
          <input type="radio" name="filter-brand" value="other">
          其他
          <span class="cnt"><?php echo (int) $available_brands['other']; ?></span>
        </label>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php
    // 自動隱藏「原產國」如果分類入面只有 1 個（或 0 個）國家
    if (count($available_countries) > 1):
    ?>
    <div class="cat-filter-group">
      <div class="cat-filter-label">原產國</div>
      <label><input type="radio" name="filter-country" value="" checked> 全部</label>
      <?php
      $all = mth_countries();
      foreach ($available_countries as $key => $cnt):
        if (!isset($all[$key])) continue;
      ?>
        <label>
          <input type="radio" name="filter-country" value="<?php echo esc_attr($key); ?>">
          <?php echo esc_html($all[$key]['flag'] . ' ' . $all[$key]['zh']); ?>
          <span class="cnt"><?php echo (int) $cnt; ?></span>
        </label>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="cat-filter-group">
      <div class="cat-filter-label">來源</div>
      <label><input type="radio" name="filter-source" value="" checked> 全部</label>
      <label><input type="radio" name="filter-source" value="代理正貨"> 代理正貨</label>
      <label><input type="radio" name="filter-source" value="進口"> 進口</label>
    </div>
    <div class="cat-filter-group">
      <div class="cat-filter-label">酒精度 ABV</div>
      <label><input type="radio" name="filter-abv" value="0-100" checked> 全部</label>
      <label><input type="radio" name="filter-abv" value="0-15"> 0–15%</label>
      <label><input type="radio" name="filter-abv" value="15-30"> 15–30%</label>
      <label><input type="radio" name="filter-abv" value="30-45"> 30–45%</label>
      <label><input type="radio" name="filter-abv" value="45-100"> 45%+</label>
    </div>
  </aside>

  <div class="cat-main">
    <div class="cat-toolbar">
      <div class="search-bar-wrap">
        <input id="cat-search" type="text" placeholder="搜尋<?php echo esc_attr($cat_name); ?>產品...">
      </div>
      <button id="cat-filter-toggle" class="cat-filter-toggle">篩選</button>
      <select id="cat-sort" class="cat-sort">
        <option value="title">名稱排序</option>
        <option value="newest">最新到貨</option>
        <option value="abv-desc">酒精度 高 → 低</option>
        <option value="abv-asc">酒精度 低 → 高</option>
      </select>
    </div>
    <div class="search-count" id="search-count"></div>

    <div class="product-grid" id="product-grid">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $query = new WP_Query([
      'post_type'      => 'mth_product',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'tax_query'      => [[
        'taxonomy' => 'mth_product_cat',
        'field'    => 'slug',
        'terms'    => $cat_slug,
      ]],
    ]);

    if ($query->have_posts()):
      while ($query->have_posts()): $query->the_post();
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        // 冇精選圖片唔再 skip，會顯示米色 placeholder，避免 count mismatch
        $name_zh = get_the_title();
        $name_en = get_post_meta(get_the_ID(), 'name_en', true);
        $spec    = get_post_meta(get_the_ID(), 'spec', true);
        $abv     = get_post_meta(get_the_ID(), 'abv', true);
        $source  = get_post_meta(get_the_ID(), 'source', true);
        $slug    = get_post_field('post_name', get_the_ID());
        $country = get_post_meta(get_the_ID(), 'origin_country', true);
        $search_str = strtolower($name_zh . ' ' . $name_en . ' ' . $spec);
        $abv_num    = preg_replace('/[^0-9.]/', '', $abv);
        $post_date  = get_post_time('U', false, get_the_ID());
        $brand_key  = $has_brand ? mth_get_product_brand(get_the_ID()) : '';
        $types_str  = $has_type  ? implode(',', mth_get_product_types(get_the_ID())) : '';
        ?>
        <div class="prod-card-wrap"
             data-search="<?php echo esc_attr($search_str); ?>"
             data-title="<?php echo esc_attr($name_zh); ?>"
             data-country="<?php echo esc_attr($country); ?>"
             data-source="<?php echo esc_attr($source); ?>"
             data-abv="<?php echo esc_attr($abv_num); ?>"
             data-date="<?php echo esc_attr($post_date); ?>"
             data-brand="<?php echo esc_attr($brand_key); ?>"
             data-types="<?php echo esc_attr($types_str); ?>">
        <a href="<?php the_permalink(); ?>" class="prod-card">
          <?php if($source === '代理正貨'): ?>
          <div class="badge-agency-sm" style="margin:8px 16px 0;">代理正貨</div>
          <?php endif; ?>
          <div class="prod-card-img">
            <?php if ($img_url): ?>
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($name_zh); ?>"
                   loading="lazy"
                   onerror="this.parentElement.innerHTML='<div class=&quot;prod-img-placeholder&quot;>&#127870;</div>'">
            <?php else: ?>
              <div class="prod-img-placeholder">🍾</div>
            <?php endif; ?>
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
        <button class="prod-quick-view"
                data-action="quick-view"
                data-id="<?php echo (int) get_the_ID(); ?>"
                data-title="<?php echo esc_attr($name_zh); ?>"
                data-en="<?php echo esc_attr($name_en); ?>"
                data-url="<?php echo esc_attr(get_permalink()); ?>"
                data-img="<?php echo esc_attr($img_url); ?>"
                data-spec="<?php echo esc_attr($spec); ?>"
                data-abv="<?php echo esc_attr($abv); ?>"
                data-source="<?php echo esc_attr($source); ?>"
                data-flag="<?php echo esc_attr($flag); ?>"
                data-country="<?php echo esc_attr($country); ?>"
                data-cat="<?php echo esc_attr($cat_name); ?>"
                aria-label="快速預覽">👁</button>
        <button class="prod-add-inquiry"
                data-action="add-to-inquiry"
                data-id="<?php echo (int) get_the_ID(); ?>"
                data-title="<?php echo esc_attr($name_zh); ?>"
                data-url="<?php echo esc_attr(get_permalink()); ?>"
                data-spec="<?php echo esc_attr($spec); ?>"
                data-abv="<?php echo esc_attr($abv); ?>"
                aria-label="加入查詢清單">＋</button>
        </div>
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
      var text = c.getAttribute('data-search') || '';
      var show = q === '' || text.includes(q);
      c.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    counter.textContent = '顯示 ' + visible + ' 個產品';
    noRes.style.display = visible === 0 ? 'block' : 'none';
  });
});
</script>

<?php get_footer(); ?>
