<?php
// Dashboard widget + Quick filter pills above product list
if (!defined('ABSPATH')) exit;

/* ── Dashboard widget：KPI overview ── */
add_action('wp_dashboard_setup', function() {
    if (!current_user_can('manage_options')) return;
    wp_add_dashboard_widget('mth_dash_kpi', '🍷 明德行產品概況', 'mth_render_dashboard_widget');
});

function mth_render_dashboard_widget() {
    $stats = function_exists('mth_audit_stats') ? mth_audit_stats() : array();
    $total = 0; $no_thumb = 0; $no_country = 0; $no_abv = 0;
    foreach ($stats as $r) {
        $total += $r['total'];
        $no_thumb += $r['no_thumb'];
        $no_country += $r['no_country'];
        $no_abv += $r['no_abv'];
    }
    $drafts = wp_count_posts('mth_product');
    $draft_n = isset($drafts->draft) ? (int)$drafts->draft : 0;
    $audit_url = admin_url('edit.php?post_type=mth_product&page=mth-product-audit');
    $list_url  = admin_url('edit.php?post_type=mth_product');
    $pct = function($n,$d) { return $d > 0 ? round($n/$d*100) : 0; };
    ?>
    <style>
      .mth-kpi-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; margin-bottom:12px; }
      .mth-kpi-card { background:#fafaf7; border:1px solid #e8e3d5; border-radius:6px; padding:10px 12px; }
      .mth-kpi-label { font-size:11px; color:#666; letter-spacing:.04em; text-transform:uppercase; }
      .mth-kpi-value { font-size:22px; font-weight:700; color:#1C1C1C; line-height:1.2; margin-top:4px; }
      .mth-kpi-sub { font-size:11px; color:#999; margin-top:2px; }
      .mth-kpi-alert .mth-kpi-value { color:#A32D2D; }
      .mth-kpi-actions a { margin-right:8px; font-size:12px; }
    </style>
    <div class="mth-kpi-grid">
      <div class="mth-kpi-card"><div class="mth-kpi-label">總產品</div>
        <div class="mth-kpi-value"><?php echo $total; ?></div>
        <div class="mth-kpi-sub">已發佈 · 草稿 <?php echo $draft_n; ?></div></div>
      <div class="mth-kpi-card <?php echo $no_thumb>0?'mth-kpi-alert':''; ?>"><div class="mth-kpi-label">缺精選圖</div>
        <div class="mth-kpi-value"><?php echo $no_thumb; ?></div>
        <div class="mth-kpi-sub"><?php echo $pct($no_thumb,$total); ?>%</div></div>
      <div class="mth-kpi-card <?php echo $no_country>0?'mth-kpi-alert':''; ?>"><div class="mth-kpi-label">缺原產國</div>
        <div class="mth-kpi-value"><?php echo $no_country; ?></div>
        <div class="mth-kpi-sub"><?php echo $pct($no_country,$total); ?>%</div></div>
      <div class="mth-kpi-card <?php echo $no_abv>0?'mth-kpi-alert':''; ?>"><div class="mth-kpi-label">缺 ABV</div>
        <div class="mth-kpi-value"><?php echo $no_abv; ?></div>
        <div class="mth-kpi-sub"><?php echo $pct($no_abv,$total); ?>%</div></div>
    </div>
    <div class="mth-kpi-actions">
      <a href="<?php echo esc_url($audit_url); ?>" class="button button-small">📊 詳細診斷</a>
      <a href="<?php echo esc_url($list_url); ?>" class="button button-small">📦 全部產品</a>
      <a href="<?php echo esc_url(admin_url('edit.php?post_type=mth_product&page=mth-csv-tool')); ?>" class="button button-small">📥 CSV 工具</a>
    </div>
    <?php
}

/* ── 產品列表頁頂：Quick filter pills ── */
add_action('admin_notices', function() {
    global $pagenow, $typenow;
    if ($pagenow !== 'edit.php' || $typenow !== 'mth_product') return;

    $base = admin_url('edit.php?post_type=mth_product');
    $pills = array(
        '全部'    => $base,
        '🖼 缺精選圖' => add_query_arg('mth_audit','no_thumb', $base),
        '🌍 缺原產國' => add_query_arg('origin_country','__none__', $base),
        '📝 草稿'   => add_query_arg('post_status','draft', $base),
    );
    $current_url = (is_ssl()?'https':'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    ?>
    <style>
      .mth-pills { margin:14px 0 4px; display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
      .mth-pills-label { font-size:12px; color:#666; margin-right:4px; }
      .mth-pill { display:inline-block; padding:5px 12px; background:#fff; border:1px solid #d4af37; border-radius:14px; font-size:12px; text-decoration:none; color:#1C1C1C; transition:.15s; }
      .mth-pill:hover { background:#fff8e6; }
      .mth-pill.is-active { background:#1C1C1C; color:#D4AF37; border-color:#1C1C1C; }
    </style>
    <div class="mth-pills">
      <span class="mth-pills-label">快捷篩選：</span>
      <?php foreach ($pills as $label => $url):
          $is_active = ($label === '全部')
              ? (empty($_GET['mth_audit']) && empty($_GET['origin_country']) && empty($_GET['post_status']))
              : (parse_url($url, PHP_URL_QUERY) && strpos($current_url, parse_url($url, PHP_URL_QUERY)) !== false);
      ?>
        <a href="<?php echo esc_url($url); ?>" class="mth-pill<?php echo $is_active?' is-active':''; ?>"><?php echo esc_html($label); ?></a>
      <?php endforeach; ?>
    </div>
    <?php
});
