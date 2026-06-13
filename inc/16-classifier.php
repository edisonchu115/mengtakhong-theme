<?php
// Brand/Type classifier + maps
if (!defined('ABSPATH')) exit;

/* ──────────────────────────────────────────────
   日本產品分類器（品牌 + 種類）
   無需 DB migration — 從產品標題即時分類
   ────────────────────────────────────────────── */

function mth_japan_brand_map_default() {
    return array(
        'hakutsuru'      => array('label' => '白鶴酒造 Hakutsuru',         'patterns' => array('白鶴')),
        'mars'           => array('label' => 'Mars 本坊酒造',              'patterns' => array('信州駒之岳', '信州岩井', '信州津貫', '信州幸運貓', '屋久島', '越百', '和美人', '岩井傳統', '駒之岳', '極醇')),
        'kobe'           => array('label' => 'KOBE Distillerie 神戶蒸餾所', 'patterns' => array('神戶藍神', '神戶梅酒', '神戶雅瑪', '神戶印路', '神戶精選', '神戶15')),
        'sangaria'       => array('label' => 'Sangaria 三佳利',             'patterns' => array('三佳利')),
        'sakurao'        => array('label' => 'Sakurao 櫻尾蒸溜所',          'patterns' => array('櫻尾', '桜尾', '戶河內')),
        'akkeshi'        => array('label' => 'Akkeshi 厚岸蒸溜所',          'patterns' => array('厚岸')),
        'otokoyama'      => array('label' => 'Otokoyama 男山酒造',          'patterns' => array('男山', '北稻穗')),
        'hamafukutsuru'  => array('label' => '小山本家 浜福鶴',             'patterns' => array('浜福鶴')),
        'kyohime'        => array('label' => '京姬酒造',                    'patterns' => array('京姬', '京都(京姬)', '京都（匠', '京都(匠')),
        'yomeishu'       => array('label' => '養命酒製造',                  'patterns' => array('養命酒製造', '香雪琴酒', '香の森', '蜜柑利口酒', '特調柚子味', '特調生薑味')),
        'asahikawa'      => array('label' => 'Etsu / 東京之夜（旭川）',      'patterns' => array('悅', 'Etsu', 'ETSU', '東京之夜')),
        'kanosuke'       => array('label' => '嘉之助蒸溜所',                'patterns' => array('嘉之助')),
        'okinawa'        => array('label' => '沖繩 Okinawa',                'patterns' => array('沖繩')),
        'komasa'         => array('label' => '小正釀造（櫻島）',            'patterns' => array('桜島', '櫻島')),
        'kaikyo'         => array('label' => 'Kaikyō 海峽蒸溜所',           'patterns' => array('波門崎')),
        'fuyu'           => array('label' => '冬日 FUYU',                   'patterns' => array('冬日')),
        'kitaakita'      => array('label' => '北秋田',                      'patterns' => array('北秋田')),
        'hokkaido_wine'  => array('label' => '北海道酒造（葡萄酒）',        'patterns' => array('北海道')),
        'fujihaku'       => array('label' => '富士白蒸餾所',                'patterns' => array('富士白', '槙羅漢')),
        'hinotori'       => array('label' => '火鳳凰 Hinotori',             'patterns' => array('火鳳凰')),
        'junenmyo'       => array('label' => '十年明 Junenmyo',             'patterns' => array('十年明')),
        'nirasaki'       => array('label' => '韮崎',                        'patterns' => array('韮崎')),
    );
}
function mth_japan_brand_map() {
    $saved = get_option('mth_japan_brand_map_v1', null);
    if (is_array($saved) && !empty($saved)) return $saved;
    return mth_japan_brand_map_default();
}

