<?php
namespace FFPlugin\PluginName;

add_action('admin_menu', function(){
    add_submenu_page( 'fivebyfive', PLUGIN_NAME, PLUGIN_NAME, 'manage_options', PLUGIN_SLUG, function(){
        include 'settings.php';
    });
});