<?php
get_header();

while (have_posts()): the_post();
  $name_zh = get_the_title();
  $name_en = get_post_meta(get_the_ID(), 'name_en', true);
  $spec    = get_post_meta(get_the_ID(), 'spec', true);
  $abv     = get_post_meta(get_the_ID(), 'abv', true);
  $source  = get_post_meta(get_the_ID(), 'source', true);
  $country = get_post_meta(get_the_ID(), 'origin_country', true);
  $slug    = get_post_field('post_name', get_the_ID());
  $terms   = wp_get_post_terms(get_the_ID(), 'mth_product_cat');
  $term    = $terms ? $terms[0] : null;
  $acf_img = function_exists('get_field') ? get_field('product_image') : '';
  $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large')
             ?: ($acf_img ?: content_url('/uploads/products/' . $slug . '.jpg'));
  $country_name = mth_country_name($country);
  $flag = mth_country_flag($country);
?>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo home_url('/'); ?>">首頁</a>
    <span class="sep">›</span>
    <?php if($term): ?>
    <a href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
    <span class="sep">›</span>
    <?php endif; ?>
    <span><?php echo esc_html($name_zh); ?></span>
  </div>
</div>

<div class="product-detail">
  <!-- 產品圖片放大鏡 -->
  <div class="zoom-wrapper">
    <div class="zoom-container" id="zoomContainer">
      <img id="productImg"
           src="<?php echo esc_url($img_url); ?>"
           alt="<?php echo esc_attr($name_zh); ?>"
           style="width:100%;display:block;border-radius:8px;"
           onerror="this.closest('.zoom-wrapper').innerHTML='<div style=&quot;font-size:7rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;&quot;>&#127870;</div>'">
      <div class="zoom-circle" id="zoomCircle"></div>
      <?php if ($flag): ?><span class="prod-flag" style="top:14px;right:14px;font-size:2rem;"><?php echo esc_html($flag); ?></span><?php endif; ?>
    </div>
  </div>

  <style>
  /* 產品詳情頁佈局 */
  .product-detail {
    display: flex;
    gap: 60px;
    align-items: flex-start;
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 40px;
  }
  .zoom-wrapper {
    width: 480px;
    min-width: 480px;
    height: 480px;
    flex-shrink: 0;
  }
  .zoom-container {
    width: 480px;
    height: 480px;
    background: #FFFFFF;
    border: 1px solid #E5DDD0;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    cursor: crosshair;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  #productImg {
    width: 95% !important;
    height: 95% !important;
    object-fit: contain !important;
    display: block !important;
  }
  .zoom-circle {
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px solid #D4AF37;
    box-shadow: 0 0 0 4px rgba(212,175,55,0.2), 0 4px 20px rgba(0,0,0,0.2);
    pointer-events: none;
    display: none;
    background-repeat: no-repeat;
    z-index: 10;
    transform: translate(-50%, -50%);
  }
  .product-info {
    flex: 1;
    min-width: 0;
    padding-top: 10px;
  }
  @media (max-width: 900px) {
    .product-detail { flex-direction: column; gap: 30px; padding: 0 20px; }
    .zoom-wrapper { width: 100% !important; min-width: unset !important; height: 350px !important; }
    .zoom-container { width: 100% !important; height: 350px !important; }
    #productImg { width: 95% !important; height: 95% !important; }
  }
  .health-warning-center {
    max-width: 1200px;
    margin: 40px auto 0;
    padding: 20px 40px;
    text-align: center;
    border-top: 1px solid #E5DDD0;
    border-bottom: 1px solid #E5DDD0;
    background: #FAFAFA;
  }
  .health-warning-center p {
    font-size: 10px;
    color: #aaa;
    margin: 3px 0;
    line-height: 1.8;
    letter-spacing: 0.5px;
  }
  .hw-deco {
    color: #D4AF37;
    font-size: 8px;
    letter-spacing: 4px;
    margin: 8px 0;
  }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('zoomContainer');
    var img       = document.getElementById('productImg');
    var circle    = document.getElementById('zoomCircle');
    if (!container || !img || !circle) return;

    function setupZoom() {
      var cw = container.offsetWidth;
      var ch = container.offsetHeight;
      circle.style.backgroundImage = 'url(' + img.src + ')';
      circle.style.backgroundSize  = (cw * 3) + 'px ' + (ch * 3) + 'px';
    }
    if (img.complete) { setupZoom(); } else { img.addEventListener('load', setupZoom); }

    // 手機版停用
    if (window.innerWidth <= 768) { container.style.cursor = 'default'; return; }

    container.addEventListener('mouseenter', function() { circle.style.display = 'block'; });
    container.addEventListener('mouseleave', function() { circle.style.display = 'none'; });
    container.addEventListener('mousemove', function(e) {
      var rect = container.getBoundingClientRect();
      var x = e.clientX - rect.left;
      var y = e.clientY - rect.top;
      circle.style.left = x + 'px';
      circle.style.top  = y + 'px';
      circle.style.backgroundPosition = -(x * 3 - 75) + 'px ' + -(y * 3 - 75) + 'px';
    });
  });
  </script>
  <div class="product-info">
    <?php if($source === '代理正貨'): ?>
    <div class="badge-agency">代理正貨</div>
    <?php endif; ?>
    <?php if($term): ?>
    <div class="cat-breadcrumb">
      <a href="<?php echo get_term_link($term); ?>" class="badge-cat"><?php echo esc_html($term->name); ?></a>
    </div>
    <?php endif; ?>
    <h1><?php echo esc_html($name_zh); ?></h1>
    <?php if($name_en): ?><div class="en-name" translate="no"><?php echo esc_html($name_en); ?></div><?php endif; ?>
    <div class="divider"></div>
    <table class="spec-table">
      <?php if($spec): ?><tr><td>規格</td><td><?php echo esc_html($spec); ?></td></tr><?php endif; ?>
      <?php if($abv): ?><tr><td>酒精度</td><td><?php echo esc_html($abv); ?>%</td></tr><?php endif; ?>
      <?php if($source): ?><tr><td>來源</td><td><?php echo esc_html($source); ?></td></tr><?php endif; ?>
      <?php if($country_name): ?><tr><td>原產國</td><td><?php echo esc_html($flag . ' ' . $country_name); ?></td></tr><?php endif; ?>
      <?php if($term): ?><tr><td>分類</td><td><?php echo esc_html($term->name); ?></td></tr><?php endif; ?>
    </table>
    <button class="mth-add-inquiry"
            data-action="add-to-inquiry"
            data-id="<?php echo (int) get_the_ID(); ?>"
            data-title="<?php echo esc_attr($name_zh); ?>"
            data-url="<?php echo esc_attr(get_permalink()); ?>"
            data-spec="<?php echo esc_attr($spec); ?>"
            data-abv="<?php echo esc_attr($abv); ?>">
      <span class="ai-icon">＋</span>加入查詢清單
    </button>
  </div>
