<?php

/*

  RB Internal Links
  ==============================================================================

  Link to your other blog posts without having to use the full url, just in case anything changes later on!


  Info for WordPress:
  ==============================================================================
  Plugin Name: RB Internal Links
  Plugin URI: http://www.blograndom.com/blog/
  Description: Link to other blog posts and pages without specifying the full URL. Uses a UI to ease finding the post or page you want to link to.
  Version: 2.0.16
  Text Domain: rb-internal-links
  Author: Arron Woods
  Author URI: http://www.blograndom.com

  Copyright 2009  Arron Woods (blograndom.com)  (email : info@blograndom.com)
  ==============================================================================

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Core class for RB Internal links
 * 
 * Sets up hooks, core methods, etc
 */
if (floatval(phpversion()) < 5)
    die('You must have PHP version 5+ to use RB Internal Links');

define('RBINTERNAL_PATH', plugin_dir_path(__FILE__));

// start plugin
add_action('init', array('Rb_Internal_Links', 'enable'));
// add tinymce button to wysiwyg editor
add_action('init', array('Rb_Internal_Links', 'addWysiwygFilters'));

class Rb_Internal_Links {

    static $pluginName = 'RB Internal Links';
    static $optionPrefix = 'rbinternal_';
    static $options = array('tinymce', 'return_param', 'default_text', 'code_prefix', 'code_suffix', 'page_order');
    static $defaults = array('tinymce' => true, 'return_param' => 'id', 'default_text' => 'url', 'page_order' => 'post_title');
    static $convert_count = 0;

