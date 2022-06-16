<?php
/**
 * Advance Wild card search for taxonomy
 * $Where
 * @throws error
 * @author workreap
 * @return  
 */

if( !function_exists( 'workreap_advance_search_where_freelancers' ) ) {
	function workreap_advance_search_where_freelancers($where){
		global $wpdb;
		$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
		
        if( is_page_template( 'directory/freelancer-search.php' ) ) {
			 $keyword = esc_sql($wpdb->esc_like($keyword)); 
        
			 $where  .= " AND (
				( p1.meta_key = 'fw_options' AND p1.meta_value LIKE '%".$keyword."%' ) 
				OR ( p2.meta_key = '_skills_names' AND p2.meta_value LIKE '%".$keyword."%' ) 
				OR ({$wpdb->posts}.post_title LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_content LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_excerpt LIKE '%".$keyword."%') )"; 
        } else if( is_page_template( 'directory/project-search.php' ) ) {
			 $keyword = esc_sql($wpdb->esc_like($keyword)); 
        
			 $where  .= " AND (
				( p1.meta_key = 'fw_options' AND p1.meta_value LIKE '%".$keyword."%' ) 
				OR ( p2.meta_key = '_skills_names' AND p2.meta_value LIKE '%".$keyword."%' ) 
				OR ( p3.meta_key = '_categories_names' AND p2.meta_value LIKE '%".$keyword."%' ) 
				OR ({$wpdb->posts}.post_title LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_content LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_excerpt LIKE '%".$keyword."%') )"; 
        } else if( is_page_template( 'directory/services-search.php' ) ) {
			 $keyword = esc_sql($wpdb->esc_like($keyword)); 
        
			 $where  .= " AND (
				( p1.meta_key = 'fw_options' AND p1.meta_value LIKE '%".$keyword."%' ) 
				OR ( p2.meta_key = '_categories_names' AND p2.meta_value LIKE '%".$keyword."%' ) 
				OR ({$wpdb->posts}.post_title LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_content LIKE '%".$keyword."%') 
				OR ({$wpdb->posts}.post_excerpt LIKE '%".$keyword."%') )"; 
        }
		
        return $where;
	}	
}


/**
 * Workreap typeahead skills search query
 * @throws error
 * @author Workreap
 * @return 
 */

if(!function_exists('wtTypeaheadSearchSkills')) {
    function wtTypeaheadSearchSkills() {

        if (isset( $_REQUEST['fn'] ) && $_REQUEST['fn'] == 'wtTypeaheadSearchSkills'  ) {
            $json		        = array();
            $wt_search_data		= array();
            $keyword	        = !empty($_REQUEST['terms']) ? $_REQUEST['terms'] : '';
            $skills = get_terms( array( 
                'taxonomy' 		=> 'skills',
				'orderby'       => 'name', 
				'order'         => 'ASC',
				'hide_empty'    => false,
				'fields'        => 'all',
                'name__like' 	=> $keyword
            ) );
            foreach($skills as $skill){
                $wt_search_data['skills'][] = array('title' => $skill->name,'slug'  =>  $skill->slug,'term_id' =>  $skill->term_id);
            }
			wp_send_json( $wt_search_data );
        }
    }
    add_action('wp_ajax_wtTypeaheadSearchSkills', 'wtTypeaheadSearchSkills');
    add_action('wp_ajax_nopriv_wtTypeaheadSearchSkills', 'wtTypeaheadSearchSkills');
}

/**
 * Check identity verified
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_check_post_author_identity_status' ) ) {
	add_action('workreap_check_post_author_identity_status', 'workreap_check_post_author_identity_status', 10, 1);
	function workreap_check_post_author_identity_status($postid) {
		if ( function_exists('fw_get_db_post_option' )) {
			$identity_verification    	= fw_get_db_settings_option('identity_verification');
		}
		
		$user_identity				= workreap_get_linked_profile_id( $postid,'post' );
		$user_type					= apply_filters('workreap_get_user_type', $user_identity );
		$identity_verification    	= fw_get_db_settings_option('identity_verification');
		$identity_verification_post    	= fw_get_db_settings_option('identity_verification_post');

		if( !empty($user_type) && $user_type === 'employer' ){
			if ( function_exists('fw_get_db_post_option' )) {
				$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
				$identity_verification_post    	= fw_get_db_settings_option('employer_identity_verification_post');
			}
		}

		$identity_verification_post	= !empty($identity_verification_post) ? $identity_verification_post : 'no';

		if(!empty($identity_verification) && $identity_verification === 'yes' 
			&& !empty($identity_verification_post)  && $identity_verification_post === 'no'
		){
			$is_verified	= get_post_meta($postid, 'identity_verified', true);

			if( empty( $is_verified ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Your identity is not verified, so you cannot post or buy anything.','workreap');
				wp_send_json( $json );
			}
		}
	}
}

/**
 * Check author account status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_check_post_author_status' ) ) {
	add_action('workreap_check_post_author_status', 'workreap_check_post_author_status', 10, 1);
	function workreap_check_post_author_status($postid) {
		$is_verified		= get_post_meta($postid, '_is_verified', true);
		$profile_blocked	= get_post_meta($postid, '_profile_blocked', true);

		if( empty( $is_verified ) || $is_verified === 'no' ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Your account is not verified, so you cannot post anything.','workreap');
			wp_send_json( $json );
		} else if( !empty( $profile_blocked ) && $profile_blocked === 'on' ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Your account is temporarily blocked, so you cannot post anything.','workreap');
			wp_send_json( $json );
		}
	}
}

/**
 * switch author account status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_check_switch_user_status' ) ) {
	add_action('workreap_check_switch_user_status', 'workreap_check_switch_user_status', 10, 1);
	function workreap_check_switch_user_status($postid) {
		global $current_user;
		$post_type					= get_post_type( $postid );
		$post_author				= get_post_field( 'post_author',$postid,true );

		if( function_exists('workreap_validate_user') ) { 
			workreap_validate_user();
		}; //if user is not logged in then prevent

		$account_types_permissions	= '';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
		}

		if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
			$switch_user_id	= get_user_meta($current_user->ID, 'switch_user_id', true); 
			$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
			if(!empty($switch_user_id) && $post_author == $switch_user_id ){
				$json['type'] 		= 'error';
				if( !empty($post_type) && $post_type == 'projects'){
					$json['message'] 	= esc_html__('You are not allowed to send proposal on your job','workreap');
				} else {
					$json['message'] 	= esc_html__('You are not allowed to buy your own service','workreap');
				}
				wp_send_json( $json );
			}
		}
		
	}
}

/**
 * Admin notices
 * @throws error
 * @author workreap
 * @return 
 */
