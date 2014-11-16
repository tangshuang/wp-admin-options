<?php

class AdminOptionsSEO {

  function __construct() {
    global $admin_options;
    add_filter('wp_title',array(&$this,'title_filter'),100);
    add_action('wp_head',array(&$this,'head_action'),0);
    if($admin_options[$ctrl_id]['switch_cat'] == 1){
      //add_action('category_add_form_fields','extra_term_fields');
      //add_action('created_category','save_extra_term_fileds');
      add_action('edit_category_form_fields',array(&$this,'extra_term_fields'));
      add_action('edited_category',array(&$this,'save_extra_term_fileds'));
    }
    if($admin_options[$ctrl_id]['switch_tag'] == 1){
      //add_action('add_tag_form_fields','extra_term_fields');
      //add_action('created_post_tag','save_extra_term_fileds');
      add_action('edit_tag_form_fields',array(&$this,'extra_term_fields'));
      add_action('edited_post_tag',array(&$this,'save_extra_term_fileds'));
    }
    //add_action('[taxonomy]_add_form_fields','extra_term_fields');
    //add_action('[taxonomy]_edit_form_fields','extra_term_fields');
    //add_action('created_[taxonomy]','save_extra_term_fileds');
    //add_action('edited_[taxonomy]','save_extra_term_fileds');
  }

  // 创建一个函数，用以清除描述文本中的换行、HTML标签等干扰信息，只留下文本
  function clear_code($string){
      $string=str_replace("\r\n",'',$string);//清除换行符
      $string=str_replace("\n",'',$string);//清除换行符
      $string=str_replace("\t",'',$string);//清除制表符
      $pattern=array("/> *([^ ]*) *</","/[\s]+/","/<!--[^!]*-->/","/\" /","/ \"/","'/\*[^*]*\*/'","/\[(.*)\]/");
      $replace=array(">\\1<"," ","","\"","\"","","");
      return preg_replace($pattern,$replace,$string);
  }

  // 优化网页标题，对wp_title的输出格式进行变化
  function title_filter($title){
    global $page,$paged,$post,$admin_options;
    $ctrl_id = basename(__FILE__,'.php');
    $seo = $admin_options[$ctrl_id];
    $slip = (trim($seo['slip']) == '' ? '_' : trim($seo['slip']));
    $title = trim($title);


    if($seo['switch_home'] != 1 && $seo['switch_cat'] != 1 && $seo['switch_tag'] != 1 && $seo['switch_post'] != 1)return $title;

    // 首页标题优化
    if((is_home() || is_front_page()) && $seo['switch_home'] == 1){
      if($seo['blog_title']){
        $title = $this->clear_code($seo['blog_title']);
      }else{
        $title = get_bloginfo('name').$slip.get_bloginfo('description');
      }
    }
    // 分类页标题
    elseif(is_category() && $seo['switch_cat'] == 1){
      global $cat;
      $cat_id = is_object($cat) ? $cat->cat_ID : $cat;
      $cat_title = single_cat_title('',false);
      $cat_seo_title = trim($this->get_term_meta($cat_id,'seo_title'));
      $title = $cat_seo_title ? $cat_seo_title : $title;
      if($seo['term_title'] == 1){
        $category = get_category($cat_id);
        while($category->parent){
          $category = get_category($category->parent);
          $title .= $slip.$category->cat_name;
        }
      }
      $title .= $slip.get_bloginfo('name');
    }
    // 标签页标题
    elseif(is_tag() && $seo['switch_tag'] == 1){
      global $wp_query;
      $tag_id = $wp_query->queried_object->term_id;
      $tag_name = $wp_query->queried_object->name;
      $tag_seo_title = trim($this->get_term_meta($tag_id,'seo_title'));
      $title = $tag_seo_title ? $tag_seo_title : $tag_name;
      $title .= $slip.get_bloginfo('name');
    }
    // 文章页的标题
    elseif(is_singular() && $seo['switch_post'] == 1){
      $title = ($post->post_title ? $post->post_title : $post->post_date);
      if($seo['post_title'] == 1){
        $category = get_the_category();
        $category = $category[0];
        while($category->cat_ID){
          $title .= $seo_slip.$category->cat_name;
          $category = get_category($category->parent);
        }
      }
      $title .= $slip.get_bloginfo('name');
    }
    elseif(is_feed()){
      return $title;
    }
    // 其他情况
    else{
      $title .= $slip.get_bloginfo('name');
    }
    if($paged >= 2 || $page >= 2){
      $title .= $seo_slip.sprintf(__('第%s页'),max($paged,$page));
    }
    $title = $this->clear_code($title);
    return $title;
  }

