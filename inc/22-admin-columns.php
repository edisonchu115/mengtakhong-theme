<?php
// Product list columns + filters + sortable + search join
if (!defined('ABSPATH')) exit;

/* ── 後台產品列表：自訂欄位（基本欄位永遠顯示；日本品牌/種類只喺日本 filter 時顯示）── */
add_filter('manage_mth_product_posts_columns', function($cols) {
    $new = array();
    if (isset($cols['cb'])) { $new['cb'] = $cols['cb']; unset($cols['cb']); }
    $new['mth_thumb']      = '圖';
    if (isset($cols['title'])) { $new['title'] = $cols['title']; unset($cols['title']); }
    $new['origin_country'] = '原產國';
    $new['mth_spec']       = '規格';
    $new['mth_abv']        = 'ABV';
    $new['mth_source']     = '來源';
    // 品牌 / 種類 column — 顯示產品所屬分類嘅 brand/type
    $new['mth_brand_col']  = '品牌';
    $new['mth_type_col']   = '種類';
    if (isset($cols['taxonomy-mth_product_cat'])) { $new['taxonomy-mth_product_cat'] = $cols['taxonomy-mth_product_cat']; unset($cols['taxonomy-mth_product_cat']); }
    if (isset($cols['date'])) { $new['date'] = $cols['date']; unset($cols['date']); }
    return array_merge($new, $cols);
});

add_action('manage_mth_product_posts_custom_column', function($col, $post_id) {
    switch ($col) {
        case 'mth_thumb':
            $img = get_the_post_thumbnail_url($post_id, 'thumbnail');
            if ($img) {
                echo '<img src="' . esc_url($img) . '" style="width:44px;height:44px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:4px;">';
            } else {
                echo '<span style="display:inline-block;width:44px;height:44px;line-height:44px;text-align:center;background:#f5f0e8;border-radius:4px;color:#bbb;font-size:18px;">—</span>';
            }
            break;
        case 'origin_country':
            $country = get_post_meta($post_id, 'origin_country', true);
            if ($country) {
                echo '<span style="font-size:18px;">' . esc_html(mth_country_flag($country)) . '</span> ';
                echo '<span style="color:#666;font-size:12px;">' . esc_html(mth_country_name($country)) . '</span>';
            } else {
                echo '<span style="color:#bbb;">—</span>';
            }
            break;
        case 'mth_spec':
            $v = get_post_meta($post_id, 'spec', true);
            echo $v ? esc_html($v) : '<span style="color:#bbb;">—</span>';
            break;
        case 'mth_abv':
            $v = get_post_meta($post_id, 'abv', true);
            echo $v ? esc_html($v . '%') : '<span style="color:#bbb;">—</span>';
            break;
        case 'mth_source':
            $v = get_post_meta($post_id, 'source', true);
            if ($v === '代理正貨') {
                echo '<span style="background:#fff4d6;color:#8a6d00;padding:2px 8px;border-radius:10px;font-size:11px;">代理正貨</span>';
            } elseif ($v) {
                echo esc_html($v);
            } else {
                echo '<span style="color:#bbb;">—</span>';
            }
            // ── Hidden data spans for Quick Edit pre-fill ──
            $brand_manual_v = get_post_meta($post_id, 'mth_brand_manual', true);
            $types_manual_v = get_post_meta($post_id, 'mth_types_manual', true);
            $is_jp = mth_is_japan_product($post_id) ? '1' : '';
            echo '<div class="mth-inline-data" style="display:none">';
            echo '<span class="mth-data-spec">'    . esc_html(get_post_meta($post_id, 'spec', true)) . '</span>';
            echo '<span class="mth-data-abv">'     . esc_html(get_post_meta($post_id, 'abv', true)) . '</span>';
            echo '<span class="mth-data-source">'  . esc_html(get_post_meta($post_id, 'source', true)) . '</span>';
            echo '<span class="mth-data-country">' . esc_html(get_post_meta($post_id, 'origin_country', true)) . '</span>';
            echo '<span class="mth-data-brand-manual">' . esc_html($brand_manual_v) . '</span>';
            echo '<span class="mth-data-types-manual">' . esc_html($types_manual_v) . '</span>';
            echo '<span class="mth-data-is-japan">' . esc_html($is_jp) . '</span>';
            echo '</div>';
            break;
        case 'mth_brand_col':
            $cat = mth_get_product_primary_cat($post_id);
            if (!$cat) { echo '<span style="color:#bbb;">—</span>'; break; }
            $brand_map = mth_brand_map($cat);
            if (empty($brand_map)) { echo '<span style="color:#bbb;font-size:11px;">未設定</span>'; break; }
            $effective = mth_get_product_brand($post_id);
            $manual    = get_post_meta($post_id, 'mth_brand_manual', true);
            $label     = isset($brand_map[$effective]) ? $brand_map[$effective]['label']
                          : ($effective === 'other' ? '其他' : '—');
            $icon = $manual ? '<span title="手動覆蓋" style="color:#D4AF37;">✏️</span>'
                              : '<span title="自動判斷" style="color:#999;">🤖</span>';
            echo $icon . ' <span style="font-size:12px;">' . esc_html($label) . '</span>';
            break;
        case 'mth_type_col':
            $cat = mth_get_product_primary_cat($post_id);
            if (!$cat) { echo '<span style="color:#bbb;">—</span>'; break; }
            $type_map = mth_type_map($cat);
            if (empty($type_map)) { echo '<span style="color:#bbb;font-size:11px;">未設定</span>'; break; }
            $types  = mth_get_product_types($post_id);
            $manual = get_post_meta($post_id, 'mth_types_manual', true);
            if (empty($types)) { echo '<span style="color:#bbb;">—</span>'; break; }
            $labels = array_map(function($t) use ($type_map) {
                return isset($type_map[$t]) ? $type_map[$t]['label'] : $t;
            }, $types);
            $icon = $manual ? '<span title="手動覆蓋" style="color:#D4AF37;">✏️</span>'
                              : '<span title="自動判斷" style="color:#999;">🤖</span>';
            echo $icon . ' <span style="font-size:11px;">' . esc_html(implode(' ', $labels)) . '</span>';
            break;
    }
}, 10, 2);

