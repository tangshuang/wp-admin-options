<?php

global $admin_options;
if($admin_options['mode'] >= 2) :


// 使用用户填写的avatar字段的url作为头像src
class AvatarByMeta {
  function __construct(){
      add_filter('get_avatar',array($this,'get_avatar'),99,5);
  }
  function get_avatar($avatar , $id_or_email , $size = '60'  , $default , $alt = false){
    global $wpdb;
    $image = null;
    if(is_numeric($id_or_email)){
      $image = get_user_meta($id_or_email,'avatar',true);
    }
    elseif(is_string($id_or_email)){
      $user_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM wp_users WHERE user_email=%s",$id_or_email));
      $image = get_user_meta($user_id,'avatar',true);
    }
    if($image){
      $avatar = '<img src="'.$image.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" />';
    }
    return $avatar;
  }
}
$AvatarByMeta = new AvatarByMeta();

class DoRoleMedia {
  function __construct() {
    // 投稿者也可以上传图片
    add_action('init', array($this,'allow_contributor_uploads'));
    // WordPress 媒体库只显示用户自己上传的文件 http://www.wpdaxue.com/view-user-own-media-only.html
    add_action('pre_get_posts',array($this,'my_upload_media'));
    add_filter('parse_query',array($this,'my_media_library'));
  }
  // 投稿者也可以上传图片
  function allow_contributor_uploads() {
    $contributor = get_role('contributor');
    $contributor->add_cap('upload_files');
  }
  //在文章编辑页面的[添加媒体]只显示用户自己上传的文件
  function my_upload_media( $wp_query_obj ) {
    global $current_user, $pagenow;
    if( !is_a( $current_user, 'WP_User') )
      return;
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
      return;
    if( !current_user_can( 'manage_options' ) && !current_user_can('manage_media_library') )
      $wp_query_obj->set('author', $current_user->ID );
    return;
  }
  //在[媒体库]只显示用户上传的文件
  function my_media_library( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
      if ( !current_user_can( 'manage_options' ) && !current_user_can( 'manage_media_library' ) ) {
        global $current_user;
        $wp_query->set( 'author', $current_user->id );
      }
    }
  }
}
$DoRoleMedia = new DoRoleMedia();

// 切换用户的文章URL
class UserPostUrl {
  function __construct() {
    add_action('init', array($this,'new_author_base'));
    add_filter('author_link',array($this,'author_rewrite_link'), 99, 3);
    add_filter('author_rewrite_rules',array($this,'my_author_rewrite_rules'),99);
  }
  // 把URL中的author换成user
  function new_author_base() {
    global $wp_rewrite;
    $wp_rewrite->author_base = 'user';
    $wp_rewrite->flush_rules();
  }
  function author_rewrite_link($link,$user_id,$user_nickname){
    $link = str_replace($user_nickname,$user_id,$link);
    return $link;
  }
  function my_author_rewrite_rules($rules){
    global $wp_rewrite;
    $user_base = $wp_rewrite->author_base;
    $newrules[$user_base.'/(\d+)$'] = 'index.php?author=$matches[1]';
    return $newrules;
  }
}
$UserPostUrl = new UserPostUrl();


endif; // 众人投稿模式下的媒体权限结束
