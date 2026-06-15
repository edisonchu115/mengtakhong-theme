<?php
// 主題文字編輯器 — Edison 可自助修改 front-page / footer 嘅 hardcoded 文字
if (!defined('ABSPATH')) exit;

define('MTH_TEXT_OPT', 'mth_text_settings');

/* Helper: 讀取一個文字設定，無就用 default */
function mth_text($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $opt = get_option(MTH_TEXT_OPT, array());
        $cache = is_array($opt) ? $opt : array();
    }
    return (isset($cache[$key]) && $cache[$key] !== '') ? $cache[$key] : $default;
}

/* 全部欄位定義（label / 預設值 / 類型）*/
function mth_text_fields() {
    return array(
        'hero' => array(
            'title' => '🎯 首頁 Hero 區',
            'desc'  => '首頁最頂大標題',
            'fields' => array(
                'hero_eyebrow'  => array('label'=>'頂部小字 (英文)','default'=>'Meng Tak Hong International · Est. 1998'),
                'hero_title_1'  => array('label'=>'主標題第 1 段','default'=>'澳門'),
                'hero_title_2'  => array('label'=>'主標題第 2 段 (金色高亮)','default'=>'洋酒飲品'),
                'hero_title_3'  => array('label'=>'主標題第 3 段','default'=>'批發代理'),
                'hero_subtitle' => array('label'=>'副標題','default'=>'專業B2B批發服務 · 威士忌 · 干邑 · 葡萄酒 · 日本酒 · 韓國飲品','type'=>'textarea'),
            ),
        ),
        'cathdr' => array(
            'title' => '📦 產品分類區標題',
            'desc'  => '首頁 Hero 下面、分類按鈕之前嗰段',
            'fields' => array(
                'cathdr_eyebrow'  => array('label'=>'頂部英文','default'=>'Our Categories'),
                'cathdr_title'    => array('label'=>'中文標題','default'=>'產品分類'),
                'cathdr_subtitle' => array('label'=>'副標題','default'=>'覆蓋全線洋酒、飲品及食品'),
            ),
        ),
        'stats' => array(
            'title' => '📊 數字統計區',
            'desc'  => '首頁底部 4 個數字（第 1 個係自動計算總產品數，可改 label）',
            'fields' => array(
                'stat1_label' => array('label'=>'統計 1 標籤 (總產品數會自動算)','default'=>'款產品'),
                'stat2_num'   => array('label'=>'統計 2 數字','default'=>'25'),
                'stat2_label' => array('label'=>'統計 2 標籤','default'=>'年行業經驗'),
                'stat3_num'   => array('label'=>'統計 3 數字','default'=>'8'),
                'stat3_label' => array('label'=>'統計 3 標籤','default'=>'產品系列'),
                'stat4_num'   => array('label'=>'統計 4 數字/文字','default'=>'B2B'),
                'stat4_label' => array('label'=>'統計 4 標籤','default'=>'專業批發服務'),
            ),
        ),
        'company' => array(
            'title' => '🏢 公司資料 (Footer)',
            'desc'  => '頁尾公司名、地址、電話、Email',
            'fields' => array(
                'company_zh'    => array('label'=>'公司中文名','default'=>'明德行國際有限公司'),
                'company_en'    => array('label'=>'公司英文名','default'=>'Meng Tak Hong International Co., Ltd.'),
                'company_est'   => array('label'=>'創立年份標籤','default'=>'Est. 1998'),
                'company_address' => array('label'=>'公司地址 (一行)','default'=>'澳門黑沙環慕拉士大馬路195號','type'=>'textarea'),
                'company_address2' => array('label'=>'公司地址 (第二行)','default'=>'南嶺工業大廈4樓F'),
                'company_phone1' => array('label'=>'電話 1','default'=>'+853 28415128'),
                'company_phone2' => array('label'=>'電話 2','default'=>'+853 28584838'),
                'company_email'  => array('label'=>'公司 Email','default'=>'info@mengtakhong.com'),
                'footer_tagline' => array('label'=>'頁尾右下角標語','default'=>'澳門洋酒飲品批發代理'),
            ),
        ),
        'social' => array(
            'title' => '📱 社交媒體連結',
            'desc'  => '頁尾「聯絡我們」社交按鈕 URL',
            'fields' => array(
                'social_fb_url' => array('label'=>'Facebook URL','default'=>'https://www.facebook.com/profile.php?id=61555744448402'),
                'social_ig_url' => array('label'=>'Instagram URL','default'=>'https://www.instagram.com/mengtakhong.mo/'),
            ),
        ),
        'subs' => array(
            'title' => '📧 訂閱表單',
            'desc'  => '頁尾「訂閱新到貨」表單文字',
            'fields' => array(
                'subs_title'   => array('label'=>'區塊標題','default'=>'訂閱新到貨'),
                'subs_name_ph' => array('label'=>'稱呼 placeholder','default'=>'稱呼'),
                'subs_method_ph' => array('label'=>'聯絡方式 placeholder','default'=>'聯絡方式'),
                'subs_contact_ph' => array('label'=>'聯絡方式輸入 placeholder','default'=>'輸入聯絡方式'),
                'subs_btn'     => array('label'=>'按鈕文字','default'=>'訂閱'),
                'footer_cats_title' => array('label'=>'頁尾「產品分類」標題','default'=>'產品分類'),
                'footer_contact_title' => array('label'=>'頁尾「聯絡我們」標題','default'=>'聯絡我們'),
            ),
        ),
    );
}

/* Admin menu */
add_action('admin_menu', function() {
    add_menu_page(
        '主題文字編輯',
        '🎨 主題文字',
        'manage_options',
        'mth-text-options',
        'mth_render_text_options',
        'dashicons-edit',
        61
    );
});

