<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0
 * @package           Workreap Core
 *
 * @Workreap Core
 * Plugin Name:       Workreap Core
 * Plugin URI:        https://themeforest.net/user/amentotech/portfolio
 * Description:       This plugin have the core functionality for Workreap WordPress Theme
 * Version:           2.5.3
 * Author:            Amentotech
 * Author URI:        https://themeforest.net/user/amentotech
 * Text Domain:       workreap_core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !function_exists( 'workreap_core_load_last' ) ) {
	function workreap_core_load_last() {
		$plugin_path   			= preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
		$workreap_plugin 		= plugin_basename(trim($plugin_path));
		$active_plugins 		= get_option('active_plugins');
		$workreap_plugin_key 	= array_search($workreap_plugin, $active_plugins);
			array_splice($active_plugins, $workreap_plugin_key, 1);
			array_push($active_plugins, $workreap_plugin);
			update_option('active_plugins', $active_plugins);
	}
	
	add_action("activated_plugin", "workreap_core_load_last");
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-elevator-activator.php
 */
if( !function_exists( 'activate_workreap' ) ) {
	function activate_workreap() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-system-activator.php';
		Workreap_Activator::activate();
		
	} 
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-elevator-deactivator.php
 */
if( !function_exists( 'deactivate_workreap' ) ) {
	function deactivate_workreap() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-system-deactivator.php';
		Workreap_Deactivator::deactivate();
	}
}

/**
 * Define plugin basename
 */
define( 'Workreap_Basename', plugin_basename(__FILE__));

register_activation_hook( __FILE__, 'activate_workreap' );
register_deactivation_hook( __FILE__, 'deactivate_workreap' );

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

include workreap_template_exsits( 'chat/class-chat-system' );
include workreap_template_exsits( 'hooks/hooks' );
include workreap_template_exsits( 'helpers/EmailHelper' );
include workreap_template_exsits( 'shortcodes/class-authentication' );
include workreap_template_exsits( 'libraries/mailchimp/class-mailchimp' );

require plugin_dir_path( __FILE__ ) . 'widgets/config.php';
require plugin_dir_path( __FILE__ ) . 'elementor/base.php';
require plugin_dir_path( __FILE__ ) . 'elementor/config.php';
require plugin_dir_path( __FILE__ ) . 'libraries/mailchimp/class-mailchimp-oath.php';
require plugin_dir_path( __FILE__ ) . 'helpers/register.php';
require plugin_dir_path( __FILE__ ) . 'import-users/class-readcsv.php';
require plugin_dir_path( __FILE__ ) . 'admin/settings/settings.php';
include workreap_template_exsits( 'import-users/class-import-user' );
require plugin_dir_path( __FILE__ ) . 'social-connect/class-facebook.php';
require plugin_dir_path( __FILE__ ) . 'social-connect/class-linkedin.php';
require plugin_dir_path( __FILE__ ) . 'libraries/recaptchalib/recaptchalib.php';


/**
 * Get template from plugin or theme.
 *
 * @param string $file  Templat`e file name.
 * @param array  $param Params to add to template.
 *
 * @return string
 */
function workreap_template_exsits( $file, $param = array() ) {
	extract( $param );
	if ( is_dir( get_stylesheet_directory() . '/extend/' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/extend/' . $file . '.php' ) ) {
			$template_load = get_stylesheet_directory() . '/extend/' . $file . '.php';
		} else {
			$template_load = WorkreapGlobalSettings::get_plugin_path() . '/' . $file . '.php';
		}
	} else {
		$template_load = WorkreapGlobalSettings::get_plugin_path() . '/' . $file . '.php';
	}
	
	return $template_load;
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
if( !function_exists( 'run_Workreap' ) ) {
	function run_Workreap() {
	
		$plugin = new Workreap_Core();
		$plugin->run();
	
	}
	run_Workreap();
}

/**
 * @init            Save rewrite slugs
 * @package         Rewrite Slug
 * @subpackage      Rewrite slugs
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if (!function_exists('workreap_set_custom_rewrite_rule')) {
	function workreap_set_custom_rewrite_rule() {
		global $wp_rewrite;
		$settings = (array) workreap_get_theme_settings();
		
		if( !empty( $settings['post'] ) ){
			foreach ( $settings['post'] as $post_type => $slug ) {
				if(!empty( $slug )){
					$args = get_post_type_object($post_type);
					if( !empty( $args ) ){
						$args->rewrite["slug"] = $slug;
						register_post_type($args->name, $args);
					}
				}
			}
		}

		if( !empty( $settings['term'] ) ){
			foreach ( $settings['term'] as $term => $slug ) {
				if(!empty( $slug ) ){
					$tax = get_taxonomy($term);
					if( !empty( $tax ) ){
						$tax->rewrite["slug"] = $slug;
						register_taxonomy($term, $tax->object_type[0],(array)$tax);
					}
				}
			}
		}

		$wp_rewrite->flush_rules();
	} 
	add_action('init', 'workreap_set_custom_rewrite_rule');
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'workreap_load_textdomain' );
function workreap_load_textdomain() {
  load_plugin_textdomain( 'workreap_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
