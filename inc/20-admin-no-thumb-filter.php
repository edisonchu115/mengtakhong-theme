<?php
// No-thumb filter parse_query
if (!defined('ABSPATH')) exit;

/* ── 後台產品列表：「缺精選圖」filter ── */
add_filter('parse_query', function($query) {
    global $pagenow;
    if ($pagenow !== 'edit.php') return;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return;
    if (empty($_GET['mth_audit']) || $_GET['mth_audit'] !== 'no_thumb') return;

    $mq = (array) $query->get('meta_query');
    $mq[] = array('key' => '_thumbnail_id', 'compare' => 'NOT EXISTS');
    $query->set('meta_query', $mq);

    if (!empty($_GET['cat'])) {
        $query->set('tax_query', array(array(
            'taxonomy' => 'mth_product_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['cat']),
        )));
    }
});

