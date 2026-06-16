<?php
// SEO 進階 schema：LocalBusiness（首頁）+ BreadcrumbList（產品/分類頁）
// 全部讀 mth_text() / 既有資料，保持同後台同步、可編輯。
if (!defined('ABSPATH')) exit;

/* ── LocalBusiness JSON-LD（首頁）── */
add_action('wp_head', function() {
    if (!is_front_page()) return;
    $logo = 'https://mengtakhong-mo.com/wp-content/uploads/2026/06/cropped-LOGO-270x270.jpg';
    $addr = function_exists('mth_text') ? mth_text('company_address', '澳門黑沙環慕拉士大馬路195號') : '澳門黑沙環慕拉士大馬路195號';
    $addr2= function_exists('mth_text') ? mth_text('company_address2', '南嶺工業大廈4樓F') : '';
    $tel1 = function_exists('mth_text') ? mth_text('company_phone1', '+853 28415128') : '+853 28415128';
    $tel2 = function_exists('mth_text') ? mth_text('company_phone2', '+853 28584838') : '';
    $email= function_exists('mth_text') ? mth_text('company_email', 'info@mengtakhong.com') : 'info@mengtakhong.com';
    $fb   = function_exists('mth_text') ? mth_text('social_fb_url', '') : '';
    $ig   = function_exists('mth_text') ? mth_text('social_ig_url', '') : '';

    $sameas = array_values(array_filter(array($fb, $ig)));
    $phones = array_values(array_filter(array($tel1, $tel2)));

    $data = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'LocalBusiness',
        '@id'        => 'https://mengtakhong-mo.com/#business',
        'name'       => '明德行國際有限公司',
        'alternateName' => 'Meng Tak Hong International Co., Ltd.',
        'description'=> '澳門本地酒水飲品批發代理，成立於1998年，代理威士忌、干邑、葡萄酒、日本酒、韓國飲品、烈酒力嬌等，服務餐廳、酒吧、酒店、超市。',
        'url'        => 'https://mengtakhong-mo.com',
        'logo'       => $logo,
        'image'      => $logo,
        'foundingDate' => '1998',
        'priceRange' => '$$',
        'currenciesAccepted' => 'MOP, HKD',
        'telephone'  => $phones ? $phones[0] : '',
        'email'      => $email,
        'address'    => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => trim($addr . ' ' . $addr2),
            'addressLocality' => 'Macau',
            'addressRegion'   => '澳門',
            'addressCountry'  => 'MO',
        ),
        'areaServed' => array('@type' => 'Place', 'name' => 'Macau 澳門'),
        'openingHoursSpecification' => array(array(
            '@type'       => 'OpeningHoursSpecification',
            'dayOfWeek'   => array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
            'opens'       => '09:00',
            'closes'      => '18:00',
        )),
    );
    if ($phones) $data['contactPoint'] = array_map(function($p){
        return array('@type'=>'ContactPoint','telephone'=>$p,'contactType'=>'sales','areaServed'=>'MO');
    }, $phones);
    if ($sameas) $data['sameAs'] = $sameas;

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}, 6);

/* ── BreadcrumbList JSON-LD（產品頁 + 分類頁）── */
add_action('wp_head', function() {
    $items = array();
    $home = home_url('/');

    if (is_singular('mth_product')) {
        global $post;
        if (!$post) return;
        $items[] = array('名稱'=>'首頁', 'url'=>$home);
        $terms = wp_get_post_terms($post->ID, 'mth_product_cat');
        if (!is_wp_error($terms) && $terms) {
            $items[] = array('名稱'=>$terms[0]->name, 'url'=>get_term_link($terms[0]));
        }
        $items[] = array('名稱'=>get_the_title($post->ID), 'url'=>get_permalink($post->ID));

    } elseif (is_tax('mth_product_cat')) {
        $term = get_queried_object();
        if (!$term) return;
        $items[] = array('名稱'=>'首頁', 'url'=>$home);
        $items[] = array('名稱'=>$term->name, 'url'=>get_term_link($term));
    } else {
        return;
    }

    $elements = array();
    foreach ($items as $i => $it) {
        $elements[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $it['名稱'],
            'item'     => $it['url'],
        );
    }
    $data = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $elements,
    );
    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}, 7);

/* ── Google Search Console 驗證 meta（攞到 code 後存 option 即生效）── */
add_action('wp_head', function() {
    $code = get_option('mth_gsc_verify', '');
    if ($code) echo '<meta name="google-site-verification" content="' . esc_attr($code) . '">' . "\n";
}, 1);
