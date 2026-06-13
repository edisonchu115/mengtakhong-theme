<?php
// Origin country meta box
if (!defined('ABSPATH')) exit;

/* ── 原產國 Meta Box（原生，避免 ACF 衝突）── */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'mth_origin_country_box',
        '原產國 Country of Origin',
        'mth_render_origin_country_box',
        'mth_product',
        'side',
        'default'
    );
});

function mth_render_origin_country_box($post) {
    $value = get_post_meta($post->ID, 'origin_country', true);
    wp_nonce_field('mth_save_origin_country_' . $post->ID, 'mth_origin_country_nonce');
    $current_flag = mth_country_flag($value);
    $current_name = mth_country_name($value);

    echo '<div style="font-size:48px;line-height:1;text-align:center;margin:8px 0 4px;min-height:54px;">';
    echo $current_flag ? esc_html($current_flag) : '<span style="font-size:14px;color:#aaa;">未設定</span>';
    echo '</div>';
    if ($current_name) {
        echo '<div style="text-align:center;font-size:13px;color:#555;margin-bottom:10px;">' . esc_html($current_name) . '</div>';
    }

    echo '<select name="mth_origin_country" style="width:100%;padding:4px;" id="mth_origin_country_select">';
    printf('<option value=""%s>— 不選擇 —</option>', selected($value, '', false));
    foreach (mth_countries() as $k => $info) {
        printf('<option value="%s"%s>%s %s</option>',
            esc_attr($k),
            selected($value, $k, false),
            esc_html($info['flag']),
            esc_html($info['zh'])
        );
    }
    echo '</select>';
    echo '<p style="font-size:11px;color:#999;margin-top:6px;">選擇後請按右側「更新」儲存。</p>';
}

add_action('save_post_mth_product', function($post_id) {
    if (!isset($_POST['mth_origin_country_nonce'])) return;
    if (!wp_verify_nonce($_POST['mth_origin_country_nonce'], 'mth_save_origin_country_' . $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $valid_keys = array_merge(array(''), array_keys(mth_countries()));
    $country = isset($_POST['mth_origin_country']) ? sanitize_text_field(wp_unslash($_POST['mth_origin_country'])) : '';
    if (!in_array($country, $valid_keys, true)) $country = '';
    update_post_meta($post_id, 'origin_country', $country);
});

