<?php
// One-time: Korean products import — 讀 wp-content/uploads/products-korea/ + metadata.json
// 設計：page load 唔做重工，attachment 注冊延遲到匯入 POST 處理
if (!defined('ABSPATH')) exit;

define('MTH_KOREA_DIR', WP_CONTENT_DIR . '/uploads/products-korea');
define('MTH_KOREA_URL', content_url('/uploads/products-korea'));

function mth_korea_load_metadata() {
    $meta_file = MTH_KOREA_DIR . '/metadata.json';
    if (!file_exists($meta_file)) return array();
    $raw = file_get_contents($meta_file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : array();
}

/* 注冊單一張圖為 WP attachment（cached in option map）*/
function mth_korea_register_one($remote_name, $title) {
    $map = get_option('mth_korea_attachment_map', array());
    if (!is_array($map)) $map = array();
    if (isset($map[$remote_name]) && get_post($map[$remote_name])) {
        return (int) $map[$remote_name]; // already registered
    }

    $src = MTH_KOREA_DIR . '/' . $remote_name;
    if (!file_exists($src)) return 0;

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $upload = wp_upload_dir();
    $target_dir = $upload['path'];
    $target_name = wp_unique_filename($target_dir, $remote_name);
    $target_path = $target_dir . '/' . $target_name;
    if (!copy($src, $target_path)) return 0;

    $att_id = wp_insert_attachment(array(
        'post_mime_type' => 'image/png',
        'post_title'     => sanitize_text_field($title ?: $remote_name),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ), $target_path);
    if (is_wp_error($att_id) || !$att_id) return 0;

    $att_meta = wp_generate_attachment_metadata($att_id, $target_path);
    wp_update_attachment_metadata($att_id, $att_meta);

    $map[$remote_name] = (int) $att_id;
    update_option('mth_korea_attachment_map', $map, false);
    return (int) $att_id;
}

/* Admin submenu */
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '韓國產品匯入',
        '🇰🇷 韓國匯入',
        'manage_options',
        'mth-korea-import',
        'mth_render_korea_import'
    );
});

function mth_render_korea_import() {
    if (!current_user_can('manage_options')) return;

    $meta = mth_korea_load_metadata();
    $map = get_option('mth_korea_attachment_map', array());
    if (!is_array($map)) $map = array();
    $total = count($meta);
    $registered = count($map);
    ?>
    <div class="wrap">
        <h1>🇰🇷 韓國產品批量匯入 (91 隻)</h1>
        <p>圖片已 FTP 上載至 <code>wp-content/uploads/products-korea/</code>，metadata 對齊「韓國台灣泰國產品整理.TXT」(72/91 有 EN 名)。</p>

        <?php if (isset($_GET['done'])): ?>
            <div class="notice notice-success">
                <p>✅ 已建立 <strong><?php echo (int) $_GET['done']; ?></strong> 個產品 / 跳過 <strong><?php echo (int) ($_GET['skip'] ?? 0); ?></strong> 個（已存在）</p>
            </div>
        <?php endif; ?>

        <div style="background:#fff;padding:14px 18px;border:1px solid #ccd0d4;border-radius:4px;margin:14px 0;">
            <h3 style="margin:0 0 8px;">📊 統計</h3>
            <ul style="margin:0;">
                <li>Metadata 總數：<strong><?php echo $total; ?></strong></li>
                <li>已注冊為 WP attachment：<strong><?php echo $registered; ?></strong>（其餘喺匯入時即時注冊）</li>
            </ul>
            <p style="margin:8px 0 0;color:#888;font-size:12px;">💡 為避免 timeout，建議分批匯入：例如先勾選頭 30 個套用 → 再勾 30 個 → 最後 31 個。</p>
        </div>

        <?php if (empty($meta)): ?>
            <div class="notice notice-error"><p>❌ 搵唔到 metadata.json</p></div>
            <?php return; ?>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="mth-korea-form">
            <input type="hidden" name="action" value="mth_apply_korea_import">
            <?php wp_nonce_field('mth_apply_korea_import'); ?>

            <p style="margin:14px 0;">
                <button type="button" class="button" id="mth-k-check-all">☑ 全部勾選</button>
                <button type="button" class="button" id="mth-k-uncheck-all">☐ 取消全部</button>
                <button type="button" class="button" id="mth-k-check-30">📦 首 30 個</button>
                <button type="button" class="button" id="mth-k-check-30-60">📦 31-60</button>
                <button type="button" class="button" id="mth-k-check-60-end">📦 61-尾</button>
                <button type="submit" class="button button-primary" style="margin-left:20px;">✅ 套用已勾選</button>
                <span id="mth-k-count" style="margin-left:14px;color:#666;font-size:13px;"></span>
            </p>

            <table class="widefat striped" style="margin-top:8px;">
                <thead>
                    <tr style="background:#1C1C1C;color:#D4AF37;">
                        <th style="width:40px;">建立</th>
                        <th style="width:60px;">圖</th>
                        <th style="width:30%;">中文名</th>
                        <th style="width:25%;">英文名</th>
                        <th style="width:140px;">規格</th>
                        <th style="width:70px;">ABV</th>
                        <th style="width:60px;">來源</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($meta as $i => $item):
                    $rn = $item['remote_name'];
                    $zh = isset($item['zh']) ? $item['zh'] : '';
                    $en = isset($item['en']) ? $item['en'] : '';
                    $spec = isset($item['spec']) ? $item['spec'] : '';
                    $abv = isset($item['abv']) ? $item['abv'] : '';
                    $img_url = MTH_KOREA_URL . '/' . $rn;
                    $matched = !empty($item['matched_txt']);

                    // 重複偵測（lite）
                    $existing = get_posts(array(
                        'post_type' => 'mth_product',
                        'title' => $zh,
                        'post_status' => array('publish','draft','pending'),
                        'numberposts' => 1,
                        'fields' => 'ids',
                    ));
                    $dup = !empty($existing);
                    $bg = $dup ? '#f0f0f0' : ($matched ? '#fff' : '#fffbf0');
                ?>
                <tr style="background:<?php echo $bg; ?>;">
                    <td style="text-align:center;">
                        <?php if ($dup): ?>
                            <span title="已存在" style="color:#999;">已建</span>
                        <?php else: ?>
                            <input type="checkbox" name="items[<?php echo $i; ?>][use]" value="1" class="mth-k-row-use">
                            <input type="hidden" name="items[<?php echo $i; ?>][rn]" value="<?php echo esc_attr($rn); ?>">
                        <?php endif; ?>
                    </td>
                    <td><img src="<?php echo esc_url($img_url); ?>" loading="lazy" style="width:48px;height:48px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:3px;"></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][zh]" value="<?php echo esc_attr($zh); ?>" style="width:100%;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][en]" value="<?php echo esc_attr($en); ?>" style="width:100%;padding:4px;" placeholder="<?php echo $matched?'':'(TXT 無)'; ?>" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][spec]" value="<?php echo esc_attr($spec); ?>" style="width:100%;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][abv]" value="<?php echo esc_attr($abv); ?>" style="width:60px;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td style="font-size:11px;text-align:center;color:<?php echo $matched?'#3B6D11':'#A88200'; ?>;"><?php echo $matched?'TXT':'解析'; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:20px;">
                <button type="submit" class="button button-primary button-large">✅ 套用已勾選（建立產品 + 設精選圖）</button>
            </p>
        </form>

        <script>
        (function($){
            function updateCount() {
                var n = $('.mth-k-row-use:checked').length;
                $('#mth-k-count').text('已勾 ' + n + ' / ' + <?php echo $total; ?>);
            }
            $('#mth-k-check-all').on('click', function(){ $('.mth-k-row-use').prop('checked', true); updateCount(); });
            $('#mth-k-uncheck-all').on('click', function(){ $('.mth-k-row-use').prop('checked', false); updateCount(); });
            $('#mth-k-check-30').on('click', function(){
                $('.mth-k-row-use').prop('checked', false);
                $('.mth-k-row-use').slice(0,30).prop('checked', true); updateCount();
            });
            $('#mth-k-check-30-60').on('click', function(){
                $('.mth-k-row-use').prop('checked', false);
                $('.mth-k-row-use').slice(30,60).prop('checked', true); updateCount();
            });
            $('#mth-k-check-60-end').on('click', function(){
                $('.mth-k-row-use').prop('checked', false);
                $('.mth-k-row-use').slice(60).prop('checked', true); updateCount();
            });
            $(document).on('change', '.mth-k-row-use', updateCount);
            updateCount();
        })(jQuery);
        </script>
    </div>
    <?php
}

