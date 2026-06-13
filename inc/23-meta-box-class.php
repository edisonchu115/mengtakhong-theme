<?php
// Brand/Type meta box
if (!defined('ABSPATH')) exit;

/* ── 品牌 / 種類覆蓋 Meta Box — 通用，按產品所屬分類顯示 ── */
add_action('add_meta_boxes_mth_product', function($post) {
    add_meta_box(
        'mth_class_box',
        '🏷️ 品牌 / 種類覆蓋',
        'mth_render_class_box',
        'mth_product',
        'side',
        'high'
    );
});

function mth_render_class_box($post) {
    $cat = mth_get_product_primary_cat($post->ID);
    if (!$cat) {
        echo '<p style="color:#888;font-size:12px;margin:4px 0;line-height:1.6;">';
        echo '請先喺右側「<strong>產品分類</strong>」勾選一個分類，按「<strong>更新</strong>」儲存，再 refresh 呢頁。';
        echo '</p>';
        return;
    }

    $brand_map = mth_brand_map($cat);
    $type_map  = mth_type_map($cat);
    $cat_term  = get_term_by('slug', $cat, 'mth_product_cat');
    $cat_name  = $cat_term ? $cat_term->name : $cat;

    if (empty($brand_map) && empty($type_map)) {
        echo '<p style="color:#888;font-size:12px;margin:4px 0;line-height:1.6;">';
        echo '「<strong>' . esc_html($cat_name) . '</strong>」分類未設定任何品牌或種類。<br>';
        echo '👉 請去「<a href="' . esc_url(admin_url('edit.php?post_type=mth_product&page=mth-filter-mgmt&cat=' . $cat)) . '">🏷️ 分類篩選管理</a>」加。';
        echo '</p>';
        return;
    }

    wp_nonce_field('mth_japan_class_box', 'mth_japan_class_nonce');
    $brand_manual = get_post_meta($post->ID, 'mth_brand_manual', true);
    $types_manual = get_post_meta($post->ID, 'mth_types_manual', true);
    $manual_types_arr = $types_manual ? array_filter(array_map('trim', explode(',', $types_manual))) : array();

    $auto_brand = mth_classify_brand_auto_v2($post->post_title, $cat);
    $auto_types = mth_classify_types_auto_v2($post->post_title, $cat);

    $auto_brand_label = isset($brand_map[$auto_brand]) ? $brand_map[$auto_brand]['label'] : ($auto_brand === 'other' ? '其他' : '—');
    $auto_types_label = array_map(function($t) use ($type_map) {
        return isset($type_map[$t]) ? $type_map[$t]['label'] : $t;
    }, $auto_types);

    echo '<p style="font-size:11px;color:#888;margin:4px 0 10px;">分類：<strong>' . esc_html($cat_name) . '</strong></p>';

    if (!empty($brand_map)): ?>
    <p style="margin-top:4px;">
        <label style="display:block;margin-bottom:4px;"><strong>品牌</strong></label>
        <select name="mth_brand_manual" style="width:100%;">
            <option value="">— 自動判斷（<?php echo esc_html($auto_brand_label); ?>）—</option>
            <?php foreach ($brand_map as $k => $info): ?>
                <option value="<?php echo esc_attr($k); ?>" <?php selected($brand_manual, $k); ?>><?php echo esc_html($info['label']); ?></option>
            <?php endforeach; ?>
            <option value="other" <?php selected($brand_manual, 'other'); ?>>其他</option>
        </select>
    </p>
    <?php endif; ?>

    <?php if (!empty($type_map)): ?>
    <p>
        <label style="display:block;margin-bottom:6px;"><strong>種類</strong>（可多選）</label>
        <?php foreach ($type_map as $k => $info): ?>
            <label style="display:block;margin:2px 0;font-size:13px;">
                <input type="checkbox" name="mth_types_manual[]" value="<?php echo esc_attr($k); ?>" <?php checked(in_array($k, $manual_types_arr, true)); ?>>
                <?php echo esc_html($info['label']); ?>
            </label>
        <?php endforeach; ?>
        <small style="display:block;color:#999;margin-top:6px;">
            全部不選 = 自動判斷（<?php echo esc_html(implode(' / ', $auto_types_label) ?: '冇'); ?>）
        </small>
    </p>
    <?php endif; ?>
    <?php
}

// 儲存 Meta Box — 通用，按產品所屬分類處理
add_action('save_post_mth_product', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['mth_japan_class_nonce'])) return;
    if (!wp_verify_nonce($_POST['mth_japan_class_nonce'], 'mth_japan_class_box')) return;

    $cat = mth_get_product_primary_cat($post_id);
    if (!$cat) return;

    // 品牌
    if (isset($_POST['mth_brand_manual'])) {
        $brand = sanitize_text_field($_POST['mth_brand_manual']);
        $valid = array_merge(array('', 'other'), array_keys(mth_brand_map($cat)));
        if (in_array($brand, $valid, true)) {
            update_post_meta($post_id, 'mth_brand_manual', $brand);
        }
    }
    // 種類
    if (isset($_POST['mth_types_manual']) && is_array($_POST['mth_types_manual'])) {
        $valid_t = array_keys(mth_type_map($cat));
        $types = array_filter(array_map('sanitize_text_field', $_POST['mth_types_manual']),
            function($t) use ($valid_t) { return in_array($t, $valid_t, true); });
        update_post_meta($post_id, 'mth_types_manual', implode(',', $types));
    } elseif (!empty(mth_type_map($cat))) {
        // 有 type_map 但用戶冇勾選 = 清除
        update_post_meta($post_id, 'mth_types_manual', '');
    }
});

