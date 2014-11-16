jQuery(function($){
  // 选择分类
  $(document).on('click','.select-category',function(){
    var $this = $(this),
        input_id = $this.attr('data-input-to'),
        $input = (input_id != undefined && $(input_id).length > 0 ? $(input_id) : $this.prev()),
        cats = $input.val().split(','),
        $dialog = $('.select-category-dialog'),
        $list = $dialog.find('.dialog-text li input');
    $list.each(function(){
      var $this = $(this),
        cat = $this.val();
      $this.attr('checked',false);
      if(cats)if($.inArray(cat,cats) >= 0) {
        $this.attr('checked',true);
      }
    });
    $dialog.show();
    $input.attr('id','input-wait-to-insert-cats');
  });
  $('.select-category-dialog').on('click','.submit',function(){
    var $dialog = $('.select-category-dialog'),
        $list = $dialog.find('.dialog-text li input'),
        $input = $('#input-wait-to-insert-cats'),
        cats = [];
    $list.each(function(){
      var $this = $(this),checked = $this.attr('checked');
      if(checked) {
        cats.push($this.val());
      }
    });
    cats = cats.join(',');
    $input.val(cats).removeAttr('id');
    $dialog.hide();
  });
  // 关闭弹窗
  $('.admin-options-dialog').on('click','.close,.dialog-bg',function(){
    $('#input-wait-to-insert-cats').removeAttr('id')
    $('.admin-options-dialog').hide();
  });
});