<?php
// admin_menu registrations (consolidated)
if (!defined('ABSPATH')) exit;

/* ── CSV 匯出 / 匯入工具 + 診斷工具 ── */
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        'CSV 匯入/匯出',
        'CSV 工具',
        'manage_options',
        'mth-csv-tool',
        'mth_render_csv_tool'
    );
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '產品診斷',
        '產品診斷',
        'manage_options',
        'mth-product-audit',
        'mth_render_product_audit'
    );
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '分類篩選管理',
        '🏷️ 分類篩選管理',
        'manage_options',
        'mth-filter-mgmt',
        'mth_render_filter_mgmt'
    );
});