</div>

<div class="health-warning-center">
  <div class="hw-deco">◆ ◆ ◆</div>
  <p>過量飲酒危害健康</p>
  <p>CONSUMIR BEBIDAS ALCOÓLICAS EM EXCESSO PREJUDICA A SAÚDE</p>
  <p>EXCESSIVE DRINKING OF ALCOHOLIC BEVERAGES IS HARMFUL TO HEALTH</p>
  <p>禁止向未滿十八歲人士銷售或提供酒精飲料</p>
  <p>A VENDA OU DISPONIBILIZAÇÃO DE BEBIDAS ALCOÓLICAS A MENORES DE 18 ANOS É PROIBIDA</p>
  <p>THE SALE OR SUPPLY OF ALCOHOLIC BEVERAGES TO ANYONE UNDER THE AGE OF 18 IS PROHIBITED</p>
  <div class="hw-deco">◆ ◆ ◆</div>
</div>

<?php
if ($term) {
    // 搵同分類產品（最多20個隨機）
    $related = new WP_Query([
        'post_type'      => 'mth_product',
        'posts_per_page' => 20,
        'orderby'        => 'rand',
        'post__not_in'   => [get_the_ID()],
        'tax_query'      => [[
            'taxonomy' => 'mth_product_cat',
            'field'    => 'slug',
            'terms'    => $term->slug,
        ]],
    ]);

    // 同品牌（名稱前兩字相同）排前面
    $current_prefix = mb_substr($name_zh, 0, 2);
    $same_brand = [];
    $other_cat  = [];
    if ($related->have_posts()) {
        while ($related->have_posts()) {
            $related->the_post();
            $r_prefix = mb_substr(get_the_title(), 0, 2);
            if ($r_prefix === $current_prefix) {
                $same_brand[] = get_the_ID();
            } else {
                $other_cat[] = get_the_ID();
            }
        }
    }
    wp_reset_postdata();

    $ymal_ids = array_slice(array_merge($same_brand, $other_cat), 0, 10);

    if (!empty($ymal_ids)):
?>
<div class="you-may-like-section">
  <div style="max-width:1300px;margin:0 auto;padding:40px 20px;">
    <div class="ymal-title"><span class="zh-text">你可能喜歡</span> <small translate="no">You May Also Like</small></div>
    <div class="carousel-wrap-outer">
      <button class="carousel-btn carousel-prev" onclick="ymalMove(-1)">&#10094;</button>
      <div class="carousel-track-outer">
        <div class="carousel-track" id="ymal-track">
          <?php foreach ($ymal_ids as $pid):
            $p_name_zh = get_the_title($pid);
            $p_name_en = get_post_meta($pid, 'name_en', true);
            $p_spec    = get_post_meta($pid, 'spec', true);
            $p_slug    = get_post_field('post_name', $pid);
            $p_img     = get_the_post_thumbnail_url($pid, 'medium')
                         ?: content_url('/uploads/products/' . $p_slug . '.jpg');
          ?>
          <a href="<?php echo get_permalink($pid); ?>" class="carousel-card">
            <div class="carousel-card-img" style="background:#FFFFFF;">
              <img src="<?php echo esc_url($p_img); ?>"
                   alt="<?php echo esc_attr($p_name_zh); ?>"
                   loading="lazy"
                   style="max-width:85%;max-height:85%;object-fit:contain;"
                   onerror="this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#ccc;font-size:12px;\'>No Image</div>'">
            </div>
            <div class="carousel-card-body">
              <div class="carousel-card-zh"><?php echo esc_html($p_name_zh); ?></div>
              <?php if ($p_name_en): ?><div class="carousel-card-en"><?php echo esc_html($p_name_en); ?></div><?php endif; ?>
              <?php if ($p_spec): ?><div class="carousel-card-spec"><?php echo esc_html($p_spec); ?></div><?php endif; ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="carousel-btn carousel-next" onclick="ymalMove(1)">&#10095;</button>
    </div>
  </div>
</div>
<script>
var ymalState = {idx: 0, total: <?php echo count($ymal_ids); ?>};
function ymalMove(dir) {
  var track = document.getElementById('ymal-track');
  if (!track || !track.children.length) return;
  var cardW = track.children[0].offsetWidth + 18;
  var visible = Math.floor(track.parentElement.offsetWidth / cardW);
  var max = Math.max(0, ymalState.total - visible);
  ymalState.idx = Math.max(0, Math.min(ymalState.idx + dir, max));
  track.style.transform = 'translateX(-' + (ymalState.idx * cardW) + 'px)';
}
</script>
<?php endif; ?>
<?php } ?>

<!-- 最近瀏覽（純 localStorage，JS 渲染） -->
<?php
  $rv_img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: '';
  $rv_data = array(
    'id'    => (int) get_the_ID(),
    'title' => $name_zh,
    'url'   => get_permalink(),
    'img'   => $rv_img,
    'spec'  => $spec,
    'abv'   => $abv,
    'flag'  => $flag,
  );
?>
<div id="mth-recently-viewed"
     class="recently-viewed-section"
     data-current="<?php echo esc_attr(wp_json_encode($rv_data)); ?>"
     style="display:none;">
  <div class="rv-inner">
    <div class="rv-title"><span class="zh-text">最近瀏覽</span> <small translate="no">Recently Viewed</small></div>
    <div class="rv-track" id="rv-track"></div>
  </div>
</div>

<?php endwhile; ?>
<?php get_footer(); ?>