if( !function_exists( 'workreap_admin_notice_error' ) ) {
	function workreap_admin_notice_error() {
		if(!is_admin()){return;}
		$theme_version = wp_get_theme('workreap');
		$class 	 = 'notice notice-success is-dismissible';
		$message = esc_html__( 'Workreap', 'workreap' ).' '.$theme_version->get('Version');

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	//add_action( 'admin_notices', 'workreap_admin_notice_error' );
}

/**
 * Advance Wild card search for taxonomy
 * $join
 * @throws error
 * @author workreap
 * @return 
 */
if( !function_exists( 'workreap_advance_search_join' ) ) {
	function workreap_advance_search_join($join){
		global $wpdb;
		if( is_page_template( 'directory/freelancer-search.php' ) ) {
			$join .=" INNER JOIN {$wpdb->postmeta} p1 ON {$wpdb->posts}.ID= p1.post_id ";
			$join .=" INNER JOIN {$wpdb->postmeta} p2 ON {$wpdb->posts}.ID= p2.post_id ";
			return $join;
		}elseif( is_page_template( 'directory/project-search.php' ) ) {
			$join .=" INNER JOIN {$wpdb->postmeta} p1 ON {$wpdb->posts}.ID= p1.post_id ";
			$join .=" INNER JOIN {$wpdb->postmeta} p2 ON {$wpdb->posts}.ID= p2.post_id ";
			$join .=" INNER JOIN {$wpdb->postmeta} p3 ON {$wpdb->posts}.ID= p3.post_id ";
			return $join;
		}elseif( is_page_template( 'directory/services-search.php' ) ) {
			$join .=" INNER JOIN {$wpdb->postmeta} p1 ON {$wpdb->posts}.ID= p1.post_id ";
			$join .=" INNER JOIN {$wpdb->postmeta} p2 ON {$wpdb->posts}.ID= p2.post_id ";
			return $join;
		}

		return $join;
	}
}

/**
 * Advance Wild card search for taxonomy 
 * $groupby
 * @throws error
 * @author workreap
 * @return 
 */
if( !function_exists( 'workreap_advance_search_groupby' ) ) {
	function workreap_advance_search_groupby($groupby){
		global $wpdb;

		// we need to group on post ID
		$groupby_id = "{$wpdb->posts}.ID";
		if(!is_search() || strpos($groupby, $groupby_id) !== false) return $groupby;

		// groupby was empty, use ours
		if(!strlen(trim($groupby))) return $groupby_id;

		// wasn't empty, append ours
		return $groupby.", ".$groupby_id;
	}
}



/**
 * Manage user colums
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_manage_user_columns')) {
    add_filter('manage_users_columns', 'workreap_user_manage_user_columns');

    function workreap_user_manage_user_columns($column) {
        $column['wt_profile']	= esc_html__('Linked profile', 'workreap');
		$column['wt_varifiled']	= esc_html__('Profile verification', 'workreap');
		
        return $column;
    }
}

/**
 * Manage user push notification
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_push_notify')) {
    add_action('workreap_user_push_notify', 'workreap_user_push_notify',10,4);
	function workreap_user_push_notify($user_ids,$title_key,$message_key,$data) {
		if (function_exists('fw_get_db_settings_option')) {
			$instance_id	= fw_get_db_settings_option('pusher_instance_id');
			$secret_key		= fw_get_db_settings_option('pusher_secret_key');
			$enable_pusher		= fw_get_db_settings_option('enable_pusher');
			
			$title_key		= !empty($title_key) ? fw_get_db_settings_option($title_key) : '';
			$message_key	= fw_get_db_settings_option($message_key);
		}
		
		$message_template	= $message_key;

		//replace data
		if(!empty($data)){
			foreach($data as $replace_key => $value){
				if(strpos($replace_key, '%') !== false){
					$message_key = str_replace($replace_key, $value, $message_key);
				}
				
				if(strpos($replace_key, '%replace_') !== false){
					$kkey	= str_replace('replace_', '', $replace_key);
					$message_template = str_replace($kkey, $value, $message_template);
				}
			}
		}
		
		$message	= !empty($message_key) ? $message_key : '';
		$title		= !empty($title_key) ? $title_key : esc_html__('Notification','workreap').' '.workreap_unique_increment(8);
		$sender		= !empty($data['sender_id']) ? $data['sender_id'] : 1;
		
		if(!empty($user_ids)){
			foreach($user_ids as $key => $ID){

				$push_post = array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_status'   => 'pending',
					'post_content'  => $message_template,
					'post_author'   => $ID,
					'post_type'     => 'push_notifications',
				);

				$push_post_id    		= wp_insert_post($push_post);
				
				//Update meta value
				if(!empty($data)){
					foreach($data as $item_key => $metavalue){
						if(strpos($item_key, '%') === false){
							update_post_meta( $push_post_id, $item_key, $metavalue );
						}
					}
				}

				//Push notify
				if( !empty($instance_id) && !empty($secret_key) && $enable_pusher === 'yes'){
					$beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
					  "instanceId" 	=> $instance_id,
					  "secretKey" 	=> $secret_key,
					));


					$publishResponse = $beamsClient->publishToUsers(
					  array("private-user-".$ID),
					  array(
						"fcm" => array(
						  "notification" => array(
							"title" 		=> $title,
							"body" 			=> $message
						  )
						),
						"apns" => array("aps" => array(
						  "alert" => array(
							"title" 		=> $title,
							"body" 			=> $message
						  )
						)),
						"web" => array(
						  "notification" => array(
							"title" 		=> $title,
							"body" 			=> $message
						  )
						)
					));

					update_post_meta( $push_post_id, 'publishId', $publishResponse->publishId );
				}
			}
		}
	}
}

/**
 * Floating chat window
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_chat_window_floating')) {
    add_filter('workreap_chat_window_floating', 'workreap_chat_window_floating',10,1);

    function workreap_chat_window_floating($detail_page_chat) {
		if ( function_exists('fw_get_db_post_option' )) {
			$chat_settings    	= fw_get_db_settings_option('chat');
		}
		
		if(!empty($chat_settings['gadget']) && $chat_settings['gadget'] === 'chat' ){
			$detail_page_chat	=  !empty( $chat_settings['chat']['floating_chat'] ) ?  $chat_settings['chat']['floating_chat'] : '';
		}
		
        return $detail_page_chat;
    }
}

/**
 * Get portfolio settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_portfolio_settings' ) ) {
	add_filter('workreap_portfolio_settings', 'workreap_portfolio_settings',10,1);
    function workreap_portfolio_settings($gadget){
        $portfolio_settings    	= fw_get_db_settings_option('portfolio');
		$portfolio_other		= !empty( $portfolio_settings['enable']['others'] ) ?  $portfolio_settings['enable']['others'] : 'no';

		if( isset($gadget) && $gadget == 'gadget' ){
			$portfolio_other		= !empty( $portfolio_settings['gadget'] ) ?  $portfolio_settings['gadget'] : 'hide';
		}
		
		return $portfolio_other;
    }
}

/**
 * Return Number Users
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_insights' ) ) {
	add_filter('workreap_freelancer_insights','workreap_freelancer_insights',10,1);
	function workreap_freelancer_insights($key){
		if (function_exists('fw_get_db_settings_option') ) {
			$freelancer_insights	= fw_get_db_settings_option('freelancer_insights');
		}
		
		if(!empty($freelancer_insights) && in_array($key, $freelancer_insights) ){
			return false;
		}
		
		return true;
	}
}

/**
 * Return insight true or false
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_employer_insights' ) ) {
	add_filter('workreap_employer_insights','workreap_employer_insights',10,1);
	function workreap_employer_insights($key){
		if (function_exists('fw_get_db_settings_option') ) {
			$employer_insights	= fw_get_db_settings_option('employer_insights');
		}
		
		if(!empty($employer_insights) && in_array($key, $employer_insights) ){
			return false;
		}
		
		return true;
	}
}

/**
 * Refine post title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_refine_title' ) ) {
	add_filter('document_title_parts','workreap_refine_title',20,1);
	function workreap_refine_title($title){
		global $post;
		if( !empty( $post->post_type) && ( $post->post_type === 'freelancers' || $post->post_type === 'employers' ) ){
			$title_data	= workreap_get_username('',$post->ID);
			$data['title'] = $title_data;
			$data['site']  = !empty($title['site']) ? $title['site'] : '';
			return $data;
		}
		   
		return $title;
	}
}

/**
 * Refine post title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_refine_pre_title' ) ) {
	//add_filter('pre_get_document_title','workreap_refine_pre_title',90,1);
	function workreap_refine_pre_title($title){
		global $post;
		if( !empty( $post->post_type) && ( $post->post_type === 'freelancers' || $post->post_type === 'employers' ) ){
			$title_data	= workreap_get_username('',$post->ID);
			$sep = apply_filters( 'document_title_separator', ' - ' );
			
			if ( is_front_page() ) {
				$tagline = get_bloginfo( 'description', 'display' );
			} else {
				$tagline = get_bloginfo( 'name', 'display' );
			}
			
			return $title_data.$sep.$tagline;
		}
		   
		return $title;
	}
}


/**
 * Date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_date_format_fix')) {
    add_filter('workreap_date_format_fix', 'workreap_date_format_fix',10,1);
    function workreap_date_format_fix($dateStr) {
		if(empty($dateStr)){ return '';}
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$calendar_format    = fw_get_db_settings_option('calendar_format');
		}
		
		$calendar_format	= !empty( $calendar_format ) ?  explode(' ',$calendar_format) : 'Y-m-d';
		
		if( !empty( $calendar_format[0] ) && $calendar_format[0] === 'd-m-Y'){
			$dateStr	= str_replace('/','-',$dateStr);
			$data   = explode(' ',$dateStr);
			$parts 	= explode("-",$data[0]);
			$_date	= $parts[2].'-'.$parts[1].'-'.$parts[0];
			return $_date;
		} else if( !empty( $calendar_format[0] ) && $calendar_format[0] === 'd/m/Y'){
			$dateStr	= str_replace('/','-',$dateStr);
			$data   = explode(' ',$dateStr);
			$parts 	= explode("-",$data[0]);
			$_date	= $parts[2].'-'.$parts[1].'-'.$parts[0];
			return $_date;
		} else {
			return $dateStr;
		}
    }
}


/**
 * Date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_date_format_field')) {
    add_filter('workreap_date_format_field', 'workreap_date_format_field',10,1);
    function workreap_date_format_field($date_val) {
		if(empty($date_val)){ return '';}
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$calendar_format    = fw_get_db_settings_option('calendar_format');
		}
		
		$calendar_format	= !empty( $calendar_format ) ? $calendar_format : 'Y-m-d';
		$date_str			= str_replace('/','-',$date_val);
		$date_str			= explode(' ',$date_str);
		$date 				= !empty( $date_str[0] ) ? new DateTime($date_str[0]) : $date_val;
		return $date->format($calendar_format);
    }
}

/**
 * Date format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_picker_date_format')) {
    add_filter('workreap_picker_date_format', 'workreap_picker_date_format',10,1);
    function workreap_picker_date_format($date_val) {
		if(empty($date_val)){ return '';}
		
		$calendar_format	= 'Y/m/d H:i:s';
		$date_str			= strtr($date_val, '/', '-');
		return date($calendar_format,strtotime($date_str));
		
    }
}


/**
 * Manage users rows column
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_manage_user_column_row')) {
    add_filter('manage_users_custom_column', 'workreap_user_manage_user_column_row', 10, 3);

    function workreap_user_manage_user_column_row($val, $column_name, $user_id) {
        switch ($column_name) {
            case 'wt_profile' :
				$user_meta	= get_userdata($user_id);
				if ( in_array( 'administrator', (array) $user_meta->roles ) ) {
					return;
				}
				
				$linked_profile	= workreap_get_linked_profile_id($user_id);
				if( !empty( $linked_profile ) ){
					$val = '<a target="_blank" href="'.esc_url(get_edit_post_link($linked_profile)).'">' . esc_html( get_the_title($linked_profile) ). '</a>';
					return $val;
				}
				
				return $val;
				
			case 'wt_varifiled' :
				$linked_profile	= workreap_get_linked_profile_id($user_id);
				$is_verified 	= get_post_meta($linked_profile, '_is_verified',true);
				if ( function_exists('fw_get_db_post_option' )) {
					$identity_verification    	= fw_get_db_settings_option('identity_verification');
				}
				
				$user_type	= apply_filters('workreap_get_user_type', $user_id );
				if( !empty($user_type) && $user_type === 'employer' ){
					if ( function_exists('fw_get_db_post_option' )) {
						$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
					}
				}
				
				//for admin only
				$user_meta	= get_userdata($user_id);
				
				if ( in_array( 'administrator', (array) $user_meta->roles ) ) {
					return;
				}
				
				$status	= isset($is_verified) && $is_verified === 'yes' ? 'reject' : 'approve';
		
				$val .= "<a title='".esc_html__('Email Verification','workreap')."' class='do_verify_user dashicons-before " . (!empty($is_verified) && $is_verified === 'yes' ? 'wt-icon-color-green' : 'wt-icon-color-red') . "' data-type='".$status."' data-id='".intval( $linked_profile )."' data-user_id='".intval( $user_id )."' href='#'><img class='wt-font-icon' src='".get_template_directory_uri()."/images/email_verified_users.svg'></a>";
				
				if ( in_array( 'freelancers', (array) $user_meta->roles ) || in_array( 'employers', (array) $user_meta->roles ) ) {
					if(!empty($identity_verification) && $identity_verification === 'yes'){
						$identity_verified  = get_post_meta($linked_profile, 'identity_verified', true);
						$verification_attachments   = get_post_meta($linked_profile, 'verification_attachments', true);
						$identity_status			= !empty($identity_verified) ? 'approved' : 'inprogress';

						$val .= "<a title='".esc_html__('Identity Verification','workreap')."' class='do_verify_identity dashicons-before " . ((!empty($identity_verified) ) ? 'wt-icon-color-green' : 'wt-icon-color-red') . " ' data-type='".$identity_status."' data-id='".intval( $linked_profile )."' data-user_id='".intval( $user_id )."'  href='#' ><img class='wt-font-icon' src='".get_template_directory_uri()."/images/identity_verification.svg'></a>";
						
						if(!empty($verification_attachments)){
							$val .= "<a data-user='".json_encode($linked_profile)."' class='do_download_identity' href='#'>" . esc_html__('View detail', 'workreap') . "</a>";
						}
					}
				}
				
				return $val;
				
				break;
            default:
        }
    }
}

/**
 * Check per hour rate settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_perhour_rate_settings')) {
    add_filter('workreap_user_perhour_rate_settings', 'workreap_user_perhour_rate_settings', 10, 1);

    function workreap_user_perhour_rate_settings($user_id='') {
		
		if( function_exists('fw_get_db_settings_option')  ){
			$hide_perhour	= fw_get_db_settings_option('hide_freelancer_perhour', $default_value = null);
		}

		if( isset($hide_perhour) && $hide_perhour === 'yes' ){
			return false;
		}
	
		$user_perhour	= get_post_meta($user_id, '_hourly_rate_settings', true);
		if( isset($user_perhour) && $user_perhour == 'on' ){
			return false;
		}
		
		return true;
    }

}

/**
 * Manage user table action links
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_user_table_action_links')) {
    add_filter('user_row_actions', 'workreap_user_user_table_action_links', 10, 2);

    function workreap_user_user_table_action_links($actions, $user) {
		$linked_profile			= workreap_get_linked_profile_id($user->ID);
		$is_verified 			= get_post_meta($linked_profile, '_is_verified',true);
		
		if ( !empty($linked_profile) && ( in_array( 'employers', (array) $user->roles ) || in_array( 'freelancers', (array) $user->roles ) ) ) {
			$actions['view']  = '<a href="'.esc_url(get_the_permalink($linked_profile)).'">' .  esc_html__('View Profile', 'workreap'). '</a>';
		}
		
		if( !empty( $linked_profile ) ){
			$profile 				= '<a href="'.esc_url(get_edit_post_link($linked_profile)).'">' .  esc_html__('linked profile', 'workreap'). '</a>';
			$actions['wt_profile']  = $profile;
		}


        return $actions;
    }

}

/**
 * Change users status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_change_status')) {
    add_action('admin_action_workreap_change_status', 'workreap_change_status');

    function workreap_change_status() {

        if (isset($_REQUEST['users']) && isset($_REQUEST['nonce'])) {
            $nonce = !empty($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
            $users = !empty($_REQUEST['users']) ? $_REQUEST['users'] : '';

            if (wp_verify_nonce($nonce, 'workreap_change_status_' . $users)) {
				
				$linked_profile			= workreap_get_linked_profile_id($users);
				$is_verified 			= get_post_meta($linked_profile, '_is_verified',true);
				
				//for admin only
				$user_meta	= get_userdata($users);
				
				if ( in_array( 'administrator', (array) $user_meta->roles ) ) {
					$is_verified = get_user_meta($user_meta->ID, '_is_verified',true);
				}

                if (isset($is_verified) && $is_verified === 'yes') {	
                    $new_status = '';
					$message_param = 'unapproved';
					
                } else {
                    $new_status 	= 'yes';
                    $message_param  = 'approved';
				}

				if(apply_filters('workreap_get_user_type', $users ) === 'freelancer'){
					//Prepare Params

					$params_array['user_identity'] 	= (int) $users;
					$params_array['profile_status'] = $message_param;
					$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $users );
					$params_array['type'] 			= 'profile_approved';
					
					//child theme : update extra settings
					do_action('wt_process_profile_verified', $params_array);
				}

                update_post_meta($linked_profile, '_is_verified', $new_status);
				update_user_meta($users, '_is_verified', $new_status);

                $redirect = admin_url('users.php?updated=' . $message_param);
				
            } else {
                $redirect = admin_url('users.php?updated=workreap_false');
            }
        } else {
            $redirect = admin_url('users.php?updated=workreap_false');
        }
		
        wp_redirect($redirect);
    }

}

/**
 * Users change status notices
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_change_status_notices')) {
    add_action('admin_notices', 'workreap_user_change_status_notices');

    function workreap_user_change_status_notices() {
        global $pagenow;
        if ($pagenow == 'users.php') {
            if (isset($_REQUEST['updated'])) {
                $message = $_REQUEST['updated'];
                if ($message == 'workreap_false') {
                    print '<div class="updated notice error is-dismissible"><p>' . esc_html__('Something wrong. Please try again.', 'workreap') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'workreap') . '</span></button></div>';
                }
                if ($message == 'approved') {
                    print '<div class="updated notice is-dismissible"><p>' . esc_html__('User approved.', 'workreap') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'workreap') . '</span></button></div>';
                }
                if ($message == 'unapproved') {
                    print '<div class="updated notice is-dismissible"><p>' . esc_html__('User unapproved.', 'workreap') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'workreap') . '</span></button></div>';
                }
            }
        }
    }

}

/**
 * Get user role by id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('workreap_get_user_role') ){
  function workreap_get_user_role($user_id = ''){
    if( !empty( $user_id ) ){
        $user = get_userdata( $user_id );
        $user_roles = $user->roles;
        $role = '';
        if( !empty( $user_roles[0] ) ) {
            $role = $user_roles[0];
        }
        return $role;
    }
  }
  add_filter('workreap_get_user_role','workreap_get_user_role', 10, 1);
}
/**
 * User password hint
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_password_hint')) {
	add_filter('password_hint','workreap_password_hint');
	function workreap_password_hint($hint) {
		$hint 	= esc_html__( 'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).','workreap' );
		return $hint;
	}
}

/**
 * User reset password
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_reset_password_form')) {

    function workreap_reset_password_form() {
        global $wpdb;      
        if (!empty($_GET['key']) && ( isset($_GET['action']) && $_GET['action'] == 'reset_pwd' ) && !empty($_GET['login']) ) {
            $reset_key 			= sanitize_text_field( $_GET['key'] );
            $user_login 		= $_GET['login'];
            $reset_action 		= sanitize_text_field( $_GET['action'] );

            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
            if ($reset_key === $key) {
                $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

                $user_login = $user_data->user_login;
                $user_email = $user_data->user_email;

                if (!empty($user_data)) {
                    ob_start();
                    ?>
                    <div class="modal fade wt-user-reset-model wt-resetpass" tabindex="-1" role="dialog">
                        <div class="modal-dialog wt-modaldialog">
                            <div class="wt-modalcontentvtwo modal-content wt-modalcontent">
                               	<div class="wt-popuptitle">
									<h4><?php esc_html_e('Reset Password', 'workreap'); ?></h4>
									<a href="#" onclick="event_preventDefault(event);" class="wt-closebtn close"><i class="lnr lnr-cross" data-dismiss="modal"></i></a>
								</div>
                                <div class="panel-lostps modal-body">
                                    <form class="wt-form-modal wt-form-signup wt-reset_password_form">
                                        <fieldset>
                                            <p><?php echo wp_get_password_hint(); ?></p>
                                            <div class="forgot-fields">
                                                <div class="form-group">
                                                    <label for="password"><?php esc_html_e('New password', 'workreap') ?></label>
                                                    <input type="password"  name="password" id="password" class="form-control" size="20" value="" autocomplete="off" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="verify_password"><?php esc_html_e('Repeat new password', 'workreap') ?></label>
                                                    <input type="password" name="verify_password" id="verify_password" class="form-control" size="20" value="" autocomplete="off" />
                                                </div>
                                            </div>                                     
                                            <button class="wt-btn wt-btn-lg wt-change-password" type="button"><?php esc_html_e('Reset Password', 'workreap'); ?></button>
                                            <input type="hidden" name="key" value="<?php echo esc_attr($reset_key); ?>" />
                                            <input type="hidden" name="reset_action" value="<?php echo esc_attr($reset_action); ?>" />
                                            <input type="hidden" name="login" value="<?php echo esc_attr($user_login); ?>" />
                                        </fieldset>
                                    </form>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" onclick="javascript:void(0);" class="open-reset-window" data-toggle="modal" data-target=".wt-user-reset-model"></a>
                    <script>jQuery(document).ready(function ($) {setTimeout(function() {jQuery('.open-reset-window').trigger('click');},100);});</script>
                    <?php
                    echo ob_get_clean();
					
                }
            }
        }
    }

    add_action('workreap_reset_password_form', 'workreap_reset_password_form');
}

/**
 * Employees list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_employees_list' ) ){
	function workreap_print_employees_list($db_key=''){
		$list = worktic_get_employees_list();
		$counter = 0;
		if( !empty( $list ) ){
			if( !empty( $db_key ) ){
				$checked_val = $db_key;
			} else{
				$checked_val = 1;
			}
			
			foreach ( $list as $key => $value ) { 
			$counter++;
			?>
			<span class="wt-radio">
				<input id="wt-just<?php echo esc_attr( $counter ); ?>" <?php checked( $value['value'], $checked_val); ?> type="radio" name="employees" value="<?php echo esc_attr( $value['value'] ); ?>">
				<label for="wt-just<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
			</span>
		<?php
	} } }
	add_action('workreap_print_employees_list', 'workreap_print_employees_list', 10,1);
}

/**
 * Get departments list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_departments_list' ) ) {
	function worktic_get_departments_list($db_key=''){
		if( taxonomy_exists('department') ) {
			$departments = get_terms( array(
				'taxonomy' => 'department',
				'hide_empty' => false,
			) );

			if( !empty( $departments ) ){ 
				if( !empty( $db_key ) ){
					$checked_val = $db_key;
				} else{
					$checked_val = '';
				}

				$counter = 0;
				foreach ($departments as $key => $value) { 
					$counter++;
					if( empty( $checked_val ) && $counter === 1 ){
						$checked_val = $value->term_id;
					}
					?>
					<span class="wt-radio">
						<input id="wt-department<?php echo esc_attr( $counter ); ?>" <?php checked( $value->term_id, $checked_val); ?> type="radio" name="department" value="<?php echo esc_attr( $value->term_id ); ?>">
						<label for="wt-department<?php echo esc_attr( $counter ); ?>"><?php echo esc_html( $value->name ); ?></label>
					</span>	
				<?php } 			
			}
		}
	}
	add_action('worktic_get_departments_list', 'worktic_get_departments_list', 10,1);
}

/**
 * Redirect detail page
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_redirect_single_page' ) ) {
	add_action( 'template_redirect', 'workreap_redirect_single_page' );
	function workreap_redirect_single_page() {
		if ( is_singular( 'employers' ) ) {
			if (function_exists('fw_get_db_post_option') ) {
				$hide_emp_detail	= fw_get_db_settings_option('hide_emp_detail');
			}

			if(!empty($hide_emp_detail) && $hide_emp_detail === 'yes'){
				wp_redirect( home_url('/'));
				exit;
			}
		}
	}
}

/**
 * Get location list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_locations_list' ) ) {
	function worktic_get_locations_list($name='location',$selected=''){
		if( taxonomy_exists('locations') ) {
			wp_dropdown_categories( array(
									'taxonomy' 			=> 'locations',
									'hide_empty' 		=> false,
									'hierarchical' 		=> 1,
									'show_option_all' 	=> esc_html__('Select Location', 'workreap'),
									'walker' 			=> new Workreap_Walker_Location_Dropdown,
									'class' 			=> 'item-location-dpss chosen-select',
									'orderby' 			=> 'name',
									'name' 				=> $name,
									'id'                => 'location-dp',
									'selected' 			=> $selected
								)
							);
		}
	}
	add_action('worktic_get_locations_list', 'worktic_get_locations_list', 10,2);
}

/**
 * Get category list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_categories_list' ) ) {
	function workreap_get_categories_list($name='location',$selected=''){
		if (function_exists('fw_get_db_post_option') ) {
			$services_categories	= fw_get_db_settings_option('services_categories');
		}

		$services_categories	= !empty($services_categories) ? $services_categories : 'no';
		if( !empty($services_categories) && $services_categories === 'no' ) {
			$taxonomy_type	= 'project_cat';
		}else{
			$taxonomy_type	= 'service_categories';
		}
		
		wp_dropdown_categories( array(
								'taxonomy' 			=> $taxonomy_type,
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'walker' 			=> new Workreap_Walker_Category_Dropdown,
								'class' 			=> 'item-category-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'project_cat_multiselect',
								'current' 			=> $selected,
								'required' 			=> 'required',
							)
						);
	}
	add_action('workreap_get_categories_list', 'workreap_get_categories_list', 10,2);
}

/**
 * Get category list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_project_categories_list' ) ) {
	function workreap_get_project_categories_list($name='location',$selected=''){
		$taxonomy_type	= 'project_cat';
		
		wp_dropdown_categories( array(
								'taxonomy' 			=> $taxonomy_type,
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'walker' 			=> new Workreap_Walker_Category_Dropdown,
								'class' 			=> 'item-category-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'project_cat_multiselect',
								'current' 			=> $selected,
								'required' 			=> 'required',
							)
						);
	}
	add_action('workreap_get_project_categories_list', 'workreap_get_project_categories_list', 10,2);
}

/**
 * Get category list
 * of given taxonomy
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_cat_list' ) ) {
	function workreap_get_cat_list($taxonomy, $id, $name='location',$selected=''){
		wp_dropdown_categories( array(
								'taxonomy' 			=> $taxonomy,
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'walker' 			=> new Workreap_Walker_Category_Dropdown,
								'class' 			=> 'item-category-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => $id,
								'current' 			=> $selected,
								'required' 			=> 'required',
							)
						);
	}
	add_action('workreap_get_cat_list', 'workreap_get_cat_list', 10, 4);
}

/**
 * init multiselect wp dropdown
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_apply_multiple_select_dropdown' ) ) {
	add_filter('wp_dropdown_cats','workreap_apply_multiple_select_dropdown',10,2);
	function workreap_apply_multiple_select_dropdown($output,$arguments){
		if( !empty( $arguments['id'] ) && $arguments['id'] === 'project_cat_multiselect' ){
			$output = str_replace('required','multiple data-placeholder="'.esc_html__('Select categories','workreap').'"',$output);
		}
		
		return trim( $output );
	}
}

/**
 * Hired freelancer 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_hired_freelancer_html' ) ) {
	add_filter('workreap_hired_freelancer_html', 'workreap_hired_freelancer_html', 10, 1);
	function workreap_hired_freelancer_html($post_id='') {
		$proposal_id				= get_post_meta( $post_id, '_proposal_id', true);
		$hired_freelance_id			= get_post_field('post_author',$proposal_id);
		$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
		$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id);
		$hired_freelancer_title 	= esc_html( get_the_title( $hire_linked_profile ));
		$hired_freelancer_avatar 	= apply_filters(
			'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 100, 'height' => 100 )
		);
		ob_start();
		?>
		<div class="wt-rightarea">
			<div class="wt-hireduserstatus">
				<h4><?php esc_html_e('Hired','workreap');?></h4>
				<span><?php echo esc_html($hired_freelancer_title); ?></span>
				<ul class="wt-hireduserimgs">
					<li>
						<figure>
							<img src="<?php echo esc_url( $hired_freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>">
						</figure>
					</li>
				</ul>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
}

/**
 * milstone freelancer 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_milstone_freelancer_html' ) ) {
	add_filter('workreap_milstone_freelancer_html', 'workreap_milstone_freelancer_html', 10, 1);
	function workreap_milstone_freelancer_html($proposal_id='') {
		$hired_freelance_id			= get_post_field('post_author',$proposal_id);
		$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
		$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id);
		$hired_freelancer_title 	= esc_html( workreap_get_username('', $hire_linked_profile ));
		$hired_freelancer_avatar 	= apply_filters(
			'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 100, 'height' => 100 )
		);
		ob_start();
		?>
		<div class="wt-rightarea">
			<div class="wt-hireduserstatus">
				<h4><?php esc_html_e('Hired','workreap');?></h4>
				<span><?php echo esc_html($hired_freelancer_title); ?></span>
				<ul class="wt-hireduserimgs">
					<li>
						<figure>
							<img src="<?php echo esc_url( $hired_freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>">
						</figure>
					</li>
				</ul>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
}

/**
 * milstone amount stat 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_milstone_amount_statistics' ) ) {
	add_filter('workreap_milstone_amount_statistics', 'workreap_milstone_amount_statistics', 10, 2);
	function workreap_milstone_amount_statistics($proposal_id='',$type='') {
		if(!empty($proposal_id)) {
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$project_id		= get_post_meta( $proposal_id, '_project_id', true );
			$total_price	= get_post_meta( $proposal_id, '_amount', true );
			$total_price	= !empty($total_price) ? workreap_price_format($total_price,'return') : 0;

			$completed_price	= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'completed','amount') :'';
			$completed_price	= !empty($completed_price) ? workreap_price_format($completed_price,'return') : workreap_price_format(0,'return');

			$hired_price		= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'hired','amount') : 0;
			$hired_price		= !empty($hired_price) ? workreap_price_format($hired_price,'return') : workreap_price_format(0,'return');

			$pending_price		= workreap_get_milestone_statistics($proposal_id,'pending');
			$pending_price		= !empty($pending_price) ? workreap_price_format($pending_price,'return') : workreap_price_format(0,'return');
			
			$total_budget	= !empty($milestone['enable']['total_budget']['url']) ? $milestone['enable']['total_budget']['url'] : get_template_directory_uri() . '/images/budget.png';
			$in_escrow		= !empty($milestone['enable']['in_escrow']['url']) ? $milestone['enable']['in_escrow']['url'] : get_template_directory_uri() . '/images/escrow.png';
			$milestone_paid	= !empty($milestone['enable']['milestone_paid']['url']) ? $milestone['enable']['milestone_paid']['url'] : get_template_directory_uri() . '/images/paid.png';
			$remainings		= !empty($milestone['enable']['remainings']['url']) ? $milestone['enable']['remainings']['url'] : get_template_directory_uri() . '/images/remainings.png';
			ob_start();
			?>
			<div class="wt-tabscontenttitle">
				<h2><?php esc_html_e('Project budget details','workreap');?></h2>
			</div>
			<div class="wt-milestonesingle__detail wt-flexbox">
				<ul>
					<li class="toolip-wrapo">
						<div class="wt-milestonesingle__detail--img">
							<img src="<?php echo esc_url($total_budget);?>" alt="<?php esc_attr_e('Total Budget','workreap');?>">
						</div>
						<div class="wt-milestonesingle__detail--description">
							<h3 class="wt-green"><?php echo esc_html($total_price);?></h3>
							<span><?php esc_html_e('Total Budget','workreap');?></span>
						</div>
						<?php if(!empty($type)) { do_action('workreap_get_tooltip','element','total_budget');}?>
					</li>

					<li class="toolip-wrapo">
						<div class="wt-milestonesingle__detail--img">
							<img src="<?php echo esc_url($in_escrow);?>" alt="<?php esc_attr_e('In Escrow','workreap');?>">
						</div>
						<div class="wt-milestonesingle__detail--description">
							<h3 class="wt-blue"><?php echo esc_html($hired_price);?></h3>
							<span><?php esc_html_e('In Escrow','workreap');?></span>
						</div>
						<?php if(!empty($type)) { do_action('workreap_get_tooltip','element','milestone_paid'); }?>
					</li>

					<li class="toolip-wrapo">
						<div class="wt-milestonesingle__detail--img">
							<img src="<?php echo esc_url($milestone_paid);?>" alt="<?php esc_attr_e('Milestone Paid','workreap');?>">
						</div>
						<div class="wt-milestonesingle__detail--description">
							<h3 class="wt-purple"><?php echo esc_html($completed_price);?></h3>
							<span><?php esc_html_e('Milestone Paid','workreap');?></span>
						</div>
						<?php if(!empty($type)) { do_action('workreap_get_tooltip','element','milestone_paid'); }?>
					</li>

					<li class="toolip-wrapo">
						<div class="wt-milestonesingle__detail--img">
							<img src="<?php echo esc_url($remainings);?>" alt="<?php esc_attr_e('Remainings','workreap');?>">
						</div>
						<div class="wt-milestonesingle__detail--description">
							<h3 class="wt-red"><?php echo esc_html($pending_price);?></h3>
							<span><?php esc_html_e('Remainings','workreap');?></span>
						</div>
						<?php if(!empty($type)) { do_action('workreap_get_tooltip','element','remainings'); }?>
					</li>
				</ul>
			</div>
		<?php
			echo ob_get_clean();
		}
	}
}
/**
 * Employer Project
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_project_employer_html' ) ) {
	add_filter('workreap_project_employer_html', 'workreap_project_employer_html', 10, 1);
	function workreap_project_employer_html($post_id='') {
		$employer_id					= get_post_field('post_author',$post_id);
		$employer_id					= !empty( $employer_id ) ? intval( $employer_id ) : '';
		$employer_linked_profile		= workreap_get_linked_profile_id($employer_id);
		$employer_frelancer_title 		= esc_html( get_the_title( $employer_linked_profile ));
		$employer_avatar 				= apply_filters(
			'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $employer_linked_profile ), array( 'width' => 100, 'height' => 100 )
		);
		ob_start();
		?>
		<div class="wt-rightarea">
			<div class="wt-hireduserstatus">
				<h4><?php esc_html_e('Employer','workreap');?></h4>
				<span><?php echo esc_html($employer_frelancer_title); ?></span>
				<ul class="wt-hireduserimgs">
					<li>
						<figure>
							<img src="<?php echo esc_url( $employer_avatar );?>" alt="<?php esc_attr_e('employer','workreap');?>">
						</figure>
					</li>
				</ul>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}
}


/**
 * Freelancer per hour Rate
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_freelancer_per_hour_rate' ) ) {
	function workreap_freelancer_per_hour_rate($user_id='',$return='no'){
		$rat_settings	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$rat_settings	= fw_get_db_settings_option('freelancer_price_option', $default_value = null);
		}
		
		if( apply_filters('workreap_user_perhour_rate_settings',$user_id) === true ){
			$perhour_rate	= '';
			if( function_exists('fw_get_db_post_option') ){
				$perhour_rate	= fw_get_db_post_option($user_id, '_perhour_rate', true);
			}
			$perhour_rate				= function_exists('workreap_price_format') && !empty($perhour_rate) ? workreap_price_format($perhour_rate,'return') : '';
			
			if(!empty($rat_settings) && $rat_settings === 'enable' ){
				$max_price	= '';
				if( function_exists('fw_get_db_post_option') ){
					$max_price	= fw_get_db_post_option($user_id, 'max_price', true);
				}
				
				$max_price			= function_exists('workreap_price_format') && !empty($max_price) ? '-'.workreap_price_format($max_price,'return') : '';
				$freelancer_rate	= !empty($perhour_rate) ? $perhour_rate.' '.$max_price : '';
			} else {
				$freelancer_rate	= $perhour_rate;
			}
			
			ob_start();
			?>
				<li><span><i class="fa fa-money"></i><?php echo esc_html($freelancer_rate);?>&nbsp;/&nbsp;<?php esc_html_e('hr','workreap');?></span></li>
			<?php
			if( $return === 'yes' ) {
				return ob_get_clean();
			} else {
				echo ob_get_clean();
			}
		}
	}
	
	add_action( 'workreap_freelancer_per_hour_rate', 'workreap_freelancer_per_hour_rate',10 );
	add_filter( 'workreap_freelancer_per_hour_rate', 'workreap_freelancer_per_hour_rate',10 ,2 );
}

/**
 * get allowed featured by key and user id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_feature_allowed')) {

    function workreap_is_feature_allowed( $key,$user_id ) {
		$is_enabled	= workreap_get_subscription_metadata( $key,$user_id );
        if (!empty($is_enabled) && $is_enabled == 'yes') {
            return true;
        } else {
            return false;
        }
		
    }
	
	add_filter('workreap_is_feature_allowed', 'workreap_is_feature_allowed', 10 , 2);
}

/**
 * get allowed featured by key and user id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_show_packages_if_expired')) {

    function workreap_show_packages_if_expired($user_identity) {
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$show_packages_if 	= fw_get_db_settings_option( 'show_packages_if', $default_value = null );
		} 

		$wt_subscription 	= get_user_meta($user_identity, 'wt_subscription', true);
		if( empty($wt_subscription ) && $show_packages_if === 'yes'){
			return true;
		}
		
		return false;
    }
	
	add_filter('workreap_show_packages_if_expired', 'workreap_show_packages_if_expired', 10 , 2);
}

/**
 * subscription feature value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_feature_value')) {

    function workreap_is_feature_value( $key,$user_id ) {
		$value	= workreap_get_subscription_metadata( $key,$user_id );
        if ( !empty($value) ) {
            return $value;
        } else {
            return false;
        }
    }
	add_filter('workreap_is_feature_value', 'workreap_is_feature_value', 10 , 2);
}

/**
 * get number of remaining connects
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_feature_connects')) {

    function workreap_feature_connects( $user_id ) {
		if(apply_filters('workreap_is_listing_free',false,$user_id) === true ){
			return true;
		}
		
		$remaning_connects		= workreap_get_subscription_metadata( 'wt_connects',intval($user_id) );
		$remaning_connects		= !empty( $remaning_connects ) ? intval( $remaning_connects ) : 0;
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$defult_connects 	= fw_get_db_settings_option( 'proposal_connects', $default_value = null );
			$defult_connects	= !empty( $defult_connects ) ? intval( $defult_connects ) : '';
		} 

        if ( ( !empty($remaning_connects) && !empty($defult_connects) ) && ( $remaning_connects >= $defult_connects) ) {
            return $remaning_connects;
        } else {
            return false;
        }
    }
	
	add_filter('workreap_feature_connects', 'workreap_feature_connects', 10 , 1);
}

/**
 * @check Select features job values for employer
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_feature_job')) {

    function workreap_is_feature_job( $key,$user_id ) {
		$featured_jobs		= workreap_get_subscription_metadata( $key,$user_id );
		$featured_jobs		= !empty( $featured_jobs ) ? intval( $featured_jobs ) : '';
		$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$user_id );
		
        if ( !empty($featured_jobs) ) {
        	$jobs_count 	= workreap_count_posts_by_meta( 'projects', $user_id, '_expiry_string', $expiry_string );
			$jobs_count		= !empty( $jobs_count ) ? intval( $jobs_count ) : '';
			if( $featured_jobs >= $jobs_count ) {
				return true;
			} else {
				return false;
			}
			
        } else {
            return false;
        }
    }
	add_filter('workreap_is_feature_job', 'workreap_is_feature_job', 10 , 2);
}

/**
 * @check if jobs can be posted
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_job_posting_allowed')) {

    function workreap_is_job_posting_allowed( $key,$user_id,$featured='' ) {
		if(apply_filters('workreap_is_listing_free',false,$user_id) === true ){
			return true;
		}
		
		$wt_subscription 	= get_user_meta($user_id, 'wt_subscription', true);
		
		$current_date 		= current_time('mysql');
		
		if ( is_array( $wt_subscription ) && !empty( $wt_subscription['subscription_featured_string'] ) ) {
			if (!empty($wt_subscription['subscription_featured_string']) && $wt_subscription['subscription_featured_string'] > strtotime($current_date)) {
				$available_jobs		= !empty( $wt_subscription['wt_jobs']) ? intval( $wt_subscription['wt_jobs'] ) : '';
				$wt_featured_jobs	= !empty( $wt_subscription['wt_featured_jobs']) ? intval( $wt_subscription['wt_featured_jobs'] ) : '';
				$expiry_string		= $wt_subscription['subscription_featured_string'];

				if ( $featured === 'yes' ) {
					
					if( !empty( $wt_featured_jobs) && $wt_featured_jobs >= 1 ) {
						$jobs_count 	= workreap_count_featured_by_meta( 'projects', $user_id, '_featured_job_string', $expiry_string );
						$jobs_count		= !empty( $jobs_count ) ? intval( $jobs_count ) : '';
						if( $wt_featured_jobs >= $jobs_count ) {
							return true;
						} else {
							return false;
						}

					} else {
						return false;
					}
					
				} else{
					if( !empty( $available_jobs) && $available_jobs >= 1 ) {
						$jobs_count 	= workreap_count_posts_by_meta( 'projects', $user_id, '_expiry_string', $expiry_string );
						$jobs_count		= !empty( $jobs_count ) ? intval( $jobs_count ) : '';
						if( $available_jobs > 0 ) {
							return true;
						} else {
							return false;
						}

					} else {
						return false;
					}
				}
				
			}else{
				return false;
			}
        } else{
			return false;
		}

    }
	add_filter('workreap_is_job_posting_allowed', 'workreap_is_job_posting_allowed', 10 , 3);
}


/**
 * @check if service can be posted
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_service_posting_allowed')) {

    function workreap_is_service_posting_allowed( $key,$user_id,$featured='' ) {
		if(apply_filters('workreap_is_listing_free',false,$user_id) === true ){
			return true;
		}
		
		$wt_subscription 	= get_user_meta($user_id, 'wt_subscription', true);
		$current_date 		= current_time('mysql');

		if ( is_array( $wt_subscription ) && !empty( $wt_subscription['subscription_featured_string'] ) ) {
			if (!empty($wt_subscription['subscription_featured_string']) && $wt_subscription['subscription_featured_string'] > strtotime($current_date)) {
				$available_services		= !empty( $wt_subscription['wt_services']) ? intval( $wt_subscription['wt_services'] ) : '';
				$wt_featured_services	= !empty( $wt_subscription['wt_featured_services']) ? intval( $wt_subscription['wt_featured_services'] ) : '';
				$expiry_string			= $wt_subscription['subscription_featured_string'];
;
				if ( $featured === 'yes' ) {
					if( !empty( $wt_featured_services) && $wt_featured_services >= 1 ) {
						$services_count 	= workreap_count_featured_by_meta( 'micro-services', $user_id, '_featured_service_string', $expiry_string );
						$services_count		= !empty( $services_count ) ? intval( $services_count ) : '';
						
						if( $wt_featured_services >= $services_count ) {
							return true;
						} else {
							return false;
						}

					} else {
						return false;
					}
				} else{
					if ( !empty($available_services) ) {
						$services_count 	= workreap_count_posts_by_meta( 'micro-services', $user_id, '_expiry_string', $expiry_string );
						$services_count		= !empty( $services_count ) ? intval( $services_count ) : '';
						if( $available_services > 0 ) {
							return true;
						} else {
							return false;
						}

					} else {
						return false;
					}
				}
			}else{
				return false;
			}
        } else{
			return false;
		}
    }
	add_filter('workreap_is_service_posting_allowed', 'workreap_is_service_posting_allowed', 10 , 3);
}

/**
**
 * @check Select featured jobs for employer
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_featured_job')) {

    function workreap_featured_job( $user_id ) {
		$featured_jobs		= workreap_get_subscription_metadata( 'wt_featured_jobs',$user_id );
		$featured_jobs		= !empty( $featured_jobs ) ? intval( $featured_jobs ) : '';
		
		$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$user_id );
		$expiry_string		= !empty( $expiry_string ) ? intval( $expiry_string ) : 0;
		
        if ( !empty($featured_jobs) ) {
        	$jobs_count 	= workreap_count_posts_by_meta( 'projects', $user_id, '_featured_job_string', $expiry_string );
			$jobs_count		= !empty( $jobs_count ) ? intval( $jobs_count ) : '';
			
			if( $featured_jobs > $jobs_count ) {
				return true;
			} else {
				return false;
			}
			
        } else {
            return false;
        }
    }
	add_filter('workreap_featured_job', 'workreap_featured_job', 10 , 2);
}

/**
**
 *  Count posts by meta keys and autor
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_count_posts_by_meta')) {

    function workreap_count_posts_by_meta( $post_type, $author_id, $key, $value, $status ='' ) {
		$meta_query_args	= array();
		$args 			= array(
							'posts_per_page' => -1,
							'post_type' => $post_type
						);
		//status
		if( !empty( $author_id ) ){
			$args['author'] = $author_id;	
		}
		//status
		if( !empty( $status ) ){
			if(!empty($status) && is_array($status)){
				$args['post_status'] = $status;	
			} else{
				$args['post_status'] = array( $status );	
			}
			
		}
		
		//meta filterss
		if( !empty( $key ) && !empty( $value ) ){
			$meta_query_args[] = array(
								'key' 		=> $key,
								'value' 	=> $value,
								'compare' 	=> '='
							);
		
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);	
		}

		$query 				= new WP_Query($args);
		$count_post 		= $query->found_posts;
		return $count_post;
    }
	add_filter('workreap_count_posts_by_meta', 'workreap_count_posts_by_meta', 10 , 5);
}


/**
 * Get numaric values
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_numaric_values')) {

    function workreap_get_numaric_values($step = 1,$max = 1) {
		$number_values = array(
                        'step' => $step,
                        'min' => $max
                    );
		return $number_values;
	}
	add_action('workreap_get_numaric_values', 'workreap_get_numaric_values' , 2);
}

/**
 * Get option values
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_option_values')) {

    function workreap_get_option_values($options ='') {
		if ( empty ( $options )) {
			$options = array ( 'yes' => esc_html__( 'Yes', 'workreap' ),'no' => esc_html__( 'No', 'workreap' ));
		}
		
		$number_values = $options;
		return $number_values;
	}
	add_action('workreap_get_option_values', 'workreap_get_option_values' , 2);
}

/**
 * Get packages duration type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_duration_types')) {

    function workreap_get_duration_types($key='',$type='array') {
		$durations = array(
						'weekly' 	=> array( 
										'title' => esc_html__( 'Weekly ( 7 days )', 'workreap' ),
										'value' => 7
									),
						'biweekly' 	=> array( 
										'title' => esc_html__( 'Biweekly ( 14 days )', 'workreap' ),
										'value' => 14
									),
                        'monthly' 	=> array( 
										'title' => esc_html__( 'Monthly', 'workreap' ),
										'value' => 30
									),
						'bimonthly' 	=> array( 
										'title' => esc_html__( 'Bimonthly ( 60 days )', 'workreap' ),
										'value' => 60
									),
                        'quarterly' 	=> array( 
										'title' => esc_html__( 'Quarterly ( 90 days )', 'workreap' ),
										'value' => 90
									),
						'biannually'	=> array( 
										'title' => esc_html__( 'Biannually( 6 Months )', 'workreap' ),
										'value' => 180
									),
						'yearly'	=> array( 
										'title' => esc_html__( 'Yearly', 'workreap' ),
										'value' => 365
									),
						'2yearly'	=> array( 
										'title' => esc_html__( 'For 2 Years', 'workreap' ),
										'value' => 730
									),
						'3yearly'	=> array( 
										'title' => esc_html__( 'For 3 Years', 'workreap' ),
										'value' => 1095
									),
						'4yearly'	=> array( 
										'title' => esc_html__( 'For 4 Years', 'workreap' ),
										'value' => 1460
									),
						'5yearly'	=> array( 
										'title' => esc_html__( 'For 5 Years', 'workreap' ),
										'value' => 1825
									)
                    );
		
		
		$durations	= apply_filters('workreap_filter_duration_types',$durations);
		
		if( $type === 'title' ){
			return !empty( $durations[$key]['title'] ) ? $durations[$key]['title'] : '';
		} else if( $type === 'value' ){
			return !empty( $durations[$key]['value'] ) ? $durations[$key]['value'] : '';
		} else{
			$options = array();
			foreach( $durations as $key => $item ){
				$options[$key] =  $item['title'];
			}
			return $options;
		}
		
	}
	add_action('workreap_get_duration_types', 'workreap_get_duration_types');
}

/**
 * Package features
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_print_pakages_features')) {
	add_action('workreap_print_pakages_features', 'workreap_print_pakages_features',10,4);
    function workreap_print_pakages_features($key='',$val='', $post_id='', $type='') {
		$item	 = get_post_meta($post_id,$key,true);
		$text	 = !empty( $val['text'] ) ? $val['text'] : '';
		$lable	 = !empty( $val['title'] ) ? $val['title'] : '';
		$class	 = '';
		
		if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
			if($type == 'v2') {
				$class   .= "class=jb-not-available"; 
				$feature = '<i class="fa fa-times-circle"></i>';
			} else {
				$feature = '<i class="ti-na"></i>';	
			}
		} elseif( isset( $item ) && $item === 'yes' ){
			if($type == 'v2') {
				$class   .= "class=jb-available";
				$feature  = '<i class="fa fa-check-circle"></i>';
			} else {
				$feature = '<i class="ti-check"></i>';
			}
		} elseif( isset( $key ) && $key === 'wt_duration_type' ){
			$feature = workreap_get_duration_types($item,'value');
		} elseif( isset( $key ) && $key === 'wt_badget' ){
			if(!empty($item) ){
				$badges		= get_term( intval($item) );
				if(!empty($badges->name)) {
					$feature	= $badges->name;
				} else {
					$feature	= '';
				}
			}
		} else{
			$feature = $item;
		}
		
		ob_start();
		?>
			<li>
				<?php if($type == 'v2') { ?>
					<?php if( !empty( $lable ) ){?>
						<p><?php echo esc_html($lable);?></p>
					<?php } ?>
					<span <?php echo esc_html($class); ?>>				
						<?php echo do_shortcode($feature);?>&nbsp;<?php echo esc_html($text);?>
					</span>
				<?php } else { ?>
					<span <?php echo esc_html($class); ?>>				
						<?php if( !empty( $lable ) ){?>
							<em><?php echo esc_html($lable);?></em>
						<?php } ?>
							<dl><?php echo do_shortcode($feature);?>&nbsp;<?php echo esc_html($text);?></dl>
					</span>
				<?php } ?>
			</li>
		<?php
		echo ob_get_clean();
	}
}


/**
 * Get packages features
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_pakages_features')) {

    function workreap_get_pakages_features() {
		if (function_exists('fw_get_db_settings_option')) {
			$remove_chat = fw_get_db_settings_option('remove_chat', $default_value = null);
		}
       $packages = array(
				'wt_connects' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of credits','workreap'),
		   			'remaining' 	=> esc_html__('Remaining credits','workreap'),
					'classes' 		=> 'wt_freelancer wt-all-field',
					'hint' 			=> '',
					'user_type'		=> 'freelancer',
		   			'feature_type'	=> 'job',
					'text'			=> '',
					'options'		=> array()

				),
		   		'wt_services' => array(
					'type'			=> 'number',
		   			'title' 		=> esc_html__('No of Services','workreap'),
		   			'remaining' 	=> esc_html__('Total Services','workreap'),
					'classes' 		=> 'wt_freelancer wt-all-field',
					'hint' 			=> '',
		   			'feature_type'	=> 'service',
					'user_type'		=> 'freelancer',
					'text'			=> '',
					'options'		=> array()
				),
		   		'wt_featured_services' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of Featured Services','workreap'),
		   			'remaining' 	=> esc_html__('Remaining Featured Services','workreap'),
					'classes' 		=> 'wt_freelancer wt-all-field',
					'hint' 			=> '',
		   			'feature_type'	=> 'service',
					'user_type'		=> 'freelancer',
					'text'			=> '',
					'options'		=> array()
				),

				'wt_jobs' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of job(s)','workreap'),
		   			'remaining' 	=> esc_html__('Remaining job(s)','workreap'),
					'classes' 		=> 'wt_employer  wt-all-field',
					'hint' 			=> '',
					'user_type'		=> 'employer',
		   			'feature_type'	=> 'job',
					'text'			=> '',
					'options'		=> array()
				),
				'wt_featured_jobs' => array(
						'type'			=> 'number',
						'title' 		=> esc_html__('No of featured job','workreap'),
		   				'remaining' 	=> esc_html__('Remaining featured job(s)','workreap'),
						'classes' 		=> 'wt_employer  wt-all-field',
						'hint' 			=> '',
		   				'text'			=> '',
						'user_type'		=> 'employer',
		   				'feature_type'	=> 'job',
		   				'options'		=> array()
					),
				'wt_badget' => array(
						'type'			=> 'select',
						'title' 		=> esc_html__('Badge','workreap'),
		   				'remaining' 	=> esc_html__('Badge','workreap'),
						'classes' 		=> 'wt_freelancer wt-all-field',
						'hint' 			=> '',
						'user_type'		=> 'freelancer',
		   				'feature_type'	=> 'both',
		   				'text'			=> '',
		   				'options'		=> workreap_get_pakages_badges()
					),
				'wt_banner' => array(
						'type'			=> 'select',
						'title' 		=> esc_html__('Banner Options','workreap'),
		   				'remaining' 	=> esc_html__('Banner Options','workreap'),
						'classes' 		=> 'wt-common-field wt-all-field',
						'hint' 			=> '',
						'user_type'		=> 'common',
		   				'feature_type'	=> 'both',
		   				'text'			=> '',
		   				'options'		=> workreap_get_option_values()
					),
		   		'wt_duration_type' => array(
						'type'			=> 'select',
						'title' 		=> esc_html__('Duration','workreap'),
		   				'remaining' 	=> esc_html__('Duration','workreap'),
						'classes' 		=> 'wt-common-field wt-all-field',
						'hint' 			=> '',
						'user_type'		=> 'common',
		   				'feature_type'	=> 'both',
		   				'text'			=> esc_html__('Days','workreap'),
		   				'options'		=> workreap_get_duration_types()
					),
				'wt_no_skills' => array(
						'type'			=> 'number',
						'title' 		=> esc_html__('No of skills','workreap'),
		   				'remaining' 	=> esc_html__('Allowed skill(s)','workreap'),
						'classes' 		=> 'wt_freelancer wt-all-field',
						'hint' 			=> '',
						'user_type'		=> 'freelancer',
		   				'feature_type'	=> 'both',
		   				'text'			=> '',
		   				'options'		=> array()
					),
				'wt_pr_chat' => array(
						'type'			=> 'select',
						'title' 		=> esc_html__('Private Quick Chat','workreap'),
		   				'remaining' 	=> esc_html__('Private Quick Chat','workreap'),
						'classes' 		=> 'wt-common-field wt-all-field',
						'hint' 			=> '',
						'user_type'		=> 'common',
		   				'feature_type'	=> 'both',
		   				'text'			=> '',
		   				'options'		=> workreap_get_option_values()
					)
			);
		
		$access_type	= workreap_return_system_access();
		
		if( $access_type != 'both' && $access_type === 'job' ) {
			unset($packages['wt_services']);
			unset($packages['wt_featured_services']);
		} else if( $access_type === 'service' ) {
			unset($packages['wt_jobs']);
			unset($packages['wt_connects']);
			unset($packages['wt_featured_jobs']);
		}
		
		if( !empty( $remove_chat ) && $remove_chat === 'yes' ){
			unset($packages['wt_pr_chat']);
		}
		
		$packages		= apply_filters('workreap_filter_packages_features',$packages);
		
		return $packages;
    }

    add_action('workreap_get_pakages_features', 'workreap_get_pakages_features');
}

/**
 * Project cover letter
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'worrketic_proposal_cover' ) ) {
	add_action( 'worrketic_proposal_cover', 'worrketic_proposal_cover',10,1 );
	function worrketic_proposal_cover( $job_id ='' ) {
		$proposal_id = !empty( $job_id ) ? intval( $job_id ) : '';
		?>
		<div class="wt-hireduserstatus">
			<a href="#" onclick="event_preventDefault(event);" class="covert_letter" data-id="<?php echo intval($proposal_id);?>">
				<i class="fa fa-envelope"></i>
				<span><?php esc_html_e('Cover Letter','workreap');?></span>
			</a>
		</div>
		<?php		
	}
}

/**
 * Project attahments
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'worrketic_proposal_attachments' ) ) {
	add_action( 'worrketic_proposal_attachments', 'worrketic_proposal_attachments',10,1 );
	function worrketic_proposal_attachments($job_id) {
		if( empty($job_id) ){return;}
		
		if (function_exists('fw_get_db_post_option')) {
			$proposal_docs = fw_get_db_post_option($job_id, 'proposal_docs');
		}
		
		$proposal_docs = !empty( $proposal_docs ) ?  count( $proposal_docs ) : 0;
		?>
		<div class="wt-hireduserstatus">
			<a href="#" onclick="event_preventDefault(event);" class="download-project-attachments" data-post-id="<?php echo intval($job_id);?>">
				<i class="fa fa-paperclip"></i>
				<span><?php echo intval( $proposal_docs );?>&nbsp;<?php esc_html_e('file attached','workreap');?></span>
			</a>
		</div>	
		<?php		
	}
}

/**
 * Project duration and amount
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'worrketic_proposal_duration_and_amount' ) ) {
	add_action( 'worrketic_proposal_duration_and_amount', 'worrketic_proposal_duration_and_amount',10,1 );
	function worrketic_proposal_duration_and_amount($job_id) {
		$project_id  		= get_post_meta($job_id, '_project_id', true);
		$project_type    	= fw_get_db_post_option($project_id,'project_type');
		$proposed_amount  	= get_post_meta($job_id, '_amount', true);
		$total_amount		= '';
		
		?>
		<div class="wt-hireduserstatus">
			<h5><?php do_action('workreap_price_format',$proposed_amount);?></h5>
			<?php if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'fixed' ) { 
				$proposed_duration  = get_post_meta($job_id, '_proposed_duration', true);
				$duration_list		= worktic_job_duration_list();
				$duration			= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
				if( !empty( $duration ) ) {?>
				<span><?php echo esc_html( $duration );?></span>
			<?php }}  ?>
			<?php if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'hourly' ) { 
				$estimeted_time		= get_post_meta($job_id,'_estimeted_time',true);
				$per_hour_amount	= get_post_meta($job_id,'_per_hour_amount',true);
				$estimeted_time		= !empty( $estimeted_time ) ? $estimeted_time : 0;
				$per_hour_amount	= !empty( $per_hour_amount ) ? $per_hour_amount : 0;
				$total_amount		= apply_filters('workreap_price_format',$per_hour_amount,'return');
			?>
			<?php if( !empty( $estimeted_time ) ){?>
				<span><?php esc_html_e('Estimated hours','workreap');?>&nbsp;(<?php echo esc_html( $estimeted_time );?>)</span>
			<?php } ?>
			<?php if( !empty( $per_hour_amount ) ){?>
				<span><?php esc_html_e('Amount per hour','workreap');?>&nbsp;(<?php echo esc_html( $total_amount );?>)</span>
			<?php } ?>
		<?php }  ?>
		</div>
		<?php
	}
}

/**
 * Proposal statuses
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_job_statuses' ) ) {
    function worktic_job_statuses(){
        $list = array(
			'hired' 		=> esc_html__('Hired','workreap'),
			'completed' 	=> esc_html__('Completed','workreap'),
			'cancelled' 	=> esc_html__('Cancelled','workreap'),
        );

        $list = apply_filters('worktic_filters_job_statuses', $list);         
        return $list;
    }
    add_filter('worktic_job_statuses', 'worktic_job_statuses', 10, 1);
}

/**
 * Proposal statuses
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_service_statuses' ) ) {
    function worktic_service_statuses(){
        $list = array(
			'deleted' 	=> esc_html__('Deleted','workreap'),
        );

        $list = apply_filters('worktic_filters_service_statuses', $list);         
        return $list;
    }
    add_filter('worktic_service_statuses', 'worktic_service_statuses', 10, 1);
}

/**
 * Register statuses
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_register_job_statuses' ) ) {
	function worktic_register_job_statuses(){
		$statuses	= apply_filters('worktic_job_statuses','default');
		$services	= apply_filters('worktic_service_statuses','default');
		$statuses	= array_merge($statuses,$services);
		foreach( $statuses as $key => $val ){
			
			if( $key ==='hired' || $key ==='completed' ) {
				$public	= true;
			} else {
				$public	= false;
			}
			
			register_post_status($key, array(
				'label'                     => $val,
				'public'                    => $public,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
			) );
		}

	}
	add_action( 'init', 'worktic_register_job_statuses' );
}

/**
 * list jobs proposals
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_proposals_received_html' ) ) {
	add_filter('workreap_proposals_received_html', 'workreap_proposals_received_html', 10, 1);
	function workreap_proposals_received_html($post_id='') {
		global $current_user;
		ob_start();
		$proposals  = workreap_get_totoal_proposals($post_id,'array','-1');
		$count		= !empty( $proposals ) ? count($proposals) : 0;
		?>
		<div class="wt-hireduserstatus">
			<h4><?php echo intval( $count );?></h4><span><?php esc_html_e('Proposals','workreap');?></span>
			<ul class="wt-hireduserimgs">
				<?php 
				if( !empty( $proposals ) ){
					foreach( $proposals as $key=> $proposal ){
						$author_id	= $proposal->post_author;
						$author_id 	= workreap_get_linked_profile_id( $author_id );
						$freelancer_avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $author_id ), array( 'width' => 225, 'height' => 225 )
									);
						?>
						<li>
							<figure>
								<img src="<?php echo esc_url( $freelancer_avatar );?>" alt="<?php esc_attr_e('freelancer','workreap');?>">
							</figure>
						</li>
				<?php }}?>
			</ul>
		</div>
		<?php
		echo ob_get_clean();
	}
}

/**
 * get user type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_user_type')) {

    function workreap_get_user_type($user_identity) {
        if (!empty($user_identity)) {
            $data = get_userdata($user_identity);
            if (!empty($data->roles[0]) && $data->roles[0] === 'freelancers') {
                return 'freelancer';
            } else if (!empty($data->roles[0]) && $data->roles[0] === 'employers') {
                return 'employer';
            } else if (!empty($data->roles[0]) && $data->roles[0] === 'administrator') {
                return 'administrator';
            } else if (!empty($data->roles[0]) && $data->roles[0] === 'subscriber') {
                return 'subscriber';
            } else{
                return false;
            }
        }

        return false;
    }

    add_filter('workreap_get_user_type', 'workreap_get_user_type', 10, 1);
}

/**
 * user freelancer featured profile tag
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_featured_freelancer_tag' ) ) {
	function workreap_featured_freelancer_tag($user_id='',$print_class='no'){
		$linked_profile	= workreap_get_linked_profile_id($user_id);
		$featured_id	= workreap_is_feature_value( 'wt_badget',$user_id );
		
		$featured_id	= !empty($featured_id) ? intval($featured_id) : '';
		$featured_timestamp		= get_post_meta( $linked_profile,'_featured_timestamp',true );
		if( !empty( $featured_timestamp ) ) {
			if( $print_class === 'yes' ){return 'wt-featured';} 
			if( empty( $featured_id ) ){ return 'wt-featured';}
			
			$term	= get_term( $featured_id );
			if( !empty($term) ) {
				ob_start();
				$badge_icon  = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_icon');
				$badge_color = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_color');

				if( !empty( $badge_icon['url'] ) ){
					$color = !empty( $badge_color ) ? $badge_color : '#ff5851';
				?>
				<span class="wt-featuredtag" style="border-top:40px solid <?php echo esc_attr($color);?>">
					<img src="<?php echo esc_url($badge_icon['url']);?>" alt="<?php echo esc_attr($term->name);?>" data-tipso="<?php echo esc_attr($term->name);?>" class="template-content tipso_style wt-tipso">
				</span>
				<?php }

				echo ob_get_clean();
			}
		}
	}
	
	add_action( 'workreap_featured_freelancer_tag', 'workreap_featured_freelancer_tag',10,2 );
	add_filter( 'workreap_featured_freelancer_tag', 'workreap_featured_freelancer_tag',10,2 );
}


/**
 * Freelancer shorting by title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_freelancer_sort_title' ) ) {
	function workreap_freelancer_sort_title($orderby){
			ob_start();
			if(empty($orderby) || $orderby === 'DESC'){ ?>
				<li>
					<span class="wt-tag-radiobox">
					<input id="wt-order-freelancer" type="radio" name="sortby" value="ASC">
						<label for="wt-order-freelancer"><!--<i class="fa fa-sort-alpha-up"></i>-->&nbsp;<?php esc_html_e('Sort By A-Z','workreap');?></label>
					</span>
				</li>
			<?php } else if( $orderby === 'ASC'){ ?>
				<li>
					<span class="wt-tag-radiobox">
					<input id="wt-order-freelancer" type="radio" name="sortby" value="DESC">
						<label for="wt-order-freelancer"><!--<i class="fa fa-sort-alpha-up"></i>-->&nbsp;<?php esc_html_e('Sort By Z-A','workreap');?></label>
					</span>
				</li>
			<?php }
			echo ob_get_clean();
	}
	
	add_action( 'workreap_freelancer_sort_title', 'workreap_freelancer_sort_title',10,1 );
}

/**
 * Freelancer shorting by title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_freelancer_sort_rating' ) ) {
	function workreap_freelancer_sort_rating($orderby){
			ob_start();
			if(empty($orderby) || $orderby === 'DESC'){ ?>
				<li>
					<span class="wt-tag-radiobox">
					<input id="wt-order-rating" type="radio" name="rating_order" value="ASC">
						<label for="wt-order-rating"><i class="fa fa-sort-up"></i>&nbsp;<?php esc_html_e('Sort By Rating DESC','workreap');?></label>
					</span>
				</li>
			<?php } else if( $orderby === 'ASC'){ ?>
				<li>
					<span class="wt-tag-radiobox">
					<input id="wt-order-rating" type="radio" name="rating_order" value="DESC">
						<label for="wt-order-rating"><i class="fa fa-sort-down"></i>&nbsp;<?php esc_html_e('Sort By Rating ASC','workreap');?></label>
					</span>
				</li>
			<?php }
			echo ob_get_clean();
	}
	
	add_action( 'workreap_freelancer_sort_rating', 'workreap_freelancer_sort_rating',10,1 );
}

/**
 * Freelancer breadcrumbs( location, per hour rate and saved )
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_freelancer_breadcrumbs' ) ) {
	function workreap_freelancer_breadcrumbs($user_id='',$classes=''){
		$rat_settings	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$rat_settings	= fw_get_db_settings_option('freelancer_price_option', $default_value = null);
		}
		
		$perhour_rate	= '';
		if( function_exists('fw_get_db_post_option') ){
			$perhour_rate	= fw_get_db_post_option($user_id, '_perhour_rate', true);
		}
		
		$perhour_rate				= function_exists('workreap_price_format') && !empty($perhour_rate) ? workreap_price_format($perhour_rate,'return') : '';
		
		if(!empty($rat_settings) && $rat_settings === 'enable' ){
			$max_price	= '';
			if( function_exists('fw_get_db_post_option') ){
				$max_price	= fw_get_db_post_option($user_id, 'max_price', true);
			}
			
			$max_price			= function_exists('workreap_price_format') && !empty($max_price) ? '- '.workreap_price_format($max_price,'return') : '';
			$freelancer_rate	= !empty($perhour_rate) ? $perhour_rate.' '.$max_price : '';
		} else {
			$freelancer_rate	= $perhour_rate;
		}

		ob_start();
		?>
		<ul class="wt-userlisting-breadcrumb <?php echo esc_attr( $classes );?>">
			<?php if( !empty($perhour_rate) && apply_filters('workreap_user_perhour_rate_settings',$user_id) === true ){?>
				<li><span><i class="fa fa-money"></i><?php echo esc_html($freelancer_rate);?>&nbsp;/&nbsp;<?php esc_html_e('hr','workreap');?></span></li>
			<?php }?>
			<?php do_action('workreap_print_location',$user_id);?>
			<li><?php do_action('workreap_save_freelancer_html', $user_id);?></li>
		</ul>
		<?php
		echo ob_get_clean();
	}
	
	add_action( 'workreap_freelancer_breadcrumbs', 'workreap_freelancer_breadcrumbs',10,2 );
}

/**
 * price format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_price_format' ) ) {
	function workreap_price_format($price='', $type = 'echo'){
		$price  = !empty($price) ? str_replace( ',', '', $price) : $price;
		if (class_exists('WooCommerce')) {
			if(function_exists('wmc_get_price')){
				$price = html_entity_decode(wc_price( wmc_get_price( $price ) )); //WooCommerce Multi Currency Compatibility
			}else{
				$price = wc_price($price);
			}
			
		} else{
			$currency	= workreap_get_current_currency();
			$price = !empty($currency['symbol'] ) ? $currency['symbol'].$price : '$';
		}
		
		if( $type === 'return' ) {
			return wp_strip_all_tags( $price );
		} else {
			echo wp_strip_all_tags( $price );
		}
		
	}
	
	add_action( 'workreap_price_format', 'workreap_price_format',10,2 );
	add_filter( 'workreap_price_format', 'workreap_price_format',10,2 );
}

/**
 * Multi currency price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_price_without_symbol' ) ) {
	function workreap_price_without_symbol($price='', $type = 'echo'){
		if (class_exists('WooCommerce')) {
			if(function_exists('wmc_get_price')){
				$price = wc_price( wmc_get_price( $price )); //WooCommerce Multi Currency Compatibility
			}else{
				$price = wc_price($price);
			}
			
		} else{
			$price 		= $price;
		}

		if( $type === 'return' ) {
			return wp_strip_all_tags( $price );
		} else {
			echo wp_strip_all_tags( $price );
		}
		
	}
	
	add_action( 'workreap_price_without_symbol', 'workreap_price_without_symbol',10,2 );
	add_filter( 'workreap_price_without_symbol', 'workreap_price_without_symbol',10,2 );
}


/**
 * save freelancer HTML
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_save_freelancer_html' ) ) {

	function workreap_save_freelancer_html( $id='', $type ='v1' ) {
		global $current_user;
		ob_start();
		if( is_user_logged_in() ) {
			$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);
			$saved_freelancers	= get_post_meta($linked_profile, '_saved_freelancers', true);	
			$saved_freelancers	= !empty( $saved_freelancers ) ?  $saved_freelancers : array();
		} else {
			$saved_freelancers	= array();
		}
			
		if ($type == 'v1') {
			if ( in_array($id, $saved_freelancers) ) {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-clicksave"><i class="fa fa-heart"></i>&nbsp;<span><?php esc_html_e('Saved','workreap');?></span></a>
			<?php } else {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-clicksave wt-savefreelancer" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Saved', 'workreap'); ?>"><i class="fa fa-heart" ></i><span><?php esc_html_e('Save', 'workreap'); ?></span></a>
			<?php
			}
		} else if ($type == 'v2') {
			if ( in_array($id, $saved_freelancers) ) {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-liked"><i class="ti-heart"></i>&nbsp;<?php esc_html_e('Saved','workreap');?></a>
			<?php } else {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-savefreelancer" data-type="v2" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Saved', 'workreap'); ?>"><i class="ti-heart"></i><span><?php esc_html_e('Save', 'workreap'); ?></span></a>
			<?php
			}
		} else if ($type == 'v3') {
			if ( in_array($id, $saved_freelancers) ) {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-likeproject wt-likedpro"><i class="ti-heart"></i></a>
			<?php } else { ?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-savefreelancer wt-likeproject" data-type="v2" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Saved', 'workreap'); ?>"><i class="ti-heart"></i></a>
			<?php
			}
		}
		echo ob_get_clean();
	}

	add_action( 'workreap_save_freelancer_html', 'workreap_save_freelancer_html', 10, 2 );
}

/**
 * get location
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_print_location' ) ) {
    function workreap_print_location($user_id = ''){
		ob_start();
        if( !empty( $user_id ) ){ 
            $args = array();
			if( taxonomy_exists('locations') ) {
				$terms = wp_get_post_terms( $user_id, 'locations', $args );
				if( !empty( $terms ) ){
					foreach ( $terms as $key => $term ) {    
						$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
						?>
						<li>
							<span>
								<?php if( !empty( $country['url'] ) ){ ?>
									<em><img class="wt-checkflag" src="<?php echo esc_url( $country['url'] ); ?>" alt="<?php echo esc_url( $country['url'] ); ?>"></em><?php echo esc_html( $term->name );?>
								<?php }else{?>
									<i class="fa fa-flag"></i><?php echo esc_html( $term->name );?>
								<?php }?>
							</span>
						</li>
						<?php 
					}
				}
			}
        }
		
		echo ob_get_clean();
    }
    
    add_action( 'workreap_print_location', 'workreap_print_location',10,4 );
}

/**
 * Report form for employer, project or freelancer 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_report_post_type_form' ) ) {

	function workreap_report_post_type_form( $post_id='',$type='freelancer' ) {
		global $current_user;
		ob_start();

		if( $type === 'employer' ){
			$title	= esc_html__('Report this employer', 'workreap'); 
		} else if( $type === 'project' ){
			$title	= esc_html__('Report this project', 'workreap'); 
		} elseif( $type === 'freelancer' ){
			$title	= esc_html__('Report this freelancer', 'workreap'); 
		}elseif( $type === 'service' ){
			$title	= esc_html__('Report this service', 'workreap'); 
		}
		
		$reasons	 = workreap_get_report_reasons();
		$placeholder = esc_html__('Select reason', 'workreap'); 
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$remove_settings 	= fw_get_db_settings_option( 'report_'.$type, $default_value = null );
			$remove_report		= !empty( $remove_settings['gadget'] ) ? $remove_settings['gadget'] : 'no';
			$reasons			= !empty( $remove_settings['no']['report_options'] ) ? $remove_settings['no']['report_options'] : 'no';
			
			if( !empty( $reasons ) and is_array( $reasons ) ){
				$reasons = array_filter($reasons);
				$reasons = array_combine(array_map('sanitize_title', $reasons), $reasons);
			} else{
				$reasons	= workreap_get_report_reasons();
			}
		} 
		
		if( empty( $remove_report ) || $remove_report === 'yes' ){ return '';}

		?>
		<div class="wt-widget wt-reportjob" id="wt-reportuser">
			<div class="wt-widgettitle">
				<h2><?php echo esc_html( $title );?></h2>
			</div>
			<div class="wt-widgetcontent">
				<form class="wt-formtheme wt-formreport">
					<fieldset>
						<?php if( !empty( $reasons ) ){?>
						<div class="form-group">
							<span class="wt-select">
								<select name="reason">
									<option value=""><?php echo esc_html( $placeholder );?></option>
									<?php foreach( $reasons as $key => $val ){?>
										<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
									<?php }?>
								</select>
							</span>
						</div>
						<?php }?>
						<div class="form-group">
							<textarea class="form-control" name="description" placeholder="<?php esc_attr_e('Report description', 'workreap'); ?>"></textarea>
						</div>
						<div class="form-group wt-btnarea">
							<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-report-user" data-id='<?php echo esc_attr( $post_id );?>' data-type='<?php echo esc_attr( $type );?>'><?php esc_html_e('Report now', 'workreap'); ?></a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}

	add_action( 'workreap_report_post_type_form', 'workreap_report_post_type_form',10,2 );
}

/**
 * user verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_get_verification_check' ) ) {
	
	function workreap_get_verification_check($user_id='',$text=''){
		
		if( !empty($user_id) ) {
			if ( function_exists('fw_get_db_post_option' )) {
				$identity_verification    	= fw_get_db_settings_option('identity_verification');
				$email_verify_icon    		= fw_get_db_settings_option('email_verify_icon');
				$identity_verify_icon    	= fw_get_db_settings_option('identity_verify_icon');
			}
			
			$user_linked_id	= workreap_get_linked_profile_id( $user_id,'post' );
			$user_type		= apply_filters('workreap_get_user_type', $user_linked_id );
			if( !empty($user_type) && $user_type === 'employer' ){
				if ( function_exists('fw_get_db_post_option' )) {
					$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
				}
			}
			
			$is_verified 		= get_post_meta($user_id, '_is_verified', true);
			$identity_verified 	= get_post_meta($user_id, 'identity_verified', true);
			$title			= $text;
			
			if( function_exists('workreap_get_username') ){
				$title	= workreap_get_username('',$user_id);
			}
			
			$email_verify_icon = !empty($email_verify_icon['url']) ? $email_verify_icon['url'] : get_template_directory_uri().'/images/email_verified_color.svg';
			$identity_verify_icon = !empty($identity_verify_icon['url']) ? $identity_verify_icon['url'] : get_template_directory_uri().'/images/identity_verified_color.svg';
			
			ob_start();
			if( !empty( $is_verified ) && $is_verified === 'yes' ){?>
				<img class="tipso_style wt-tipso verificationtags-img" data-tipso="<?php esc_attr_e('Email Verified', 'workreap'); ?>" alt="<?php esc_attr_e('Email Verified', 'workreap'); ?>" src="<?php echo esc_url($email_verify_icon);?>">
			<?php }?>
			<?php if(!empty($identity_verification) && $identity_verification === 'yes'){?>
			<?php if(!empty($identity_verified) ){?><img class="tipso_style wt-tipso verificationtags-img" data-tipso="<?php esc_attr_e('Identity Verified', 'workreap'); ?>" alt="<?php esc_attr_e('Identity Verified', 'workreap'); ?>" src="<?php echo esc_url($identity_verify_icon);?>"><?php }}?>
			<a href="<?php echo esc_url( get_the_permalink($user_id) ); ?>" class="verification-tags"><?php echo esc_html($title);?></a>
			<?php
			echo ob_get_clean();
		}
	}
	add_action( 'workreap_get_verification_check', 'workreap_get_verification_check',10,2 );
}

/**
 * follow employer HTML
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_follow_employer_html' ) ) {

	function workreap_follow_employer_html( $type='v1', $id='' ) {
		global $current_user;
		ob_start();
		$follower = '';
		$linked_profile   		= workreap_get_linked_profile_id($current_user->ID);
		$user_followings 		= get_post_meta($linked_profile, '_following_employers', true);
		$user_followings 		= !empty( $user_followings ) ?  $user_followings : array();
		
		if ( $type === 'v1' ) {
			if ( in_array($id,$user_followings) ) {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-clicksavebtn wt-clicksave"><i class ="fa fa-heart"></i>&nbsp;<?php esc_html_e('Following', 'workreap'); ?></a>
			<?php } else {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-clicksavebtn wt-follow-emp" data-type="v1" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Following', 'workreap'); ?>"><i class="fa fa-heart" ></i>&nbsp;<span><?php esc_html_e('Click to follow', 'workreap'); ?></span></a>
			<?php
			}
		} else {
			if ( in_array($id,$user_followings) ) {?>
				<a href="#" onclick="event_preventDefault(event);"><span><?php esc_html_e('Following', 'workreap'); ?></span></a>
			<?php } else {?>
				<a href="#" onclick="event_preventDefault(event);"  data-type="v2" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Following', 'workreap'); ?>" class="wt-follow-emp"><span><?php esc_html_e('Follow', 'workreap'); ?></span></a>
			<?php
			}
		}

		echo ob_get_clean();
	}

	add_action( 'workreap_follow_employer_html', 'workreap_follow_employer_html', 10, 2 );
}

/**
 * Employer followers
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_employer_followers' ) ) {

	function workreap_employer_followers( $post_id='' ) {
		global $current_user;
		ob_start();
		$emp_followers 		= get_post_meta($post_id, '_followers', true);
		$emp_followers		= !empty( $emp_followers ) ? $emp_followers : array('');
		if( !empty( $emp_followers ) ){
			$args	= array( 'post_type' 		=> array('freelancers','employers'),
							 'posts_per_page'      	=> -1,
						   	 'post_status' 			=> 'publish',
            				 'suppress_filters' 	=> false,
							 'ignore_sticky_posts' 	=> 1,
							 'post__in'				=> $emp_followers
						   );
			$emp_followers = get_posts($args);
			
			if( !empty( $emp_followers ) ){?>
			<div class="wt-widget">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Company Followers', 'workreap'); ?></h2>
				</div>
				<div class="wt-widgetcontent wt-comfollowers wt-verticalscrollbar">
					<ul>
						<?php 
							foreach( $emp_followers as $key => $follower  ){
								$user_type 	= get_post_meta($follower->ID, '_user_type', true);
								if( !empty( $user_type ) && $user_type === 'freelancer' ){
									$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $follower->ID), array('width' => 100, 'height' => 100) 
									);
								} else if( !empty( $user_type ) && $user_type === 'employer' ){
									$avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $follower->ID), array('width' => 100, 'height' => 100) 
									);
								} else{
									$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', '', array('width' => 100, 'height' => 100) 
									); 
								}
								?>
								<li>
									<a href="<?php echo esc_url(get_the_permalink($follower->ID));?>">
										<span><img src="<?php echo esc_attr( $avatar );?>" alt="<?php esc_attr_e('Follower', 'workreap'); ?>"></span>
										<span><?php echo esc_html(get_the_title($follower->ID));?></span>
									</a>
								</li>
								<?php
							}
						?>
					</ul>
				</div>
			</div>
			<?php 
			}
		}
		echo ob_get_clean();
	}

	add_action( 'workreap_employer_followers', 'workreap_employer_followers', 10, 2 );
}

/**
 * fallback image for freelancer banner
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_freelancer_banner_fallback' ) ) {

	function workreap_freelancer_banner_fallback( $object, $atts = array() ) {
		
		extract( shortcode_atts( array(
			"width" => '1920',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/frbanner-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'workreap_freelancer_banner_fallback', 'workreap_freelancer_banner_fallback', 10, 2 );
}

/**
 * fallback image for employer banner
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_employer_banner_fallback' ) ) {

	function workreap_employer_banner_fallback( $object, $atts = array() ) {
		extract( shortcode_atts( array(
			"width" => '1140',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/embanner-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'workreap_employer_banner_fallback', 'workreap_employer_banner_fallback', 10, 2 );
}

/**
 * fallback image for freelancer avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_freelancer_avatar_fallback' ) ) {

	function workreap_freelancer_avatar_fallback( $object, $atts = array() ) {
		extract( shortcode_atts( array(
			"width" => '1920',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/fravatar-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'workreap_freelancer_avatar_fallback', 'workreap_freelancer_avatar_fallback', 10, 2 );
}

/**
 * fallback image for employer avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_employer_avatar_fallback' ) ) {

	function workreap_employer_avatar_fallback( $object, $atts = array() ) {
		extract( shortcode_atts( array(
			"width" => '1140',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/emavatar-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'workreap_employer_avatar_fallback', 'workreap_employer_avatar_fallback', 10, 2 );
}

/**
 * get QR code
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_qr_code')) {
	add_action('workreap_get_qr_code', 'workreap_get_qr_code',10,2);
    function workreap_get_qr_code($type='user',$id='') {
		$remove_qrcode = 'no';
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$remove_qrcode 	= fw_get_db_settings_option( 'remove_qrcode', $default_value = null );
		} 
		
		if( !empty( $remove_qrcode ) && $remove_qrcode === 'no' ){?>
			<div class="wt-authorcodescan wt-widget wt-widgetcontent">
				<div class="wt-qrscan">
					<figure class="wt-qrcodeimg">
						<img class="wt-qrcodedata" src="<?php echo esc_url( get_template_directory_uri()); ?>/images/qrcode.png" alt="<?php esc_attr_e('QR-Code', 'workreap'); ?>">
						<figcaption>
							<a href="#" onclick="event_preventDefault(event);" class="wt-qrcodedetails" data-type="<?php echo esc_attr( $type ); ?>" data-key="<?php echo esc_attr( $id ); ?>">
								<span><i class="lnr lnr-redo"></i><?php esc_html_e('load', 'workreap'); ?><br><?php esc_html_e('QR code', 'workreap'); ?></span>
							</a>
						</figcaption>
					</figure>
				</div>
				<div class="wt-qrcodedetail">
					<span class="lnr lnr-laptop-phone"></span>
					<div class="wt-qrcodefeat">
						<h3><?php esc_html_e('Scan with your', 'workreap'); ?> <span><?php echo esc_html_e('Smart Phone', 'workreap'); ?> </span> <?php esc_html_e('To Get It Handy.', 'workreap'); ?></h3>
					</div>	
				</div>	
			</div>
			<?php
		}
	}
}

/**
 * Display Templates name with Page Name in
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_display_post_status' ) ) {
    add_filter( 'display_post_states', 'workreap_display_post_status', 10, 2 );

    /**
     * Add a post display state for special WC pages in the page list table.
     *
     * @param array   $post_states An array of post display states.
     * @param WP_Post $post        The current post object.
     */
    function workreap_display_post_status( $post_states, $post ) {

        $temp_name  = get_post_meta( $post->ID, '_wp_page_template', true );
        if( !empty( $temp_name ) && $temp_name === 'directory/employer-search.php' ){
            $post_states['workreap_employer_search']   = esc_html__('Search Employer Page', 'workreap');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/freelancer-search.php' ){
            $post_states['workreap_freelancer_search'] = esc_html__('Search Freelancer Page', 'workreap');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/project-search.php' ){
            $post_states['workreap_project_search']  = esc_html__('Search Projects Page', 'workreap');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/services-search.php' ){
            $post_states['workreap_project_search']  = esc_html__('Search Services Page', 'workreap');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/dashboard.php' ){
            $post_states['workreap_dashboard']  = esc_html__('Dashboard', 'workreap');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/project-proposal.php' ){
            $post_states['workreap_dashboard']  = esc_html__('Submit Proposal', 'workreap');
        }
        
        return $post_states;
    }
}

