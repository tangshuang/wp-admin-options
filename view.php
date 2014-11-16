<div class="admin-options-header">
  <h1>Admin Options <small>/ by <a href="http://weibo.com/hz184">@否子戈</a></small></h1>
</div>
<div class="wrapper">
  <div class="admin-options">
    <div class="admin-options-menu">
      <ul>
        <?php foreach($admin_options_config['menu'] as $menu) { echo '<li><a href="#admin-options-'.$menu['id'].'">'.$menu['name'].'</a></li>'; } ?>
      </ul>
    </div>
    <div class="admin-options-container">
      <?php
      foreach($admin_options_config['menu'] as $ctrl) {
        $ctrl_id = $ctrl['id'];
        $tpl = dirname(__FILE__)."/view/{$ctrl_id}.php";
        if(file_exists($tpl))include($tpl);
      } ?>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="admin-options-footer">
  <p>Admin Options 是我开发的一套WordPress后台快速开发框架，主要是为开发过程建立后台管理面板节省时间，可用性更高。</p>
  <p>它有点类似MVC的思路，如果没有特别的hook要挂载，只要在controller.php中加入菜单项，然后在view文件夹下面丢入一个和菜单id同名的php文档，在这个php文档中加入name=options[yourkey]，提交这个表达，这个yourkey就被记录到数据库中了，在主题前台文件中只需要global $admin_options;再使用$admin_options[yourkey]就可以调用这个值。</p>
  <p>当然，它还有一些不完善的地方，我把它托管在github，你可以<a href="https://github.com/tangshuang/wp-admin-options" target="_blank">点击这里Fork</a>它，它已经内置了logo、seo、幻灯、广告、社交媒体等选项，这些功能是完全免费的，如果你喜欢，希望你在自己的网站中添加我的个人网站的友情链接：<a href="http://www.utubon.com" target="_blank">乌徒帮</a>。我会把它的开发文档丢到github中，并且在我的网站和<a href="http://weibo.com/hz184" target="_blank">微博</a>中会放出升级或进一步开发的信息，欢迎你参与讨论。</p>
</div>
<?php
do_action('admin_options_view_loaded');