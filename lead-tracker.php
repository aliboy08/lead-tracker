<?php
/*
 * Plugin name: FF Lead Tracker
 * Plugin URI: https://www.fivebyfive.com.au/
 * Description: Extract data for lead tracking
 * Version: 1.1.0
*/

$vite = new FF_Vite([
    'id' => 'ff_lead_tracker',
    'url' => plugins_url('/', __FILE__),
    'path' => plugin_dir_path(__FILE__),
]);

define('FF_LEAD_TRACKER_VITE', $vite);

include 'gravityforms/add-on.php';