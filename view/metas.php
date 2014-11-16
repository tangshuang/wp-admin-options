<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <form method="post" autocomplete="off">
  <h2>文章字段设置</h2>
  <div class="metabox-holder">
    <div class="postbox">
      <h3 class="hndle">设置新增加的字段</h3>
      <div class="inside">
        <p>
          <a href="javascript:void(0);" onclick="add_meta_line();" class="btn button">添加一行</a>
          <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-post-metas','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
        </p>
      </div>
      <div id="admin-options-post-metas" class="admin-options-list">
      <?php
        $_SESSION['poser'][$ctrl_id] = 0;
        $metas = $admin_options[$ctrl_id];
        if(!empty($metas)) :
          $metas = array_values($metas);
          foreach($metas as $i => $meta){
            echo '<div class="inside box">';
            echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div>';
            echo '<p>名称：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" class="regular-text short-text" value="'.$meta[0].'">';
            echo '<select name="options['.$ctrl_id.']['.$i.'][]">
              <option value="1" '.selected($meta[1],1,false).'>单行文本</option>
              <option value="2" '.selected($meta[1],2,false).'>多行文本</option>
              <option value="3" '.selected($meta[1],3,false).'>单选按钮</option>
              <option value="4" '.selected($meta[1],4,false).'>多选按钮</option>
              <option value="5" '.selected($meta[1],5,false).'>下拉列表</option>
            </select>';
            echo '分类：<input type="text" name="options['.$ctrl_id.']['.$i.'][]" value="'.$meta[2].'" class="regular-text"> <a class="button select-category">选择</a></p>';
            echo '<p style="'.($meta[1]>2 ? 'display:block;' : 'display:none;').'"><textarea name="options['.$ctrl_id.']['.$i.'][]" class="large-text" rows="3" placeholder="项目一,项目二,项目三">'.stripslashes($meta[3]).'</textarea></p>';
            echo '</div>';
            $_SESSION['poser'][$ctrl_id] = $i;
          }
        else :
          echo '<div class="inside box">';
          echo '<div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div>';
          echo '<p>名称：<input type="text" name="options['.$ctrl_id.'][0][]" class="regular-text short-text">';
          echo '<select name="options['.$ctrl_id.'][0][]" class="change-meta-type">
            <option value="1">单行文本</option>
            <option value="2">多行文本</option>
            <option value="3">单选按钮</option>
            <option value="4">多选按钮</option>
            <option value="5">下拉列表</option>
          </select>';
          echo '分类：<input type="text" name="options['.$ctrl_id.'][0][]" class="regular-text"> <a class="button select-category">选择</a></p>';
          echo '<p style="display:none;"><textarea name="options['.$ctrl_id.'][0][]" class="large-text" rows="3" placeholder="项目一,项目二,项目三"></textarea></p>';
          echo '</div>';
        endif;
      ?>
      </div>
      <div class="inside">
        <p>
          <a href="javascript:void(0);" onclick="add_meta_line('append');" class="btn button">添加一行</a>
          <a href="javascript:void(0);" onclick="clear_line_in_list('#admin-options-post-metas','options[<?php echo $ctrl_id; ?>]');" class="btn button">清除无效</a>
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
      <p>这是一个非常高级的功能，它能够帮你轻松的创建文章的新定义字段，例如你希望你的产品有“颜色|尺寸|库存”等字段，但是WordPress本身是没有的，我们利用post_meta实现了该功能。你可以通过上面的面板创建各种字段，创建过程中需要注意以下几点：</p>
      <ol>
        <li>名称可以直接用中文，WordPress可以很好的识别</li>
        <li>单选、复选、下拉三种类型需要设置选项，例如你建立了一个单选按钮组，那么你需要在出现的文本区域中写上这个按钮组的每一个按钮的值是什么，不同的值用逗号隔开。</li>
        <li>分类的地方非常有用，例如你建立了几个分类，其中有一个分类叫“产品列表”，这个分类下的文章需要有“颜色|尺寸|库存”等字段，你就可以为这些字段确定分类。在你写文章的时候，只有勾选了这些分类中的一个或多个，才会出现这些字段，不勾选则不出现，防止扰乱是先。在默认的情况下，分类处的内容是空，也就是说所有分类下都会出现。</li>
      </ol>
      <p>具体什么效果，你可以通过先建立一些字段后，再新建一篇文章，在文章的撰写页面就可以看到效果了。</p>
      <p>在模板中直接使用<code>get_post_metas($post_id,'中文字段名')</code>来调用某字段的值，如果你在值中有html代码，还要使用<code>stripslashes</code>函数来格式化。</p>
      <p>在数据库中保存在$wpdb->postmeta表中，meta_key是<code>_post_metas_中文字段名</code>，你可能在其他场景中用到</p>
    </div>
  </div></div>
</div>
<script>
// 添加一行按钮，这个函数不放到script.js中，因为这里需要一个line值
line.meta = <?php echo $_SESSION['poser'][$ctrl_id]; ?>;
function add_meta_line(pend){
  line.meta ++;
  var html = '<div class="inside box"><div class="btns"><a href="javascript:void(0);" class="up" title="向上移一位">▲</a><a href="javascript:void(0);" class="dn" title="向下移一位">▼</a><a href="javascript:void(0);" class="del" title="删除，注意：不可撤销">&times;</a></div><p>名称：<input type="text" name="options[<?php echo $ctrl_id; ?>]['+line.meta+'][]" class="regular-text short-text"><select name="options[<?php echo $ctrl_id; ?>]['+line.meta+'][]" class="change-meta-type"><option value="1">单行文本</option><option value="2">多行文本</option><option value="3">单选按钮</option><option value="4">多选按钮</option><option value="5">下拉列表</option></select>分类：<input type="text" name="options[<?php echo $ctrl_id; ?>]['+line.meta+'][]" class="regular-text"> <a class="button select-category">选择</a></p><p style="display:none;"><textarea name="options[<?php echo $ctrl_id; ?>]['+line.meta+'][]" class="large-text" rows="3" placeholder="项目一,项目二,项目三"></textarea></p></div>',$list = jQuery('#admin-options-post-metas');
  if(pend == 'append')$list.append(html);
  else $list.prepend(html);
}
</script>