  // 将关键词和描述输出在wp_head区域
  function head_action(){
    $this->keywords();
    $this->description();
  }
  // 网页关键字描述
  function keywords(){
    global $admin_options;
    $ctrl_id = basename(__FILE__,'.php');
    $seo = $admin_options[$ctrl_id];

    if($seo['switch_home'] != 1 && $seo['switch_cat'] != 1 && $seo['switch_tag'] != 1 && $seo['switch_post'] != 1)return;
    // 为了避免翻页带来的问题，把翻页以后的给屏蔽掉
    if(is_paged())return;
    
    $keywords = '';
    if((is_home() || is_front_page()) && $seo['switch_home'] == 1){
      $keywords = $seo['blog_keywords'];
    }
    elseif(is_category() && $seo['switch_cat'] == 1){
      global $cat;
      $cat_id = is_object($cat) ? $cat->cat_ID : $cat;
      $cat_keywords = trim($this->get_term_meta($cat_id,'seo_keywords'));
      if($cat_keywords){
        $keywords = $cat_keywords;
      }else{
        $keywords = single_cat_title('',false);
      }
    }
    elseif(is_tag() && $seo['switch_tag'] == 1){
      global $wp_query;
      $tag_id = $wp_query->queried_object->term_id;
      $tag_keywords = trim($this->get_term_meta($tag_id,'seo_keywords'));
      if($tag_keywords){
        $keywords = $tag_keywords;
      }else{
        $keywords = $wp_query->queried_object->name;
      }
    }
    elseif(is_singular() && $seo['switch_post'] == 1){
      global $post;
      // 第一种是使用标签
      $tags = strip_tags(get_the_tag_list('',',',''));
      // 第二种是使用自定义域的keywords
      $metakeywords = trim(stripslashes(strip_tags(get_post_meta($post->ID,'keywords',true))));
      // 第三种是使用分类名称
      $cats = '';
      $categories = get_the_category();
      foreach($categories as $category){
        $cats .= ','.$category->cat_name;
      }
      // 当存在标签时，使用标签；当存在自定义keywords时，把它附加到标签上，如果没有标签，就使用自定义的keywords；如果这两者都不存在，就使用分类名称
      if($tags && $metakeywords){
        $keywords = $tags.','.$metakeywords;
      }elseif($tags && !$metakeywords){
        $keywords = $tags;
      }elseif(!$tags && $metakeywords){
        $keywords = $metakeywords;
      }else{
        $keywords = $post->post_title;
      }
      $keywords .= $cats;
      $keywords = trim(str_replace('"','',$keywords));
      $keywords = $this->clear_code($keywords);
    }
    if($keywords)echo '<meta name="keywords" content="'.$keywords.'" />'."\n";
  }
  // 网页描述
  function description(){
    global $admin_options;
    $ctrl_id = basename(__FILE__,'.php');
    $seo = $admin_options[$ctrl_id];
    
    if($seo['switch_home'] != 1 && $seo['switch_cat'] != 1 && $seo['switch_tag'] != 1 && $seo['switch_post'] != 1)return;
    // 为了避免翻页带来的问题，把翻页以后的给屏蔽掉
    if(is_paged())return;

    $description = '';
    if((is_home() || is_front_page()) && $seo['switch_home'] == 1){
      $description = __(strip_tags($seo['blog_description']));
    }
    elseif(is_category() && $seo['switch_cat'] == 1){
      $description = __(strip_tags(category_description()));
    }
    elseif(is_tag() && $seo['switch_tag'] == 1){
      $description = __(strip_tags(tag_description()));
    }
    elseif(is_singular() && $seo['switch_post'] == 1){
      global $post;
      // 第一种是使用自定义域的
      $description = __(strip_tags(get_post_meta($post->ID,'description',true)));
      // 第二种是使用摘要
      $excerpt = __(strip_tags($post->post_excerpt));
      // 第三种是使用文章的前200字
      $content = mb_strimwidth(__(strip_tags($post->post_content)),0,300,'...');
      // 将三者结合起来
      if($description == '')$description = $excerpt;
      if($description == '')$description = $content;
      $description = str_replace('"','',$description);
      $description = $this->clear_code(trim($description));
    }
    if($description)echo '<meta name="description" content="'.$description.'" />'."\n";
  }

  /**
  下方的代码用以实现term_meta
  **/

  function extra_term_fields($term){
    $metas = array(
      array('meta_name' => 'SEO Title','meta_key' => 'seo_title'),
      array('meta_name' => 'SEO Keywords','meta_key' => 'seo_keywords')
    );
    $term_id = $term->term_id;
    foreach($metas as $meta) {
      $meta_name = $meta['meta_name'];
      $meta_key = $meta['meta_key'];
      $meta_value = get_option("term_{$term_id}_meta_{$meta_key}");
      ?>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="term_<?php echo $meta_key; ?>"><?php echo $meta_name; ?></label></th>
    <td><input type="text" name="term_meta_<?php echo $meta_key; ?>" id="term_<?php echo $meta_key; ?>" class="regular-text" value="<?php echo $meta_value; ?>"></td>
  </tr>
      <?php
    }
  }

  function save_extra_term_fileds($term_id){
    if(!empty($_POST))foreach($_POST as $key => $value){
      echo $key;
      if(strpos($key,'term_meta_') === 0 && trim($value) != '') {
        $meta_key = str_replace('term_meta_','',$key);
        $meta_value = trim($value);
        update_option("term_{$term_id}_meta_{$meta_key}",$meta_value) OR add_option("term_{$term_id}_meta_{$meta_key}",$meta_value);
      }
    }
  }

  function get_term_meta($term_id,$meta_key){
    if(is_object($term_id))$term_id = $term_id->term_id;
    $term_meta = get_option("term_{$term_id}_meta_{$meta_key}");
    if($term_meta){
      return $term_meta;
    }else{
      return null;
    }
  }

} // END OF CLASS
$AdminOptionsSEO = new AdminOptionsSEO();