<?php
// Product CPT + Taxonomy
if (!defined('ABSPATH')) exit;

/* ── Custom Post Type: Product ── */
function mth_register_product_cpt() {
    $labels = [
        'name'               => '產品',
        'singular_name'      => '產品',
        'menu_name'          => '產品管理',
        'add_new'            => '新增產品',
        'add_new_item'       => '新增產品',
        'edit_item'          => '編輯產品',
        'new_item'           => '新產品',
        'view_item'          => '查看產品',
        'search_items'       => '搜尋產品',
        'not_found'          => '未找到產品',
        'not_found_in_trash' => '回收桶沒有產品',
    ];
    register_post_type( 'mth_product', [
        'labels'       => $labels,
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'product'],
        'supports'     => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'menu_icon'    => 'dashicons-store',
        'menu_position'=> 5,
        'taxonomies'   => ['mth_product_cat'],
    ]);
}
add_action( 'init', 'mth_register_product_cpt' );

/* ── Custom Taxonomy: Product Category ── */
function mth_register_product_taxonomy() {
    $labels = [
        'name'              => '產品分類',
        'singular_name'     => '產品分類',
        'search_items'      => '搜尋分類',
        'all_items'         => '所有分類',
        'parent_item'       => '上層分類',
        'parent_item_colon' => '上層分類:',
        'edit_item'         => '編輯分類',
        'update_item'       => '更新分類',
        'add_new_item'      => '新增分類',
        'new_item_name'     => '新分類名稱',
        'menu_name'         => '產品分類',
    ];
    register_taxonomy( 'mth_product_cat', ['mth_product'], [
        'hierarchical'  => true,
        'labels'        => $labels,
        'show_ui'       => true,
        'show_in_rest'  => true,
        'rewrite'       => ['slug' => 'product-category'],
        'query_var'     => true,
    ]);
}
add_action( 'init', 'mth_register_product_taxonomy' );

/* ── Flush rewrite rules on activation ── */
function mth_flush_rewrites() {
    mth_register_product_cpt();
    mth_register_product_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mth_flush_rewrites' );

/* ── Disable Gutenberg for products ── */
function mth_disable_gutenberg( $is_enabled, $post_type ) {
    if ( $post_type === 'mth_product' ) return false;
    return $is_enabled;
}
add_filter( 'use_block_editor_for_post_type', 'mth_disable_gutenberg', 10, 2 );

/* ── Custom Post Type: Brand ── */
function mth_register_brand_cpt() {
    register_post_type('mth_brand', array(
        'labels' => array(
            'name'          => '品牌管理',
            'singular_name' => '品牌',
            'add_new'       => '新增品牌',
            'add_new_item'  => '新增品牌',
            'edit_item'     => '編輯品牌',
            'all_items'     => '所有品牌',
        ),
        'public'        => true,
        'show_in_rest'  => true,
        'show_in_menu'  => true,
        'menu_position' => 6,
        'menu_icon'     => 'dashicons-awards',
        'supports'      => array('title', 'thumbnail'),
        'rewrite'       => array('slug' => 'brand'),
    ));
}
add_action('init', 'mth_register_brand_cpt');

