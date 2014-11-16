<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <h2>社交媒体设置</h2>
  <form method="post" autocomplete="off">
  <div class="metabox-holder">
  <div class="postbox">
    <h3 class="hndle">社交媒体列表</h3>
    <div class="inside">
      <p>
        <a href="javascript:void(0);" onclick="add_social_line();" class="btn button">添加一行</a>
        <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-social-list','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
      </p>
    </div>
    <?php
    $socials = $admin_options[$ctrl_id];
    $_SESSION['poser'][$ctrl_id] = 0;
    if(!empty($socials))$socials = array_values($socials);
    // 把一些固定要填写的值放在这里
    $permas = array('微博','facebook','twitter');
    foreach($permas as $i => $perma) {
      ?>
      <div class="inside">
        <p>
        名称：<input type="text" name="options[<?php echo $ctrl_id; ?>][<?php echo $i; ?>][]" class="regular-text short-text" value="<?php echo $perma; ?>" readonly>
        值：<input type="text" name="options[<?php echo $ctrl_id; ?>][<?php echo $i; ?>][]" value="<?php echo $socials[$i][1]; ?>" class="regular-text">
        </p>
      </div>
      <?php 
      $_SESSION['poser'][$ctrl_id] = $i;
    } ?>
    <div id="admin-options-social-list" class="admin-options-list">
      <?php
        if(!empty($socials)) :
          foreach($socials as $i => $social){
            if(in_array($social[0],$permas))continue;
            echo '<div class="inside box">';
            echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>';
            echo '名称：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text short-text" value="'.$social[0].'">';
            echo '值：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" value="'.$social[1].'" class="regular-text">';
            echo '</p></div>';
            $_SESSION['poser'][$ctrl_id] = $i;
          }
        else :
          $_SESSION['poser'][$ctrl_id] ++;
          $i = $_SESSION['poser'][$ctrl_id];
          echo '<div class="inside box">';
          echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>';
          echo '名称：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text short-text">';
          echo '值：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text">';
          echo '</p></div>';
        endif;
      ?>
    </div>
    <div class="inside">
      <p>
        <a href="javascript:void(0);" onclick="add_social_line('append');" class="btn button">添加一行</a>
        <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-social-list','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
      </p>
    </div>
  </div>
  </div>
  <p class="submit"><input name="save" type="submit" class="button-primary" value="提交" /></p>
  <input type="hidden" name="save_admin_options" value="1" />
  <?php wp_nonce_field(); ?>
  </form>
  <div class="metabox-holder"><div class="postbox">
    <h3 class="hndle">使用说明</h3>
    <div class="inside">
      <p>前台主题中怎么调用呢？非常简单，看看下面的PHP代码就懂了：</p>
<pre>global $admin_options;
$socials = array_admin_options($admin_options['<?php echo $ctrl_id; ?>']);
$QQ = $socials['QQ'];</pre>
      <p>上面的这段代码中，'QQ'是你上面填写的名称，$socials['QQ']获得的值就是你填写的值。</p>
    </div>
  </div></div>
</div>
<script>
// 添加一行按钮，这个函数不放到script.js中，因为这里需要一个line值
line.social = <?php echo $_SESSION['poser'][$ctrl_id]; ?>;
function add_social_line(pend){
  line.social ++;
  var html = '<div class="inside box"><div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>名称：<input type="text" name="options[<?php echo $ctrl_id; ?>]['+line.social+'][]" class="regular-text short-text">值：<input type="text" name="options[<?php echo $ctrl_id; ?>]['+line.social+'][]" class="regular-text"></p></div>',$list = jQuery('#admin-options-social-list');
  if(pend == 'append')$list.append(html);
  else $list.prepend(html);
}
</script>