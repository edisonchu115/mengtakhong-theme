<?php
// Product audit + CSV export of missing-data products
if (!defined('ABSPATH')) exit;

function mth_audit_stats_compute() {
    $cats = get_terms(array('taxonomy' => 'mth_product_cat', 'hide_empty' => false));
    if (is_wp_error($cats) || empty($cats)) return array();
    $out = array();
    foreach ($cats as $cat) {
        $ids = get_posts(array(
            'post_type'      => 'mth_product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
            'tax_query'      => array(array('taxonomy' => 'mth_product_cat', 'field' => 'term_id', 'terms' => $cat->term_id)),
        ));
        $row = array('name'=>$cat->name, 'slug'=>$cat->slug,
            'total'=>count($ids), 'has_thumb'=>0, 'no_thumb'=>0,
            'no_zh'=>0, 'no_en'=>0, 'no_spec'=>0, 'no_country'=>0, 'no_abv'=>0);
        foreach ($ids as $pid) {
            if (get_post_thumbnail_id($pid)) $row['has_thumb']++; else $row['no_thumb']++;
            $t = get_the_title($pid);
            if (!$t || $t === '自動草稿') $row['no_zh']++;
            if (!get_post_meta($pid,'name_en',true))        $row['no_en']++;
            if (!get_post_meta($pid,'spec',true))           $row['no_spec']++;
            if (!get_post_meta($pid,'origin_country',true)) $row['no_country']++;
            if (!get_post_meta($pid,'abv',true))            $row['no_abv']++;
        }
        $out[] = $row;
    }
    return $out;
}

function mth_audit_stats() {
    $cached = get_transient('mth_audit_stats_v1');
    if ($cached !== false) return $cached;
    $data = mth_audit_stats_compute();
    set_transient('mth_audit_stats_v1', $data, 5 * MINUTE_IN_SECONDS);
    return $data;
}

// 任何產品儲存都失效 cache
add_action('save_post_mth_product', function(){ delete_transient('mth_audit_stats_v1'); });
add_action('deleted_post', function(){ delete_transient('mth_audit_stats_v1'); });

function mth_render_product_audit() {
    if (!current_user_can('manage_options')) return;
    if (isset($_GET['clear_cache'])) {
        delete_transient('mth_audit_stats_v1');
        echo '<div class="notice notice-success"><p>✅ Cache 已清除，重新計算中…</p></div>';
    }
    $stats = mth_audit_stats();
    $export_base = admin_url('admin-post.php?action=mth_export_missing');
    ?>
    <div class="wrap">
        <h1>產品診斷</h1>
        <p>列出每個分類嘅產品狀態。資料 cache 5 分鐘（每次儲存產品會自動失效）。
        <a href="<?php echo esc_url(add_query_arg('clear_cache','1')); ?>" class="button button-small">🔄 強制重新計算</a></p>

        <?php if (empty($stats)): ?>
            <p>未有分類</p>
        <?php else: ?>
        <table class="widefat striped" style="margin-top:18px;">
            <thead><tr><th>分類</th><th>總數</th><th>有圖</th><th>缺圖</th><th>缺中文</th><th>缺英文</th><th>缺規格</th><th>缺國旗</th><th>缺 ABV</th><th>操作</th></tr></thead>
            <tbody>
            <?php foreach ($stats as $r):
                $url_no_thumb = admin_url('edit.php?post_type=mth_product&mth_audit=no_thumb&cat=' . $r['slug']);
            ?>
            <tr>
                <td><strong><?php echo esc_html($r['name']); ?></strong><br><small><?php echo esc_html($r['slug']); ?></small></td>
                <td><?php echo $r['total']; ?></td>
                <td style="color:#3B6D11"><?php echo $r['has_thumb']; ?></td>
                <td style="color:<?php echo $r['no_thumb']>0?'#A32D2D':'#999'; ?>"><?php echo $r['no_thumb']; ?></td>
                <td><?php echo $r['no_zh']; ?></td>
                <td><?php echo $r['no_en']; ?></td>
                <td><?php echo $r['no_spec']; ?></td>
                <td style="color:<?php echo $r['no_country']>0?'#A32D2D':'#999'; ?>"><?php echo $r['no_country']; ?></td>
                <td style="color:<?php echo $r['no_abv']>0?'#A32D2D':'#999'; ?>"><?php echo $r['no_abv']; ?></td>
                <td><a href="<?php echo esc_url($url_no_thumb); ?>" class="button button-small">睇缺圖</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <h2 style="margin-top:30px;">📥 缺資料 CSV 匯出</h2>
        <p>下載指定類型嘅問題產品 CSV，補完喺 Excel 再用「CSV 工具」匯入更新。</p>
        <p>
            <a href="<?php echo esc_url(wp_nonce_url($export_base . '&type=no_thumb', 'mth_export_missing')); ?>" class="button">缺精選圖</a>
            <a href="<?php echo esc_url(wp_nonce_url($export_base . '&type=no_country', 'mth_export_missing')); ?>" class="button">缺原產國</a>
            <a href="<?php echo esc_url(wp_nonce_url($export_base . '&type=no_abv', 'mth_export_missing')); ?>" class="button">缺 ABV</a>
            <a href="<?php echo esc_url(wp_nonce_url($export_base . '&type=no_spec', 'mth_export_missing')); ?>" class="button">缺規格</a>
            <a href="<?php echo esc_url(wp_nonce_url($export_base . '&type=no_en', 'mth_export_missing')); ?>" class="button">缺英文名</a>
        </p>

        <h2 style="margin-top:40px;">一次性：威士忌國旗批量套用</h2>
        <p>嚴謹研究 38 隻威士忌，35 隻有官方來源確認：</p>
        <ul style="list-style:disc;margin-left:24px;font-size:13px;">
            <li>🏴󠁧󠁢󠁳󠁣󠁴󠁿 蘇格蘭：31 隻 / 🇨🇦 加拿大：2 隻 / 🇺🇸 美國：1 隻 / 🇹🇭 泰國：1 隻</li>
            <li>⚠️ 未確認（不標）：ID 310、325、879</li>
        </ul>
        <?php
        $applied = get_option('mth_whisky_flags_applied');
        if (!empty($_GET['flags_done'])) echo '<div class="notice notice-success"><p>✅ 已套用 ' . (int) $_GET['flags_done'] . ' 隻</p></div>';
        ?>
        <p>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=mth_apply_whisky_flags'), 'mth_apply_whisky_flags')); ?>"
               class="button button-primary"
               onclick="return confirm('確認套用？會覆蓋現有原產國設定。');">🎯 套用威士忌國旗</a>
            <?php if ($applied): ?><span style="margin-left:12px;color:#666;font-size:13px;">上次：<?php echo esc_html($applied); ?></span><?php endif; ?>
        </p>
    </div>
    <?php
}

