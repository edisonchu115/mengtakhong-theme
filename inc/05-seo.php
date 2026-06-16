<?php
// SEO: Schema, robots, GA, html lang
if (!defined('ABSPATH')) exit;

/* 已發佈產品實數（5 分鐘 cache，避免每次 query）*/
function mth_product_count() {
    $c = get_transient('mth_seo_prod_count');
    if ($c === false) {
        $obj = wp_count_posts('mth_product');
        $c = $obj ? (int) $obj->publish : 0;
        set_transient('mth_seo_prod_count', $c, 5 * MINUTE_IN_SECONDS);
    }
    return $c;
}

/* ── Schema.org Product JSON-LD（單品頁自動產出）── */
add_action('wp_head', function() {
    if (!is_singular('mth_product')) return;
    global $post;
    if (!$post) return;
    $name_en = get_post_meta($post->ID, 'name_en', true);
    $spec    = get_post_meta($post->ID, 'spec', true);
    $abv     = get_post_meta($post->ID, 'abv', true);
    $country = get_post_meta($post->ID, 'origin_country', true);
    $img     = get_the_post_thumbnail_url($post->ID, 'large') ?: 'https://mengtakhong-mo.com/wp-content/uploads/2026/06/cropped-LOGO-270x270.jpg';
    $terms   = wp_get_post_terms($post->ID, 'mth_product_cat', array('fields' => 'names'));

    $data = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => get_the_title($post->ID) . ($name_en ? ' ' . $name_en : ''),
        'image'       => $img,
        'description' => trim(get_the_title($post->ID) . ' ' . $name_en . ' ' . $spec),
        'brand'       => array('@type' => 'Brand', 'name' => '明德行國際有限公司'),
        'category'    => $terms ? $terms[0] : '洋酒',
        'offers'      => array(
            '@type'           => 'Offer',
            'availability'    => 'https://schema.org/InStock',
            'priceCurrency'   => 'MOP',
            'price'           => '0',
            'url'             => get_permalink($post->ID),
            'seller'          => array('@type' => 'Organization', 'name' => '明德行國際有限公司'),
        ),
    );
    if ($country) {
        $data['countryOfOrigin'] = array('@type' => 'Country', 'name' => mth_country_name($country));
    }
    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}, 5);

/* ── EN 模式自動切換 html lang ── */
add_filter('language_attributes', function($output) {
    if (isset($_COOKIE['googtrans']) && strpos($_COOKIE['googtrans'], '/en') !== false) {
        $output = 'lang="en"';
    }
    return $output;
});

/* ── 自訂 robots.txt 規則 ── */
add_filter('robots_txt', function($output, $public) {
    if (!$public) return $output;
    $output .= "\nUser-agent: *\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /?s=\n";
    $output .= "Disallow: /search/\n";
    $output .= "Allow: /wp-admin/admin-ajax.php\n";
    $output .= "\nSitemap: " . home_url('/wp-sitemap.xml') . "\n";
    return $output;
}, 10, 2);

/* ── GA / GTM slot（將來貼 ID 即用）── */
add_action('wp_head', function() {
    $ga_id  = get_option('mth_ga4_id', '');  // 喺 wp_options 填 G-XXXXXXX 即生效
    $gtm_id = get_option('mth_gtm_id', '');  // GTM-XXXXXXX（如有）
    if ($ga_id) {
        echo "<!-- Google Analytics -->\n";
        echo "<script async src='https://www.googletagmanager.com/gtag/js?id=" . esc_attr($ga_id) . "'></script>\n";
        echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js($ga_id) . "');</script>\n";
    }
    if ($gtm_id) {
        echo "<!-- Google Tag Manager -->\n";
        echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . esc_js($gtm_id) . "');</script>\n";
    }
}, 2);

/* ── Document title ── */
add_filter('document_title_parts', function($parts) {
    if (is_singular('mth_product')) {
        $parts['site'] = '明德行國際有限公司 澳門';
    } else {
        $parts['tagline'] = '明德行國際有限公司';
    }
    return $parts;
});

