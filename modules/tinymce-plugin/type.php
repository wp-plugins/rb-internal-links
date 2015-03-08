<?php

$type = Rb_Internal_Links::requestVar('type', 'post');
$parent = (int) Rb_Internal_Links::requestVar('parent', 0);
$post_type = Rb_Internal_Links::requestVar('post_type', 'post');

switch ($type) {
    case 'post':
        $categories = get_categories(array('type' => 'post', 'orderby' => 'name', 'hide_empty' => true));
        $categories = filter_categories($categories, $parent);

        $items = ($parent > 0 || $post_type !== 'post') ? get_posts(array('numberposts' => -1, 'post_type' => $post_type, 'category' => $parent, 'post_status' => 'publish,pending,draft,future')) : array();

        // remove custom post types that are categorised from the root
        if($parent === 0 && $post_type !== 'post'){
            foreach($items AS $i => $item){
                $category = get_the_category($item->ID);
                if(count($category) > 0){
                    unset($items[$i]);
                }
            }
        }

        include($path . '/templates/tinymce-plugin/categories.php');
        break;
    case 'page':
        $topPage = ($parent > 0) ? get_page($parent) : false;

        $pages = get_pages(array('child_of' => 0, 'sort_column' => Rb_Internal_Links::loadOption('page_order'), 'post_status' => 'publish'));
        $pages = filter_categories($pages, $parent, 'post_parent');
        include($path . '/templates/tinymce-plugin/pages.php');
        break;
    case 'category':
        $topCategory = ($parent > 0) ? get_category($parent) : false;

        $categories = get_categories(array('type' => 'post', 'orderby' => 'name', 'hide_empty' => true));
        $categories = filter_categories($categories, $parent);

        include($path . '/templates/tinymce-plugin/categories.php');
        break;
    case 'custom':
            $post_types = get_post_types(array(
                        'public' => true,
                        '_builtin' => false
                            ), 'objects');
        include($path . '/templates/tinymce-plugin/custom.php');
        break;
    case 'search':
        include($path . '/templates/tinymce-plugin/search.php');
        break;
    case 'test':
        include($path . '/templates/tinymce-plugin/test.php');
        break;
}

function filter_categories($categories, $parent, $variable = 'category_parent') {
    $return = array();
    foreach ($categories AS $category)
        if ($category->$variable == $parent)
            $return[] = $category;

    return $return;
}
