<?php
// Filter management page (current)
if (!defined('ABSPATH')) exit;

function mth_render_filter_mgmt() {
    if (!current_user_can('manage_options')) return;

    // 攞所有分類
    $all_cats = get_terms(array('taxonomy' => 'mth_product_cat', 'hide_empty' => false, 'orderby' => 'name'));
    if (is_wp_error($all_cats) || empty($all_cats)) {
        echo '<div class="wrap"><h1>🏷️ 分類篩選管理</h1><p>未有產品分類。</p></div>';
        return;
    }

    // 揀緊邊個分類
    $current_cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';
    if (!$current_cat) $current_cat = $all_cats[0]->slug;
    $current_term = get_term_by('slug', $current_cat, 'mth_product_cat');
    $current_name = $current_term ? $current_term->name : $current_cat;

    // === 處理儲存 ===
    if (isset($_POST['mth_save_filters']) && check_admin_referer('mth_filter_mgmt')) {
        $process = function($rows_post) {
            if (!is_array($rows_post)) return array();
            $out = array();
            foreach ($rows_post as $row) {
                if (!empty($row['delete'])) continue;
                $key = isset($row['key']) ? preg_replace('/[^a-zA-Z0-9_\-]/', '', $row['key']) : '';
                if (empty($key)) continue;
                $label = isset($row['label']) ? sanitize_text_field($row['label']) : '';
                $patterns_raw = isset($row['patterns']) ? sanitize_text_field($row['patterns']) : '';
                $patterns = array_values(array_filter(array_map('trim', explode(',', $patterns_raw))));
                $out[$key] = array('label' => $label, 'patterns' => $patterns);
            }
            return $out;
        };

        $brands_new = $process($_POST['brands'] ?? array());
        $types_new  = $process($_POST['types'] ?? array());

        update_option('mth_brand_map_' . $current_cat, $brands_new);
        update_option('mth_type_map_'  . $current_cat, $types_new);

        echo '<div class="notice notice-success is-dismissible"><p>✅ 已儲存「' . esc_html($current_name) . '」分類嘅變更</p></div>';
    }

    // === 處理重置 ===
    if (isset($_POST['mth_reset_filters']) && check_admin_referer('mth_filter_mgmt')) {
        delete_option('mth_brand_map_' . $current_cat);
        delete_option('mth_type_map_'  . $current_cat);
        if ($current_cat === 'japan') {
            // Japan 額外：重新將舊 default 寫返入新 key
            delete_option('mth_japan_brand_map_v1');
            delete_option('mth_japan_type_map_v1');
        }
        echo '<div class="notice notice-success is-dismissible"><p>✅ 已重置「' . esc_html($current_name) . '」</p></div>';
    }

    $brands = mth_brand_map($current_cat);
    $types  = mth_type_map($current_cat);
    ?>
    <div class="wrap">
        <h1>🏷️ 分類篩選管理</h1>
        <p style="font-size:14px;">每個產品分類都可以設定自己嘅<strong>品牌</strong>同<strong>種類</strong>篩選選項。設定咗之後前台分類頁會自動出現對應 filter sidebar。</p>

        <!-- 揀分類 -->
        <form method="get" style="background:#fff;padding:12px 16px;border:1px solid #ccd0d4;border-radius:4px;margin:14px 0;">
            <input type="hidden" name="post_type" value="mth_product">
            <input type="hidden" name="page" value="mth-filter-mgmt">
            <label style="font-weight:600;">揀分類：
                <select name="cat" onchange="this.form.submit()" style="margin-left:8px;min-width:200px;">
                    <?php foreach ($all_cats as $c): ?>
                        <option value="<?php echo esc_attr($c->slug); ?>" <?php selected($current_cat, $c->slug); ?>>
                            <?php echo esc_html($c->name); ?>（<?php echo (int) $c->count; ?> 個產品）
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <span style="color:#888;margin-left:14px;font-size:13px;">當前 slug: <code><?php echo esc_html($current_cat); ?></code></span>
        </form>

        <form method="post">
            <?php wp_nonce_field('mth_filter_mgmt'); ?>
            <input type="hidden" name="cat" value="<?php echo esc_attr($current_cat); ?>">

            <h2 style="margin-top:30px;">🏭 「<?php echo esc_html($current_name); ?>」品牌</h2>
            <p style="color:#666;font-size:13px;">「<strong>關鍵字</strong>」用於自動判斷產品品牌（產品標題包含任何一個關鍵字會自動歸入此品牌，用逗號分隔）。</p>
            <table class="widefat striped" style="margin-top:8px;">
                <thead>
                    <tr style="background:#1C1C1C;color:#D4AF37;">
                        <th style="width:18%">Key（英文標識）</th>
                        <th style="width:32%">顯示名稱</th>
                        <th style="width:36%">關鍵字（逗號分隔）</th>
                        <th style="width:14%">刪除</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($brands as $key => $info): ?>
                    <tr>
                        <td><input type="text" name="brands[<?php echo $i; ?>][key]" value="<?php echo esc_attr($key); ?>" style="width:100%"></td>
                        <td><input type="text" name="brands[<?php echo $i; ?>][label]" value="<?php echo esc_attr($info['label']); ?>" style="width:100%"></td>
                        <td><input type="text" name="brands[<?php echo $i; ?>][patterns]" value="<?php echo esc_attr(implode(',', $info['patterns'] ?? array())); ?>" style="width:100%"></td>
                        <td style="text-align:center;"><label><input type="checkbox" name="brands[<?php echo $i; ?>][delete]" value="1"> 刪除</label></td>
                    </tr>
                    <?php $i++; endforeach; ?>

                    <?php for ($n = 0; $n < 3; $n++): $idx = $i + $n; ?>
                    <tr style="background:#fff8e8;">
                        <td><input type="text" name="brands[<?php echo $idx; ?>][key]" placeholder="新 key" style="width:100%"></td>
                        <td><input type="text" name="brands[<?php echo $idx; ?>][label]" placeholder="顯示名稱" style="width:100%"></td>
                        <td><input type="text" name="brands[<?php echo $idx; ?>][patterns]" placeholder="關鍵字1,關鍵字2" style="width:100%"></td>
                        <td style="text-align:center;color:#888;">← 新增</td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <h2 style="margin-top:40px;">🍶 「<?php echo esc_html($current_name); ?>」種類</h2>
            <p style="color:#666;font-size:13px;">「<strong>顯示名稱</strong>」可加 emoji。一隻產品可以屬於多個種類。</p>
            <table class="widefat striped" style="margin-top:8px;">
                <thead>
                    <tr style="background:#1C1C1C;color:#D4AF37;">
                        <th style="width:18%">Key</th>
                        <th style="width:32%">顯示名稱</th>
                        <th style="width:36%">關鍵字</th>
                        <th style="width:14%">刪除</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0; foreach ($types as $key => $info): ?>
                    <tr>
                        <td><input type="text" name="types[<?php echo $j; ?>][key]" value="<?php echo esc_attr($key); ?>" style="width:100%"></td>
                        <td><input type="text" name="types[<?php echo $j; ?>][label]" value="<?php echo esc_attr($info['label']); ?>" style="width:100%"></td>
                        <td><input type="text" name="types[<?php echo $j; ?>][patterns]" value="<?php echo esc_attr(implode(',', $info['patterns'] ?? array())); ?>" style="width:100%"></td>
                        <td style="text-align:center;"><label><input type="checkbox" name="types[<?php echo $j; ?>][delete]" value="1"> 刪除</label></td>
                    </tr>
                    <?php $j++; endforeach; ?>

                    <?php for ($n = 0; $n < 3; $n++): $idx = $j + $n; ?>
                    <tr style="background:#fff8e8;">
                        <td><input type="text" name="types[<?php echo $idx; ?>][key]" placeholder="新 key" style="width:100%"></td>
                        <td><input type="text" name="types[<?php echo $idx; ?>][label]" placeholder="🍶 顯示名稱" style="width:100%"></td>
                        <td><input type="text" name="types[<?php echo $idx; ?>][patterns]" placeholder="關鍵字1,關鍵字2" style="width:100%"></td>
                        <td style="text-align:center;color:#888;">← 新增</td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <p style="margin-top:30px;">
                <button type="submit" name="mth_save_filters" value="1" class="button button-primary button-large">💾 儲存「<?php echo esc_html($current_name); ?>」</button>
                <button type="submit" name="mth_reset_filters" value="1" class="button" style="margin-left:10px;"
                    onclick="return confirm('確定重置「<?php echo esc_js($current_name); ?>」？');">↺ 重置呢個分類</button>
            </p>
        </form>

        <div style="background:#fff;border:1px solid #D4AF37;border-radius:8px;padding:20px;margin-top:40px;">
            <h2 style="margin-top:0;">💡 點樣修正單個產品嘅分類？</h2>
            <ol style="font-size:14px;line-height:2;">
                <li>入「<a href="<?php echo esc_url(admin_url('edit.php?post_type=mth_product')); ?>"><strong>產品管理</strong></a>」</li>
                <li>頂部分類下拉揀對應分類（例如「<?php echo esc_html($current_name); ?>」）按「篩選」</li>
                <li>搵到產品撳「<strong>編輯</strong>」（或「快速編輯」）</li>
                <li>右側「<strong>🏷️ 品牌 / 種類覆蓋</strong>」box 揀正確嘅品牌/種類</li>
                <li>儲存</li>
            </ol>
        </div>
    </div>
    <?php
}

