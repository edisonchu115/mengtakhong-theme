<?php
// One-time: Korean products import — 讀 wp-content/uploads/products-korea/ + metadata.json
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

/* 確保每個圖片都已注冊為 WP attachment；返回 mapping remote_name => attachment_id */
function mth_korea_ensure_attachments() {
    $map = get_option('mth_korea_attachment_map', array());
    if (!is_array($map)) $map = array();

    $meta = mth_korea_load_metadata();
    if (empty($meta)) return $map;

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    foreach ($meta as $item) {
        $rn = $item['remote_name'];
        if (isset($map[$rn]) && get_post($map[$rn])) continue; // 已注冊

        $src = MTH_KOREA_DIR . '/' . $rn;
        if (!file_exists($src)) continue;

        // 複製到 uploads/{Y}/{m}/ + 注冊
        $upload = wp_upload_dir();
        $target_subdir = $upload['subdir'];
        $target_dir = $upload['path'];
        $title = isset($item['zh']) ? $item['zh'] : $rn;
        $target_name = wp_unique_filename($target_dir, $rn);
        $target_path = $target_dir . '/' . $target_name;

        if (!copy($src, $target_path)) continue;

        $att_id = wp_insert_attachment(array(
            'post_mime_type' => 'image/png',
            'post_title'     => sanitize_text_field($title),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ), $target_path);

        if (is_wp_error($att_id) || !$att_id) continue;

        $att_meta = wp_generate_attachment_metadata($att_id, $target_path);
        wp_update_attachment_metadata($att_id, $att_meta);

        $map[$rn] = (int) $att_id;
    }

    update_option('mth_korea_attachment_map', $map, false);
    return $map;
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
    $attachment_map = mth_korea_ensure_attachments();
    $total_imgs = count($meta);
    $registered = count($attachment_map);
    ?>
    <div class="wrap">
        <h1>🇰🇷 韓國產品批量匯入</h1>
        <p>從 <code>wp-content/uploads/products-korea/</code> 讀 91 隻韓國產品 + metadata.json，自動建立 WP attachment + 產品。</p>

        <?php if (isset($_GET['done'])): ?>
            <div class="notice notice-success">
                <p>✅ 已建立 <strong><?php echo (int) $_GET['done']; ?></strong> 個產品 / 跳過 <strong><?php echo (int) ($_GET['skip'] ?? 0); ?></strong> 個（已存在）</p>
            </div>
        <?php endif; ?>

        <div style="background:#fff;padding:14px 18px;border:1px solid #ccd0d4;border-radius:4px;margin:14px 0;">
            <h3 style="margin:0 0 8px;">📊 統計</h3>
            <ul style="margin:0;">
                <li>Metadata JSON 入面產品數：<strong><?php echo $total_imgs; ?></strong></li>
                <li>已注冊為 WP attachment：<strong><?php echo $registered; ?></strong></li>
            </ul>
        </div>

        <?php if (empty($meta)): ?>
            <div class="notice notice-error"><p>❌ 搵唔到 metadata.json，請確認 <code><?php echo esc_html(MTH_KOREA_DIR); ?></code> 入面有檔案</p></div>
            <?php return; ?>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="mth-korea-form">
            <input type="hidden" name="action" value="mth_apply_korea_import">
            <?php wp_nonce_field('mth_apply_korea_import'); ?>

            <p style="margin:14px 0;">
                <button type="button" class="button" id="mth-k-check-all">☑ 全部勾選</button>
                <button type="button" class="button" id="mth-k-uncheck-all">☐ 取消全部</button>
                <button type="submit" class="button button-primary" style="margin-left:20px;">✅ 套用已勾選</button>
                <span style="margin-left:14px;color:#666;font-size:13px;">改完欄位後撳套用，就會建立產品 + 設定精選圖</span>
            </p>

            <table class="widefat striped" style="margin-top:8px;">
                <thead>
                    <tr style="background:#1C1C1C;color:#D4AF37;">
                        <th style="width:40px;">建立</th>
                        <th style="width:60px;">圖</th>
                        <th style="width:35%;">中文名</th>
                        <th style="width:25%;">英文名</th>
                        <th style="width:90px;">規格</th>
                        <th style="width:70px;">ABV</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($meta as $i => $item):
                    $rn = $item['remote_name'];
                    $zh = $item['zh'];
                    $spec = $item['spec'];
                    $abv = $item['abv'];
                    $att_id = isset($attachment_map[$rn]) ? $attachment_map[$rn] : 0;
                    $thumb_url = $att_id ? wp_get_attachment_thumb_url($att_id) : MTH_KOREA_URL . '/' . $rn;
                    // 判斷是否已存在產品 (按 zh + spec)
                    $existing = get_posts(array(
                        'post_type' => 'mth_product',
                        'title' => $zh,
                        'post_status' => array('publish','draft','pending'),
                        'numberposts' => -1,
                        'fields' => 'ids',
                    ));
                    $dup = false;
                    foreach ($existing as $eid) {
                        if (get_post_meta($eid, 'spec', true) === $spec) { $dup = true; break; }
                    }
                    $bg = $dup ? '#f0f0f0' : '#fff';
                ?>
                <tr style="background:<?php echo $bg; ?>;">
                    <td style="text-align:center;">
                        <?php if ($dup): ?>
                            <span title="已存在" style="color:#999;">已建</span>
                        <?php else: ?>
                            <input type="checkbox" name="items[<?php echo $i; ?>][use]" value="1" class="mth-k-row-use" checked>
                            <input type="hidden" name="items[<?php echo $i; ?>][rn]" value="<?php echo esc_attr($rn); ?>">
                            <input type="hidden" name="items[<?php echo $i; ?>][att]" value="<?php echo (int) $att_id; ?>">
                        <?php endif; ?>
                    </td>
                    <td>
                        <img src="<?php echo esc_url($thumb_url); ?>" style="width:50px;height:50px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:3px;">
                    </td>
                    <td><input type="text" name="items[<?php echo $i; ?>][zh]" value="<?php echo esc_attr($zh); ?>" style="width:100%;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][en]" value="" placeholder="（可選）英文名" style="width:100%;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][spec]" value="<?php echo esc_attr($spec); ?>" style="width:100%;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                    <td><input type="text" name="items[<?php echo $i; ?>][abv]" value="<?php echo esc_attr($abv); ?>" style="width:60px;padding:4px;" <?php echo $dup?'disabled':''; ?>></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:20px;">
                <button type="submit" class="button button-primary button-large">✅ 套用已勾選嘅項目（建立產品 + 設精選圖）</button>
            </p>
        </form>

        <script>
        (function($){
            $('#mth-k-check-all').on('click', function(){ $('.mth-k-row-use').prop('checked', true); });
            $('#mth-k-uncheck-all').on('click', function(){ $('.mth-k-row-use').prop('checked', false); });
        })(jQuery);
        </script>
    </div>
    <?php
}

/* 套用建立產品 */
add_action('admin_post_mth_apply_korea_import', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_korea_import')) wp_die('Bad nonce');

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
        $att_id = isset($item['att']) ? (int) $item['att'] : 0;

        if (!$zh && !$en) { $skipped++; continue; }

        // 避免重複
        $existing = get_posts(array(
            'post_type' => 'mth_product',
            'title' => $zh,
            'post_status' => array('publish','draft','pending'),
            'numberposts' => -1,
            'fields' => 'ids',
        ));
        $dup = false;
        foreach ($existing as $eid) {
            if (get_post_meta($eid, 'spec', true) === $spec) { $dup = true; break; }
        }
        if ($dup) { $skipped++; continue; }

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
        if ($att_id) set_post_thumbnail($post_id, $att_id);

        $created++;
    }

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-korea-import&done=' . $created . '&skip=' . $skipped));
    exit;
});
