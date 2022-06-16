<?php
/**
 * Packages options
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (class_exists('WooCommerce')) {
	if (!function_exists('workreap_packages_option')) {
		add_action( 'init', 'workreap_packages_option' );

		function workreap_packages_option(){
			add_filter( 'woocommerce_cod_process_payment_order_status','workreap_update_order_status', 10, 2 );
			add_filter( 'woocommerce_cheque_process_payment_order_status','workreap_update_order_status', 10, 2 );
			add_filter( 'woocommerce_bacs_process_payment_order_status','workreap_update_order_status', 10, 2 );
			
			if( is_admin() ){
				add_action( 'woocommerce_order_status_completed','workreap_payment_complete',10,1 );
			}

		}
	}
}

/**
 * PayPal redirect after payments
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_woocommerce_thankyou')) {
	add_action('woocommerce_thankyou', 'workreap_woocommerce_thankyou', 10, 1);
	function workreap_woocommerce_thankyou( $order_id ) {
		if ( ! $order_id ){return;}
		
		if( is_wc_endpoint_url('order-received') ) {
			if(!empty($_GET['utm_nooverride'])){
				$order = wc_get_order( $order_id );
				$redirect_url   = $order->get_checkout_order_received_url();
				wp_redirect($redirect_url);
			}
		}
	}
}

/**
 * PayPal Order process
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if (!function_exists('workreap_paypal_payment_complete_order_status')) {
	//add_filter('woocommerce_payment_complete_order_status', 'workreap_paypal_payment_complete_order_status', 10, 2 );
	function workreap_paypal_payment_complete_order_status( $order_status, $order_id ){
		$order = wc_get_order( $order_id );
		if( $order->get_payment_method() === 'paypal' ){
			$order_status = 'completed';
		}

		return $order_status;
	}
}
/**
 * change status for offline payment gateway
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if (!function_exists('workreap_update_order_status')) {

	function workreap_update_order_status( $status,$order  ) {
		return 'on-hold';
	}
}

/**
 * offline packages after checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if (!function_exists('workreap_offline_onhold')) {

	function workreap_offline_onhold( $order_id  ) {
		$order 		= wc_get_order($order_id);
        $user 		= $order->get_user();
		$items 		= $order->get_items();
		$hiring_id	= get_post_meta( $order_id, '_hiring_id',true);
		if( empty($hiring_id) ){
			foreach ($items as $key => $item) {
				$order_detail 					= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );

				if ($user) {
					$payment_type = wc_get_order_item_meta( $key, 'payment_type', true );
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapOfflinePackages')) {
							$email_helper 				= new WorkreapOfflinePackages();
							$emailData 					= array();
							$emailData['email_to']		= !empty( $user->user_email ) ? is_email($user->user_email) : '';
							$emailData['employer_name'] = !empty( $user->ID ) ? workreap_get_username( $user->ID ) : '';
							
							if( !empty( $payment_type ) && $payment_type == 'hiring' ) {
								$proposal_id		= !empty($order_detail['proposal_id']) ? intval($order_detail['proposal_id']) : 0;
								$project_id			= !empty( $order_detail['project_id'] ) ? intval($order_detail['project_id']) : 0;
								$emailData['order_name']		= !empty( $project_id ) ? get_the_title($project_id) : '';
								$emailData['order_link']		= !empty( $project_id ) ? get_the_permalink($project_id) : '';
								
								update_post_meta( $order_id, '_hiring_id', $proposal_id );
								update_post_meta( $project_id, '_order_id', $order_id );
								$email_helper->recived_offline_order($emailData);
								
								//Push notification
								$push	= array();
								$push['receiver_id']		= !empty( $user->ID ) ? $user->ID : '';
								$push['project_id']			= $project_id;
								$push['%employer_name%']	= $emailData['employer_name'];
								$push['%order_link%']		= $emailData['order_link'];
								$push['%order_name%']		= $emailData['order_name'];
								$push['type']				= 'offline_order';

								do_action('workreap_user_push_notify',array($push['receiver_id']),'','pusher_offline_order_notification_content',$push);
								
							} else if( !empty( $payment_type )  && $payment_type == 'hiring_service') {
								$service_id						= !empty( $order_detail['service_id'] ) ? intval($order_detail['service_id']) : 0;
								$emailData['order_name']		= !empty( $service_id ) ? get_the_title($service_id) : '';
								$emailData['order_link']		= !empty( $service_id ) ? get_the_permalink($service_id) : '';
								$email_helper->recived_offline_order($emailData);
								
								//Push notification
								$push	= array();
								$push['receiver_id']		= !empty( $user->ID ) ? $user->ID : '';
								$push['project_id']			= $project_id;
								$push['%employer_name%']	= $emailData['employer_name'];
								$push['%order_link%']		= $emailData['order_link'];
								$push['%order_name%']		= $emailData['order_name'];
								$push['type']				= 'offline_order';

								do_action('workreap_user_push_notify',array($push['receiver_id']),'','pusher_offline_order_notification_content',$push);
								
							}  else if( !empty( $payment_type )  && $payment_type == 'milestone') {
								$milestone_id	= !empty( $order_detail['milestone_id'] ) ? $order_detail['milestone_id'] : '';
								$project_id		= !empty( $order_detail['project_id'] ) ? intval($order_detail['project_id']) : 0;
								update_post_meta( $order_id, '_hiring_id', $milestone_id );
								update_post_meta( $milestone_id, '_order_id', $order_id );
							}
							
						}
					}
				}
			}
		}
	}
}

/**
 * Complete order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */ 
if (!function_exists('workreap_payment_complete')) {
    add_action('woocommerce_payment_complete', 'workreap_payment_complete',10,1 );
	//add_action( 'woocommerce_order_status_completed','workreap_payment_complete',10,1 );
    function workreap_payment_complete($order_id) {
		global $current_user, $wpdb;
		
        $order 		= wc_get_order($order_id);
        $user 		= $order->get_user();
        $items 		= $order->get_items();
        $offset 	= get_option('gmt_offset') * intval(60) * intval(60);
		
		//Update order status
		$order->update_status( 'completed' );
		$order->save();
		
		
		$invoice_id = esc_html__('Order #','workreap') . '&nbsp;' . $order_id;
        foreach ($items as $key => $item) {
            $product_id 	= !empty($item['product_id']) ? intval($item['product_id']) : '';
            $product_qty 	= !empty($item['qty']) ? $item['qty'] : 1;

            if ($user) {
				$payment_type = wc_get_order_item_meta( $key, 'payment_type', true );
				if( !empty( $payment_type ) && $payment_type == 'hiring' ) {
					workreap_update_hiring_data( $order_id );
					//update api key data
					$proposal_id = get_post_meta($order_id, '_hiring_id', true);
					$project_id = '';
					
					if(!empty($proposal_id)) {
						$project_id = get_post_meta($proposal_id, '_project_id', true);
					}
					
				}else if( !empty( $payment_type )  && $payment_type == 'hiring_service') {
					workreap_update_hiring_service_data( $order_id,$user->ID );
				} else if( !empty( $payment_type )  && $payment_type == 'milestone') {
					workreap_update_hiring_milestone_data( $order_id,$user->ID );
				} else if( !empty( $payment_type ) && $payment_type == 'subscription' ) {
					workreap_update_pakage_data( $product_id ,$user->ID,$order_id );
				} else  {
					do_action('workreap_update_product_metadata',$product_id ,$user->ID,$order_id);
				}
            }
        }
    }
}

