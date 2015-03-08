<?php

die('Deprecated file');

define('WP_ADMIN', true);

$current_error_reporting = error_reporting();
error_reporting($current_error_reporting & ~E_STRICT);

require('../../../wp-load.php');
$path = realpath(dirname(__FILE__));

require($path . '/compat.php');

$content = addslashes(Rb_Internal_Links::requestVar('content'));
$action = Rb_Internal_Links::requestVar('rb-internal-links-action');
switch ($action) {
    case 'start':
        include($path . '/templates/tinymce-plugin/start.php');
        break;
    case 'type':
        include($path . '/modules/tinymce-plugin/type.php');
        break;
    case 'search':
        include($path . '/modules/tinymce-plugin/search.php');
        break;
    case 'form':
        include($path . '/modules/tinymce-plugin/form.php');
        break;
    case 'link':
        include($path . '/modules/tinymce-plugin/link.php');
        break;
    default:
        include($path . '/templates/tinymce-plugin/index.php');
}

function request($key, $default = false) {
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

error_reporting($current_error_reporting);
