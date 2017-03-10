<?php
/**
 * Plugin Name: MindzGroupTech - Embed Video Link
 * Plugin URI: https://www.mindzgrouptech.net/wordpress-plugin-embed-video-link
 * Description: A plugin that renders / embeds YouTube link in posts with just a shortcode.
 * Version: 0.0.5
 * Author: MindzGroupTech
 * Author URI: https://www.mindzgrouptech.net
 */

define('EVL_PLUGIN_FILE', __FILE__);
define('EVL_PLUGIN_PATH', plugin_dir_path(__FILE__));

require EVL_PLUGIN_PATH.'plugin_base.php';
require EVL_PLUGIN_PATH.'youtube.php';
require EVL_PLUGIN_PATH.'vimeo.php';
require EVL_PLUGIN_PATH.'utilities.php';
if(is_blog_admin()) {
    require EVL_PLUGIN_PATH.'videolinkadmin.php';
    new EVL_Admin();
} else {
    require EVL_PLUGIN_PATH.'videolink.php';
    new EVL_Front();
}
