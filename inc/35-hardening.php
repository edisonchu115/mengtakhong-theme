<?php
// 網站加固：安全 + 效能（全部 theme-level，安全可逆，唔郁外掛/wp-config）
if (!defined('ABSPATH')) exit;

/* ── 1. 補上缺嘅安全 HTTP headers（X-Frame/X-Content 已由 LiteSpeed 出，呢度只補缺）── */
add_action('send_headers', function () {
    if (is_admin()) return;
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');
    // HSTS：全站 HTTPS（CSP 已 upgrade-insecure-requests），6 個月，唔含 subdomains 較安全
    if (is_ssl()) header('Strict-Transport-Security: max-age=15768000');
});

/* ── 2. 移除 WordPress 版本外露（防針對版本嘅攻擊）── */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');
// 去走核心 CSS/JS 上嘅 ?ver=<wp版本>（保留主題自己嘅版本號做 cache bust）
function mth_strip_core_ver($src) {
    $v = get_bloginfo('version');
    if ($v && strpos($src, 'ver=' . $v) !== false) $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'mth_strip_core_ver', 9999);
add_filter('script_loader_src', 'mth_strip_core_ver', 9999);

/* ── 3. 停用 XML-RPC（常見暴力破解/DDoS 入口，公司冇用）── */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function ($h) { unset($h['X-Pingback']); return $h; });
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
add_filter('xmlrpc_methods', function () { return array(); });

/* ── 4. 封 REST API / ?author 使用者列舉（防偷 admin username）── */
add_filter('rest_endpoints', function ($endpoints) {
    foreach (array('/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)') as $k) {
        if (isset($endpoints[$k])) unset($endpoints[$k]);
    }
    return $endpoints;
});
add_action('template_redirect', function () {
    if (!is_admin() && !is_user_logged_in() && isset($_GET['author']) && (int) $_GET['author'] > 0) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

/* ── 5. 移除 wp-emoji（每頁慳 ~10KB JS/CSS + 一次外部請求）── */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('emoji_svg_url', '__return_false');
add_filter('tiny_mce_plugins', function ($p) { return is_array($p) ? array_diff($p, array('wpemoji')) : $p; });

/* ── 6. 移除多餘 wp_head 標籤（shortlink；唔郁 REST/oEmbed 避免影響功能）── */
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

/* ── 7. 限制文章修訂版本（慳資料庫，加快後台）── */
add_filter('wp_revisions_to_keep', function ($num, $post) { return 5; }, 10, 2);

/* ── 8. Heartbeat 減頻（慳 admin-ajax 負載，更穩）── */
add_filter('heartbeat_settings', function ($s) { $s['interval'] = 60; return $s; });

/* ── 9. 封 uploads 內 .json 直接讀取（metadata.json 等，低風險加固）── */
// 注意：靜態檔由伺服器處理，PHP 攔唔到；改用 robots 提示唔索引（實際封鎖要 server rule）
add_filter('robots_txt', function ($out, $public) {
    if ($public) $out .= "\nDisallow: /wp-content/uploads/*/metadata.json\n";
    return $out;
}, 11, 2);
