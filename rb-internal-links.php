<?php
/*
Plugin Name: RB Internal Links
Version: 0.1
Plugin URI: http://www.blograndom.com/extras/
Author: Cohen
Author URI: http://www.blograndom.com/
Description: Use wiki style tags to link to internal posts and pages within your blog without hardcoding them.

Installation: See readme.

*/

// set up important actions and filters 
add_filter('the_content', 'rbinternal_parse_content', 1);
add_filter('the_excerpt', 'rbinternal_parse_content', 1);
add_action('init', 'rbinternal_addbuttons');
add_action('admin_head', 'rbinternal_admin_header', 10);
add_action('admin_menu', 'rbinternal_add_pages');

// set some default options
add_option('rbinternal_tinymce', 1);
add_option('rbinternal_post_orderby', 'post_date');
add_option('rbinternal_post_sort', 'DESC');
add_option('rbinternal_page_orderby', 'post_title');
add_option('rbinternal_page_sort', 'ASC');

$rbinternal_url = get_settings('siteurl').'/wp-content/plugins/rb-internal-links/';

// this function gets the url based on post id or slug
function rbinternal_get_url($post, &$text) {
	global $wpdb;
	
	if(empty($post)) return false;
	$field = (is_numeric($post))? 'ID' : 'post_name';
	$post = $wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts WHERE $field = '$post'");
	if(empty($post->post_title)) return false;
	elseif(empty($text)) $text = $post->post_title;
	
	return get_permalink($post->ID);
	
}

// posts and content get sent to this function which will look for our bbcode
function rbinternal_parse_content($content) {
	$content = str_replace('&quot;', '"', $content);
	$content = str_replace("&#8221;", '"', $content);
	
	$result = '';
	
	$segments = array();
	$codes = array();
	$_segment = rbinternal_strTokeniser($content, '{{');
	if($_segment == '')
		$_segment = '<!-- rbStTkFx -->'; // for some reason the token at the beginning of the content messes things up, hoping to get rid of this at some point
	
	$t = 0;
	while($_segment){		
		$segments[$t] = $_segment;		
		$codes[$t] = rbinternal_strTokeniser($content, '}}');
		
		$_segment = rbinternal_strTokeniser($content, '{{');
		if($_segment == false){
			$segments[$t+1] = $content;
			break;
		}
		$t++;
	}
	
	$result = '';
	for($t=0;$t<count($segments);$t++){
		$result .= $segments[$t];
		
		//parse token
		
		if(!isset($codes[$t])) break;
		$code = $codes[$t];
		$verb = rbinternal_strTokeniser($code, array(' ', '}}'));
		if($verb){
			$params = array();
			
			while(1){
				$fieldName = rbinternal_strTokeniser($code, '=');
				if($fieldName){
					$fieldName = trim($fieldName);
					if($fieldName == '') break;
					$value = rbinternal_strTokeniser($code, '"');
					$value = trim($value, '"');
					
					$params[$fieldName] = $value;
				}else
					break;
			}
			
			$insertStr = rbinternal_render_content(strtolower($verb), $params);
			if($insertStr) $result .= $insertStr;
		}
	}
	
	return $result;
}

function rbinternal_render_content($verb, $params){
	
	switch($verb){
		case 'post': 
			if(!isset($params['id'])) return false;
			if(!isset($params['text'])) $params['text'] = 0;
			$html = '<a href="'. rbinternal_get_url($params['id'], $params['text']) .'" '; 
			if(isset($params['class'])) $html .= 'class="'. $params['class'] . '" ';
			if(isset($params['target'])) $html .= 'target="'. $params['target'] . '" '; 
			$html .= '>'. $params['text'] .'</a>';
			
			return $html;
		default:
			return '[rbinternal code not found]';
	}
}


// tinyMCE functions
function rbinternal_addbuttons() {    
	global $wp_db_version;    
	// Check for WordPress 2.1+ and that its turned on
	if(3664 <= $wp_db_version AND get_option('rbinternal_tinymce') == 1){  
		if ('true' == get_user_option('rich_editing')) {
		add_filter("mce_plugins", "rbinternal_mce_plugins", 10);
		add_filter("mce_buttons", "rbinternal_mce_buttons", 10);
		add_action('tinymce_before_init','rbinternal_external_plugins');
		}
	}
}
function rbinternal_mce_plugins($plugins) {    
	array_push($plugins, "-rbinternallinks");    
	return $plugins;
}
function rbinternal_mce_buttons($buttons) {
	array_push($buttons, "separator", "rbinternallinks");
	return $buttons;
}
function rbinternal_external_plugins() {	
	global $rbinternal_url;
	echo 'tinyMCE.loadPlugin("rbinternallinks", "'.$rbinternal_url.'tmce/rb-internal-links/");' . "\n"; 
	return;
}



function rbinternal_admin_header(){
	global $rbinternal_url;
	echo '<script language="JavaScript" type="text/javascript">' . "\n" . '/* <![CDATA[ */' . "\n";
	echo 'var rbinternal_url="'. $rbinternal_url .'";' . "\n";
	echo '/* ]]> */' . "\n" . '</script>';
}

function rbinternal_add_pages(){
	add_options_page('RB Internal Links', 'RB Internal Links', 8, __FILE__, 'rbinternal_admin_page');
}