/* ── CSV 匯出：缺某類資料嘅產品 ── */
add_action('admin_post_mth_export_missing', function() {
    if (!current_user_can('manage_options')) wp_die('Forbidden');
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mth_export_missing')) wp_die('Invalid nonce');
    $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
    $allowed = array('no_thumb','no_country','no_abv','no_spec','no_en','no_zh');
    if (!in_array($type, $allowed, true)) wp_die('Invalid type');

    $args = array('post_type'=>'mth_product','posts_per_page'=>-1,'post_status'=>'publish','fields'=>'ids');
    $ids = get_posts($args);
    $rows = array();
    foreach ($ids as $pid) {
        $hit = false;
        switch ($type) {
            case 'no_thumb':   $hit = !get_post_thumbnail_id($pid); break;
            case 'no_country': $hit = !get_post_meta($pid,'origin_country',true); break;
            case 'no_abv':     $hit = !get_post_meta($pid,'abv',true); break;
            case 'no_spec':    $hit = !get_post_meta($pid,'spec',true); break;
            case 'no_en':      $hit = !get_post_meta($pid,'name_en',true); break;
            case 'no_zh':      $t = get_the_title($pid); $hit = (!$t || $t === '自動草稿'); break;
        }
        if (!$hit) continue;
        $terms = wp_get_post_terms($pid, 'mth_product_cat', array('fields'=>'slugs'));
        $rows[] = array(
            'id'             => $pid,
            'title'          => get_the_title($pid),
            'name_en'        => get_post_meta($pid,'name_en',true),
            'spec'           => get_post_meta($pid,'spec',true),
            'abv'            => get_post_meta($pid,'abv',true),
            'source'         => get_post_meta($pid,'source',true),
            'origin_country' => get_post_meta($pid,'origin_country',true),
            'category_slug'  => is_array($terms) && !empty($terms) ? $terms[0] : '',
        );
    }

    $filename = 'mth-missing-' . $type . '-' . date('Ymd-His') . '.csv';
    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
    fputcsv($out, array('id','title','name_en','spec','abv','source','origin_country','category_slug'));
    foreach ($rows as $r) fputcsv($out, $r);
    fclose($out);
    exit;
});
