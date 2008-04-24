<?php
/*
Plugin Name: RB Internal Links
Version: 0.21
Plugin URI: http://www.blograndom.com/blog/extras/
Author: Cohen
Author URI: http://www.blograndom.com/
Description: Use wiki style tags to link to internal posts and pages within your blog without hardcoding them.

Installation: See readme.

*/

$rbinternal_version = '0.21';

// set up important actions and filters 
add_filter('the_content', 'rbinternal_parse_content', 1);
add_filter('the_content_rss', 'rbinternal_parse_content', 1);
add_filter('the_excerpt', 'rbinternal_parse_content', 1);
add_action('init', 'rbinternal_init');
add_action('admin_head', 'rbinternal_admin_header', 10);
add_action('admin_menu', 'rbinternal_add_pages');
add_filter('tiny_mce_version', 'rbinternal_refresh_mce'); // update tinymce cache	


$rbinternal_url = get_settings('siteurl').'/wp-content/plugins/rb-internal-links/';

function rbinternal_init(){

	global $rbinternal_version;
	$current_version = get_option('rbinternal_version');
	
	if($rbinternal_version != $current_version){
		rbinternal_update($rbinternal_version, $current_version);
		update_option('rbinternal_version', $rbinternal_version);
	}
	

	rbinternal_addbuttons();
	
}

function rbinternal_update($version, $current_version){
	
	// set some default options
	add_option('rbinternal_tinymce', 1);
	add_option('rbinternal_post_orderby', 'post_date');
	add_option('rbinternal_post_sort', 'DESC');
	add_option('rbinternal_page_orderby', 'post_title');
	add_option('rbinternal_page_sort', 'ASC');
	add_option('rbinternal_return_par	am', 'ID');
		
}

function rbinternal_refresh_mce($ver) {
  $ver += 36;
  return $ver;
}

// this function gets the url based on post id or slug
function rbinternal_get_url($id, &$text, $type) {
	global $wpdb;
	
	if(empty($id)) return false;
	
	switch($type){
		case 'post':
			$field = (is_numeric($id))? 'ID' : 'post_name';
			$post = $wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts WHERE $field = '$id'");
			if(empty($post->post_title)) return false;
			elseif(empty($text)) $text = $post->post_title;
			
			return get_permalink($post->ID);
		case 'category':
			return get_category_link($id);
		default:
			return false;
	}
	
}

// posts and content get sent to this function which will look for our bbcode
function rbinternal_parse_content($content) {

	if(strpos($content, "{{post") !== FALSE OR strpos($content, "<!--intlink") !== FALSE)
		$content = preg_replace("/({{|<!--)(post|intlink)([^}}].*?|[^-->].*?)(}}|-->)/ei", "rbinternal_parse_params('\\2', '\\3')", $content);
		// {{ * }} for backwards compatibility
		
	return $content;
	
}

function rbinternal_parse_params($verb, $paramStr){

		$paramStr = stripslashes($paramStr);
  	$paramStr = str_replace('&quot;', '"', $paramStr);
  	$paramStr = str_replace("&#8221;", '"', $paramStr);
		
		preg_match_all("/(\w+)\=\"([^\"].*?)\"/i", $paramStr, $matches);
		
		if(is_array($matches[1]) AND is_array($matches[2]))
			foreach($matches[1] AS $i=>$key)
				$params[$key] = isset($matches[2][$i])? $matches[2][$i] : false;
		
		return rbinternal_render_content($verb, $params);

}

function rbinternal_render_content($verb, $params){
	
	if(!isset($params['type'])) $params['type'] = 'post';
	
	switch($verb){
		case 'intlink':
		case 'post': //backwards compatibility
			if(!isset($params['id'])) return false;
			if(!isset($params['text'])) $params['text'] = 0;
			$html = '<a href="'. rbinternal_get_url($params['id'], $params['text'], $params['type']) . ((isset($params['anchor']))? '#' . $params['anchor'] : '') . '" '; 
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
	// Check for WordPress 2.5+ and that its turned on
	if($wp_db_version >= 7098 AND get_option('rbinternal_tinymce') == 1){
		// Don't bother doing this stuff if the current user lacks permissions
	   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
	     return;
	 
	   // Add only in Rich Editor mode
	   if ( get_user_option('rich_editing') == 'true') {
	     add_filter("mce_external_plugins", "rbinternal_external_plugins_25");
	     add_filter('mce_buttons', 'rbinternal_mce_buttons');
	     add_filter("mce_css", "rbinternal_mce_css");
	   }
	// Check for WordPress 2.1+ and that its turned on
	}elseif(3664 <= $wp_db_version AND get_option('rbinternal_tinymce') == 1){  
		if ('true' == get_user_option('rich_editing')) {
		add_filter("mce_plugins", "rbinternal_mce_plugins", 10);
		add_filter("mce_buttons", "rbinternal_mce_buttons", 10);
		add_action('tinymce_before_init','rbinternal_external_plugins');
		}
	}
}
// pre v2.5 tinymce plugin load
function rbinternal_mce_plugins($plugins) {    
	array_push($plugins, "-rbinternallinks");    
	return $plugins;
}
// pre 2.5 plugin load
function rbinternal_external_plugins() {	
	global $rbinternal_url;
	echo 'tinyMCE.loadPlugin("rbinternallinks", "'.$rbinternal_url.'tmce/rb-internal-links/");' . "\n"; 
	return;
}
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function rbinternal_external_plugins_25($plugin_array) {
	global $rbinternal_url;
   $plugin_array['rbinternallinks'] = $rbinternal_url.'tmce/rb-internal-links/editor_plugin_25.js';
   return $plugin_array;
}
// 2.5 + pre 2.5 button load
function rbinternal_mce_buttons($buttons) {
	array_push($buttons, "separator", "rbinternallinks");
	return $buttons;
}
// 2.5 + mce css load
function rbinternal_mce_css($css){
	global $rbinternal_url;
	return $css . ',' . $rbinternal_url.'styles.css';
}



function rbinternal_admin_header(){
	global $rbinternal_url;
	echo '<script language="JavaScript" type="text/javascript">' . "\n" . '// <![CDATA[ ' . "\n";
	echo 'var rbinternal_url="'. $rbinternal_url .'";' . "\n";
	echo '// ]]>' . "\n" . '</script>';
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
		$rbinternal_return_param = $_REQUEST['rbinternal_return_param'];
		
		// Save the posted value in the database
    update_option('rbinternal_tinymce', $rbinternal_tinymce);
    update_option('rbinternal_post_orderby', $rbinternal_post_orderby);
    update_option('rbinternal_post_sort', $rbinternal_post_sort);
    update_option('rbinternal_page_orderby', $rbinternal_page_orderby);
    update_option('rbinternal_page_sort', $rbinternal_page_sort);
		update_option('rbinternal_return_param', $rbinternal_return_param);

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
			$rbinternal_return_param = get_option('rbinternal_return_param');
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
	Return the 
	<select name="rbinternal_return_param">
	<option value="ID" <?php if($rbinternal_return_param == 'ID') echo 'selected="selected"'; ?>>article ID</option>
	<option value="slug" <?php if($rbinternal_return_param == 'slug') echo 'selected="selected"'; ?>>article slug</option>
	</select>
	to the editor
</li>
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
?>