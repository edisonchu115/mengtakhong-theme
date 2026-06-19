<?php get_header(); ?>

<!-- Hero -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-eyebrow"><?php echo esc_html(mth_text('hero_eyebrow', 'Meng Tak Hong International · Est. 1998')); ?></div>
    <h1><?php echo esc_html(mth_text('hero_title_1', '澳門')); ?> <span><?php echo esc_html(mth_text('hero_title_2', '洋酒飲品')); ?></span><br><?php echo esc_html(mth_text('hero_title_3', '批發代理')); ?></h1>
    <p><?php echo esc_html(mth_text('hero_subtitle', '專業B2B批發服務 · 威士忌 · 干邑 · 葡萄酒 · 日本酒 · 韓國飲品')); ?></p>
  </div>
</section>

<!-- Brand Marquee：橫向自動捲動，直接讀 mth_brand（加新品牌自動更新）-->
<?php
$mth_marquee = new WP_Query(array(
  'post_type'      => 'mth_brand',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'title',
  'order'          => 'ASC',
));
$mth_logos = array();
if ($mth_marquee->have_posts()) {
  while ($mth_marquee->have_posts()) { $mth_marquee->the_post();
    $u = get_the_post_thumbnail_url(get_the_ID(), 'medium');
    if ($u) $mth_logos[] = array('u' => $u, 't' => get_the_title());
  }
  wp_reset_postdata();
}
if (count($mth_logos) >= 4):
  $mth_dur = max(20, count($mth_logos) * 2.5); // 速度跟品牌數自動調節，每加一個都唔會變快
?>
<section class="brand-marquee-section" aria-label="合作品牌">
  <div class="brand-marquee">
    <div class="brand-marquee-track" style="animation-duration: <?php echo esc_attr($mth_dur); ?>s;">
      <?php for ($d = 0; $d < 2; $d++): foreach ($mth_logos as $lg): ?>
        <div class="brand-marquee-item">
          <img src="<?php echo esc_url($lg['u']); ?>" alt="<?php echo esc_attr($lg['t']); ?>" loading="lazy"<?php echo $d ? ' aria-hidden="true"' : ''; ?>>
        </div>
      <?php endforeach; endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Category Buttons -->
<div class="section-dark">
  <div class="section-dark-inner">
    <div class="section-header">
      <div class="eyebrow"><?php echo esc_html(mth_text('cathdr_eyebrow', 'Our Categories')); ?></div>
      <h2 class="white"><?php echo esc_html(mth_text('cathdr_title', '產品分類')); ?></h2>
      <p class="white"><?php echo esc_html(mth_text('cathdr_subtitle', '覆蓋全線洋酒、飲品及食品')); ?></p>
      <div class="divider-gold"></div>
    </div>
    <div class="cat-btn-grid">
      <?php
      $categories = [
        ['slug'=>'whisky',  'zh'=>'威士忌',       'en'=>'Whisky'],
        ['slug'=>'cognac',  'zh'=>'干邑/拔蘭地',   'en'=>'Cognac & Brandy'],
        ['slug'=>'japan',   'zh'=>'日本產品',      'en'=>'Japanese Products'],
        ['slug'=>'korea',   'zh'=>'韓國/亞洲飲品', 'en'=>'Korean & Asian Beverages'],
        ['slug'=>'wine',    'zh'=>'葡萄酒/香檳',   'en'=>'Wine & Champagne'],
        ['slug'=>'liqueur-gin-rum', 'zh'=>'力嬌/琴酒/冧酒', 'en'=>'Liqueur, Gin & Rum'],
        ['slug'=>'chinese', 'zh'=>'中國白酒',      'en'=>'Chinese Baijiu'],
        ['slug'=>'beer-beverages', 'zh'=>'啤酒/飲料', 'en'=>'Beer & Beverages'],
      ];
      foreach ($categories as $cat):
        $term = get_term_by('slug', $cat['slug'], 'mth_product_cat');
        $count = $term ? $term->count : 0;
      ?>
      <a href="<?php echo esc_url(home_url('/product-category/' . $cat['slug'] . '/')); ?>" class="cat-btn">
        <span class="cat-btn-zh"><?php echo esc_html($cat['zh']); ?></span>
        <span class="cat-btn-en" translate="no"><?php echo esc_html($cat['en']); ?></span>
        <span class="cat-btn-count"><?php echo $count ? (int)$count . ' 款產品' : ''; ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Carousels -->
<?php
$carousels = [
  ['slug'=>'whisky',  'zh'=>'威士忌精選',   'en'=>'Whisky Selection'],
  ['slug'=>'cognac',  'zh'=>'干邑精選',     'en'=>'Cognac & Brandy Selection'],
  ['slug'=>'japan',   'zh'=>'日本產品精選', 'en'=>'Japanese Selection'],
  ['slug'=>'korea',   'zh'=>'韓國產品精選', 'en'=>'Korean Selection'],
  ['slug'=>'wine',    'zh'=>'葡萄酒精選',   'en'=>'Wine Selection'],
  ['slug'=>'liqueur-gin-rum', 'zh'=>'力嬌/琴酒/冧酒精選', 'en'=>'Liqueur, Gin & Rum Selection'],
  ['slug'=>'chinese', 'zh'=>'中國白酒精選', 'en'=>'Chinese Baijiu Selection'],
  ['slug'=>'beer-beverages', 'zh'=>'啤酒/飲料精選', 'en'=>'Beer & Beverages Selection'],
];

