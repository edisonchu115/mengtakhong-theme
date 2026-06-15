<?php get_header(); ?>

<div class="about-hero">
  <div class="eyebrow" translate="no"><?php echo esc_html(mth_text('about_eyebrow', 'About Us')); ?></div>
  <h1><?php echo esc_html(mth_text('about_title_1', '關於')); ?><span><?php echo esc_html(mth_text('about_title_2', '明德行')); ?></span></h1>
  <p><?php echo esc_html(mth_text('about_subtitle', '澳門本地專業洋酒飲品批發代理，深耕市場逾廿五年')); ?></p>
</div>

<div class="about-body">
  <div class="stat-grid">
    <div class="stat-box"><div class="num"><?php echo esc_html(mth_text('about_stat1_num', '1998')); ?></div><div class="label"><?php echo esc_html(mth_text('about_stat1_label', '年成立')); ?></div></div>
    <div class="stat-box"><div class="num"><?php echo esc_html(mth_text('about_stat2_num', '25+')); ?></div><div class="label"><?php echo esc_html(mth_text('about_stat2_label', '年行業經驗')); ?></div></div>
    <div class="stat-box"><div class="num"><?php echo esc_html(mth_text('about_stat3_num', '234')); ?>+</div><div class="label"><?php echo esc_html(mth_text('about_stat3_label', '款產品')); ?></div></div>
    <div class="stat-box"><div class="num"><?php echo esc_html(mth_text('about_stat4_num', '8')); ?></div><div class="label"><?php echo esc_html(mth_text('about_stat4_label', '大產品系列')); ?></div></div>
    <div class="stat-box"><div class="num"><?php echo esc_html(mth_text('about_stat5_num', 'B2B')); ?></div><div class="label"><?php echo esc_html(mth_text('about_stat5_label', '專業批發服務')); ?></div></div>
  </div>

  <h2><?php echo esc_html(mth_text('about_intro_title', '公司簡介')); ?></h2>
  <p><?php echo esc_html(mth_text('about_intro_p1', '明德行國際有限公司（Meng Tak Hong International Co., Ltd.）於1998年在澳門成立，是澳門本地歷史最悠久的洋酒及飲品批發代理商之一。')); ?></p>
  <p><?php echo esc_html(mth_text('about_intro_p2', '多年來，我們致力為澳門各類餐飲及零售業客戶提供穩定、優質的產品供應，建立了廣泛的合作網絡及良好的市場口碑。')); ?></p>

  <h2><?php echo esc_html(mth_text('about_biz_title', '主要業務')); ?></h2>
  <ul>
    <?php
    $biz_default = "蘇格蘭、愛爾蘭、美國、日本單一麥芽及調和威士忌代理\n法國干邑及拔蘭地進口\n優質葡萄酒及香檳（法國、澳洲、葡萄牙、智利等）\n日本清酒、燒酎、日本威士忌\n韓國燒酒（真露、舞鶴等）及亞洲飲品食品\n琴酒、伏特加、冧酒、龍舌蘭及各式力嬌酒\n中國白酒（茅台、習酒等）\n各式啤酒及非酒精飲料";
    $biz_items = preg_split('/\r?\n/', mth_text('about_biz_items', $biz_default));
    foreach ($biz_items as $item):
      $item = trim($item);
      if (!$item) continue;
    ?>
    <li><?php echo esc_html($item); ?></li>
    <?php endforeach; ?>
  </ul>

  <h2><?php echo esc_html(mth_text('about_clients_title', '客戶群體')); ?></h2>
  <p><?php echo esc_html(mth_text('about_clients_intro', '我們主要服務 B2B 批發客戶，包括：')); ?></p>
  <ul>
    <?php
    $cli_default = "酒店、度假村及高級餐廳\n酒吧、夜店及娛樂場所\n超市、便利店及士多\n企業及活動採購\n私人買酒用家";
    $cli_items = preg_split('/\r?\n/', mth_text('about_clients_items', $cli_default));
    foreach ($cli_items as $item):
      $item = trim($item);
      if (!$item) continue;
    ?>
    <li><?php echo esc_html($item); ?></li>
    <?php endforeach; ?>
  </ul>

  <h2><?php echo esc_html(mth_text('about_contact_title', '聯絡我們')); ?></h2>
  <p><?php echo esc_html(mth_text('about_contact_p', '如需查詢箱價、最新優惠或有任何採購需要，歡迎致電或透過以下方式聯絡我們的銷售團隊。')); ?></p>
  <div style="margin-top:24px;">
    <a href="<?php echo home_url('/contact/'); ?>" class="btn-gold"><?php echo esc_html(mth_text('about_contact_btn', '聯絡我們')); ?></a>
  </div>
</div>

<?php get_footer(); ?>
