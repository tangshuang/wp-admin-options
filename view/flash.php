<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <h2>幻灯设置</h2>
  <form method="post" enctype="multipart/form-data" autocomplete="off">
<?php
function set_flash_list($name,$ctrl = null) {// 第一个参数是falsh的别名，例如你使用slider，那么前台调用的时候就用$admin_options['slider']，第二个参数是传入的控制器，因为本函数处在一个foreach循环中，$ctrl只是一个临时的值，而非全局变量，所以无法直接使用$ctrl来引用。
  global $admin_options;
?>
  <div class="metabox-holder"><div class="meta-box-sortables"><div class="postbox">
    <div class="handlediv" title="点击以切换"><br></div>
    <h3 class="hndle">[<?php echo $name; ?>]幻灯列表</h3>
    <div class="inside">
      <p>
        <a href="javascript:void(0);" onclick="add_flash_line('#admin-options-flash-list-<?php echo $name; ?>','options[<?php echo $name; ?>]');" class="btn button">添加一行</a>
        <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-flash-list-<?php echo $name; ?>','options[<?php echo $name; ?>]');" class="btn button">清除无效</a>
        <code>图片的尺寸请保持为：<?php echo $ctrl['data']['img_size']; ?>px</code>
      </p>
    </div>
    <div id="admin-options-flash-list-<?php echo $name; ?>" class="admin-options-list">
      <?php
        $flash_list = $admin_options[$name];
        if(!empty($flash_list)) :
          $flash_list = array_values($flash_list);
          foreach($flash_list as $i => $flash){
            echo '<div class="inside box">';
            echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div>';
            echo '<p>图片：<input type="text" name="options['.$name.']['.$i.'][]" class="regular-text" value="'.$flash[0].'"> <a class="button upload-media">上传</a></p>';
            echo '<p>链接：<input type="text" name="options['.$name.']['.$i.'][]" class="regular-text" value="'.$flash[1].'"></p>';
            echo '<p><textarea name="options['.$name.']['.$i.'][]" class="large-text code" rows="3">'.stripslashes($flash[2]).'</textarea></p>';
            echo '</div>';
          }
        else :
          echo '<div class="inside box">';
          echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a></div>';
          echo '<p>图片：<input type="text" name="options['.$name.'][0][]" class="regular-text"> <a class="button upload-media">上传</a></p>';
          echo '<p>链接：<input type="text" name="options['.$name.'][0][]" class="regular-text"></p>';
          echo '<p><textarea name="options['.$name.'][0][]" class="large-text code" rows="3"></textarea></p>';
          echo '</div>';
        endif;
      ?>
    </div>
  </div></div></div>
<?php
}
set_flash_list($ctrl_id,$ctrl);
//set_flash_list('slider');
?>
  <p class="submit">
    <input name="save" type="submit" class="button-primary" value="提交" />
  </p>
  <input type="hidden" name="save_admin_options" value="1" />
  <?php wp_nonce_field(); ?>
  </form>
  <div class="metabox-holder"><div class="postbox">
    <h3 class="hndle">使用说明</h3>
    <div class="inside" style="background:#fafafa">
      <p>代码文本框中可以选择下方的代码模板</p>
<p><textarea class="large-text code" rows="3" readonly><h3 data-pos="['10%', '110%', '10%', '5%']" data-duration="700" data-effect="move">移动效果</h3>
<div data-pos="['-30%', '25%', '30%', '25%']" data-duration="700" data-effect="move">第一段文字</div>
<div data-pos="['60%', '-40%', '60%', '11%']" data-duration="700" data-effect="move">第二段文字</div>
<div data-pos="['23%', '110%', '23%', '42%']" data-duration="700" data-effect="move"><img data-lazy-src="images/add.jpg"/></div></textarea></p>
<p><textarea class="large-text code" rows="3" readonly><h3 data-pos="['0%', '8%']" data-duration="1000" data-effect="fadein">渐现效果</h3>
<div data-pos="['44%', '15%']" data-duration="700" data-effect="fadein">第一段文字</div>
<div data-pos="['66%', '11%']" data-duration="700" data-effect="fadein">第二段文字</div>
<div data-pos="['29%', '46%']" data-duration="700" data-delay="500" data-effect="fadein"><img data-lazy-src="images/add.jpg"/></div></textarea></p>
<p>data-pos如果是两个参数，就是其位置(top,left)如果是四个参数，就是起始位置(top,left)和最终位置(top,left)</p>
    </div>
    <div class="inside">
      <p>前台调用方法：</p>
      <pre>global $admin_options;
$flash_list = $admin_options['<?php echo $ctrl_id; ?>'];
if(!empty($flash_list))foreach($flash_list as $flash){
  $img_src = $flash[0];
  $flash_link = trim($flash[1]);
  $flash_code = stripslashes($flash[2]);
}</pre>
    </div>
  </div></div>
</div>
<script>
// 添加一行按钮，这个函数不放到script.js中，因为这里需要一个line值
function add_flash_line(element,name){
  var $element = jQuery(element),
      line = $element.find('.box').length;
  $element.prepend('<div class="inside box"><div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>图片：<input type="text" name="'+name+'['+line+'][]" class="regular-text"> <a class="button upload-media">上传</a></p><p>链接：<input type="text" name="'+name+'['+line+'][]" class="regular-text"></p><p><textarea name="'+name+'['+line+'][]" class="large-text code" rows="3"></textarea></p></div>');
}
</script>