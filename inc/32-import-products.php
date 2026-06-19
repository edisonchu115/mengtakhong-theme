<?php
// 進口產品批量匯入（日本 / 汽水啤酒 / 烈酒力嬌）
// 圖已壓縮 + FTP 上載至 uploads/products-import-<batch>/ + metadata.json
// 設計同 inc/30 一樣：page load 唔做重工，attachment 延遲到匯入時注冊。
if (!defined('ABSPATH')) exit;

function mth_imp_batches() {
    return array(
        'japan'  => array('label' => '🇯🇵 日本進口',  'dir' => 'products-import-japan'),
        'beer'   => array('label' => '🥤 汽水啤酒',    'dir' => 'products-import-beer'),
        'liquor' => array('label' => '🥃 烈酒力嬌',    'dir' => 'products-import-liquor'),
        'whisky' => array('label' => '🥃 進口威士忌',  'dir' => 'products-import-whisky'),
        'cognac' => array('label' => '🍇 拔蘭地干邑',  'dir' => 'products-import-cognac'),
        'wine'   => array('label' => '🍷 葡萄酒香檳',  'dir' => 'products-import-wine'),
        'agencywine' => array('label' => '🍷 代理葡萄酒', 'dir' => 'products-import-agencywine'),
    );
}

function mth_imp_meta($batch) {
    $b = mth_imp_batches();
    if (!isset($b[$batch])) return array();
    $file = WP_CONTENT_DIR . '/uploads/' . $b[$batch]['dir'] . '/metadata.json';
    if (!file_exists($file)) return array();
    $d = json_decode(file_get_contents($file), true);
    return is_array($d) ? $d : array();
}

function mth_imp_url($batch, $name) {
    $b = mth_imp_batches();
    return content_url('/uploads/' . $b[$batch]['dir'] . '/' . $name);
}

/* 用 中文名 + 規格 搵現有產品（同名唔同容量 = 唔同產品）*/
function mth_imp_find($zh, $spec) {
    if ($zh === '') return 0;
    $ids = get_posts(array('post_type'=>'mth_product','title'=>$zh,
        'post_status'=>array('publish','draft','pending','future'),'numberposts'=>-1,'fields'=>'ids'));
    foreach ($ids as $pid) {
        if ((string) get_post_meta($pid, 'spec', true) === (string) $spec) return (int) $pid;
    }
    return 0;
}

/* 注冊單一張壓縮圖為 attachment（cached map）*/
function mth_imp_register_one($batch, $remote_name, $title) {
    $opt = 'mth_imp_att_map';
    $map = get_option($opt, array());
    if (!is_array($map)) $map = array();
    $key = $batch . '/' . $remote_name;
    if (isset($map[$key]) && get_post($map[$key])) return (int) $map[$key];

    $b = mth_imp_batches();
    $src = WP_CONTENT_DIR . '/uploads/' . $b[$batch]['dir'] . '/' . $remote_name;
    if (!file_exists($src)) return 0;

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $upload = wp_upload_dir();
    $target_name = wp_unique_filename($upload['path'], $remote_name);
    $target_path = $upload['path'] . '/' . $target_name;
    if (!copy($src, $target_path)) return 0;

    $att_id = wp_insert_attachment(array(
        'post_mime_type' => 'image/png',
        'post_title'     => sanitize_text_field($title ?: $remote_name),
        'post_status'    => 'inherit',
    ), $target_path);
    if (is_wp_error($att_id) || !$att_id) return 0;

    wp_update_attachment_metadata($att_id, wp_generate_attachment_metadata($att_id, $target_path));
    $map[$key] = (int) $att_id;
    update_option($opt, $map, false);
    return (int) $att_id;
}

/* Admin submenu */
add_action('admin_menu', function() {
    add_submenu_page('edit.php?post_type=mth_product', '進口產品匯入', '📦 進口匯入',
        'manage_options', 'mth-import', 'mth_render_import');
});

