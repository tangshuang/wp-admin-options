<?php

class AdminOptionsAds {
  var $ads;
  function __construct() {
    $this->ads = array();
    add_shortcode('ad',array($this,'add_ad_shortcode'));
    add_filter('widget_text','do_shortcode');
  }
  function add_ad_shortcode($atts){
    global $admin_options;
    $ads = array_admin_options($admin_options['ads']);
    extract(shortcode_atts(array(
      'name' => ''
    ), $atts));
    if(isset($ads[$name])){
      $this->ads[$name] = stripslashes($ads[$name]);
      add_action('wp_footer',array($this,'print_ad_code'));
      return '<div id="admin-options-ad-'.$name.'"></div>';
    }
  }
  function print_ad_code() {
    if(!empty($this->ads)) foreach($this->ads as $name => $code) {
      echo '<div id="admin-options-ad-'.$name.'-pend" style="display:none;">'.$code.'</div>';
      echo '<script>jQuery("#admin-options-ad-'.$name.'").html(jQuery("#admin-options-ad-'.$name.'-pend").html())</script>'."\n";
    }
  }
}
$AdminOptionsAds = new AdminOptionsAds();