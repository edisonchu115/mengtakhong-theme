<?php
// Weekly email digest — 每週缺資料報告
if (!defined('ABSPATH')) exit;

define('MTH_DIGEST_OPT', 'mth_email_digest_settings');

/* Settings submenu */
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '每週報告設定', '📧 每週報告',
        'manage_options', 'mth-email-digest', 'mth_render_digest_settings'
    );
});

function mth_digest_get_settings() {
    $s = get_option(MTH_DIGEST_OPT, array());
    return wp_parse_args(is_array($s) ? $s : array(), array(
        'enabled' => 0,
        'email'   => get_option('admin_email'),
    ));
}

function mth_render_digest_settings() {
    if (!current_user_can('manage_options')) return;
    if (isset($_POST['mth_digest_save']) && check_admin_referer('mth_digest_save')) {
        $new = array(
            'enabled' => !empty($_POST['enabled']) ? 1 : 0,
            'email'   => sanitize_email($_POST['email']),
        );
        update_option(MTH_DIGEST_OPT, $new);
        // (un)schedule
        if ($new['enabled'] && !wp_next_scheduled('mth_send_weekly_digest')) {
            wp_schedule_event(strtotime('next monday 09:00'), 'weekly', 'mth_send_weekly_digest');
        } elseif (!$new['enabled']) {
            wp_clear_scheduled_hook('mth_send_weekly_digest');
        }
        echo '<div class="notice notice-success"><p>✅ 已儲存</p></div>';
    }
    if (isset($_POST['mth_digest_test']) && check_admin_referer('mth_digest_save')) {
        $sent = mth_send_weekly_digest();
        echo '<div class="notice ' . ($sent ? 'notice-success' : 'notice-error') . '"><p>'
            . ($sent ? '✅ 測試郵件已寄出' : '❌ 寄送失敗') . '</p></div>';
    }
    $s = mth_digest_get_settings();
    $next = wp_next_scheduled('mth_send_weekly_digest');
    ?>
    <div class="wrap">
        <h1>每週缺資料報告</h1>
        <p>每星期一早 9:00 自動 email 一份缺資料摘要俾你（總產品 / 缺圖 / 缺國旗 / 缺 ABV 等）。</p>
        <form method="post">
            <?php wp_nonce_field('mth_digest_save'); ?>
            <table class="form-table">
                <tr><th>啟用</th><td><label><input type="checkbox" name="enabled" value="1" <?php checked($s['enabled']); ?>> 開啟每週報告</label></td></tr>
                <tr><th>收件 Email</th><td><input type="email" name="email" value="<?php echo esc_attr($s['email']); ?>" class="regular-text"></td></tr>
                <tr><th>下次寄送</th><td><?php echo $next ? esc_html(wp_date('Y-m-d H:i', $next)) : '<em>未排程</em>'; ?></td></tr>
            </table>
            <p>
                <button type="submit" name="mth_digest_save" class="button button-primary">儲存設定</button>
                <button type="submit" name="mth_digest_test" class="button">🧪 即刻寄一封測試</button>
            </p>
        </form>
    </div>
    <?php
}

/* Cron 寄送 */
add_action('mth_send_weekly_digest', 'mth_send_weekly_digest');

function mth_send_weekly_digest() {
    $s = mth_digest_get_settings();
    if (empty($s['email'])) return false;
    $stats = function_exists('mth_audit_stats') ? mth_audit_stats() : array();

    $total=0; $no_thumb=0; $no_country=0; $no_abv=0; $no_spec=0;
    foreach ($stats as $r) { $total+=$r['total']; $no_thumb+=$r['no_thumb']; $no_country+=$r['no_country']; $no_abv+=$r['no_abv']; $no_spec+=$r['no_spec']; }

    $audit_url = admin_url('edit.php?post_type=mth_product&page=mth-product-audit');
    $rows = '';
    foreach ($stats as $r) {
        $rows .= sprintf(
            '<tr><td>%s</td><td>%d</td><td style="color:%s">%d</td><td style="color:%s">%d</td><td style="color:%s">%d</td></tr>',
            esc_html($r['name']), $r['total'],
            $r['no_thumb']>0?'#A32D2D':'#999', $r['no_thumb'],
            $r['no_country']>0?'#A32D2D':'#999', $r['no_country'],
            $r['no_abv']>0?'#A32D2D':'#999', $r['no_abv']
        );
    }

    $subject = sprintf('[明德行] 每週產品資料報告 — 缺圖 %d · 缺國旗 %d · 缺 ABV %d', $no_thumb, $no_country, $no_abv);
    $body = '<!DOCTYPE html><html><body style="font-family:sans-serif;color:#1C1C1C;">'
        . '<h2 style="color:#D4AF37;">明德行 — 每週產品資料報告</h2>'
        . '<p>' . esc_html(wp_date('Y-m-d D')) . '</p>'
        . '<div style="background:#fafaf7;border:1px solid #e8e3d5;padding:14px;border-radius:6px;margin:14px 0;">'
        . '<strong>整體：</strong> 總產品 <strong>' . $total . '</strong> · '
        . '缺圖 <strong style="color:#A32D2D;">' . $no_thumb . '</strong> · '
        . '缺國旗 <strong style="color:#A32D2D;">' . $no_country . '</strong> · '
        . '缺 ABV <strong style="color:#A32D2D;">' . $no_abv . '</strong> · '
        . '缺規格 <strong>' . $no_spec . '</strong>'
        . '</div>'
        . '<table cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;border-color:#ddd;">'
        . '<thead style="background:#1C1C1C;color:#D4AF37;"><tr><th>分類</th><th>總數</th><th>缺圖</th><th>缺國旗</th><th>缺 ABV</th></tr></thead>'
        . '<tbody>' . $rows . '</tbody></table>'
        . '<p style="margin-top:20px;"><a href="' . esc_url($audit_url) . '" style="background:#1C1C1C;color:#D4AF37;padding:10px 18px;text-decoration:none;border-radius:4px;">前往詳細診斷 →</a></p>'
        . '<p style="font-size:11px;color:#888;margin-top:30px;">此郵件由明德行網站系統自動發送。喺「📧 每週報告」頁面可關閉。</p>'
        . '</body></html>';

    add_filter('wp_mail_content_type', function(){ return 'text/html'; });
    $sent = wp_mail($s['email'], $subject, $body);
    remove_all_filters('wp_mail_content_type');
    return $sent;
}

/* 主題反 active 時清 cron */
register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('mth_send_weekly_digest');
});
