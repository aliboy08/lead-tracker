<?php
/*
 * Plugin name: FF Lead Tracker
 * Plugin URI: https://www.fivebyfive.com.au/
 * Description: Extract data for lead tracking
 * Version: 1.0
*/

namespace FFPlugin\LeadTracker;

define(__NAMESPACE__.'\PLUGIN_NAME', 'Lead Tracker');
define(__NAMESPACE__.'\PLUGIN_SLUG', 'ff_lead_tracker');

define(__NAMESPACE__.'\PLUGIN_URL', plugins_url('/', __FILE__));
define(__NAMESPACE__.'\PLUGIN_PATH', plugin_dir_path(__FILE__));

include 'vite/vite-wp.php';
// include 'admin/admin.php';
include 'gf/add-on.php';