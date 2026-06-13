<?php
// Quick Edit + Bulk Edit + JS pre-fill
if (!defined('ABSPATH')) exit;

/* ── 後台產品：Quick Edit（inline）── */
add_action('quick_edit_custom_box', function($column_name, $post_type) {
    if ($post_type !== 'mth_product') return;
    if (!in_array($column_name, array('origin_country','mth_spec','mth_abv','mth_source','mth_brand_col','mth_type_col'), true)) return;
    static $nonce_done = false;
    if (!$nonce_done) {
        wp_nonce_field('mth_quick_edit', 'mth_quick_edit_nonce');
        $nonce_done = true;
    }
    echo '<fieldset class="inline-edit-col-right"><div class="inline-edit-col">';
    switch ($column_name) {
        case 'origin_country':
            echo '<label class="inline-edit-group"><span class="title">原產國</span><select name="origin_country"><option value="">— 不選擇 —</option>';
            foreach (mth_countries() as $k => $info) {
                printf('<option value="%s">%s %s</option>', esc_attr($k), esc_html($info['flag']), esc_html($info['zh']));
            }
            echo '</select></label>';
            break;
        case 'mth_spec':
            echo '<label class="inline-edit-group"><span class="title">規格</span><input type="text" name="spec" value=""></label>';
            break;
        case 'mth_abv':
            echo '<label class="inline-edit-group"><span class="title">ABV</span><input type="text" name="abv" value="" style="width:60px"> %</label>';
            break;
        case 'mth_source':
            echo '<label class="inline-edit-group"><span class="title">來源</span><select name="source"><option value="">—</option><option value="代理正貨">代理正貨</option><option value="進口">進口</option></select></label>';
            break;
        case 'mth_brand_col':
            $cat_slug = mth_get_admin_filter_cat_slug();
            if (!$cat_slug) {
                echo '<label class="inline-edit-group"><p style="color:#888;font-size:11px;margin:6px 0;">💡 篩咗分類先可以改品牌/種類</p></label>';
                break;
            }
            $brand_map = mth_brand_map($cat_slug);
            if (empty($brand_map)) {
                echo '<label class="inline-edit-group"><p style="color:#888;font-size:11px;margin:6px 0;">此分類未設定品牌，請先去「🏷️ 分類篩選管理」加</p></label>';
                break;
            }
            echo '<label class="inline-edit-group"><span class="title">品牌（覆蓋）</span><select name="mth_brand_manual"><option value="">— 自動判斷 —</option>';
            foreach ($brand_map as $k => $info) {
                printf('<option value="%s">%s</option>', esc_attr($k), esc_html($info['label']));
            }
            echo '<option value="other">其他</option>';
            echo '</select></label>';
            break;
        case 'mth_type_col':
            $cat_slug = mth_get_admin_filter_cat_slug();
            if (!$cat_slug) break;
            $type_map = mth_type_map($cat_slug);
            if (empty($type_map)) {
                echo '<label class="inline-edit-group"><p style="color:#888;font-size:11px;margin:6px 0;">此分類未設定種類</p></label>';
                break;
            }
            echo '<label class="inline-edit-group"><span class="title">種類（覆蓋）</span></label>';
            echo '<div style="margin:4px 0 0 8px;display:flex;flex-wrap:wrap;gap:8px;">';
            foreach ($type_map as $k => $info) {
                printf('<label style="white-space:nowrap;font-size:12px;"><input type="checkbox" name="mth_types_manual[]" value="%s"> %s</label>',
                    esc_attr($k), esc_html($info['label']));
            }
            echo '</div>';
            echo '<p style="font-size:11px;color:#888;margin:4px 0 0 8px;">全部不選 = 自動判斷</p>';
            break;
    }
    echo '</div></fieldset>';
}, 10, 2);

/* ── 後台產品：Bulk Edit（批量）── */
add_action('bulk_edit_custom_box', function($column_name, $post_type) {
    if ($post_type !== 'mth_product') return;
    if (!in_array($column_name, array('origin_country','mth_source','mth_brand_col'), true)) return;
    static $nonce_done = false;
    if (!$nonce_done) {
        wp_nonce_field('mth_bulk_edit', 'mth_bulk_edit_nonce');
        $nonce_done = true;
    }
    echo '<fieldset class="inline-edit-col-right"><div class="inline-edit-col">';
    switch ($column_name) {
        case 'origin_country':
            echo '<label class="inline-edit-group"><span class="title">原產國</span><select name="bulk_origin_country"><option value="-1">— 不變 —</option>';
            foreach (mth_countries() as $k => $info) {
                printf('<option value="%s">%s %s</option>', esc_attr($k), esc_html($info['flag']), esc_html($info['zh']));
            }
            echo '</select></label>';
            break;
        case 'mth_source':
            echo '<label class="inline-edit-group"><span class="title">來源</span><select name="bulk_source"><option value="-1">— 不變 —</option><option value="代理正貨">代理正貨</option><option value="進口">進口</option></select></label>';
            break;
        case 'mth_brand_col':
            $cat_slug = mth_get_admin_filter_cat_slug();
            if (!$cat_slug) break;
            $brand_map = mth_brand_map($cat_slug);
            if (empty($brand_map)) break;
            echo '<label class="inline-edit-group"><span class="title">品牌</span><select name="bulk_brand_manual"><option value="-1">— 不變 —</option><option value="">清除（自動）</option>';
            foreach ($brand_map as $k => $info) {
                printf('<option value="%s">%s</option>', esc_attr($k), esc_html($info['label']));
            }
            echo '<option value="other">其他</option>';
            echo '</select></label>';
            break;
    }
    echo '</div></fieldset>';
}, 10, 2);