/* ── 後台產品列表：原產國 + 日本品牌 + 日本種類 篩選下拉 ── */
add_action('restrict_manage_posts', function() {
    global $typenow;
    if ($typenow !== 'mth_product') return;

    // 原產國
    $selected = isset($_GET['origin_country']) ? sanitize_text_field($_GET['origin_country']) : '';
    echo '<select name="origin_country"><option value="">所有原產國</option>';
    printf('<option value="__none__"%s>— 未設定 —</option>', selected($selected, '__none__', false));
    foreach (mth_countries() as $k => $info) {
        printf('<option value="%s"%s>%s %s</option>',
            esc_attr($k), selected($selected, $k, false),
            esc_html($info['flag']), esc_html($info['zh']));
    }
    echo '</select> ';

    // 品牌 / 種類 — 只喺已篩咗任何分類時先顯示，並用該分類嘅 map
    $cat_slug = mth_get_admin_filter_cat_slug();
    if ($cat_slug) {
        $brand_map = mth_brand_map($cat_slug);
        $type_map  = mth_type_map($cat_slug);

        if (!empty($brand_map)) {
            $sb = isset($_GET['mth_brand_filter']) ? sanitize_text_field($_GET['mth_brand_filter']) : '';
            echo '<select name="mth_brand_filter"><option value="">所有品牌</option>';
            foreach ($brand_map as $k => $info) {
                printf('<option value="%s"%s>%s</option>', esc_attr($k), selected($sb, $k, false), esc_html($info['label']));
            }
            printf('<option value="other"%s>其他</option>', selected($sb, 'other', false));
            echo '</select> ';
        }

        if (!empty($type_map)) {
            $st = isset($_GET['mth_type_filter']) ? sanitize_text_field($_GET['mth_type_filter']) : '';
            echo '<select name="mth_type_filter"><option value="">所有種類</option>';
            foreach ($type_map as $k => $info) {
                printf('<option value="%s"%s>%s</option>', esc_attr($k), selected($st, $k, false), esc_html($info['label']));
            }
            echo '</select>';
        }
    }
});

// 後台篩選器：根據品牌或種類 filter，需要查 manual meta + 標題包含 pattern
add_filter('parse_query', function($query) {
    global $pagenow;
    if ($pagenow !== 'edit.php') return;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return;

    $cat_slug = mth_get_admin_filter_cat_slug();
    if (!$cat_slug) return; // 冇分類 filter 嘅話，brand/type filter 都唔生效

    // 品牌篩選
    if (!empty($_GET['mth_brand_filter'])) {
        $brand = sanitize_text_field($_GET['mth_brand_filter']);
        $brand_map = mth_brand_map($cat_slug);
        if (isset($brand_map[$brand]) || $brand === 'other') {
            $patterns = ($brand === 'other') ? array() : $brand_map[$brand]['patterns'];
            $query->set('_mth_brand_filter', $brand);
            $query->set('_mth_brand_patterns', $patterns);
        }
    }

    // 種類篩選
    if (!empty($_GET['mth_type_filter'])) {
        $type = sanitize_text_field($_GET['mth_type_filter']);
        $type_map = mth_type_map($cat_slug);
        if (isset($type_map[$type])) {
            $patterns = $type_map[$type]['patterns'];
            $query->set('_mth_type_filter', $type);
            $query->set('_mth_type_patterns', $patterns);
        }
    }
});

