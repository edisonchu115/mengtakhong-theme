<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "明德行國際有限公司",
    "alternateName": ["Meng Tak Hong International Co., Ltd.", "mengtakhong", "明德行"],
    "foundingDate": "1998",
    "description": "澳門本地酒水飲品批發代理，超過25年行業經驗，代理威士忌、干邑、葡萄酒、日本酒等683款產品",
    "url": "https://mengtakhong-mo.com",
    "logo": "https://mengtakhong-mo.com/wp-content/uploads/2026/06/cropped-LOGO-270x270.jpg",
    "image": "https://mengtakhong-mo.com/wp-content/uploads/2026/06/cropped-LOGO-270x270.jpg",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "黑沙環慕拉士大馬路195號南嶺工業大廈4樓F座",
      "addressLocality": "澳門",
      "addressRegion": "澳門",
      "addressCountry": "MO"
    },
    "telephone": ["+85328415128", "+85328584838"],
    "email": "info@mengtakhong.com",
    "sameAs": [
      "https://www.facebook.com/profile.php?id=61555744448402",
      "https://www.instagram.com/mengtakhong.mo/"
    ],
    "areaServed": { "@type": "City", "name": "澳門" },
    "knowsAbout": ["威士忌", "干邑", "葡萄酒", "日本酒", "韓國飲品", "中國白酒", "烈酒", "啤酒"]
  }
  </script>
  <!-- Google Translate (visually hidden but active) -->
  <div id="google_translate_element" style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;"></div>
  <script>
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'zh-TW',
      includedLanguages: 'en',
      autoDisplay: false,
      gaTrack: false
    }, 'google_translate_element');
  }
  </script>
  <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <style>
  .site-header { position: sticky; top: 0; z-index: 9999; width: 100%; }
  .legal-bar {
    background: #111111; padding: 5px 0; text-align: center; width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 10px;
  }
  .legal-text { font-size: 11px; color: #777; letter-spacing: 0.8px; }
  .legal-deco { color: #D4AF37; font-size: 8px; }
  .navbar { position: static !important; }
  /* Mobile controls hidden on desktop */
  .mobile-nav-controls { display: none; }
  /* Language toggle button */
  .lang-toggle-btn {
    background: transparent;
    border: 1px solid rgba(212,175,55,.5);
    color: rgba(255,255,255,.8);
    font-size: .75rem;
    letter-spacing: .06em;
    padding: 5px 11px;
    border-radius: 20px;
    cursor: pointer;
    font-family: inherit;
    white-space: nowrap;
    transition: background .2s, color .2s, border-color .2s;
    -webkit-tap-highlight-color: transparent;
    flex-shrink: 0;
  }
  .lang-toggle-btn:hover { background: rgba(212,175,55,.12); color: #D4AF37; border-color: #D4AF37; }
  .lang-toggle-btn.en-active { background: #D4AF37; color: #1C1C1C; border-color: #D4AF37; font-weight: 700; }
  /* Hide Google Translate top banner & iframe */
  .goog-te-banner-frame, .skiptranslate { display: none !important; visibility: hidden !important; }
  body { top: 0 !important; }
  .goog-te-gadget { display: none !important; }
  </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="legal-bar">
    <span class="legal-deco">◆</span>
    <span class="legal-text">根據第 6/2023 號法律的規定，禁止向未滿十八歲人士銷售或提供酒精飲料</span>
    <span class="legal-deco">◆</span>
  </div>

  <nav class="navbar">
    <a href="<?php echo home_url('/'); ?>" class="navbar-logo">
      <img src="<?php echo content_url('/uploads/LOGO.jpg'); ?>" alt="明德行" onerror="this.style.display='none'">
      <div>
        <div class="logo-text" translate="no">明德行國際有限公司</div>
        <div class="logo-sub" translate="no">MENG TAK HONG International Limited</div>
      </div>
    </a>
    <ul class="nav-menu">
      <li><a href="<?php echo home_url('/'); ?>" <?php if(is_front_page()) echo 'class="active"'; ?>>首頁</a></li>
      <li>
        <a href="#">產品分類 ▾</a>
        <ul class="dropdown">
          <li><a href="<?php echo home_url('/product-category/whisky/'); ?>">威士忌</a></li>
          <li><a href="<?php echo home_url('/product-category/cognac/'); ?>">干邑/拔蘭地</a></li>
          <li><a href="<?php echo home_url('/product-category/japan/'); ?>">日本產品</a></li>
          <li><a href="<?php echo home_url('/product-category/korea/'); ?>">韓國/亞洲飲品</a></li>
          <li><a href="<?php echo home_url('/product-category/wine/'); ?>">葡萄酒/香檳</a></li>
          <li><a href="<?php echo home_url('/product-category/liqueur-gin-rum/'); ?>">力嬌/琴酒/冧酒</a></li>
          <li><a href="<?php echo home_url('/product-category/chinese/'); ?>">中國白酒</a></li>
          <li><a href="<?php echo home_url('/product-category/beer-beverages/'); ?>">啤酒/飲料</a></li>
        </ul>
      </li>
      <li><a href="<?php echo home_url('/brands/'); ?>" <?php if(is_page('brands')) echo 'class="active"'; ?>>品牌</a></li>
      <li><a href="<?php echo home_url('/about/'); ?>" <?php if(is_page('about')) echo 'class="active"'; ?>>關於我們</a></li>
      <li><a href="<?php echo home_url('/contact/'); ?>" <?php if(is_page('contact')) echo 'class="active"'; ?>>聯絡我們</a></li>
    </ul>
    <form class="navbar-search" action="<?php echo home_url('/'); ?>" method="get" role="search">
      <input type="text" name="s" id="global-search" placeholder="搜尋產品..." autocomplete="off">
      <input type="hidden" name="post_type" value="mth_product">
      <button type="submit">&#128269;</button>
    </form>
    <!-- Language toggle -->
    <button class="lang-toggle-btn" id="lang-toggle-btn" aria-label="切換語言">中/EN</button>
    <!-- Mobile: search icon + hamburger (hidden on desktop via CSS) -->
    <div class="mobile-nav-controls">
      <button class="mobile-search-btn-nav" id="mobile-search-btn-nav" aria-label="搜尋">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </button>
      <button class="hamburger-btn" id="hamburger-btn" aria-label="開啟選單" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>
</header>

<!-- Mobile Nav Overlay (slides from left, z-index below age gate) -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay" aria-hidden="true">
  <div class="mobile-nav-top">
    <a href="<?php echo home_url('/'); ?>" class="mobile-nav-logo-wrap">
      <img src="<?php echo content_url('/uploads/LOGO.jpg'); ?>" alt="明德行" onerror="this.style.display='none'">
      <span class="mobile-nav-logo-name" translate="no">明德行國際有限公司</span>
    </a>
    <button class="mobile-nav-close" id="mobile-nav-close" aria-label="關閉選單">✕</button>
  </div>
  <div class="mobile-nav-body">
    <nav class="mobile-nav-main-links">
      <a href="<?php echo home_url('/'); ?>">首頁 <span>›</span></a>
      <a href="<?php echo home_url('/brands/'); ?>">品牌 <span>›</span></a>
      <a href="<?php echo home_url('/about/'); ?>">關於我們 <span>›</span></a>
      <a href="<?php echo home_url('/contact/'); ?>">聯絡我們 <span>›</span></a>
    </nav>
    <div class="mobile-nav-section-label">產品分類</div>
    <div class="mobile-nav-cats">
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/whisky/'); ?>">威士忌</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/cognac/'); ?>">干邑/拔蘭地</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/japan/'); ?>">日本產品</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/korea/'); ?>">韓國/亞洲飲品</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/wine/'); ?>">葡萄酒/香檳</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/liqueur-gin-rum/'); ?>">力嬌/琴酒/冧酒</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/chinese/'); ?>">中國白酒</a>
      <a class="mobile-nav-cat" href="<?php echo home_url('/product-category/beer-beverages/'); ?>">啤酒/飲料</a>
    </div>
    <div class="mobile-nav-social">
      <a class="snav-fb" href="https://www.facebook.com/profile.php?id=61555744448402" target="_blank" rel="noopener noreferrer">Facebook</a>
      <a class="snav-ig" href="https://www.instagram.com/mengtakhong.mo/" target="_blank" rel="noopener noreferrer">Instagram</a>
    </div>
    <div style="margin-top:16px;">
      <button id="lang-toggle-btn-mobile" style="width:100%;background:transparent;border:1px solid rgba(212,175,55,.4);color:rgba(255,255,255,.75);font-size:.9rem;padding:12px;border-radius:8px;cursor:pointer;font-family:inherit;letter-spacing:.06em;" aria-label="切換語言">中 / EN</button>
    </div>
  </div>
</div>

<!-- Mobile Search Overlay -->
<div class="mobile-search-overlay" id="mobile-search-overlay" aria-hidden="true">
  <div class="mobile-search-header">
    <span class="mobile-search-title">搜尋產品</span>
    <button class="mobile-search-cancel-btn" id="mobile-search-cancel">取消</button>
  </div>
  <form class="mobile-search-form" action="<?php echo home_url('/'); ?>" method="get">
    <input type="hidden" name="post_type" value="mth_product">
    <input class="mobile-search-input" type="text" name="s" id="mobile-search-input" placeholder="輸入產品名稱..." autocomplete="off">
    <button class="mobile-search-submit" type="submit">搜尋</button>
  </form>
</div>
