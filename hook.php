<?php

foreach($admin_options_config['menu'] as $ctrl) {
  $ctrl_id = $ctrl['id'];
  $hook = dirname(__FILE__)."/hook/{$ctrl_id}.php";
  if(file_exists($hook))include($hook);
}

/*
 * 增加一个钩子，用来在后台加载选择分类的代码
 * 在需要使用的地方，使用方法如：
  if(is_admin() && basename($_SERVER['PHP_SELF']) == 'widgets.php')do_action('admin_options_category_dialog');
 */
class AdminOptionsCategoryDialog {
  function __construct() {
    add_action('admin_options_category_dialog',array(&$this,'init'));
  }
  function init() {
    // 载入脚本样式之类
    add_action('admin_enqueue_scripts',array(&$this,'scripts_init'));
    // 载入面板
    add_action('admin_print_footer_scripts',array(&$this,'category_dialog'));
  }
  function category_dialog() {
    include_once("view/tpl-cat-dialog.php");
  }
  function scripts_init() {
    wp_register_script('admin_options_category_dialog',get_template_directory_uri().'/admin-options/js/select-category.js');
    wp_enqueue_script('admin_options_category_dialog');

    wp_register_style('admin_options_category_dialog',get_template_directory_uri().'/admin-options/css/select-category.css');
    wp_enqueue_style('admin_options_category_dialog');
  }
}
$AdminOptionsCategoryDialog = new AdminOptionsCategoryDialog();


class AdminOptionsMediaDialog {
  function __construct () {
    add_action('admin_options_media_dialog',array(&$this,'init'));
  }
  function init() {
    add_action('admin_enqueue_scripts',array(&$this,'scripts_init'));
  }
  function scripts_init() {
    global $wp_version;
    if(function_exists('wp_enqueue_media') && $wp_version >= 3.5) {
      wp_enqueue_media();
    }
    else {
      wp_enqueue_script('media-upload');
      wp_enqueue_script('thickbox');
      wp_enqueue_style('thickbox');
    }
    wp_register_script('admin_options_media_dialog',get_template_directory_uri().'/admin-options/js/media.js');
    wp_enqueue_script('admin_options_media_dialog');

    wp_enqueue_style('media');
  }
}
$AdminOptionsMediaDialog = new AdminOptionsMediaDialog();