function rbinternal_admin_page(){

	if( $_POST['rbinternal_submit'] == 'Y' ) {
    // Read their posted value
		$rbinternal_tinymce = isset($_REQUEST['rbinternal_tinymce'])? $_REQUEST['rbinternal_tinymce'] : 0;
    $rbinternal_post_orderby = $_REQUEST['rbinternal_post_orderby'];
    $rbinternal_post_sort = $_REQUEST['rbinternal_post_sort'];
    $rbinternal_page_orderby = $_REQUEST['rbinternal_page_orderby'];
 		$rbinternal_page_sort = $_REQUEST['rbinternal_page_sort'];
		
		// Save the posted value in the database
    update_option('rbinternal_tinymce', $rbinternal_tinymce);
    update_option('rbinternal_post_orderby', $rbinternal_post_orderby);
    update_option('rbinternal_post_sort', $rbinternal_post_sort);
    update_option('rbinternal_page_orderby', $rbinternal_page_orderby);
    update_option('rbinternal_page_sort', $rbinternal_page_sort);

    // Put an options updated message on the screen
?>
<div class="updated"><p><strong>Settings updated.</strong></p></div>
<?php
    }else{
      $rbinternal_tinymce = get_option('rbinternal_tinymce');
      $rbinternal_post_orderby = get_option('rbinternal_post_orderby');
      $rbinternal_post_sort = get_option('rbinternal_post_sort');
      $rbinternal_page_orderby = get_option('rbinternal_page_orderby');
 			$rbinternal_page_sort = get_option('rbinternal_page_sort');
	}
?>

<div class="wrap">
<h2>RB Internal Link Settings</h2>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="rbinternal_submit" value="Y">

<h3>General</h3>
<ul>
<li><input type="checkbox" name="rbinternal_tinymce" value="1" <?php if($rbinternal_tinymce == 1) echo 'checked="checked"'; ?>> Enable wysiwyg editor plugin.</li>
<li>
	Order the list of posts by 
	<select name="rbinternal_post_orderby">
	<option value="post_date" <?php if($rbinternal_post_orderby == 'post_date') echo 'selected="selected"'; ?>>Post Date</option>
	<option value="post_title" <?php if($rbinternal_post_orderby == 'post_title') echo 'selected="selected"'; ?>>Post Title</option>
	<option value="post_modified" <?php if($rbinternal_post_orderby == 'post_modified') echo 'selected="selected"'; ?>>Modified Date</option>
	<option value="ID" <?php if($rbinternal_post_orderby == 'ID') echo 'selected="selected"'; ?>>Post ID</option>
	<option value="post_author" <?php if($rbinternal_post_orderby == 'post_author') echo 'selected="selected"'; ?>>Post Author</option>
	<option value="post_name" <?php if($rbinternal_post_orderby == 'post_name') echo 'selected="selected"'; ?>>Post Slug</option>
	</select> 
	and sort 
	<select name="rbinternal_post_sort">
	<option value="ASC" <?php if($rbinternal_post_sort == 'ASC') echo 'selected="selected"'; ?>>Ascending</option>
	<option value="DESC" <?php if($rbinternal_post_sort == 'DESC') echo 'selected="selected"'; ?>>Descending</option>
	</select>
</li>
<li>
	Order the list of pages by 
	<select name="rbinternal_page_orderby">
	<option value="post_date" <?php if($rbinternal_page_orderby == 'post_date') echo 'selected="selected"'; ?>>Post Date</option>
	<option value="post_title" <?php if($rbinternal_page_orderby == 'post_title') echo 'selected="selected"'; ?>>Post Title</option>
	<option value="post_modified" <?php if($rbinternal_page_orderby == 'post_modified') echo 'selected="selected"'; ?>>Modified Date</option>
	<option value="ID" <?php if($rbinternal_page_orderby == 'ID') echo 'selected="selected"'; ?>>Post ID</option>
	<option value="post_author" <?php if($rbinternal_page_orderby == 'post_author') echo 'selected="selected"'; ?>>Post Author</option>
	<option value="post_name" <?php if($rbinternal_page_orderby == 'post_name') echo 'selected="selected"'; ?>>Post Slug</option>
	</select> 
	and sort 
	<select name="rbinternal_page_sort">
	<option value="ASC" <?php if($rbinternal_page_sort == 'ASC') echo 'selected="selected"'; ?>>Ascending</option>
	<option value="DESC" <?php if($rbinternal_page_sort == 'DESC') echo 'selected="selected"'; ?>>Descending</option>
	</select>
</li>
</ul>

<p class="submit">
<input type="submit" name="Submit" value="Update Options" />
</p>

<h2>Debug:</h2>
<p>DB Version: <?php global $wp_db_version; echo $wp_db_version; ?></p>
<p>Rich Editing Enabled: <?php if ('true' == get_user_option('rich_editing')) echo 'Yes'; else echo 'No'; ?></p>
<p>Plugin wysiwyg enabled: <?php if (get_option('rbinternal_tinymce') == 1) echo 'Yes'; else echo 'No'; ?></p>

</form>
</div>
<?php
}



function rbinternal_strTokeniser(&$cutString, $_at){
  $offset = 0;
  if(!is_array($_at)) $_at = array($_at);
  foreach($_at as $at){
          $offset = strpos($cutString, $at);
  
          $cutString = $cutString . '';
          if(($offset === false) && (substr($cutString, $offset, 1) != $at)){
                  continue;
          }
  
          if($offset == 0){
                  $offset = strpos($cutString, $at, 1);
                  if($offset !== false) $offset++;
          }
  
          $result = substr($cutString, 0, $offset);
          $offset += strlen($at);
          $cutString = substr($cutString, $offset, strlen($cutString) - $offset);
          return $result;
  }
  return '';
}
?>