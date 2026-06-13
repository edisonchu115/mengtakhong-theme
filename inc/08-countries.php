<?php
// Country data + flag helpers
if (!defined('ABSPATH')) exit;

/* ── 原產國資料表（單一來源，將來加國家只需改呢度）── */
function mth_countries() {
    return array(
        // 'flag' = emoji（後台 / 純文字用）；'iso' = flagcdn ISO code（前台圖片用）
        'scotland'  => array('flag' => "\u{1F3F4}\u{E0067}\u{E0062}\u{E0073}\u{E0063}\u{E0074}\u{E007F}", 'zh' => '蘇格蘭',   'iso' => 'gb-sct'),
        'ireland'   => array('flag' => '🇮🇪', 'zh' => '愛爾蘭', 'iso' => 'ie'),
        'canada'    => array('flag' => '🇨🇦', 'zh' => '加拿大', 'iso' => 'ca'),
        'usa'       => array('flag' => '🇺🇸', 'zh' => '美國',   'iso' => 'us'),
        'france'    => array('flag' => '🇫🇷', 'zh' => '法國',   'iso' => 'fr'),
        'spain'     => array('flag' => '🇪🇸', 'zh' => '西班牙', 'iso' => 'es'),
        'portugal'  => array('flag' => '🇵🇹', 'zh' => '葡萄牙', 'iso' => 'pt'),
        'italy'     => array('flag' => '🇮🇹', 'zh' => '意大利', 'iso' => 'it'),
        'chile'     => array('flag' => '🇨🇱', 'zh' => '智利',   'iso' => 'cl'),
        'australia' => array('flag' => '🇦🇺', 'zh' => '澳洲',   'iso' => 'au'),
        'japan'     => array('flag' => '🇯🇵', 'zh' => '日本',   'iso' => 'jp'),
        'china'     => array('flag' => '🇨🇳', 'zh' => '中國',   'iso' => 'cn'),
        'korea'     => array('flag' => '🇰🇷', 'zh' => '韓國',   'iso' => 'kr'),
        'taiwan'    => array('flag' => '🇹🇼', 'zh' => '台灣',   'iso' => 'tw'),
        'thailand'  => array('flag' => '🇹🇭', 'zh' => '泰國',   'iso' => 'th'),
        'vietnam'   => array('flag' => '🇻🇳', 'zh' => '越南',   'iso' => 'vn'),
    );
}

function mth_country_flag($key) {
    $c = mth_countries();
    return isset($c[$key]) ? $c[$key]['flag'] : '';
}

function mth_country_name($key) {
    $c = mth_countries();
    return isset($c[$key]) ? $c[$key]['zh'] : '';
}

// 取得 flagcdn ISO code
function mth_country_iso($key) {
    $c = mth_countries();
    return isset($c[$key]) && !empty($c[$key]['iso']) ? $c[$key]['iso'] : '';
}

// 返回 flag 圖片 URL（前台用，避免 iOS emoji 白底框問題）
function mth_country_flag_url($key, $width = 40) {
    $iso = mth_country_iso($key);
    if (!$iso) return '';
    return 'https://flagcdn.com/w' . (int) $width . '/' . $iso . '.png';
}

// 返回完整 <img> HTML（前台 prod-flag 用）
function mth_country_flag_img($key, $width = 40) {
    $url = mth_country_flag_url($key, $width);
    if (!$url) return '';
    $name = mth_country_name($key);
    return '<img class="mth-flag-img" src="' . esc_url($url) . '" alt="' . esc_attr($name) . '" loading="lazy">';
}

