<?php

/***

	* WordPress inbuild function used in this file
	* wp_remote_get, wp_remote_retrieve_body and wp_die
	* $wpdb - global variable used for database
*/

$atomchatIntegration = 'wordpress';

class AtomChatInstaller {
/*
	AtomChatInstaller  constructor
*/
	public $writablepath ;
	public $latest_v;
	public $atomchatPluginReferrer;
	public $integration;
	public $licensekey;
	public $target;
	public $token;
	public $download_link;
	public $atomchat_api_response;
	public $wpdb;
	public $accessKey = 'flGBNxeq8Mgu5bynUhS5w3S2CJ7dfo3latMTxDNa';

	function __construct($arguments = array()){
		$this->latest_v = !empty($arguments['latest_v']) ? $arguments['latest_v']: "";
		$this->integration = !empty($arguments['integration']) ? $arguments['integration']: "";
		$this->licensekey = !empty($arguments['licensekey']) ? $arguments['licensekey']: "";
		$this->token = !empty($arguments['token']) ? $arguments['token']: "";
		$this->target = !empty($arguments['target']) ? $arguments['target']: "";
		$this->download_link = !empty($arguments['download_link']) ? $arguments['download_link']: "";
		$this->atomchat_api_response = !empty($arguments['atomchat_api_response']) ? $arguments['atomchat_api_response']: "";
		$this->wpdb = !empty($arguments['wpdb']) ? $arguments['wpdb']: "";
		$this->basePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
		ini_set('memory_limit', '-1');
	}

	/**
	 * atomchatCheckLicenseKey: check for valid license key
	 * @param: license key
	 * @return json response
	*/
	public function atomchatCheckLicenseKey(){
		try{
			$response = array();

			$url = "https://app.atomchat.com/api-software/subscription?accessKey=".$this->accessKey;
			$url .= "&licenseKey=".$this->licensekey;

			/***WordPress remote call start ***/
			$data = wp_remote_get( $url );
	        $body = wp_remote_retrieve_body( $data );
	        /***WordPress remote call end ***/

			$licensedata = !empty($body) ? json_decode($body) : '';
			$response['atomchat_api_response'] = !empty($body) ? $body : '';

			/*** cms details start ***/
			if(empty($this->integration)){
				$atomchat_cms_file = (is_object($licensedata) && property_exists($licensedata, 'integration')) ? $licensedata->integration->file : 'standalone';
			}else{
				$atomchat_cms_file = $this->integration;
			}
			/*** cms details end ***/

			/*** cloud status start ***/
			$atomchat_cloud_active = (is_object($licensedata) && property_exists($licensedata, 'cloud') && !empty($licensedata->cloud)) ? 1 : 0 ;
			if($atomchat_cloud_active){
				setcookie('atomchat_license_key', $this->licensekey, time() + (60 * 5), "/"); // 300 = 5 min
				update_option('atomchat_license_key', $this->licensekey);
			}
			/*** cloud status end ***/

			/*** success response ***/
			if(!empty($licensedata) && is_object($licensedata) && property_exists($licensedata, 'success') && $licensedata->success == 1){
				$response['success'] = 1;
				$response['cloud'] = $licensedata->cloud;
			}else{
			/*** error response ***/
				$response['success'] = 0;
				$response['error'] = (is_object($licensedata) && property_exists($licensedata, 'error')) ? $licensedata->error: 'License not found';
			}
		} catch (Exception $e) {
        	$response['error'] = 1;
			$response['message'] = $e->getMessage();
    	}
		header('Content-Type: application/json');
		echo json_encode($response);
		wp_die();
	}
}


if (!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchatCheckLicenseKey') {
	$licensekey = (!empty($_REQUEST['licensekey']) && is_string($_REQUEST['licensekey'])) ? sanitize_text_field($_REQUEST['licensekey']) : "";
	$update = new AtomChatInstaller(array('licensekey'=>$licensekey, 'integration'=>$atomchatIntegration, 'wpdb'=>$wpdb));
	$update -> atomchatCheckLicenseKey();
	wp_die();
}

if( !function_exists( 'atomchat_friend_ajax' ) ) {
	function atomchat_friend_ajax() {
		$response = array();

		if(isset($_POST['atomchat_bp_group_sync']) && is_string($_POST['atomchat_bp_group_sync'])){
			$update_sync_option = ($_POST['atomchat_bp_group_sync'] == 'true') ? 'true' : 'false';
			update_option( 'atomchat_bp_group_sync' , $update_sync_option, '', 'no');
		}
		if(isset($_POST['atomchat_show_friends']) && is_string($_POST['atomchat_show_friends'])){
			$update_friends_option = ($_POST['atomchat_show_friends'] == 'true') ? 'true' : 'false';
			update_option( 'atomchat_show_friends' , $update_friends_option, '', 'no');
			atomtchatCurlRequestToAPI('updateUserListSetting', array(
				'setting_key' => 'atomchat_show_friends',
				'setting_value' => $update_friends_option
			)
		);
		}
		header('Content-Type: application/json');
		echo json_encode(array('success' => 'settings updated successfully'));
		wp_die();
	}
}

