jQuery(function($){
  // 控制面板
  $('.admin-options-menu > ul > li:first-child').addClass('selected');// 显示第一个菜单
  $('.admin-options-container > .option-box:first-child').fadeIn(500,function(){
      $('html,body').animate({scrollTop:0},500);
    });// 显示第一个面板
  // 点击菜单效果
  $('.admin-options-menu > ul > li').click(function(e){
    var $this = $(this),box = $this.children('a').attr('href'),$box = $(box);
    if($box.is(':visible'))return false;
    window.location.hash = box;
    e.preventDefault();
    $('.admin-options-container .option-box').hide();
    $box.fadeIn(500,function(){
        $('html,body').animate({scrollTop:0},500);
      });
    $('.admin-options-menu ul li').removeClass('selected');
    $this.addClass('selected');
  });
  // 窗口打开的时候，特别是存在hash可以显示某一个面板的时候
  function window_setup() {
    var hash = window.location.hash;
    if(hash != '') {
      var $box = $(hash);
      if($box.is(':visible'))return;
      $('.admin-options-container .option-box').hide();
      $box.fadeIn(500,function(){
        $('html,body').animate({scrollTop:0},500);
      });
      $('.admin-options-menu ul li').removeClass('selected');
      $('.admin-options-menu > ul li').each(function(){
        if($(this).children('a').attr('href') == hash)$(this).addClass('selected');
      });
    }
  }
  window_setup();
  // 显示和隐藏区域块
  $('.metabox-holder .postbox .handlediv').click(function(){
    $(this).parent().toggleClass('closed');
  });
  // 调整位置的按钮▲▼
  $('.admin-options-list').on('click','.box .btns a.up',function(e){
    var $box = $(this).parent().parent(),$prevBox = $box.prev('.box');
    $prevBox.before($box);
    $box.hide();
    $box.fadeIn(500);
  });
  $('.admin-options-list').on('click','.box .btns a.dn',function(e){
    var $box = $(this).parent().parent(),$nextBox = $box.next('.box');
    $nextBox.after($box);
    $box.hide();
    $box.fadeIn(500);
  });
  // 点击删除按钮
  $('.admin-options-list').on('click','.box .btns a.del',function(e){
    var $box = $(this).parent().parent(),$list = $box.parent(),$btns = $list.prev().find('a');
    if(confirm("删除不可撤销，确认删除吗？")) {
      $box.fadeOut(500,function(){
        $box.remove()
        if($list.find('.box').length <= 0) {
          $btns.eq(0).trigger('click');
        }
      });
    }
  });
  // metas更换类型
  $('#admin-options-post-metas').on('change','.box select',function(){
    var $this = $(this),
        $list = $this.parent().next('p'),
        value = $this.val();
    if(value > 2 && $list.is(':hidden'))$list.slideDown(500);
    else if(value <= 2 && $list.is(':visible')) $list.slideUp(500);
  });
  // 把textarea项目列表中的，替换为,
  $('#admin-options-post-metas').on('keyup','.box textarea',function(){
    var $this = $(this),value = $this.val();
    value = value.replace('，',',');
    $this.val(value);
  });

});

// 清除列表列表中的无效行按钮，这个必须放在jquery外面，因为function在html中被直接用，放在jquery里面就无法直接在html中使用了
function clear_line_in_list(element,name) {
  var $element = jQuery(element),$boxes = $element.find('.box');
  $boxes.each(function(){
    var $box = jQuery(this),
        $lines = $box.find("input[name^='"+name+"'],textarea[name^='"+name+"']"),
        $btns = $element.prev().find('a'),
        is_delete = true;
    $lines.each(function(){
      if(jQuery(this).val() != '')is_delete = false;
    });
    if(is_delete) {
      $box.fadeOut(500,function(){
        $box.remove();
        if($element.find('.box').length <= 0) {
          $btns.eq(0).trigger('click');
        }
      });
    }
  });
}

var line = {};
