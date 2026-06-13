<?php
// One-time: Japan data fixes
if (!defined('ABSPATH')) exit;

/* ──────────────────────────────────────────────
   日本產品資料修正（一次性錯字 / 質感清理）
   ────────────────────────────────────────────── */

function mth_japan_fixes() {
    return array(
        // 撇 → 撰 系統性錯字
        1171 => array('title' => '白鶴小百合特撰 純米濁酒'),
        1172 => array('title' => '白鶴小百合特撰 純米濁酒'),
        1173 => array('title' => '白鶴超特撰純米大吟釀 (新白鶴錦)'),
        1179 => array('title' => '白鶴翔雲超特撰 純米大吟釀'),
        1251 => array('title' => '白鶴特撰 純米吟釀'),
        1252 => array('title' => '白鶴特撰 純米吟釀'),
        1253 => array('title' => '白鶴特撰 純米吟釀'),
        1254 => array('title' => '白鶴上撰清酒 杯裝'),
        1255 => array('title' => '白鶴上撰清酒 紙盒裝'),
        1281 => array('title' => '白鶴上撰生貯藏酒'),

        // 大錯字
        1240 => array('title' => '日本火鳳凰5 年威士忌'),
        1233 => array('title' => '日本櫻尾臍橙味琴酒'),
        1249 => array('title' => '神戶梅酒氈酒'),
        1283 => array('title' => '日本信州岩井 極醇威士忌'),
        1280 => array('title' => '白鶴梅酒原酒', 'spec' => '1800ml'),

        // 京都長標題清理
        1288 => array('title' => '京都(京姬)匠大吟釀 無濾過生貯藏'),
        1291 => array('title' => '京都（匠）純米吟釀原酒 山田錦100%使用'),

        // 三佳利規格清理 + 統一「炭酸」(原本 Sangaria 日本用字)
        1303 => array('spec' => '500ml'),
        1304 => array('title' => '三佳利炭酸飲料 原味波子汽水-罐裝', 'spec' => '250g'),
        1305 => array('title' => '三佳利炭酸飲料 蜜瓜味-罐裝', 'spec' => '250g'),
        1306 => array('title' => '三佳利炭酸飲料 橙味-罐裝', 'spec' => '250g'),
        1307 => array('title' => '三佳利炭酸飲料 葡萄味-罐裝', 'spec' => '250g'),
        1308 => array('spec' => '340g'),
        1309 => array('spec' => '240g'),
        1310 => array('spec' => '240g'),
    );
}

add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '日本資料修正',
        '🔧 日本資料修正',
        'manage_options',
        'mth-japan-fix',
        'mth_render_japan_fix'
    );
});

function mth_render_japan_fix() {
    if (!current_user_can('manage_options')) return;
    $fixes = mth_japan_fixes();
    ?>
    <div class="wrap">
        <h1>🔧 日本產品資料修正</h1>
        <p>一次過修正所有錯字、清理標題同規格欄位。修正前會 preview，確認後先 apply。</p>

        <?php if (isset($_GET['fix_done'])): ?>
            <div class="notice notice-success"><p>✅ 已成功修正 <?php echo (int) $_GET['fix_done']; ?> 個產品 / 跳過 <?php echo (int) ($_GET['fix_skip'] ?? 0); ?> 個</p></div>
        <?php endif; ?>

        <table class="widefat striped">
            <thead>
                <tr style="background:#1C1C1C;color:#D4AF37;">
                    <th style="width:60px;">ID</th>
                    <th>欄位</th>
                    <th>現有</th>
                    <th>改做</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fixes as $id => $fix):
                    $post = get_post($id);
                    if (!$post) continue;
                ?>
                    <?php if (isset($fix['title']) && $post->post_title !== $fix['title']): ?>
                        <tr>
                            <td><strong><?php echo $id; ?></strong></td>
                            <td>標題</td>
                            <td style="color:#A32D2D;"><?php echo esc_html($post->post_title); ?></td>
                            <td style="color:#3B6D11;"><strong><?php echo esc_html($fix['title']); ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (isset($fix['spec'])):
                        $current_spec = get_post_meta($id, 'spec', true);
                        if ($current_spec !== $fix['spec']):
                    ?>
                        <tr>
                            <td><strong><?php echo $id; ?></strong></td>
                            <td>規格</td>
                            <td style="color:#A32D2D;"><?php echo esc_html($current_spec); ?></td>
                            <td style="color:#3B6D11;"><strong><?php echo esc_html($fix['spec']); ?></strong></td>
                        </tr>
                    <?php endif; endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top:20px;">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=mth_apply_japan_fixes'), 'mth_apply_japan_fixes')); ?>"
               class="button button-primary button-large"
               onclick="return confirm('確認套用所有修正？');">
                ✅ 套用所有修正
            </a>
        </p>
    </div>
    <?php
}

add_action('admin_post_mth_apply_japan_fixes', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mth_apply_japan_fixes')) wp_die('Bad nonce');

    $fixes = mth_japan_fixes();
    $done = 0; $skipped = 0;
    foreach ($fixes as $id => $fix) {
        if (get_post_type($id) !== 'mth_product') { $skipped++; continue; }
        $changed = false;

        if (isset($fix['title'])) {
            $post = get_post($id);
            if ($post && $post->post_title !== $fix['title']) {
                wp_update_post(array('ID' => $id, 'post_title' => $fix['title']));
                $changed = true;
            }
        }
        if (isset($fix['spec'])) {
            $current = get_post_meta($id, 'spec', true);
            if ($current !== $fix['spec']) {
                update_post_meta($id, 'spec', $fix['spec']);
                $changed = true;
            }
        }
        if ($changed) $done++; else $skipped++;
    }

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-japan-fix&fix_done=' . $done . '&fix_skip=' . $skipped));
    exit;
});
