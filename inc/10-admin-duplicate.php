<?php
// Product duplicate row action
if (!defined('ABSPATH')) exit;

/* ── 後台產品：複製產品 Row Action ── */
add_filter('post_row_actions', function($actions, $post) {
    if ($post->post_type !== 'mth_product') return $actions;
    if (!current_user_can('edit_posts')) return $actions;
    $url = wp_nonce_url(
        admin_url('admin-post.php?action=mth_duplicate_product&post=' . $post->ID),
        'mth_duplicate_' . $post->ID
    );
    $actions['duplicate'] = '<a href="' . esc_url($url) . '">複製</a>';
    return $actions;
}, 10, 2);

add_action('admin_post_mth_duplicate_product', function() {
    if (empty($_GET['post'])) wp_die('No post');
    $original_id = (int) $_GET['post'];
    if (!current_user_can('edit_post', $original_id)) wp_die('No permission');
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mth_duplicate_' . $original_id)) {
        wp_die('Invalid nonce');
    }
    $orig = get_post($original_id);
    if (!$orig || $orig->post_type !== 'mth_product') wp_die('Invalid product');

    $new_id = wp_insert_post(array(
        'post_title'   => $orig->post_title . ' (副本)',
        'post_content' => $orig->post_content,
        'post_excerpt' => $orig->post_excerpt,
        'post_status'  => 'draft',
        'post_type'    => 'mth_product',
        'post_author'  => get_current_user_id(),
    ));
    if (is_wp_error($new_id)) wp_die($new_id->get_error_message());

    // 複製 meta
    $meta_keys = array('name_zh','name_en','spec','abv','source','origin_country','product_image');
    foreach ($meta_keys as $k) {
        $v = get_post_meta($original_id, $k, true);
        if ($v !== '') update_post_meta($new_id, $k, $v);
    }
    // 複製分類
    $terms = wp_get_object_terms($original_id, 'mth_product_cat', array('fields' => 'ids'));
    if (!is_wp_error($terms) && !empty($terms)) {
        wp_set_object_terms($new_id, $terms, 'mth_product_cat');
    }
    // 複製 featured image
    $thumb_id = get_post_thumbnail_id($original_id);
    if ($thumb_id) set_post_thumbnail($new_id, $thumb_id);

    wp_redirect(admin_url('post.php?action=edit&post=' . $new_id));
    exit;
});

