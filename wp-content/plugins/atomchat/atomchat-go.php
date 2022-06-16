<?php

/**
 *
 *
 * @package atomchat
 */
	if ( ! defined( 'ABSPATH' ) ) exit;

	include_once(ABSPATH.'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'plugin.php');

	if ( !current_user_can( 'activate_plugins' ) ) {
		exit("You don't have permission to access this plugin.");
	}

	$atomchatAdminPanelurl = $atomchatPluginPath = $atomchatLogo = '';

	$atomchatPluginPath = plugin_dir_url( __FILE__ );
	if(!defined('ATOMCHAT_PLUGIN_REFRRER')) define('ATOMCHAT_PLUGIN_REFRRER', $atomchatPluginPath);

	$atomchatLogo = esc_url($atomchatPluginPath.'images/atom_chat_black_icon_logo.png');
	$atomchatDockedLayout = esc_url($atomchatPluginPath.'images/docked_layout.svg');
	$atomchatAuthKey = esc_url($atomchatPluginPath.'images/atomchat_auth.png');

	if(!empty($atomchat_clientid) || !empty($_COOKIE['atomchat_cloud'])) {
		$atomchat_client_url = (!empty($_COOKIE['atomchat_cloud']) && $_COOKIE['atomchat_cloud'] != 1) ? $_COOKIE['atomchat_cloud'] : $atomchat_clientid;
		if($atomchat_client_url < 50000){
			$atomchatAdminPanelurl = esc_url("//".$atomchat_client_url.".cometondemand.net/admin/");
		}else{
			$atomchatAdminPanelurl = esc_url("https://app.atomchat.com/licenses/access/".$atomchat_client_url);
		}
	}

	/** Initial access of AtomChat Installation **/
	$isCometReady = get_option('atomchatintialaccess');

	/** Check if buddypress intalled or not **/
	if(empty($isCometReady) && (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php'))){
		update_option('atomchat_show_friends', 'true');
		atomtchatCurlRequestToAPI('updateUserListSetting', array(
				'setting_key' => 'atomchat_show_friends',
				'setting_value' => 'true'
			)
		);
	}

    /** Check if Initial access of AtomChat Installation or not **/
	if(empty($isCometReady)){
		update_option('show_docked_layout_on_all_pages', 'true');
	}

	if(!empty($atomchat_clientid) || !empty($_COOKIE['atomchat_cloud'])){
		if(empty($isCometReady)){
			include_once(plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'atomchat-auth.php');
			add_option('atomchatintialaccess','1','','no');
		}elseif($isCometReady == '1'){
			include_once(plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'atomchat-ready.php');
			update_option('atomchatintialaccess','2');
		}else {
			include_once(plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'atomchat-admin.php');
		}
	}else{
		$dir = plugin_dir_path( __FILE__ ).'installer.php';
		require_once($dir);
	}
?>