function mth_japan_type_map_default() {
    return array(
        // 主類（互相獨立）
        'whisky'      => array('label' => '🥃 威士忌',     'patterns' => array('威士忌', '麥芽', 'whisky', 'Whisky')),
        'gin'         => array('label' => '🍸 琴酒（氈酒）','patterns' => array('琴酒', '氈酒', '杜松子', 'gin', 'Gin', 'GIN')),
        'umeshu'      => array('label' => '🌸 梅酒',       'patterns' => array('梅酒')),
        'brandy'      => array('label' => '🥃 拔蘭地',     'patterns' => array('拔蘭地', '白蘭地', 'Brandy', 'brandy')),
        'wine'        => array('label' => '🍇 葡萄酒',     'patterns' => array('葡萄酒', '葡萄白', 'Wine', 'wine', 'WINE')),  // 精準，避免match「葡萄味」soda
        'liqueur'     => array('label' => '🍑 利口酒',     'patterns' => array('利口酒', '柚子酒', '桃味', '蜜柑', '蜂蜜花梨', '花梨酒')),
        'jelly'       => array('label' => '🍹 果凍酒',     'patterns' => array('果凍')),
        'soft_drink'  => array('label' => '🥤 無酒精飲料', 'patterns' => array('飲料', '炭酸', '碳酸', '波子汽水', '綠茶', '牛奶', '果汁')),
        // 清酒系列（umbrella + 細分）
        'sake'             => array('label' => '🍶 清酒（總類）',  'patterns' => array('清酒', '純米', '吟釀', '大吟', '上撰', '名取酒', '生貯', '翔雲', '山田錦', '北稻穗', '無濾過', '備前雄町')),
        'junmai'           => array('label' => '🍶 純米酒',        'patterns' => array('純米酒')),  // 不會 match 純米吟釀/純米大吟釀
        'junmai_ginjo'     => array('label' => '🍶 純米吟釀',      'patterns' => array('純米吟釀')),
        'junmai_daiginjo'  => array('label' => '🍶 純米大吟釀',    'patterns' => array('純米大吟釀')),
        'daiginjo'         => array('label' => '🍶 大吟釀',        'patterns' => array('大吟釀')),  // 同時 match 純米大吟釀（hierarchy）
        'nigori'           => array('label' => '🍶 濁酒',          'patterns' => array('濁酒', 'Nigori', 'NIGORI', 'さゆり', 'SAYURI')),
        'karakuchi'        => array('label' => '🍶 大辛口（極辛味）','patterns' => array('大辛口')),
    );
}
function mth_japan_type_map() {
    $saved = get_option('mth_japan_type_map_v1', null);
    if (is_array($saved) && !empty($saved)) return $saved;
    return mth_japan_type_map_default();
}

// ── Helper：呢個產品係咪日本分類？（backward-compat）──
function mth_is_japan_product($post_id) {
    return has_term('japan', 'mth_product_cat', $post_id);
}

// ── Helper：後台列表頁係咪正在篩日本產品？（backward-compat）──
function mth_is_admin_japan_filter() {
    return mth_get_admin_filter_cat_slug() === 'japan';
}

// ── 通用 Helpers：適用任何分類 ──
// 後台列表頁正在篩邊個分類？返回 slug 或空字串
function mth_get_admin_filter_cat_slug() {
    if (!is_admin()) return '';
    $current_term_id = isset($_GET['mth_product_cat']) ? (int) $_GET['mth_product_cat'] : 0;
    if (!$current_term_id) return '';
    $term = get_term($current_term_id, 'mth_product_cat');
    return ($term && !is_wp_error($term)) ? $term->slug : '';
}

// 產品嘅主分類 slug（攞第一個）
function mth_get_product_primary_cat($post_id) {
    $terms = wp_get_post_terms($post_id, 'mth_product_cat', array('fields' => 'slugs'));
    if (is_wp_error($terms) || empty($terms)) return '';
    return $terms[0];
}

