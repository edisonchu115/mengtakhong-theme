<?php
// CSV import/export
if (!defined('ABSPATH')) exit;

function mth_render_csv_tool() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>產品 CSV 匯入 / 匯出</h1>
        <?php if (isset($_GET['imported'])): ?>
            <div class="notice notice-success"><p>已成功處理 <?php echo (int) $_GET['imported']; ?> 行，新增 <?php echo (int) $_GET['created']; ?> 件，更新 <?php echo (int) $_GET['updated']; ?> 件。</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['err'])): ?>
            <div class="notice notice-error"><p>匯入失敗：<?php echo esc_html($_GET['err']); ?></p></div>
        <?php endif; ?>

        <h2 style="margin-top:30px;">匯出</h2>
        <p>下載所有產品成 CSV 檔。可用 Excel 或 Google Sheets 打開編輯，然後再匯入更新。</p>
        <p>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=mth_export_csv'), 'mth_export_csv')); ?>" class="button button-primary">
                ⬇ 下載產品 CSV
            </a>
        </p>

        <h2 style="margin-top:30px;">匯入</h2>
        <p><strong>CSV 格式（第一行係欄位名）：</strong></p>
        <code style="display:block;padding:10px;background:#f0f0f0;border:1px solid #ddd;margin:10px 0;font-size:12px;">id, title, name_en, spec, abv, source, origin_country, category_slug</code>
        <ul style="list-style:disc;margin-left:20px;font-size:13px;">
            <li><code>id</code>：留空 = 新增，填數字 = 更新已有產品</li>
            <li><code>title</code>：中文名（必填）</li>
            <li><code>source</code>：代理正貨 / 進口</li>
            <li><code>origin_country</code>：國家 key（scotland、japan、france...）</li>
            <li><code>category_slug</code>：分類 slug（whisky、cognac、japan...）</li>
        </ul>
        <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:14px;">
            <input type="hidden" name="action" value="mth_import_csv">
            <?php wp_nonce_field('mth_import_csv', 'mth_import_nonce'); ?>
            <input type="file" name="csv" accept=".csv" required>
            <button type="submit" class="button button-primary">⬆ 上傳並匯入</button>
        </form>
    </div>
    <?php
}

add_action('admin_post_mth_export_csv', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'mth_export_csv')) wp_die('Bad nonce');

    $filename = 'mth-products-' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel

    $out = fopen('php://output', 'w');
    fputcsv($out, array('id','title','name_en','spec','abv','source','origin_country','category_slug'));

    $q = new WP_Query(array(
        'post_type' => 'mth_product', 'posts_per_page' => -1,
        'post_status' => array('publish','draft','pending'),
        'orderby' => 'title', 'order' => 'ASC',
    ));
    foreach ($q->posts as $p) {
        $cats = wp_get_post_terms($p->ID, 'mth_product_cat', array('fields' => 'slugs'));
        fputcsv($out, array(
            $p->ID,
            $p->post_title,
            get_post_meta($p->ID, 'name_en', true),
            get_post_meta($p->ID, 'spec', true),
            get_post_meta($p->ID, 'abv', true),
            get_post_meta($p->ID, 'source', true),
            get_post_meta($p->ID, 'origin_country', true),
            $cats ? $cats[0] : '',
        ));
    }
    fclose($out);
    exit;
});

add_action('admin_post_mth_import_csv', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!isset($_POST['mth_import_nonce']) || !wp_verify_nonce($_POST['mth_import_nonce'], 'mth_import_csv')) wp_die('Bad nonce');
    if (empty($_FILES['csv']['tmp_name'])) {
        wp_redirect(add_query_arg('err', '未選擇檔案', admin_url('edit.php?post_type=mth_product&page=mth-csv-tool')));
        exit;
    }

    $h = fopen($_FILES['csv']['tmp_name'], 'r');
    if (!$h) {
        wp_redirect(add_query_arg('err', '無法讀取檔案', admin_url('edit.php?post_type=mth_product&page=mth-csv-tool')));
        exit;
    }

    // 跳過 BOM
    $bom = fread($h, 3);
    if ($bom !== "\xEF\xBB\xBF") rewind($h);

    $headers = fgetcsv($h);
    if (!$headers) {
        fclose($h);
        wp_redirect(add_query_arg('err', 'CSV 空檔', admin_url('edit.php?post_type=mth_product&page=mth-csv-tool')));
        exit;
    }
    $idx = array_flip(array_map('trim', $headers));

    $created = 0; $updated = 0; $processed = 0;
    $valid_countries = array_merge(array(''), array_keys(mth_countries()));

    while (($row = fgetcsv($h)) !== false) {
        if (empty(array_filter($row))) continue;
        $processed++;

        $id     = isset($idx['id'])    && isset($row[$idx['id']])    ? (int) $row[$idx['id']] : 0;
        $title  = isset($idx['title']) && isset($row[$idx['title']]) ? sanitize_text_field($row[$idx['title']]) : '';
        if (!$title) continue;

        $name_en = isset($idx['name_en']) && isset($row[$idx['name_en']]) ? sanitize_text_field($row[$idx['name_en']]) : '';
        $spec    = isset($idx['spec'])    && isset($row[$idx['spec']])    ? sanitize_text_field($row[$idx['spec']])    : '';
        $abv     = isset($idx['abv'])     && isset($row[$idx['abv']])     ? sanitize_text_field($row[$idx['abv']])     : '';
        $source  = isset($idx['source'])  && isset($row[$idx['source']])  ? sanitize_text_field($row[$idx['source']])  : '';
        $country = isset($idx['origin_country']) && isset($row[$idx['origin_country']]) ? sanitize_text_field($row[$idx['origin_country']]) : '';
        $cat     = isset($idx['category_slug'])  && isset($row[$idx['category_slug']])  ? sanitize_text_field($row[$idx['category_slug']])  : '';

        if (!in_array($country, $valid_countries, true)) $country = '';

        if ($id > 0 && get_post_type($id) === 'mth_product') {
            wp_update_post(array('ID' => $id, 'post_title' => $title));
            $updated++;
        } else {
            $id = wp_insert_post(array(
                'post_title'  => $title,
                'post_type'   => 'mth_product',
                'post_status' => 'publish',
            ));
            if (is_wp_error($id) || !$id) continue;
            $created++;
        }

        if ($name_en !== '') update_post_meta($id, 'name_en', $name_en);
        if ($spec !== '')    update_post_meta($id, 'spec', $spec);
        if ($abv !== '')     update_post_meta($id, 'abv', $abv);
        if ($source !== '')  update_post_meta($id, 'source', $source);
        update_post_meta($id, 'origin_country', $country);

        if ($cat) {
            $term = get_term_by('slug', $cat, 'mth_product_cat');
            if ($term) wp_set_object_terms($id, array((int) $term->term_id), 'mth_product_cat');
        }
    }
    fclose($h);

    wp_redirect(add_query_arg(array(
        'imported' => $processed,
        'created'  => $created,
        'updated'  => $updated,
    ), admin_url('edit.php?post_type=mth_product&page=mth-csv-tool')));
    exit;
});