    /**
     * Enable
     *
     * Called when wordpress is initialised, sets up the actions/hooks/shortcode used throughout the system
     */
    static function enable() {
        // add shortcode hook for processing our links
        add_shortcode('intlink', array(__CLASS__, 'shortcode'));
        // add link to settings menu for plugin
        add_action('admin_menu', array(__CLASS__, 'addOptionsPages'));
        // register ajax action
        add_action('wp_ajax_rb-internal-links-ajax', array(__CLASS__, 'ajaxAction'));

        // start gettext
        $plugin_dir = basename(dirname(__FILE__));
        load_plugin_textdomain('rb-internal-links', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Shortcode
     * 
     * The shortcode parser that replaces the shortcode in the blog post with a link to 
     * a post
     * 
     * @param array		$atts
     * @param string	$content
     * @return string
     */
    static function shortcode($atts, $content = null) {
        if (!isset($atts['id']))
            throw new Exception('Incorrect shortcode for RB Internal Links');

        // default type is post
        if (empty($atts['type']))
            $atts['type'] = 'post';

        $prefix = self::loadOption('code_prefix');
        $suffix = self::loadOption('code_suffix');

        $params = $atts;
        $url = self::url($params['id'], $params['type'], $content) . ((isset($params['anchor'])) ? '#' . $params['anchor'] : '');

        if ($url) {
            $prefix .= '<a' . self::shortcode_attr('href', $url);
            unset($params['id'], $params['type'], $params['anchor']);

            foreach ($params AS $attr => $value) {
                $prefix .= self::shortcode_attr($attr, $value);
            }

            $prefix .= '>';
        } else {
            $prefix .= '<a href="#" class="missingLink">';
        }

        $content = do_shortcode($content);

        $suffix .= '</a>';


        return $prefix . $content . $suffix;
    }

    /**
     * Shortcode_attr
     *
     * Quick little function to correctly parse any attributes we want to create ready
     * for the shortcode return
     *
     * @param string	$attr	The attribute key
     * @param string	$value	The value to put within quote marks (also gets escaped)
     */
    static function shortcode_attr($attr, $value) {
        return ' ' . $attr . '="' . htmlspecialchars($value) . '"';
    }

    /**
     * Url
     *
     * Generates the correct URL based on the ID and type of URL we want
     * If the content variable is empty it may be replaced with the elements title (i.e. post title)
     *
     * @param int		$id
     * @param string	$type
     * @return string	The url
     */
    static function url($id, $type = 'post', &$content = null) {
        switch ($type) {
            case 'post':
            case 'page':
                global $wpdb;
                $field = (is_numeric($id)) ? 'ID' : 'post_name';
                $post = $wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts WHERE $field = '$id'");
                if (empty($post)) {
                    if (is_user_logged_in()) {
                        return get_option('siteurl') . '/wp-admin/post-new.php?post_title=' . urlencode($content);
                    } else {
                        return false;
                    }
                } else {
                    $url = get_permalink($post->ID);
                    if (empty($content) || $content === '{{empty}}') {
                        $content = self::title($id, $type, $post, $url);
                    }
                    return $url;
                }
            case 'category':
                $url = get_category_link($id);
                if (empty($content) || $content === '{{empty}}') {
                    $content = self::title($id, $type, null, $url);
                }
                return $url;
            default:
                return '#';
        }
    }

    /**
     * Title
     *
     * Finds the title of the post/page/category for the link
     *
     * @param int		$id
     * @param string	$type
     * @return string	The url
     */
    static function title($id, $type = 'post', $data = null, $url = null) {
        switch (self::loadOption('default_text')) {
            case 'title':
                switch ($type) {
                    case 'post':
                    case 'page':
                        return $data->post_title;
                    case 'category':
                        return get_cat_name($id);
                    default:
                        return '#';
                }
            default:
                return $url;
        }
    }

    static function saveOption($key, $value) {
        $thisKey = self::$optionPrefix . $key;
        if (get_option($thisKey) === false)
            add_option($thisKey, $value);
        else
            update_option($thisKey, $value);
    }

    static function loadOption($key) {
        $option = get_option(self::$optionPrefix . $key);
        if ($option === false)
            $option = ((isset(self::$defaults[$key])) ? self::$defaults[$key] : false);

        return $option;
    }

    static function loadOptions() {
        $options = array();
        foreach (self::$options AS $key)
            $options[$key] = self::loadOption($key);
        return $options;
    }

    /**
     * Add page to admin panel menu
     */
    static function addOptionsPages() {
        add_options_page(self::$pluginName, self::$pluginName, 'manage_options', 'rb-internal-links', array(__CLASS__, 'adminSettings'));
    }

    /**
     * Renders the settings page in the admin panel
     */
    static function adminSettings() {
        if (isset($_POST['rbinternal_submit'])) {
            foreach (self::$options AS $option) {
                $default = isset(self::$defaults[$option]) ? self::$defaults[$option] : '';
                $value = isset($_POST[$option]) ? $_POST[$option] : $default;
                self::saveOption($option, $value);
            }

            $updateSuccess = true;
        }

        if (isset($_POST['rbinternal_update_code'])) {
            try {
                self::updateOldCode();
                $codeUpdateSuccess = self::$convert_count;
            } catch (Exception $e) {
                die($e);
            }
        }

        $options = self::loadOptions();
        include_once(dirname(__FILE__) . '/templates/admin-settings.php');
    }
    
    static function requestVar($key, $default = false){
        return isset($_REQUEST[$key])? $_REQUEST[$key] : $default;
    }

    static function ajaxAction() {
        if (!current_user_can('edit_posts')) {
            die('edit_posts permission required');
        }

        $path = __DIR__;
        $content = addslashes(self::requestVar('content'));
        $action = self::requestVar('rb-internal-links-action');

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
        
        exit;
    }

    /**
     * Sets up the filters that will get called if we're editing a post or a page
     */
    static function addWysiwygFilters() {
        if (self::loadOption('tinymce')) {
            // Don't bother doing this stuff if the current user lacks permissions
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
                return;

            // Add only in Rich Editor mode
            if (get_user_option('rich_editing') == 'true') {
                add_filter('mce_external_plugins', array(__CLASS__, 'wysiwygPluginAdd'));
                add_filter('mce_buttons', array(__CLASS__, 'wysiwygButtonAdd'));
                add_filter('tiny_mce_version', array(__CLASS__, 'wysiwygRefresh'));
            }
        }
    }

    /**
     * Increment tinymce version number for cache of config
     */
    static function wysiwygRefresh($ver) {
        $ver += 3;
        return $ver;
    }

    /**
     * Adds the tinymce plugin to the list of available plugins
     */
    static function wysiwygPluginAdd($plugin_array) {
        $plugin_array['rbinternallinks'] = self::getPluginUrl() . '/tinymce/editor_plugin.js';
        return $plugin_array;
    }

    /**
     * Add the button to tinymce
     */
    static function wysiwygButtonAdd($buttons) {
        array_push($buttons, 'separator', 'rbinternallinks');
        return $buttons;
    }

    static function getPluginUrl() {
        return rtrim(plugin_dir_url(__FILE__), '/');
    }

    static function getCurrentPage() {
        $page = basename(__FILE__);
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = preg_replace('[^a-zA-Z0-9\.\_\-]', '', $_GET['page']);
        }

        if (function_exists("admin_url"))
            return admin_url(basename($_SERVER["PHP_SELF"])) . "?page=" . $page;
        else
            return $_SERVER['PHP_SELF'] . "?page=" . $page;
    }

    static function updateOldCode() {
        $posts = get_posts(array('numberposts' => -1));
        foreach ($posts AS $item) {
            $content = preg_replace_callback("/<!--(post|intlink)([^-->].*?)-->/i", array(__CLASS__, 'processOldCode'), $item->post_content);
            // old code method too
            $content = preg_replace_callback("/{{(post|intlink)([^}}].*?)}}/i", array(__CLASS__, 'processOldCode'), $content);
            // update database
            $update = array('ID' => $item->ID, 'post_content' => $content);
            wp_update_post($update);
        }

        $pages = get_pages();
        foreach ($pages AS $item) {
            $content = preg_replace_callback("/<!--(post|intlink)([^-->].*?)-->/i", array(__CLASS__, 'processOldCode'), $item->post_content);
            $content = preg_replace_callback("/{{(post|intlink)([^}}].*?)}}/i", array(__CLASS__, 'processOldCode'), $content);
            $update = array('ID' => $item->ID, 'post_content' => $content);
            wp_update_post($update);
        }
    }

    static function processOldCode($code) {
        $params = isset($code[2]) ? $code[2] : false;
        if (!$params)
            return false;

        self::$convert_count++;

        // covert escaped double quotes to avoid confusion
        $params = str_replace('\"', '!!DBLQUOTES!!', $params);

        // look for the text attribute
        preg_match("/text=\"([^\"].*?)\"/i", $params, $matches);
        $find = $matches[0];
        $text = $matches[1];
        // replace escaped quotes with normal quotes
        $text = str_replace('!!DBLQUOTES!!', '"', $text);

        $params = trim(str_replace($find, '', $params));

        // build new code
        $new = '[intlink ' . $params . ((empty($text)) ? ' /' : '') . ']' . ((!empty($text)) ? $text . '[/intlink]' : '');
        // convert back any other double quotes
        $new = str_replace('!!DBLQUOTES!!', '\"', $new);
        return $new;
    }

}
