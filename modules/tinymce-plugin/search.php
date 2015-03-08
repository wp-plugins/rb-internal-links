<?php
$q = Rb_Internal_Links::requestVar('q');

if(empty($q))
	$items = array();
else
{
	$q = addslashes($q);
	$query = "SELECT ID, post_title, post_date FROM $wpdb->posts WHERE MATCH(post_title, post_content) AGAINST(\"$q\" IN BOOLEAN MODE) AND post_type='post' AND post_status='publish'";
	$items = $wpdb->get_results($query, OBJECT);
}

$type = 'post';
include($path . '/templates/tinymce-plugin/search-results.php');
