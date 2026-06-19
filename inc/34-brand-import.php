<?php
// 品牌 Logo 批量匯入（mth_brand CPT：標題 + 精選圖）
// 圖已統一處理 + 上載至 uploads/brand-logos/ + metadata.json
if (!defined('ABSPATH')) exit;

define('MTH_BRAND_DIR', WP_CONTENT_DIR . '/uploads/brand-logos');
define('MTH_BRAND_URL', content_url('/uploads/brand-logos'));

function mth_brand_meta() {
    $f = MTH_BRAND_DIR . '/metadata.json';
    if (!file_exists($f)) return array();
    $d = json_decode(file_get_contents($f), true);
    return is_array($d) ? $d : array();
}

/* 檢查同名品牌（只計 publish/draft/pending，唔計回收筒）*/
function mth_brand_exists($name) {
    $ids = get_posts(array('post_type'=>'mth_brand','title'=>$name,
        'post_status'=>array('publish','draft','pending'),'numberposts'=>1,'fields'=>'ids'));
    return !empty($ids);
}

function mth_brand_register_one($remote_name, $title) {
    $map = get_option('mth_brand_att_map', array());
    if (!is_array($map)) $map = array();
    if (isset($map[$remote_name]) && get_post($map[$remote_name])) return (int) $map[$remote_name];
    $src = MTH_BRAND_DIR . '/' . $remote_name;
    if (!file_exists($src)) return 0;
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $up = wp_upload_dir();
    $tname = wp_unique_filename($up['path'], $remote_name);
    $tpath = $up['path'] . '/' . $tname;
    if (!copy($src, $tpath)) return 0;
    $att = wp_insert_attachment(array('post_mime_type'=>'image/png','post_title'=>sanitize_text_field($title),'post_status'=>'inherit'), $tpath);
    if (is_wp_error($att) || !$att) return 0;
    wp_update_attachment_metadata($att, wp_generate_attachment_metadata($att, $tpath));
    $map[$remote_name] = (int) $att;
    update_option('mth_brand_att_map', $map, false);
    return (int) $att;
}

add_action('admin_menu', function() {
    add_submenu_page('edit.php?post_type=mth_brand', 'Logo 匯入', '📥 Logo 匯入',
        'manage_options', 'mth-brand-import', 'mth_render_brand_import');
});

