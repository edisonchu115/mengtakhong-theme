<?php
// Theme setup + Enqueue scripts/styles
if (!defined('ABSPATH')) exit;

/* ── Theme Setup ── */
function mth_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption'] );

    register_nav_menus([
        'primary' => '主導航選單',
        'footer'  => 'Footer 選單',
    ]);
}
add_action( 'after_setup_theme', 'mth_setup' );

/* ── Enqueue Scripts & Styles ── */
function mth_scripts() {
    wp_enqueue_style( 'google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&family=Noto+Serif+TC:wght@400;700;900&display=swap',
        [], null );
    wp_enqueue_style( 'mth-main', get_template_directory_uri() . '/assets/css/main.css', [], '2.5.0' );
    wp_enqueue_script( 'mth-products-data',
        get_template_directory_uri() . '/assets/js/products-data.js', [], '1.0.0', true );
    wp_enqueue_script( 'mth-main',
        get_template_directory_uri() . '/assets/js/main.js', ['mth-products-data'], '2.5.1', true );

    wp_localize_script( 'mth-main', 'MTH_WP', [
        'home_url'    => home_url('/'),
        'theme_url'   => get_template_directory_uri(),
        'uploads_url' => content_url('/uploads/products'),
        'search_url'  => home_url('/?s='),
        'product_url' => home_url('/product/'),
        'cat_url'     => home_url('/product-category/'),
    ]);
}
add_action( 'wp_enqueue_scripts', 'mth_scripts' );

