<?php
/**
 * APP API to Dashboard insights
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_Dashboard')) {

    class AndroidApp_Dashboard extends WP_REST_Controller{

        /**
         * Register the routes for the chat.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'dashboard';
			
			//get employer insights
            register_rest_route($namespace, '/' . $base . '/get_employer_insights',
                array(                 
                    array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_employer_insights'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//get employer insights
            register_rest_route($namespace, '/' . $base . '/get_packages',
                array(                 
                    array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_packages'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			//get freelancer insights
			register_rest_route($namespace, '/' . $base . '/get_freelancer_insights',
                array(                 
                    array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_freelancer_insights'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			//get freelancer insights
			register_rest_route($namespace, '/' . $base . '/create_checkout_page',
                array(                 
                    array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'create_checkout_page'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );

        }
		
		 /**
         * get employer insights
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_employer_insights($request){
            $json = array();
			$user_id 			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$pakeges_features 	= workreap_get_pakages_features();
			$user_role			= workreap_get_user_type( $user_id );

			$available_balance			= workreap_get_sum_earning_freelancer($user_id,'completed','freelancer_amount');
			$pending_balance 			= workreap_get_sum_earning_freelancer($user_id,'hired','freelancer_amount');
			
			$completed_jobs				= workreap_count_posts_by_meta( 'projects' ,$user_id, '', '', 'completed');
			$total_completed_jobs		= !empty($completed_jobs) && intval($completed_jobs) > 0 ? sprintf('%02d', intval($completed_jobs)) : 0;

			$ongoing_jobs				= workreap_count_posts_by_meta( 'projects' ,$user_id, '', '', 'hired');
			$total_ongoing_jobs			= !empty($ongoing_jobs) && intval($ongoing_jobs) > 0 ? sprintf('%02d', intval($ongoing_jobs)) : 0;

			$cancelled_jobs				= workreap_count_posts_by_meta( 'projects' ,$user_id, '', '', 'cancelled');
			$total_cancelled_jobs		= !empty($cancelled_jobs) && intval($cancelled_jobs) > 0 ? sprintf('%02d', intval($cancelled_jobs)) : 0;

			$posted_jobs				= workreap_count_posts_by_meta( 'projects' ,$user_id, '', '', 'any');
			$total_posted_jobs			= !empty($posted_jobs) && intval($posted_jobs) > 0 ? sprintf('%02d', intval($posted_jobs)) : 0;
			
			$completed_services			= workreap_count_posts_by_meta( 'services-orders' ,$user_id, '', '', 'completed');
			$total_completed_services	= !empty($completed_services) && intval($completed_services) > 0 ? sprintf('%02d', intval($completed_services)) : 0;

			$ongoing_services			= workreap_count_posts_by_meta( 'services-orders' ,$user_id, '', '', 'hired');
			$total_ongoing_services		= !empty($ongoing_services) && intval($ongoing_services) > 0 ? sprintf('%02d', intval($ongoing_services)) : 0;

			$cancelled_services			= workreap_count_posts_by_meta( 'services-orders' ,$user_id, '', '', 'cancelled');
			$total_cancelled_services	= !empty($cancelled_services) && intval($cancelled_services) > 0? sprintf('%02d', intval($cancelled_services)) : 0;
			$expiry_string					= workreap_get_subscription_metadata( 'subscription_featured_string',intval($user_id) );

			$unread_messages	= '0';
			if (class_exists('ChatSystem')) {
				$unread_messages = ChatSystem::getUsersThreadListData($user_id,'','count_unread_msgs');
			}

			$json['unread_messages']		= $unread_messages;
			$json['expiry_string']			= !empty($expiry_string) ? date("Y-m-d H:i:s", $expiry_string) : '';
						
			$json['total_completed_jobs']		= $total_completed_jobs;
			$json['total_ongoing_jobs']			= $total_ongoing_jobs;
			$json['total_cancelled_jobs']		= $total_cancelled_jobs;
			$json['total_posted_jobs']			= $total_posted_jobs;
			
			$json['total_completed_services']	= $total_completed_services;
			$json['total_ongoing_services']		= $total_ongoing_services;
			$json['total_cancelled_services']	= $total_cancelled_services;
			
			
			$json['available_balance']		= workreap_price_format($available_balance,'return');
			
			$packages_info	= array();
	
			if ( !empty ( $pakeges_features )) {
				foreach( $pakeges_features as $key => $vals ) { 
					if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
						$packages_list	= array();
						$text	 = !empty( $vals['text'] ) ? $vals['text'] : '';
						$feature	= workreap_get_subscription_metadata($key,$user_id);
						
						if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
							$feature = 'no';
						}elseif( $key	=== 'wt_duration_type') {
							$feature = workreap_get_duration_types($feature,'value');
						}elseif($key	=== 'wt_badget' ) {
							if(!empty($feature) ){
								$badges		= get_term( intval($feature) );
								if(!empty($badges->name)) {
									$feature	= $badges->name;
								} else {
									$feature	= 'no';
								}
							} else{
								$feature	= 'no';
							}
						}elseif( !empty( $feature ) && $feature === 'yes') {
							$feature	= 'yes';
						} elseif( !empty( $feature ) && $feature === 'no') {
							$feature	= 'no';
						}

						$feature				= !empty( $feature ) ? $feature : '0';
						$packages_list['title']	= !empty($vals['remaining']) ? $vals['remaining'] : '';
						$packages_list['value']	= $feature.'&nbsp;'.esc_html($text);
						
						$json['packages'][]		= $packages_list;

					}
				}
			}
			
			$json					        = maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
		}
		
		/**
         * Get user packages
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function get_packages($request){

			$json			= array();
			$user_type 		= !empty( $request['user_type'] ) ?  $request['user_type'] : '';
			if(!empty($user_type)){
				$pakeges_features 	= workreap_get_pakages_features();
				$currency_symbol	= workreap_get_current_currency();
				$args 				= array(
					'post_type' 			=> 'product',
					'posts_per_page' 		=> -1,
					'post_status' 			=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				$meta_query_args[] = array(
					'key' 		=> 'package_type',
					'value' 	=> $user_type,
					'compare' 	=> '=',
				);

				$query_relation 	= array('relation' => 'AND',);
				$meta_query_args 	= array_merge($query_relation, $meta_query_args);
				$args['meta_query'] = $meta_query_args;
				$loop = new WP_Query( $args );
				$array_packages	= array();
				
				while ( $loop->have_posts() ) : $loop->the_post();
					global $product; 
					$product_array	= array();
					$post_id 		= intval($product->get_id());
					$duration_type	= get_post_meta($post_id,'wt_duration_type',true);
					$duration_title = workreap_get_duration_types($duration_type,'title');
					$product_array['ID']			= $post_id;
					$product_array['title']			= get_the_title($post_id);
					$product_array['price']			= $product->get_price();
					$product_array['duration']		= !empty($duration_title) ? $duration_title : '';
					$product_array['symbol']		= !empty($currency_symbol['symbol']) ? $currency_symbol['symbol'] : '';
				
					if ( !empty ( $pakeges_features )) {
						$featured_array	= array();
						foreach( $pakeges_features as $key => $vals ) {
							$featurs		= array();
							if( $vals['user_type'] === $user_type || $vals['user_type'] === 'common' ) {
								$item	 = get_post_meta($post_id,$key,true);
								
								if( isset( $key ) && $key === 'wt_duration_type' ){
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
								
								$featurs['title']	= $vals['title'];
								$featurs['value']	= !empty($feature) ? $feature : '';
								$featured_array[]	= $featurs;
							}
						}
						
						$product_array['features']	= $featured_array;
					}
					
					$array_packages[]	= $product_array;
				endwhile;
				wp_reset_postdata();
				$json['type'] 		= "success";
				$json['pakcages'] 	= maybe_unserialize($array_packages);
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__('User type required', 'workreap_api');               
				return new WP_REST_Response($json, 203); 
			}
		}

		/**
         * Create temp chekcout data
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function create_checkout_page($request){
			global $wpdb;
			$json	= array();
			$item	= array();
			$items	= array();
			$params 	= $request->get_params();       
			if( !empty( $params['payment_data'] ) ){
			   
				$insert_data = "insert into `".MOBILE_APP_TEMP_CHECKOUT."` set `temp_data`='".stripslashes($params['payment_data'])."'";     
				$wpdb->query($insert_data);

				if(isset($wpdb->insert_id)){ 
					$data_id = $wpdb->insert_id; 
				} else{
					$data_id = $wpdb->print_error();
				}

				$json['type'] 		= "success";
				$json['message'] 	= esc_html__("You order has been placed, Please pay to make it complete", "doctreat_api");

				 $pages = query_posts(array(
					 'post_type' 	=> 'page',
					 'meta_key'  	=> '_wp_page_template',
					 'meta_value'	=> 'mobile-checkout.php'
				 ));

				$url = null;
				if(!empty($pages[0])) {
					 $url = get_page_link($pages[0]->ID).'?order_id='.$data_id.'&platform=mobile';
				}

				$json['url'] 		= esc_url_raw($url);
				return new WP_REST_Response($json, 200);
			 } else {
				$json['type'] = "error";
				$json['message'] = esc_html__("Invalid Parem Data", 'doctreat_api');
				return new WP_REST_Response($json, 203);
			}

		}
		
		
         /**
         * get freelancer insights
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_freelancer_insights($request){
            $json = array();
			$user_id 		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
            
			//Get packages
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			$pakeges_features 	= workreap_get_pakages_features();
			$user_role			= workreap_get_user_type( $user_id );

			$available_balance			= workreap_get_sum_earning_freelancer($user_id,'completed','freelancer_amount');
			$pending_balance 			= workreap_get_sum_earning_freelancer($user_id,'hired','freelancer_amount');
			
			$completed_services				= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'completed');
			$total_completed_services		= !empty($completed_services) && intval($completed_services) > 0 ? sprintf('%02d', intval($completed_services)) : 0;

			$ongoing_services				= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'hired');
			$total_ongoing_services			= !empty($ongoing_services) && intval($ongoing_services) > 0? sprintf('%02d', intval($ongoing_services)) : 0;

			$cancelled_services				= workreap_count_posts_by_meta( 'services-orders' ,'', '_service_author', $user_id, 'cancelled');
			$total_cancelled_services		= !empty($cancelled_services) && intval($cancelled_services) > 0? sprintf('%02d', intval($cancelled_services)) : 0;
			
			$completed_jobs					= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'completed');
			$total_completed_jobs			= !empty($completed_jobs) && intval($completed_jobs) > 0 ? sprintf('%02d', intval($completed_jobs)) : 0;

			$ongoing_jobs					= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'hired');
			$total_ongoing_jobs				= !empty($ongoing_jobs) && intval($ongoing_jobs) > 0 ? sprintf('%02d', intval($ongoing_jobs)) : 0;

			$cancelled_jobs					= workreap_count_posts_by_meta( 'proposals' ,$user_id, '', '', 'cancelled');
			$total_cancelled_jobs			= !empty($cancelled_jobs) ? $cancelled_jobs : 0;
			$expiry_string					= workreap_get_subscription_metadata( 'subscription_featured_string',intval($user_id) );

			//plugin core active
			$unread_messages	= '0';
			if (class_exists('ChatSystem')) {
				$unread_messages = ChatSystem::getUsersThreadListData($user_id,'','count_unread_msgs');
			}

			$json['unread_messages']		= $unread_messages;
			$json['expiry_string']			= !empty($expiry_string) ? date("Y-m-d H:i:s", intval($expiry_string)) : '';
			$json['available_balance']		= workreap_price_format($available_balance,'return');
			$json['pending_balance']		= workreap_price_format($pending_balance,'return');

			$json['completed_jobs']			= $completed_jobs;
			$json['ongoing_jobs']			= $ongoing_jobs;
			$json['total_cancelled_jobs']	= $total_cancelled_jobs;
			
			$json['total_completed_services']	= $total_completed_services;
			$json['ongoing_services']			= $ongoing_services;
			$json['total_cancelled_services']	= $total_cancelled_services;
			
			
			$packages_info	= array();
	
			if ( !empty ( $pakeges_features )) {
				foreach( $pakeges_features as $key => $vals ) { 
					if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
						$packages_list	= array();
						$text	 = !empty( $vals['text'] ) ? $vals['text'] : '';
						$feature	= workreap_get_subscription_metadata($key,$user_id);
						
						if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
							$feature = 'no';
						}elseif( $key	=== 'wt_duration_type') {
							$feature = workreap_get_duration_types($feature,'value');
						}elseif($key	=== 'wt_badget' ) {
							if(!empty($feature) ){
								$badges		= get_term( intval($feature) );
								if(!empty($badges->name)) {
									$feature	= $badges->name;
								} else {
									$feature	= 'no';
								}
							} else{
								$feature	= 'no';
							}
						}elseif( !empty( $feature ) && $feature === 'yes') {
							$feature	= 'yes';
						} elseif( !empty( $feature ) && $feature === 'no') {
							$feature	= 'no';
						}

						$feature				= !empty( $feature ) ? $feature : '0';
						$packages_list['title']	= !empty($vals['remaining']) ? $vals['remaining'] : '';
						$packages_list['value']	= $feature.'&nbsp;'.esc_html($text);
						$json['packages'][]		= $packages_list;
					}
				}
			}

			$json	= maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
        }
    }
}

add_action('rest_api_init',
        function () {
			$controller = new AndroidApp_Dashboard;
			$controller->register_routes();
});