foreach ($carousels as $c):
  // 優先選有精選圖嘅產品；如果完全冇，就 fallback 顯示無圖產品
  $query = new WP_Query([
    'post_type'      => 'mth_product',
    'posts_per_page' => 15,
    'post_status'    => 'publish',
    'orderby'        => 'rand',
    'tax_query'      => [[
      'taxonomy' => 'mth_product_cat',
      'field'    => 'slug',
      'terms'    => $c['slug'],
    ]],
    'meta_query'     => [[
      'key'     => '_thumbnail_id',
      'compare' => 'EXISTS',
    ]],
  ]);
  // 如果完全冇精選圖嘅產品，就顯示所有產品（無圖會用 placeholder）
  if (!$query->have_posts()) {
    $query = new WP_Query([
      'post_type'      => 'mth_product',
      'posts_per_page' => 15,
      'post_status'    => 'publish',
      'orderby'        => 'rand',
      'tax_query'      => [[
        'taxonomy' => 'mth_product_cat',
        'field'    => 'slug',
        'terms'    => $c['slug'],
      ]],
    ]);
  }
  $carousel_id = 'carousel-' . $c['slug'];
  $is_empty = !$query->have_posts();
?>
<div class="carousel-section">
  <div class="carousel-header">
    <div class="carousel-header-left">
      <div class="carousel-title"><?php echo esc_html($c['zh']); ?></div>
      <div class="carousel-subtitle" translate="no"><?php echo esc_html($c['en']); ?></div>
    </div>
    <?php if (!$is_empty): ?>
    <a href="<?php echo home_url('/product-category/' . $c['slug'] . '/'); ?>" class="carousel-view-all">瀏覽全部 →</a>
    <?php endif; ?>
  </div>
  <?php if ($is_empty): ?>
  <div class="carousel-empty">
    <span class="ce-icon">🎯</span>
    <div class="ce-text">產品準備中 — 敬請期待</div>
    <a href="<?php echo home_url('/contact/'); ?>" class="ce-link">查詢報價 →</a>
  </div>
  <?php else: ?>
  <div class="carousel-wrap-outer">
    <button class="carousel-btn carousel-prev" onclick="mthCarouselMove('<?php echo $carousel_id; ?>',-1)">&#10094;</button>
    <div class="carousel-track-outer">
      <div class="carousel-track" id="<?php echo $carousel_id; ?>">
        <?php while ($query->have_posts()): $query->the_post();
          $pid     = get_the_ID();
          $title   = get_the_title();
          $name_en = get_post_meta($pid, 'name_en', true);
          $spec    = get_post_meta($pid, 'spec', true);
          $slug    = get_post_field('post_name', $pid);
          $acf_img = function_exists('get_field') ? get_field('product_image', $pid) : '';
          $img_url = get_the_post_thumbnail_url($pid, 'medium')
                     ?: ($acf_img ?: content_url('/uploads/products/' . $slug . '.jpg'));
          $country = get_post_meta($pid, 'origin_country', true);
        ?>
        <a href="<?php the_permalink(); ?>" class="carousel-card">
          <div class="carousel-card-img">
            <img src="<?php echo esc_url($img_url); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy"
                 onerror="this.style.display='none';this.parentElement.style.background='#f5f0e8';">
            <?php $flag = mth_country_flag($country); if ($flag): ?><span class="prod-flag"><?php echo esc_html($flag); ?></span><?php endif; ?>
          </div>
          <div class="carousel-card-body">
            <div class="carousel-card-zh"><?php echo esc_html($title); ?></div>
            <?php if ($name_en): ?>
            <div class="carousel-card-en" translate="no"><?php echo esc_html($name_en); ?></div>
            <?php endif; ?>
            <?php if ($spec): ?>
            <div class="carousel-card-spec"><?php echo esc_html($spec); ?></div>
            <?php endif; ?>
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <button class="carousel-btn carousel-next" onclick="mthCarouselMove('<?php echo $carousel_id; ?>',1)">&#10095;</button>
  </div>
  <?php endif; ?>
</div>
<?php endforeach; ?>

<!-- Stats -->
<div class="stats-section">
  <div class="stats-inner">
    <div class="stat-item"><div class="num"><?php echo esc_html(mth_text('stat1_num', '880')); ?><span>+</span></div><div class="lbl"><?php echo esc_html(mth_text('stat1_label', '款產品')); ?></div></div>
    <div class="stat-item"><div class="num"><span><?php echo esc_html(mth_text('stat2_num', '25')); ?></span>+</div><div class="lbl"><?php echo esc_html(mth_text('stat2_label', '年行業經驗')); ?></div></div>
    <div class="stat-item"><div class="num"><?php echo esc_html(mth_text('stat3_num', '8')); ?></div><div class="lbl"><?php echo esc_html(mth_text('stat3_label', '產品系列')); ?></div></div>
    <div class="stat-item"><div class="num"><?php echo esc_html(mth_text('stat4_num', 'B2B')); ?></div><div class="lbl"><?php echo esc_html(mth_text('stat4_label', '專業批發服務')); ?></div></div>
  </div>
</div>

<script>
var mthCarouselIdx = {};
function mthCarouselMove(id, dir) {
  var track = document.getElementById(id);
  if (!track || !track.children.length) return;
  var cards   = track.children;
  var cardW   = cards[0].offsetWidth + 18;
  var visible = Math.floor(track.parentElement.offsetWidth / cardW);
  var total   = cards.length;
  var max     = Math.max(0, total - visible);
  if (mthCarouselIdx[id] === undefined) mthCarouselIdx[id] = 0;
  mthCarouselIdx[id] = Math.max(0, Math.min(mthCarouselIdx[id] + dir, max));
  track.style.transform = 'translateX(-' + (mthCarouselIdx[id] * cardW) + 'px)';
}
</script>

<?php get_footer(); ?>
