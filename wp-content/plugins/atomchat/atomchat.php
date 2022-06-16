<?php

/**
* Plugin Name: AtomChat
* Description: Voice, video & text chat for your WordPress site
* Version: 1.1.0
* Author: AtomChat
* Author URI: https://www.atomchat.com/
*/



include_once(ABSPATH.'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'plugin.php');

global $atomchat_clientid,$atomchat_old_client;

$atomchat_license_key = get_option('atomchat_license_key');

/* Start: To change the default plugin load order to avoid buddypress plugin conflict */
$currentPluginLoadOrder = get_option('active_plugins');
foreach ($currentPluginLoadOrder as $key => $value) {
	if($value == 'atomchat/atomchat.php'){
		unset($currentPluginLoadOrder[$key]);
	}
}
array_push($currentPluginLoadOrder, 'atomchat/atomchat.php');
update_option('active_plugins', $currentPluginLoadOrder);
/* End: To change the default plugin load order to avoid buddypress plugin conflict */

/**
 * atomchatGetCloudClientId
 * Return client id of cloud
 * @param (type) no param
 * @return (array) curl response
*/
if( !function_exists( 'atomchatGetCloudClientId' ) ) {
	function atomchatGetCloudClientId(){
		global $atomchat_clientid;

		$atomchat_clientid_temp = get_option('atomchat_clientid');

		if (!empty($atomchat_clientid_temp)) {
			$atomchat_clientid = $atomchat_clientid_temp;
		} else {

			$accessKey = 'flGBNxeq8Mgu5bynUhS5w3S2CJ7dfo3latMTxDNa';
			$atomchat_license_key = get_option('atomchat_license_key');
			if(!empty($atomchat_license_key)){
				$url = "https://app.atomchat.com/api-software/subscription?accessKey=".$accessKey;
				$url .= "&licenseKey=".$atomchat_license_key;
				$response = wp_remote_get( $url );
				$body = wp_remote_retrieve_body( $response );
			}
			$licenseinfo = !empty($body) ? json_decode($body): '';
			$atomchat_clientid = (!empty($licenseinfo) && is_object($licenseinfo) && property_exists($licenseinfo, 'success') && $licenseinfo->success == 1 && property_exists($licenseinfo, 'cloud') && $licenseinfo->cloud != 0) ? $licenseinfo->cloud : 0;

			add_option('atomchat_clientid',$atomchat_clientid,'','no');
		}

		return $atomchat_clientid;
	}
}

if(!empty($atomchat_license_key)){
	$atomchat_clientid = atomchatGetCloudClientId();
	$atomchat_old_clientid = $atomchat_clientid;
}

if($atomchat_clientid == 1 && !empty($atomchat_license_key)){
	$atomchat_clientid = substr($atomchat_license_key, -5, 5);
}

if(!empty($atomchat_clientid)){
	$dir = plugin_dir_path( __FILE__ ).'includes/atomchat_cloud.php';
}else{
	$dir = plugin_dir_path( __FILE__ ).'includes/atomchat_selfhosted.php';
}

include_once($dir);


/**
 * atomchatAddToWordPressMenu
 * Return adding menu option to wordpress admin panel
 * @param (type) no param
*/
if( !function_exists( 'atomchatAddToWordPressMenu' ) ) {
	function atomchatAddToWordPressMenu() {
		add_menu_page( 'AtomChat', 'AtomChat', 'manage_options', 'atomchat/atomchat-go.php', '', plugins_url( '/images/atom_chat_white_ icon.png', __FILE__ ), '75' );
	}
}
/**
 * atomchatDeleteLicenseKey
 * delete atomchat license key from database table
 */
if( !function_exists( 'atomchatDeleteLicenseKey' ) ) {
	function atomchatDeleteLicenseKey() {
		$atomchat_license_key = get_option('atomchat_license_key');

		if(!empty($atomchat_license_key)){
			delete_option('atomchat_license_key');
		}
		if(!empty($_COOKIE['atomchat_cloud']) || $_COOKIE['atomchat_cms_file'] || $_COOKIE['atomchat_license_key']){
			unset($_COOKIE['atomchat_cloud']);
			unset($_COOKIE['atomchat_license_key']);
			unset($_COOKIE['atomchat_cms_file']);
			setcookie('atomchat_cloud', null, -1, '/');
			setcookie('atomchat_license_key', null, -1, '/');
			setcookie('atomchat_cms_file', null, -1, '/');
		}
	}
}
/**
 * atomchatRemoveDatabase
 * @return removed database tables
 */
if( !function_exists( 'atomchatRemoveDatabase' ) ) {
	function atomchatRemoveDatabase() {
		global $wpdb;
		global $wp_roles;

		atomchatDeleteLicenseKey();
		$roles = array_keys($wp_roles->get_names());
		foreach ($roles as $key => $value) {
			delete_option($value);
		}
		delete_option('inbox_sync');
		delete_option('hide_bar');
		delete_option('atomchatintialaccess');
		delete_option('atomchat_clientid');
		delete_option('atomchat_bp_group_sync');
		delete_option('atomchat_show_friends');
		delete_option('atomchat_auth_key');
		delete_option('atomchat_api_key');
		delete_option('show_docked_layout_on_all_pages');
		delete_option('show_name_in_chat');
	}
}
/**
 * atomchatRegisterSettings
 * @return insert inbox_sync and hide_bar in wp_options table
 */
if( !function_exists( 'atomchatRegisterSettings' ) ) {
	function atomchatRegisterSettings() {
		global $wp_roles;

		$roles = array_keys($wp_roles->get_names());
		foreach ($roles as $key => $value) {
			$role = get_role($value);
			$role->add_cap( 'enable_atomchat',true );
		}
		add_option('atomchat_show_friends','false','','no');
		add_option('atomchat_bp_group_sync','false','','no');
		add_option('show_docked_layout_on_all_pages','false','','no');
	}
}

if( !function_exists( 'atomchat_action' ) ) {
	function atomchat_action() {
		global $wpdb;

		$requestHandler = plugin_dir_path( __FILE__ ).'includes/atomchat_requesthandler.php';
		include_once($requestHandler);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
/**
 * atomtchatCurlRequestToAPI
 * Return make cURL to AtomChat API
 * @param $action = api action that needs to be called, $data = data to send to api
*/
if( !function_exists( 'atomtchatCurlRequestToAPI' ) ) {
	function atomtchatCurlRequestToAPI($action,$data) {
		global $atomchat_clientid;

		$request_url = "https://".$atomchat_clientid.".cometondemand.net/cometchat_update.php?action=".$action;
		if(function_exists('curl_init')){
			$result = wp_remote_post($request_url, array(
				'method' 	=> 'POST',
				'body' 		=> http_build_query($data),
				'headers'	=> array(
					'Content-Type'	=>	'application/x-www-form-urlencoded'
				)
			)
		);
		}
	}
}
/**
 * atomchatCustomLogin: function will verify username and password
 * Return basedata
 * @param
*/
if( !function_exists( 'atomchatCustomLogin' ) ) {
	function atomchatCustomLogin() {
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'v1'.DIRECTORY_SEPARATOR.'atomchatLogin.php');
	}
}

if( !function_exists( 'atomchatDeductPointsCallback' ) ) {
	function atomchatDeductPointsCallback() {
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'mycred'.DIRECTORY_SEPARATOR.'credits.php');
	}
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'api/v1', 'atomchatLogin', [
		'methods' => 'POST',
		'callback' => 'atomchatCustomLogin',
		'permission_callback' => '__return_true',
	]);
});


if(is_plugin_active('mycred/mycred.php')){
	add_action( 'rest_api_init', function() {
		register_rest_route( 'plugins/mycred', 'credits', [
			'methods' => 'POST',
			'callback' => 'atomchatDeductPointsCallback',
			'permission_callback' => '__return_true',
		]);
	});
}


add_filter('https_ssl_verify', '__return_false');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_action( 'wp_ajax_atomchat_action', 'atomchat_action' );
add_action('admin_menu', 'atomchatAddToWordPressMenu');
register_activation_hook( __FILE__, 'atomchatRegisterSettings');
register_uninstall_hook( __FILE__, 'atomchatRemoveDatabase' );

register_activation_hook( __FILE__, 'atomchat_activation');

?>
