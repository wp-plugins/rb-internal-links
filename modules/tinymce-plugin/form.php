<?php
$type = Rb_Internal_Links::requestVar('type');
$id = Rb_Internal_Links::requestVar('id');

// try to use slug if the user wants it
if(get_option('rbinternal_return_param') == 'slug')
{
	switch($type)
	{
		case 'post':
			$item = get_post($id);
			$id = $item->post_name;
			break;
		case 'page':
			$item = get_page($id);
			$id = $item->post_name;
			break;
	}
}

include($path . '/templates/tinymce-plugin/form.php');