/* Settings API */
add_action('admin_init', function() {
    register_setting('mth_text_group', MTH_TEXT_OPT, array(
        'type' => 'array',
        'sanitize_callback' => 'mth_text_sanitize',
    ));
});

function mth_text_sanitize($input) {
    if (!is_array($input)) return array();
    $out = array();
    $sections = mth_text_fields();
    foreach ($sections as $sect) {
        foreach ($sect['fields'] as $key => $meta) {
            if (!isset($input[$key])) continue;
            $val = wp_unslash($input[$key]);
            $type = isset($meta['type']) ? $meta['type'] : 'text';
            if ($type === 'textarea') {
                $val = sanitize_textarea_field($val);
            } elseif (strpos($key, '_url') !== false) {
                $val = esc_url_raw($val);
            } else {
                $val = sanitize_text_field($val);
            }
            $out[$key] = $val;
        }
    }
    return $out;
}

function mth_render_text_options() {
    if (!current_user_can('manage_options')) return;
    $opt = get_option(MTH_TEXT_OPT, array());
    if (!is_array($opt)) $opt = array();
    $sections = mth_text_fields();
    $home_url = home_url('/');
    ?>
    <style>
      .mth-text-wrap { max-width: 960px; }
      .mth-text-toolbar { background:#fff; border:1px solid #ddd; border-radius:6px; padding:12px 16px; margin:14px 0 20px; display:flex; gap:10px; align-items:center; }
      .mth-text-toolbar .button-primary { background:#1C1C1C; border-color:#1C1C1C; }
      .mth-text-toolbar .button-primary:hover { background:#D4AF37; border-color:#D4AF37; color:#1C1C1C; }
      .mth-text-section { background:#fff; border:1px solid #e8e3d5; border-radius:8px; margin-bottom:18px; overflow:hidden; }
      .mth-text-section-head { background:linear-gradient(180deg,#fafaf7,#f5f0e8); padding:14px 20px; border-bottom:1px solid #e8e3d5; }
      .mth-text-section-head h2 { margin:0; font-size:16px; color:#1C1C1C; }
      .mth-text-section-head p { margin:4px 0 0; font-size:12px; color:#888; }
      .mth-text-section-body { padding:8px 20px 20px; }
      .mth-text-row { display:flex; align-items:flex-start; gap:16px; padding:10px 0; border-bottom:1px dashed #f0ebe0; }
      .mth-text-row:last-child { border-bottom:none; }
      .mth-text-label { flex:0 0 200px; font-size:13px; color:#444; padding-top:6px; }
      .mth-text-input { flex:1; }
      .mth-text-input input[type=text], .mth-text-input textarea { width:100%; max-width:520px; }
      .mth-text-input textarea { min-height:60px; }
      .mth-text-default { font-size:11px; color:#999; margin-top:3px; font-style:italic; }
      .mth-text-saved { color:#3B6D11; font-weight:600; }
    </style>
    <div class="wrap mth-text-wrap">
      <h1>🎨 主題文字編輯</h1>
      <p>直接修改首頁、頁尾嘅文字。<strong>留空 = 用預設值</strong>。改完撳「儲存設定」即生效（前台立刻顯示新文字）。</p>

      <?php settings_errors(); ?>

      <form method="post" action="options.php">
        <?php settings_fields('mth_text_group'); ?>

        <div class="mth-text-toolbar">
          <button type="submit" name="submit" class="button button-primary">💾 儲存設定</button>
          <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="button">← 返回後台首頁</a>
          <a href="<?php echo esc_url($home_url); ?>" target="_blank" class="button">🔍 前台預覽 (新分頁)</a>
          <span style="margin-left:auto;color:#888;font-size:12px;">💡 改完撳上面個「儲存」掣，唔好直接關</span>
        </div>

        <?php foreach ($sections as $sk => $section): ?>
        <div class="mth-text-section">
          <div class="mth-text-section-head">
            <h2><?php echo esc_html($section['title']); ?></h2>
            <?php if (!empty($section['desc'])): ?><p><?php echo esc_html($section['desc']); ?></p><?php endif; ?>
          </div>
          <div class="mth-text-section-body">
            <?php foreach ($section['fields'] as $key => $meta):
              $val = isset($opt[$key]) ? $opt[$key] : '';
              $type = isset($meta['type']) ? $meta['type'] : 'text';
              $name = MTH_TEXT_OPT . '[' . $key . ']';
            ?>
            <div class="mth-text-row">
              <div class="mth-text-label"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($meta['label']); ?></label></div>
              <div class="mth-text-input">
                <?php if ($type === 'textarea'): ?>
                  <textarea id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_attr($meta['default']); ?>"><?php echo esc_textarea($val); ?></textarea>
                <?php else: ?>
                  <input type="text" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>" placeholder="<?php echo esc_attr($meta['default']); ?>">
                <?php endif; ?>
                <div class="mth-text-default">預設：<?php echo esc_html($meta['default']); ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>

        <div class="mth-text-toolbar">
          <button type="submit" name="submit" class="button button-primary">💾 儲存設定</button>
          <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="button">← 返回後台首頁</a>
          <a href="<?php echo esc_url($home_url); ?>" target="_blank" class="button">🔍 前台預覽</a>
        </div>
      </form>
    </div>
    <?php
}

/* 任何 setting 更新都清 mth_text() static cache（其實下次 request 自動 fresh，呢個 hook 主要係保險）*/
add_action('update_option_' . MTH_TEXT_OPT, function() {
    // PHP static 喺 next request 自然 reset，呢度唔需要做嘢，只係 placeholder hook
});
