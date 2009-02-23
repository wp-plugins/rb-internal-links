<?php

$type = request('type', 'post');
$parent = request('parent', 0);
switch($type)
{
	case 'post':
		$categories = get_categories(array('type' => 'post', 'orderby' => 'name', 'hide_empty' => true));
		$categories = filter_categories($categories, $parent);
	
		$items = ($parent > 0)? get_posts(array('numberposts' => -1, 'category' => $parent)) : array();
		
		include($path . '/templates/tinymce-plugin/categories.php');
		break;
	case 'page':
		$topPage = ($parent > 0)? get_page($parent) : false;
		
		$pages = get_pages(array('child_of' => 0));
		$pages = filter_categories($pages, $parent, 'post_parent');
		include($path . '/templates/tinymce-plugin/pages.php');
		break;
	case 'category':
		$topCategory = ($parent > 0)? get_category($parent) : false;
		
		$categories = get_categories(array('type' => 'post', 'orderby' => 'name', 'hide_empty' => true));
		$categories = filter_categories($categories, $parent);
		
		include($path . '/templates/tinymce-plugin/categories.php');
		break;
	case 'search':
		include($path . '/templates/tinymce-plugin/search.php');
		break;
	case 'test':
		include($path . '/templates/tinymce-plugin/test.php');
		break;
}


function filter_categories($categories, $parent, $variable = 'category_parent')
{
	$return = array();
	foreach($categories AS $category)
		if($category->$variable == $parent)
			$return[] = $category;
	
	return $return;
}
