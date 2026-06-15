<?php
// Activity log — 記錄產品改動 (last 200 entries in wp_options)
if (!defined('ABSPATH')) exit;

define('MTH_LOG_KEY', 'mth_activity_log');
define('MTH_LOG_MAX', 200);

function mth_log_add($action, $post_id, $extra = '') {
    $log = get_option(MTH_LOG_KEY, array());
    if (!is_array($log)) $log = array();
    $user = wp_get_current_user();
    $log[] = array(
        't'      => current_time('mysql'),
        'user'   => $user && $user->ID ? $user->user_login : 'system',
        'action' => $action,
        'pid'    => $post_id,
        'title'  => $post_id ? get_the_title($post_id) : '',
        'extra'  => $extra,
    );
    if (count($log) > MTH_LOG_MAX) $log = array_slice($log, -MTH_LOG_MAX);
    update_option(MTH_LOG_KEY, $log, false);
}

/* 記錄 save / delete */
add_action('save_post_mth_product', function($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post->post_status === 'auto-draft' || $post->post_status === 'trash') return;
    $action = $update ? 'update' : 'create';
    // Detect bulk/quick edit
    if (isset($_POST['mth_bulk_edit_nonce']))  $action = 'bulk_edit';
    elseif (isset($_POST['mth_quick_edit_nonce'])) $action = 'quick_edit';
    mth_log_add($action, $post_id);
}, 50, 3);

add_action('before_delete_post', function($post_id) {
    if (get_post_type($post_id) !== 'mth_product') return;
    mth_log_add('delete', $post_id);
});

add_action('wp_trash_post', function($post_id) {
    if (get_post_type($post_id) !== 'mth_product') return;
    mth_log_add('trash', $post_id);
});

/* Admin submenu */
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '活動紀錄', '📋 活動紀錄',
        'manage_options', 'mth-activity-log', 'mth_render_activity_log'
    );
});

function mth_render_activity_log() {
    if (!current_user_can('manage_options')) return;
    if (isset($_GET['clear']) && check_admin_referer('mth_clear_log')) {
        delete_option(MTH_LOG_KEY);
        echo '<div class="notice notice-success"><p>✅ 已清空</p></div>';
    }
    $log = get_option(MTH_LOG_KEY, array());
    if (!is_array($log)) $log = array();
    $log = array_reverse($log); // newest first
    $labels = array(
        'create'=>'➕ 新增','update'=>'✏️ 編輯','quick_edit'=>'⚡ Quick Edit',
        'bulk_edit'=>'📦 Bulk Edit','trash'=>'🗑 移到垃圾筒','delete'=>'❌ 永久刪除');
    $clear_url = wp_nonce_url(add_query_arg('clear','1'), 'mth_clear_log');
    ?>
    <div class="wrap">
        <h1>產品活動紀錄</h1>
        <p>顯示最近 <?php echo MTH_LOG_MAX; ?> 條改動記錄。
        <a href="<?php echo esc_url($clear_url); ?>" class="button button-small" onclick="return confirm('確定清空？');">清空</a></p>
        <?php if (empty($log)): ?>
            <p>暫無紀錄</p>
        <?php else: ?>
        <table class="widefat striped">
            <thead><tr><th style="width:140px;">時間</th><th style="width:100px;">用戶</th><th style="width:120px;">動作</th><th>產品</th></tr></thead>
            <tbody>
            <?php foreach ($log as $e):
                $edit_link = $e['pid'] ? get_edit_post_link($e['pid']) : '';
                $title = $e['title'] ?: ('#' . $e['pid']);
            ?>
            <tr>
                <td><small><?php echo esc_html($e['t']); ?></small></td>
                <td><?php echo esc_html($e['user']); ?></td>
                <td><?php echo esc_html(isset($labels[$e['action']]) ? $labels[$e['action']] : $e['action']); ?></td>
                <td><?php if ($edit_link): ?><a href="<?php echo esc_url($edit_link); ?>"><?php echo esc_html($title); ?></a><?php else: ?><?php echo esc_html($title); ?> <small>(已刪)</small><?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php
}
