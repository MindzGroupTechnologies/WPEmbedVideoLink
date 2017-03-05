<?php
/**
 * Plugin Name: MindzGroupTech - Embed Video Link
 * Plugin URI: https://www.mindzgrouptech.net/wordpress-plugin-embed-video-link
 * Description: A plugin that renders / embeds YouTube link in posts with just a shortcode.
 * Version: 0.0.3
 * Author: MindzGroupTech
 * Author URI: https://www.mindzgrouptech.net
 */

define('EVL_PLUGIN_FILE', __FILE__);
define('EVL_PLUGIN_PATH', plugin_dir_path(__FILE__));

require EVL_PLUGIN_PATH.'videolink.php';

new VideoLink();