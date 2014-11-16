<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <h2>广告设置</h2>
  <form method="post" autocomplete="off">
  <div class="metabox-holder">
  <div class="postbox">
    <h3 class="hndle">广告列表</h3>
    <div class="inside">
      <p>
        <a href="javascript:void(0);" onclick="add_ad_line();" class="btn button">添加一行</a>
        <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-ad-list','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
      </p>
    </div>
    <?php
    $ads = $admin_options[$ctrl_id];
    $_SESSION['poser'][$ctrl_id] = 0;
    if(!empty($ads))$ads = array_values($ads);
    // 把一些固定要填写的值放在这里
    $permas = array('边栏悬浮');
    foreach($permas as $i => $perma) {
      ?>
      <div class="inside">
        <p>广告位名称：<input type="text" name="options[<?php echo $ctrl_id; ?>][<?php echo $i; ?>][]" class="regular-text" value="<?php echo $perma; ?>" readonly></p>
        <p>广告代码：<br><textarea name="options[<?php echo $ctrl_id; ?>][<?php echo $i; ?>][]" class="large-text code"><?php echo stripslashes($ads[$i][1]); ?></textarea>
        </p>
      </div>
      <?php 
      $_SESSION['poser'][$ctrl_id] = $i;
    } ?>
    <div id="admin-options-ad-list" class="admin-options-list">
      <?php
        if(!empty($ads)) :
          foreach($ads as $i => $ad){
            if(in_array($ad[0],$permas))continue;
            echo '<div class="inside box">';
            echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div>';
            echo '<p>广告位名称：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text" value="'.$ad[0].'"></p>';
            echo '<p>广告代码：<br><textarea name="options['.$ctrl_id.']['.$i.'][]" class="large-text code">'.stripslashes($ad[1]).'</textarea></p>';
            echo '</div>';
            $_SESSION['poser'][$ctrl_id] = $i;
          }
        else :
          $_SESSION['poser'][$ctrl_id] ++;
          $i = $_SESSION['poser'][$ctrl_id];
          echo '<div class="inside box">';
          echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div>';
          echo '<p>广告位名称：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text"></p>';
          echo '<p>广告代码：<br><textarea name="options['.$ctrl_id.']['.$i.'][]" class="large-text code"></textarea></p>';
          echo '</div>';
        endif;
      ?>
    </div>
    <div class="inside">
      <p>
        <a href="javascript:void(0);" onclick="add_ad_line('append');" class="btn button">添加一行</a>
        <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-ad-list','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
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
$ads = <b>array_admin_options</b>($admin_options['<?php echo $ctrl_id; ?>']);
$google = <b>stripslashes</b>($ads['GOOGLE-300-250']);</pre>
      <p>上面的这段代码中，'GOOGLE-300-250'是你上面填写的名称，$ads['GOOGLE-300-250']获得的值就是你填写的值。</p>
      <p>在文章内容中，在边栏的文本小工具中，可以使用短代码来显示广告，而且我强烈推荐使用这种方法。</p>
<pre>
[ad name="边栏悬浮"]
或者
&lt;php do_shortcode('[ad name="边栏悬浮"]'); ?&gt;
</pre>
      <p>使用短代码的形式可以实现异步加载，防止网页加载到广告代码处被中断。</p>
    </div>
  </div></div>
</div>
<script>
// 添加一行按钮，这个函数不放到script.js中，因为这里需要一个line值
line.ad = <?php echo $_SESSION['poser'][$ctrl_id]; ?>;
function add_ad_line(pend){
  line.ad ++;
  var html = '<div class="inside box"><div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>广告位名称：<input type="text" name="options[<?php echo $ctrl_id; ?>]['+line.ad+'][]" class="regular-text"></p><p>广告代码：<br><textarea name="options[<?php echo $ctrl_id; ?>]['+line.ad+'][]" class="large-text code"></textarea></p></div>',$list = jQuery('#admin-options-ad-list');
  if(pend == 'append')$list.append(html);
  else $list.prepend(html);
}
</script>