/* ── SEO Meta Tags (完整版) ── */
add_action('wp_head', function() {
    global $post;
    $site_name  = '明德行國際有限公司';
    $logo_url   = 'https://mengtakhong-mo.com/wp-content/uploads/2026/06/cropped-LOGO-270x270.jpg';

    // 首頁
    if (is_front_page()) {
        $pc = mth_product_count();
        $desc = '明德行國際有限公司 Meng Tak Hong - 澳門本地酒水飲品批發代理，成立於1998年，超過25年行業經驗。代理威士忌、干邑、葡萄酒、日本酒、韓國飲品、中國白酒等' . ($pc ? $pc . '款' : '多款') . '產品，服務澳門餐廳、酒吧、酒店、超市。';
        $kw   = '明德行,Meng Tak Hong,mengtakhong,澳門酒水批發,澳門洋酒,澳門威士忌,澳門干邑,澳門葡萄酒,澳門飲品代理,酒水批發澳門,洋酒批發,威士忌批發,干邑批發,日本酒批發,韓國飲品,中國白酒,澳門B2B';
        $og_title = '明德行國際有限公司 | 澳門洋酒飲品批發代理';
        $og_desc  = '澳門本地酒水飲品批發代理，成立於1998年。' . ($pc ? $pc . '款' : '多款') . '產品，服務餐廳、酒吧、酒店、超市。';
        $og_img   = $logo_url;
        $og_url   = 'https://mengtakhong-mo.com';
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
        echo '<meta name="keywords" content="' . esc_attr($kw) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_desc) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($og_img) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($og_desc) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_img) . '">' . "\n";

    // 產品頁
    } elseif (is_singular('mth_product') && $post) {
        $name_en = get_post_meta($post->ID, 'name_en', true);
        $spec    = get_post_meta($post->ID, 'spec', true);
        $title   = get_the_title($post->ID);
        $desc    = trim($title . ($name_en ? ' ' . $name_en : '') . ($spec ? ' ' . $spec : '')) . ' - 明德行國際有限公司澳門代理，歡迎查詢批發價格。';
        $img     = get_the_post_thumbnail_url($post->ID, 'large') ?: $logo_url;
        $url     = get_permalink($post->ID);
        echo '<meta name="description" content="' . esc_attr(mb_substr($desc, 0, 160)) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . ' | ' . esc_attr($site_name) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(mb_substr($desc, 0, 160)) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<meta property="og:type" content="product">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . ' | ' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(mb_substr($desc, 0, 160)) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($img) . '">' . "\n";

    // 分類頁
    } elseif (is_tax('mth_product_cat')) {
        $term    = get_queried_object();
        $desc    = $term->name . ' - 明德行國際有限公司澳門批發代理，提供各類' . $term->name . '批發服務，歡迎餐廳、酒吧、酒店查詢。';
        $kw      = $term->name . ',澳門' . $term->name . '批發,明德行,' . $term->name . '代理';
        $url     = get_term_link($term);
        echo '<meta name="description" content="' . esc_attr(mb_substr($desc, 0, 160)) . '">' . "\n";
        echo '<meta name="keywords" content="' . esc_attr($kw) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($term->name) . ' | ' . esc_attr($site_name) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(mb_substr($desc, 0, 160)) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($logo_url) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary">' . "\n";

    // 其他頁面
    } else {
        $default_desc = '澳門洋酒飲品批發代理商，代理威士忌、干邑、日本產品、葡萄酒等' . (mth_product_count() ?: '多') . '款產品，服務澳門各大酒店、餐廳及零售業。歡迎B2B查詢。';
        echo '<meta name="description" content="' . esc_attr($default_desc) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title() ?: $site_name) . ' | ' . esc_attr($site_name) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($default_desc) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($logo_url) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink() ?: home_url('/')) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }
}, 1);

