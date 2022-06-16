<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0
 * @package           Android App API
 *
 * Plugin Name:       Workreap Mobile APP API
 * Plugin URI:        https://codecanyon.net/user/amentotech/portfolio
 * Description:       This plugin is used for creating custom API for Workreap WordPress Theme. Please don't update this plugin until you update your APP.
 * Version:           2.3
 * Author:            Amento Tech
 * Author URI:        https://themeforest.net/user/amentotech
 * Text Domain:       workreap_api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $woocommerce , $wpdb;
define( 'MOBILE_APP_TEMP_CHECKOUT', $wpdb->prefix . 'mobile_temp_checkout' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-elevator-activator.php
 */
if( !function_exists( 'activate_android_app' ) ) {
	function activate_android_app() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-system-activator.php';
		AndroidApp_Activator::activate();
	} 
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-elevator-deactivator.php
 */
if( !function_exists( 'deactivate_android_app' ) ) {
	function deactivate_android_app() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-system-deactivator.php';
		AndroidApp_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_android_app' );
register_deactivation_hook( __FILE__, 'deactivate_android_app' );

/**
 * Plugin configuration file,
 * It include getter & setter for global settings
 */
require plugin_dir_path( __FILE__ ) . 'config.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-system.php';
require plugin_dir_path( __FILE__ ) . 'hooks/hooks.php';
require plugin_dir_path( __FILE__ ) . 'mobile-checkout/init.php';

//include APP API
$dir = AndroidAppGlobalSettings::get_plugin_path();
$scan = glob("$dir/lib/api/*");
if( !empty($scan) ){
	foreach ($scan as $path) {
		@include $path;
	}
}



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if( !function_exists( 'run_AndroidApp' ) ) {
	function run_AndroidApp() {
	
		$plugin = new AndroidApp_Core();
		$plugin->run();
	
	}
	run_AndroidApp();
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'android_app_load_textdomain' );
function android_app_load_textdomain() {
  load_plugin_textdomain( 'workreap_api', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
