<?php 

include("../../../wp-config.php");
$linktext = isset($_REQUEST['linktext'])? str_replace('\"', '\'', $_REQUEST['linktext']) : ''; // get preselected html and remove "'s

$action = $_REQUEST['action'];
switch($action){
	case 'get_pages':
		$returnDiv = $_REQUEST['returnDiv'];
		$category = $_REQUEST['category'];
		if($category == 'pages')
			$articles = get_pages('numberposts=0&orderby='.get_option('rbinternal_page_orderby').'&order='.get_option('rbinternal_page_sort'));
		else 
			$articles = get_posts('numberposts=0&category='.$category.'&orderby='.get_option('rbinternal_post_orderby').'&order='.get_option('rbinternal_post_sort'));
		$posts = '<ul>';
		foreach($articles AS $article)
			$posts .= '<li id="article_'.$article->ID.'"><span class="date">'.substr($article->post_date, 0, 10).'</span><a href="javascript:;" onclick="properties(\''.$article->post_name.'\');">'.$article->post_title.'</a></li>';
		if(empty($articles)) $posts .= '<li>No posts found.</li>';
		$posts .= '</ul>';
		die("document.getElementById('".$returnDiv."').innerHTML = \"".addslashes(str_replace("\n", '', $posts))."\"");

}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RB Internal Linker</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script language="javascript" type="text/javascript">
	var rbinternal_url = "<?php echo $rbinternal_url; ?>"; 
	</script>
	<script language="javascript" type="text/javascript" src="../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
  <?php wp_print_scripts( array( 'sack' )); ?>
	<script language="javascript" type="text/javascript">
	var the_postId = 0;
	
	function showcategories(){
	  document.getElementById('categoriesContainer').style.display='block';
		document.getElementById('pagesContainer').style.display='none';
		document.getElementById('propertiesContainer').style.display='none';
		document.getElementById('pages').innerHTML='';
		document.getElementById('linkText').value = '<?php echo $linktext; ?>';
		document.getElementById('linkClass').value = '';
		document.getElementById('linkTarget').value = '';
	}
	
	
	function getposts(category){
  	var mysack = new sack( "tmce_plugin.php" );
  	mysack.execute = 1;
  	mysack.method = 'POST';
  	mysack.setVar("action", "get_pages");
		mysack.setVar("returnDiv", "pages");
		mysack.setVar("category", category);
  	mysack.onError = function() { alert('AJAX error in voting' )};
    mysack.runAJAX();
  
		document.getElementById('pages').innerHTML = 'Loading posts...';
  	document.getElementById('categoriesContainer').style.display='none';
		document.getElementById('pagesContainer').style.display='block';
  
  	return true;
  
  }
	
	function properties(postId){
		the_postId = postId;
		document.getElementById('pagesContainer').style.display='none';
		document.getElementById('propertiesContainer').style.display='block';
	}
	
	function insertLink(){
	
	if(the_postId == 0){ showcategories(); return false; }
	
	the_text = document.getElementById('linkText').value;
	the_class = document.getElementById('linkClass').value;
	the_target = document.getElementById('linkTarget').value;
	
	rCode = '{{post id="' + the_postId + '" text="' + the_text + '"';
	if(the_class != '') rCode += ' class="' + the_class + '"';
	if(the_target != '') rCode += ' target="' + the_target + '"';
	rCode += '}}';
	
	tinyMCEPopup.execCommand("mceInsertContent", false, rCode);
	tinyMCEPopup.close();

	}
	
	</script>
	
	<style>
	body{font-family: arial, verdana, tahoma, sans-serif; font-size: 12px;}
	#container{ scroll: auto; overflow: auto; width: 100%; height: 100%; }
	h2{font-size: 14px; font-weight: bold; margin-bottom: 8px;}
	a{text-decoration: none;}
	a:hover{text-decoration: underline;}
	ul{list-style-type: none; margin: 0; padding: 0;}
	li{padding: 2px;}
	ul.children{margin-left: 10px;}
	.date{background-color: #E5F3FF; margin-right: 2px; padding: 1px;}
	.row{position: relative; float: left; clear: left; margin-bottom: 5px;}
	.action{position: relative; float: left; clear: left; padding: 2px; background-color: #E5F3FF; width: 330px; text-align: right;}
	.textinput{ width: 250px; }
	label{position: relative; float: left; clear: left; margin: 0 5px 5px 0; width: 80px; padding-top: 2px;}
	.back{position: relative; float: left; clear: left; margin-top: 10px;}
	</style>	
	
</head>
<body>

<div id="container">

<div id="categoriesContainer">

  <h2>Categories</h2>
  <ul>
  <?php
  $categories = get_categories(array('type' => 'post', 'orderby' => 'name', 'hide_empty' => true));
  rbinternal_tier_categories($categories, 0);
  
  function rbinternal_tier_categories($data, $parent){
  	foreach($data AS $cat){
  		if($cat->category_parent == $parent){
  			echo '<li id="category_'.$cat->cat_ID.'"><a href="javascript:;" onclick="getposts('.$cat->cat_ID.');">'.$cat->cat_name.'</a><ul class="children">';
  			echo rbinternal_tier_categories($data, $cat->cat_ID);
  			echo '</ul></li>';
  		}
  	}	
  }
  
  ?>
	<li id="category_pages"><a href="javascript:;" onclick="getposts('pages');">Pages</a>
  </ul>

</div>

<div id="pagesContainer" style="display: none;">
  <h2>Pages</h2>
  <div id="pages"></div>
  <a href="javascript:;" onclick="showcategories();" class="back">Back to categories</a>
</div>

<div id="propertiesContainer" style="display: none;">
	<h2>Properties</h2>
	<div class="row">
		<label>Link Text:</label><input type="text" class="textinput" id="linkText" value="<?php echo $linktext; ?>" />
	</div>
	<div class="row">
		<label>Link Class:</label><input type="text" class="textinput" id="linkClass" value="" />
	</div>
	<div class="row">
	<label>Link Target:</label>
		<select class="textinput" id="linkTarget" value="">
			<option value=""></option>
			<option value="_blank">_blank</option>
			<option value="_parent">_parent</option>
			<option value="_self">_self</option>
			<option value="_top">_top</option>
		</select>
	</div>
	<div class="action"><input type="button" value="Insert Link" onclick="insertLink();" /></div>
	<a href="javascript:;" onclick="showcategories();" class="back">Back to categories</a>
</div>

</div>

</body>
</html>