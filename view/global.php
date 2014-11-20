<div class="option-box" id="admin-options-<?php echo $ctrl_id; ?>">
  <form method="post" enctype="multipart/form-data">
  <h2>全局设置</h2>
  <div class="metabox-holder">
  <div class="postbox">
    <h3 class="hndle">核心设置</h3>
    <div class="inside">
      <p>撰稿模式：<select name="options[mode]">
        <option value="0">个人独撰</option>
        <option value="1" <?php selected($admin_options['mode'],1); ?>>多人合作</option>
        <option value="2" <?php selected($admin_options['mode'],2); ?>>众人投稿</option>
      </select>
      如果打算让别人注册，还需要在<a href="<?php echo admin_url('options-general.php'); ?>">常规设置</a>中开放成员注册。
    </p>
    </div>
    <div class="inside">
      <p><label><input type="checkbox" name="options[home_list_sticky]" value="1" <?php checked($admin_options['home_list_sticky'],1); ?>> 首页文章列表按发布时间倒序排（不勾选的情况下置顶的文章出现在列表最前面）</label></p>
    </div>
    <div class="inside">
      <p><?php wp_dropdown_pages(array(
        'selected'              => $admin_options['page_for_tougao'],
        'name'                  => 'options[page_for_tougao]'
      )); ?> 用来作为投稿页面。</p>
    </div>
    <div class="inside">
      <p><?php wp_dropdown_pages(array(
        'selected'              => $admin_options['page_for_usercenter'],
        'name'                  => 'options[page_for_usercenter]'
      )); ?> 用来作为用户中心。</p>
    </div>
    <div class="inside">
      <p><label>Pad版式 ≤ <input type="text" name="options[response_pad_width]" class="regular-text short-text" value="<?php echo $admin_options['response_pad_width']; ?>">px</label>，<label>Phone版式 ≤ <input type="text" name="options[response_phone_width]" class="regular-text short-text" value="<?php echo $admin_options['response_phone_width']; ?>">px</label>。<br><small>设置好之后用pad或手机浏览你的网站检查效果。推荐为800和480。留空时不使用自适应屏幕功能。</small></p>
    </div>
    <div class="inside">
      <p><label>自动加载<input type="text" name="options[pagenavi]" class="regular-text short-text" value="<?php echo $admin_options['pagenavi']; ?>">页后停止自动加载，显示页数导航。<small>不是指第几页，而是打算往后加载几页。</small></label></p>
    </div>
    <div class="inside">
      <p><label><input type="checkbox" name="options[img_lazyload]" value="1" <?php checked($admin_options['img_lazyload'],1); ?>> 主要区域（.post img）内的图片延时加载（不勾选的情况下图片正常显示）</label></p>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">Logo 设置</h3>
    <div class="inside">
      <p><input type="text" name="options[logo]" class="regular-text" value="<?php echo $admin_options['logo']; ?>"> <a class="button upload-media">上传</a></p>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">版权设置</h3>
    <div class="inside">
      <?php wp_editor(stripslashes($admin_options['copyright']),'admin-options-copyright',$settings = array(
        'textarea_name' => 'options[copyright]',
        'textarea_rows' => 3
      )); ?>
    </div>
  </div>
  </div>
  <p class="submit">
    <input name="save" type="submit" class="button-primary" value="提交" />
  </p>
  <input type="hidden" name="save_admin_options" value="1" />
  <?php wp_nonce_field(); ?>
  </form>
</div>