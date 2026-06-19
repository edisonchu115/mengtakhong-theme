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
                'stat1_num'   => array('label'=>'統計 1 數字','default'=>'880'),
                'stat1_label' => array('label'=>'統計 1 標籤','default'=>'款產品'),
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

        'about_hero' => array(
            'title' => '📖 關於我們頁 — Hero',
            'desc'  => '/about/ 頁面頂部',
            'fields' => array(
                'about_eyebrow'  => array('label'=>'頂部英文','default'=>'About Us'),
                'about_title_1'  => array('label'=>'標題第 1 段','default'=>'關於'),
                'about_title_2'  => array('label'=>'標題第 2 段 (金色)','default'=>'明德行'),
                'about_subtitle' => array('label'=>'副標題','default'=>'澳門本地專業洋酒飲品批發代理，深耕市場逾廿五年','type'=>'textarea'),
            ),
        ),
        'about_stats' => array(
            'title' => '📖 關於我們頁 — 5 個統計數字',
            'desc'  => '5 個 box，全部手動填',
            'fields' => array(
                'about_stat1_num'=>array('label'=>'統計 1 數字','default'=>'1998'),
                'about_stat1_label'=>array('label'=>'統計 1 標籤','default'=>'年成立'),
                'about_stat2_num'=>array('label'=>'統計 2 數字','default'=>'25+'),
                'about_stat2_label'=>array('label'=>'統計 2 標籤','default'=>'年行業經驗'),
                'about_stat3_num'=>array('label'=>'統計 3 數字','default'=>'880'),
                'about_stat3_label'=>array('label'=>'統計 3 標籤','default'=>'款產品'),
                'about_stat4_num'=>array('label'=>'統計 4 數字','default'=>'8'),
                'about_stat4_label'=>array('label'=>'統計 4 標籤','default'=>'大產品系列'),
                'about_stat5_num'=>array('label'=>'統計 5 數字','default'=>'B2B'),
                'about_stat5_label'=>array('label'=>'統計 5 標籤','default'=>'專業批發服務'),
            ),
        ),
        'about_body' => array(
            'title' => '📖 關於我們頁 — 內文',
            'desc'  => '公司簡介、業務、客戶、聯絡',
            'fields' => array(
                'about_intro_title' => array('label'=>'第 1 段標題','default'=>'公司簡介'),
                'about_intro_p1'    => array('label'=>'第 1 段 — 段落 1','default'=>'明德行國際有限公司（Meng Tak Hong International Co., Ltd.）於1998年在澳門成立，是澳門本地歷史最悠久的洋酒及飲品批發代理商之一。','type'=>'textarea'),
                'about_intro_p2'    => array('label'=>'第 1 段 — 段落 2','default'=>'多年來，我們致力為澳門各類餐飲及零售業客戶提供穩定、優質的產品供應，建立了廣泛的合作網絡及良好的市場口碑。','type'=>'textarea'),
                'about_biz_title'   => array('label'=>'第 2 段標題','default'=>'主要業務'),
                'about_biz_items'   => array('label'=>'業務列表 (每行一項)','default'=>"蘇格蘭、愛爾蘭、美國、日本單一麥芽及調和威士忌代理\n法國干邑及拔蘭地進口\n優質葡萄酒及香檳（法國、澳洲、葡萄牙、智利等）\n日本清酒、燒酎、日本威士忌\n韓國燒酒（真露、舞鶴等）及亞洲飲品食品\n琴酒、伏特加、冧酒、龍舌蘭及各式力嬌酒\n中國白酒（茅台、習酒等）\n各式啤酒及非酒精飲料",'type'=>'textarea'),
                'about_clients_title' => array('label'=>'第 3 段標題','default'=>'客戶群體'),
                'about_clients_intro' => array('label'=>'第 3 段引言','default'=>'我們主要服務 B2B 批發客戶，包括：'),
                'about_clients_items' => array('label'=>'客戶列表 (每行一項)','default'=>"酒店、度假村及高級餐廳\n酒吧、夜店及娛樂場所\n超市、便利店及士多\n企業及活動採購\n私人買酒用家",'type'=>'textarea'),
                'about_contact_title' => array('label'=>'第 4 段標題','default'=>'聯絡我們'),
                'about_contact_p'     => array('label'=>'第 4 段內文','default'=>'如需查詢箱價、最新優惠或有任何採購需要，歡迎致電或透過以下方式聯絡我們的銷售團隊。','type'=>'textarea'),
                'about_contact_btn'   => array('label'=>'按鈕文字','default'=>'聯絡我們'),
            ),
        ),

        'contact_hero' => array(
            'title' => '📞 聯絡頁 — Hero',
            'desc'  => '/contact/ 頁面頂部',
            'fields' => array(
                'contact_eyebrow'  => array('label'=>'頂部英文','default'=>'Contact Us'),
                'contact_title_1'  => array('label'=>'標題第 1 段','default'=>'聯絡'),
                'contact_title_2'  => array('label'=>'標題第 2 段 (金色)','default'=>'我們'),
                'contact_subtitle' => array('label'=>'副標題','default'=>'歡迎 B2B 客戶查詢批發報價及補貨事宜','type'=>'textarea'),
            ),
        ),
        'contact_body' => array(
            'title' => '📞 聯絡頁 — 內文',
            'desc'  => '聯絡資料區（地址/電話/Email/辦公時間）',
            'fields' => array(
                'contact_info_title' => array('label'=>'區塊標題','default'=>'聯絡資料'),
                'contact_info_subtitle' => array('label'=>'區塊副標題（公司英文名）','default'=>'Meng Tak Hong International Co., Ltd.'),
                'contact_addr_label'=> array('label'=>'地址欄 label','default'=>'地址'),
                'contact_addr_val'  => array('label'=>'地址欄內容','default'=>'澳門黑沙環慕拉士大馬路195號 南嶺工業大廈4樓F座','type'=>'textarea'),
                'contact_phone_label'=> array('label'=>'電話欄 label','default'=>'電話'),
                'contact_email_label'=> array('label'=>'電郵欄 label','default'=>'電郵'),
                'contact_hours_label'=> array('label'=>'辦公時間 label','default'=>'辦公時間'),
                'contact_hours_val'  => array('label'=>'辦公時間內容','default'=>'週一至週六 9:00 – 18:00'),
                'contact_show_wa'    => array('label'=>'顯示 WhatsApp 按鈕？(填 yes/no)','default'=>'no'),
                'contact_wa_url'     => array('label'=>'WhatsApp 連結','default'=>'https://wa.me/85366687448'),
                'contact_map_open_label' => array('label'=>'地圖「在 Google Maps 開啟」按鈕字','default'=>'在 Google Maps 開啟'),
            ),
        ),

        'brands_hero' => array(
            'title' => '🏷️ 品牌頁',
            'desc'  => '/brands/ 頁面',
            'fields' => array(
                'brands_title'    => array('label'=>'頁面標題','default'=>'代理品牌'),
                'brands_subtitle' => array('label'=>'副標題','default'=>'Our Brands · 明德行代理及進口產品品牌'),
                'brands_empty'    => array('label'=>'未有品牌時嘅提示','default'=>'品牌資料載入中，請稍後再試。'),
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
