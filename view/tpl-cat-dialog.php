<div class="admin-options-dialog select-category-dialog">
  <div class="dialog-bg"></div>
  <div class="dialog-content"><div class="dialog-container">
    <a class="dialog-close close">&times;</a>
    <div class="dialog-title"><?php _e('选择分类'); ?></div>
    <div class="dialog-text"><ul><?php dropdown_categories(); ?></ul><div class="clear"></div></div>
    <div class="dialog-btns">
      <a href="javascript:void(0)" class="button close"><?php _e('取消'); ?></a>
      <a href="javascript:void(0)" class="button-primary submit"><?php _e('确定'); ?></a>
    </div>
  </div></div>
</div>