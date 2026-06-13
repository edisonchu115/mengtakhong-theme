<?php
// Product audit + No-thumb filter
if (!defined('ABSPATH')) exit;


function mth_render_product_audit() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>產品診斷</h1>
        <p>列出每個分類嘅產品狀態，幫你一眼睇邊啲產品需要補資料。</p>

        <?php
        $cats = get_terms(array('taxonomy' => 'mth_product_cat', 'hide_empty' => false));
        if (is_wp_error($cats) || empty($cats)) { echo '<p>未有分類</p>'; return; }

        echo '<table class="widefat striped" style="margin-top:18px;">';
        echo '<thead><tr><th>分類</th><th>總數</th><th>有精選圖</th><th>缺精選圖</th><th>缺中文名</th><th>缺英文名</th><th>缺規格</th><th>缺原產國</th><th>操作</th></tr></thead><tbody>';

        foreach ($cats as $cat) {
            $all = new WP_Query(array(
                'post_type'      => 'mth_product',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => 'publish',
                'tax_query'      => array(array('taxonomy' => 'mth_product_cat', 'field' => 'term_id', 'terms' => $cat->term_id)),
            ));
            $total = count($all->posts);
            $has_thumb = 0; $no_thumb = 0; $no_zh = 0; $no_en = 0; $no_spec = 0; $no_country = 0;
            foreach ($all->posts as $pid) {
                if (get_post_thumbnail_id($pid)) $has_thumb++; else $no_thumb++;
                $title = get_the_title($pid);
                if (!$title || $title === '自動草稿') $no_zh++;
                if (!get_post_meta($pid, 'name_en', true)) $no_en++;
                if (!get_post_meta($pid, 'spec', true)) $no_spec++;
                if (!get_post_meta($pid, 'origin_country', true)) $no_country++;
            }
            $filter_no_thumb_url = admin_url('edit.php?post_type=mth_product&mth_audit=no_thumb&cat=' . $cat->slug);
            printf(
                '<tr><td><strong>%s</strong><br><small>%s</small></td><td>%d</td><td style="color:#3B6D11">%d</td><td style="color:%s">%d</td><td>%d</td><td>%d</td><td>%d</td><td>%d</td><td><a href="%s" class="button button-small">睇缺圖產品</a></td></tr>',
                esc_html($cat->name),
                esc_html($cat->slug),
                $total,
                $has_thumb,
                $no_thumb > 0 ? '#A32D2D' : '#999',
                $no_thumb,
                $no_zh, $no_en, $no_spec, $no_country,
                esc_url($filter_no_thumb_url)
            );
        }
        echo '</tbody></table>';
        ?>

        <h2 style="margin-top:30px;">輪播狀態</h2>
        <p>首頁輪播會優先顯示有精選圖嘅產品；如果整個分類都無精選圖，就會 fallback 顯示無圖產品（米色 placeholder）。</p>
        <p><strong>建議：</strong>每個分類至少有 5–10 隻有精選圖嘅產品，輪播效果先靚。</p>

        <h2 style="margin-top:40px;">一次性：威士忌國旗批量套用</h2>
        <p>嚴謹研究 38 隻威士忌，35 隻有官方來源確認，3 隻無法 100% 確認嘅唔處理：</p>
        <ul style="list-style:disc;margin-left:24px;font-size:13px;">
            <li>🏴󠁧󠁢󠁳󠁣󠁴󠁿 蘇格蘭：31 隻</li>
            <li>🇨🇦 加拿大：2 隻</li>
            <li>🇺🇸 美國：1 隻（Bourbon）</li>
            <li>🇹🇭 泰國：1 隻</li>
            <li>⚠️ 未確認（不標）：ID 310 麥純威 Milhson's、ID 325 詩龍納斯 Sylenius、ID 879 嘉富 Giardino</li>
        </ul>
        <?php
        $applied = get_option('mth_whisky_flags_applied');
        if (!empty($_GET['flags_done'])) {
            echo '<div class="notice notice-success"><p>✅ 已成功套用 ' . (int) $_GET['flags_done'] . ' 隻威士忌嘅國旗</p></div>';
        }
        ?>
        <p style="margin-top:14px;">
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=mth_apply_whisky_flags'), 'mth_apply_whisky_flags')); ?>"
               class="button button-primary"
               onclick="return confirm('確認套用？呢個動作會覆蓋現有嘅原產國設定。');">
                🎯 套用威士忌國旗
            </a>
            <?php if ($applied): ?>
                <span style="margin-left:12px;color:#666;font-size:13px;">上次執行：<?php echo esc_html($applied); ?>（可以再執行覆蓋）</span>
            <?php endif; ?>
        </p>
    </div>
    <?php
}

