<?php
// FileBird Lite modal hack
if (!defined('ABSPATH')) exit;

/* ──────────────────────────────────────────────
   FileBird Lite 媒體 Modal 資料夾過濾（免費 Pro 替代）
   讀 wp_fbv 同 wp_fbv_attachment_folder 表，
   喺「精選圖片」彈窗加返資料夾下拉
   ────────────────────────────────────────────── */

function mth_fb_tables() {
    global $wpdb;
    static $cache = null;
    if ($cache !== null) return $cache;

    $folder = $wpdb->prefix . 'fbv';
    $map    = $wpdb->prefix . 'fbv_attachment_folder';

    $has_folder = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $folder)) === $folder;
    $has_map    = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $map)) === $map;

    $cache = ($has_folder && $has_map) ? array('folder' => $folder, 'map' => $map) : false;
    return $cache;
}

function mth_fb_get_folders() {
    $t = mth_fb_tables();
    if (!$t) return array();
    global $wpdb;
    $rows = $wpdb->get_results("SELECT id, name, parent, ord FROM {$t['folder']} ORDER BY ord ASC, name ASC");
    return $rows ?: array();
}

// 按 folder_id 過濾 attachment query
add_filter('ajax_query_attachments_args', function($args) {
    if (empty($_REQUEST['query']['mth_fb_folder'])) return $args;
    $folder_id = sanitize_text_field($_REQUEST['query']['mth_fb_folder']);

    $t = mth_fb_tables();
    if (!$t) return $args;
    global $wpdb;

    if ($folder_id === 'uncategorized') {
        // 未分類：搵冇任何 folder 紀錄嘅 attachment
        $assigned_ids = $wpdb->get_col("SELECT attachment_id FROM {$t['map']}");
        if (!empty($assigned_ids)) {
            $args['post__not_in'] = array_map('intval', $assigned_ids);
        }
    } else {
        $fid = (int) $folder_id;
        if ($fid > 0) {
            $ids = $wpdb->get_col($wpdb->prepare(
                "SELECT attachment_id FROM {$t['map']} WHERE folder_id = %d",
                $fid
            ));
            $args['post__in'] = !empty($ids) ? array_map('intval', $ids) : array(0);
        }
    }
    return $args;
});

// 注入 dropdown UI 到所有媒體 modal
add_action('admin_enqueue_scripts', function($hook) {
    if (!current_user_can('upload_files')) return;
    if (!mth_fb_tables()) return;

    $folders = mth_fb_get_folders();
    if (empty($folders)) return;

    // 將 folders 傳俾 JS
    wp_add_inline_script('media-views', 'window.MTH_FB_FOLDERS = ' . wp_json_encode($folders) . ';', 'before');

    add_action('admin_print_footer_scripts', 'mth_fb_inject_script', 20);
});

function mth_fb_inject_script() {
    ?>
    <script>
    (function($) {
      if (typeof wp === 'undefined' || !wp.media || !window.MTH_FB_FOLDERS) return;

      var folders = window.MTH_FB_FOLDERS;

      function buildOptions(parent, depth) {
        var html = '';
        folders.forEach(function(f) {
          if (parseInt(f.parent, 10) === parseInt(parent, 10)) {
            var indent = '';
            for (var i = 0; i < depth; i++) indent += '— ';
            html += '<option value="' + f.id + '">' + indent + ' 📁 ' + $('<div>').text(f.name).html() + '</option>';
            html += buildOptions(f.id, depth + 1);
          }
        });
        return html;
      }

      var optionsHTML = '<option value="">📂 所有資料夾</option>' +
                        '<option value="uncategorized">📄 未分類</option>' +
                        buildOptions(0, 0);

      // Hook 入 AttachmentsBrowser toolbar
      var origCreate = wp.media.view.AttachmentsBrowser.prototype.createToolbar;
      wp.media.view.AttachmentsBrowser.prototype.createToolbar = function() {
        origCreate.apply(this, arguments);
        var browser = this;
        var $toolbar = browser.toolbar.$el;
        if ($toolbar.find('.mth-fb-filter').length) return;

        var $select = $('<select class="mth-fb-filter attachment-filters" style="margin-right:8px;max-width:200px;">' + optionsHTML + '</select>');

        // 揀返之前嘅 folder
        var current = browser.collection.props.get('mth_fb_folder');
        if (current) $select.val(current);

        $select.on('change', function() {
          var v = $(this).val();
          browser.collection.props.set('mth_fb_folder', v || null);
          // Trigger refresh
          browser.collection.props.trigger('change');
        });

        // 插入到 toolbar 左邊
        $toolbar.find('.media-toolbar-secondary').prepend($select);
      };

      // 確保 query param 會傳到 server
      var origMore = wp.media.model.Attachments.prototype.more;
      // 已經透過 ajax_query_attachments_args 處理，所以唔需要再 hook
    })(jQuery);
    </script>
    <style>
      .mth-fb-filter { height: 28px !important; line-height: 26px !important; font-size: 12px !important; }
    </style>
    <?php
}