if( !function_exists( 'atomchat_mycred_setting' ) ) {
	function atomchat_mycred_setting() {
		$response = array();
		$atomchat_mycred_url = "";
		if(isset($_POST['mycred_url'])){
			$atomchat_mycred_url = (!empty($_POST['mycred_url']) && is_string($_POST['mycred_url'])) ? sanitize_text_field($_POST['mycred_url']) : "";
		}
		if(isset($_POST['atomchat_enable_mycred']) && is_string($_POST['atomchat_enable_mycred'])){
			$atomchat_enable_mycred = ($_POST['atomchat_enable_mycred'] == 'true') ? 'true' : 'false';
			update_option( 'atomchat_enable_mycred' , $atomchat_enable_mycred, '', 'no');
			atomtchatCurlRequestToAPI('atomchat_mycred_setting', array(
				'setting_key' => 'Enable_MyCred',
				'setting_value' => $atomchat_enable_mycred,
				'mycred_url' => $atomchat_mycred_url
			)
		);
		}

		header('Content-Type: application/json');
		echo json_encode(array('success' => 'settings updated successfully'));
		wp_die();

	}
}

if( !function_exists( 'atomchat_update_credeits' ) ) {
	function atomchat_update_credeits(){
		$data = array();

		if(!empty($_POST['role']) && is_string($_POST['role'])){
			$role = sanitize_text_field($_POST['role']);
		}
		$data['creditToDeduct'] = (!empty($_POST['creditToDeduct']) && is_string($_POST['creditToDeduct'])) ? intval($_POST['creditToDeduct']) : 0;
		$data['creditOnMessage'] = (!empty($_POST['creditOnMessage']) && is_string($_POST['creditOnMessage'])) ? intval($_POST['creditOnMessage']) : 0;
		$data['creditToDeductAudio'] = (!empty($_POST['creditToDeductAudio']) && is_string($_POST['creditToDeductAudio'])) ? intval($_POST['creditToDeductAudio']) : 0;
		$data['creditToDeductAudioOnMinutes'] = (!empty($_POST['creditToDeductAudioOnMinutes']) && is_string($_POST['creditToDeductAudioOnMinutes'])) ? intval($_POST['creditToDeductAudioOnMinutes']) : 0;
		$data['creditToDeductVideo'] = (!empty($_POST['creditToDeductVideo']) && is_string($_POST['creditToDeductVideo'])) ? intval($_POST['creditToDeductVideo']) : 0;
		$data['creditToDeductVideoOnMinutes'] = (!empty($_POST['creditToDeductVideoOnMinutes']) && is_string($_POST['creditToDeductVideoOnMinutes'])) ? intval($_POST['creditToDeductVideoOnMinutes']) : 0;

		update_option('atomchat_'.$role , serialize($data));
		header('Content-Type: application/json');
		echo json_encode(array('success' => 'settings updated successfully'));
		wp_die();

	}
}

if( !function_exists( 'atomchat_update_auth_ajax' ) ) {
	function atomchat_update_auth_ajax() {
		$response = array();
		$atomchat_auth_key = (!empty($_POST['atomchat_auth_key']) && is_string($_POST['atomchat_auth_key'])) ? sanitize_text_field($_POST['atomchat_auth_key']) : '';
		$atomchat_api_key = (!empty($_POST['atomchat_api_key']) && is_string($_POST['atomchat_api_key'])) ? sanitize_text_field($_POST['atomchat_api_key']) : '';
		update_option( 'atomchat_auth_key' , $atomchat_auth_key);
		update_option( 'atomchat_api_key' , $atomchat_api_key);
		header('Content-Type: application/json');
		echo json_encode(array('success' => 'auth key updated successfully'));
		wp_die();
	}
}

if( !function_exists( 'atomchat_update_layout_ajax' ) ) {
	function atomchat_update_layout_ajax() {
		$response = array();
		$show_docked_layout_on_all_pages = (!empty($_POST['show_docked_layout_on_all_pages']) && is_string($_POST['show_docked_layout_on_all_pages'])) ? sanitize_text_field($_POST['show_docked_layout_on_all_pages']) : '';
		$show_name_in_chat = (!empty($_POST['show_name_in_chat']) && is_string($_POST['show_name_in_chat'])) ? sanitize_text_field($_POST['show_name_in_chat']) : '';
		update_option( 'show_docked_layout_on_all_pages' , $show_docked_layout_on_all_pages);
		update_option( 'show_name_in_chat' , $show_name_in_chat);
		header('Content-Type: application/json');
		echo json_encode(array('success' => 'layout settings updated successfully'));
		wp_die();
	}
}

if(!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchat_friend_ajax') {
	atomchat_friend_ajax();
}
if(!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchat_mycred_setting') {
	atomchat_mycred_setting();
}
if(!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchat_update_credeits') {
	atomchat_update_credeits();
}
if(!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchat_update_auth_ajax') {
	atomchat_update_auth_ajax();
}
if(!empty($_REQUEST['api']) && $_REQUEST['api'] == 'atomchat_update_layout_ajax') {
	atomchat_update_layout_ajax();
}