// 通用 brand map — 由 wp_option 讀取
function mth_brand_map($cat_slug) {
    if (empty($cat_slug)) return array();
    $saved = get_option('mth_brand_map_' . $cat_slug, null);
    if (is_array($saved)) return $saved;
    // Backward migration for Japan
    if ($cat_slug === 'japan') {
        $old = get_option('mth_japan_brand_map_v1', null);
        if (is_array($old) && !empty($old)) {
            update_option('mth_brand_map_japan', $old);
            return $old;
        }
        return mth_japan_brand_map_default();
    }
    return array(); // 其他分類預設空
}

// 通用 type map
function mth_type_map($cat_slug) {
    if (empty($cat_slug)) return array();
    $saved = get_option('mth_type_map_' . $cat_slug, null);
    if (is_array($saved)) return $saved;
    if ($cat_slug === 'japan') {
        $old = get_option('mth_japan_type_map_v1', null);
        if (is_array($old) && !empty($old)) {
            update_option('mth_type_map_japan', $old);
            return $old;
        }
        return mth_japan_type_map_default();
    }
    return array();
}

// 自動分類 — 通用
function mth_classify_brand_auto_v2($title, $cat_slug) {
    if (empty($cat_slug)) return 'other';
    $map = mth_brand_map($cat_slug);
    foreach ($map as $key => $info) {
        if (empty($info['patterns'])) continue;
        foreach ($info['patterns'] as $p) {
            if ($p && mb_strpos($title, $p) !== false) return $key;
        }
    }
    return 'other';
}
function mth_classify_types_auto_v2($title, $cat_slug) {
    if (empty($cat_slug)) return array();
    $map = mth_type_map($cat_slug);
    $types = array();
    foreach ($map as $key => $info) {
        if (empty($info['patterns'])) continue;
        foreach ($info['patterns'] as $p) {
            if ($p && mb_strpos($title, $p) !== false) { $types[] = $key; break; }
        }
    }
    return array_values(array_unique($types));
}

// 公開 API（用 manual override > 自動）
function mth_get_product_brand($post_id) {
    $manual = get_post_meta($post_id, 'mth_brand_manual', true);
    if ($manual) return $manual;
    $cat = mth_get_product_primary_cat($post_id);
    if (!$cat) return 'other';
    return mth_classify_brand_auto_v2(get_the_title($post_id), $cat);
}
function mth_get_product_types($post_id) {
    $manual = get_post_meta($post_id, 'mth_types_manual', true);
    if ($manual) {
        return array_values(array_unique(array_filter(array_map('trim', explode(',', $manual)))));
    }
    $cat = mth_get_product_primary_cat($post_id);
    if (!$cat) return array();
    return mth_classify_types_auto_v2(get_the_title($post_id), $cat);
}

// 純自動分類（由標題判斷）
function mth_classify_brand_auto($title) {
    foreach (mth_japan_brand_map() as $key => $info) {
        foreach ($info['patterns'] as $p) {
            if (mb_strpos($title, $p) !== false) return $key;
        }
    }
    return 'other';
}
function mth_classify_types_auto($title) {
    $types = array();
    foreach (mth_japan_type_map() as $key => $info) {
        foreach ($info['patterns'] as $p) {
            if (mb_strpos($title, $p) !== false) { $types[] = $key; break; }
        }
    }
    return array_values(array_unique($types));
}

// 判斷品牌（優先：手動 override → 自動判斷）
function mth_get_japan_brand($title, $post_id = null) {
    if ($post_id) {
        $manual = get_post_meta($post_id, 'mth_brand_manual', true);
        if ($manual) return $manual;
    }
    return mth_classify_brand_auto($title);
}

// 判斷種類（優先：手動 override → 自動判斷）
function mth_get_japan_types($title, $post_id = null) {
    if ($post_id) {
        $manual = get_post_meta($post_id, 'mth_types_manual', true);
        if ($manual) {
            $types = array_filter(array_map('trim', explode(',', $manual)));
            return array_values(array_unique($types));
        }
    }
    return mth_classify_types_auto($title);
}
