<?php
// ACF Local Field Group
if (!defined('ABSPATH')) exit;

/* ── ACF Local Field Group: 產品資料 ── */
add_action('acf/init', function() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_mth_product',
            'title' => '產品資料',
            'fields' => array(
                array(
                    'key' => 'field_name_zh',
                    'label' => '中文名稱',
                    'name' => 'name_zh',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_name_en',
                    'label' => '英文名稱',
                    'name' => 'name_en',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_spec',
                    'label' => '規格',
                    'name' => 'spec',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_abv',
                    'label' => '酒精度',
                    'name' => 'abv',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_source',
                    'label' => '來源',
                    'name' => 'source',
                    'type' => 'select',
                    'choices' => array(
                        '代理正貨' => '代理正貨',
                        '進口' => '進口',
                    ),
                ),
                array(
                    'key' => 'field_product_image',
                    'label' => '產品圖片',
                    'name' => 'product_image',
                    'type' => 'image',
                    'return_format' => 'url',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mth_product',
                    ),
                ),
            ),
        ));

    }
});

