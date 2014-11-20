<?php


class AdminOptionsPostMetas {

  function __construct() {
    add_action('add_meta_boxes',array(&$this,'add_post_metas_box'));
    add_action('save_post',array(&$this,'save_post_metas'));
    add_shortcode('postmeta',array(&$this,'add_meta_shortcode_in_post'));
  }

  function add_post_metas_box() {
    add_meta_box('post_metas','文章新字段',array(&$this,'post_metas_box'),'post','advanced','high');
  }

  // 在文章撰写页增加这些字段
  function post_metas_box(){
    global $post,$admin_options;
    $ctrl_id = basename(__FILE__,'.php');
    $metas = $admin_options[$ctrl_id];
    echo '<div id="post-metas-box-list">';
    if(!empty($metas))foreach($metas as $i => $meta) {
      if(trim($meta[0]) == ''){
        unset($metas[$i]);
        continue;
      }
      $name = $meta[0];
      $type = $meta[1];
      $cats = $meta[2];
      $list = $meta[3];
      $value = get_post_metas($post->ID,$name);
      // 把分类处理为[cat_id]的列表形式
      if($cats != '') {
        $catsarr = array_filter(explode(',',$cats));
        if(is_array($catsarr)) {
          $cats = '';
          foreach($catsarr as $cat) {
            $cats .= "[$cat]";
          }
        }
        else {
          $cats = "[$cats]";
        }
      }
      if($type == 1) {
        echo '<p class="hidden post-metas-box-line" data-cats="'.$cats.'">'.$name.': <input type="text" name="post_metas['.$name.']" value="'.$value.'" class="regular-text" disabled></p>';
      }
      elseif($type == 2) {
        echo '<p class="hidden post-metas-box-line" data-cats="'.$cats.'">'.$name.': <br><textarea name="post_metas['.$name.']" class="large-text" disabled>'.stripslashes($value).'</textarea></p>';
      }
      elseif($type == 3) {
        $items = explode(',',$list);
        echo '<p class="hidden post-metas-box-line" data-cats="'.$cats.'">'.$name.': ';
        foreach($items as $item) {
          echo '<label><input type="radio" name="post_metas['.$name.']" value="'.$item.'" '.checked($item,$value,false).' disabled>'.$item.'</label>';
        }
        echo '</p>';
      }
      elseif($type == 4) {
        $items = explode(',',$list);
        echo '<p class="hidden post-metas-box-line" data-cats="'.$cats.'">'.$name.': ';
        foreach($items as $item) {
          echo '<label><input type="checkbox" name="post_metas['.$name.'][]" value="'.$item.'" '.(is_array($value) && in_array($item,$value) ? 'checked' : '').' disabled>'.$item.'</label>';
        }
        echo '</p>';
      }
      elseif($type == 5) {
        $items = explode(',',$list);
        echo '<p class="hidden post-metas-box-line" data-cats="'.$cats.'">'.$name.': <select name="post_metas['.$name.']" disabled>';
        foreach($items as $item) {
          echo '<option '.selected($value,$item).'>'.$item.'</option>';
        }
        echo '</select></p>';
      }
    }
    echo '</div>';
    if(count($metas) <= 0){
      echo '<div style="height:60px;line-height:60px;text-align:center;" id="loadding-post-metas">暂无可用字段。<a href="'.admin_url('themes.php?page=admin-options#admin-options-metas').'" target="_blank">点击设置</a></div>';
      return;
    }
    ?>
    <style>#post-metas-box-list input.regular-text {max-width: 90% !important;}</style>
    <div style="height:60px;line-height:60px;text-align:center;" id="loadding-post-metas">正在加载...</div>
  <script>
  jQuery(function($){
    $('#categorydiv input').each(function(){
      var $this = $(this),
          value = $this.val(),
          checked = $this.attr("checked");
      if(checked){
        $("#post-metas-box-list p[data-cats*='["+value+"]']").removeClass('hidden')
          .find('input,textarea,select').removeAttr('disabled');
        $('#loadding-post-metas').hide();
      }
    });
    $('#loadding-post-metas').text('选择对应的分类时，会显示出其对应的字段');
    // 如果某些字段没有选择分类，那么意思是所有的分类都可以用
    if($("#post-metas-box-list p[data-cats='']").length > 0) {
      $("#post-metas-box-list p[data-cats='']").removeClass('hidden')
        .find('input,textarea,select').removeAttr('disabled');
      $('#loadding-post-metas').hide();
    }
    // 点击选择获取去除某一个分类
    $('#categorydiv').on('change','input',function(){
        var $this = $(this),
            value = $this.val(),
            checked = $this.attr("checked");
      if(checked){
        $("#post-metas-box-list p[data-cats*='["+value+"]']").removeClass('hidden')
          .find('input,textarea,select').removeAttr('disabled');
        $('#loadding-post-metas').hide();
      }
      // 去除勾选
      else{
        $("#post-metas-box-list p[data-cats*='["+value+"]']").addClass('hidden')
          .find('input,textarea,select').attr('disabled',true);
        // 为了防止某些选项是应用在多个分类中的，因此，当一个分类去除勾选之后，可能其他分类还勾选着，因此还要遍历来确定是否要使用这个选项
        $('#categorydiv input').each(function(){
          var $this = $(this),
              value = $this.val(),
              checked = $this.attr("checked");
          if(checked){
            $("#post-metas-box-list p[data-cats*='["+value+"]']").removeClass('hidden')
              .find('input,textarea,select').removeAttr('disabled');
            $('#loadding-post-metas').hide();
          }
        });
        // 如果已经没有选项显示了，干脆直接显示为一个提醒界面
        if($('#post-metas-box-list p.post-metas-box-line:visible').length <= 0)$('#loadding-post-metas').show();
      }
    });
  });
  </script>
    <?php
  }

  // 保存字段
  function save_post_metas($post_id){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)return;
    $post_metas = $_POST['post_metas'];
    if(!empty($post_metas))foreach($post_metas as $name => $value) {
      update_post_meta($post_id,'_post_metas_'.$name,$value) or add_post_meta($post_id,'_post_metas_'.$name,$value,true);
    }
  }

  // 在文章中使用[postmeta key="属性"]调用属性值
  function add_meta_shortcode_in_post($atts){
    extract(shortcode_atts(array(
      'key' => ''
    ),$atts));
    global $post;
    $value = get_post_meta($post->ID,'_post_metas_'.$key,true);
    return $value;
  }

} // end of class
$AdminOptionsPostMetas = new AdminOptionsPostMetas();