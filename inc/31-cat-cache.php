<?php
// 分類頁 body 快取失效控制
// taxonomy-mth_product_cat.php 用 transient 快取訪客頁面；
// 任何產品/分類改動就 bump 版本號 → 全部分類 cache 即時失效，下次訪客重新生成。
if (!defined('ABSPATH')) exit;

function mth_bump_cat_cache_ver($post_id = 0) {
    // 跳過 autosave / revision，避免無謂 thrash
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post_id && wp_is_post_revision($post_id)) return;
    $v = (int) get_option('mth_cat_cache_ver', 1);
    update_option('mth_cat_cache_ver', $v + 1, false);
}

// 產品 新增/更新/刪除
add_action('save_post_mth_product', 'mth_bump_cat_cache_ver', 10, 1);
add_action('deleted_post',          'mth_bump_cat_cache_ver', 10, 1);
add_action('trashed_post',          'mth_bump_cat_cache_ver', 10, 1);
add_action('untrashed_post',        'mth_bump_cat_cache_ver', 10, 1);

// 分類 term 改動（slug / 名 / 排序）
add_action('edited_mth_product_cat',  'mth_bump_cat_cache_ver');
add_action('created_mth_product_cat', 'mth_bump_cat_cache_ver');
add_action('delete_term',             'mth_bump_cat_cache_ver');

// brand/type map 喺後台改完亦要 bust（mth_brand_map_* / mth_type_map_* options）
add_action('updated_option', function($option) {
    if (strpos($option, 'mth_brand_map_') === 0 || strpos($option, 'mth_type_map_') === 0) {
        mth_bump_cat_cache_ver();
    }
}, 10, 1);
