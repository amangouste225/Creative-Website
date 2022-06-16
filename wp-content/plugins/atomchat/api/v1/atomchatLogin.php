<?php

/**
 * atomchatLogin
 * Return basedata
 * @param $username, $password
*/

if( !function_exists( 'atomchatLogin' ) ) {
	function atomchatLogin($username, $password, $type) {
		$response = [];

		switch ($type) {
			case 'social':
			$user = get_user_by('email', $username);
			if(empty($user)){
				$response['error'] = 'Invalid User';
			}
			break;
			case 'site':
			$user = wp_authenticate($username, $password);
			if(property_exists($user, 'errors')){
				$keys = array_keys($user->errors);
				$response['error'] = reset($keys);
			}
			break;
			default:
			$response['error'] = 'Invalid type';
			break;
		}
		if(!empty($response['error'])) {
			echo json_encode($response);
			exit;
		}
		$user_id = $user->data->ID;
		$user_name = $user->data->user_login;
		$display_name = $user->data->user_nicename;
		$user_email = $user->data->user_email;
		$avatar = get_avatar_url($user_id);
		$role = reset($user->roles);
		$friends = "";

		if(function_exists('bp_core_fetch_avatar')) {
			$avatar = bp_core_fetch_avatar([
				'item_id' => $user_id,
				'type' 	  => 'thumb',
				'width'   => 32,
				'height'  => 32,
				'class'   => 'friend-avatar',
				'html'	  => false
			]);
		}
		if(function_exists('bp_core_get_user_domain')) {
			$link = bp_core_get_user_domain($user_id);
		} else {
			$link = !empty( get_userdata($user_id)->user_url ) ? get_userdata($user_id)->user_url : '';
		}
		if(function_exists('bp_get_friend_ids')) {
			$friends = !empty( bp_get_friend_ids($user_id) ) ? bp_get_friend_ids($user_id): '';
		}
		$user_info = [
			"id"		=> $user_id,
			"n"			=> $user_name,
			"dn"		=> $display_name,
			"a"			=> $avatar,
			"l"			=> $link,
			"role"		=> $role,
			"friends"	=> $friends
		];
		if(!empty($user_email)) {
			$user_info['email'] = $user_email;
		}
		if(get_option('atomchat_auth_key')){
			$user_info['auth'] = get_option('atomchat_auth_key');
		}
		if(get_option('atomchat_api_key')){
			$api_key = get_option('atomchat_api_key');
			$user_info['signature'] = md5(
				implode(',', [
					$user_id,
					$user_name,
					$api_key
				]
			)
			);
		}
		$response['success'] = [
			'basedata' => rawurlencode(
				base64_encode(
					rawurlencode(
						json_encode($user_info)
					)
				)
			)
		];
		$result = atomchatCurlRequest('atomchatLogin', [
			'basedata' => $response['success']['basedata']
		]);
		if(!empty($result) && !empty($result->userid)) {
			echo json_encode($response);
		}
		exit;
	}
}

/**
 * atomchatCurlRequest
 * Return make cURL
 * @param $action = api action that needs to be called, $data = data to send to api
*/
if( !function_exists( 'atomchatCurlRequest' ) ) {
	function atomchatCurlRequest($action, $data) {
		$app_id = get_option('atomchat_clientid');

		if(empty($app_id)) {
			return false;
		}
		$request_url = "https://".$app_id.".cometondemand.net/cometchat_update.php?action=".$action;
		if(function_exists('curl_init')){
			$result = wp_remote_post($request_url, array(
				'method' 	=> 'POST',
				'body' 		=> http_build_query($data),
				'headers'	=> array(
					'Content-Type'	=>	'application/x-www-form-urlencoded'
				)
			)
		);
			return json_decode(wp_remote_retrieve_body($result));
		}
	}	
}

$username = !empty(($_REQUEST['username']) && is_string($_REQUEST['username'])) ? sanitize_text_field($_REQUEST['username']) : '';
$password = !empty(($_REQUEST['password']) && is_string($_REQUEST['password'])) ? sanitize_text_field($_REQUEST['password']) : '';
$type = !empty(($_REQUEST['type']) && is_string($_REQUEST['type'])) ? sanitize_text_field($_REQUEST['type']) : 'site';

atomchatLogin($username, $password, $type);

?>