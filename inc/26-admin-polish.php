<?php
// Admin polish: thumbnail hover zoom + search hint + login screen branding
if (!defined('ABSPATH')) exit;

/* ── F4 + F10: 產品列表 thumbnail hover 放大 + 搜尋框 hint ── */
add_action('admin_head-edit.php', function() {
    global $typenow;
    if ($typenow !== 'mth_product') return;
    ?>
    <style>
      /* Thumbnail hover zoom */
      .wp-list-table .column-mth_thumb { position:relative; width:60px; }
      .wp-list-table .column-mth_thumb img { transition: transform .15s; cursor:zoom-in; }
      .wp-list-table .column-mth_thumb img:hover {
        transform: scale(4); transform-origin: left center;
        z-index: 9999; position:relative;
        box-shadow: 0 4px 20px rgba(0,0,0,.25); background:#fff; border-color:#d4af37 !important;
      }
      /* 搜尋框寬一啲 + placeholder */
      .post-type-mth_product #posts-search-input { min-width: 240px; }
    </style>
    <script>
    jQuery(function($){
      var $s = $('#post-search-input');
      if ($s.length) $s.attr('placeholder', '中文名 / 英文名 / 規格 / ABV');
    });
    </script>
    <?php
});

/* ── F8: Login screen branding（明德行 logo + 顏色）── */
add_action('login_enqueue_scripts', function() {
    ?>
    <style>
      body.login { background: linear-gradient(135deg,#1C1C1C 0%,#2a2520 100%); }
      .login h1 a {
        background: none !important;
        color: #D4AF37 !important;
        text-indent: 0 !important;
        font-family: 'Noto Serif TC', serif;
        font-size: 28px !important;
        font-weight: 900 !important;
        width: auto !important;
        height: auto !important;
        line-height: 1.3 !important;
        padding: 10px 0 !important;
        letter-spacing: .05em;
      }
      .login h1 a::after { content: '明德行'; display:block; font-size:14px; color:#aaa; font-weight:400; letter-spacing:.15em; margin-top:4px; }
      .login form { background:#fff; border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,.4); }
      .login #backtoblog a, .login #nav a { color:#d4af37 !important; }
      .login #backtoblog a:hover, .login #nav a:hover { color:#fff !important; }
      .wp-core-ui .button-primary { background:#1C1C1C !important; border-color:#1C1C1C !important; }
      .wp-core-ui .button-primary:hover { background:#D4AF37 !important; border-color:#D4AF37 !important; color:#1C1C1C !important; }
    </style>
    <?php
});
add_filter('login_headertext', function() { return 'Meng Tak Hong'; });
add_filter('login_headerurl', function()  { return home_url('/'); });

/* ── Admin footer 加版本 tag（方便 debug）── */
add_filter('admin_footer_text', function($txt) {
    return $txt . ' · <span style="color:#999;">明德行主題 v2.5</span>';
});