/* ── Quick / Bulk Edit 儲存處理 ── */
add_action('save_post_mth_product', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Quick edit
    if (isset($_POST['mth_quick_edit_nonce']) && wp_verify_nonce($_POST['mth_quick_edit_nonce'], 'mth_quick_edit')) {
        if (isset($_POST['origin_country'])) {
            $val = sanitize_text_field($_POST['origin_country']);
            $valid = array_merge(array(''), array_keys(mth_countries()));
            if (in_array($val, $valid, true)) update_post_meta($post_id, 'origin_country', $val);
        }
        if (isset($_POST['spec']))   update_post_meta($post_id, 'spec', sanitize_text_field($_POST['spec']));
        if (isset($_POST['abv']))    update_post_meta($post_id, 'abv', sanitize_text_field($_POST['abv']));
        if (isset($_POST['source'])) update_post_meta($post_id, 'source', sanitize_text_field($_POST['source']));

        // 品牌 / 種類覆蓋 — 用產品所屬分類嘅 map 做驗證
        $cat = mth_get_product_primary_cat($post_id);
        if ($cat) {
            if (isset($_POST['mth_brand_manual'])) {
                $brand = sanitize_text_field($_POST['mth_brand_manual']);
                $brand_map = mth_brand_map($cat);
                $valid_b = array_merge(array('', 'other'), array_keys($brand_map));
                if (in_array($brand, $valid_b, true)) {
                    update_post_meta($post_id, 'mth_brand_manual', $brand);
                }
            }
            if (isset($_POST['mth_types_manual']) && is_array($_POST['mth_types_manual'])) {
                $type_map = mth_type_map($cat);
                $valid_t = array_keys($type_map);
                $types = array_filter(array_map('sanitize_text_field', $_POST['mth_types_manual']),
                    function($t) use ($valid_t) { return in_array($t, $valid_t, true); });
                update_post_meta($post_id, 'mth_types_manual', implode(',', $types));
            }
        }
    }

    // Bulk edit
    if (isset($_POST['mth_bulk_edit_nonce']) && wp_verify_nonce($_POST['mth_bulk_edit_nonce'], 'mth_bulk_edit')) {
        if (isset($_POST['bulk_origin_country']) && $_POST['bulk_origin_country'] !== '-1') {
            $val = sanitize_text_field($_POST['bulk_origin_country']);
            $valid = array_merge(array(''), array_keys(mth_countries()));
            if (in_array($val, $valid, true)) update_post_meta($post_id, 'origin_country', $val);
        }
        if (isset($_POST['bulk_source']) && $_POST['bulk_source'] !== '-1') {
            update_post_meta($post_id, 'source', sanitize_text_field($_POST['bulk_source']));
        }
        if (isset($_POST['bulk_brand_manual']) && $_POST['bulk_brand_manual'] !== '-1') {
            $cat = mth_get_product_primary_cat($post_id);
            if ($cat) {
                $brand = sanitize_text_field($_POST['bulk_brand_manual']);
                $valid_b = array_merge(array('', 'other'), array_keys(mth_brand_map($cat)));
                if (in_array($brand, $valid_b, true)) {
                    update_post_meta($post_id, 'mth_brand_manual', $brand);
                }
            }
        }
    }
});

/* ── Quick Edit 預填現有資料（解決每次撳 Quick Edit 都係空白嘅問題）── */
add_action('admin_footer-edit.php', function() {
    global $typenow;
    if ($typenow !== 'mth_product') return;
    ?>
    <script>
    (function($) {
      if (typeof inlineEditPost === 'undefined') return;
      var origEdit = inlineEditPost.edit;
      inlineEditPost.edit = function(id) {
        origEdit.apply(this, arguments);
        var post_id = 0;
        if (typeof(id) === 'object') {
          post_id = parseInt(this.getId(id), 10);
        }
        if (!post_id) return;

        var $row  = $('#post-' + post_id);
        var $edit = $('#edit-' + post_id);

        function read(cls) { return $row.find('.mth-data-' + cls).text() || ''; }

        // 預填現有資料到 Quick Edit form
        $edit.find('input[name="spec"]').val(read('spec'));
        $edit.find('input[name="abv"]').val(read('abv'));
        $edit.find('select[name="source"]').val(read('source'));
        $edit.find('select[name="origin_country"]').val(read('country'));
        $edit.find('select[name="mth_brand_manual"]').val(read('brand-manual'));

        // 種類 checkbox 預填
        var typesManual = read('types-manual');
        var typesArr = typesManual ? typesManual.split(',') : [];
        $edit.find('input[name="mth_types_manual[]"]').each(function() {
          this.checked = typesArr.indexOf(this.value) !== -1;
        });

        // 非日本產品自動隱藏品牌 / 種類欄
        var isJapan = read('is-japan') === '1';
        var $brandFs = $edit.find('select[name="mth_brand_manual"]').closest('fieldset');
        var $typeFs  = $edit.find('input[name="mth_types_manual[]"]').first().closest('fieldset');
        $brandFs.toggle(isJapan);
        $typeFs.toggle(isJapan);
        // 加提示（非日本產品時）
        $edit.find('.mth-non-jp-notice').remove();
        if (!isJapan) {
          $brandFs.before('<fieldset class="inline-edit-col-right mth-non-jp-notice"><div class="inline-edit-col"><p style="color:#888;font-size:11px;margin:6px 0;">💡 「日本品牌 / 種類」只係日本產品分類可用</p></div></fieldset>');
        }
      };
    })(jQuery);
    </script>
    <?php
});

