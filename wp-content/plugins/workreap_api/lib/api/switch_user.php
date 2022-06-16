<?php
/**
 * APP API to manage users
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_Switch_User_Route')) {

    class AndroidApp_Switch_User_Route extends WP_REST_Controller{

        /**
         * Register the routes for the user.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
			$base 		= 'switch_user';
			
			//switch user
            register_rest_route($namespace, '/' . $base . '/switch_user_account',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array(&$this, 'switch_user_account'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );

			// switch user info
			register_rest_route($namespace, '/' . $base . '/user_info',
                array(
					array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_user_info'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			
		}
		/**
         * Switch user to other account
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function get_user_info($request) {
			$json		= array();
			$user_id	= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			if( empty( $user_id ) ) {
				$json['type'] 		= 'error';
            	$json['message'] 	= esc_html__('User ID is required field.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				if( function_exists('workreap_switch_user') ){
					$switch_user_id	= get_user_meta($user_id, 'switch_user_id',true); 
					$user_pmetadata = array();
					if( !empty($switch_user_id) ){
						$profile_id		= workreap_get_linked_profile_id($switch_user_id); 
						$user_type		= apply_filters('workreap_get_user_type', $switch_user_id );
						$user_pmetadata['type']	= 'user_exist';
						if( 'freelancer' === $user_type ){ 
							$user_pmetadata['user_type']	= esc_html__('Freelancer','workreap-api');
							
							$user_pmetadata['profile_img'] 	= apply_filters(
																'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $profile_id ), array( 'width' => 100, 'height' => 100 )
															); 
							
						} else if( 'employer' == $user_type ){
							
							$user_pmetadata['user_type']	= esc_html__('Employer','workreap-api');
							
							$user_pmetadata['profile_img'] 	=  apply_filters(
																	'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id), array('width' => 100, 'height' => 100) 
																);  
							
						}
						$user_pmetadata['user_name']	= workreap_get_username($switch_user_id); 
						
					} else {
						$user_pmetadata['type']	= 'not_exist';
						$user_pmetadata['text']	= esc_html__('Switch user','workreap-api');
					}
					$switch_user	= maybe_unserialize($user_pmetadata);

					return new WP_REST_Response($switch_user, 200);
				}
			}
		}
		/**
         * Switch user to other account
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function switch_user_account($request) {
			$json		= array();
			$user_id	= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			if( empty( $user_id ) ) {
				$json['type'] 		= 'error';
            	$json['message'] 	= esc_html__('User ID is required field.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				if( function_exists('workreap_switch_user') ){
					$switch_user_id	= workreap_switch_user($user_id);
					$user			= get_user_by( 'id', $switch_user_id );
					unset($user->allcaps);
					unset($user->filter);
					
					$user_metadata	= array();
					$profile_data	= array();
					$shipping		= array();
					$billing		= array();
					
					$profile_id		= workreap_get_linked_profile_id($user->data->ID);
					
					if (function_exists('fw_get_db_post_option')) {
						$banner_image       = fw_get_db_post_option($profile_id, 'banner_image', true);	
					}else {
						$banner_image	= array();
					}
					
					//$user_pmetadata['featur_job_op']	= '';
					
					if( 'freelancer' === apply_filters('workreap_get_user_type', $user->data->ID ) ){
						
						$user_pmetadata['user_type']	= 'freelancer';
						
						$user_pmetadata['profile_img'] 	= apply_filters(
															'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $profile_id ), array( 'width' => 100, 'height' => 100 )
														);
						$user_pmetadata['banner_img'] 	= apply_filters(
															'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner( array( 'width' => 100, 'height' => 100 ), $profile_id ), array( 'width' => 100, 'height' => 100 )
														);
						
					} else if( 'employer' == apply_filters('workreap_get_user_type', $user->data->ID ) ){
						
						$user_pmetadata['user_type']	= 'employer';
						
						$user_pmetadata['profile_img'] 	=  apply_filters(
																'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $profile_id), array('width' => 100, 'height' => 100) 
															);
						
						$user_pmetadata['banner_img'] 	=  apply_filters(
																'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 100, 'height' => 100), $profile_id), array('width' => 100, 'height' => 100) 
															); 
						
					}
					
					$first_name	= get_user_meta($user->data->ID, 'first_name', true);
					$last_name	= get_user_meta($user->data->ID, 'last_name', true);
					$first_name	= !empty( $first_name ) ? $first_name : '';
					$last_name	= !empty( $last_name ) ? $last_name : '';

					$permission			= '';
					if(apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user->data->ID) === true) {
						$permission		= 'allow';	
					} else {
						$permission		= 'notallow';	
					}
					
					//milestone allowerd in post
					if (function_exists('fw_get_db_settings_option')) {
						$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
					}

					$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
					
					$user_meta	= array(
						'profile_id'		=> $profile_id,
						'id' 				=> $user->data->ID,
						'user_login' 		=> $user->data->user_login,
						'user_pass' 		=> $user->data->user_pass,
						'first_name' 		=> $first_name,
						'last_name' 		=> $last_name,
						'user_email' 		=> $user->data->user_email,
						'chat_permission'	=> $permission,
						'milestone'			=> $milestone
					);
					
					if ( function_exists( 'fw_get_db_settings_option' ) ) {
						$chat_settings    			= fw_get_db_settings_option('chat');
						$user_meta['chat_type']		= !empty( $chat_settings['gadget'] ) ? $chat_settings['gadget'] : 'inbox';
						$user_meta['host']			=  !empty( $chat_settings['chat']['host'] ) ?  $chat_settings['chat']['host'] : '';
						$user_meta['port']			=  !empty( $chat_settings['chat']['port'] ) ?  $chat_settings['chat']['port'] : '';
					} 
					$post_meta	= array(
						'_tag_line' 			=> '_tag_line',
						'_gender' 				=> '_gender',
						'_is_verified' 			=> '_is_verified',
						'_featured_timestamp' 	=> '_featured_timestamp'
					);
					
					foreach( $post_meta as $key => $usermeta ){
							$user_pmetadata[$key] = get_post_meta($profile_id,$key,true);		
					}
					
					$user_meta['service_access']	= '';
					$user_meta['job_access']		= '';
					
					if( apply_filters('workreap_system_access','service_base') === true ){
						$user_meta['service_access']	= 'yes';
					}
					
					if( apply_filters('workreap_system_access','job_base') === true ){
						$user_meta['job_access']	= 'yes';
					}

					$user_meta['listing_type']	= 'free';
					if(apply_filters('workreap_is_listing_free',false,$user->data->ID) === false ){
						$user_meta['listing_type']	= 'paid';
					}

					if ( class_exists('WC_Customer') ) {
						$customer 	= new WC_Customer( $user->data->ID );
						$shipping	= $customer->get_shipping();
						$billing	= $customer->get_billing();
					}
					
					$json['profile']['shipping'] 		= maybe_unserialize($shipping);
					$json['profile']['billing'] 		= maybe_unserialize($billing);
					
					$user_pmetadata['full_name']	= get_the_title($profile_id);
					$json['profile']['pmeta'] 		= maybe_unserialize($user_pmetadata);
					$json['profile']['umeta'] 		= maybe_unserialize($user_meta);
					$json['type'] 					= 'success';
                    $json['message'] 				= esc_html__('You are logged in', 'workreap_api');
                    $items							= maybe_unserialize($json);
				}
			}
			return new WP_REST_Response($json, 200);
		}
    }
}

add_action('rest_api_init',
        function () {
    $controller = new AndroidApp_Switch_User_Route;
    $controller->register_routes();
});