/* 套用建立產品 */
add_action('admin_post_mth_apply_korea_import', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_korea_import')) wp_die('Bad nonce');

    @set_time_limit(0);
    @ini_set('memory_limit', '512M');

    $items = (isset($_POST['items']) && is_array($_POST['items'])) ? $_POST['items'] : array();
    $created = 0; $skipped = 0;

    $term = get_term_by('slug', 'korea', 'mth_product_cat');
    $term_id = $term ? (int) $term->term_id : 0;

    foreach ($items as $key => $item) {
        if (empty($item['use'])) continue;

        $zh   = isset($item['zh'])   ? sanitize_text_field(wp_unslash($item['zh']))   : '';
        $en   = isset($item['en'])   ? sanitize_text_field(wp_unslash($item['en']))   : '';
        $spec = isset($item['spec']) ? sanitize_text_field(wp_unslash($item['spec'])) : '';
        $abv  = isset($item['abv'])  ? sanitize_text_field(wp_unslash($item['abv']))  : '';
        $rn   = isset($item['rn'])   ? sanitize_text_field(wp_unslash($item['rn']))   : '';

        if (!$zh && !$en) { $skipped++; continue; }

        // 避免重複
        $existing = get_posts(array(
            'post_type' => 'mth_product',
            'title' => $zh,
            'post_status' => array('publish','draft','pending'),
            'numberposts' => 1,
            'fields' => 'ids',
        ));
        if (!empty($existing)) { $skipped++; continue; }

        $post_id = wp_insert_post(array(
            'post_title' => $zh ?: $en,
            'post_type' => 'mth_product',
            'post_status' => 'publish',
        ));
        if (is_wp_error($post_id) || !$post_id) { $skipped++; continue; }

        if ($en)   update_post_meta($post_id, 'name_en', $en);
        if ($spec) update_post_meta($post_id, 'spec', $spec);
        if ($abv)  update_post_meta($post_id, 'abv', $abv);
        update_post_meta($post_id, 'source', '代理正貨');
        update_post_meta($post_id, 'origin_country', 'korea');

        if ($term_id) wp_set_object_terms($post_id, array($term_id), 'mth_product_cat');

        // 注冊 attachment 並設精選圖
        if ($rn) {
            $att_id = mth_korea_register_one($rn, $zh);
            if ($att_id) set_post_thumbnail($post_id, $att_id);
        }

        $created++;
    }

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-korea-import&done=' . $created . '&skip=' . $skipped));
    exit;
});
