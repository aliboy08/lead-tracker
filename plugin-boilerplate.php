<?php
/*
 * Plugin name: FF Plugin Boilerplate
 * Plugin URI: https://www.fivebyfive.com.au/
 * Description: Enter plugin description here
 * Version: 1.0
*/

namespace FFPlugin\PluginName;

define(__NAMESPACE__.'\PLUGIN_NAME', 'Plugin Name Here');
define(__NAMESPACE__.'\PLUGIN_SLUG', 'plugin_slug_here');

define(__NAMESPACE__.'\PLUGIN_URL', plugins_url('/', __FILE__));
define(__NAMESPACE__.'\PLUGIN_PATH', plugin_dir_path(__FILE__));

include 'vite/vite-wp.php';
include 'admin/admin.php';