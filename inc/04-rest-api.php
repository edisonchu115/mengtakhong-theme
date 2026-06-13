<?php
// REST API routes + suggest endpoint
if (!defined('ABSPATH')) exit;

/* ── REST API: custom endpoint to list products ── */
function mth_register_api_routes() {
    register_rest_route( 'mth/v1', '/products', [
        'methods'             => 'GET',
        'callback'            => 'mth_get_products',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route( 'mth/v1', '/products/(?P<cat>[a-zA-Z0-9_-]+)', [
        'methods'             => 'GET',
        'callback'            => 'mth_get_products_by_cat',
        'permission_callback' => '__return_true',
    ]);
}
add_action( 'rest_api_init', 'mth_register_api_routes' );

/* ── 搜尋建議 API（autocomplete 用）── */
add_action('rest_api_init', function() {
    register_rest_route('mth/v1', '/suggest', array(
        'methods'             => 'GET',
        'callback'            => 'mth_search_suggest',
        'permission_callback' => '__return_true',
        'args' => array(
            'q' => array('required' => true, 'sanitize_callback' => 'sanitize_text_field'),
        ),
    ));
});
function mth_search_suggest($request) {
    global $wpdb;
    $q = trim($request['q']);
    if (mb_strlen($q) < 1) return array();
    $like = '%' . $wpdb->esc_like(strtolower($q)) . '%';

    // Title 直接匹配優先，meta 匹配次之
    $sql = $wpdb->prepare(
        "SELECT DISTINCT p.ID, p.post_title, p.post_name,
                CASE WHEN LOWER(p.post_title) LIKE %s THEN 1 ELSE 2 END AS rank
         FROM {$wpdb->posts} p
         LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
         WHERE p.post_type = 'mth_product'
           AND p.post_status = 'publish'
           AND (
                LOWER(p.post_title) LIKE %s
                OR (pm.meta_key IN ('name_en','name_zh','spec') AND LOWER(pm.meta_value) LIKE %s)
           )
         ORDER BY rank ASC, p.post_title ASC
         LIMIT 8",
        $like, $like, $like
    );
    $rows = $wpdb->get_results($sql);
    $out = array();
    foreach ($rows as $r) {
        $name_en = get_post_meta($r->ID, 'name_en', true);
        $img     = get_the_post_thumbnail_url($r->ID, 'thumbnail');
        $country = get_post_meta($r->ID, 'origin_country', true);
        $out[] = array(
            'id'      => (int) $r->ID,
            'title'   => $r->post_title,
            'name_en' => $name_en,
            'url'     => get_permalink($r->ID),
            'img'     => $img ?: '',
            'flag'    => mth_country_flag($country),
        );
    }
    return $out;
}

function mth_get_products( $request ) {
    $posts = get_posts([
        'post_type'   => 'mth_product',
        'numberposts' => -1,
        'post_status' => 'publish',
    ]);
    return array_map( 'mth_format_product', $posts );
}

function mth_get_products_by_cat( $request ) {
    $cat_slug = sanitize_text_field( $request['cat'] );
    $posts = get_posts([
        'post_type'   => 'mth_product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'tax_query'   => [[
            'taxonomy' => 'mth_product_cat',
            'field'    => 'slug',
            'terms'    => $cat_slug,
        ]],
    ]);
    return array_map( 'mth_format_product', $posts );
}

function mth_format_product( $post ) {
    $meta = get_post_meta( $post->ID );
    $cats = wp_get_post_terms( $post->ID, 'mth_product_cat', ['fields' => 'slugs'] );
    return [
        'id'      => $post->ID,
        'slug'    => $post->post_name,
        'name_zh' => $meta['name_zh'][0] ?? $post->post_title,
        'name_en' => $meta['name_en'][0] ?? '',
        'spec'    => $meta['spec'][0] ?? '',
        'abv'     => $meta['abv'][0] ?? '',
        'source'  => $meta['source'][0] ?? '',
        'cat'     => $cats[0] ?? '',
        'image'   => get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '',
    ];
}