/**
 * Display proposals count
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_show_proposals_count' ) ) {
    add_action( 'workreap_show_proposals_count', 'workreap_show_proposals_count', 10, 1 );
    function workreap_show_proposals_count($post_id = '' ) {
		global $current_user;

        if( !empty( $post_id ) ){
			$proposals  = workreap_get_totoal_proposals($post_id,'array',-1);
			$count		= !empty( $proposals ) ? count($proposals) : 0;
			$expiry_date	= '';
			if (function_exists('fw_get_db_post_option')) {
				$expiry_date   		= fw_get_db_post_option($post_id, 'expiry_date', true);
			}
			$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
            ob_start(); ?>
            <div class="wt-proposalsrcontent proposal-display-wrap">
                <span class="wt-proposalsicon"><i class="fa fa-angle-double-down"></i><i class="fa fa-newspaper-o"></i></span>
                <div class="wt-title">
                    <h3><?php echo esc_html( $count ); ?>&nbsp;<?php esc_html_e('Proposals', 'workreap'); ?></h3>
                    <span><?php esc_html_e('Received till', 'workreap'); ?>&nbsp;<?php echo date_i18n(get_option('date_format'),strtotime($expiry_date)); ?></span>
                </div>
                <?php if( !empty( $proposals ) ){?>
					<ul class="wt-hireduserimgs">
						<?php 
							foreach( $proposals as $key=> $proposal ){
								$author_id	= $proposal->post_author;
								$author_id 	= workreap_get_linked_profile_id( $author_id );
								$username   	= workreap_get_username( '',$author_id );
								$freelancer_avatar = apply_filters(
												'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $author_id ), array( 'width' => 225, 'height' => 225 )
											);
								?>
								<li>
									<figure>
										<img src="<?php echo esc_url( $freelancer_avatar );?>" data-tipso="<?php echo esc_attr($username);?>" class="template-content tipso_style wt-tipso">
									</figure>
								</li>
						<?php }?>
					</ul>
           		<?php }?>
            </div>  
            <?php 
            echo ob_get_clean();
        }
    }
}

/**
 * Company box
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_project_company_box' ) ){
    function workreap_project_company_box( $employer_id = '' ){
        if( !empty( $employer_id ) ){
        $employer_avatar = apply_filters(
            'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_id), array('width' => 100, 'height' => 100) 
        );

        $employer_banner = apply_filters(
										'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 352, 'height' => 200), $employer_id), array('width' => 352, 'height' => 200) 
									);
        $company_title = esc_html( get_the_title( $employer_id )); 
        $company_link  = esc_url( get_the_permalink( $employer_id ));
		if (function_exists('fw_get_db_post_option')) {
			$tag_line      = fw_get_db_post_option($employer_id,'tag_line');
		}
			
        ob_start(); 
        ?>
        <div class="wt-widget">
            <div class="wt-companysdetails">
				<figure class="wt-companysimg">
					<img src="<?php echo esc_url($employer_banner); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>">
				</figure>
				<div class="wt-companysinfo">
					<figure><img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>"></figure>
					<div class="wt-title emp-title">
						<?php do_action('workreap_get_verification_check',$employer_id,esc_html__('Verified Employer','workreap'));?>
						<?php if( !empty( $tag_line ) ){?><h2><a href="<?php echo esc_url($company_link);?>"><?php echo esc_html(stripslashes($tag_line)); ?></a></h2><?php }?>
					</div>
					<ul class="wt-postarticlemeta">
						<li><a href="<?php echo esc_url($company_link);?>?#posted-projects"><span><?php esc_html_e('Open Jobs','workreap');?></span></a></li>
						<li><a href="<?php echo esc_url($company_link);?>"><span><?php esc_html_e('Full Profile','workreap');?></span></a></li>
						<li><?php do_action('workreap_follow_employer_html','v2',$employer_id);?></li>
					</ul>
				</div>
			</div>
        </div>
        <?php 
        echo ob_get_clean();
        }
    }
    add_action('workreap_project_company_box', 'workreap_project_company_box', 10, 1);
}

/**
 * Print categories html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_categories' ) ){
    function workreap_print_categories(){
		global $wp_query;
		if (is_tax('project_cat')) {
			$sub_cat = $wp_query->get_queried_object();
			if (!empty($sub_cat->slug)) {
				$categories_list = array($sub_cat->slug);
			}
		} else {
			$categories_list = !empty( $_GET['category']) ? $_GET['category'] : array();
		}

		$count = !empty($categories_list) && is_array($categories_list) ? count($categories_list) : 0;

		$categories = get_terms( 
			array(
				'taxonomy' 		=> 'project_cat',
				'hide_empty' 	=> false,
			) 
		);
		
		if( !empty( $categories ) ){
		ob_start(); 
        ?>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2><?php esc_html_e('Categories', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Category', 'workreap'); ?>">
                            <a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="wt-checkboxholder wt-filterscroll">    
                           <?php 
								wp_list_categories( array(
										'taxonomy' 			=> 'project_cat',
										'hide_empty' 		=> false,
										'current_category'	=> $categories_list,
										'style' 			=> '',
										'walker' 			=> new Workreap_Walker_Category,
									)
								);
                            ?>          
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_categories', 'workreap_print_categories', 10);
}

/**
 * Print categories html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_service_categories' ) ){
    function workreap_print_service_categories(){
		global $wp_query;
		if (function_exists('fw_get_db_post_option') ) {
			$services_categories	= fw_get_db_settings_option('services_categories');
		}

		$services_categories	= !empty($services_categories) ? $services_categories : 'no';
		if( !empty($services_categories) && $services_categories === 'no' ) {
			$taxonomy_type	= 'project_cat';
		}else{
			$taxonomy_type	= 'service_categories';
		}
		
		if (is_tax('project_cat') || is_tax('service_categories')) {
			$sub_cat = $wp_query->get_queried_object();
			if (!empty($sub_cat->slug)) {
				$categories_list = array($sub_cat->slug);
			}
		} else {
			$categories_list = !empty( $_GET['category']) ? $_GET['category'] : array();
		}

		$count = !empty($categories_list) && is_array($categories_list) ? count($categories_list) : 0;

		$categories = get_terms( 
			array(
				'taxonomy' 		=> $taxonomy_type,
				'hide_empty' 	=> false,
			) 
		);
		
		if( !empty( $categories ) ){
		ob_start(); 
        ?>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2><?php esc_html_e('Categories', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Category', 'workreap'); ?>">
                            <a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="wt-checkboxholder wt-filterscroll">    
                           <?php 
								wp_list_categories( array(
										'taxonomy' 			=> $taxonomy_type,
										'hide_empty' 		=> false,
										'current_category'	=> $categories_list,
										'style' 			=> '',
										'walker' 			=> new Workreap_Walker_Category,
									)
								);
                            ?>          
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_service_categories', 'workreap_print_service_categories', 10);
}

/**
 * Print locations html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_locations' ) ){
    function workreap_print_locations(){
		$location = !empty( $_GET['location']) ? $_GET['location'] : array();
		$count    = !empty($location) && is_array($location) ? count($location) : 0;
		$active_class		= !empty($count) ? 'wt-displayfilter' : '';

        ob_start(); 
        ?>
        <div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
            <div class="wt-widgettitle">
                <h2><?php esc_html_e('Location', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</span></h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Location', 'workreap'); ?>">
                            <a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="wt-checkboxholder wt-filterscroll">              
                            <?php 
								wp_list_categories( array(
										'taxonomy' 			=> 'locations',
										'hide_empty' 		=> false,
										'current_category'	=> $location,
										'style' 			=> '',
										'walker' 			=> new Workreap_Walker_Location,
									)
								);
                             ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();      
    }
    add_action('workreap_print_locations', 'workreap_print_locations', 10);
}

/**
 * Print skills html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_filter_skills' ) ){
    function workreap_filter_skills(){
		if( taxonomy_exists('skills') ) {
			$skill = !empty( $_GET['skills']) ? $_GET['skills'] : array();
			$count = !empty($skill) && is_array($skill) ? count($skill) : 0;
			$active_class		= !empty($count) ? 'wt-displayfilter' : '';
			$skills_typehead	= '';
			
			if (function_exists('fw_get_db_settings_option')) {
				$skills_typehead	= fw_get_db_settings_option('skills_typehead');
			}
			
			$lx_filters_array['skills']	= $skill;
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Skills', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">
						<fieldset>
							<div class="form-group">
								<input type="text" value="" class="form-control wt-filter-field wt-typeahead-skills-search" placeholder="<?php esc_attr_e('Type keyword to search skills', 'workreap'); ?>">
								<a href="#" onclick="event_preventDefault(event);"  class="wt-searchgbtn"><i class="fa fa-search"></i></a>
							</div>
						</fieldset>
						<?php if(empty( $skills_typehead ) || $skills_typehead === 'no' ) { ?>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll"> 
									<?php 
										wp_list_categories( array(
												'taxonomy' 			=> 'skills',
												'hide_empty' 		=> false,
												'current_category'	=> $skill,
												'style' 			=> '',
												'walker' 			=> new Workreap_Walker_Skills,
											)
										);
									?>    
								</div>
							</fieldset>
						<?php } ?>
					</div>
				</div>
				<?php if( !empty( $skills_typehead ) && $skills_typehead === 'yes' ) { ?>
					<div class="wt-selected-skills <?php echo empty($lx_filters_array['skills']) ? 'd-none' : ''  ?>">
						<ul class="wt-skills-selection">
							<?php 
								if( !empty($lx_filters_array['skills']) ){
									foreach($lx_filters_array as $taxonomy=>$terms){
										foreach($terms as $term){
											$taxonomy_term = get_term_by( 'slug', $term, $taxonomy );?>
											<li>
												<span><?php echo esc_html($taxonomy_term->name);?> <a href="javascript:void(0);" class="wt-term-remove-options" data-taxonomy="<?php echo esc_attr($taxonomy);?>" data-term_value="<?php echo esc_attr($term);?>"><em class="fa fa-close"></em></a></span>
												<input type="hidden" name="skills[]" value="<?php echo esc_attr($taxonomy_term->slug);?>" />
											</li>
									<?php }
									}
								}
							?>
						</ul>
						<script type="text/template" id="tmpl-load-skills">
							<# if( !_.isEmpty(data.skills) ) {
									let title 	= data.skills.title;
									let slug  	= data.skills.slug;
									let term_id = data.skills.term_id;
								#>
								<div class="wt-typeahead-skills">
									<a href="javascript:void(0);" class="wt-search-box" data-id="{{term_id}}" data-type="skills" data-slug="{{slug}}">{{title}}</a>
								</div>
							<# } #>
						</script>
					</div>
				<?php } ?>
			</div>
			<?php
			echo ob_get_clean();   
        }     
    }
    add_action('workreap_filter_skills', 'workreap_filter_skills');
}

/**
 * Print project time html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_time_html' ) ){
    function workreap_print_project_time_html(){
		if (function_exists('fw_get_db_settings_option')) {
			$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
		}
		
		if(!empty($remove_project_duration) && $remove_project_duration === 'no' ){ 
			$duration       = !empty( $_GET['duration'] ) ? $_GET['duration'] : array();
			$count 			= !empty($duration) && is_array($duration) ? count($duration) : 0;
			$duration_list  = worktic_job_duration_list();
			if( !empty( $duration_list ) ){               
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Project Length', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">             
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ( $duration_list as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="duration<?php echo esc_attr( $key ); ?>" type="checkbox" name="duration[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array($key, $duration) ); ?>>
											<label for="duration<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();   
			}
        }     
    }
    add_action('workreap_print_project_time_html', 'workreap_print_project_time_html', 10);
}

/**
 * Print languages html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_job_exprience' ) ){
    function workreap_job_exprience(){
		$job_experience_option	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$job_experience_option	= fw_get_db_settings_option('job_experience_option', $default_value = null);
		}
		$job_experience_option 	= !empty($job_experience_option['gadget']) ? $job_experience_option['gadget'] : '';
		if( taxonomy_exists('project_experience') ) {
			$experience = !empty( $_GET['experience']) ? $_GET['experience'] : array();
			$project_experience = get_terms( 
				array(
					'taxonomy' => 'project_experience',
					'hide_empty' => false,
				) 
			);

			if( !empty( $project_experience ) && !empty($job_experience_option) && $job_experience_option === 'enable' ){
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Experience', 'workreap'); ?></h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Experience', 'workreap'); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ($project_experience as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="experience<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="experience[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array( $value->slug, $experience ) ); ?>>
											<label for="experience<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_html( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();
			}
        }     
    }
    add_action('workreap_job_exprience', 'workreap_job_exprience', 10);
}

/**
 * Print languages html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_languages' ) ){
    function workreap_print_languages(){
		if( taxonomy_exists('languages') ) {
			$language = !empty( $_GET['language']) ? $_GET['language'] : array();
			$count = !empty($language) && is_array($language) ? count($language) : 0;

			$languages = get_terms( 
				array(
					'taxonomy' => 'languages',
					'hide_empty' => false,
				) 
			);
			$active_class		= !empty($count) ? 'wt-displayfilter' : '';
			if( !empty( $languages ) ){
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Languages', 'workreap'); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</span></h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Language', 'workreap'); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ($languages as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="language<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="language[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array( $value->slug, $language ) ); ?>>
											<label for="language<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_html( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();
			}
        }     
    }
    add_action('workreap_print_languages', 'workreap_print_languages', 10);
}

/**
 * Print industrial exprience html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_freelancer_industrial_exprience' ) ){
    function workreap_print_freelancer_industrial_exprience(){
		if( taxonomy_exists('wt-industrial-experience') ) {
			$industrial_experience = !empty( $_GET['industrial_experience']) ? $_GET['industrial_experience'] : array();
			$count  			   = !empty($industrial_experience) && is_array($industrial_experience) ? count($industrial_experience) : 0;
			$active_class		= !empty($count) ? 'wt-displayfilter' : '';
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Industrial experiences', 'workreap'); ?>:<span>( <em><?php echo intval($count);?></em> <?php esc_html_e('selected','workreap');?> )</span></h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">
						<fieldset>
							<div class="form-group">
								<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Industrial experiences', 'workreap'); ?>">
								<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
							</div>
						</fieldset>
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
							   <?php 
									wp_list_categories( array(
											'taxonomy' 			=> 'wt-industrial-experience',
											'hide_empty' 		=> false,
											'current_category'	=> $industrial_experience,
											'style' 			=> '',
											'walker' 			=> new Workreap_Walker_Experience,
										)
									);
								 ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();
		} 
    }
    add_action('workreap_print_freelancer_industrial_exprience', 'workreap_print_freelancer_industrial_exprience', 10);
}

/**
 * Get industrial exprience list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_industrial_exprience_list' ) ) {
	function worktic_get_industrial_exprience_list($name='industrial_exprience',$selected='',$depth=0){
		$name	= !empty($name) ? $name : 'industrial_exprience';
		if( taxonomy_exists('wt-industrial-experience') ) {
			wp_dropdown_categories( array(
									'taxonomy' 			=> 'wt-industrial-experience',
									'hide_empty' 		=> false,
									'hierarchical' 		=> 1,
									//'show_option_all' 	=> esc_html__('Select industrial experiences', 'workreap'),
									'walker' 			=> new Workreap_Walker_Experience_Dropdown,
									'class' 			=> 'chosen-select wt-experiences-title',
									'orderby' 			=> 'name',
									'name' 				=> $name,
									'id'                => 'experiences-dp',
									'selected' 			=> $selected,
									'depth'				=> $depth
									
								)
							);
		}
	}
	add_action('worktic_get_industrial_exprience_list', 'worktic_get_industrial_exprience_list', 10,3);
}

/**
 * Get industrial exprience list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_skills_list' ) ) {
	function worktic_get_skills_list($name='skills',$selected='',$depth=0,$id='skills-dp'){ 
		$name	= !empty($name) ? $name : 'skills';
		if( taxonomy_exists('skills') ) {
			wp_dropdown_categories( array(
									'taxonomy' 			=> 'skills',
									'hide_empty' 		=> false,
									'hierarchical' 		=> 1,
									//'show_option_all' 	=> esc_html__('Select skills', 'workreap'),
									'walker' 			=> new Workreap_Walker_Skills_Dropdown,
									'class' 			=> 'chosen-select wt-skill-title',
									'orderby' 			=> 'name',
									'name' 				=> $name,
									'id'                => $id,
									'selected' 			=> $selected,
									'depth'				=> $depth
									
								)
							);
		}
	}
	add_action('worktic_get_skills_list', 'worktic_get_skills_list', 10,4);
}

/**
 * Print specialization html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_freelancer_specialization' ) ){
    function workreap_print_freelancer_specialization(){
		if( taxonomy_exists('wt-specialization') ) {
			$specialization = !empty( $_GET['specialization']) ? $_GET['specialization'] : array();
			$count          = !empty($specialization) && is_array($specialization) ? count($specialization) : 0;
			$active_class		= !empty($count) ? 'wt-displayfilter' : '';
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Specialization', 'workreap'); ?>:<span>( <em><?php echo intval($count);?></em> <?php esc_html_e('selected','workreap');?> )</span></h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">
						<fieldset>
							<div class="form-group">
								<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search specialization', 'workreap'); ?>">
								<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
							</div>
						</fieldset>
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
							   <?php 
									wp_list_categories( array(
											'taxonomy' 			=> 'wt-specialization',
											'hide_empty' 		=> false,
											'current_category'	=> $specialization,
											'style' 			=> '',
											'walker' 			=> new Workreap_Walker_Specialization,
										)
									);
								 ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();
		} 
    }
    add_action('workreap_print_freelancer_specialization', 'workreap_print_freelancer_specialization', 10);
}

/**
 * Get specialization list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_get_specialization_list' ) ) {
	function worktic_get_specialization_list($name='specialization',$selected=''){
		if( taxonomy_exists('wt-specialization') ) {
			wp_dropdown_categories( array(
									'taxonomy' 			=> 'wt-specialization',
									'hide_empty' 		=> false,
									'hierarchical' 		=> 1,
									//'show_option_all' 	=> esc_html__('Select specialization', 'workreap'),
									'walker' 			=> new Workreap_Walker_Specialization_Dropdown,
									'class' 			=> 'chosen-select wt-specialization-title',
									'orderby' 			=> 'name',
									'name' 				=> $name,
									'id'                => 'specialization-dp',
									'selected' 			=> $selected
								)
							);
		}
	}
	add_action('worktic_get_specialization_list', 'worktic_get_specialization_list', 10,2);
}

/**
 * Print project skills html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_freelancer_type' ) ){
    function workreap_print_project_freelancer_type( $title = '' ){ 
		global $wp_query;
		$type 				= !empty( $_GET['type'] ) ? $_GET['type'] : array();
		
		//taxonomy page search
		if( is_tax( 'freelancer_type' ) ){
			$type = $wp_query->get_queried_object();
			if (!empty($type->slug)) {
				$type = array($type->slug);
			}
			
			$count  			= 1; 
		}else{
			$count  			= !empty($type) && is_array($type) ? count($type) : 0; 
		}

		$active_class		= !empty($count) ? 'wt-displayfilter' : '';
		$freelancer_level   = worktic_freelancer_level_list();
		if( !empty( $freelancer_level ) ){               
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<?php if( !empty( $title ) ){ ?>
					<div class="wt-widgettitle">
						<h2><?php echo esc_html( $title ); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</span></h2>
					</div>
				<?php } ?>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">             
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
								<?php foreach ( $freelancer_level as $key => $value ) { ?>
									<span class="wt-checkbox">
										<input id="freelancer<?php echo esc_attr( $key ); ?>" type="checkbox" name="type[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $type ) ); ?>>
										<label for="freelancer<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
									</span>
								<?php } ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_project_freelancer_type', 'workreap_print_project_freelancer_type', 10, 1);
}

/**
 * Print project option html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_option' ) ){
    function workreap_print_project_option( $title = '' ){ 
		
		$job_option	= array();
		if(function_exists('fw_get_db_settings_option')) {
			$job_option	= fw_get_db_settings_option('job_option', $default_value = null);
		}
		
		$job_option 		= !empty($job_option) ? $job_option : '';
		$option 			= !empty( $_GET['option'] ) ? $_GET['option'] : array();   
		$count  			= !empty($option) && is_array($option) ? count($option) : 0; 
		$job_options		= function_exists('workreap_get_job_option') ? workreap_get_job_option() : array();
		
		if( !empty( $job_options ) && !empty($job_option) && $job_option ==='enable' ){               
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder">
				<?php if( !empty( $title ) ){ ?>
					<div class="wt-widgettitle">
						<h2><?php echo esc_html( $title ); ?>:<span>( <em><?php echo esc_html($count); ?></em> <?php esc_html_e('selected', 'workreap'); ?> )</span></h2>
					</div>
				<?php } ?>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">             
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
								<?php foreach ( $job_options as $key => $value ) { ?>
									<span class="wt-checkbox">
										<input id="option<?php echo esc_attr( $key ); ?>" type="checkbox" name="option[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $option ) ); ?>>
										<label for="option<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
									</span>
								<?php } ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_project_option', 'workreap_print_project_option', 10, 1);
}

/**
 * Print reelancer english level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_freelancer_english_level' ) ){
    function workreap_print_freelancer_english_level(){
		$english_levels  = !empty( $_GET['english_level'] ) ? $_GET['english_level'] : array();
		$count           = !empty($english_levels) && is_array($english_levels) ? count($english_levels) : 0;
		$english_level   = worktic_english_level_list();
		
		$active_class		= !empty($count) ? 'wt-displayfilter' : '';
        if( !empty( $english_level ) ){               
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('English Level', 'workreap'); ?>:<span>( <em><?php echo intval($count);?></em> <?php esc_html_e('selected','workreap');?> )</span></h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">             
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
								<?php foreach ( $english_level as $key => $value) { ?>
									<span class="wt-checkbox">
										<input id="english<?php echo esc_attr( $key ); ?>" type="checkbox" name="english_level[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $english_levels ) ); ?>>
										<label for="english<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
									</span>
								<?php } ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_freelancer_english_level', 'workreap_print_freelancer_english_level', 10);
}

/**
 * Print hourly rate
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_hourly_rate' ) ){
    function workreap_print_hourly_rate(){
		$hourly_rate  = !empty( $_GET['hourly_rate'] ) ? $_GET['hourly_rate'] : '';
		$count 		  = !empty($hourly_rate) ? intval(1) : 0;
		$active_class		= !empty($count) ? 'wt-displayfilter' : '';
        $hourly_list  = apply_filters('worktic_hourly_rate_list', 'default');
        if( !empty( $hourly_list ) ){               
			ob_start(); 
			?>
			<div class="wt-widget wt-effectiveholder <?php echo esc_attr($active_class);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Hourly Rate', 'workreap'); ?></h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">             
						<fieldset>
							<div class="wt-checkboxholder wt-filterscroll">              
								<?php foreach ( $hourly_list as $key => $value) { ?>
									<span class="wt-radio">
										<input id="hour<?php echo esc_attr( $key ); ?>" <?php checked($hourly_rate, $key); ?> type="radio" name="hourly_rate" value="<?php echo esc_attr( $key ); ?>">
										<label for="hour<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
									</span>
								<?php } ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<?php
			echo ob_get_clean();   
        }     
    }
    add_action('workreap_print_hourly_rate', 'workreap_print_hourly_rate', 10);
}

/**
 * Get project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_get_project_type' ) ) {
    function workreap_get_project_type(){
        $project_type = !empty( $_GET['project_type'] ) ? sanitize_text_field( $_GET['project_type'] ) : '';
        $class = 'wt-none';
		
        if( !empty( $project_type ) && $project_type == 'hourly' ){
            $class = 'wt-display';
        }
		
        $hourly_rate = !empty( $_GET['hourlyrate'] ) ? sanitize_text_field( $_GET['hourlyrate'] ) : '';
        $range_array = !empty($hourly_rate) ? str_replace('$', '', explode('-', $hourly_rate)) : array();
        $min = !empty($range_array[0]) ? $range_array[0] : '';
        $max = !empty($range_array[1]) ? $range_array[1] : '';
        ob_start(); 
        ?>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2><?php esc_html_e('Project Type', 'workreap'); ?></h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="wt-checkboxholder">
                            <span class="wt-radio">
                                <input id="project" type="radio" class="wt-type"  name="project_type" value="projects" <?php checked( $project_type, 'projects' ); ?> checked>
                                <label for="project"> <?php esc_html_e('Any Project Type', 'workreap'); ?></label>
                            </span>
                            <span class="wt-radio">
                                <input id="hourly" type="radio" class="wt-type" name="project_type" value="hourly" <?php checked( $project_type, 'hourly' ); ?>>
                                <label for="hourly"> <?php esc_html_e('Hourly Based Project', 'workreap'); ?> </label>
                            </span>
                            <div id="wt-productrangeslider" class="wt-productrangeslider wt-themerangeslider <?php echo esc_attr($class); ?>"></div>
                            <div class="wt-amountbox <?php echo esc_attr($class); ?>">
                                <input type="text" id="wt-consultationfeeamount" name="hourlyrate" readonly data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>">
                            </div>
                            <span class="wt-radio">
                                <input id="fixed" type="radio" class="wt-type" name="project_type" value="fixed" <?php checked( $project_type, 'fixed' ); ?>>
                                <label for="fixed"> <?php esc_html_e('Fixed Price Project', 'workreap'); ?></label>
                            </span>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();   
    }
    add_action('workreap_get_project_type', 'workreap_get_project_type', 10);
}

/**
 * Print project Save icon
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_save_icon_project_html' ) ){
    function workreap_save_icon_project_html(){              
        ob_start(); 
        ?>
        <li>
			<a href="#" onclick="event_preventDefault(event);" class="wt-clicksave">
				<i class="fa fa-heart"></i>
				<span><?php esc_html_e('Saved','workreap');?></span>
			</a>
		</li>
        <?php
        echo ob_get_clean();       
    }
    add_action('workreap_save_icon_project_html', 'workreap_save_icon_project_html');
}

/**
 * Print project Save icon
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_trash_icon_project_html' ) ){
    function workreap_trash_icon_project_html( $post_id = '' ,$item_id = '' ,$itme_type = '' ){              
        ob_start(); 
        ?>
        <li>
			<a href="#" onclick="event_preventDefault(event);" data-post-id="<?php echo intval($post_id);?>" data-item-id="<?php echo intval($item_id);?>" data-itme-type="<?php echo esc_attr( $itme_type );?>" class="wt-clicksave wt-clickremove">
				<i class="lnr lnr-cross"></i>
				<?php esc_html_e('Remove','workreap');?>
			</a>
		</li>
        <?php
        echo ob_get_clean();       
    }
    add_action('workreap_trash_icon_project_html', 'workreap_trash_icon_project_html', 10 , 3);
}

/**
 * Print Freelancer reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_get_reviews' ) ){
    function workreap_freelancer_get_reviews( $post_id = '' ,$itme_type = 'v1' ){              
		ob_start(); 
		if( !empty ( $post_id ) ) {
			$reviews_data 	= get_post_meta( $post_id , 'review_data');
			$reviews_rate	= !empty( $reviews_data[0]['wt_average_rating'] ) ? floatval( $reviews_data[0]['wt_average_rating'] ) : 0 ;
			$total_rating	= !empty( $reviews_data[0]['wt_total_rating'] ) ? intval( $reviews_data[0]['wt_total_rating'] ) : 0 ;
		} else {
			$reviews_rate	= 0;
			$total_rating	= 0;
		}

		$round_rate 		= $reviews_rate;
		$rating_average		= ( $round_rate / 5 )*100;

		if ( $itme_type === 'v1' ) {?>
			<div class="wt-proposalfeedback">
				<span class="wt-starcontent"><?php echo esc_html( $round_rate );?>/<i><?php esc_html_e('5','workreap');?></i>&nbsp;<em>(<?php echo esc_html( $total_rating );?>&nbsp;<?php esc_html_e('Feedback','workreap');?>)</em></span>
			</div>
		<?php } elseif( $itme_type === "v2" ) { ?>
			<div class="wt-rightarea user-stars-v2">
				<span class="wt-stars"><span style="width: <?php echo esc_html($rating_average);?>%;"></span></span><span class="wt-starcontent"><?php echo esc_html( $round_rate );?><sub>/<?php esc_html_e('5','workreap');?></sub><em>(<?php echo esc_html( $total_rating );?>&nbsp;<?php esc_html_e('Feedback','workreap');?>)</em></span>
			</div>
		<?php } elseif( $itme_type === "v3" ) { ?>
			<div class="wt-rating">
				<span class="wt-stars wt-starstwo"><span style="width: <?php echo esc_html($rating_average);?>%;"></span></span> <em><?php echo esc_html( $round_rate );?> / <?php esc_html_e('5','workreap');?></em>
			</div>
		<?php }
		
        echo ob_get_clean();       
    }
    add_action('workreap_freelancer_get_reviews', 'workreap_freelancer_get_reviews', 10 , 2);
}

/**
 * Print project rating by rating value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_get_project_rating' ) ){
    function workreap_freelancer_get_project_rating( $rating = '',$post_id ){              
		ob_start(); 
		$rating			= !empty($rating) ? $rating : 0.0;
		$round_rate 	= number_format((float) $rating, 1);
		$rating_average	= ( $round_rate / 5 )*100;
		$rating_headings			= workreap_project_ratings();
		?>
		<li class="user-stars-v2 wt-overallratingarea">
			<span class="wt-stars"><span style="width: <?php echo esc_attr($rating_average);?>%;"></span></span>
			<?php if( !empty( $rating_headings ) ) {?>
				<i class="fa fa-exclamation-circle"></i>
				<div class="wt-overallrating">
					<ul class="wt-servicesrating">
					<?php 
					foreach ( $rating_headings  as $key => $item ) {
						$saved_projects     = get_post_meta($post_id, $key, true);
						if( !empty( $saved_projects ) ) {
							$percentage	= $saved_projects * 20;
						?>
						<li>
							<span class="wt-stars"><span style="width:<?php echo esc_attr( $percentage );?>%;"></span></span>
							<em><?php echo esc_html( $item );?></em>
						</li>
						<?php }}?>
					</ul>
				</div>
			<?php }?>
		</li>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_freelancer_get_project_rating', 'workreap_freelancer_get_project_rating', 10 , 2);
}

/**
 * print post date
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_post_date' ) ){
    function workreap_post_date( $post_id = '' ){              
		ob_start(); 
		
		$date_formate	= get_option('date_format');
		$post_date		= !empty($post_id) ? get_post_field('post_date',$post_id) : "";
		
		if( !empty($post_date) ) {?>
			<li><span><i class="fa fa-calendar"></i> <?php echo date_i18n($date_formate,strtotime($post_date));?></span></li>
		<?php 
		}
        echo ob_get_clean();       
    }
    add_action('workreap_post_date', 'workreap_post_date', 10 , 1);
}

/**
 * Filter Project Location
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'workreap_filter_project_location' ) ) {
    function workreap_filter_project_location($user_id = ''){
		
		$location	= array();
		
        if( !empty( $user_id ) ){ 
            $args 		= array();
            $terms = wp_get_post_terms( $user_id, 'locations', $args );  			
            if( !empty( $terms ) ){
                foreach ( $terms as $key => $term ) {    
					$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
					if( !empty( $country['url'] ) ){
						$location['flag_url']		= workreap_add_http( $country['url'] );
					} else {
						$location['country_name']	= esc_html( $term->name );
					} 
                }
            }
        }
		
		return $location;
    }
    
    add_filter('workreap_filter_project_location','workreap_filter_project_location', 10, 1);
}

/**
 * Filter project level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_filter_project_level' ) ){
	function workreap_filter_project_level( $post_id = '', $single_sign = 'yes' ){
		if( !empty( $post_id ) ) {
			$project_level	= '';
			$level			= array();
			if (function_exists('fw_get_db_post_option')) {
				$project_level          = fw_get_db_post_option($post_id, 'project_level', true);                
			}

			$level['level_title']		= workreap_get_project_level($project_level);
			
			if( !empty( $project_level ) ){
				if( $single_sign === 'yes' ){
					$level['level_sign']	= 0;
				} else{
					if( $project_level === 'basic' ){
						$level['level_sign']	= 1;
					} elseif( $project_level === 'medium' ){ 
						$level['level_sign']	= 2;
					} elseif( $project_level === 'expensive'){ 
						$level['level_sign']	= 3;
					}
				}
			}
			
			return $level; 
		}
	}
	 add_filter('workreap_filter_project_level','workreap_filter_project_level', 10, 2);
}

/**
 * Filter project level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists( 'workreap_filter_project_skills' ) ){
	function workreap_filter_project_skills( $post_id = '' ){
		$skills			= array();
		
		if( !empty( $post_id ) ) {
			
			if (function_exists('fw_get_db_post_option')) {
				$project_skills	= fw_get_db_post_option($post_id, 'skills', true);                
			}
			
			if( !empty( $project_skills ) && is_array($project_skills) ){
				
				$count	= 0;
				foreach( $project_skills as $key => $item ){
					$count	++;
					if( !empty( $item['skill'][0] ) ){
						$skill					= get_term_by('id', $item['skill'][0], 'skills');
						$skills[$count]['skill_val']	= !empty( $item['value'] ) ? $item['value'] : 0;
						$skills[$count]['skill_name']	= $skill->name;
						
					}
				}
			}
			
			return array_values($skills); 
		}
	}
	 add_filter('workreap_filter_project_skills','workreap_filter_project_skills', 10, 1);
}

/**
 * if no Record Found
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'workreap_empty_records_html' ) ) {
	add_action('workreap_empty_records_html', 'workreap_empty_records_html', 10, 3);
	function workreap_empty_records_html($class_name= '', $message = '',$wrap=false) {
		ob_start();
		?>
		<?php if( $wrap === true ){?>
			<div class="wt-emptydata-holder">
		<?php }?>
			<div class="wt-emptydata">
				<div class="wt-emptydetails <?php echo esc_attr( $class_name );?>">
					<span></span>
					<?php if( !empty($message) ) { ?>
						<em><?php echo esc_html($message);?></em>
					<?php } ?>
				</div>
			</div>
		<?php if( $wrap === true ){?>
			</div>
		<?php }?>
		<?php
		echo ob_get_clean();
	}
}

/**
 * Return Job Duration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_job_duration_list' ) ) {
    function worktic_job_duration_list(){
		if(taxonomy_exists('english_level')){
			$term_data = get_terms( 
				array(
					'taxonomy' 		=> 'durations',
					'hide_empty' 	=> false,
				) 
			);

			if( !empty( $term_data ) ){
				return wp_list_pluck( $term_data, 'name', 'slug' );
			}
		}
				
        $list = array(
            'weekly' 		=> esc_html__('Less than a week','workreap'),
			'monthly' 		=> esc_html__('Less than a month','workreap'),
			'three_month' 	=> esc_html__('01 to 03 months','workreap'),
			'six_month' 	=> esc_html__('03 to 06 months','workreap'),
			'more_than_six' => esc_html__('More than 06 months','workreap'),
        );

        $list = apply_filters('worktic_filters_duration_list', $list);         
        return $list;
    }
    add_filter('worktic_job_duration_list', 'worktic_job_duration_list', 10, 1);
}

/**
 * Return Job English Level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_english_level_list' ) ) {
    function worktic_english_level_list(){
		if(taxonomy_exists('english_level')){
			$term_data = get_terms( 
				array(
					'taxonomy' 		=> 'english_level',
					'hide_empty' 	=> false,
				) 
			);

			if( !empty( $term_data ) ){
				return wp_list_pluck( $term_data, 'name', 'slug' );
			}
		}

        $list = array(
            'basic'             => esc_html__('Basic', 'workreap'),
            'conversational'    => esc_html__('Conversational', 'workreap'),
            'fluent'            => esc_html__('Fluent', 'workreap'),
            'native'            => esc_html__('Native Or Bilingual', 'workreap'),
            'professional'      => esc_html__('Professional', 'workreap'),            
        );
		
        $list = apply_filters('worktic_filters_english_level_list', $list);         
        return $list;
    }
    add_filter('worktic_english_level_list', 'worktic_english_level_list', 10, 1);
}

/**
 * Return Freelancer Level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_freelancer_level_list' ) ) {
    function worktic_freelancer_level_list(){
		if(taxonomy_exists('english_level')){
			$term_data = get_terms( 
				array(
					'taxonomy' 		=> 'freelancer_type',
					'hide_empty' 	=> false,
				) 
			);

			if( !empty( $term_data ) ){
				return wp_list_pluck( $term_data, 'name', 'slug' );
			}
		}

        $list = array(
            'independent'       => esc_html__('Independent Freelancers', 'workreap'),
            'agency'            => esc_html__('Agency Freelancers', 'workreap'),
            'rising_talent'     => esc_html__('New Rising Talent', 'workreap'),            
        );
		
        $list = apply_filters('worktic_filters_freelancer_level_list', $list);         
        return $list;
    }
    add_filter('worktic_freelancer_level_list', 'worktic_freelancer_level_list', 10, 1);
}

/**
 * Return Hourly Rate
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'worktic_hourly_rate_list' ) ) {
    function worktic_hourly_rate_list(){
		if(taxonomy_exists('hourly_rate')){
			$term_data = get_terms( 
				array(
					'taxonomy' 		=> 'hourly_rate',
					'hide_empty' 	=> false,
					'orderby' 		=> 'ID',
					'order' 		=> 'DESC',
				) 
			);

			if( !empty( $term_data ) ){
				return wp_list_pluck( $term_data, 'name', 'slug' );
			}
		}
		
        $list = array(
            '0-5'              => esc_html__('$5 And Below', 'workreap'),
            '5-10'             => esc_html__('$5 - $10', 'workreap'),          
            '10-20'            => esc_html__('$10 - $20', 'workreap'),          
            '20-30'            => esc_html__('$20 - $30', 'workreap'),          
            '30-40'            => esc_html__('$30 - $40', 'workreap'),          
            '40-50'            => esc_html__('$40 - $50', 'workreap'),          
            '50-60'            => esc_html__('$50 - $60', 'workreap'),          
            '60-70'            => esc_html__('$60 - $70', 'workreap'),          
            '70-80'            => esc_html__('$70 - $80', 'workreap'),          
            '80-90'            => esc_html__('$80 - $90', 'workreap'),          
            '90-0'             => esc_html__('$100 And Above', 'workreap'),          
        );
		
		$list = apply_filters('worktic_set_hourly_rate_list', $list);
		$price_symbol		= workreap_get_current_currency();
		
		if( !empty($price_symbol['symbol']) ){
			$list = str_replace('$',$price_symbol['symbol'],$list);
		}

        return $list;
    }
    add_filter('worktic_hourly_rate_list', 'worktic_hourly_rate_list', 10, 1);
}


/**
 * Return project header
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_job_detail_header' ) ) {
    function workreap_job_detail_header( $post_id = '' ){
        if( !empty( $post_id ) ){?>
             <ul class="wt-userlisting-breadcrumb wt-userlisting-breadcrumbvtwo">
                <?php do_action('workreap_print_project_option_type', $post_id); ?>
                <?php do_action('workreap_print_project_duration_html', $post_id); ?>
                <?php do_action('workreap_project_print_project_level', $post_id); ?>
                <?php do_action('workreap_print_project_date', $post_id); ?>
				<?php do_action('workreap_print_location', $post_id); ?>
            </ul>
    <?php }
    }
    add_action('workreap_job_detail_header', 'workreap_job_detail_header', 10, 1);
}

/**
 * Return project header on search
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_job_short_detail' ) ) {
    function workreap_job_short_detail( $post_id = '' ){
        if( !empty( $post_id ) ){?>
             <ul class="wt-userlisting-breadcrumb wt-joblistind-short">
				<?php do_action('workreap_print_project_price', $post_id);?>	
            </ul>
    <?php }
    }
    add_action('workreap_job_short_detail', 'workreap_job_short_detail', 10, 1);
}

/**
 * Return Project price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_price' ) ){
    function workreap_print_project_price( $post_id = '', $type = 'v1' ){
        if( !empty( $post_id ) ){
			$project_price	= workreap_project_price($post_id);

			$project_cost	= !empty($project_price['cost']) ? $project_price['cost'] : 0;
			$job_type_text	= !empty($project_price['text']) ? $project_price['text'] : '';
			$job_price_text	= !empty($project_price['price_text']) ? $project_price['price_text'] : '';
			
			if( !empty( $project_cost ) && $type == 'v1' ) { 
				$job_price_type	= !empty($project_price['type']) && $project_price['type'] === 'hourly' ? '&nbsp;'.esc_html__( 'For', 'workreap' ).'&nbsp;'.$project_price['estimated_hours'].'&nbsp;'.esc_html__( 'hours', 'workreap' ) : ''; ?>

				<li><span class="wt-budget"><img class="wt-job-icon" src="<?php echo esc_url(get_template_directory_uri());?>/images/job-cost.png" alt="<?php esc_attr_e('Project cost', 'workreap'); ?>"><?php echo esc_html($job_price_text); ?>&nbsp;<em><?php echo esc_html($project_cost);?></em><?php echo esc_html($job_price_type);?></span></li>
            <?php } else if (!empty( $project_cost ) && $type == 'v2') { 
				$job_price_type	= !empty($project_price['type']) && $project_price['type'] === 'hourly' ? '('.$project_price['estimated_hours'].'&nbsp;'.esc_html__( 'Hours', 'workreap' ).')' : ''; ?>
				<h4><?php echo esc_html($project_cost);?>
				<em><?php echo esc_html($job_price_type);?></em></h4>
			<?php } else if (!empty( $project_cost ) && $type == 'v3') {
				$job_price_type	= !empty($project_price['type']) && $project_price['type'] === 'hourly' ? '('.$project_price['estimated_hours'].'&nbsp;'.esc_html__( 'Hours', 'workreap' ).')' : '';
				 ?>
				<span><?php esc_html_e('Project budget','workreap');?>:</span>
				<strong>
					<?php 
					echo esc_html($project_cost);
					if( !empty($job_price_type) ){ ?>
						&nbsp;<?php echo esc_html($job_price_type);?>
					<?php } ?>
				</strong>
			<?php }
        }
    }
    add_action('workreap_print_project_price', 'workreap_print_project_price', 10, 2);
}

/**
 * Return Project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_type' ) ){
    function workreap_print_project_type( $post_id = '' ){
        if( !empty( $post_id ) ){
            $project_type = '';   
            if (function_exists('fw_get_db_post_option')) {
                $project_type = fw_get_db_post_option($post_id, 'project_type', true);
			}
			$icon_img	= '';
            if (function_exists('fw_get_db_settings_option')) {                       
				$icon_img	= fw_get_db_settings_option('project_type_img');
            }
			$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/job-type.png';

			$project_type   = !empty( $project_type['gadget'] ) ? $project_type['gadget'] : '';
			$project_type	= isset( $project_type ) && $project_type == 'hourly' ?  esc_html__('Hourly', 'workreap') : esc_html__('Fixed Price', 'workreap');
            
            if( !empty( $project_type ) ) { ?>
                <li><span><img class="wt-job-icon"  src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Project type', 'workreap'); ?>"><?php esc_html_e('Project type', 'workreap'); ?>:&nbsp;<?php echo esc_html( ucfirst( $project_type ) ); ?></span></li>
            <?php }
        }
    }
    add_action('workreap_print_project_type', 'workreap_print_project_type', 10, 1);
}

/**
 * Return Project option type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_option_type' ) ){
    function workreap_print_project_option_type( $post_id = '' ){
        if( !empty( $post_id ) ){ 
			$icon_img	= '';
            if (function_exists('fw_get_db_settings_option')) {                       
				$icon_img	= fw_get_db_settings_option('job_type_img');
            }
			$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/office-job-location.png';

			$job_option	= get_post_meta($post_id, '_job_option', true);
			$job_option	= !empty($job_option) ? workreap_get_job_option($job_option) : '';
            if( !empty( $job_option ) ) { ?>
                <li><span><img class="wt-job-icon"  src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Job type', 'workreap'); ?>"><?php esc_html_e('Job type', 'workreap'); ?>:&nbsp;<?php echo esc_html( ( $job_option ) ); ?></span></li>
            <?php }
        }
    }
    add_action('workreap_print_project_option_type', 'workreap_print_project_option_type', 10, 1);
}

/**
 * Return Project duration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_duration_html' ) ){
    function workreap_print_project_duration_html( $post_id = '', $type = 'v1' ){
        if( !empty( $post_id ) ){            
			$project_duration   = '';
			$icon_img			= '';
            if (function_exists('fw_get_db_post_option')) {                               
                $project_duration   		= fw_get_db_post_option($post_id, 'project_duration', true);
				$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
				$icon_img   				= fw_get_db_settings_option('job_duration_img');
            }
			
			$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/job-duration.png';
			if(!empty($remove_project_duration) && $remove_project_duration === 'no' ){ 
				$duration_list = worktic_job_duration_list();
				$project_duration_value = !empty( $duration_list[$project_duration] ) ? $duration_list[$project_duration] : '';
				if( !empty( $project_duration_value ) && $type == 'v1' ) { ?>
					<li><span><img class="wt-job-icon"  src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Job Duration', 'workreap'); ?>"><?php echo esc_html( $project_duration_value ); ?></span></li>
				<?php } else if( !empty( $project_duration_value ) && $type == 'v2' ) { ?>
					<span><?php echo esc_html( $project_duration_value ); ?></span>
				<?php } else if( !empty( $project_duration_value ) && $type == 'v3' ) { ?>
					<strong><?php echo esc_html( $project_duration_value ); ?></strong>
				<?php }
			}
        }
    }
    add_action('workreap_print_project_duration_html', 'workreap_print_project_duration_html', 10, 2);
}

/**
 * Return Project duration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_date' ) ){
    function workreap_print_project_date( $post_id = '' ,$type=''){
        if( !empty( $post_id ) ){            
            $expiry_date   = '';
            if (function_exists('fw_get_db_post_option')) {                               
                $expiry_date   = fw_get_db_post_option($post_id, 'expiry_date', true);
            }
			
			$icon_img	= '';
			if (function_exists('fw_get_db_settings_option')) {                       
				$icon_img	= fw_get_db_settings_option('job_expiry_img');
			}
			$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/job-expiry.png';

			$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
			
            if( !empty( $expiry_date ) ) {
				if( current_time( 'timestamp' ) > strtotime($expiry_date) ){
					$status	=  esc_html__('Expired','workreap');
				} else{
					$status	=  date_i18n( get_option('date_format'), strtotime($expiry_date));
				}
				
				if( !empty($type) && $type == 'v2' ){?>
					<strong><?php echo esc_html($status);?></strong>
				<?php } else { ?>
                	<li><span><img class="wt-job-icon"  src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Project deadline', 'workreap'); ?>"><?php echo esc_html( $status ); ?></span></li>
			<?php }
			}
        }
    }
    add_action('workreap_print_project_date', 'workreap_print_project_date', 10, 2);
}

/**
 * Return project skills
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_display_categories_html' ) ){
    function workreap_display_categories_html( $post_id = '', $show_title = 'true',$type='',$view=4){
		$uniq_flag  	= rand(1,9999);
        if( !empty( $post_id ) ){ 
			
            $args 	= array();
			if( taxonomy_exists('project_cat') ) {
				$terms 			= wp_get_post_terms( $post_id, 'project_cat', $args );
				$total_cats		= !empty($terms) ? count($terms) : 0;
				if( !empty($type) && $type == 'v2' ){ ?>
					<div class="wt-prjectstags">
						<?php if( !empty( $terms ) ){ ?>
							<ul>
							<?php 
								$count_cats	= 0;
								foreach ( $terms as $key => $term ) {
									
									$term_link = get_term_link( $term->term_id, 'project_cat' );

									$style	= '';	

									if( !empty( $view ) && $total_cats > $view ) {
										if( $count_cats >= $view ) {
											$style	= 'style="display: none;"';
										}

										if( $count_cats == $view ) {
											if( $total_cats >= $view){
												$more_count	= ($total_cats - $view);
												echo '<li class="showmore_skills" data-id="'.esc_attr( $post_id ).'-'.esc_attr($uniq_flag).'"><a class="wt-moretags" href="#" onclick="event_preventDefault(event);">'.sprintf(esc_html__('+%s more','workreap'),$more_count).'</a></li>';;
											}
										}
									}
									$count_cats++;
								?>
									<li class="skills_<?php echo intval($post_id);?>-<?php echo esc_attr($uniq_flag);?>" <?php echo do_shortcode( $style );?>><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
					</div>
				<?php } else { ?>
					<div class="wt-skillsrequired">
						<?php if($show_title === 'true') { ?>
							<div class="wt-title">
								<h3><?php esc_html_e('Industry Categories', 'workreap'); ?></h3>
							</div>
						<?php } ?>
						<?php if( !empty( $terms ) ){ ?>
							<div class="wt-tag wt-widgettag">
								<?php 
									$count_skills	= 0;
									foreach ( $terms as $key => $term ) {
										$count_skills++;
										$term_link = get_term_link( $term->term_id, 'project_cat' );
									?>
									<a class="cat_<?php echo intval($post_id);?>" href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $term->name ); ?></a>                     
								<?php } ?>
							</div>
						<?php }?>
					</div>
				<?php }
			}
        }
    }
    add_action('workreap_display_categories_html', 'workreap_display_categories_html', 10, 4);
}

/**
 * Return project langauge 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_display_langauges_html' ) ){
    function workreap_display_langauges_html( $post_id = ''){

        if( !empty( $post_id ) ){ 
			
            $args 	= array();
			if( taxonomy_exists('languages') ) {
				$terms 	= wp_get_post_terms( $post_id, 'languages', $args );
				if( !empty( $terms ) ){ 
				?>
				<div class="wt-skillsrequired">
					<div class="wt-title">
						<h3><?php esc_html_e('Languages required', 'workreap'); ?></h3>
					</div>
					<div class="wt-tag wt-widgettag">
						<?php 
							$count_skills	= 0;
							foreach ( $terms as $key => $term ) {
								$count_skills++;
							?>
							<a class="cat_<?php echo intval($post_id);?>" href="#" onclick="event_preventDefault(event);"><?php echo esc_html( $term->name ); ?></a>                     
						<?php } ?>
					</div>
				</div>
				<?php 
				}
			}
        }
    }
    add_action('workreap_display_langauges_html', 'workreap_display_langauges_html', 10, 1);
}

/**
 * Return service langauge 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_display_service_langauges_html' ) ){
    function workreap_display_service_langauges_html( $post_id = ''){

        if( !empty( $post_id ) ){ 
			
            $args 	= array();
			if( taxonomy_exists('languages') ) {
				$terms 	= wp_get_post_terms( $post_id, 'languages', $args );
				if( !empty( $terms ) ){ 
				?>
				<div class="wt-languagesrequired wt-haslayout">
					<div class="wt-title">
						<h3><?php esc_html_e('Languages freelancer can speak', 'workreap'); ?></h3>
					</div>
					<div class="wt-tag wt-widgettag">
						<?php 
							$count_skills	= 0;
							foreach ( $terms as $key => $term ) {
								$count_skills++;
							?>
							<a class="cat_<?php echo intval($post_id);?>" href="#" onclick="event_preventDefault(event);"><?php echo esc_html( $term->name ); ?></a>                     
						<?php } ?>
					</div>
				</div>
				<?php 
				}
			}
        }
    }
    add_action('workreap_display_service_langauges_html', 'workreap_display_service_langauges_html', 10, 1);
}

/**
 * Return project langauge 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_display_required_freelancer_html' ) ){
    function workreap_display_required_freelancer_html( $post_id = ''){

        if( !empty( $post_id ) ){ 
			$terms 	= get_post_meta( $post_id, '_freelancer_level', true );
			$terms	= !is_array($terms)? array($terms) : $terms;

			if( !empty( $terms[0] ) ){?>
			<div class="wt-skillsrequired">
				<div class="wt-title">
					<h3><?php esc_html_e('Freelancer type required for this project', 'workreap'); ?></h3>
				</div>
				<div class="wt-tag wt-widgettag">
					<?php 
						$count_type	= 0;
						foreach ( $terms as $key => $term ) {
							$count_type++;
							$term_data = get_term_by( 'slug', $term, 'freelancer_type' );
							if(!empty( $term_data )){
						?>
						<a href="#" onclick="event_preventDefault(event);" class="cat_<?php echo intval($post_id);?>"><?php echo esc_html( $term_data->name ); ?></a>                     
					<?php }} ?>
				</div>
				
			</div>
			<?php 
			}
        }
    }
    add_action('workreap_display_required_freelancer_html', 'workreap_display_required_freelancer_html', 10, 1);
}

/**
 * Return project skills
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_skills_html' ) ){
    function workreap_print_skills_html( $post_id = '', $title = '',$view = 4){
        if( !empty( $post_id ) ){ 
            $args 	= array();
			if( taxonomy_exists('skills') ) {
				$terms 	= wp_get_post_terms( $post_id, 'skills', $args );
				$view	= intval($view);
				$total_skills	= count($terms);
				if( !empty( $terms[0] ) ){
				echo '<div class="wt-skillsrequired">';
					if( !empty( $title ) ){ ?>
						<div class="wt-title">
							<h3><?php echo esc_html( $title ); ?></h3>
						</div>
					<?php } ?>
					<div class="wt-tag wt-widgettag">
						<?php 
							$count_skills	= 0;
							foreach ( $terms as $key => $term ) {
								$count_skills++;
								$term_link = get_term_link( $term->term_id, 'skills' );
								$search_page  = workreap_get_search_page_uri('jobs');
								
								$search_page	= !empty( $search_page ) ? $search_page.'?skills[]='.$term->slug : $term_link;
								$style	= '';	

								if( !empty( $view ) && $total_skills > 4 ) {
									if( $count_skills >= $view ) {
										$style	= 'style="display: none;"';
									}

									if( $count_skills == $view ) {
										echo '<a href="#" onclick="event_preventDefault(event);" class="showmore_skills" data-id="'.esc_attr( $post_id ).'">...</a>';;
									}
								}
							?>
							<a <?php echo do_shortcode($style);?> class="skills_<?php echo intval($post_id);?>" href="<?php echo esc_url( $search_page ); ?>"><?php echo esc_html( $term->name ); ?></a>                     
						<?php } ?>
					</div>
				</div>
				<?php }
			}
        }
    }
    add_action('workreap_print_skills_html', 'workreap_print_skills_html', 10, 3);
}

/**
 * Return project skills
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_freelancer_skills' ) ){
    function workreap_print_freelancer_skills( $post_id = '', $title = '',$view = 4){
        if( !empty( $post_id ) ){ 
            $args 	= array();
			if( taxonomy_exists('skills') ) {
				$terms 	= wp_get_post_terms( $post_id, 'skills', $args );
				$view	= intval($view);
				$total_skills	= count($terms);
				if( !empty( $title ) ){ ?>
					<div class="wt-title">
						<h3><?php echo esc_html( $title ); ?></h3>
					</div>
				<?php } ?>
				<?php if( !empty( $terms ) ){ ?>
					<div class="wt-tag wt-widgettag">
						<?php 
							$count_skills	= 0;
							foreach ( $terms as $key => $term ) {
								$count_skills++;
								$term_link = get_term_link( $term->term_id, 'skills' );
								$search_page  = workreap_get_search_page_uri('freelancer');
								
								$search_page	= !empty( $search_page ) ? $search_page.'?skills[]='.$term->slug : $term_link;
								$style	= '';	

								if( !empty( $view ) && $total_skills > 4 ) {
									if( $count_skills >= $view ) {
										$style	= 'style="display: none;"';
									}

									if( $count_skills == $view ) {
										echo '<a href="#" onclick="event_preventDefault(event);" class="showmore_skills" data-id="'.esc_attr( $post_id ).'">...</a>';;
									}
								}
							?>
							<a <?php echo do_shortcode($style);?> class="skills_<?php echo intval($post_id);?>" href="<?php echo esc_url( $search_page ); ?>"><?php echo esc_html( $term->name ); ?></a>                     
						<?php } ?>
					</div>
				<?php }
			}
        }
    }
    add_action('workreap_print_freelancer_skills', 'workreap_print_freelancer_skills', 10, 3);
}
                               
/**
 * Return project documents
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_job_detail_documents' ) ) { 
    function workreap_job_detail_documents( $post_id = '' ){
        if( !empty( $post_id ) ){
            $project_documents  = array();
            if (function_exists('fw_get_db_post_option')) {
                $project_documents  = fw_get_db_post_option($post_id, 'project_documents', true);
				$show_attachments  = fw_get_db_post_option($post_id, 'show_attachments', true);
				$attachment_display = fw_get_db_settings_option('attachment_display');
            }
			
			$display = 'wt-attachfile';
			if(!empty($attachment_display) && $attachment_display === 'grid'){
				$display	= 'wt-fileuploadwrap';
			}

            if( !empty( $show_attachments ) && $show_attachments === 'on' && !empty( $project_documents ) ){?>
            <div class="wt-attachments wt-skillsrequired">
                <div class="wt-title">
                    <h3><?php esc_html_e('Attachments', 'workreap'); ?></h3>
                </div>
                <ul class="<?php echo esc_attr($display);?>">
                    <?php 
                    foreach ( $project_documents as $key => $value ) {
						$document_name   = esc_html( get_the_title( $value['attachment_id'] ));
						$file_size       = !empty( get_attached_file( $value['attachment_id'] ) ) ? filesize( get_attached_file( $value['attachment_id'] ) ) : '';
						$full_url		= '';
						$filetype        = wp_check_filetype( $value['url'] );
						$file_detail         = Workreap_file_permission::getDecrpytFile($value);
						$name                = $file_detail['filename'];
						$doc_url             = $file_detail['dirname'].'/'.$name;
						
					   if(!empty($attachment_display) && $attachment_display === 'grid'){
						   $extension       = !empty( $filetype['ext'] ) ? $filetype['ext'] : '';

						   $icon			= workreap_get_icon_name($extension);
						   $image			= array('gif','png','jpg','jpeg','JPEG','JPG');
						   
						   if(!empty($extension) && in_array($extension,$image) ){
							   $width 	= 235;
							   $height  = 149;
							   $full_url  = $value['url'];
							   $thumb_url = wp_get_attachment_image_src( $value['attachment_id'], array( $width, $height ), true );

								if ( $thumb_url[1] === $width && $thumb_url[2] === $height ) {
									$thumb_url = !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
								} else {
									$thumb_url = wp_get_attachment_image_src( $value['attachment_id'], 'full', true );
									if (strpos($thumb_url[0],'media/default.png') !== false) {
										$thumb_url = '';
									} else{
										$thumb_url = !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
									}
								}

						   }
						   ?>
							<li class="wt-<?php echo esc_attr($extension);?>">
								<div class="wt-fileuploaded">
									<figure class="wt-fileicon">
										<?php if(!empty($full_url)){
											$script	= "jQuery('.wt-venobox').venobox();";
											wp_add_inline_script( 'venobox', $script, 'after' );
											?>
											<a data-autoplay="true" data-vbtype="video" href="<?php echo esc_url($full_url);?>" class="wt-imgzoom wt-venobox"><i class="ti-zoom-in"></i> <img src="<?php echo esc_url($thumb_url);?>" alt="<?php esc_attr_e('video', 'workreap'); ?>"></a>
										<?php }else{?>
											 <i class="<?php echo esc_attr($icon);?>"></i>
											 <a href="<?php echo esc_url( $value['url'] ); ?>" class="wt-filwsave" download><i class="ti-download"></i></a>
										<?php }?>
									</figure>
									<div class="wt-filetitle">
										<h5><?php echo esc_html( $name ); ?></h5>
										<span><?php esc_html_e('File size:', 'workreap'); ?>&nbsp;<?php echo esc_html( size_format($file_size, 2) ); ?></span>
									</div>
								</div>
							</li>
						<?php }else{?>               
						<li>
							<label>
								<span><?php echo esc_html( 	$name ); ?></span>
								<em><?php esc_html_e('File size', 'workreap'); ?>:&nbsp;<?php echo esc_html( size_format($file_size, 2) ); ?><a href="javascript:void(0);" class="wt-download-single-file" data-id="<?php echo esc_attr( $value['attachment_id'] ); ?>"><i class="lnr lnr-download"></i></a></em>
							</label>
						</li>
					<?php }} ?>                   
                </ul>
            </div>
        <?php } 
        }
    }
    add_action('workreap_job_detail_documents', 'workreap_job_detail_documents', 10, 1);
}



/**
 * Return project Level
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
 if( !function_exists( 'workreap_project_print_project_level') ){
    function workreap_project_print_project_level( $post_id = '', $single_sign = 'yes' ){
        if( !empty( $post_id ) ) {
			$project_level 	= '';
			$icon_img		= '';
            if (function_exists('fw_get_db_post_option')) {
                $project_level          = fw_get_db_post_option($post_id, 'project_level', true);
				$remove_project_level   = fw_get_db_settings_option('remove_project_level');
				$icon_img   			= fw_get_db_settings_option('job_level_img');
            }
			$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/job-level.png';
			if(!empty($remove_project_level) && $remove_project_level === 'no' && !empty($project_level) ){
				$level	= workreap_get_project_level($project_level);
				ob_start();
				?>
				<li><span><img class="wt-job-icon" src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Project Level', 'workreap'); ?>"><?php echo esc_html( $level );?></span></li>
				<?php
				echo ob_get_clean(); 
			}
        }
    }
    add_action('workreap_project_print_project_level', 'workreap_project_print_project_level', 10, 2);
 }

/**
 * Return save project html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_save_project_html') ){
    function workreap_save_project_html( $project_id, $type = 'v1' ){
        global $current_user;        
        $post_id            = workreap_get_linked_profile_id($current_user->ID);
        $saved_projects     = get_post_meta($post_id, '_saved_projects', true);
        $saved_projects     = !empty( $saved_projects ) ? $saved_projects : array();

		$icon_img	= '';
		if (function_exists('fw_get_db_settings_option')) {                       
			$icon_img	= fw_get_db_settings_option('job_save_img');
		}
		$image_url	= !empty($icon_img['url']) ? $icon_img['url'] : get_template_directory_uri().'/images/favorite.png';

		
        if( $type == 'v1' ){            
            if ( in_array($project_id, $saved_projects) ) { ?>
                <span><a href="#" onclick="event_preventDefault(event);" class="wt-clicksavebtn wt-clicksave" data-id="<?php echo esc_attr( $project_id ); ?>"><img class="wt-job-icon" src="<?php echo esc_url(get_template_directory_uri());?>/images/favorite.png" alt="<?php esc_attr_e('Project Level', 'workreap'); ?>"><?php esc_html_e('Saved', 'workreap'); ?></a></span>
            <?php } else { ?>
                <span><a href="#" onclick="event_preventDefault(event);" class="wt-clicksavebtn wt-add-to-saved_projects" data-id="<?php echo esc_attr( $project_id ); ?>"><img class="wt-job-icon" src="<?php echo esc_url(get_template_directory_uri());?>/images/favorite.png" alt="<?php esc_attr_e('Project Level', 'workreap'); ?>"><em><?php esc_html_e('Click to save', 'workreap'); ?></em></a></span>
            <?php }
        } elseif( $type == 'v2' ) {           
             if ( in_array($project_id, $saved_projects) ) { ?>
                <span><a href="#" onclick="event_preventDefault(event);" class="wt-clicklike wt-clicksave" data-id="<?php echo esc_attr( $project_id ); ?>"><img class="wt-job-icon" src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Project Level', 'workreap'); ?>"><?php esc_html_e('Saved', 'workreap'); ?></a></span>
            <?php } else { ?>
                <span><a href="#" onclick="event_preventDefault(event);" class="wt-clicklike wt-add-to-saved_projects" data-id="<?php echo esc_attr( $project_id ); ?>"><img class="wt-job-icon" src="<?php echo esc_url($image_url);?>" alt="<?php esc_attr_e('Project Level', 'workreap'); ?>"><em><?php esc_html_e('Save', 'workreap'); ?></em></a></span>               
            <?php }            
        } else {
			if ( in_array($project_id, $saved_projects) ) { ?>
                <a href="#" onclick="event_preventDefault(event);" class="wt-btnlike wt-clicksave" data-id="<?php echo esc_attr( $project_id ); ?>"><i class="ti-heart"></i></a>
            <?php } else { ?>
                <a href="#" onclick="event_preventDefault(event);" class="wt-btnlike wt-add-to-saved_projects" data-type="v3" data-id="<?php echo esc_attr( $project_id ); ?>"><i class="ti-heart"></i></a>               
            <?php } 
		}
    }
    add_action('workreap_save_project_html', 'workreap_save_project_html', 10, 2);
}

/**
 * Return Featured Job with Html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
 if( !function_exists( 'workreap_project_print_featured') ){
    function workreap_project_print_featured( $post_id = '', $returnClass='non' ){
        if( !empty( $post_id ) ) {
			$title			= esc_html( get_the_title( $post_id ));
			$is_featured	= get_post_meta( $post_id, '_featured_job_string',true);
			$is_featured    = !empty( $is_featured ) ? intval( $is_featured ) : '';
			
			$defult	= get_template_directory_uri().'/images/featured.png';
            if (function_exists('fw_get_db_settings_option')) {
                $featured_image		= fw_get_db_settings_option('featured_job_img');
				$featured_bg_color	= fw_get_db_settings_option('featured_job_bg');
            }
			
			$tag		  = !empty( $featured_image['url'] ) ? $featured_image['url'] : $defult;
			$color		  = !empty( $featured_bg_color ) ? $featured_bg_color : '#f1c40f';

			if( !empty( $is_featured ) && $is_featured === 1 ) {
				if( $returnClass === 'yes' ){
					return 'wt-featured';
				} else{?>
					<span class="wt-featuredtag" style="border-top: 40px solid <?php echo esc_attr($color);?>"><img src="<?php echo esc_url($tag);?>" alt="<?php echo esc_attr($title);?>" data-tipso="<?php esc_attr_e('Featured','workreap');?>" class="template-content tipso_style wt-tipso"></span>
				<?php
				}
			}
        }
    }
    add_action('workreap_project_print_featured', 'workreap_project_print_featured', 10, 2);
	add_filter('workreap_project_print_featured', 'workreap_project_print_featured', 10, 2);
 }

/**
 * Search by geolocation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
 if( !function_exists( 'workreap_geoloacation_search') ){
    function workreap_geoloacation_search( $classes = ''){
		ob_start();

        if (function_exists('fw_get_db_settings_option')) {
            $dir_radius 			= fw_get_db_settings_option('dir_radius');
            $dir_default_radius 	= fw_get_db_settings_option('dir_default_radius');
            $dir_max_radius 		= fw_get_db_settings_option('dir_max_radius');
            $dir_distance_type 		= fw_get_db_settings_option('dir_distance_type');
            $dir_longitude 			= fw_get_db_settings_option('dir_longitude');
            $dir_latitude 			= fw_get_db_settings_option('dir_latitude');
			$dir_location 			= fw_get_db_settings_option('dir_location');
        } else {
            $dir_radius 			= '';
            $dir_radius 			= '';
            $dir_default_radius 	= 50;
            $dir_max_radius 		= 300;
            $dir_distance_type 		= 'mi';
        }
		
		if( !empty( $dir_location ) && $dir_location === 'enable' ){
			$dir_longitude 			= !empty($dir_longitude) ? $dir_longitude : '-0.1262362';
			$dir_latitude 			= !empty($dir_latitude) ? $dir_latitude : '51.5001524';
			$dir_default_radius 	= !empty($dir_default_radius) ? $dir_default_radius : 50;
			$dir_max_radius 		= !empty($dir_max_radius) ? $dir_max_radius : 300;
			$dir_distance_type 		= !empty($dir_distance_type) ? $dir_distance_type : 'mi';

			$lat 	= !empty( $_GET['lat'] ) ? $_GET['lat'] : '';
			$long 	= !empty( $_GET['long'] ) ? $_GET['long'] : '';
			$fetched_location 	= !empty( $_GET['location'] ) ? $_GET['location'] : '';

			$location = '';
			if (isset($_GET['geo']) && !empty($_GET['geo'])) {
				$location = $_GET['geo'];
			}

			$distance = $dir_default_radius;
			if (  !empty($_GET['geo_distance']) ) {
				$distance = $_GET['geo_distance'];
			}

			$distance_title = esc_html__('( Miles )', 'workreap');
			if ($dir_distance_type === 'km') {
				$distance_title = esc_html__('( Kilometers )', 'workreap');
			}

			$flag	= rand(1,9999);

			?>
			<div class="wt-widget wt-startsearch <?php echo esc_attr($classes);?>">
				<div class="wt-widgettitle">
					<h2><?php esc_html_e('Search By Geo Location','workreap');?></h2>
				</div>
				<div class="wt-widgetcontent">
					<div class="wt-formtheme wt-formsearch">
						<fieldset>
							<div class="locate-me-wrap">
								<div id="location-pickr-map" class="elm-display-none"></div>
								<input type="text"  autocomplete="on" id="location-address" value="<?php echo esc_attr($location); ?>" name="geo" placeholder="<?php esc_attr_e('Geo location', 'workreap'); ?>" class="form-control">
								<?php if (isset($dir_radius) && $dir_radius === 'enable') { ?>
									<a href="#" onclick="event_preventDefault(event);" class="geolocate"><i class="fa fa-crosshairs geolocate"></i></a>
									<a href="#" onclick="event_preventDefault(event);" class="geodistance"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
									<div class="geodistance_range elm-display-none">
										<div class="distance-ml distance-ml-<?php echo esc_attr( $flag );?>"><?php esc_html_e('Distance in', 'workreap'); ?>&nbsp;<?php echo esc_attr($distance_title); ?>&nbsp;<span><?php echo esc_js($distance); ?></span></div>
										<input type="hidden" class="geo_distance geo_distance-<?php echo esc_attr( $flag );?>" name="geo_distance" value="<?php echo esc_js($distance); ?>" />
										<div class="geo_distance geo_distance-<?php echo esc_attr( $flag );?>" id="geo_distance_<?php echo esc_attr( $flag );?>"></div>
									</div>
								<?php } ?>
								<input type="hidden" id="location-latitude" name="lat" value="<?php echo esc_attr( $lat );?>" />
								<input type="hidden" id="location-longitude" name="long" value="<?php echo esc_attr( $long );?>" />
								<?php
									$script = "jQuery(document).ready(function(e) {jQuery.workreap_init_map('" . esc_js($dir_latitude) . "','" . esc_js($dir_longitude) . "');});";
									wp_add_inline_script('workreap-maps', $script, 'after');

									$geo_distance = 'jQuery( "#geo_distance_'.esc_attr( $flag ).'" ).slider({
										   range: "min",
										   min:1,
										   max:' . esc_js($dir_max_radius) . ',
										   value:' . esc_js($distance) . ',
										   animate:"slow",
										   orientation: "horizontal",
										   slide: function( event, ui ) {
											  jQuery( ".distance-ml-'. esc_attr( $flag ).' span" ).html( ui.value );
											  jQuery( ".geo_distance-'.esc_attr( $flag ).'" ).val( ui.value );
										   }	
										});';
									wp_add_inline_script('jquery-ui-slider', $geo_distance);
								?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		<?php
		}
		echo ob_get_clean();
    }
    add_action('workreap_geoloacation_search', 'workreap_geoloacation_search', 10, 1);
 }

/**
 * Search by keyword
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
 if( !function_exists( 'workreap_keyword_search') ){
    function workreap_keyword_search( $classes = ''){
		ob_start();
		$keyword 		= !empty( $_GET['keyword']) ? stripslashes( $_GET['keyword'] ) : '';
		?>
        <div class="wt-widget wt-startsearch <?php echo esc_attr($classes);?>">
			<div class="wt-widgettitle">
				<h2><?php esc_html_e('Start Your Search','workreap');?></h2>
				<a href="#" onclick="event_preventDefault(event);" class="dc-docsearch"><span class="dc-advanceicon"><i></i> <i></i> <i></i></span><span><?php esc_html_e('Advanced','workreap');?><br><?php esc_html_e('Search','workreap');?></span></a>
			</div>
			<div class="wt-widgetcontent">
				<div class="wt-formtheme wt-formsearch">
					<fieldset>
						<div class="form-group">
							<input type="text" name="keyword" value="<?php echo esc_attr( $keyword );?>" class="form-control" placeholder="<?php esc_attr_e('Type keyword','workreap');?>">
							<button class="wt-searchgbtn" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</fieldset>
				</div>
				
			</div>
		</div>
        <?php
		echo ob_get_clean();
    }
    add_action('workreap_keyword_search', 'workreap_keyword_search', 10, 1);
 }

/**
 * Display Departments HTML
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_filter_departments' ) ){
    function workreap_filter_departments(){
		if( taxonomy_exists('department') ) {
			$departments_list = !empty( $_GET['department']) ? $_GET['department'] : array();
			$departments = get_terms( 
				array(
					'taxonomy' 		=> 'department',
					'hide_empty' 	=> false,
				) 
			);

			if( !empty( $departments ) ){
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Departments', 'workreap'); ?></h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Category', 'workreap'); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ($departments as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="category<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="department[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array($value->slug, $departments_list)); ?> >
											<label for="category<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_html( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();
			}
        }     
    }
    add_action('workreap_filter_departments', 'workreap_filter_departments', 10);
}

/**
 * Return filter by no of employees
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_filter_no_of_employees' ) ){
	function workreap_filter_no_of_employees(){
		$employees  = !empty( $_GET['employees'] ) ? $_GET['employees'] : '';    
		$list = worktic_get_employees_list();
        ob_start(); 
        ?>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2><?php esc_html_e('No. Of Employees', 'workreap'); ?></h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">             
                    <fieldset>
                        <div class="wt-checkboxholder wt-filterscroll">              
                            <?php if( !empty( $list ) ){
								foreach ( $list as $key => $value) { ?>
                                <span class="wt-radio">
                                    <input id="employees-<?php echo esc_attr( $value['value'] ); ?>" <?php checked($employees, $value['value']); ?> type="radio" name="employees" value="<?php echo esc_attr( $value['value'] ); ?>">
                                    <label for="employees-<?php echo esc_attr( $value['value'] ); ?>"><?php echo esc_html( $value['search_title'] ); ?></label>
                                </span>
                            <?php }} ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();   
	} 
	add_action('workreap_filter_no_of_employees', 'workreap_filter_no_of_employees');
}

/**
 * Return WP Avatar
 *
 * @throws error
 * @WP Guppy Compatibility
 * @return 
 */
