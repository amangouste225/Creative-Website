<?php

$groupSync = get_option('atomchat_bp_group_sync');

/**
* atomchatCreateBaseData
* Return object
* @param (type) no param
* @return (object)
*/
if( !function_exists( 'atomchatCreateBaseData' ) ) {
	function atomchatCreateBaseData(){
		global $atomchat_clientid;
		global $atomchat_base;

		if(!empty($atomchat_base)) {
			wp_enqueue_script( 'atomchat_base', plugin_dir_url( __DIR__ ).'js/scripttag.js');
			wp_add_inline_script( 'atomchat_base', 'var atomchat_base = '.$atomchat_base.';' );
		}else{
			if(get_option('atomchat_auth_key')){
				wp_enqueue_script( 'atomchat_base', plugin_dir_url( __DIR__ ).'js/scripttag.js');
				wp_add_inline_script( 'atomchat_base', 'var chat_auth = "'.get_option('atomchat_auth_key').'";' );
			}
		}
	}
}

/**
 * atomchatGetCloudDockedLayoutCode
 * Return cloud docked layout html code
 * @param (type) no param
 * @return (string) footer code
*/
if( !function_exists( 'atomchatGetCloudDockedLayoutCode' ) ) {
	function atomchatGetCloudDockedLayoutCode() {
		global $atomchat_clientid;
		global $atomchat_base;

		wp_enqueue_style("atomchat_corecss", "//fast.cometondemand.net/".$atomchat_clientid."x_x".substr(md5($atomchat_clientid),0,5).".css");
		wp_enqueue_script("atomchat_corejs", "//fast.cometondemand.net/".$atomchat_clientid."x_x".substr(md5($atomchat_clientid),0,5).".js");

	}
}

/**
 * atomchatGetShortCode
 * @param mixed $atts = width, height, layout, groupid, guid, groupsonly
 * @return shortcode
 */
if( !function_exists( 'atomchatGetShortCode' ) ) {
	function atomchatGetShortCode($atts){
		global $atomchat_clientid;
		global $atomchat_base;
		$GUID = '';
		extract(shortcode_atts(
			array(
				'width' => 400,
				'height' => 420,
				'layout' => 'embedded',
				'groupid' => '',
				'guid' => '',
				'groupsonly' => ''
			), $atts)
	);

		$site_url = get_site_url();

		if(!empty($groupid)){
			$GUID = 'crid='.$groupid;
		}

		if(!empty($guid)){
			$GUID = 'guid='.$guid;
		}

		if(!empty($groupsonly)){
			$groupsonly = '&chatroomsonly=1';
		}

		if($layout == 'docked'){
			wp_enqueue_style("atomchat_corecss", "//fast.cometondemand.net/".$atomchat_clientid."x_x".substr(md5($atomchat_clientid),0,5).".css");
			wp_enqueue_script("atomchat_corejs", "//fast.cometondemand.net/".$atomchat_clientid."x_x".substr(md5($atomchat_clientid),0,6).".js");
			/** Force enabled AtomChat Docked Layout (6) in atomchat_corejs **/
		} else{

			wp_enqueue_script( 'atomchat_shortcodejs', '//fast.cometondemand.net/'.$atomchat_clientid."x_x".substr(md5($atomchat_clientid),0,5).'x_xcorex_xembedcode.js' );

			wp_enqueue_script( 'atomchat_shortcode', plugin_dir_url( __DIR__ ).'js/scripttag.js' );
			wp_add_inline_script( 'atomchat_shortcode', 'var iframeObj = {};iframeObj.module="synergy";iframeObj.style="min-height:420px;min-width:350px;";iframeObj.width="'.$width.'px";iframeObj.height="'.$height.'px";iframeObj.src="//'.$atomchat_clientid.'.cometondemand.net/cometchat_embedded.php?'.$GUID.$groupsonly.'";if(typeof(addEmbedIframe)=="function"){addEmbedIframe(iframeObj);}' );

			return '
			<div id="cometchat_embed_synergy_container" style="width:'.$width.'px;height:'.$height.'px;max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;"></div>';
		}
	}
}

