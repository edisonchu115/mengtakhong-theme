<?php get_header(); ?>

<div class="about-hero">
  <div class="eyebrow" translate="no">About Us</div>
  <h1>關於<span>明德行</span></h1>
  <p>澳門本地專業洋酒飲品批發代理，深耕市場逾廿五年</p>
</div>

<div class="about-body">
  <div class="stat-grid">
    <div class="stat-box"><div class="num">1998</div><div class="label">年成立</div></div>
    <div class="stat-box"><div class="num">25+</div><div class="label">年行業經驗</div></div>
    <div class="stat-box"><div class="num"><?php echo wp_count_posts('mth_product')->publish ?: '683'; ?>+</div><div class="label">款產品</div></div>
    <div class="stat-box"><div class="num">8</div><div class="label">大產品系列</div></div>
    <div class="stat-box"><div class="num">B2B</div><div class="label">專業批發服務</div></div>
  </div>

  <h2>公司簡介</h2>
  <p>明德行國際有限公司（Meng Tak Hong International Co., Ltd.）於1998年在澳門成立，是澳門本地歷史最悠久的洋酒及飲品批發代理商之一。</p>
  <p>多年來，我們致力為澳門各類餐飲及零售業客戶提供穩定、優質的產品供應，建立了廣泛的合作網絡及良好的市場口碑。</p>

  <h2>主要業務</h2>
  <ul>
    <li>蘇格蘭、愛爾蘭、美國、日本單一麥芽及調和威士忌代理</li>
    <li>法國干邑及拔蘭地進口</li>
    <li>優質葡萄酒及香檳（法國、澳洲、葡萄牙、智利等）</li>
    <li>日本清酒、燒酎、日本威士忌</li>
    <li>韓國燒酒（真露、舞鶴等）及亞洲飲品食品</li>
    <li>琴酒、伏特加、冧酒、龍舌蘭及各式力嬌酒</li>
    <li>中國白酒（茅台、習酒等）</li>
    <li>各式啤酒及非酒精飲料</li>
  </ul>

  <h2>客戶群體</h2>
  <p>我們主要服務 B2B 批發客戶，包括：</p>
  <ul>
    <li>酒店、度假村及高級餐廳</li>
    <li>酒吧、夜店及娛樂場所</li>
    <li>超市、便利店及士多</li>
    <li>企業及活動採購</li>
    <li>私人買酒用家</li>
  </ul>

  <h2>聯絡我們</h2>
  <p>如需查詢箱價、最新優惠或有任何採購需要，歡迎致電或透過以下方式聯絡我們的銷售團隊。</p>
  <div style="margin-top:24px;">
    <a href="<?php echo home_url('/contact/'); ?>" class="btn-gold">聯絡我們</a>
  </div>
</div>

<?php get_footer(); ?>