if (!function_exists('wpguppy_user_profile_avatar')) {
	add_filter('get_avatar_url','wpguppy_user_profile_avatar',10,3);
	function wpguppy_user_profile_avatar($avatar = '', $id_or_email='', $args=array()){
		if(!empty($id_or_email) && is_numeric($id_or_email)){
			$user_type		= apply_filters('workreap_get_user_type', $id_or_email );
			$link_id		= workreap_get_linked_profile_id( $id_or_email );
			if ( $user_type === 'employer' ){
				$avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
									);
			} else{
				$avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
									);
			}
		}

		return $avatar;
	}
}

/**
 * User default avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_profile_avatar')) {
	add_filter('get_avatar','workreap_user_profile_avatar',10,5);
	function workreap_user_profile_avatar($avatar = '', $id_or_email, $size = 60, $default = '', $alt = false ){
		
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;
		 
		if ( empty( $user_id ) )
			return $avatar;
		
		$user_type	= apply_filters('workreap_get_user_type', $user_id );
		if( ( $user_type === 'freelancer' 
			  || $user_type === 'employer'
			)
			  && !empty( $user_id )
		) {
			$profile_linked_profile		= workreap_get_linked_profile_id($user_id);
			if( $user_type === 'freelancer' ) {
				$filter		= 'workreap_freelancer_filter_avatar_fallback';
				$local_avatars 	= apply_filters(
					'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
			} else if( $user_type === 'employer' ) {
				$filter		= 'workreap_employer_filter_avatar_fallback';
				
				$local_avatars 	= apply_filters(
					'workreap_employer_avatar_fallback', workreap_get_employer_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
				
			} else {
				$filter	= 'workreap_avatar_fallback';
			}
		}else {
			$local_avatars = workreap_get_user_avatar( 0, $user_id );
			$filter	= 'workreap_avatar_fallback';
		}
		
		
		
		if ( empty( $local_avatars ) ){
			return $avatar;
		}

		$size = (int) $size;

		if ( empty( $alt ) ){
			$alt = get_the_author_meta( 'display_name', $user_id );
		}

		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $local_avatars ) . "' class='avatar photo' width='".esc_attr( $size )."' height='".esc_attr( $size )."'  />";

		return $avatar;
		
	}
}


/**
 * Atom chat avatar filter
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_atomchat_filter_avatar')) {
    //add_filter('atomchat_filter_avatar', 'workreap_atomchat_filter_avatar',10,2);
	function workreap_atomchat_filter_avatar($avatar,$user_id) {
		$size	= 100;
		$user_type	= apply_filters('workreap_get_user_type', $user_id );
		if( ( $user_type === 'freelancer' 
			  || $user_type === 'employer'
			)
			  && !empty( $user_id )
		) {
			$profile_linked_profile		= workreap_get_linked_profile_id($user_id);
			if( $user_type === 'freelancer' ) {
				$filter		= 'workreap_freelancer_filter_avatar_fallback';
				$local_avatars 	= apply_filters(
					'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
			} else if( $user_type === 'employer' ) {
				$filter		= 'workreap_employer_filter_avatar_fallback';
				
				$local_avatars 	= apply_filters(
					'workreap_employer_avatar_fallback', workreap_get_employer_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
				
			} else {
				$filter	= 'workreap_avatar_fallback';
			}
		}else {
			$local_avatars = workreap_get_user_avatar( 0, $user_id );
			$filter	= 'workreap_avatar_fallback';
		}

		if ( empty( $local_avatars ) ){
			return $avatar;
		}

	}
}

	
/**
 * Manage user colums
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_check_plugin_activated')) {
    add_filter('workreap_check_plugin_activated', 'workreap_check_plugin_activated',10,1);

    function workreap_check_plugin_activated($type='core') {
        if( $type === 'core' ){
			if( class_exists( 'WorkreapGlobalSettings' ) ){
				return true;
			}
		}
		
        return false;
    }
}
/**
 * @check Module accress
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_module_access')) {

    function workreap_module_access( $key ) {
		$options	= '';
		if ( function_exists('fw_get_db_settings_option') ) {
			$options = fw_get_db_settings_option( 'micro_module' ,$default_value = null );
		}
		if( !empty( $key ) && (!empty( $options ) && $options === $key )) {
			return true; 
		} else {
			return false; 
		}
    }
	add_filter('workreap_module_access', 'workreap_module_access');
}

/**
 * Return Service Delivery
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_service_delivery_html' ) ){
    function workreap_print_service_delivery_html( $post_id = '' ){
        if( !empty( $post_id ) ){            
            $delivery_time   = '';
            if (function_exists('fw_get_db_post_option')) {                               
                $delivery_time   = fw_get_db_post_option($post_id, 'delivery_time', true);
            }
            
            $duration_list = worktic_services_delivery_list();
            $delivery_time_value = !empty( $delivery_time ) ? $duration_list[$delivery_time] : '';
            if( !empty( $delivery_time_value ) ) { ?>
                <li><span><i class="fa fa-clock-o wt-viewjobclock"></i><?php echo esc_html( $delivery_time_value ); ?></span></li>
            <?php }
        }
    }
    add_action('workreap_print_service_delivery_html', 'workreap_print_service_delivery_html', 10, 1);
}

/**
 * Return Service Price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_service_price_html' ) ){
    function workreap_print_service_price_html( $post_id = '' ){
        if( !empty( $post_id ) ){            
            $price   = '';
            if (function_exists('fw_get_db_post_option')) {                               
                $price   = fw_get_db_post_option($post_id, 'price', true);
            }
            $price = !empty( $price ) ? workreap_price_format($price,'return') : '';
            if( !empty( $price ) ) { ?>
                <li><span><i class="fa fa-dollar wt-viewjobdollar"></i><?php echo esc_html( $price ); ?></span></li>
            <?php }
        }
    }
    add_action('workreap_print_service_price_html', 'workreap_print_service_price_html', 10, 1);
}


/**
 * Return Featured service with Html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
 if( !function_exists( 'workreap_service_print_featured') ){
    function workreap_service_print_featured( $post_id = '', $returnClass='no' ){
        if( !empty( $post_id ) ) {
			$title			= get_the_title( $post_id );
			$is_featured	= get_post_meta( $post_id, '_featured_service_string',true);
			$is_featured    = !empty( $is_featured ) ? intval( $is_featured ) : '';

			if( !empty( $is_featured ) && $is_featured === 1 ) {
				if( $returnClass === 'yes' ){
					return 'wt-featured';
				} else{?>
					<span class="wt-featuredtagvtwo"><?php esc_html_e('Featured','workreap');?></span>
				<?php
				}
			}
        }
    }
    add_action('workreap_service_print_featured', 'workreap_service_print_featured', 10, 2);
	add_filter('workreap_service_print_featured', 'workreap_service_print_featured', 10, 2);
 }

/**
 * Print Service Duration
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_service_duration' ) ){
	function workreap_print_service_duration(){
		if( taxonomy_exists('delivery') ) {
			$delivery 	= !empty( $_GET['service_duration']) ? $_GET['service_duration'] : array();
			$deliveries = get_terms( 
				array(
					'taxonomy' => 'delivery',
					'hide_empty' => false,
				) 
			);
			
			$count  			= !empty($delivery) && is_array($delivery) ? count($delivery) : 0; 
			if( !empty( $deliveries ) ){
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Delivery time', 'workreap'); ?>:<span>( <em><?php echo intval($count);?></em> <?php esc_html_e('selected','workreap');?> )</span></h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search delivery time', 'workreap'); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ($deliveries as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="delivery<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="service_duration[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array( $value->slug, $delivery ) ); ?>>
											<label for="delivery<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_attr( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean(); 
			}
        }     
    }
    add_action('workreap_print_service_duration', 'workreap_print_service_duration', 10);
}

/**
 * Print Response time
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_response_time' ) ){
	function workreap_print_response_time(){
		if( taxonomy_exists('response_time') ) {
			$response 	= !empty( $_GET['response_time']) ? $_GET['response_time'] : array();
			$response_time = get_terms( 
				array(
					'taxonomy' => 'response_time',
					'hide_empty' => false,
				) 
			);
			
			$count  			= !empty($response) && is_array($response) ? count($response) : 0; 
			if( !empty( $response_time ) ){
				ob_start(); 
				?>
				<div class="wt-widget wt-effectiveholder">
					<div class="wt-widgettitle">
						<h2><?php esc_html_e('Response time', 'workreap'); ?>:<span>( <em><?php echo intval($count);?></em> <?php esc_html_e('selected','workreap');?> )</span></h2>
					</div>
					<div class="wt-widgetcontent">
						<div class="wt-formtheme wt-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control wt-filter-field" placeholder="<?php esc_attr_e('Search Response time', 'workreap'); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="wt-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="wt-checkboxholder wt-filterscroll">              
									<?php foreach ($response_time as $key => $value) { ?>
										<span class="wt-checkbox">
											<input id="response<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="response_time[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array( $value->slug, $response ) ); ?>>
											<label for="response<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_attr( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();
			}
        }     
    }
    add_action('workreap_print_response_time', 'workreap_print_response_time', 10);
}

/**
 * save service HTML
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_save_services_html' ) ) {

	function workreap_save_services_html( $id='' ,$type='') {
		global $current_user;
		ob_start();
		
		if( is_user_logged_in() ) {
			$linked_profile  	= workreap_get_linked_profile_id($current_user->ID);
			$saved_services	= get_post_meta($linked_profile, '_saved_services', true);	
			$saved_services	= !empty( $saved_services ) ?  $saved_services : array();
		} else {
			$saved_services	= array();
		}

		if( !empty($type) && $type == 'v2' ){ 
			if ( in_array($id,$saved_services) ) {?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-clicksave wt-likedv2"><i class="ti-heart"></i></a>
			<?php } else { ?>
				<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Saved','workreap');?>" class="wt-clicksave wt-saveservice-v2"><i class="ti-heart"></i></a>
			<?php } ?>
		<?php } elseif ( in_array($id,$saved_services) ) {?>
			<li><a href="#" onclick="event_preventDefault(event);" class="wt-clicksave"><i class="fa fa-heart"></i>&nbsp;<?php esc_html_e('Saved','workreap');?></a></li>
		<?php } else {?>
			<li><a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval( $id );?>" data-text="<?php esc_attr_e('Saved','workreap');?>" class="wt-clicksave wt-saveservice"><i class="fa fa-heart"></i><span><?php esc_html_e('Click to save','workreap');?></span></a></li>
		<?php
		}
		echo ob_get_clean();
	}

	add_action( 'workreap_save_services_html', 'workreap_save_services_html', 10, 2 );
}

/**
 * Print Service reviews
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_get_reviews' ) ){
    function workreap_service_get_reviews( $post_id = '' ,$itme_type = 'v1' ){              
		ob_start(); 
		if( !empty ( $post_id ) ) {
			$serviceTotalRating		= get_post_meta( $post_id , '_service_total_rating',true );
			$serviceFeedbacks		= get_post_meta( $post_id , '_service_feedbacks',true );
			$queu_services			= workreap_get_services_count('services-orders',array('hired'),$post_id);
			$serviceTotalRating		= !empty( $serviceTotalRating ) ? $serviceTotalRating : 0;
			$serviceFeedbacks		= !empty( $serviceFeedbacks ) ? intval( $serviceFeedbacks ) : 0;
		} else {
			$serviceTotalRating	= 0;
			$serviceFeedbacks	= 0;
			$queu_services		= 0;
		}
		if( !empty( $serviceTotalRating ) || !empty( $serviceFeedbacks ) ) {
			$serviceTotalRating	= $serviceTotalRating / $serviceFeedbacks;
		} else {
			$serviceTotalRating	= 0;
		}
		
		$serviceTotalRating 		= number_format((float) $serviceTotalRating, 1);
		
		if ( $itme_type === 'v1' ) {?>
			<li><span><i class="fa fa-star"></i></i> <?php echo esc_html($serviceTotalRating);?>/<?php esc_html_e('5','workreap');?> (<?php echo intval( $serviceFeedbacks );?>&nbsp;<?php esc_html_e('Feedback','workreap');?>)</span></li>
		<?php } elseif( $itme_type === "v2" ) { ?>
			<li><span><i class="fa fa-star"></i><?php echo esc_html($serviceTotalRating);?>/<?php esc_html_e('5','workreap');?> (<?php echo intval( $serviceFeedbacks );?>)</span></li>
		<?php }elseif( $itme_type === "v3" ) { ?>
			<li><i class="fa fa-star icon-yellow"></i><em> <?php echo esc_html($serviceTotalRating);?> </em> <span>(<?php echo intval( $serviceFeedbacks );?>&nbsp;<?php esc_html_e('Reviews','workreap');?>)</span></li>
		<?php } ?>
			<?php if( empty($itme_type) || $itme_type != "v3" ) {?>
				<li><span><i class="fa fa-list-ol"></i><?php echo intval( $queu_services );?>&nbsp;<?php esc_html_e('in Queue','workreap');?></span></li>
		<?php }
		
        echo ob_get_clean();       
    }
    add_action('workreap_service_get_reviews', 'workreap_service_get_reviews', 10 , 2);
}

/**
 * Print Service short des
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_shortdescription' ) ){
    function workreap_service_shortdescription( $post_id = '',$linked_profile ){              
		ob_start();
		$freelancer_avatar = apply_filters(
									'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
								);
		$freelancer_title 		= get_the_title( $linked_profile );	
		$service_title			= get_the_title( $post_id );
		$service_url			= get_the_permalink( $post_id );
		$linked_user			= workreap_get_linked_profile_id($linked_profile,'post');
		?>
			<div class="wt-freelancers-details">
				<?php if( !empty( $freelancer_avatar ) ){?>
					<figure class="wt-freelancers-img">
						<img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php esc_attr_e('Service ','workreap');?>">
						<?php do_action('workreap_print_user_status',$linked_user);?>
					</figure>
				<?php }?>
				<?php do_action( 'workreap_save_services_html', $post_id,'v2' );?>
				<div class="wt-freelancers-content">
					<div class="dc-title">
						<?php do_action( 'workreap_get_verification_check', $linked_profile, $freelancer_title ); ?>
						<a href="<?php echo esc_url($service_url);?>"><h3><?php echo esc_html( $service_title );?></h3></a>
						<?php do_action('workreap_service_price_html',$post_id);?>
					</div>
				</div>
				<div class="wt-freelancers-rating">
					<ul><?php do_action('workreap_service_get_reviews',$post_id,'v2');?></ul>
				</div>
			</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_service_shortdescription', 'workreap_service_shortdescription', 10 , 2);
}
/**
 * Print Service short des V2
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_shortdescriptionv2' ) ){
    function workreap_service_shortdescriptionv2( $post_id = '',$linked_profile ){              
		$freelancer_avatar = apply_filters(
									'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
								);
		
		$freelancer_title 		= workreap_get_username('',$linked_profile);	
		$service_title			= get_the_title( $post_id );
		$service_url			= get_the_permalink( $post_id );
		$linked_user			= workreap_get_linked_profile_id($linked_profile,'post');
		
		$services_views_count   = get_post_meta($post_id, 'services_views', true);
		$services_views_count	= !empty($services_views_count) ? intval($services_views_count) : 0;
		?>
		<div class="wt-bestservice__content">
			<?php if( !empty( $freelancer_avatar ) ){?>
				<div class="wt-bestservice__user">
					<figure class="template-content">
						<img src="<?php echo esc_url($freelancer_avatar);?>" alt="<?php echo esc_attr($freelancer_title);?>">
						<?php do_action('workreap_print_user_status',$linked_user);?>
					</figure>
				</div>
			<?php }?>
			<div class="wt-cards__title">
				<?php if( !empty($freelancer_title) ){?>
					<a href="<?php echo esc_url(get_the_permalink( $linked_profile ));?>"><?php echo esc_html($freelancer_title);?></a>
				<?php } ?>
				<?php if( !empty($service_title) ){?>
					<h5><a href="<?php echo esc_url($service_url);?>"><?php echo esc_html($service_title);?></a></h5>
				<?php } ?>
				<ul class="wt-rateviews">
					<?php do_action('workreap_service_get_reviews',$post_id,'v3');?>
					<?php if( !empty($services_views_count) ){?>
						<li><i class="fa fa-eye"></i> <span> <?php echo intval($services_views_count);?> </span></li>
					<?php } ?>
				</ul>
				<?php do_action('workreap_service_price_html',$post_id,'','v2');?>
			</div>
			<?php do_action( 'workreap_save_services_html', $post_id,'v2' );?>
		</div>
		<?php       
    }
    add_action('workreap_service_shortdescriptionv2', 'workreap_service_shortdescriptionv2', 10 , 2);
}

/**
 * Print portfolio desc
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_portfolio_shortdescription' ) ){
    function workreap_portfolio_shortdescription( $post_id = '',$linked_profile ){              
		ob_start();
		$freelancer_avatar = apply_filters(
									'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
								);
		
		$freelancer_title 		= get_the_title( $linked_profile );	
		$portfolio_title		= get_the_title( $post_id );
		$portfolio_url			= get_the_permalink( $post_id );
		$linked_user			= workreap_get_linked_profile_id($linked_profile,'post');
		?>
		<div class="wt-freelancers-details">
			<?php if( !empty( $freelancer_avatar ) ){?>
				<figure class="wt-freelancers-img">
					<img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php esc_attr_e('portfolio ','workreap');?>">
					<?php do_action('workreap_print_user_status',$linked_user);?>
				</figure>
			<?php }?>
			<div class="wt-freelancers-content">
				<div class="dc-title">
					<?php do_action( 'workreap_get_verification_check', $linked_profile, $freelancer_title ); ?>
					<h3><a class="modal-link" href="<?php echo esc_url($portfolio_url);?>"><?php echo esc_html( $portfolio_title );?></a></h3>
				</div>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_portfolio_shortdescription', 'workreap_portfolio_shortdescription', 10 , 2);
}

/**
 * Print Service listing basic
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_listing_basic' ) ){
    function workreap_service_listing_basic( $post_id = '',$class ='' ,$service_detail = '',$order=''){              
		ob_start();
		$db_docs		= array();

		if (function_exists('fw_get_db_post_option')) {
			$db_docs   	= fw_get_db_post_option($post_id,'docs');
		}
		
		$service_title	= get_the_title($post_id);
		$service_link	= get_the_permalink($post_id);
		$featured_img	= get_the_post_thumbnail_url($post_id,array(100,100));
		$post_status	= get_post_status( $post_id );

		if ( function_exists('fw_get_db_post_option' ) && empty($featured_img)) {
			$featured_img_default    	= fw_get_db_settings_option('default_service_banner');
			if(!empty($featured_img_default['attachment_id'])){
				$featured_img		=  workreap_prepare_image_source($featured_img_default['attachment_id'],100,100);;
			}
		}
		
		$service_downloadable		= get_post_meta( $post_id, '_downloadable', true);
		?>
		<div class="wt-service-tabel <?php echo esc_attr( $class );?>">
			<?php if( !empty( $featured_img ) ){ ?>
				<figure><img src="<?php echo esc_url( $featured_img );?>" alt="<?php echo esc_attr($service_title);?>"></figure>
			<?php } ?>
			<div class="wt-freelancers-content">
				<div class="dc-title">
					<?php do_action('workreap_service_print_featured', $post_id); ?>
					<h3><a target="_blank" href="<?php echo esc_url( $service_link );?>"><?php echo esc_html( $service_title );?></a></h3>
					<?php do_action('workreap_service_price_html',$post_id,$order);?>
					<?php do_action('workreap_service_type_html',$post_id);?>
				</div>
				<?php if( !empty( $service_detail ) && $post_status === 'published' ) {?>
					<div class="wt-rightarea">
						<a href="<?php echo esc_url( $service_link );?>" class="wt-btn"><?php esc_html_e('Service Details','workreap');?></a>
					</div>
				<?php } elseif( $service_detail === 'show_details') {?>
				<?php 
					if( !empty( $service_downloadable ) && $service_downloadable === 'yes' ){ 
						$downloadable_files		= get_post_meta( $post_id, '_downloadable_files', true);
						$downloadable_files		= !empty( $downloadable_files ) ? $downloadable_files : array();
						if( !empty( $downloadable_files ) ){ ?>
							<div class="wt-rightarea">
								<a class="wt-btn wt-download-files-doenload" data-id="<?php echo intval($post_id);?>" href="#"><?php esc_html_e('Download','workreap');?></a>
							</div>
						<?php } ?>	
				<?php } ?>
			<?php } ?>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_service_listing_basic', 'workreap_service_listing_basic', 10 , 4);
}

/**
 * Print Service listing basic
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_quote_listing_basic' ) ){
    function workreap_quote_listing_basic( $service_id = '',$quote_id='',$declined=false){              
		ob_start();
		$service_title	= get_the_title($service_id);
		$reason			= get_post_meta($quote_id,'reason',true);
		$service_link	= get_the_permalink($service_id);
		$featured_img	= get_the_post_thumbnail_url($service_id,array(100,100));
		$user_price 	= get_post_meta($quote_id, 'price', true);
		?>
		<div class="wt-service-tabel">
			<?php if( !empty( $featured_img ) ){ ?>
				<figure><img src="<?php echo esc_url( $featured_img );?>" alt="<?php echo esc_attr($service_title);?>"></figure>
			<?php } ?>
			<div class="wt-freelancers-content">
				<div class="dc-title">
					<h3><a target="_blank" href="<?php echo esc_url( $service_link );?>"><?php echo esc_html( $service_title );?></a></h3>
					<span><?php esc_html_e('Offered price','workreap');?>
					<strong><?php echo workreap_price_format( $user_price );?></strong></span>
					<?php if(!empty($declined) && !empty($reason)){?>
						<div class="decline-reason">
							<a href="#" class="wt-ratinginfo wt-quote-details" data-description="<?php echo esc_attr($reason);?>" data-toggle="modal" data-target="#wt-projectmodalbox">
								<i class="fa fa-question-circle-o"></i>
							</a>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_quote_listing_basic', 'workreap_quote_listing_basic', 10 , 3);
}

/**
 * Print Service employer html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_employer_html' ) ){
    function workreap_service_employer_html( $employer_id = '' ){              
		ob_start();
		$profile_id			= workreap_get_linked_profile_id($employer_id);
		$employer_title		= get_the_title($profile_id);
		$tagline			= workreap_get_tagline($profile_id);
		$employer_avatar 	= apply_filters(
								'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id), array('width' => 100, 'height' => 100) 
							);
		
		?>
		<div class="wt-userlistingsingle">
			<figure class="wt-userlistingimg"><img src="<?php echo esc_url( $employer_avatar ); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>"></figure>
			<div class="wt-userlistingcontent">
				<div class="wt-contenthead wt-followcomhead">
					<div class="wt-title">
						<?php do_action('workreap_get_verification_check',$profile_id,$employer_title);?>
						<?php if( !empty( $tagline ) ) {?>
							<h3><?php echo esc_html( $tagline);?></h3>
						<?php }?>
					</div>

				</div>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_service_employer_html', 'workreap_service_employer_html', 10 , 1);
}

/**
 * Print Service freelancer html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_freelancer_html' ) ){
    function workreap_service_freelancer_html( $freelancer_id = '' ){              
		ob_start();
		$profile_id				= workreap_get_linked_profile_id($freelancer_id);
		$freelancer_title		= get_the_title($profile_id);
		$tagline				= workreap_get_tagline($profile_id);
		$freelancer_avatar 	= apply_filters(
								'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array('width' => 100, 'height' => 100), $profile_id ), array( 'width' => 100, 'height' => 100 ) 
							);
		
		?>
		<div class="wt-userlistingsingle">
			<figure class="wt-userlistingimg"><img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php esc_attr_e('freelancer', 'workreap'); ?>"></figure>
			<div class="wt-userlistingcontent">
				<div class="wt-contenthead wt-followcomhead">
					<div class="wt-title">
						<?php do_action('workreap_get_verification_check',$profile_id,$freelancer_title);?>
						<?php if( !empty( $tagline ) ) {?>
							<h3><?php echo esc_html( $tagline );?></h3>
						<?php }?>
					</div>

				</div>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_service_freelancer_html', 'workreap_service_freelancer_html', 10 , 1);
}

/**
 * Print Service listing basic
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_price_html' ) ){
    function workreap_service_price_html( $post_id = '',$order_id='',$type='' ){              
		ob_start();
		$db_price		= 0;
		
		if(!empty($order_id)){

			if ( get_post_status( $order_id ) ){
				$order = new WC_Order($order_id);
				$db_price	= $order->get_total();
			}else{
				$db_price   = fw_get_db_post_option($post_id,'price');
			}
			
			$price_tag	= esc_html__( 'Order total:','workreap' );
		}else{
			if (function_exists('fw_get_db_post_option')) {
				$db_price   = fw_get_db_post_option($post_id,'price');
			}
			
			$price_tag	= esc_html__( 'Starting from:','workreap' );
		}
		
		if( !empty($type) && $type == 'v2' && !empty( $db_price )){ 
			$currency			= workreap_get_current_currency();
			$currency_symbol 	= !empty( $currencies['symbol'] ) ? $currencies['symbol'] : '$';?>
			<div class="wt-startingprice order_id-<?php echo esc_html($order_id);?>">
				<i><?php echo esc_html($price_tag);?></i>
				<span><?php echo workreap_price_format( $db_price );?></span>
			</div>
		<?php }else if( !empty( $db_price ) ){?>
			<span class="order_id-<?php echo esc_html($order_id);?>"><?php echo esc_html($price_tag);?>&nbsp;<strong><?php echo workreap_price_format( $db_price );?></strong></span>
		<?php 
		}
        echo ob_get_clean();       
    }
    add_action('workreap_service_price_html', 'workreap_service_price_html', 10 , 3);
}

/**
 * Print Service Type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_service_type_html' ) ){
    function workreap_service_type_html( $post_id = '' ){              
		ob_start();

		if (function_exists('fw_get_db_post_option')) {
			$db_downloadable   	= fw_get_db_post_option($post_id,'downloadable');
		}
		
		$db_downloadable	= !empty( $db_downloadable ) && $db_downloadable !== 'no' ? $db_downloadable : '';
		
		if( !empty( $db_downloadable ) ){?>
			<span class="wt-downladable"><i data-tipso="<?php esc_attr_e( 'Downloadable','workreap' )?>" class="fa fa-download tipso_style wt-tipso"></i></span>
		<?php 
		}
        echo ob_get_clean();       
    }
    add_action('workreap_service_type_html', 'workreap_service_type_html', 10 , 1);
}

/**
 * Print service rating by rating value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_get_service_rating' ) ){
    function workreap_freelancer_get_service_rating($post_id = '' ){              
		ob_start(); 
		$service_ratings	= get_post_meta($post_id,'_hired_service_rating',true);
		$service_ratings	= !empty( $service_ratings ) ? $service_ratings : 0;

		?>
		<div class="user-stars-v2">
			<?php do_action('workreap_freelancer_single_service_rating', $service_ratings,'' ); ?>
			<a href="#" onclick="event_preventDefault(event);" data-id="<?php echo intval( $post_id );?>" class="wt-ratinginfo wt-rating-details" data-toggle="modal" data-target="#wt-projectmodalbox">
				<i class="fa fa-question-circle-o"></i>
			</a>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_freelancer_get_service_rating', 'workreap_freelancer_get_service_rating', 10 , 1);
}

/**
 * Print service rating by rating value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_freelancer_single_service_rating' ) ){
    function workreap_freelancer_single_service_rating( $rating = '',$post_id='' ){              
		ob_start(); 
		$rating			= !empty($rating) ? $rating : 0.0;
		$round_rate 	= number_format((float) $rating, 1);
		$rating_average	= ( $round_rate / 5 )*100;
		$rating_headings 	= workreap_project_ratings('services_ratings');
		?>
		<span class="wt-stars">
			<span style="width: <?php echo esc_attr($rating_average);?>%;"></span>
		</span>
		<?php if( !empty( $rating_headings ) && !empty( $post_id ) ) {?>
			<i class="fa fa-exclamation-circle"></i>
			<div class="wt-overallrating">
				<ul class="wt-servicesrating">
				<?php 
				foreach ( $rating_headings  as $key => $item ) {
					$saved_projects     = get_post_meta($post_id, $key, true);
					if( !empty( $saved_projects ) ) {
						$percentage	= $saved_projects * 20;
					?>
					<li>
						<span class="wt-stars"><span style="width:<?php echo esc_attr( $percentage );?>%;"></span></span>
						<em><?php echo esc_html( $item );?></em>
					</li>
					<?php }}?>
				</ul>
			</div>
		<?php }?>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_freelancer_single_service_rating', 'workreap_freelancer_single_service_rating', 10 , 2);
}

/**
 * add attachments on comments meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_show_comments_attachments')) {
	function workreap_show_comments_attachments ( $comment_text ) {

		if( is_admin() ){
			$project_files  = get_comment_meta( get_comment_ID(), 'message_files', true);
			if( !empty( $project_files ) ){ 
				$attachments = '<a href="#" onclick="event_preventDefault(event);" data-id="'.esc_attr( get_comment_ID()).'" class="wt-btn wt-attachmentbtn wt-download-attachment"><i class="lnr lnr-download"></i>'.esc_html__("Attachment(s)", "workreap").'</a><br>';
				return $attachments . $comment_text;
			} else {
				return $comment_text;
			}
		} else {
			return $comment_text;
		}

	}

	add_filter( 'comment_text', 'workreap_show_comments_attachments' );
}

/**
 * @check Application access
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_system_access')) {

    function workreap_system_access( $access_type = '') {
		if (function_exists('fw_get_db_settings_option')) {
        	$application_access = fw_get_db_settings_option('application_access');
		}
		
		$application_access	= !empty( $application_access ) ? $application_access : '';
		
		if( !empty( $application_access ) && !empty( $access_type ) ) {
			if( $application_access === $access_type || $application_access === 'both') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
    }
	add_filter('workreap_system_access', 'workreap_system_access', 10 , 1);
}

/**
**
 * @check Select featured Service for freelancer
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_featured_service')) {

    function workreap_featured_service( $user_id ) {
		$featured_services		= workreap_get_subscription_metadata( 'wt_featured_services',$user_id );
		$featured_services		= !empty( $featured_services ) ? intval( $featured_services ) : '';
		
		$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$user_id );
		$expiry_string		= !empty( $expiry_string ) ? intval( $expiry_string ) : 0;
		
        if ( !empty($featured_services >= 1) ) {
			return true;
        } else {
            return false;
        }
    }
	add_filter('workreap_featured_service', 'workreap_featured_service', 10 , 1);
}

/**
 * @init users online status
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_online_init')) {
	add_action('init', 'workreap_online_init');
	add_action('admin_init', 'workreap_online_init');
	function workreap_online_init(){
		$logged_in_users = get_transient('users_online_status'); 
		$user = wp_get_current_user(); //Get the current user's data
		
		if ( !isset($logged_in_users[$user->ID]['last']) || $logged_in_users[$user->ID]['last'] <= time() - 300 ){
			$logged_in_users[$user->ID] = array(
				'id' 		=> $user->ID,
				'username' 	=> $user->user_login,
				'last' 		=> time(),
			);
			
			set_transient('users_online_status', $logged_in_users, 300);
		}
	}
}

/**
 * @logout users online status update
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_logout_init')) {
	add_action('wp_logout', 'workreap_logout_init');
	function workreap_logout_init(){
		$logged_in_users = get_transient('users_online_status'); 
		$user = wp_get_current_user(); //Get the current user's data
		
		if( !empty( $user->ID ) ){
			if( !empty( $logged_in_users[$user->ID] ) ){
				unset($logged_in_users[$user->ID]);
				set_transient('users_online_status', $logged_in_users, 300);
			}
		}
		
		//redirect to home page
		$redirect_url = home_url('/');
		wp_safe_redirect( $redirect_url );
		exit;
	}
}
/**
 * @Check if user is online
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_user_online')) {
	add_filter('workreap_is_user_online','workreap_is_user_online',10,1);
	function workreap_is_user_online($id){	
		$logged_in_users = get_transient('users_online_status'); 
		return isset($logged_in_users[$id]['last']) && $logged_in_users[$id]['last'] > time() - 300;
	}
}

/**
 * @get user last login
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_last_login')) {
	add_action('workreap_user_last_login','workreap_user_last_login',10,1);
	function workreap_user_last_login($id){
		$logged_in_users = get_transient('users_online_status'); 

		if ( isset($logged_in_users[$id]['last']) ){
			return $logged_in_users[$id]['last'];
		} else {
			return false;
		}
	}	
}

/**
 * @Online status html
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_print_user_status')) {
	add_action('workreap_print_user_status','workreap_print_user_status',10,2);
	add_filter('workreap_print_user_status','workreap_print_user_status',10,2);
	function workreap_print_user_status($id,$return='no'){	
		$is_online	= apply_filters('workreap_is_user_online',$id);
		$online		= ''; 

		if( $is_online === true ){
			$online		= '<div class="wt-userdropdown wt-online template-content tipso_style wt-tipso" data-tipso="'.esc_html__('Online','workreap').'" ></div>';
		} else{
			$online		= '<div class="wt-userdropdown wt-away template-content tipso_style wt-tipso" data-tipso="'.esc_html__('Offline','workreap').'"></div>';
		}
		
		$html	= apply_filters('workreap_fetch_online_status',$online);
		
		if (function_exists('fw_get_db_settings_option')) {
			$hide_status = fw_get_db_settings_option('hide_status', $default_value = null);
			if( $hide_status === 'show' ){
					if( $return === 'yes' ){
					return $html;
				} else{
					echo do_shortcode( $html );
				}
			}
		}
	}
}

/**
 * @System access type
  *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_is_listing_free')) {
	add_filter('workreap_is_listing_free','workreap_is_listing_free',10,2);
	function workreap_is_listing_free($option,$user_id){	
		if (function_exists('fw_get_db_settings_option')) {
			$system_access = fw_get_db_settings_option('system_access', $default_value = null);
			if( isset( $system_access ) && $system_access === 'both' ){
				return true;
			} elseif( isset( $system_access ) && $system_access === 'paid' ){
				return false;
			} elseif( isset( $system_access ) && $system_access === 'employer_free' ){
				$user_type	= workreap_get_user_type( $user_id );
				if( isset( $user_type ) && $user_type === 'employer' ){
					return true;
				} else{
					return false;
				}
			} elseif( isset( $system_access ) && $system_access === 'freelancer_free' ){
				$user_type	= workreap_get_user_type( $user_id );
				if( isset( $user_type ) && $user_type === 'freelancer' ){
					return true;
				} else{
					return false;
				}
			} else{
				return false;
			}
		}
		
		return $option;
		
	}
}

/**
 * @get tooltip settings
 * @return 
 */
