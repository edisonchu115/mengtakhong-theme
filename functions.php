<?php
/**
 * Meng Tak Hong Theme — Functions Loader
 *
 * 主檔只負責 load inc/ 入面所有 module
 * 各 module 按 prefix（01-, 02-, ...）順序載入
 *
 * 加新 module：放入 inc/ 即可，按 prefix 自動排序
 * 文件變動歷史：見 /Desktop/MTH/CHANGELOG.md
 */

if (!defined('ABSPATH')) exit;

// Auto-load all modules under inc/ by alphabetical (numeric prefix) order
$mth_inc_dir = get_template_directory() . '/inc/';
if (is_dir($mth_inc_dir)) {
    $files = glob($mth_inc_dir . '*.php');
    if ($files) {
        sort($files); // sort by filename (prefix 01-, 02-, etc.)
        foreach ($files as $file) {
            require_once $file;
        }
    }
}
