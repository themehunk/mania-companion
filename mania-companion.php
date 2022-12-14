<?php
/**
 * Plugin Name:             Mania Companion
 * Plugin URI:              https://themehunk.com
 * Description:             TH Advance Product Search plugin is a powerful AJAX based search plugin which will display result for Product, Post and Pages. This plugin is capable to search across all your WooCommerce products ( Product title, Description, Categories, ID and SKU ) . Plugin comes with user friendly settings, You can use shortcode and widget to display search bar at your desired location.This plugin provide you freedom to choose color and styling to match up with your website. It also supports Google search analytics to monitor your website visitor and searching behaviour. <a href="https://themehunk.com/plugins/" target="_blank">Get more plugins for your website on <strong>ThemeHunk</strong></a>
 * Version:                 1.0.0
 * Author:                  ThemeHunk
 * Author URI:              https://themehunk.com
 * Requires at least:       5.0
 * Tested up to:            6.1
 * WC requires at least:    3.2
 * WC tested up to:         5.1
 * Domain Path:             /languages
 * Text Domain:             mania-companion
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (!defined('MANIA_COMPANION_PLUGIN_FILE')) {
    define('MANIA_COMPANION_PLUGIN_FILE', __FILE__);
}

if (!defined('MANIA_COMPANION_PLUGIN_URI')) {
    define( 'MANIA_COMPANION_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

if (!defined('MANIA_COMPANION_PLUGIN_PATH')) {
    define( 'MANIA_COMPANION_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if (!defined('MANIA_COMPANION_PLUGIN_DIRNAME')) {
    define( 'MANIA_COMPANION_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
}

if (!defined('MANIA_COMPANION_PLUGIN_BASENAME')) {
    define( 'MANIA_COMPANION_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if (!defined('MANIA_COMPANION_IMAGES_URI')) {
define( 'MANIA_COMPANION_IMAGES_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'images' ) );
}

if (!defined('MANIA_COMPANION_VERSION')) {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), false);
    define('MANIA_COMPANION_VERSION', $plugin_data['version']);
}

function mania_companion_text_domain(){
    $theme = wp_get_theme();
    $themeArr = array();
    $themeArr[] = $theme->get('TextDomain');
    $themeArr[] = $theme->get('Template');
    return $themeArr;
}


function mania_companion_load_plugin(){
    $theme = mania_companion_text_domain(); 

    if(in_array("th-shop-mania", $theme)){

     if ( !function_exists('th_shop_mania_pro_load_plugin' )) {
     require_once MANIA_COMPANION_PLUGIN_PATH . 'th-shop-mania/init.php';
     require_once( MANIA_COMPANION_PLUGIN_PATH . '/import/themehunk.php' );
     add_action('admin_enqueue_scripts', 'mania_companion_admin_scripts');
        }
    }

}
add_action('after_setup_theme', 'mania_companion_load_plugin');


function mania_companion_admin_scripts(){
        wp_localize_script('th-shop-mania-admin-load', 'mania_companion_import',  
            array(
            'plugin'                   => 'mania-companion'
           )
         );
    }