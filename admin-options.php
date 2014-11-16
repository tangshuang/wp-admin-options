<?php



/*
 * 前台后台都可以使用的函数
 * 其他插件或文件中，不能重复定义下面的函数
 * 注意，这些函数不能提前使用admin_options的功能，它们是前置的，只能使用WordPress内核来构造
 */

function array_admin_options($options = array()) {
  $option = array();
  if(!empty($options))foreach($options as $opt) {
    $option[$opt[0]] = $opt[1];
  }
  return $option;
}

function get_admin_options($key = false) { // key为具体值的时候获取对应的值，key为false的时候，获取全部值
  if($key) {
    $option = get_option('admin_options_'.$key);
    return $option;
  }
  else {
    global $wpdb;
    $options = array();
    $sets = $wpdb->get_results("SELECT option_name,option_value FROM $wpdb->options WHERE option_name LIKE 'admin_options_%';");
    if(!empty($sets))foreach($sets as $set){
      $key = str_replace('admin_options_','',$set->option_name);
      $value = $set->option_value;
      $options[$key] = is_serialized($value) ? unserialize($value) : $value;
    }
    return $options;
  }
}

function update_admin_options($data,$value = null){// data为数组时，即键名和键值对应，相当于执行多次update；data为字符时，value可以是单个字符，也可以是数组
  if(is_array($data) || is_object($data)) {
    $data = (array)$data;
    foreach($data as $key => $value) {
      if(!empty($value))update_option('admin_options_'.$key,$value);
      else delete_option('admin_options_'.$key);
    }
  }
  elseif(is_string($data)) {
    if(!empty($value))update_option('admin_options_'.$data,$value);
    else delete_option('admin_options_'.$data);
  }
}

// 获取文章字段
function get_post_metas($post_id,$meta_name = array()) {// meta_name为具体值的时候，获取对应的值，为某一个数组时，只获取数组中对应的那些值，以数组的形式返回，为空数组时，以数组的形式返回所有值
  if(is_array($meta_name) || is_object($meta_name)) {
    $meta_name = (array)$meta_name;
    $metas = array();
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE '_post_metas_%';");
    if($results)foreach($results as $meta){
      $key = str_replace('_post_metas_','',$meta->meta_key);
      $value = $meta->meta_value;
      $metas[$key] = is_serialized($value) ? unserialize($value) : $value;
      if(!empty($meta_name) && !in_array($key,$meta_name))unset($metas[$key]);
    }
  }
  elseif(is_string($meta_name)) {
    $metas = get_post_meta($post_id,'_post_metas_'.$meta_name,true);
  }
  return $metas;
}


/*
 * 前台后台都载入的钩子
 * 注意：钩子中全部使用class来实现，以防止和其他函数冲突
 */
global $admin_options; // 因为是在action/filter等钩子中使用，所以必须要声明为global，一旦声明为global，在主题中也可以使用。这个地方也有个技巧，要在主题文件functions.php中早点载入admin-options.php，这样就可以在主题中全局使用$admin_options了。
global $admin_options_config;
$admin_options = get_admin_options();
include(dirname(__FILE__)."/controller.php");
include(dirname(__FILE__)."/hook.php");


/*
 * 后台视图
 */
class AdminOptionsView {
  function __construct() {
    add_action('admin_menu', array(&$this,'init'));
  }
  function init() {
    if(isset($_POST['save_admin_options']) && $_POST['save_admin_options'] == 1){
      check_admin_referer();
      $options = $_POST['options'];
      update_admin_options($options);
      wp_redirect(add_query_arg('time',time()));
    }
    global $admin_options_config;
    $admin_options_page = $admin_options_config['settings']['page'];
    if(@$_GET['page'] == $admin_options_page) {
      add_action('admin_enqueue_scripts',array(&$this,'scripts_init'));
      do_action('admin_options_media_dialog');
      do_action('admin_options_category_dialog');
    }
    add_theme_page('Theme Options','Theme Options','edit_themes',$admin_options_page,array(&$this,'view'));
  }
  function scripts_init() {
    wp_register_script('admin_options_script',get_template_directory_uri().'/admin-options/js/script.js');
    wp_enqueue_script('admin_options_script');
    wp_register_style('admin_options_style',get_template_directory_uri().'/admin-options/css/style.css');
    wp_enqueue_style('admin_options_style');
  }
  function view() {
    global $admin_options;
    include(dirname(__FILE__)."/controller.php");
    include(dirname(__FILE__)."/view.php");
  }
}
$AdminOptionsView = new AdminOptionsView();