/**
 * atomChatUserDetails
 * Return atomchat_base for user login
 * @param (type) no param
*/
if( !function_exists( 'atomChatUserDetails' ) ) {
	function atomChatUserDetails() {
		global $atomchat_base;
		global $current_user;
		global $role,$user_info;

		$link = $avatar = $user_id = $user_name = $userRole = $friends = $user_fullname = '';

		if(is_user_logged_in()) {
			$user_id = $current_user->ID;
			$show_name_in_chat_as = !empty(get_option("show_name_in_chat"))?get_option("show_name_in_chat"):'fname_lname';
			/* Start: Check if first name and last name both are available then assign it as username */
			if(!empty(get_user_meta( $user_id, 'first_name', true )) && !empty(get_user_meta( $user_id, 'last_name', true ))){
				$first_name = get_user_meta( $user_id, 'first_name', true );
				$last_name = get_user_meta( $user_id, 'last_name', true );
				$user_fullname = $first_name.' '.$last_name;
			}
			/* End: Check if first name and last name both are available then assign it as username */

			/* Start: Set name of the user as per setting */
			switch ($show_name_in_chat_as) {
				case 'username':
					$user_name = $display_name = $current_user->user_login;
					break;
				case 'nickname':
					if(function_exists('get_the_author_meta')){
						$user_name = $display_name = get_the_author_meta( 'nickname', $user_id );
					}
					break;
				case 'fname_lname':
					$user_name = $display_name = $user_fullname;
					break;
				case 'display_name':
					$user_name = $display_name = $current_user->display_name;
					break;
				default:
					$user_name = $display_name = $user_fullname;
					break;
			}
			/* End: Set name of the user as per setting */

			/* If name is not set as per setting then assign username as name */
			if(empty($user_name)){
				$user_name = $current_user->user_login;
			}
			if(empty($display_name)){
				$display_name = $current_user->user_login;
			}

			$role = reset($current_user->roles);
			if(!empty($display_name)){
				$user_name = $display_name;
			}

			$avatar = getUserAvatar($user_id);

			if(function_exists('bp_loggedin_user_domain')) {
				$link = bp_loggedin_user_domain();
			} else {

				$link_temp = get_userdata($user_id)->user_url;

				if (!empty($link_temp)) {
					$link = $link_temp;
				} else {
					$link = '';
				}
			}

			if(function_exists('bp_get_friend_ids')) {
				$friends = bp_get_friend_ids($user_id);
				if(empty($friends)){
					$friends = "";
				}
			}

			/** Check third party membership plugin for role */
			$role = membershipRole($role);

			if(empty($avatar)){
				$avatar = "";
			}

			$user_info = array(
				"id"		=> $user_id,
				"n"			=> $user_name,
				"dn"		=> $display_name,
				"a"			=> $avatar,
				"l"			=> $link,
				"role"		=> $role,
				"friends"	=> $friends
			);
			if(function_exists('mycred_get_users_balance')){
				$user_info['balance'] = mycred_get_users_balance( $user_id );
			}
			if(!empty($current_user->user_email)) {
				$user_info['email'] = $current_user->user_email;
			}
			if(get_option('atomchat_auth_key')){
				$user_info['auth'] = get_option('atomchat_auth_key');
			}
			if(get_option('atomchat_api_key')){
				$api_key = get_option('atomchat_api_key');
				$user_info['signature'] = md5(implode(',', array($user_id,$user_name,$api_key)));
			}
			$atomchat_base = json_encode($user_info);
		}
	}
}


/**
* membershipRole
* Return current user role
* @param (type) string
*/

if( !function_exists( 'membershipRole' ) ) {
	function membershipRole($role){
		global $current_user;

		if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){
			$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
			$role = $current_user->membership_level->name;
		}

		// To get MemberPres Plugin Membership
		if(is_user_logged_in() && class_exists('MeprUser')){
			$u = new MeprUser($current_user->ID);
			$role = $u->get_active_subscription_titles();
		}

		//To get PremiumPress Membership
		if(is_user_logged_in() && isset(get_user_meta($current_user->ID)['ppt_subscription_key'])){
		  	$ppt_subscription_key = get_user_meta($current_user->ID)['ppt_subscription_key'][0];
		  	$role = _ppt('mem'.$ppt_subscription_key.'_name');
		}

		// If both the plugin will not present then it will return the default role
		if(empty($role)){
			$role = reset($current_user->roles);
		}

		return $role;
	}
}