function mth_render_import() {
    if (!current_user_can('manage_options')) return;
    $batches = mth_imp_batches();
    $cur = isset($_GET['batch']) && isset($batches[$_GET['batch']]) ? $_GET['batch'] : 'japan';
    $meta = mth_imp_meta($cur);
    ?>
    <div class="wrap">
        <h1>📦 進口產品批量匯入</h1>
        <p>圖已壓縮 + 上載。揀批次 → 核對國旗/資料 → 勾選 → 套用。<strong>建議分批（每次 ~30 個）避免 timeout。</strong></p>

        <?php if (isset($_GET['done'])): ?>
            <div class="notice notice-success"><p>✅ 新增 <strong><?php echo (int) $_GET['done']; ?></strong> 個 / 更新 <strong><?php echo (int) ($_GET['upd'] ?? 0); ?></strong> 個 / 跳過 <strong><?php echo (int) ($_GET['skip'] ?? 0); ?></strong> 個</p></div>
        <?php endif; ?>

        <h2 class="nav-tab-wrapper">
        <?php foreach ($batches as $k => $b):
            $c = count(mth_imp_meta($k)); ?>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=mth_product&page=mth-import&batch=' . $k)); ?>"
               class="nav-tab <?php echo $cur === $k ? 'nav-tab-active' : ''; ?>"><?php echo esc_html($b['label']); ?> (<?php echo $c; ?>)</a>
        <?php endforeach; ?>
        </h2>

        <?php if (empty($meta)): ?>
            <div class="notice notice-error"><p>❌ 搵唔到 <?php echo esc_html($cur); ?> metadata.json</p></div>
            <?php return; ?>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="mth_apply_import">
            <input type="hidden" name="batch" value="<?php echo esc_attr($cur); ?>">
            <?php wp_nonce_field('mth_apply_import'); ?>

            <p style="margin:14px 0;">
                <button type="button" class="button" id="imp-all">☑ 全部</button>
                <button type="button" class="button" id="imp-none">☐ 清除</button>
                <button type="button" class="button" id="imp-30">📦 首 30</button>
                <button type="button" class="button" id="imp-30-60">📦 31-60</button>
                <button type="button" class="button" id="imp-60">📦 61-尾</button>
                <button type="submit" class="button button-primary" style="margin-left:18px;">✅ 套用已勾選</button>
                <span id="imp-count" style="margin-left:12px;color:#666;"></span>
            </p>

            <table class="widefat striped">
                <thead><tr style="background:#1C1C1C;color:#D4AF37;">
                    <th style="width:36px;">建</th><th style="width:54px;">圖</th>
                    <th style="width:26%;">中文名</th><th style="width:24%;">英文名</th>
                    <th style="width:130px;">規格</th><th style="width:120px;">國旗</th><th style="width:54px;">ABV</th>
                </tr></thead>
                <tbody>
                <?php foreach ($meta as $i => $it):
                    $zh = $it['zh'] ?? ''; $en = $it['en'] ?? ''; $spec = $it['spec'] ?? '';
                    $country = $it['country'] ?? ''; $rn = $it['remote_name'] ?? '';
                    $flag = $country ? mth_country_flag($country) : '';
                    $cname = $country ? mth_country_name($country) : '';
                    $found = mth_imp_find($zh, $spec);
                ?>
                <tr style="background:<?php echo $found ? '#eef6ff' : '#fff'; ?>;">
                    <td style="text-align:center;">
                        <input type="checkbox" name="items[<?php echo $i; ?>][use]" value="1" class="imp-row" checked>
                        <input type="hidden" name="items[<?php echo $i; ?>][i]" value="<?php echo $i; ?>">
                        <div style="font-size:10px;margin-top:2px;color:<?php echo $found ? '#1d6fb8' : '#3B6D11'; ?>;">
                            <?php echo $found ? '已建·更新' : '新增'; ?>
                        </div>
                    </td>
                    <td><img src="<?php echo esc_url(mth_imp_url($cur, $rn)); ?>" loading="lazy" style="width:44px;height:44px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:3px;"></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][zh]" value="<?php echo esc_attr($zh); ?>" style="width:100%;"></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][en]" value="<?php echo esc_attr($en); ?>" style="width:100%;"></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][spec]" value="<?php echo esc_attr($spec); ?>" style="width:100%;"></td>
                    <td style="text-align:center;font-size:18px;" title="<?php echo esc_attr($cname); ?>">
                        <?php echo $flag ? $flag . ' <span style="font-size:11px;color:#666;">' . esc_html($cname) . '</span>' : '<span style="color:#bbb;font-size:11px;">無</span>'; ?>
                    </td>
                    <td><input type="text" name="items[<?php echo $i; ?>][abv]" value="<?php echo esc_attr($it['abv'] ?? ''); ?>" style="width:50px;"></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top:18px;"><button type="submit" class="button button-primary button-large">✅ 套用已勾選</button></p>
        </form>

        <script>
        (function($){
            var total = <?php echo count($meta); ?>;
            function cnt(){ $('#imp-count').text('已勾 ' + $('.imp-row:checked').length + ' / ' + total); }
            $('#imp-all').click(function(){ $('.imp-row').prop('checked',true); cnt(); });
            $('#imp-none').click(function(){ $('.imp-row').prop('checked',false); cnt(); });
            $('#imp-30').click(function(){ $('.imp-row').prop('checked',false).slice(0,30).prop('checked',true); cnt(); });
            $('#imp-30-60').click(function(){ $('.imp-row').prop('checked',false).slice(30,60).prop('checked',true); cnt(); });
            $('#imp-60').click(function(){ $('.imp-row').prop('checked',false).slice(60).prop('checked',true); cnt(); });
            $(document).on('change','.imp-row',cnt); cnt();
        })(jQuery);
        </script>
    </div>
    <?php
}

