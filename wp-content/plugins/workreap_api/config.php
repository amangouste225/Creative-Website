<?php
/**
 * The plugin configuration file
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           AndroidApp
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}

if( !class_exists( 'AndroidAppGlobalSettings' ) ) {

	abstract class AndroidAppGlobalSettings{
		const PluginName 		= "Workreap Mobile APP API";
		const PluginVersion 	 = '2.3';
		
		 /**
		 * Getter for Plugin Version
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    PluginName    The ID of this plugin.
		 */
		 public static function get_plugin_name(){
			return self::PluginName;	
		 }
		 
		 /**
		 * Getter for Plugin Version
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    PluginVersion    The ID of this plugin.
		 */
		 public static function get_plugin_verion(){
			return self::PluginVersion;	
		 }
		 
		 /**
		 * Getter for Plugin Path
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    get_plugin_path    The ID of this plugin.
		 */
		 public static function get_plugin_path(){
			return plugin_dir_path( __FILE__ );
		 }
		 
		 /**
		 * Getter for Plugin URL
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    get_plugin_url    The ID of this plugin.
		 */
		 public static function get_plugin_url(){
			return plugin_dir_url( __FILE__ );	
		 }
		 
	}
}