if (!function_exists('workreap_get_tooltip')) {
	function workreap_get_tooltip($type,$element){
		if( empty( $element ) ){return;}
		$type	= !empty( $type ) ? $type : 'element';	
		$tipso =  true;
		
		if (is_page_template('directory/dashboard.php') || $tipso === true ) {
			if (function_exists('fw_get_db_settings_option')) {
				$data = fw_get_db_settings_option('tip_'.$element, $default_value = null);
				if( !empty( $data[0]['content'] ) ){
					$title		= !empty( $data[0]['title'] ) ?  $data[0]['title'] : '';
					$content	= !empty( $data[0]['content'] ) ?  $data[0]['content'] : '';

					if( !empty( $content ) ){?>
						<span class="wt-<?php echo esc_attr( $type );?>-hint"><i data-tipso-title="<?php echo esc_attr( $title );?>" data-tipso="<?php echo esc_attr( $content );?>" class="fa fa-question-circle template-content tipso_style wt-tipso"></i></span>
					<?php 
					}

				}
			}
		}
	}
	add_action('workreap_get_tooltip', 'workreap_get_tooltip',10,2);
}

/**
 * @get search page map
 * @return html
 */
if (!function_exists('workreap_get_search_toggle_map')) {

    function workreap_get_search_toggle_map($classes='wt-mapvone') {
		$dir_map_scroll = 'false';
		if (function_exists('fw_get_db_settings_option')) {
			$dir_map_scroll = fw_get_db_settings_option('dir_map_scroll');
			$search_page_map = fw_get_db_settings_option('search_page_map');
		}
		
		if( isset( $search_page_map ) && $search_page_map === 'enable' ){
			$dir_map_scroll	= isset( $dir_map_scroll ) && $dir_map_scroll === 'true' ? 'unlock' : 'lock';
			ob_start();
			?>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-left">
					<div id="sp-search-map" class="wt-map <?php echo esc_attr( $classes );?>"></div>
					<div class="wt-mapcontrols">
						<span id="doc-lock"><i class="fa fa-<?php echo esc_attr( $dir_map_scroll );?>"></i></span>
					</div>
				</div>
			<?php
			echo ob_get_clean();
		}
    }

    add_action('workreap_get_search_toggle_map', 'workreap_get_search_toggle_map',10,1);
}