// 加 SQL JOIN/WHERE 處理品牌/種類篩選（手動 meta OR 標題 LIKE）
add_filter('posts_where', function($where, $query) {
    global $wpdb, $pagenow;
    if (!is_admin() || $pagenow !== 'edit.php') return $where;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return $where;

    $brand    = $query->get('_mth_brand_filter');
    $patterns_b = $query->get('_mth_brand_patterns');
    $type     = $query->get('_mth_type_filter');
    $patterns_t = $query->get('_mth_type_patterns');

    if ($brand) {
        $clauses = array();
        // 條件 A：mth_brand_manual = brand
        $clauses[] = $wpdb->prepare(
            "EXISTS (SELECT 1 FROM {$wpdb->postmeta} mb WHERE mb.post_id={$wpdb->posts}.ID AND mb.meta_key='mth_brand_manual' AND mb.meta_value=%s)",
            $brand
        );
        if ($brand !== 'other' && !empty($patterns_b)) {
            // 條件 B：mth_brand_manual 未設定 AND 標題包含 pattern
            $like_parts = array();
            foreach ($patterns_b as $p) {
                $like_parts[] = $wpdb->prepare("{$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($p) . '%');
            }
            $clauses[] = "( NOT EXISTS (SELECT 1 FROM {$wpdb->postmeta} mb2 WHERE mb2.post_id={$wpdb->posts}.ID AND mb2.meta_key='mth_brand_manual' AND mb2.meta_value!='') AND (" . implode(' OR ', $like_parts) . ") )";
        }
        $where .= ' AND (' . implode(' OR ', $clauses) . ') ';
    }

    if ($type) {
        $clauses = array();
        // 條件 A：mth_types_manual 包含 type
        $clauses[] = $wpdb->prepare(
            "EXISTS (SELECT 1 FROM {$wpdb->postmeta} mt WHERE mt.post_id={$wpdb->posts}.ID AND mt.meta_key='mth_types_manual' AND (mt.meta_value=%s OR mt.meta_value LIKE %s OR mt.meta_value LIKE %s OR mt.meta_value LIKE %s))",
            $type, $type . ',%', '%,' . $type, '%,' . $type . ',%'
        );
        if (!empty($patterns_t)) {
            $like_parts = array();
            foreach ($patterns_t as $p) {
                $like_parts[] = $wpdb->prepare("{$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($p) . '%');
            }
            $clauses[] = "( NOT EXISTS (SELECT 1 FROM {$wpdb->postmeta} mt2 WHERE mt2.post_id={$wpdb->posts}.ID AND mt2.meta_key='mth_types_manual' AND mt2.meta_value!='') AND (" . implode(' OR ', $like_parts) . ") )";
        }
        $where .= ' AND (' . implode(' OR ', $clauses) . ') ';
    }

    return $where;
}, 10, 2);

/* ── 原本嘅原產國 parse_query（保留，獨立處理 origin_country）── */
add_filter('parse_query', function($query) {
    global $pagenow;
    if ($pagenow !== 'edit.php') return;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return;
    if (empty($_GET['origin_country'])) return;
    $val = sanitize_text_field($_GET['origin_country']);
    $mq = (array) $query->get('meta_query');
    if ($val === '__none__') {
        $mq[] = array('key' => 'origin_country', 'compare' => 'NOT EXISTS');
        $mq[] = array('relation' => 'OR',
            array('key' => 'origin_country', 'value' => '', 'compare' => '='),
            array('key' => 'origin_country', 'compare' => 'NOT EXISTS'),
        );
    } else {
        $mq[] = array('key' => 'origin_country', 'value' => $val, 'compare' => '=');
    }
    $query->set('meta_query', $mq);
});

/* ── 後台產品列表：可按欄位排序 ── */
add_filter('manage_edit-mth_product_sortable_columns', function($cols) {
    $cols['origin_country'] = 'origin_country';
    return $cols;
});
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('orderby') === 'origin_country') {
        $query->set('meta_key', 'origin_country');
        $query->set('orderby', 'meta_value');
    }
});

/* ── 後台產品列表：列出可搜尋欄位（搜尋 name_en/spec）── */
add_filter('posts_join', function($join, $query) {
    global $wpdb, $pagenow;
    if (!is_admin() || $pagenow !== 'edit.php') return $join;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return $join;
    if (empty($_GET['s'])) return $join;
    $join .= " LEFT JOIN {$wpdb->postmeta} mth_sm ON ({$wpdb->posts}.ID = mth_sm.post_id) ";
    return $join;
}, 10, 2);
add_filter('posts_where', function($where, $query) {
    global $wpdb, $pagenow;
    if (!is_admin() || $pagenow !== 'edit.php') return $where;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return $where;
    if (empty($_GET['s'])) return $where;
    $s = '%' . $wpdb->esc_like(sanitize_text_field($_GET['s'])) . '%';
    $where .= $wpdb->prepare(
        " OR (mth_sm.meta_key IN ('name_en','name_zh','spec','abv') AND mth_sm.meta_value LIKE %s) ",
        $s
    );
    return $where;
}, 10, 2);
add_filter('posts_distinct', function($distinct, $query) {
    global $pagenow;
    if (!is_admin() || $pagenow !== 'edit.php') return $distinct;
    if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'mth_product') return $distinct;
    if (empty($_GET['s'])) return $distinct;
    return 'DISTINCT';
}, 10, 2);