/**
* getUserAvatar
* Return current user avatar
* @param (type) string
*/
if( !function_exists( 'getUserAvatar' ) ) {
	function getUserAvatar($user_id){
		global $current_user;

		$avatar = get_avatar_url($user_id);

		/** Avatar configuration for BuddyPress  */
		if(function_exists('bp_core_fetch_avatar')) {
			$avatar = bp_core_fetch_avatar(array(
					'item_id' 	=> $user_id,
					'type' 		=> 'thumb',
					'width' 	=> 32,
					'height'	=> 32,
					'class' 	=> 'friend-avatar',
					'html'		=> false
				)
			);
		}

		/** Avatar configuration for third party membership plugin  */
		if(function_exists('get_avatar')) {
			$img_tag = get_avatar($user_id);
			if(preg_match( "@src='([^']+)'@" , $img_tag, $match )){
				$src = array_pop($match);
			}else if(preg_match( '@src="([^"]+)"@' , $img_tag, $match )){
				$src = array_pop($match);
			}
			if(!empty($src)){
				$avatar = $src;
			}else{
				$avatar = "";
			}
		}
		/** Avatar configuration for DSP dating  */

		if(function_exists('display_members_photo')){
		  	$avatar = get_site_url().'/wp-content/'.display_members_photo($current_user->ID, $imagepath);
		}

		/** Avatar configuration for MediaPress */
		if(function_exists('get_user_avatar_url')) {
		  	$avatar = get_user_avatar_url(get_current_user_id(), 'thumbnail');
		}

		/** Filtering Avatar URL */
		if(!empty($avatar) && strpos($avatar, '&') !== false){
			$newAvatar = explode('&', $avatar);
			$avatar = $newAvatar[0];
		}
		/** Filtering Avatar URL */

		$avatar = !empty($avatar) ? $avatar : '';
		return $avatar;
	}
}


/**
 * atomchat_buddypress_groups_sync
 * Return create group
 * @param (type) no param
*/
if( !function_exists( 'atomchat_buddypress_groups_sync' ) ) {
	function atomchat_buddypress_groups_sync() {
		global $atomchat_clientid;
		global $current_user;
		$user_id = $current_user->ID;
		$buddypressgroupinfo = array();

		if(function_exists('bp_is_active')){
			if(bp_is_active( 'groups' )) {
				$groups = BP_Groups_Group::get(array('type'=>'active','per_page'=>10));
				foreach ($groups['groups'] as $group) {
					if ($group->status != 'public') {
						$members = BP_Groups_Member::get_group_member_ids($group->id);
						$buddypressgroupinfo[$group->id] = array('groupid' => $group->id, 'groupname'=>$group->name,'creator_id' => $group->creator_id,'clearExisting'=>true,'type'=> intval(4),'members'=>$members);
					}else{
						$members = BP_Groups_Member::get_group_member_ids($group->id);
						$buddypressgroupinfo[$group->id] = array('groupid' => $group->id, 'groupname'=>$group->name,'creator_id' => $group->creator_id,'clearExisting'=>true,'type'=>0,'members'=>$members);
					}
				}
				atomtchatCurlRequestToAPI('sendgroupinfo',array('buddypressgroupinfo' =>$buddypressgroupinfo));
			}
		}
	}
}


/**
 * atomchat_activation
 * Return create schedular
 * @param (type) no param
*/
if( !function_exists( 'atomchat_activation' ) ) {
	function atomchat_activation() {
		if (! wp_next_scheduled ( 'atomchat_buddypress_groups_sync_scheduler' )) {
			wp_schedule_event(time(), 'hourly', 'atomchat_buddypress_groups_sync_scheduler');
		}
	}
}

/**
 * atomchat_deactivation
 * Return clear schedular
 * @param (type) no param
*/
if( !function_exists( 'atomchat_deactivation' ) ) {
		function atomchat_deactivation() {
			wp_clear_scheduled_hook('atomchat_buddypress_groups_sync_scheduler');
			wp_clear_scheduled_hook('groups_group_create_complete');
		}
}

add_action('wp_head', 'atomchatCreateBaseData',1);
if(get_option('show_docked_layout_on_all_pages') === 'true'){
	add_action('wp_head', 'atomchatGetCloudDockedLayoutCode');
}
add_action('init','atomChatUserDetails');
add_shortcode('atomchat', 'atomchatGetShortCode');

if((is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) && ($groupSync === 'true')) {
	add_action('atomchat_buddypress_groups_sync_scheduler', 'atomchat_buddypress_groups_sync');
	add_action( 'groups_group_create_complete',  'atomchat_buddypress_groups_sync' );
	add_action( 'groups_details_updated', 'atomchat_buddypress_groups_sync' );
}

?>