/**
 * @Price range filter services
 * @return link
 */
if (!function_exists('workreap_print_price_range')) {
	function workreap_print_price_range($format='', $amountbox_position = 'default', $custom_price = 'enable', $text = '', $title = '') {
		if (function_exists('fw_get_db_settings_option')) {
			$price_filter_start = fw_get_db_settings_option('price_filter_start');
			$price_filter_end 	= fw_get_db_settings_option('price_filter_end',$default_value = 1000);
		}

		$price_filter_start	= !empty( $price_filter_start ) ? $price_filter_start : 0;
		$price_filter_end	= !empty( $price_filter_end ) ? $price_filter_end : 1000;
		
		$minprice = !empty($_GET['minprice']) ? esc_attr($_GET['minprice']) : $price_filter_start;
		$maxprice = !empty($_GET['maxprice']) ? esc_attr($_GET['maxprice']) : $price_filter_end;
		$currencies	= workreap_get_current_currency();
		$currency_symbol 	  = !empty( $currencies['symbol'] ) ? $currencies['symbol'] : '$';
		$title	= !empty($title) ? $title : esc_html__('Price Range:','workreap');
		$current_val		  = apply_filters('workreap_price_format',$minprice,'return').' - '.apply_filters('workreap_price_format',$maxprice,'return');
		
		ob_start(); 
		?>
		<div class="wt-widget wt-effectiveholder data-currency" data-currency="<?php echo esc_attr($currency_symbol);?>">
			<div class="wt-widgettitle">
				<h2><?php echo esc_html($title); ?></h2>
			</div>
			<div class="wt-widgetcontent">
				<div class="wt-formtheme wt-formsearch">
					<fieldset>
						<?php if($amountbox_position == 'top') { ?>
							<div class="wt-amountbox">
								<input type="text" value="<?php echo esc_attr( $current_val );?>" id="wt-consultationfeeamount" readonly>
								<?php if($custom_price == 'enable') { ?>
									<span class="lnr lnr-pencil custom-price-edit"></span>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if(!empty($text)) { ?>
							<span><?php echo esc_html($text); ?></span>
						<?php } ?>
						<div class="form-group">
							<div class="starts_from wt-themerangeslider" id="starts_from"></div>   
						</div>
						<?php if($amountbox_position == 'default') { ?>
							<div class="wt-amountbox">
								<input type="text" value="<?php echo esc_attr( $current_val );?>" id="wt-consultationfeeamount" readonly>
								<span class="lnr lnr-pencil custom-price-edit"></span>
							</div>
						<?php } ?>
						<div class="offer-filter wt-hide-form">
							<input type="text" name="minprice" class="ca-minprice" value="<?php echo esc_attr($minprice); ?>">
							<input type="text" name="maxprice" class="ca-maxprice" value="<?php echo esc_attr($maxprice); ?>">
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	 <?php
		$script = "jQuery(document).on('ready', function () { 
			var initialValue = '".$maxprice."';
			var sliderTooltip = function(event, ui) {
				var curValue = ui.value || initialValue;
				var curValue_1 = ui.values[0] || 0;
				var curValue_2 = ui.values[1] || '".$maxprice."';
				jQuery('.ca-minprice').val(curValue_1);
				jQuery('.ca-maxprice').val(curValue_2);
				var currency	= jQuery('.data-currency').data('currency');
				jQuery( '#wt-consultationfeeamount' ).val( currency_pos(currency,curValue_1)+ ' - '+ currency_pos(currency,curValue_2) );
			}
			
			jQuery( '#starts_from' ).slider({
			   range: true,
			   min:0,
			   max:'".$maxprice."',
			   values: [ ".esc_attr( $minprice ).",".esc_attr( $maxprice )." ],
			   animate:'slow',
			   orientation: 'horizontal',
			   slide: sliderTooltip,
			   stop:function(event,ui){
					jQuery('.tooltip-wrap').show();
				}
			});

		 });";
		 wp_add_inline_script('workreap-callbacks', $script, 'after');
	 echo ob_get_clean(); 

    }

    add_action('workreap_print_price_range', 'workreap_print_price_range', 10, 5);
}