function mth_render_brand_import() {
    if (!current_user_can('manage_options')) return;
    $meta = mth_brand_meta();
    $existing = (int) wp_count_posts('mth_brand')->publish;
    ?>
    <div class="wrap">
        <h1>📥 品牌 Logo 匯入</h1>
        <p>Logo 已統一尺寸 + 上載。先「清除現有品牌」再匯入全新一批。</p>

        <?php if (isset($_GET['done'])): ?>
            <div class="notice notice-success"><p>✅ 已建立 <strong><?php echo (int) $_GET['done']; ?></strong> 個品牌 / 跳過 <strong><?php echo (int)($_GET['skip']??0); ?></strong></p></div>
        <?php endif; ?>
        <?php if (isset($_GET['cleared'])): ?>
            <div class="notice notice-warning"><p>🗑 已清除 <strong><?php echo (int) $_GET['cleared']; ?></strong> 個現有品牌（移至回收筒）</p></div>
        <?php endif; ?>

        <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:14px 18px;margin:14px 0;">
            <strong>現有品牌（已發佈）：<?php echo $existing; ?> 個</strong>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline;margin-left:16px;"
                  onsubmit="return confirm('確定將全部現有品牌移去回收筒？');">
                <input type="hidden" name="action" value="mth_clear_brands">
                <?php wp_nonce_field('mth_clear_brands'); ?>
                <button class="button" style="color:#b32d2e;">🗑 清除全部現有品牌</button>
            </form>
        </div>

        <?php if (empty($meta)): ?>
            <div class="notice notice-error"><p>❌ 搵唔到 brand-logos/metadata.json</p></div>
            <?php return; ?>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="mth_apply_brand_import">
            <?php wp_nonce_field('mth_apply_brand_import'); ?>
            <p>
                <button type="button" class="button" id="ba">☑ 全部</button>
                <button type="button" class="button" id="bn">☐ 清除</button>
                <button type="submit" class="button button-primary" style="margin-left:14px;">✅ 套用建立品牌</button>
            </p>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            <?php foreach ($meta as $i => $it):
                $name = $it['name'] ?? ''; $rn = $it['remote_name'] ?? '';
                $dup = mth_brand_exists($name); ?>
                <div style="border:1px solid #ddd;border-radius:6px;padding:10px;background:#fff;">
                    <div style="height:90px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #f0f0f0;border-radius:4px;">
                        <img src="<?php echo esc_url(MTH_BRAND_URL . '/' . $rn); ?>" loading="lazy" style="max-width:100%;max-height:100%;object-fit:contain;">
                    </div>
                    <label style="display:flex;align-items:center;gap:6px;margin-top:8px;">
                        <input type="checkbox" name="items[<?php echo $i; ?>][use]" value="1" class="brow" <?php echo $dup?'':'checked'; ?>>
                        <input type="hidden" name="items[<?php echo $i; ?>][rn]" value="<?php echo esc_attr($rn); ?>">
                        <input type="text" name="items[<?php echo $i; ?>][name]" value="<?php echo esc_attr($name); ?>" style="width:100%;padding:3px;">
                    </label>
                    <?php if ($dup): ?><span style="font-size:11px;color:#999;">已有同名</span><?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
            <p style="margin-top:18px;"><button type="submit" class="button button-primary button-large">✅ 套用建立品牌</button></p>
        </form>
        <script>
        (function($){
            $('#ba').click(function(){ $('.brow').prop('checked',true); });
            $('#bn').click(function(){ $('.brow').prop('checked',false); });
        })(jQuery);
        </script>
    </div>
    <?php
}

/* 清除全部現有品牌（移回收筒） */
add_action('admin_post_mth_clear_brands', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_clear_brands')) wp_die('Bad nonce');
    $ids = get_posts(array('post_type'=>'mth_brand','post_status'=>array('publish','draft','pending'),'numberposts'=>-1,'fields'=>'ids'));
    $n = 0;
    foreach ($ids as $id) { if (wp_trash_post($id)) $n++; }
    do_action('litespeed_purge_all');
    wp_redirect(admin_url('edit.php?post_type=mth_brand&page=mth-brand-import&cleared=' . $n));
    exit;
});

/* 建立品牌 */
add_action('admin_post_mth_apply_brand_import', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_brand_import')) wp_die('Bad nonce');
    @set_time_limit(0); @ini_set('memory_limit', '512M');
    $items = (isset($_POST['items']) && is_array($_POST['items'])) ? $_POST['items'] : array();
    $created = 0; $skipped = 0;
    foreach ($items as $row) {
        if (empty($row['use'])) continue;
        $name = isset($row['name']) ? sanitize_text_field(wp_unslash($row['name'])) : '';
        $rn   = isset($row['rn'])   ? sanitize_text_field(wp_unslash($row['rn']))   : '';
        if (!$name) { $skipped++; continue; }
        if (mth_brand_exists($name)) { $skipped++; continue; }
        $pid = wp_insert_post(array('post_title'=>$name,'post_type'=>'mth_brand','post_status'=>'publish'));
        if (is_wp_error($pid) || !$pid) { $skipped++; continue; }
        if ($rn) { $att = mth_brand_register_one($rn, $name); if ($att) set_post_thumbnail($pid, $att); }
        $created++;
    }
    do_action('litespeed_purge_all');
    wp_redirect(admin_url('edit.php?post_type=mth_brand&page=mth-brand-import&done=' . $created . '&skip=' . $skipped));
    exit;
});
