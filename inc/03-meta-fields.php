<?php
// REST API meta exposure
if (!defined('ABSPATH')) exit;

/* ── REST API: expose custom fields ── */
function mth_register_meta_fields() {
    $fields = ['name_zh', 'name_en', 'spec', 'abv', 'source', 'product_slug', 'product_image', 'origin_country'];
    foreach ( $fields as $field ) {
        register_post_meta( 'mth_product', $field, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ]);
    }
}
add_action( 'init', 'mth_register_meta_fields' );

