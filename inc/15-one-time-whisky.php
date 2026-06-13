<?php
// One-time: Whisky flags bulk update
if (!defined('ABSPATH')) exit;

/* ── 一次性 bulk update：威士忌國旗 ── */
add_action('admin_post_mth_apply_whisky_flags', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mth_apply_whisky_flags')) wp_die('Bad nonce');

    $mapping = array(
        // 蘇格蘭 31 隻（全部有官方來源確認，移除咗 Milhson's 因無官方來源）
        'scotland' => array(275, 276, 278, 279, 294, 295, 305, 306, 307, 323, 324, 639, 640, 643, 644, 646, 273, 274, 277, 289, 291, 292, 308, 309, 321, 322, 338, 339, 340, 341, 342),
        // 加拿大 2 隻
        'canada'   => array(293, 337),
        // 美國 1 隻（Bourbon, Kentucky）
        'usa'      => array(290),
        // 泰國 1 隻
        'thailand' => array(326),
        // ⚠️ 未處理（無官方來源確認）：310 Milhson's、325 Sylenius、879 Giardino
    );

    $count = 0;
    foreach ($mapping as $country => $ids) {
        foreach ($ids as $id) {
            if (get_post_type($id) === 'mth_product') {
                update_post_meta($id, 'origin_country', $country);
                $count++;
            }
        }
    }
    update_option('mth_whisky_flags_applied', current_time('mysql'));

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-product-audit&flags_done=' . $count));
    exit;
});

