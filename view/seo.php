<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <h2>SEO Ttitle（标题） Description（网页描述） Keywords（网页关键词）设置</h2>
  <form method="post" autocomplete="off">
  <div class="metabox-holder">
  <div class="postbox">
    <h3 class="hndle">全局设置</h3>
    <div class="inside"><p>
      开关设置：
      <label><input type="checkbox" name="options[<?php echo $ctrl_id; ?>][switch_home]" value="1" <?php checked($admin_options['seo']['switch_home']); ?>>首页</label>
      <label><input type="checkbox" name="options[<?php echo $ctrl_id; ?>][switch_cat]" value="1" <?php checked($admin_options['seo']['switch_cat']); ?>>分类页</label>
      <label><input type="checkbox" name="options[<?php echo $ctrl_id; ?>][switch_tag]" value="1" <?php checked($admin_options['seo']['switch_tag']); ?>>标签页</label>
      <label><input type="checkbox" name="options[<?php echo $ctrl_id; ?>][switch_post]" value="1" <?php checked($admin_options['seo']['switch_post']); ?>>内容页</label>
    </p></div>
    <div class="inside">
      <p>间隔符：<input type="text" class="regular-text" style="width:60px;" name="options[<?php echo $ctrl_id; ?>][slip]" value="<?php echo $admin_options['seo']['slip']; ?>"></p>
      <p><code>一般来说都从"-"、"_"、"|"中进行选择。</code></p>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">首页设置</h3>
    <div class="inside">
      <p>标题：<input type="text" class="regular-text" name="options[<?php echo $ctrl_id; ?>][blog_title]" value="<?php echo $admin_options[$ctrl_id]['blog_title']; ?>" /></p>
    </div>
    <div class="inside">
      <p>关键词：<input type="text" class="regular-text" name="options[<?php echo $ctrl_id; ?>][blog_keywords]" value="<?php echo $admin_options[$ctrl_id]['blog_keywords']; ?>" /></p>
    </div>
    <div class="inside">
      <p>网页描述：<br>
        <textarea class="large-text" name="options[<?php echo $ctrl_id; ?>][blog_description]"><?php echo $admin_options[$ctrl_id]['blog_description']; ?></textarea>
      </p>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">分类（category）标签（tag）页设置</h3>
    <div class="inside">
      <p>标题格式：<select name="options[<?php echo $ctrl_id; ?>][term_title]">
        <option value="0" <?php selected($admin_options[$ctrl_id]['term_title'],0); ?>>分类名-博客名</option>
        <option value="1" <?php selected($admin_options[$ctrl_id]['term_title'],1); ?>>分类名-父分类-博客名</option>
      </select></p>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">文章（post）页设置</h3>
    <div class="inside">
      <p>标题格式：<select name="options[<?php echo $ctrl_id; ?>][post_title]">
        <option value="0" <?php selected($admin_options[$ctrl_id]['post_title'],0); ?>>文章名-博客名</option>
        <option value="1" <?php selected($admin_options[$ctrl_id]['post_title'],1); ?>>文章名-分类层级-博客名</option>
      </select></p>
    </div>
  </div>
  </div>
  <p class="submit">
    <input name="save" type="submit" class="button-primary" value="提交" />
  </p>
  <input type="hidden" name="save_admin_options" value="1" />
  <?php wp_nonce_field(); ?>
  </form>

  <div class="metabox-holder" ><div class="postbox">
    <h3 class="hndle">说明</h3>
    <div class="inside">
      <p>修改你的主题文件，网页的标题输出直接使用<code><?php echo htmlspecialchars("<title><?php wp_title(''); ?></title>"); ?></code>来输出，头部中必须包含<code><?php echo htmlspecialchars("<?php wp_head(); ?>"); ?></code>才能输出关键词和描述。具体你可以看下<a href="http://www.utubon.com/?p=1612" target="_blank">这篇文章</a>。</p>
    </div>
    <div class="inside">
      <p>上方勾选开关，才会起效，虽然你可能设置了分类、标签等页面的SEO，但是如果你不勾选的话，在这些页面仍然不起效。</p>
    </div>
    <div class="inside">
      <p>一般情况下，wp_title都会直接打印出分类名作为分类页的标题，本功能允许你设置自己的分类页标题。在编辑具体的分类页时可以看到category_meta字段，你可以填写对应的值。分类页的描述将直接采用分类描述。<br>
      注意：这些meta值应该与你在这里填写的标题格式进行统筹规划。</p>
      <p>例如，您在原本为“帆布鞋”的分类中填写了新的标题字段为“帆布鞋 凡客诚品”，那么在页面中将使用后面的代替前面的，如你的标题将变为“帆布鞋 凡客诚品-父分类-根分类-博客名”</p>
      <p>分类页的关键词由category_meta_keywords确定，如果不填写，直接使用分类名。</p>
      <p>分类页的描述由分类的描述确定。</p>
      <p>因为标签没有父标签之说，所以这里的设置对标签无效。</p>
    </div>
    <div class="inside">
      <p>文章页的重要性不必多说了吧！</p>
      <p>文章页的关键词：首先使用文章标签作为关键词，接着你自己创建的keywords自定义栏目的值作为关键词，再接着使用文章名和分类名称作为关键词。本插件会同时使用它们，无论缺少谁都不会直接影响关键词的使用。</p>
      <p>文章页的描述：首先使用文章自定义栏目description的值作为描述，如果没有的话，使用填写的文章摘要作为描述，如果还没有的话，摘取文章开头的150个字作为描述。注意，它们之间的选择是有先后顺序的，如果你同时填写了description自定义栏目和摘要，只会选择自定义栏目的值作为摘要。记住，你的文章开头150个字也很重要。</p>
    </div>
  </div></div>
</div>