/**
 * Update User Hiring Milestone payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_update_hiring_milestone_data')) {
    function workreap_update_hiring_milestone_data( $order_id ) {
        global $product,$woocommerce,$wpdb,$current_user;
		$current_date 	= current_time('mysql');
		$gmt_time		= current_time( 'mysql', 1 );
		
		$order 		= new WC_Order( $order_id );
		$items 		= $order->get_items();
		$earning	= array();
		
		if( !empty( $items ) ) {
			$counter	= 0;
			
			foreach( $items as $key => $order_item ){
				$counter++;
				$order_detail 					= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$earning['freelancer_amount']	= wc_get_order_item_meta( $key, 'freelancer_shares', true );
				$earning['admin_amount'] 		= wc_get_order_item_meta( $key, 'admin_shares', true );
				
				$earning['user_id']			= get_post_meta($order_detail['milestone_id'], '_freelancer_id', true);
				$earning['amount']			= !empty( $order_detail['price'] ) ? $order_detail['price'] : '';
				$earning['project_id']		= !empty( $order_detail['project_id'] ) ? $order_detail['project_id'] : '';
				$earning['milestone_id']	= !empty( $order_detail['milestone_id'] ) ? $order_detail['milestone_id'] : '';
			}
			
			$earning['order_id']		= $order_id;
			$earning['process_date'] 	= date('Y-m-d H:i:s', strtotime($current_date));
			$earning['date_gmt'] 		= date('Y-m-d H:i:s', strtotime($gmt_time));
			$earning['year'] 			= date('Y', strtotime($current_date));
			$earning['month'] 			= date('m', strtotime($current_date));
			$earning['timestamp'] 		= strtotime($current_date);
			$earning['status'] 			= 'hired';
			$earning['project_type'] 	= 'milestone';
			
			if( function_exists('workreap_get_current_currency') ) {
				$currency					= workreap_get_current_currency();
				$earning['currency_symbol']	= $currency['symbol'];
			} else {
				$earning['currency_symbol']	= '$';
			}
			
			if( !empty($earning['milestone_id']) && !empty($order_detail['project_id']) ) {
				workreap_hired_milestone_after_payment( $earning['milestone_id'] );
				$table_name = $wpdb->prefix . "wt_earnings";
				if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
					$tablename = $wpdb->prefix.'wt_earnings';
					$wpdb->insert( $tablename, $earning);
				}
				
				//update order meta 
				update_post_meta( $order_id, 'freelancer_id', $earning['user_id'] );
				
				//email data
				$milestone_title    = !empty( $earning['milestone_id'] ) ? get_the_title($earning['milestone_id']) :'';
				$project_title		= !empty( $earning['project_id'] ) ? get_the_title($earning['project_id']) : '';
				$project_link		= !empty( $earning['project_id'] ) ? get_the_permalink($earning['project_id']) : '';
				
				$freelancer_user_id			= $earning['user_id'];
				$hired_freelancer_title 	= workreap_get_username( $freelancer_user_id );

				$user_email 	= !empty( $freelancer_user_id ) ? get_userdata( $freelancer_user_id )->user_email : '';

				update_post_meta( $order_id, '_hiring_id', $earning['milestone_id'] );
				update_post_meta( $earning['milestone_id'], '_order_id', $order_id );
				
				//Send email to freelancer
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapMilestoneRequest')) {
						$email_helper = new WorkreapMilestoneRequest();
						$emailData = array();
						
						$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
						$emailData['project_title'] 	= esc_html( $project_title);
						$emailData['project_link'] 		= esc_html( $project_link);
						$emailData['milestone_title'] 	= esc_html( $milestone_title);

						$emailData['email_to'] 			= esc_html( $user_email);

						$email_helper->send_hired_against_milestone_to_freelancer_email($emailData);
						
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelancer_user_id;
						$push['project_id']			= $earning['project_id'];
						$push['type']				= 'milestone_hired';

						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%milestone_title%']	= $emailData['milestone_title'];
						$push['%project_title%']	= $emailData['project_title'];
						$push['%project_link%']		= $emailData['project_link'];
						
						$push['%replace_milestone_title%']	= $emailData['milestone_title'];

						do_action('workreap_user_push_notify',array($freelancer_user_id),'','pusher_hired_ml_content',$push);
					}
				}	

			}
		}
    }
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if (!function_exists('workreap_update_hiring_data')) {
    function workreap_update_hiring_data( $order_id ) {
		
        global $product,$woocommerce,$wpdb,$current_user;
		$current_date 	= current_time('mysql');
		$gmt_time		= current_time( 'mysql', 1 );
		
		$order 		= new WC_Order( $order_id );
		$items 		= $order->get_items();
		$earning	= array();
		
		if( !empty( $items ) ) {
			$counter	= 0;
			foreach( $items as $key => $order_item ){
				$counter++;
				$order_detail 					= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$earning['freelancer_amount']	= wc_get_order_item_meta( $key, 'freelancer_shares', true );
				$earning['admin_amount'] 		= wc_get_order_item_meta( $key, 'admin_shares', true );
				
				$earning['user_id']		= get_post_field('post_author',$order_detail['proposal_id']);
				$earning['amount']		= !empty( $order_detail['price'] ) ? $order_detail['price'] : '';
				$earning['project_id']	= !empty( $order_detail['project_id'] ) ? $order_detail['project_id'] : '';
			}
			
			$earning['order_id']		= $order_id;
			$earning['process_date'] 	= date('Y-m-d H:i:s', strtotime($current_date));
			$earning['date_gmt'] 		= date('Y-m-d H:i:s', strtotime($gmt_time));
			$earning['year'] 			= date('Y', strtotime($current_date));
			$earning['month'] 			= date('m', strtotime($current_date));
			$earning['timestamp'] 		= strtotime($current_date);
			$earning['status'] 			= 'hired';
			
			if( function_exists('workreap_get_current_currency') ) {
				$currency					= workreap_get_current_currency();
				$earning['currency_symbol']	= $currency['symbol'];
			} else {
				$earning['currency_symbol']	= '$';
			}
			
			if( !empty($earning['project_id']) && !empty($order_detail['proposal_id']) ) {
				workreap_hired_freelancer_after_payment( $earning['project_id'],$order_detail['proposal_id']);
				$table_name = $wpdb->prefix . "wt_earnings";
				if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
					$tablename = $wpdb->prefix.'wt_earnings';
					$wpdb->insert( $tablename,$earning);
				}

				$project_id				= !empty( $earning['project_id'] ) ? $earning['project_id'] : '';
				$employer_id_user		= get_post_field ('post_author', $project_id);
				$freelancer_user_id		= get_post_field ('post_author', $order_detail['proposal_id']);
				
				$project_title			= esc_html( get_the_title( $project_id ) );
				$project_link			= esc_url( get_the_permalink( $project_id ));
				$message				= esc_html__('You are hiring for','workreap').' '.$project_title.' '.$project_link;
				
				if (class_exists('ChatSystem')){
					$chat_api				= array();
					if (function_exists('fw_get_db_settings_option')) {
						$chat_api = fw_get_db_settings_option('chat');
					}
					
					$comet_apikey = '';
					if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') { 
						$comet_apikey = get_option('atomchat_api_key');
						$params_array = array(
							'senderId' 		=> $employer_id_user,
							'receiverId' 	=> $freelancer_user_id,
							'message' 		=> $message,
							'comet_api' 	=> $comet_apikey
						);
						$api_msg_response = ChatSystem::createCometChatUser($params_array);
						$api_msg_response = ChatSystem::initCometChatRequest($params_array);
					}else if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
						do_action('wpguppy_send_message_to_user',$employer_id_user,$freelancer_user_id,$message);
					}else {
						$insert_data = array(
							'sender_id' 		=> $employer_id_user,
							'receiver_id' 		=> $freelancer_user_id,
							'chat_message' 		=> $message,
							'status' 			=> 'unread',
							'timestamp' 		=> time(),
							'time_gmt' 			=> $gmt_time,
						);
						$msg_id = ChatSystem::getUsersThreadListData($employer_id_user, $freelancer_user_id, 'insert_msg', $insert_data, '');
					}


					do_action('workreap_send_message_to_user_action',$employer_id_user,$freelancer_user_id,$message);
				}
				
				update_post_meta( $order_id, '_hiring_id', $order_detail['proposal_id'] );
				update_post_meta( $project_id, '_order_id', $order_id );
				update_post_meta( $order_detail['proposal_id'], '_order_id', $order_id );
				
				//update order meta 
				update_post_meta( $order_id, 'freelancer_id', $freelancer_user_id );
				
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapHireFreelancer')) {
						$email_helper 	= new WorkreapHireFreelancer();
						$emailData 	  	= array();
						$employer_id	= workreap_get_linked_profile_id($employer_id_user);
						$freelancer_id	= get_post_meta($order_detail['proposal_id'],'_send_by',true);
						$profile_id		= workreap_get_linked_profile_id($freelancer_id,'post');
						$user_email 	= !empty( get_userdata( $profile_id )->user_email ) ? get_userdata( $profile_id )->user_email : '';
						$employer_email 	= !empty( get_userdata( $employer_id_user )->user_email ) ? get_userdata( $employer_id_user )->user_email : '';

						$emailData['freelancer_link'] 		= esc_url( get_the_permalink( $freelancer_id ));
						$emailData['freelancer_name'] 		= esc_html( get_the_title($freelancer_id));
						$emailData['employer_link']       	= esc_url( get_the_permalink( $employer_id ) );
						$emailData['employer_name'] 		= esc_html( get_the_title($employer_id));
						$emailData['project_link']        	= $project_link;
						$emailData['project_title']      	= $project_title;
						$emailData['email_to']      		= $user_email;
						$emailData['employer_email_to']		= $employer_email;
						$emailData['project_id']      		= $project_id;
						$emailData['employer_id']      		= $employer_id;
						$emailData['freelancer_id']      	= $profile_id;
						$emailData['employer_user_id']      = $profile_id;
						$emailData['hired_user_id']      = $employer_id_user;
						
						$email_helper->send_hire_freelancer_email($emailData);
						$email_helper->send_hiring_employer_email($emailData);
						$email_helper->send_rejected_freelancers_email($emailData);
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $profile_id;
						$push['employer_id']		= $employer_id_user;
						$push['project_id']			= $project_id;

						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['project_title'];
						$push['%project_link%']		= $emailData['project_link'];
						
						$push['type']				= 'freelancer_hired';

						do_action('workreap_user_push_notify',array($profile_id),'','pusher_frl_hire_content',$push);
					}
				}
			}
		}
    }
}
/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_update_hiring_service_data')) {
    function workreap_update_hiring_service_data( $order_id,$user_id ) {
        global $product,$woocommerce,$wpdb,$current_user;
		$current_date 	= current_time('mysql');
		$gmt_time		= current_time( 'mysql', 1 );

		$order 		= new WC_Order( $order_id );
		$items 		= $order->get_items();
		$earning	= array();
		
		if( !empty( $items ) ) {
			$counter	= 0;
			foreach( $items as $key => $order_item ){
				$counter++;
				$order_detail 					= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$earning['freelancer_amount']	= wc_get_order_item_meta( $key, 'freelancer_shares', true );
				$earning['admin_amount'] 		= wc_get_order_item_meta( $key, 'admin_shares', true );
				$earning['amount']				= $order_detail['price'];
				
			}
			
			$earning['order_id']		= $order_id;
			$earning['project_type']	= 'service';
			$earning['process_date'] 	= date('Y-m-d H:i:s', strtotime($current_date));
			$earning['date_gmt'] 		= date('Y-m-d H:i:s', strtotime($gmt_time));
			$earning['year'] 			= date('Y', strtotime($current_date));
			$earning['month'] 			= date('m', strtotime($current_date));
			$earning['timestamp'] 		= strtotime($current_date);
			$earning['status'] 			= 'hired';
			
			$price	= !empty($earning['amount']) ? $earning['amount'] : 0.0;
			
			if( function_exists('workreap_get_current_currency') ) {
				$currency					= workreap_get_current_currency();
				$earning['currency_symbol']	= $currency['symbol'];
			} else {
				$earning['currency_symbol']	= '$';
			}
			
			if( !empty($order_detail['service_id']) ) {
				$addons				= !empty( $order_detail['addons'] ) ? $order_detail['addons'] : array();
				$quote				= !empty( $order_detail['quote'] ) ? $order_detail['quote'] : '';
				$receiver_id		= get_post_field('post_author',$order_detail['service_id'] );
				$service_title		= get_the_title( $order_detail['service_id'] );
				$service_link		= get_the_permalink( $order_detail['service_id'] );
				
				$order_post = array(
					'post_title'    => wp_strip_all_tags( $service_title ).' #'.$order_id,
					'post_status'   => 'hired',
					'post_author'   => $user_id,
					'post_type'     => 'services-orders',
				);

				$order_post    = wp_insert_post( $order_post );
				
				if( !empty( $order_post ) ) {
					update_post_meta($order_post,'_service_id',$order_detail['service_id']);
					update_post_meta($order_post,'_service_title',esc_attr( $service_title ));
					update_post_meta($order_post,'_service_author',$receiver_id);
					update_post_meta($order_post,'_order_id',$order_id);
					update_post_meta($order_post,'_addons',$addons);
					update_post_meta( $order_id, '_hiring_id', $order_post );
					
					//update order meta 
					update_post_meta( $order_id, 'freelancer_id', $receiver_id );
				}

				//Update quote hiring
				if(!empty($quote)){
					update_post_meta( $quote, 'hiring_status', 'hired' );
				}
				
				$earning['user_id']		= $receiver_id;
				$earning['project_id']	= $order_post;
				
				$table_name = $wpdb->prefix . "wt_earnings";
				if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
					$tablename = $wpdb->prefix.'wt_earnings';
					$wpdb->insert( $tablename,$earning);
				}
				
				$message				= esc_html__('Congratulations! You have been hired for the','workreap').' '.$service_title.' '.$service_link;

				if (class_exists('ChatSystem')) {
					$chat_api				= array();
					if (function_exists('fw_get_db_settings_option')) {
						$chat_api = fw_get_db_settings_option('chat');
					}
					
					$comet_apikey = '';
					if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') { 
						$comet_apikey = get_option('atomchat_api_key');
						$params_array = array(
							'senderId' 		=> $user_id,
							'receiverId' 	=> $receiver_id,
							'message' 		=> $message,
							'comet_api' 	=> $comet_apikey
						);
						$api_msg_response = ChatSystem::createCometChatUser($params_array);
						$api_msg_response = ChatSystem::initCometChatRequest($params_array);
					}else if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
						do_action('wpguppy_send_message_to_user',$user_id,$receiver_id,$message);
					} else {
						$insert_data = array(
							'sender_id' 		=> $user_id,
							'receiver_id' 		=> $receiver_id,
							'chat_message' 		=> $message,
							'status' 			=> 1,
							'timestamp' 		=> time(),
							'time_gmt' 			=> $gmt_time,
						);
						$msg_id 	= ChatSystem::getUsersThreadListData($receiver_id, $user_id, 'insert_msg', $insert_data, '');
					}

					do_action('workreap_send_message_to_user_action',$user_id,$receiver_id,$message);
				}
				
				$service_id	=  $order_detail['service_id'];
				
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapHireFreelancer')) {
						$email_helper = new WorkreapHireFreelancer();
						$emailData 	  = array();
						$freelancer_id	= workreap_get_linked_profile_id( $receiver_id );
						$employer_id	= workreap_get_linked_profile_id( $user_id );
						$user_email 		= !empty( $receiver_id ) ? get_userdata( $receiver_id )->user_email : '';
						$employer_email 	= !empty( $user_id ) ? get_userdata( $user_id )->user_email : '';
						
						$emailData['freelancer_link'] 		= get_the_permalink( $freelancer_id );
						$emailData['freelancer_name'] 		= get_the_title($freelancer_id);
						$emailData['employer_link']       	= get_the_permalink( $employer_id );
						$emailData['employer_name'] 		= get_the_title($employer_id);
						$emailData['service_link']        	= $service_link;
						$emailData['service_title']      	= $service_title;
						$emailData['service_price']      	= workreap_price_format( $price ,'return');;
						$emailData['email_to']      		= $user_email;
						$emailData['employer_email']      	= $employer_email;

						$email_helper->send_hire_freelancer_email_service($emailData);
						$email_helper->send_hire_employer_email_service($emailData);
						
						//Push notification
						$push	= array();
						$push['freelancer_id']		= $receiver_id;
						$push['employer_id']		= $user_id;
						$push['service_id']			= $service_id;
						
						
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'];
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%service_link%']		= $emailData['service_link'];
						$push['%service_title%']	= $emailData['service_title'];
						$push['%service_price%']	= $emailData['service_price'];
						$push['type']				= 'service_purchased';

						do_action('workreap_user_push_notify',array($push['freelancer_id']),'','pusher_service_buy_content',$push);
						
						
						do_action('workreap_user_push_notify',array($push['employer_id']),'','pusher_service_buy_content_employer',$push);
					}
				}
			}
		}
    }
}

/**
 * Get formated user billing address
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_user_billing_address')) {
	function workreap_user_billing_address($user_id) {
		$address  = '';
		$address .= get_user_meta( $user_id, 'billing_first_name', true );
		$address .= ' ';
		$address .= get_user_meta( $user_id, 'billing_last_name', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_company', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_address_1', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_city', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_state', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_postcode', true );
		$address .= "\n";
		$address .= get_user_meta( $user_id, 'billing_country', true );

		return $address;
	}
}

/**
 * Custom order filter
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_add_order_custom_filter')) {
	function workreap_add_order_custom_filter( $query, $query_vars ) {
		if ( !empty( $query_vars['freelancer_id'] ) ) {
			$meta_query_args	= array();
			$query_relation 	= array('relation' => 'OR',);
			
			$order_meta_query[] = array(
				'key'	=> 'freelancer_id',
				'value' => intval( $query_vars['freelancer_id'] ),
			);
			
			$order_meta_query[] = array(
				'key'	=> '_customer_user',
				'value' => intval( $query_vars['freelancer_id'] ),
			);

			
			$query['meta_query'][] = array_merge($query_relation, $order_meta_query);
		}

		return $query;
	}
	add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'workreap_add_order_custom_filter', 10, 2 );
}

/**
 * Update User Pakage
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_update_pakage_data')) {
    function workreap_update_pakage_data( $product_id, $user_id,$order_id ) {
        $user_type					= workreap_get_user_type( $user_id );
		$package_data				= array();
		$pakeges_features			= workreap_get_pakages_features();
		$profile_id					= workreap_get_linked_profile_id($user_id);
		
		$current_date = current_time('mysql');
		$wt_featured	= '';
		$wt_isbadge	    = 'no';
		
		if ( !empty ( $pakeges_features )) {
			foreach( $pakeges_features as $key => $vals ) {
				if( !empty( $vals['user_type'] ) &&  ( $vals['user_type'] === $user_type || $vals['user_type'] === 'common' ) ) {
					$item				= get_post_meta($product_id,$key,true);
					
					if( $key === 'wt_duration_type' ) {
						$wt_featured 		= workreap_get_duration_types($item,'value');
						$feature			= $item;
					} elseif( $key === 'wt_badget' ) {
						$feature 		= !empty( $item ) ? $item : 0;
						if( !empty($feature) ){
							$wt_isbadge	    = 'yes';
						}
						
					} else {
						$feature 	= $item;
					}
					
					$package_data[$key]	= $feature;
				}
			}
		}
		
		
		$duration 		= $wt_featured; //no of days for a featured listings
		$featured_date  = date('Y-m-d H:i:s');
		
		if ( !empty( $duration ) && $duration > 0 ) {
			$featured_date = strtotime("+" . $duration . " days", strtotime($current_date));
			$featured_date = date('Y-m-d H:i:s', $featured_date);
		}
		
		$featured_string	= !empty( $featured_date ) ?  strtotime( $featured_date ) : 0;
		
		$package_data['subscription_id'] 				= $product_id;
		$package_data['subscription_featured_expiry'] 	= $featured_date;
		$package_data['subscription_featured_string'] 	= $featured_string;
		
		if ( !empty( $duration ) && $duration > 0 && $wt_isbadge === 'yes' ) {
			update_post_meta($profile_id, '_featured_timestamp', 1);
			update_post_meta($profile_id, '_expiry_string', $featured_string);
			
			$fw_options             = fw_get_db_post_option($profile_id);
			$fw_options['featured_post'] = true;
			$fw_options['featured_expiry'] = $featured_date;
			
			fw_set_db_post_option($profile_id, null, $fw_options);
			
			
		} else{
			update_post_meta($profile_id, '_featured_timestamp', 0);
		}
		
		update_user_meta( $user_id, 'wt_subscription', $package_data);

		if( !empty( $order_id ) ) {
			//Send email to users
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapSubscribePackage')) {
					$email_helper = new WorkreapSubscribePackage();
					$emailData 	= array();
					$user_type		= apply_filters('workreap_get_user_type', $user_id );

					$order 			= wc_get_order($order_id);

					$product 		= wc_get_product($product_id);
					$invoice_id 	= esc_html__('Order #','workreap').$order_id;
					$package_name   = $product->get_title();
					$amount 		= $product->get_price();
					$status 		= $order->get_status();
					$method 		= $order->payment_method;
					$name 			= $order->billing_first_name . '&nbsp;' . $order->billing_last_name;
					$user_email 	= get_userdata( $user_id )->user_email;

					$amount 		= wc_price( $amount );

					if( empty( $name ) ){
						$name 		= workreap_get_username($user_id);
					}

					$emailData['invoice'] 		= esc_html( $invoice_id );
					$emailData['package_name'] 	= esc_html( $package_name );
					$emailData['amount'] 		= wp_strip_all_tags( $amount );
					$emailData['status']        = esc_html( $status );
					$emailData['method']        = esc_html( $method );
					$emailData['date']      	= date( get_option('date_format'),strtotime( $current_date ) );
					$emailData['expiry'] 		= date( get_option('date_format'),strtotime( $featured_date ) );
					$emailData['name'] 			= esc_html( $name );
					$emailData['email_to'] 		= sanitize_email( $user_email );
					$emailData['link'] 			= esc_url( get_the_permalink( $profile_id ) );

					if ( $user_type === 'employer' ) {
						$email_helper->send_subscription_email_to_employer($emailData);
					} else if ( $user_type === 'freelancer' ) {
						$email_helper->send_subscription_email_to_freelancer($emailData);
					}
				}
			}
		}
    }
}

/**
 * Remove payment gateway
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_unused_payment_gateways')) {
    function workreap_unused_payment_gateways($load_gateways) {

        $remove_gateways = array(
            'WC_Gateway_BACS',
            'WC_Gateway_Cheque',
            'WC_Gateway_COD',
        );
		
        foreach ($load_gateways as $key => $value) {
            if (in_array($value, $remove_gateways)) {
                unset($load_gateways[$key]);
            }
        }
		
        return $load_gateways;
    }

}

/**
 * Get packages features
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_package_features')) {

    function workreap_get_package_features($key='') {
		$features	= workreap_get_pakages_features();
		if( !empty( $features[$key] ) ){
			return $features[$key]['title'];
		} else{
			return '';
		}
    }
}

/**
 * Get Hiring freelancer title
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hiring_payment_title')) {

    function workreap_get_hiring_payment_title($key) {
		$hirings	= workreap_get_hiring_payment();
		
		if( !empty( $hirings[$key] ) ){
			return $hirings[$key]['title'];
		} else{
			return '';
		}
	}
}

/**
 * Get Hiring freelancer array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hiring_payment')) {

    function workreap_get_hiring_payment() {
		$hiring	= array(
				'project_id' 	=> array('title' => esc_html__('Project title','workreap')),
				'price'  		=> array('title' => esc_html__('Amount','workreap')),
				'proposal_id'   => array('title' => esc_html__('Freelancer','workreap')),
				'processing_fee'   => array('title' => esc_html__('Processing/taxes fee','workreap')),
			);
		
		return $hiring;
	}
}

/**
 * Get Hiring milestone freelancer array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hiring_milestone_payment')) {

    function workreap_get_hiring_milestone_payment($key) {
		$hiring	= array(
				'project_id' 		=> esc_html__('Project title','workreap'),
				'price'  			=> esc_html__('Amount','workreap'),
				'milestone_id'   	=> esc_html__('Milestone','workreap'),
			);
		
		return $hiring[$key];
	}
}
/**
 * Get Hiring milestone meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hiring_milestone_value')) {

    function workreap_get_hiring_milestone_value($val='',$key='') {
		
		if( !empty($key) && ($key === 'project_id' || $key === 'milestone_id') ) {
			$val 			= esc_html( get_the_title( $val ) );
		}  else if( !empty($key) && $key === 'price' ) {
			$price_symbol	= workreap_get_current_currency();
			$val			= $price_symbol['symbol'].floatval($val);
		}
		
		return $val;
	}
}

/**
 * Get Hiring meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hiring_value')) {

    function workreap_get_hiring_value($val='',$key='') {
		
		if( !empty($key) && $key === 'project_id' ) {
			$val 			= esc_html( get_the_title( $val ) );
		} else if( !empty($key) && $key === 'proposal_id' ) {
			$freelancer_id	= get_post_field('post_author',$val);
			$profile_id		= workreap_get_linked_profile_id( $freelancer_id );
			
			$title			= esc_html( get_the_title( intval($profile_id) ) );
			$permalink		= esc_url( get_the_permalink( $profile_id ));
			$val			= '<a href="'.esc_url($permalink).'" title="'.esc_attr($title).'" >'.esc_html($title).'</a>';
		} else if( !empty($key) && $key === 'price' ) {
			$val			= workreap_price_format( $val ,'return');
		} else if( !empty($key) && $key === 'processing_fee' ) {
			$val			= workreap_price_format( $val ,'return');
		}
		
		return $val;
	}
}

/**
 * Get package Feature values
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_package_feature_value')) {

    function workreap_get_package_feature_value($val='',$key='') {
		if( !empty($key) && $key == 'wt_badget' ) {
			if(!empty($val) ){
				$badges		= get_term( intval($val) );
				if(!empty($badges->name)) {
					$return	= $badges->name;
				} else {
					$return	= '<i class="fa fa-times-circle sp-pk-not-allowed"></i>';
				}
			} else {
				$return	= '<i class="fa fa-times-circle sp-pk-not-allowed"></i>';
			}
		}elseif( isset( $val ) && $val === 'yes' ){
			$return	= '<i class="fa fa-check-circle sp-pk-allowed"></i>';
		} elseif( isset( $val ) && $val === 'no' ){
			$return	= '<i class="fa fa-times-circle sp-pk-not-allowed"></i>';
		} else{
			$return	= $val;
		}
		
		return $return;
	}
}

/**
 * Get Service attributes
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_service_attribute')) {

    function workreap_get_service_attribute($key='',$val) {
		$services		= array();
		$delviery		= array();
		if( function_exists( 'worktic_service_cart_attributes' ) ) {
			$services	= worktic_service_cart_attributes();
		}
		
		$return	= array();
		
		if( !empty( $services[$key] ) ) {
			if( $key === 'service_id' ) {
				$return['title']	= $services[$key];
				$return['value']	= get_the_title($val);
			} else if( $key === 'delivery_time' ) {
				$return['title']	= $services[$key];
				$return['value']	= workreap_get_term_name($val,'delivery');
			} else if( $key === 'delivery_date' ) {
				$return['title']	= $services[$key];
				$return['value']	= date(get_option('date_format'),strtotime($val));
			} else {
				$return['title']	= $services[$key];
				$return['value']	= $val;
			}
			
		} else if( $key === 'addons') {
			if( !empty( $val ) ) {
				$title	= '';
				foreach( $val as $akey => $addon_id ){
					if(!empty($addon_id['price'])){
						$price	= $addon_id['price'];
					}else{
						$price	= get_post_meta($akey,'_price',true);
					}
					
					$title	.= '<p>'.get_the_title($akey).' ('.workreap_price_format( $price ,'return').') </p>';
				}
				
				$return['title']	= esc_html__('Addons','workreap');
				$return['value']	= $title;
			}
		} else if( $key === 'service_price') {
			if( !empty( $val ) ) {
				
				$return['title']	= esc_html__('Service Price','workreap');
				$return['value']	= workreap_price_format( $val ,'return');
			}
		} else if( $key === 'processing_fee') {
			if( !empty( $val ) ) {
				
				$return['title']	= esc_html__('Processing/taxes fee','workreap');
				$return['value']	= workreap_price_format( $val ,'return');
			}
		} 
		return $return;
	}
}

/**
 * Add data in checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_add_new_fields_checkout')) {
	add_filter( 'woocommerce_checkout_after_customer_details', 'workreap_add_new_fields_checkout', 10, 1 );
	function workreap_add_new_fields_checkout() {
		global $product,$woocommerce;
		$cart_data = WC()->session->get( 'cart', null );
		if( !empty( $cart_data ) ) {
			foreach( $cart_data as $key => $cart_items ){
				if( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'hiring' ) {
					$title		= esc_html( get_the_title($cart_items['cart_data']['project_id']) );
					$quantity	= !empty( $cart_items['quantity'] ) ?  $cart_items['quantity'] : 1;

					if( !empty( $cart_items['cart_data'] ) ){?>
					<div class="wt-haslayout">
						<div class="cart-data-wrap">
						  <h3><?php echo esc_html($title);?>( <span class="cus-quantity">×<?php echo esc_html( $quantity );?></span> )</h3>
						  <div class="selection-wrap">
							<?php 
								$counter	= 0;
								foreach( $cart_items['cart_data'] as $key => $value ){
									$counter++;
								?>
								<div class="cart-style"> 
									<span class="style-lable"><?php echo workreap_get_hiring_payment_title( $key );?></span> 
									<span class="style-name"><?php echo workreap_get_hiring_value( $value,$key );?></span> 
								</div>
							<?php }?>
						  </div>
						</div>
					 </div>	
					<?php
					}
				} elseif( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'hiring_service' ) {
					$title		= esc_attr( get_the_title($cart_items['cart_data']['service_id']) );
					$quantity	= !empty( $cart_items['quantity'] ) ?  $cart_items['quantity'] : 1;
					
					
					if( !empty( $cart_items['cart_data'] ) ){?>
					<div class="wt-haslayout">
						<div class="cart-data-wrap">
						  <h3><?php echo esc_html($title);?>( <span class="cus-quantity">×<?php echo esc_attr( $quantity );?></span> )</h3>
						  <div class="selection-wrap">
							<?php 
								$counter	= 0;
								foreach( $cart_items['cart_data'] as $key => $value ){
									$counter++;
									
									$attributes	= workreap_get_service_attribute($key,$value);
									if( !empty( $attributes ) ){
								?>
									<div class="cart-style"> 
										<span class="style-lable"><?php echo esc_html($attributes['title']);;?></span> 
										<span class="style-name"><?php echo do_shortcode($attributes['value']);?></span> 
									</div>
								<?php }?>
							<?php }?>
						  </div>
						</div>
					 </div>	
					<?php
					}
				} elseif( !empty( $cart_items['payment_type'] )  && $cart_items['payment_type'] == 'milestone' ) {
					$title		= esc_attr( get_the_title($cart_items['cart_data']['milestone_id']) );
					$quantity	= !empty( $cart_items['quantity'] ) ?  $cart_items['quantity'] : 1;
					
					
					if( !empty( $cart_items['cart_data'] ) ){?>
						<div class="wt-haslayout">
							<div class="cart-data-wrap">
							<h3><?php echo esc_html($title);?>( <span class="cus-quantity">×<?php echo esc_html( $quantity );?></span> )</h3>
							<div class="selection-wrap">
								<?php 
									$counter	= 0;
									foreach( $cart_items['cart_data'] as $key => $value ){
										$counter++;
									?>
									<div class="cart-style"> 
										<span class="style-lable"><?php echo workreap_get_hiring_milestone_payment( $key );?></span> 
										<span class="style-name"><?php echo workreap_get_hiring_milestone_value( $value,$key );?></span> 
									</div>
								<?php }?>
							</div>
							</div>
						</div>
					 <?php
					}
				} elseif( !empty( $cart_items['payment_type'] ) && $cart_items['payment_type'] === 'subscription') {
					$title		= esc_html(get_the_title($cart_items['product_id']));
					$quantity	= !empty( $cart_items['quantity'] ) ?  $cart_items['quantity'] : 1;

					if( !empty( $cart_items['cart_data'] ) ){
					?>
					<div class="wt-haslayout">
						<div class="cart-data-wrap">
						  <h3><?php echo esc_html($title);?>( <span class="cus-quantity">×<?php echo esc_html( $quantity );?></span> )</h3>
						  <div class="selection-wrap">
							<?php 
								$counter	= 0;
								foreach( $cart_items['cart_data'] as $key => $value ){
									$counter++;
								?>
								<div class="cart-style"> 
									<span class="style-lable"><?php echo workreap_get_package_features( $key );?></span> 
									<span class="style-name" data-v="<?php echo esc_attr( $value );?>"  data-k="<?php echo esc_attr( $key );?>"><?php echo workreap_get_package_feature_value( $value,$key );?></span> 
								</div>
							<?php }?>
						  </div>
						</div>
					 </div>	
					<?php
					}
				}
				
			}
		}
	}
}

/**
 * Add meta on order item
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_woo_convert_item_session_to_order_meta')) {
	add_action( 'woocommerce_new_order_item', 'workreap_woo_convert_item_session_to_order_meta',  1, 3 );
	function workreap_woo_convert_item_session_to_order_meta( $item_id, $item, $order_id ) {
		if ( !empty( $item->legacy_values['cart_data'] ) ) {
			wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
			update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
		}
		
		if ( !empty( $item->legacy_values['payment_type'] ) ) {
			wc_add_order_item_meta( $item_id, 'payment_type', $item->legacy_values['payment_type'] );
			update_post_meta( $order_id, 'payment_type', $item->legacy_values['payment_type'] );
		}
		
		if ( !empty( $item->legacy_values['admin_shares'] ) ) {
			wc_add_order_item_meta( $item_id, 'admin_shares', $item->legacy_values['admin_shares'] );
			update_post_meta( $order_id, 'admin_shares', $item->legacy_values['admin_shares'] );
		}
		
		if ( !empty( $item->legacy_values['freelancer_shares'] ) ) {
			wc_add_order_item_meta( $item_id, 'freelancer_shares', $item->legacy_values['freelancer_shares'] );
			update_post_meta( $order_id, 'freelancer_shares', $item->legacy_values['freelancer_shares'] );
		}
		
		if ( !empty( $item->legacy_values['employer_id'] ) ) {
			wc_add_order_item_meta( $item_id, 'employer_id', $item->legacy_values['employer_id'] );
			update_post_meta( $order_id, 'employer_id', $item->legacy_values['employer_id'] );
		}
		
		if ( !empty( $item->legacy_values['freelancer_id'] ) ) {
			wc_add_order_item_meta( $item_id, 'freelancer_id', $item->legacy_values['freelancer_id'] );
			update_post_meta( $order_id, 'freelancer_id', $item->legacy_values['freelancer_id'] );
		}
		
		if ( !empty( $item->legacy_values['current_project'] ) ) {
			wc_add_order_item_meta( $item_id, 'current_project', $item->legacy_values['current_project'] );
		}

		if ( !empty( $item->legacy_values['cart_data']['processing_fee'] ) ) {
			wc_add_order_item_meta( $item_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
			update_post_meta( $order_id, 'processing_fee', $item->legacy_values['cart_data']['processing_fee'] );
		}

	}
}


/**
 * Get woocommerce session data
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_woo_get_item_data')) {
	function workreap_woo_get_item_data( $cart_item_key, $key = null, $default = null ) {
		global $woocommerce;

		$data = (array)WC()->session->get( 'cart',$cart_item_key );
		if ( empty( $data[$cart_item_key] ) ) {
			$data[$cart_item_key] = array();
		}

		// If no key specified, return an array of all results.
		if ( $key == null ) {
			return $data[$cart_item_key] ? $data[$cart_item_key] : $default;
		}else{
			return empty( $data[$cart_item_key][$key] ) ? $default : $data[$cart_item_key][$key];
		}
		
		
		global $woocommerce;
        $data = (array)WC()->session->get( 'cart',$cart_item_key );
        $data = reset($data);
        if ( empty( $data ) ) {
            $data = array();
        }
		
        // If no key specified, return an array of all results.
        if ( $key == null ) {
            return $data ? $data : $default;
        }else{
            return empty( $data[$key] ) ? $default : $data[$key];
        }
	}
}

/**
 * Display order detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_display_order_data')) {
	add_action( 'woocommerce_thankyou', 'workreap_display_order_data', 20 ); 
	add_action( 'woocommerce_view_order', 'workreap_display_order_data', 20 );
	function workreap_display_order_data( $order_id ) {
		global $product,$woocommerce,$wpdb,$current_user;
		
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		if( !empty( $items ) ) {
			$counter	= 0;
			foreach( $items as $key => $order_item ){
				$counter++;
				$payment_type 	= wc_get_order_item_meta( $key, 'payment_type', true );
				$order_detail 	= wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
				$item_id    	= $order_item['product_id'];
				
				if( !empty($payment_type)  && $payment_type === 'hiring' ) {
					$order_item_name 	= workreap_get_hiring_value($order_detail['project_id'],'project_id');
				}
				
				$name		= !empty( $order_item_name ) ?  $order_item_name : $order_item['name'];
				$quantity	= !empty( $order_item['qty'] ) ?  $order_item['qty'] : 5;
				if( !empty( $order_detail ) ) {?>
					<div class="row">
						<div class="col-md-12">
							<div class="cart-data-wrap">
							  <h3><?php echo esc_html($name);?>( <span class="cus-quantity">×<?php echo esc_html( $quantity );?></span> )</h3>
							  <div class="selection-wrap">
								<?php 
									$counter	= 0;
									foreach( $order_detail as $key => $value ){
										$counter++;
										if(!empty($payment_type)  && $payment_type ==='milestone' ) { ?>
											<div class="cart-style"> 
												<span class="style-lable"><?php echo workreap_get_hiring_milestone_payment( $key );?></span> 
												<span class="style-name"><?php echo workreap_get_hiring_milestone_value( $value,$key );?></span> 
											</div>
										<?php }else if( !empty($payment_type)  && $payment_type === 'hiring' ) {?>
											<div class="cart-style"> 
												<span class="style-lable"><?php echo workreap_get_hiring_payment_title( $key );?></span> 
												<span class="style-name"><?php echo workreap_get_hiring_value( $value,$key );?></span> 
											</div>
										<?php }else if( !empty($payment_type)  && $payment_type === 'hiring_service' ) {
											$attributes	= workreap_get_service_attribute($key,$value);
											if( !empty( $attributes ) ){
												?>
											<div class="cart-style"> 
												<span class="style-lable"><?php echo esc_html($attributes['title']);?></span> 
												<span class="style-name"><?php echo do_shortcode($attributes['value']);?></span> 
											</div>
											<?php }?>
										<?php } else if( !empty( $payment_type ) && $payment_type === 'subscription' ) { ?>
											<div class="cart-style"> 
												<span class="style-lable"><?php echo workreap_get_package_features($key);?></span> 
												<span class="style-name"><?php echo workreap_get_package_feature_value( $value,$key );?></span> 
											</div>
										<?php } ?>
									<?php }?>
							  </div>
							</div>
						 </div>
						 <?php if( !empty( $current_user->ID ) ){?>
							 <div class="col-md-12">
								<a class="wt-btn" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('insights', $current_user->ID); ?>"><?php esc_html_e('Return to dashboard','workreap');?></a>
							 </div>
						 <?php }?>	
					</div>
				<?php
				}
			}
		}
	}
}

/**
 * Print order meta at back-end in order detail page
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_woo_order_meta')) {
	add_filter( 'woocommerce_after_order_itemmeta', 'workreap_woo_order_meta', 10, 3 );
	function workreap_woo_order_meta( $item_id, $item, $_product ) {
		global $product,$woocommerce,$wpdb;
		$order_detail = wc_get_order_item_meta( $item_id, 'cus_woo_product_data', true );
		
		$order_item 		= new WC_Order_Item_Product($item_id);
		$order				= $order_item->get_order();
		$order_status		= $order->get_status();
  		$customer_user 		= get_post_meta( $order->get_id(), '_customer_user', true );
		$payment_type 		= wc_get_order_item_meta( $item_id, 'payment_type', true );

		if( !empty( $order_detail ) ) {?>
			<div class="order-edit-wrap">
				<div class="view-order-detail">
					<a href="#" onclick="event_preventDefault(event);" data-target="#cus-order-modal-<?php echo esc_attr( $item_id );?>" class="cus-open-modal cus-btn cus-btn-sm"><?php esc_html_e('View order detail?','workreap');?></a>
				</div>
				<div class="cus-modal" id="cus-order-modal-<?php echo esc_attr( $item_id );?>">
					<div class="cus-modal-dialog">
						<div class="cus-modal-content">
							<div class="cus-modal-header">
								<a href="#" onclick="event_preventDefault(event);" data-target="#cus-order-modal-<?php echo esc_attr( $item_id );?>" class="cus-close-modal">×</a>
								<h4 class="cus-modal-title"><?php esc_html_e('Order Detail','workreap');?></h4>
							</div>
							<div class="cus-modal-body">
								<div class="sp-order-status">
									<p><?php echo ucwords( $order_status );?></p>
								</div>
								<div class="cus-form cus-form-change-settings">
									<div class="edit-type-wrap">
										<?php 
										$counter	= 0;
										foreach( $order_detail as $key => $value ){
											$counter++;
											
											if( !empty($payment_type) && $payment_type === 'milestone') {?>
												<div class="cus-options-data">
													<label><span><?php echo workreap_get_hiring_milestone_payment($key);?></span></label>
													<div class="step-value">
														<span><?php echo workreap_get_hiring_milestone_value( $value, $key );?></span>
													</div>
												</div>
											<?php } else if( !empty($payment_type) && $payment_type === 'hiring') {?>
												<div class="cus-options-data">
													<label><span><?php echo workreap_get_hiring_payment_title($key);?></span></label>
													<div class="step-value">
														<span><?php echo workreap_get_hiring_value( $value, $key );?></span>
													</div>
												</div>
											<?php } elseif( !empty($payment_type) && $payment_type === 'hiring_service') {
													$attributes	= workreap_get_service_attribute($key,$value);
													if( !empty( $attributes ) ){
													?>
													<div class="cus-options-data">
														<label><span><?php echo esc_html($attributes['title']);?></span></label>
														<div class="step-value">
															<span><?php echo do_shortcode($attributes['value']);?></span>
														</div>
													</div>
												<?php }?>
											<?php } else if( !empty($payment_type) && $payment_type === 'subscription' ) { ?>
												<div class="cus-options-data">
													<label><span><?php echo workreap_get_package_features($key);?></span></label>
													<div class="step-value">
														<span><?php echo workreap_get_package_feature_value( $value, $key );?></span>
													</div>
												</div>
											<?php }
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php						
		}
	}
}

/**
 * Filter woocommerce display itme meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_filter_woocommerce_display_item_meta')) {
	function workreap_filter_woocommerce_display_item_meta( $html, $item, $args ) {
		// make filter magic happen here... 
		return ''; 
	}; 

	// add the filter 
	add_filter( 'woocommerce_display_item_meta', 'workreap_filter_woocommerce_display_item_meta', 10, 3 ); 
}

/**
 * Woocommerce account menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_my_account_menu_items')) {
	add_filter( 'woocommerce_account_menu_items', 'workreap_my_account_menu_items' );
	function workreap_my_account_menu_items( $items ) {
		unset($items['dashboard']);
		unset($items['downloads']);
		unset($items['edit-address']);
		unset($items['payment-methods']);
		unset($items['edit-account']);
		unset($items['orders']);
		unset($items['customer-logout']);
		return $items;
	}
}

/**
 * Hired product ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_get_hired_product_id')) {

    function workreap_get_hired_product_id() {
		$meta_query_args = array();
		$args = array(
			'post_type' 			=> 'product',
			'posts_per_page' 		=> -1,
			'order' 				=> 'DESC',
			'orderby' 				=> 'ID',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts' 	=> 1
		);


		$meta_query_args[] = array(
			'key' 			=> '_workreap_hiring',
			'value' 		=> 'yes',
			'compare' 		=> '=',
		);
		
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$args['meta_query'] 	= $meta_query_args;
		
		$hired_product = get_posts($args);
		
		if (!empty($hired_product)) {
            return (int) $hired_product[0]->ID;
        } else{
			 return 0;
		}
		
	}
}

/**
 * Price override
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_apply_custom_price_to_cart_item')) {
	
	add_action( 'woocommerce_before_calculate_totals', 'workreap_apply_custom_price_to_cart_item', 99 );
	function workreap_apply_custom_price_to_cart_item( $cart_object ) {  
		if( !WC()->session->__isset( "reload_checkout" )) {
			foreach ( $cart_object->cart_contents as $key => $value ) {
				$product 		= $value['data'];
				$product_id		= !empty($value['product_id']) ? $value['product_id'] : 0;
				$original_name  = !empty($product->get_name()) ?  $product->get_name() : '';
				$original_name  = !empty($original_name) && !empty($product_id) ?  get_the_title($product_id) : $original_name;

				if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'hiring' ){
					if( isset( $value['cart_data']['price'] ) ){
						$bk_price = floatval( $value['cart_data']['price'] );
						$value['data']->set_price($bk_price);
					}

					$new_name 	= !empty($value['cart_data']['project_id']) ? get_the_title($value['cart_data']['project_id']) : $original_name;
				} else if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'hiring_service' ){
					if( isset( $value['cart_data']['price'] ) ){
						$bk_price = floatval( $value['cart_data']['price'] );
						$value['data']->set_price($bk_price);
					}

					$new_name 	= !empty($value['cart_data']['service_id']) ? get_the_title($value['cart_data']['service_id']) : $original_name;
				} else if( !empty( $value['payment_type'] ) && $value['payment_type'] == 'milestone' ){
					if( isset( $value['cart_data']['price'] ) ){
						$bk_price = floatval( $value['cart_data']['price'] );
						$value['data']->set_price($bk_price);
					}

					$new_name 	= !empty($value['cart_data']['milestone_id']) ? get_the_title($value['cart_data']['milestone_id']) : $original_name;
				}

				if( !empty($new_name) && method_exists( $product, 'set_name' ) ){
					$product->set_name( $new_name );
				}
			}   
		}
	}
}

/**
 * Cart fees for employers
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_cart_calculate_fees')) {
	
	add_action( 'woocommerce_cart_calculate_fees', 'workreap_cart_calculate_fees', 99 );
	function workreap_cart_calculate_fees( $cart_object ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ){
			return;
		}
		
		$item_count 	= 0;
		
		foreach( WC()->cart->get_cart() as $values ) {
			$item = $values['data'];
			if ( empty( $item ) ){
				break;
			}
			
			$fee	= !empty($values['cart_data']['processing_fee']) ? $values['cart_data']['processing_fee'] : 0.0;
			$item_id = $item->get_id();
			$item_count++;
		}
		
		if(!empty($fee)){
			$fee = $item_count *  $fee;
			WC()->cart->add_fee( esc_html__('Processing/taxes fee','workreap'), $fee, false );
		}
	}
}

/**
 * Add product type options
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_product_type_options')) {
	add_filter('product_type_options', 'workreap_product_type_options', 10, 1);
	function workreap_product_type_options( $options ) {
		if(current_user_can('administrator')) {
			$options['workreap_hiring'] = array(
				'id' 			=> '_workreap_hiring',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label' 		=> esc_html__('Hire Freelancer', 'workreap'),
				'description' 	=> esc_html__('Hire freelancer product will be used to make the payment for the project/job', 'workreap'),
				'default' => 'no'
			);
		}
		
		return $options;
	}
}

/**
 * Save products meta
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_woocommerce_process_product_meta')) {
	add_action('woocommerce_process_product_meta_variable', 'workreap_woocommerce_process_product_meta', 10, 1);
	add_action('woocommerce_process_product_meta_simple', 'workreap_woocommerce_process_product_meta', 10, 1);
	function workreap_woocommerce_process_product_meta( $post_id ) {
		if(!empty($_POST['_workreap_hiring'])){
			workreap_update_hiring_product();
			$is_workreap_hiring	= isset($_POST['_workreap_hiring']) ? 'yes' : 'no';
			update_post_meta($post_id, '_workreap_hiring', $is_workreap_hiring);
		}
	}
}

/**
 * Update hiring product
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('workreap_update_hiring_product')) {

    function workreap_update_hiring_product() {
		$meta_query_args = array();
		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'order' 			=> 'DESC',
			'orderby'			=> 'ID',
			'post_status' 		=> 'publish',
			'ignore_sticky_posts' => 1
		);


		$meta_query_args[] = array(
			'key' 			=> '_workreap_hiring',
			'value' 		=> 'yes',
			'compare' 		=> '=',
		);
		
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$args['meta_query'] 	= $meta_query_args;
		
		$booking_product = get_posts($args);
		
		if (!empty($booking_product)) {
            $counter = 0;
            foreach ($booking_product as $key => $product) {
                update_post_meta($product->ID, '_workreap_hiring', 'no');
            }
        }
		
	}
}

/**
 * Remove Product link in checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
add_filter( 'woocommerce_order_item_permalink', '__return_false' );
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );