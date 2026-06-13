<?php
// Search filters
if (!defined('ABSPATH')) exit;

/* ── Search: mth_product only, published, 48 per page ── */
add_filter('pre_get_posts', function($query) {
    if ($query->is_search() && !is_admin() && $query->is_main_query()) {
        $query->set('post_type', array('mth_product'));
        $query->set('posts_per_page', 48);
        $query->set('post_status', 'publish');
    }
    return $query;
});

/* ── Search: ACF fields, case-insensitive ── */
add_filter('posts_search', function($search, $query) {
    global $wpdb;
    if (!$query->is_search() || is_admin() || !$query->is_main_query()) return $search;

    $term = $query->get('s');
    if (empty($term)) return $search;

    $like = '%' . $wpdb->esc_like(strtolower($term)) . '%';

    $exists = $wpdb->prepare(
        "EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            WHERE pm.post_id = {$wpdb->posts}.ID
            AND pm.meta_key IN ('name_en', 'name_zh', 'spec')
            AND LOWER(pm.meta_value) LIKE %s
        )",
        $like
    );

    // Wrap both conditions so the OR does not escape post_type/post_status constraints
    $inner  = preg_replace('/^\s*AND\s+/i', '', $search);
    $search = " AND (" . $inner . " OR " . $exists . ")";

    return $search;
}, 10, 2);

/* ── Search: prevent duplicate results ── */
add_filter('posts_groupby', function($groupby, $query) {
    global $wpdb;
    if ($query->is_search() && !is_admin() && $query->is_main_query()) {
        $groupby = "{$wpdb->posts}.ID";
    }
    return $groupby;
}, 10, 2);