/* 套用建立 */
add_action('admin_post_mth_apply_import', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_import')) wp_die('Bad nonce');
    @set_time_limit(0); @ini_set('memory_limit', '512M');

    $batch = isset($_POST['batch']) ? sanitize_key($_POST['batch']) : '';
    $batches = mth_imp_batches();
    if (!isset($batches[$batch])) wp_die('Bad batch');
    $meta = mth_imp_meta($batch);

    $items = (isset($_POST['items']) && is_array($_POST['items'])) ? $_POST['items'] : array();
    $created = 0; $updated = 0; $skipped = 0;

    foreach ($items as $row) {
        if (empty($row['use'])) continue;
        $idx = isset($row['i']) ? (int) $row['i'] : -1;
        if (!isset($meta[$idx])) { $skipped++; continue; }
        $src = $meta[$idx];

        $zh   = isset($row['zh'])   ? sanitize_text_field(wp_unslash($row['zh']))   : ($src['zh'] ?? '');
        $en   = isset($row['en'])   ? sanitize_text_field(wp_unslash($row['en']))   : ($src['en'] ?? '');
        $spec = isset($row['spec']) ? sanitize_text_field(wp_unslash($row['spec'])) : ($src['spec'] ?? '');
        $abv  = isset($row['abv'])  ? sanitize_text_field(wp_unslash($row['abv']))  : '';
        $country = $src['country'] ?? '';
        $cat     = $src['cat'] ?? '';
        $source  = $src['source'] ?? '進口';
        $rn      = $src['remote_name'] ?? '';
        if (!$zh && !$en) { $skipped++; continue; }

        // 中文名+規格 識別現有產品
        $pid = mth_imp_find($zh, $spec);

        if ($pid) {
            // 更新：補 ABV / 英文名 / 國家（唔郁圖片）
            if ($abv !== '')   update_post_meta($pid, 'abv', $abv);
            if ($en)           update_post_meta($pid, 'name_en', $en);
            if ($spec)         update_post_meta($pid, 'spec', $spec);
            if ($country)      update_post_meta($pid, 'origin_country', $country);
            $updated++;
            continue;
        }

        // 新增
        $pid = wp_insert_post(array('post_title'=>$zh ?: $en,'post_type'=>'mth_product','post_status'=>'publish'));
        if (is_wp_error($pid) || !$pid) { $skipped++; continue; }

        if ($en)      update_post_meta($pid, 'name_en', $en);
        if ($spec)    update_post_meta($pid, 'spec', $spec);
        if ($abv !== '') update_post_meta($pid, 'abv', $abv);
        if ($country) update_post_meta($pid, 'origin_country', $country);
        update_post_meta($pid, 'source', $source);

        if ($cat) {
            $term = get_term_by('slug', $cat, 'mth_product_cat');
            if ($term) wp_set_object_terms($pid, array((int) $term->term_id), 'mth_product_cat');
        }
        if ($rn) {
            $att = mth_imp_register_one($batch, $rn, $zh);
            if ($att) set_post_thumbnail($pid, $att);
        }
        $created++;
    }

    // 純 meta 更新唔會 trigger save_post，手動 bust 分類頁快取
    if (function_exists('mth_bump_cat_cache_ver')) mth_bump_cat_cache_ver();
    if (function_exists('do_action')) do_action('litespeed_purge_all');

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-import&batch=' . $batch . '&done=' . $created . '&upd=' . $updated . '&skip=' . $skipped));
    exit;
});