/**
 * @Price range filter services
 * @return link
 */
if (!function_exists('workreap_print_jobs_price_range')) {
    function workreap_print_jobs_price_range($format) {
		$currencies	= workreap_get_current_currency();
		if (function_exists('fw_get_db_settings_option')) {
			$price_filter_start = fw_get_db_settings_option('price_filter_start');
			$price_filter_end 	= fw_get_db_settings_option('price_filter_end',$default_value = 1000);
		}

		$price_filter_start	= !empty( $price_filter_start ) ? $price_filter_start : 1;
		$price_filter_end	= !empty( $price_filter_end ) ? $price_filter_end : 1000;
		$currency_symbol 	= !empty( $currencies['symbol'] ) ? $currencies['symbol'] : '$';
		$minprice 			= !empty($_GET['minprice']) ? esc_attr($_GET['minprice']) : $price_filter_start;
		$maxprice 			= !empty($_GET['maxprice']) ? esc_attr($_GET['maxprice']) : $price_filter_end;
		$project_type		= !empty( $_GET['project_type'] ) ? $_GET['project_type'] : '';
		$current_val		= apply_filters('workreap_price_format',$minprice,'return').' - '.apply_filters('workreap_price_format',$maxprice,'return');
		$job_type 			= workreap_get_job_type();
		ob_start(); 
		?>
		<div class="wt-widget wt-effectiveholder wt-projecttype-holder data-currency" data-currency="<?php echo esc_attr($currency_symbol);?>">
			<div class="wt-widgettitle">
				<h2><?php esc_html_e('Project Type', 'workreap'); ?></h2>
				
			</div>
			<div class="wt-widgetcontent">
				<div class="wt-formtheme wt-formsearch">
					<fieldset>
						<?php if( !empty( $job_type ) ){?>
						<div class="wt-checkboxholder">
							<span class="wt-radio">
								<input id="all_type" <?php checked( $project_type, '' ); ?>  type="radio" name="project_type" value="">
								<label for="all_type"><?php echo esc_html__( 'All','workreap' );?></label>
							</span>
							<?php 
							foreach( $job_type as $key =>  $val ) {
								if( !empty( $project_type ) && $project_type === $key ) {
									$checked	='checked';
								} else {
									$checked	='';
								}
								?>
								<span class="wt-radio">
									<input id="<?php echo esc_attr( $key );?>" <?php echo esc_attr( $checked ) ;?> type="radio" name="project_type" value="<?php echo esc_attr( $key );?>">
									<label for="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></label>
								</span>
							<?php } ?>
							<div class="form-group">
								<div class="starts_from wt-themerangeslider" id="starts_from"></div>   
							</div>
							<div class="wt-amountbox">
								<input type="text" value="<?php echo esc_attr( $current_val );?>" id="wt-consultationfeeamount" readonly>
								<span class="lnr lnr-pencil custom-price-edit"></span>
							</div>
							<div class="offer-filter wt-hide-form">
								<input type="text" name="minprice" class="ca-minprice" value="<?php echo esc_attr($minprice); ?>">
								<input type="text" name="maxprice" class="ca-maxprice" value="<?php echo esc_attr($maxprice); ?>">
							</div>
							</div>
						<?php } ?>
						
					</fieldset>
				</div>
			</div>
		</div>
	 <?php
		$script = "jQuery(document).on('ready', function () { 
			var initialValue = '".$maxprice."';
			var sliderTooltip = function(event, ui) {
				var curValue = ui.value || initialValue;
				var curValue_1 = ui.values[0] || 0;
				var curValue_2 = ui.values[1] || '".$maxprice."';
				jQuery('.ca-minprice').val(curValue_1);
				jQuery('.ca-maxprice').val(curValue_2);
				var currency	= jQuery('.data-currency').data('currency');
				jQuery( '#wt-consultationfeeamount' ).val( currency_pos(currency,curValue_1)+ ' - '+ currency_pos(currency,curValue_2) );
			}
			
			jQuery( '#starts_from' ).slider({
			   range: true,
			   min:0,
			   max:'".$maxprice."',
			   values: [ ".esc_attr( $minprice ).",".esc_attr( $maxprice )." ],
			   animate:'slow',
			   orientation: 'horizontal',
			   slide: sliderTooltip,
			   stop:function(event,ui){
					jQuery('.tooltip-wrap').show();
				}
			});

		 });";
		 wp_add_inline_script('workreap-callbacks', $script, 'after');
	 echo ob_get_clean(); 

    }

    add_action('workreap_print_jobs_price_range', 'workreap_print_jobs_price_range');
}

/**
 * @Filter settings
 * @return link
 */
if (!function_exists('workreap_filter_settings')) {
	add_filter('workreap_filter_settings', 'workreap_filter_settings',10,2);
    function workreap_filter_settings($type,$key) {
		$settings	= 'enable';
		if( function_exists('fw_get_db_post_option') ){
			$settings = fw_get_db_settings_option($type.'_'.$key);
		}
		
		return $settings;
	}
}

/**
 * @Social media icons
 * @return link
 */
if (!function_exists('workreap_get_social_media_icons_list')) {
    function workreap_get_social_media_icons_list($settings='') {
        $list	= array(
			'facebook'	=> array(
				'title' 		=> esc_html__('Facebook Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Facebook Link', 'workreap'),
				'is_url'   		=> true,
				'icon'			=> 'fa fa-facebook',
				'classses'		=> 'wt-facebook',
				'color'			=> '#3b5998',
			),
			'twitter'	=> array(
				'title' 	=> esc_html__('Twitter Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Twitter Link', 'workreap'),
				'is_url'   		=> true,
				'icon'			=> 'fa fa-twitter',
				'classses'		=> 'wt-twitter',
				'color'			=> '#55acee',
			),
			'linkedin'	=> array(
				'title' 	=> esc_html__('Linkedin Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Linkedin Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-linkedin',
				'classses'		=> 'wt-linkedin',
				'color'			=> '#0177b5',
			),
			'skype'	=> array(
				'title' 	=> esc_html__('Skype ID?', 'workreap'),
				'placeholder' 	=> esc_html__('Skype ID', 'workreap'),
				'is_url'   	=> false,
				'icon'		=> 'fa fa-skype',
				'classses'		=> 'wt-skype',
				'color'			=> '#00aff0',
			),
			'pinterest'	=> array(
				'title' 	=> esc_html__('Pinterest Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Pinterest Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-pinterest-p',
				'classses'		=> 'wt-pinterestp',
				'color'			=> '#bd081c',
			),
			'tumblr'	=> array(
				'title' 	=> esc_html__('Tumblr Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Tumblr Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-tumblr',
				'classses'		=> 'wt-tumblr',
				'color'			=> '#36465d',
			),
			'instagram'	=> array(
				'title' 	=> esc_html__('Instagram Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Instagram Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-instagram',
				'classses'		=> 'wt-instagram',
				'color'			=> '#c53081',
			),
			'flickr'	=> array(
				'title' 	=> esc_html__('Flickr Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Flickr Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-flickr',
				'classses'		=> 'wt-flickr',
				'color'			=> '#ff0084',
			),
			'medium'	=> array(
				'title' 	=> esc_html__('Medium Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Medium Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-medium',
				'classses'		=> 'wt-medium',
				'color'			=> '#02b875',
			),
			'tripadvisor'	=> array(
				'title' 	=> esc_html__('Tripadvisor Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Tripadvisor Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-tripadvisor',
				'classses'		=> 'wt-tripadvisor',
				'color'			=> '#FF0000',
			),
			'wikipedia'	=> array(
				'title' 	=> esc_html__('Wikipedia Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Wikipedia Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-wikipedia-w',
				'classses'		=> 'wt-wikipedia',
				'color'			=> '#5a5b5c',
			),
			'vimeo'	=> array(
				'title' 	=> esc_html__('Vimeo Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Vimeo Link', 'workreap'),
				'is_url'  	 => true,
				'icon'		=> 'fa fa-vimeo',
				'classses'		=> 'wt-vimeo',
				'color'			=> '#00adef',
			),
			'youtube'	=> array(
				'title' 	=> esc_html__('Youtube Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Youtube Link', 'workreap'),
				'is_url'   	=> true,
				'icon'		=> 'fa fa-youtube',
				'classses'		=> 'wt-youtube',
				'color'			=> '#cd201f',
			),
			'whatsapp'	=> array(
				'title' 	=> esc_html__('Whatsapp Number?', 'workreap'),
				'placeholder' 	=> esc_html__('Whatsapp Number', 'workreap'),
				'is_url'   	=> false,
				'icon'		=> 'fa fa-whatsapp',
				'classses'		=> 'wt-whatsapp',
				'color'			=> '#0dc143',
			),
			'vkontakte'	=> array(
				'title' 	=> esc_html__('Vkontakte Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Vkontakte Link', 'workreap'),
				'is_url'   	=> false,
				'icon'		=> 'fa fa-vk',
				'classses'		=> 'wt-vkontakte',
				'color'			=> '#5A80A7',
			),
			'odnoklassniki'	=> array(
				'title' 	=> esc_html__('Odnoklassniki Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Odnoklassniki Link', 'workreap'),
				'is_url'    => true,
				'icon'		=> 'fa fa-odnoklassniki',
				'classses'		=> 'wt-odnoklassniki',
				'color'			=> '#f58220',
			),
			'tiktok'	=> array(
				'title' 	=> esc_html__('Tiktok Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Tiktok Link', 'workreap'),
				'is_url'    => true,
				'icon'		=> 'cfatiktok-brands',
				'classses'		=> 'wt-tiktok',
				'color'			=> '#F7004D',
			),
			'snapchat'	=> array(
				'title' 	=> esc_html__('Snapchat Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Snapchat Link', 'workreap'),
				'is_url'    => true,
				'icon'		=> 'fa fa-snapchat',
				'classses'		=> 'wt-snapchat',
				'color'			=> '#F7F400',
			),
			'twitch'	=> array(
				'title' 	=> esc_html__('Twitch Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Twitch Link', 'workreap'),
				'is_url'    => true,
				'icon'		=> 'fa fa-twitch',
				'classses'		=> 'wt-twitch',
				'color'			=> '#9347FF',
			),
			'discord'	=> array(
				'title' 	=> esc_html__('Discord Link?', 'workreap'),
				'placeholder' 	=> esc_html__('Discord Link', 'workreap'),
				'is_url'    => true,
				'icon'		=> 'fa fa-discord fa-desktop',
				'classses'		=> 'wt-discord',
				'color'			=> '#5560E9',
			),
		);

		$list	= apply_filters('workreap_exclude_social_media_icons',$list);

		if( !empty($settings) && $settings ==='yes' ) {
			$list	= wp_list_pluck($list,'title');
		}
		
		return $list;
    }
    add_filter('workreap_get_social_media_icons_list', 'workreap_get_social_media_icons_list', 10,1);
}

/**
 * @Filter settings
 * @return link
 */
if (!function_exists('workreap_filter_user_promotion')) {
	add_filter('workreap_filter_user_promotion', 'workreap_filter_user_promotion',10,1);
    function workreap_filter_user_promotion($default) {
		if( function_exists('fw_get_db_post_option') ){
			$default 	= fw_get_db_settings_option('user_marketing_promation_api_settings');
			$default	= !empty($default['gadget']) ? $default['gadget'] : 'disable';
		}
		
		return $default;
	}
}

/**
 * Print Protfolio listing basic
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_portfolio_listing_basic' ) ){
    function workreap_portfolio_listing_basic( $post_id = '', $class =''){              
		ob_start();

		$portfolio_title	= get_the_title($post_id);
		$portfolio_link		= get_the_permalink($post_id);
		$featured_img		= get_the_post_thumbnail_url($post_id, array(100,100));

		?>
		<div class="wt-service-tabel <?php echo esc_attr( $class );?>">
			<?php if( !empty( $featured_img ) ){ ?>
				<figure><img src="<?php echo esc_url( $featured_img );?>" alt="<?php echo esc_attr($portfolio_title);?>"></figure>
			<?php } ?>
			<div class="wt-freelancers-content">
				<div class="dc-title">
					<h3><a target="_blank" href="<?php echo esc_url( $portfolio_link );?>"><?php echo esc_html( $portfolio_title );?></a></h3>
					<div class="wt-description">	
						<p><?php echo wp_trim_words( get_the_content($post_id), 10 ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php 
		
        echo ob_get_clean();       
    }
    add_action('workreap_portfolio_listing_basic', 'workreap_portfolio_listing_basic', 10 , 3);
}

/**
 * @Filter Images
 * @return link
 */
if (!function_exists('workreap_service_video_img')) {
	function workreap_service_video_img() {
		$img = get_template_directory_uri().'/images/play-button.png';
		$img	= '<img src="'.$img.'"alt="'.esc_attr__('Play video','workreap').'" />';
		return $img;
	}
	add_filter( 'workreap_service_video_img', 'workreap_service_video_img' );
}

/**
 * @restrict content by role
 * @return link
 */
if (!function_exists('workreap_restict_content_by_role')) {
	function workreap_restict_content_by_role($role) {
		global $current_user,$post;
		if(empty($current_user->ID)){?>
			<div class="search-result-template wt-haslayout"><div class="wt-haslayout wt-freelancer-search wt-main-section"><div class="container"><div class="row">
				<?php 
					Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap'));
					get_footer();
					die;
				?>
			</div></div></div></div>
		<?php } else{
			$roles	= explode(',',$role);
			$user_role	= apply_filters('workreap_get_user_type', $current_user->ID );
			$linked_profile	= workreap_get_linked_profile_id( $current_user->ID );
			
			if( ( !empty($post->ID) && !empty($linked_profile) ) && $linked_profile == $post->ID ){
				$roles[] = 'freelancer';
			}
			
			if( is_user_logged_in() && in_array($user_role,$roles) ){
				 // show page
			} else{?>
				<div class="search-result-template wt-haslayout"><div class="wt-haslayout wt-freelancer-search wt-main-section"><div class="container"><div class="row">
					<?php 
						Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap'));
						get_footer();
						die;
					?>
				</div></div></div></div>
		<?php }
		}

	}
	add_action( 'workreap_restict_content_by_role', 'workreap_restict_content_by_role',10,1 );
}


/**
 * Share profile meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'workreap_add_share_meta' ) ) {
	add_action('wp_head','workreap_add_share_meta');
	function workreap_add_share_meta(){
		global $current_user, $wp_roles, $post;
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		if (is_page_template('directory/dashboard.php') || is_singular('freelancers') || is_singular('employers')) { 

				if( is_singular('employers')){
					$userType = 'employer';
					$link_id	= $post->ID;
				} else if( is_singular('freelancers')){
					$userType = 'freelancer';
					$link_id	= $post->ID;
				} else{
					$user_identity 	= $current_user->ID;
					$userType 		= apply_filters('workreap_get_user_type', $user_identity);
					$link_id		= workreap_get_linked_profile_id( $user_identity );
				}

				if ( $userType === 'employer' ){

					$avatar = apply_filters(
											'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 0100, 'height' => 100) 
										);
				} else{
					$avatar = apply_filters(
											'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
										);
				}
			
			if(empty($link_id)){return;}
			$post_object 	= get_post( $link_id );
			$content 	 	= wp_strip_all_tags( get_the_excerpt($post->ID) );
				?>
				<meta property="og:url" content="<?php echo get_the_permalink($link_id);?>" />
				<meta property="og:type" content="<?php echo esc_url(home_url('/')); ?>" />
				<meta property="og:title" content="<?php echo esc_attr($blogname);?>" />
				<meta property="og:description" content="<?php echo esc_attr($content); ?>" />
				<meta property="og:image" content="<?php echo esc_attr($avatar);?>" />

				<meta name="twitter:card" content="summary">
				<meta name="twitter:title" content="<?php echo get_the_title($link_id);?>">
				<meta name="twitter:description" content="<?php echo esc_attr($content); ?>">
				<meta name="twitter:image" content="<?php echo esc_attr($avatar);?>">
			<?php }elseif (is_singular('micro-services')) { 
				if(empty($post->ID)){return;}
				$post_object 	= get_post( $post->ID );
				$content 	 	= wp_strip_all_tags( get_the_excerpt($post->ID) );
				$avatar			= workreap_prepare_thumbnail($post->ID,225,225)
				?>
				<meta property="og:url" content="<?php echo get_the_permalink($post->ID);?>" />
				<meta property="og:type" content="<?php echo get_the_title($post->ID);?>" />
				<meta property="og:title" content="<?php echo get_the_title($post->ID);?>" />
				<meta property="og:description" content="<?php echo esc_attr($content); ?>" />
				<meta property="og:image" content="<?php echo esc_attr($avatar);?>" />

				<meta name="twitter:card" content="summary">
				<meta name="twitter:title" content="<?php echo get_the_title($post->ID);?>">
				<meta name="twitter:description" content="<?php echo esc_attr($content); ?>">
				<meta name="twitter:image" content="<?php echo esc_attr($avatar);?>">
				<div style="display: none" class="description"><?php echo esc_html($content); ?></div>
			<?php }?>
			<script>function event_preventDefault(e) {e.preventDefault();}</script>
		<?php 
		
		
	}
}

/**
 * @Disable Unyson Session
 * @return 
 */
if (!function_exists('workreap_disable_fw_use_sessions')) {
	add_filter('fw_use_sessions','workreap_disable_fw_use_sessions');
	function workreap_disable_fw_use_sessions(){
		return false;
	}
}

/**
 * @Add comment from admin side.
 * @return 
 */
if (!function_exists('workreap_admin_comment_reply')) {
	add_action('wp_insert_comment', 'workreap_admin_comment_reply', 10, 2);
	function workreap_admin_comment_reply($id, $comment) {
		if ( is_admin() && !empty($comment->user_id) && $comment->user_id == 1 ) {

			$post_id	= $comment->comment_post_ID;
			$feedback	= $comment->comment_content;
			$post_type	= get_post_type($post_id);

			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapSendDispute')) {
					$email_helper = new WorkreapSendDispute();
					$emailData = array();

					if( isset( $post_type ) && $post_type === 'services-orders' ){
						$project_id 				= get_post_meta( $post_id, '_service_id', true);
						$hired_freelance_id 		= get_post_field('post_author', $project_id);
						$freelancer_id				= workreap_get_linked_profile_id($hired_freelance_id);
						$employer_id				= get_post_field('post_author', $post_id);
					} else{
						$project_id 				= get_post_meta( $post_id, '_project_id', true);
						$freelancer_id 				= get_post_meta( $project_id, '_freelancer_id', true);
						$hired_freelance_id			= get_post_field('post_author', $post_id);
						$employer_id				= get_post_field('post_author', $project_id);
					}

					$job_title 					= esc_html( get_the_title($project_id) );
					$job_link 					= get_permalink($project_id);

					$employer_name 				= workreap_get_username($employer_id);
					$employer_email 			= get_userdata( $employer_id )->user_email;

					$freelancer_title 			= esc_html( get_the_title($freelancer_id));
					$freelancer_email 			= get_userdata( $hired_freelance_id )->user_email;

					$emailData['username'] 		= esc_html( $employer_name );
					$emailData['email_to'] 		= sanitize_email( $employer_email );
					$emailData['title'] 		= esc_html( $job_title );
					$emailData['link'] 			= esc_url( $job_link );
					$emailData['feedback']  	= $feedback;

					//employer email
					$email_helper->send_admin_feedback_on_project_history($emailData);

					//freelancer email
					$emailData['username']      = esc_html( $freelancer_title );
					$emailData['email_to']      = sanitize_email( $freelancer_email );

					$email_helper->send_admin_feedback_on_project_history($emailData);
					
					
					//Push notification
					$push	= array();
					$push['employer_id']		= $employer_id;
					$push['freelancer_id']		= $hired_freelance_id;
					$push['feedback']			= $feedback;
					
					$push['%username%']			= $employer_name;
					$push['%feedback%']			= $feedback;
					$push['%link%']				= $job_link;
					$push['%title%']			= $job_title;
					$push['type']				= 'admin_reply';
					
					//To employer
					do_action('workreap_user_push_notify',array($employer_id),'','pusher_admin_feedback_content',$push);
					
					//To freelancer
					$push['%username%']			= $freelancer_title;
					do_action('workreap_user_push_notify',array($hired_freelance_id),'','pusher_admin_feedback_content',$push);
					
				}
			}
		}
	}
}

/**
 * @Profile health update
 * @return 
 */
if (!function_exists('workreap_update_profile_strength')) {
	add_action('workreap_update_profile_strength','workreap_update_profile_strength',10,3);
	function workreap_update_profile_strength($key='',$status=false,$post_id=false){
		global $current_user, $wp_roles, $post;
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$strength_fields 	= fw_get_db_settings_option( 'profile_strength_fields');
		}

		$strength_fields_list	= wp_list_pluck( $strength_fields, 'field');
		$profile_strength	= array();
		
		if(!empty($post_id)){
			$linked_profile	= $post_id;
		}else{
			$linked_profile		= workreap_get_linked_profile_id($current_user->ID);
		}
		
		$get_profile_data	= get_post_meta($linked_profile, 'profile_strength',true);
		$get_profile_data	= !empty($get_profile_data) ? $get_profile_data : array();
		$get_profile_data_new	= !empty($get_profile_data['data']) && !empty($strength_fields_list) ?  array_intersect_key($get_profile_data['data'], array_flip ($strength_fields_list)) : array();
		
		if(!empty($get_profile_data)){
			$get_profile_data['data']	= $get_profile_data_new;
		}
		
		if(!empty($strength_fields)){
			$total_fields	= count($strength_fields);
			$get_profile_data['total']	= $total_fields;
			foreach($strength_fields as $field_key => $field ){
				if( !empty($field['field']) && $field['field'] == $key ){
					if( $status === true ){
						$percentage	= !empty($field['percentage']) ?  $field['percentage'] : 0;
						$get_profile_data['data'][$key]	= $percentage;
					}else{
						$get_profile_data['data'][$key]	= 0;
					}
				}
			}

			update_post_meta($linked_profile, 'profile_strength', $get_profile_data);
		}

	}
}

/**
 * @Profile health values
 * @return 
 */
if (!function_exists('workreap_profile_strength_html')) {
	add_action('workreap_profile_strength_html','workreap_profile_strength_html',10,2);
	function workreap_profile_strength_html($user_id='',$show=false){
		$get_profile_data	= get_post_meta($user_id, 'profile_strength',true);
		$titles	= array(
			'skills' 		=> esc_html__('Skills', 'workreap'),
            'tagline' 		=> esc_html__('Tagline', 'workreap'),
            'description' 	=> _x('Description', 'Description for profile stregth', 'workreap' ),
            'identity_verification' => esc_html__('Identity verified', 'workreap'),
            'avatar' 		=> esc_html__('Profile Picture', 'workreap'),
            'experience' 	=> esc_html__('Experience', 'workreap'),
		);
		
		$total	= !empty( $get_profile_data['data'] ) ? count( $get_profile_data['data'] ) : 0;
		$total_percentage	= !empty( $get_profile_data['data'] ) ? array_sum( $get_profile_data['data'] ) : 0;
		
		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$strength_fields 	= fw_get_db_settings_option( 'show_strength');
		}
		
		//hide it
		if($show){
			if(!empty($strength_fields) && $strength_fields === 'no'){ return;}
		}
		
		if(!empty( $get_profile_data['data'] )){?>
		<div class="wtprofile-strength-wrap toolip-wrapo data-tip-strength-<?php echo esc_attr($total);?>">
			<div class="profile-strength-percentage"><span><em><?php esc_html_e('Profile health', 'workreap');?></em>&nbsp;<?php echo esc_html($total_percentage);?>%</span></div>
			<?php
				foreach($get_profile_data['data'] as $key => $item){
					$percentage	= '';
					$content	= !empty($titles[$key]) ? 'data-tipso="'.$titles[$key].'"' : '';
					if(!empty($item)){
						$percentage	= 'wt-filled-area';
					}
					?>
					<span class="wt-strength-field wt-tipso <?php echo esc_html($percentage);?>" <?php echo do_shortcode($content);?>><em></em></span>
					<?php
				}
			?>
		</div>
		<?php
		}
	}
}

/**
 * Load footer contents
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_load_footer_contents' ) ){
	add_action('wp_footer', 'workreap_load_footer_contents');
	function workreap_load_footer_contents(){
		//Reset Model
		if (!empty($_GET['key']) &&
				( isset($_GET['action']) && $_GET['action'] == "reset_pwd" ) &&
				(!empty($_GET['login']) )
		) {
			do_action('workreap_reset_password_form');
		}
		
		if (!empty($_GET['key']) && !empty($_GET['verifyemail'])) {
			do_action('workreap_verify_user_account');
		}
		
		do_action('workreap_notification_render');
	}
}

/**
 * @Notification modal
 * @return 
 */
if (!function_exists('workreap_notification_render')) {
	add_action('workreap_notification_render', 'workreap_notification_render');
    function workreap_notification_render() {
		?>
		<div class="wt-uploadimages modal fade wt-uploadrating" id="wt-notification-modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="wt-modaldialog modal-dialog" role="document">
				<div class="wt-modalcontent modal-content">
					<div class="wt-boxtitle">
						<h2><?php esc_html_e('Notification detail','workreap');?> <i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
					</div>
					<div class="wt-modalbody modal-body" id="wt-notification-detail">
					</div>
				</div>
			</div>
		</div>
	<?php 
	}
}

/**
 * @Account verification
 * @return 
 */
if (!function_exists('workreap_verify_user_account')) {

    function workreap_verify_user_account() {
        global $wpdb;

        if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) { 
			
            $verify_key 	= esc_attr( $_GET['key'] );
            $user_email 	= esc_attr( $_GET['verifyemail'] );
			$user_email		= !empty($user_email) ?  str_replace(' ','+',$user_email) : '';
            $user_identity = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_email = %s", $user_email)); 

			if( !empty( $user_identity ) ){
				$confirmation_key = get_user_meta(intval( $user_identity ), 'confirmation_key', true);
				if ( $confirmation_key === $verify_key ) {
					$post_id	= workreap_get_linked_profile_id( intval( $user_identity ),'users' );
					
					update_user_meta( intval( $user_identity ), 'confirmation_key', '');
					update_post_meta( intval( $post_id ), '_is_verified', 'yes');
					update_user_meta( intval( $user_identity ), '_is_verified', 'yes' );
					
					$linked_id 	= get_user_meta( $user_identity, '_linked_profile',true );
					update_post_meta($linked_id,'_is_verified','yes');

					$account_types_permissions	= '';
					if ( function_exists( 'fw_get_db_settings_option' ) ) {
						$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
					}
					
					if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
						$switch_user_id	= get_user_meta($user_identity, 'switch_user_id', true); 
						$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';

						$switch_user_post_id	= workreap_get_linked_profile_id( intval( $switch_user_id ),'users' );
					
						update_user_meta( intval( $switch_user_id ), 'confirmation_key', '');
						update_post_meta( intval( $switch_user_post_id ), '_is_verified', 'yes');
						update_user_meta( intval( $switch_user_id ), '_is_verified', 'yes' );
						
						$switch_user_linked_id 	= get_user_meta( $switch_user_id, '_linked_profile',true );
						update_post_meta($switch_user_linked_id,'_is_verified','yes');
					}

					if (!is_user_logged_in()) {
						$script = "
							jQuery(document).on('ready', function () {
								jQuery.ajax({
									type: 'POST',
									url: scripts_vars.ajaxurl,
									data: {
										action	: 'workreap_login_user',
										id		: '".intval($user_identity)."',
										security	: scripts_vars.ajax_nonce
									},
									dataType: 'json',
									success: function (response) {
										jQuery('body').find('.wt-preloader-section').remove();
										if (response.type === 'success') {
											jQuery.sticky(scripts_vars.account_verification, {classList: 'success', speed: 200, autoclose: 10000 });
											if(response.redirect_url){
												window.location.replace(response.redirect_url);
											}
										}
									}
								});
							});";

						} else {
							$script = "jQuery.sticky(scripts_vars.account_verification, {classList: 'success', speed: 200, autoclose: 10000 });";
						}

					wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
				}
			}
        }
    }

    add_action('workreap_verify_user_account', 'workreap_verify_user_account');
}

/**
 * @Update post status email
 * @return 
 */
if (!function_exists('workreap_update_post_status_action')) {

    function workreap_update_post_status_action($post_id,$type) {
        global $wpdb,$current_user;
		$json	= array();

		if ( function_exists( 'fw_get_db_settings_option' ) ) {
			$update_true 	= fw_get_db_settings_option( 'update_status_'.$type);
		}
		
		if(!empty($post_id) && !empty($type) && !empty($update_true) && $update_true === 'yes'){
			//Send email to freelancer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapPostStatus')) {
					$email_helper = new WorkreapPostStatus();
					$emailData = array();
					  
					$update_post = array(
					  'ID'            => $post_id,
					  'post_status'   => 'pending',
					);

					// Update the post into the database
					wp_update_post( $update_post );
					
					$emailData['name'] 			= get_the_title($post_id);
					$emailData['email_to'] 		= esc_html( $current_user->user_email);
					$emailData['type'] 			= $type;
					
					if(!empty($type) && $type === 'freelancer' ){
						$emailData['post_link'] 	= admin_url('users.php?').'&status_id='.$current_user->ID;
						update_user_meta( $current_user->ID, '_is_verified', 'no' );
						update_post_meta( $post_id, '_is_verified', 'no' );
					}else if(!empty($type) && $type === 'project' ){
						$emailData['post_link'] 	= admin_url('edit.php?post_type=projects').'&status_id='.$post_id;
					} else if(!empty($type) && $type === 'service' ){
						$emailData['post_link'] 	= admin_url('edit.php?post_type=micro-services').'&status_id='.$post_id;
					}

					$email_helper->send($emailData);
				}
			}
		}
    }

    add_action('workreap_update_post_status_action', 'workreap_update_post_status_action',10,2);
}

