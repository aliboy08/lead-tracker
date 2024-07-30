<?php
namespace FFPlugin\PluginName;

define(__NAMESPACE__.'\VITE_PATH', PLUGIN_PATH);
define(__NAMESPACE__.'\VITE_URL', PLUGIN_URL);

function get_manifest(){
    $manifest_file = VITE_PATH.'/dist/wp-manifest.json';
    $manifest = wp_json_file_decode( $manifest_file );
    return $manifest;
}

define(__NAMESPACE__.'\VITE_MANIFEST', get_manifest());

function get_mode(){
    if( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' || file_exists('.production') || !VITE_MANIFEST ) {
        return 'build';
    }
    if( VITE_MANIFEST->mode == 'dev' ) {
        return 'dev';
    }
    return 'build';
}

define(__NAMESPACE__.'\VITE_MODE', get_mode());

function get_dev_server_origin(){
    $file = VITE_PATH .'/dist/vite-dev-server.json';
    if( !file_exists( $file ) ) {
        return 'https://localhost:5173';
    }
    $server_config = wp_json_file_decode( $file );
    if( !$server_config ) return 'https://localhost:5173';
    return $server_config->origin;
}

define(__NAMESPACE__.'\VITE_SERVER_ORIGIN', get_dev_server_origin());

function vite_enqueue_build( $handle, $src, $css_only ){
    
    if( !isset( VITE_MANIFEST->entry_points->$src ) ) return;
    $asset = VITE_MANIFEST->entry_points->$src;
    
    vite_enqueue_css( $handle, $asset );
    if( $css_only ) return;
 
    $js_src = VITE_URL.'/dist/'.$asset->file;
    wp_enqueue_script($handle, $js_src, [], null, true);
    
    vite_enqueue_js_filter( $handle );
}

function vite_enqueue_css( $handle, $asset ){
    if( !isset($asset->css) ) return;
    $i = 0;
    foreach( $asset->css as $src ) { $i++;
        $css_src = VITE_URL.'/dist/'.$src;
        $css_handle = $handle .'-css-'. $i;
        wp_enqueue_style( $css_handle, $css_src );
    }
}

function vite_enqueue_dev( $handle, $src ){
    $src = VITE_SERVER_ORIGIN.'/'.$src;
    wp_enqueue_script($handle, $src);
    vite_enqueue_js_filter( $handle );
}

function vite_enqueue_js_filter( $handle ){
    add_filter('script_loader_tag', function( $tag, $js_handle ) use ($handle){
        if( $js_handle !== $handle ) return $tag;
        if( strpos( $tag, ' type="module"' ) === false ) {
            $tag = str_replace('<script', '<script type="module"', $tag);
        }
        return $tag;
    }, 100, 2);
}

function vite_enqueue( $handle, $src, $css_only = false ){

    if( isset( $GLOBALS[__NAMESPACE__.'\vite_scripts'] ) &&
        in_array( $src, $GLOBALS[__NAMESPACE__.'\vite_scripts'] ) ) return;

    if( VITE_MODE == 'dev' ) {
        vite_enqueue_dev( $handle, $src );
    } else {
        vite_enqueue_build( $handle, $src, $css_only );
    }

    $GLOBALS[__NAMESPACE__.'\vite_scripts'][] = $src;
}