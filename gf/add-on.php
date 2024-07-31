<?php
define( 'GF_LEAD_TRACKER_ADDON_VERSION', '1.0' );
 
add_action( 'gform_loaded', array( 'GF_Lead_Tracker_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_Lead_Tracker_AddOn_Bootstrap {
 
    public static function load() {
 
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
 
        require_once( 'class-gf-lead-tracker-add-on.php' );
 
        GFAddOn::register( 'GF_Lead_Tracker_AddOn' );
    }
 
}
 
// function gf_lead_tracker_addon() {
//     return GF_Lead_Tracker_AddOn::get_instance();
// }