/**
 * @Notification liisting
 * @return 
 */
if (!function_exists('workreap_push_notification_listings')) {
	add_action('workreap_push_notification_listings','workreap_push_notification_listings',10,2);
	function workreap_push_notification_listings($status,$show=-1){
		global $current_user;
		$args	= array( 'post_type' 		=> array('push_notifications'),
						 'posts_per_page'   => 50,
						 'orderby' 			=> "ID",
    					 'order' 			=> 'DESC',
						 'post_status' 			=> array('pending','draft'),
						 'suppress_filters' 	=> false,
						 'author'				=> $current_user->ID,
						 'ignore_sticky_posts' 	=> 1
					   );
		
		$notifications = get_posts($args);
		if( !empty( $notifications ) ){ 
			foreach( $notifications as $key => $notify  ){
				$date		= get_the_date( get_option( 'date_format' ), $notify->ID );
			?>
			<li class="notification-items" id="notify-<?php echo intval($notify->ID);?>">
				<a href="#" class="viewinfo-notification" onclick="event_preventDefault(event);" data-id="<?php echo intval($notify->ID);?>">
					<div class="wt-notification-title"><span><?php do_action('workreap_push_notification_excerpt',$notify->ID,true,100);?></span></div>
					<em><?php echo esc_html($date);?></em>
				</a>
			</li>
		<?php }
		}else{?>
			<li class="wt-notification-empty">
				<h5><span><?php esc_html_e('No recent notification found', 'workreap'); ?></span></h5>
			</li>
			<?php
		}
	}
}

/**
 * @Notification liisting
 * @return 
 */
if (!function_exists('workreap_push_notification_excerpt')) {
	add_filter('workreap_push_notification_excerpt','workreap_push_notification_excerpt',10,4);
	add_action('workreap_push_notification_excerpt','workreap_push_notification_excerpt',10,4);
	function workreap_push_notification_excerpt($notify_id,$excerpt=false,$length=100,$return=false){
		$content	= get_the_content( '', '', $notify_id );
		
		$freelancer_id 	= get_post_meta($notify_id, 'freelancer_id', true);
		$employer_id 	= get_post_meta($notify_id, 'employer_id', true);
		$project_id 	= get_post_meta($notify_id, 'project_id', true);
		$service_id 	= get_post_meta($notify_id, 'service_id', true);
		
		$dispute_raised_by 		= get_post_meta($notify_id, 'dispute_raised_by', true);
		$sender_id 				= get_post_meta($notify_id, 'sender_id', true);
		$dispute_against 		= get_post_meta($notify_id, 'dispute_against', true);
		$receiver_id 			= get_post_meta($notify_id, 'receiver_id', true);
		
		if(!empty($freelancer_id)){
			$linked_profile   	= workreap_get_linked_profile_id($freelancer_id);
			$freelancer_name   	= workreap_get_username($freelancer_id );
			$freelancer_link   	= get_the_permalink( $linked_profile );
			
			$content = str_replace("%name%", $freelancer_name, $content);
			$content = str_replace("%user_name%", $freelancer_name, $content);
			$content = str_replace("%freelancer_name%", $freelancer_name, $content); 
			$content = str_replace("%freelancer_link%", $freelancer_link, $content);
		}
		
		if(!empty($employer_id)){
			$linked_profile   	= workreap_get_linked_profile_id($employer_id);
			$employer_name   	= workreap_get_username($employer_id );
			$employer_link   	= get_the_permalink( $linked_profile );
			
			$content = str_replace("%name%", $employer_name, $content);
			$content = str_replace("%user_name%", $employer_name, $content);
			$content = str_replace("%employer_name%", $employer_name, $content); 
			$content = str_replace("%employer_link%", $employer_link, $content);
		}
		
		if(!empty($dispute_raised_by)){
			$user_name   		= workreap_get_username($dispute_raised_by );
			$content = str_replace("%dispute_raised_by%", $user_name, $content); 
		}
		
		
		if(!empty($sender_id)){
			$user_name   		= workreap_get_username( $sender_id );
			$content = str_replace("%sender_name%", $user_name, $content);
			$content = str_replace("%dispute_author%", $user_name, $content); 
			$content = str_replace("%name%", $user_name, $content); 
		}
		
		if(!empty($receiver_id)){
			$user_name   		= workreap_get_username( $receiver_id );
			$content = str_replace("%username%", $user_name, $content);
			$content = str_replace("%dispute_author%", $user_name, $content); 
			$content = str_replace("%name%", $user_name, $content); 
		}
		
		if(!empty($dispute_against)){
			$user_name   		= workreap_get_username($dispute_against );
			$content = str_replace("%dispute_against%", $user_name, $content); 
		}

		if(!empty($project_id)){
			$project_name   	= get_the_title($project_id );
			$project_link   	= get_the_permalink( $project_id );
			
			$content = str_replace("%project_link%", $project_link, $content); 
			$content = str_replace("%project_title%", $project_name , $content);
			
			$content = str_replace("%job_link%", $project_link, $content); 
			$content = str_replace("%job_title%", $project_name , $content);
		}

		if(!empty($service_id)){
			$service_name   	= get_the_title($service_id );
			$service_link   	= get_the_permalink( $service_id );
			
			$content = str_replace("%service_link%", $service_link, $content); 
			$content = str_replace("%service_title%", $service_name, $content);
			$content = str_replace("%link%", $service_link, $content); 
			$content = str_replace("%service_name%", $service_name, $content); 
		}
		
		if($return  === true){
			if($excerpt  === true){
				return mb_substr($content, 0, $length).'...';
			}else{
				return do_shortcode(nl2br($content));
			}
		}else{
		
			if($excerpt  === true){
				echo mb_substr($content, 0, $length).'...';
			}else{
				echo do_shortcode(nl2br($content));
			}
		}
	}
}

/*
 * Return Project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_print_project_tags' ) ){
    function workreap_print_project_tags( $post_id = '' ){
        if( !empty( $post_id ) ){
            $project_type = '';   
            if (function_exists('fw_get_db_post_option')) {
                $project_type = fw_get_db_post_option($post_id, 'project_type', true);
			}
			
			$classFeatured		= apply_filters('workreap_project_print_featured', $post_id,'yes');
			$project_type   	= !empty( $project_type['gadget'] ) ? $project_type['gadget'] : '';
			$project_type_class	= isset( $project_type ) && $project_type == 'hourly' ? 'wt-hourlytag' : 'wt-fixedtag';
			
			$project_type_text	= isset( $project_type ) && $project_type == 'hourly' ?  esc_html__('Hourly', 'workreap') : esc_html__('Fixed Price', 'workreap');
			ob_start();
			
			if( !empty($classFeatured) && $classFeatured == 'wt-featured' ){ ?>
				<a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Featured','workreap');?></a>
			<?php }
            if( !empty( $project_type ) ) { ?>
                <a href="#" onclick="event_preventDefault(event);" class="<?php echo esc_attr($project_type_class);?>"><?php echo esc_html($project_type_text);?></a>
			<?php }
			do_action( 'workreap_save_freelancer_html', $post_id,'v3' );
			
			echo ob_get_clean();
        }
    }
    add_action('workreap_print_project_tags', 'workreap_print_project_tags', 10, 1);
}

/*
 * Return Project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_signup_popup_search_results' ) ){
    function workreap_signup_popup_search_results( $signup_details = array() ,$class=''){
        if( !empty( $signup_details ) ){
			$image		= !empty($signup_details['search_logo']['url']) ? $signup_details['search_logo']['url'] : '';
			$title		= !empty($signup_details['search_title']) ? $signup_details['search_title'] : '';
			$details	= !empty($signup_details['search_details']) ? $signup_details['search_details'] : '';
			$btn_text	= !empty($signup_details['search_signup_btn_title']) ? $signup_details['search_signup_btn_title'] : esc_html__('Join Now','workreap');
			
			$login_register			= array();
			$enable_login_register	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$login_register 		= fw_get_db_settings_option('enable_login_register'); 
				$enable_login_register  = fw_get_db_settings_option('enable_login_register');
			} 
			$is_register		= !empty($login_register['enable']['registration']['gadget']) ? $login_register['enable']['registration']['gadget'] : ''; 
			$signup_page_slug   = workreap_get_signup_page_url('step', '1');
			ob_start();
			?>
			<div class="col-12 wt-joincommunityholder <?php echo esc_attr($class);?>">
				<div class="wt-joincommunity">
					<?php if( !empty($image) ){?>
						<img src="<?php echo esc_url($image);?>" alt="<?php echo esc_attr($title);?>">
					<?php } ?>
					<?php if( !empty($title) ){?>
						<h2><?php echo esc_html($title);?></h2>
					<?php } ?>
					<?php if( !empty($details) ){?>
						<p><?php echo esc_html($details);?></p>
					<?php } ?>

					<?php if ( !empty($is_register) && $is_register === 'enable' && !empty($btn_text)) {?>
						<?php if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'popup' ){?>
							<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php echo esc_html($btn_text);?></a>
						<?php } else if( !empty( $enable_login_register['enable']['login_signup_type'] ) && $enable_login_register['enable']['login_signup_type'] === 'single_step' ){?>
							<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target="#joinpopup" class="wt-btn wt-joinnowbtn"><?php echo esc_html($btn_text);?></a>
						<?php } else {?>
							<a href="<?php echo esc_url(  $signup_page_slug ); ?>"  class="wt-btn"><?php echo esc_html($btn_text);?></a>
						<?php }?>
					<?php }?> 
				</div>
			</div>
			<?php
			echo ob_get_clean();
        }
    }
    add_action('workreap_signup_popup_search_results', 'workreap_signup_popup_search_results', 10, 2);
}

/*
 * Services video display
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_services_videos' ) ){
    function workreap_services_videos( $post_id = '' ,$width='',$height=''){
        if( !empty( $post_id ) ){
			$db_videos	= array();
			if (function_exists('fw_get_db_post_option')) {
				$db_videos   		= fw_get_db_post_option($post_id,'videos');
			}
			
			ob_start();
			if( !empty($db_videos) ){
				foreach( $db_videos as $key => $vid ){
					if(!empty($vid)){
						$url 			= parse_url( $vid );
						echo '<div class="splide__slide"><div class="item item-video">';
						if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
							echo '<figure class="wt-classimg wt-media-list">';
							$content_exp  = explode("/" , $vid);
							$content_vimo = array_pop($content_exp);
							echo '<iframe width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
						></iframe>';
							echo '</figure>';
						} elseif ($url['host'] == 'soundcloud.com') {
							$video  = wp_oembed_get($vid , array (
								'height' => $height ));
							$search = array (
								'webkitallowfullscreen' ,
								'mozallowfullscreen' ,
								'frameborder="0"' );
							echo '<figure class="wt-classimg wt-media-list">';
							echo str_replace($search , '' , $video);
							echo '</figure>';
						} else if($url['host'] == 'youtu.be') {
							$path	= str_replace('/','',$url['path']);
							echo '<figure class="wt-classimg wt-media-list">';
							echo preg_replace(
								"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
								"<iframe width='" . esc_attr( $width ) ."' height='" . esc_attr( $height ) . "' src=\"//www.youtube.com/embed/$2\" frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",
								$vid
							);
							echo '</figure>';
						} else {
							echo '<figure class="wt-classimg wt-media-list">';
							$content = str_replace(array (
								'watch?v=' ,
								'http://www.dailymotion.com/' ) , array (
								'embed/' ,
								'//www.dailymotion.com/embed/' ) , $vid);
							echo '<iframe width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" src="' . esc_url( $content ) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							echo '</figure>';
						}
						echo '</div></div>';
					}
				}
			}
			echo ob_get_clean();
        }
    }
    add_action('workreap_services_videos', 'workreap_services_videos', 10, 3);
}

/*
 * Update service order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_clear_all_filters' ) ){
	add_action('workreap_clear_all_filters', 'workreap_clear_all_filters');
    function workreap_clear_all_filters(){
		ob_start();
		$action_url		= '#';
		if( function_exists('workreap_get_search_page_uri') ){
			if (is_page_template('directory/services-search.php')){
				$action_url		= workreap_get_search_page_uri('services');
			}elseif(is_page_template('directory/freelancer-search.php')){
				$action_url		= workreap_get_search_page_uri('freelancer');
			}else if (is_page_template('directory/employer-search.php')){
				$action_url		= workreap_get_search_page_uri('employer');
			}else if (is_page_template('directory/project-search.php')){
				$action_url		= workreap_get_search_page_uri('jobs');
			}
		}
		?>
		<div class="clear-filters-wrap">
			<a  href='#' data-action="<?php echo esc_url($action_url);?>" onclick='event_preventDefault(event);' class="clear-this-filters"><?php esc_html_e('Clear all filters', 'workreap');?><i class="ti-close"></i></a>
		</div>
		<?php 
		echo ob_get_clean();
	}
}

/*
 * Update service order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'workreap_create_woocommerce_order' ) ){
    function workreap_create_woocommerce_order( $post_id = '',$proposal_id='',$mobile_checkout=false){
		global $woocommerce, $current_user;
		if( class_exists('WooCommerce') ) {
			$first_name         = get_user_meta( $current_user->ID, 'billing_first_name',true );
			$last_name          = get_user_meta( $current_user->ID, 'billing_last_name',true );
			$billing_city       = get_user_meta( $current_user->ID, 'billing_city',true );
			$billing_email      = get_user_meta( $current_user->ID, 'billing_email',true );
			$billing_postcode   = get_user_meta( $current_user->ID, 'billing_postcode',true );
			$billing_phone      = get_user_meta( $current_user->ID, 'billing_phone',true );
			$billing_state      = get_user_meta( $current_user->ID, 'billing_state',true );
			$billing_country    = get_user_meta( $current_user->ID, 'billing_country',true );

			$address_1         = get_user_meta( $current_user->ID, 'billing_address_1',true );
			$billing_company   = get_user_meta( $current_user->ID, 'billing_company',true );
			$address_2         = get_user_meta( $current_user->ID, 'billing_address_2',true );

			$billing_email      = !empty($billing_email) ? $billing_email : get_userdata($current_user->ID)->user_email;
			$first_name         = !empty($first_name) ? $first_name : '';
			$last_name          = !empty($last_name) ? $last_name : '';
			$billing_city       = !empty($billing_city) ? $billing_city : '';
			$billing_postcode   = !empty($billing_postcode) ? $billing_postcode : '';
			$billing_phone      = !empty($billing_phone) ? $billing_phone : '';
			$address_1      = !empty($address_1) ? $address_1 : '';
			$address_2      = !empty($address_2) ? $address_2 : '';

			$address = array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'company'    => $billing_company,
				'email'      => $billing_email,
				'phone'      => $billing_phone,
				'address_1'  => $address_1,
				'address_2'  => $address_2,
				'city'       => $billing_city,
				'state'      => $billing_state,
				'postcode'   => $billing_postcode,
				'country'    => $billing_country
			);
			
			$order_data = array(
				'status'        => apply_filters('woocommerce_default_order_status', 'completed'),
				'customer_id'   => $current_user->ID
			);

			$order = wc_create_order( array('customer_id' => $current_user->ID ) );
			
			
			$items 		= WC()->cart->get_cart();
			$user 		= $order->get_user();
			$order_id 	= $order->get_id();

			foreach ($items as $key => $item) {
				$product_id 	= !empty($item['product_id']) ? intval($item['product_id']) : '';
				
				//Add product into order
				$item_id = $order->add_product(
				$item['data'], $item['quantity'], array(
					'variation' => $item['variation'],
					'name'		=> get_the_title($post_id),
					'totals' => array(
						'subtotal'      => $item['line_subtotal'],
						'tax_class'		=> 'zero-rate',
						'total'         => $item['line_total'],
						)
					)
				);
				
				//Add cart meta to update metadata and post meta
				if ( !empty( $item['cart_data'] ) ) {
					wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item['cart_data'] );
					update_post_meta( $order_id, 'cus_woo_product_data', $item['cart_data'] );
				}

				if ( !empty( $item['payment_type'] ) ) {
					wc_add_order_item_meta( $item_id, 'payment_type', $item['payment_type'] );
					update_post_meta( $order_id, 'payment_type', $item['payment_type'] );
				}

				if ( !empty( $item['admin_shares'] ) ) {
					wc_add_order_item_meta( $item_id, 'admin_shares', $item['admin_shares'] );
					update_post_meta( $order_id, 'admin_shares', $item['admin_shares'] );
				}

				if ( !empty( $item['freelancer_shares'] ) ) {
					wc_add_order_item_meta( $item_id, 'freelancer_shares', $item['freelancer_shares'] );
					update_post_meta( $order_id, 'freelancer_shares', $item['freelancer_shares'] );
				}

				if ( !empty( $item['employer_id'] ) ) {
					wc_add_order_item_meta( $item_id, 'employer_id', $item['employer_id'] );
					update_post_meta( $order_id, 'employer_id', $item['employer_id'] );
				}

				if ( !empty( $item['freelancer_id'] ) ) {
					wc_add_order_item_meta( $item_id, 'freelancer_id', $item['freelancer_id'] );
					update_post_meta( $order_id, 'freelancer_id', $item['freelancer_id'] );
				}

				if ( !empty( $item['current_project'] ) ) {
					wc_add_order_item_meta( $item_id, 'current_project', $item['current_project'] );
				}
				
				//Update prices
				$product 		= $item['data'];
				$product_id		= !empty($item['product_id']) ? $item['product_id'] : 0;
				$original_name  = !empty($product->get_name()) ?  $product->get_name() : '';
				$original_name  = !empty($original_name) && !empty($product_id) ?  get_the_title($product_id) : $original_name;

				if( !empty( $item['payment_type'] ) && $item['payment_type'] == 'hiring' ){
					if( isset( $item['cart_data']['price'] ) ){
						$bk_price = floatval( $item['cart_data']['price'] );
						$item['data']->set_price($bk_price);
					}

					$new_name 	= !empty($item['cart_data']['project_id']) ? get_the_title($item['cart_data']['project_id']) : $original_name;
				} else if( !empty( $item['payment_type'] ) && $item['payment_type'] == 'hiring_service' ){
					if( isset( $item['cart_data']['price'] ) ){
						$bk_price = floatval( $item['cart_data']['price'] );
						$item['data']->set_price($bk_price);
					}

					$new_name 	= !empty($item['cart_data']['service_id']) ? get_the_title($item['cart_data']['service_id']) : $original_name;
				} else if( !empty( $item['payment_type'] ) && $item['payment_type'] == 'milestone' ){
					if( isset( $item['cart_data']['price'] ) ){
						$bk_price = floatval( $item['cart_data']['price'] );
						$item['data']->set_price($bk_price);
					}

					$new_name 	= !empty($item['cart_data']['milestone_id']) ? get_the_title($item['cart_data']['milestone_id']) : $original_name;
				}

				if( !empty($new_name) && method_exists( $product, 'set_name' ) ){
					$product->set_name( $new_name );
				}
				
				//update hiring products
				if ($user) {
					$payment_type = wc_get_order_item_meta( $item_id, 'payment_type', true );
					
					
					if( !empty( $payment_type ) && $payment_type == 'hiring' ) {
						workreap_update_hiring_data( $order_id );

						//update api key data
						$proposal_id 	= get_post_meta($order_id, '_hiring_id', true);
						$project_id 	= '';

						if(!empty($proposal_id)) {
							$project_id = get_post_meta($proposal_id, '_project_id', true);
						}

					}else if( !empty( $payment_type )  && $payment_type == 'hiring_service') {
						workreap_update_hiring_service_data( $order_id,$user->ID );
					} else if( !empty( $payment_type )  && $payment_type == 'milestone') {
						workreap_update_hiring_milestone_data( $order_id,$user->ID );
					}
				}
			}
			
			$order->set_address( $address, 'billing' );
			$order->set_address( $address, 'shipping' );
		
			$order->calculate_totals();
			$order_id 	= $order->get_id();
			$order_id	= !empty($order_id) ? $order_id : 0;
			WC()->cart->empty_cart();
			
			if(!empty($mobile_checkout) && $mobile_checkout === true){
				return $order->get_checkout_order_received_url();
			}else{
				if(!empty($payment_type) && $payment_type === 'hiring_service'){
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting ongoing services', 'workreap');
					$json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('services', $current_user->ID,true,'ongoing');
					wp_send_json($json);
				}elseif(!empty($payment_type) && $payment_type === 'hiring'){
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Please wait you are redirecting ongoing project page', 'workreap');
					$json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $current_user->ID,true,'ongoing');
					wp_send_json($json);
				}elseif(!empty($payment_type) && $payment_type === 'milestone'){
					$json['type'] 			= 'checkout';
					$json['message'] 		= esc_html__('Your milestone status has been updated', 'workreap');
					$json['checkout_url']	= Workreap_Profile_Menu::workreap_profile_menu_link('milestone', $current_user->ID, true,'listing',$proposal_id);
					wp_send_json($json);
				}
			}
		}
	}	
}

/**
 * return pending listings
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_allow_pending_listings') ) {
	function workreap_allow_pending_listings($query) {
        $post_type	= $query->get( 'post_type' );
		if( is_user_logged_in()  && $query->is_main_query() && $query->is_singular() ){
			if( !empty($post_type) && $post_type === 'projects' ){
				$query->set('post_status', array('publish','hired','completed','cancelled'));
			}
        } 
  
	}
	add_action('pre_get_posts','workreap_allow_pending_listings');
}

/**
 * Update unique key with post type
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_wp_after_insert_post') ) {
	add_action('wp_insert_post','workreap_wp_after_insert_post',5,3);
	function workreap_wp_after_insert_post($post_id='',$postdata=array(),$update=false) {
		// do some login
		if( $update === false 
		   && !empty($postdata->post_type)
		   && ( $postdata->post_type === 'freelancers' 
			   || $postdata->post_type === 'employers'
			   || $postdata->post_type === 'micro-services'
			   || $postdata->post_type === 'disputes'
			   || $postdata->post_type === 'wt-milestone'
			   || $postdata->post_type === 'services-orders'
			   || $postdata->post_type === 'withdraw'
			   || $postdata->post_type === 'proposals'
			 )
		){
			$key = workreap_unique_increment(10);
		}
	}
}

/**
 * Restrict search and detail pages
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_restict_user_view_search') ) {
	add_action('workreap_restict_user_view_search','workreap_restict_user_view_search');
	function workreap_restict_user_view_search() {
		global $current_user,$post;
		if( is_user_logged_in() ) {
			$role	= apply_filters('workreap_get_user_type', $current_user->ID );
			if ( function_exists('fw_get_db_post_option' )) {
				$restict_user_view_search    	= fw_get_db_settings_option('restict_user_view_search');
				
				if(!empty($restict_user_view_search) && $restict_user_view_search == 'yes'){
					if(!empty($role) && $role === 'employer'){
						if ( is_page_template('directory/project-search.php') 
							|| is_page_template('directory/employer-search.php')
							|| is_singular('projects')
							|| is_singular('employers')
						) {
							if($post->post_author != $current_user->ID){
								wp_redirect(home_url('/'));
								die;
							}
							
						}	
					}else if(!empty($role) && $role === 'freelancer'){
						if ( is_page_template('directory/freelancer-search.php') 
							|| is_page_template('directory/services-search.php')
							|| is_singular('freelancers')
							|| is_singular('micro-services')
						) {
							if($post->post_author != $current_user->ID){
								wp_redirect(home_url('/'));
								die;
							}
						}	
					}
				}
			}
		}
	}
}

/**
 * Delete attachments for the post types
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_delete_attachments') ) {
	function workreap_delete_attachments($type='',$id='') {
        if(isset($type) && $type === 'service'){
			if (function_exists('fw_get_db_post_option')) {
				$service_attachments   	= fw_get_db_post_option($id,'docs');
			}
			
			if(!empty($service_attachments)){
				foreach( $service_attachments as $key => $item){
					if(!empty($item['attachment_id'])){
						wp_delete_attachment($item['attachment_id'], true);
					}
				}
			}
		}
  
	}
	add_action('workreap_delete_attachments','workreap_delete_attachments',10,2);
}

/**
 * WP Plugin compatibility user query filter params
 *
 * @return
 * @throws error
 */
if(!function_exists('wpguppy_filter_user_params') ) {
	add_filter('wpguppy_filter_user_params','wpguppy_filter_user_params',10,1);
	function wpguppy_filter_user_params($args=array()){
		 $meta_query_args = array();
		 $meta_query_args[] = array(
			'key' 			=> '_is_verified',
			'value' 		=> 'yes',
			'compare' 		=> '='
		);
		
		$query_relation 	= array('relation' => 'AND',);
		$meta_query_args 	= array_merge($query_relation, $meta_query_args);
		$args['meta_query'] = $meta_query_args;
		
		return $args;
	}
}

/**
 * WP Plugin compatibility user query filter params
 *
 * @return
 * @throws error
 */
if(!function_exists('workreap_identity_verification_banner') ) {
	add_filter('workreap_identity_verification_banner','workreap_identity_verification_banner',10,3);
	function workreap_identity_verification_banner($post_id='',$user_type='',$user_identity=''){
		if ( function_exists('fw_get_db_post_option' )) {
			$identity_verification    	= fw_get_db_settings_option('identity_verification');
			
			$idv_title    		= fw_get_db_settings_option($user_type.'_idv_title');
			$idv_description   	= fw_get_db_settings_option($user_type.'_idv_description');
			
			$after_title    		= fw_get_db_settings_option('after_idv_title');
			$after_description   	= fw_get_db_settings_option('after_idv_description');
		}

		if( !empty($user_type) && $user_type === 'employer' ){
			if ( function_exists('fw_get_db_post_option' )) {
				$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
			}
		}

		if(!empty($identity_verification) && $identity_verification === 'yes'){
			$is_verified	= get_post_meta($post_id, 'identity_verified', true);
			$verification_attachments  = get_post_meta($post_id, 'verification_attachments', true);
			
			if(!empty($is_verified)){return;}
		}else{
			return;
		}
	?>
	
	<?php if(empty($is_verified) && !empty($verification_attachments) ){?>
		<div class="col-12">
			<div class="tk-request-section">
				<div class="tk-verification-content">
					<?php if( !empty($after_title) ){?><h5><?php echo esc_html($after_title);?></h5><?php }?>
					<?php if( !empty($after_description) ){?><p><?php echo esc_html($after_description);?></p><?php }?>
				</div>
				<a href="javascript:void(0)" class="tk-request-btn"><i class="fa fa-spinner fa-spin"></i><?php esc_html_e('Request under review','workreap');?></a>  
			</div>
		</div>
	<?php }else{?>
	 	 <div class="col-12"> 
			 <div class="tk-verification-section">
				<div class="tk-verification-content">
					<?php if( !empty($idv_title) ){?><h5><?php echo esc_html($idv_title);?></h5><?php }?>
					<?php if( !empty($idv_description) ){?><p><?php echo esc_html($idv_description);?></p><?php }?>
				</div>
				<div class="tk-verification-btn">
					<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('identity', $user_identity); ?>" class="tk-verify-btn"><?php esc_html_e('Let’s verify now','workreap');?></a>
				</div>
			</div>
		</div>
	<?php }
	}
}
/**
 * Workreap send guppy message email
 * @throws error
 * @author Workreap
 * @return 
 */

if(!function_exists('wpguppy_on_message_sent')) {
    function wpguppy_on_message_sent($chatData=array(),$chatType='',$senderId='',$receiverId='') {
		$is_online	= apply_filters('workreap_is_user_online',$receiverId);
		if( $is_online === true ){return;} //dont' send message if user is online
		
        //Receiver chat notification
		$receiver_chat_notify = 'disable';
		if (function_exists('fw_get_db_settings_option')) {
			$receiver_chat_notify = fw_get_db_settings_option('receiver_chat_notify');
		}

		$message	= esc_html__('You have received a new text message','workreap');
		if(!empty($chatData['messageType']) && $chatData['messageType'] == 1 ){
			$message = !empty($chatData['message']) ? $chatData['message'] : esc_html__('You have received a new text message','workreap');
			
			if(!empty($chatData['attachmentsData']['attachmentType']) && $chatData['attachmentsData']['attachmentType'] == 'video'){
				$message = esc_html__('You have received a video file','workreap');
			}else if(!empty($chatData['attachmentsData']['attachmentType']) && $chatData['attachmentsData']['attachmentType'] == 'audio'){
				$message = esc_html__('You have received an audio file','workreap');
			}else if(!empty($chatData['attachmentsData']['attachmentType']) && $chatData['attachmentsData']['attachmentType'] == 'images'){
				$message = esc_html__('You have received an image file','workreap');
			}else if(!empty($chatData['attachmentsData']['attachmentType']) && $chatData['attachmentsData']['attachmentType'] == 'file'){
				$message = esc_html__('You have received a file','workreap');
			}
			
		}else if(isset($chatData['messageType']) && $chatData['messageType'] == 0 ){
			$message = !empty($chatData['message']) ? $chatData['message'] : esc_html__('You have received a new text message','workreap');
		}else if(!empty($chatData['messageType']) && $chatData['messageType'] == 2 ){
			$message = esc_html__('You have received a location','workreap');
		}else if(!empty($chatData['messageType']) && $chatData['messageType'] == 3 ){
			$message = esc_html__('You have received a voice note','workreap');
		}
		
		$default	= !empty($chatData['message']) ? $chatData['message'] : esc_html__('You have received a new text message','workreap');;
		$message	= !empty($message) ? $message : $default;
		
		if (class_exists('Workreap_Email_helper') && $receiver_chat_notify === 'enable') {
			if (class_exists('WorkreapRecChatNotification')) {
				$email_helper = new WorkreapRecChatNotification();
				$emailData 	  = array();

				$sender_id  	= workreap_get_linked_profile_id($senderId);
				$receiver_id	= workreap_get_linked_profile_id($receiverId);

				$emailData['username'] 		        = get_the_title($receiver_id);
				$emailData['sender_name'] 		    = get_the_title($sender_id);
				$emailData['message']      			= $message;
				$emailData['email_to']      		= get_userdata($receiverId)->user_email;

				$email_helper->send_chat_notification($emailData);
			}
		}
    }
    add_action('wpguppy_on_message_sent', 'wpguppy_on_message_sent',10,4);
}

/**
 * Dashboard post search 
 * @throws error
 * @author Workreap
 * @return 
 */

if(!function_exists('workreap_dashboard_search_keyword')) {
	add_action('workreap_dashboard_search_keyword','workreap_dashboard_search_keyword',10,2);
    function workreap_dashboard_search_keyword($ref='services',$mode='posted') {
		global $current_user, $wp_roles, $userdata, $post;
		$user_identity = $current_user->ID;

		$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
		?>
		<form class="wt-formtheme wt-formsearch">
			<fieldset>
				<div class="form-group">
					<input type="hidden" name="ref" value="<?php echo esc_attr($ref);?>">
					<input type="hidden" name="mode" value="<?php echo esc_attr($mode);?>">
                    <input type="hidden" name="identity" value="<?php echo esc_attr($user_identity);?>">
					<input type="text" value="<?php echo esc_attr($keyword);?>" name="keyword" class="form-control" placeholder="<?php esc_attr_e('Search by title','workreap');?>">
					<button type="submit" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></button>
				</div>
			</fieldset>
		</form>
	<?php 
	}
}

/**
 * Workreap send guppy messenger link
 * @throws error
 * @author Workreap
 * @return 
 */

if(!function_exists('wpguppy_messenger_link')) {
	add_filter('wpguppy_messenger_link','wpguppy_messenger_link',10,1);
    function wpguppy_messenger_link($url='') {
		global $current_user;	
		return Workreap_Profile_Menu::workreap_profile_menu_link('chat', $current_user->ID,true);
	}
}

/**
 * Workreap messenger link param
 * @throws error
 * @author Workreap
 * @return 
 */

if(!function_exists('wpguppy_messenger_link_seprator')) {
	add_filter('wpguppy_messenger_link_seprator','wpguppy_messenger_link_seprator',10,1);
    function wpguppy_messenger_link_seprator($seprator='?') {
		global $current_user;	
		return '&';
	}
}


/**
 * @Get WP Guppy Pro
 * @type load
 */
if(!function_exists('wpguppy_pro_admin_notices_list')){
	function wpguppy_pro_admin_notices_list() {
		if(!is_plugin_active('wp-guppy/wp-guppy.php')){?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e( 'WP Guppy Pro - A live chat plugin is compatible with Workreap Freelancer Marketplace', 'workreap' ); ?></strong></p>
				<p><a class="button button-primary" target="_blank" href="https://codecanyon.net/item/wpguppy-a-live-chat-plugin-for-wordpress/34619534?s_rank=1"><?php esc_html_e( 'Get WP Guppy Pro', 'workreap' ); ?></a></p>
			</div>
			<?php
		}
	}
	add_action( 'admin_notices', 'wpguppy_pro_admin_notices_list' );
}