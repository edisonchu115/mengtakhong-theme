<?php
// Newsletter CPT + handler
if (!defined('ABSPATH')) exit;

/* ── Newsletter Subscriber CPT ── */
add_action('init', function() {
    register_post_type('mth_subscriber', array(
        'labels' => array(
            'name'          => '訂閱者',
            'singular_name' => '訂閱者',
            'menu_name'     => '訂閱名單',
            'all_items'     => '所有訂閱者',
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-email-alt',
        'menu_position'=> 7,
        'supports'     => array('title'),
        'capability_type' => 'post',
    ));
});

/* ── Newsletter 訂閱處理 ── */
add_action('admin_post_nopriv_mth_newsletter_subscribe', 'mth_newsletter_handler');
add_action('admin_post_mth_newsletter_subscribe', 'mth_newsletter_handler');
function mth_newsletter_handler() {
    if (!isset($_POST['mth_newsletter_nonce']) || !wp_verify_nonce($_POST['mth_newsletter_nonce'], 'mth_newsletter')) {
        wp_die('Invalid request');
    }
    $name    = sanitize_text_field($_POST['nl_name'] ?? '');
    $method  = sanitize_text_field($_POST['nl_method'] ?? '');
    $contact = sanitize_text_field($_POST['nl_contact'] ?? '');

    if (!$name || !$method || !$contact) {
        wp_redirect(add_query_arg('nl', 'error', wp_get_referer() ?: home_url('/')));
        exit;
    }

    $valid_methods = array('email', 'whatsapp', 'ig', 'fb');
    if (!in_array($method, $valid_methods, true)) {
        wp_redirect(add_query_arg('nl', 'error', wp_get_referer() ?: home_url('/')));
        exit;
    }

    $title = $name . ' (' . strtoupper($method) . ')';
    $post_id = wp_insert_post(array(
        'post_type'   => 'mth_subscriber',
        'post_status' => 'publish',
        'post_title'  => $title,
    ));
    if ($post_id && !is_wp_error($post_id)) {
        update_post_meta($post_id, 'nl_name', $name);
        update_post_meta($post_id, 'nl_method', $method);
        update_post_meta($post_id, 'nl_contact', $contact);
        update_post_meta($post_id, 'nl_date', current_time('mysql'));
        update_post_meta($post_id, 'nl_ip', $_SERVER['REMOTE_ADDR'] ?? '');

        // 通知 admin
        wp_mail(
            get_option('admin_email'),
            '【明德行】新訂閱：' . $name,
            "新訂閱者：\n稱呼：{$name}\n方式：{$method}\n聯絡：{$contact}\n時間：" . current_time('mysql'),
            array('Content-Type: text/plain; charset=UTF-8')
        );
    }

    wp_redirect(add_query_arg('nl', 'ok', wp_get_referer() ?: home_url('/')));
    exit;
}

/* ── Newsletter 訂閱者列表加自訂欄 ── */
add_filter('manage_mth_subscriber_posts_columns', function($cols) {
    return array(
        'cb'         => $cols['cb'] ?? '',
        'title'      => '稱呼',
        'nl_method'  => '聯絡方式',
        'nl_contact' => '聯絡資料',
        'nl_date'    => '訂閱時間',
    );
});
add_action('manage_mth_subscriber_posts_custom_column', function($col, $post_id) {
    switch ($col) {
        case 'nl_method':
            $m = get_post_meta($post_id, 'nl_method', true);
            $icons = array('email'=>'📧 Email','whatsapp'=>'💬 WhatsApp','ig'=>'📷 Instagram','fb'=>'👍 Facebook');
            echo esc_html($icons[$m] ?? $m);
            break;
        case 'nl_contact':
            echo esc_html(get_post_meta($post_id, 'nl_contact', true));
            break;
        case 'nl_date':
            echo esc_html(get_post_meta($post_id, 'nl_date', true));
            break;
    }
}, 10, 2);

