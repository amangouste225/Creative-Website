<?php
if (!class_exists('AndroidAppGetMilestoneRoutes')) {

    class AndroidAppGetMilestoneRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'milestone';

            register_rest_route($namespace, '/' . $base . '/get_job_milestone_details',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_job_milestone_details'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);

			register_rest_route($namespace, '/' . $base . '/create_milestone',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'create_milestone'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			register_rest_route($namespace, '/' . $base . '/milstone_request',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'milstone_request'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/complete_milestone',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'complete_milestone'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//milestone details
			register_rest_route($namespace, '/' . $base . '/milestones_details',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'milestones_details'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/add_milestone',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'add_milestone'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/list_milestone',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'list_milestone'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/send_milestone_request',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'send_milestone_request'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/approve_milestone_request',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'approve_milestone_request'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/cancel_milestone_request',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'cancel_milestone_request'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
		}
		
		/**
         * Complete milestone by employer
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function complete_milestone($request){
			$json 			= array();
			$current_date 	= current_time('mysql');
			$milestone_id	= !empty($request['milestone_id']) ? intval($request['milestone_id']) : '';
			$completed_date	= date('Y-m-d H:i:s', strtotime($current_date));
			
			$required 	= array(
				'milestone_id'	=> esc_html__('Milestone ID is required', 'workreap_api'),
			);
			
			foreach ($required as $key => $value) {
				if( empty( $request[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					return new WP_REST_Response($json, 203);
				}
			}
			
			$milestone_title 	= get_the_title($milestone_id);

			$project_id 		= get_post_meta($milestone_id, '_project_id', true);
			$freelancer_id 		= get_post_meta($project_id, '_freelancer_id', true);

			$freelancer_name 	= workreap_get_username('', $freelancer_id);
			$profile_id			= workreap_get_linked_profile_id($freelancer_id, 'post');	
			$user_email 		= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';

			$update		= array( 'status' 		=> 'completed' );
			$where		= array( 'milestone_id' => $milestone_id );
			workreap_update_earning( $where, $update, 'wt_earnings');

			// complete service
			$order_id			= get_post_meta($milestone_id,'_order_id',true);
			if ( class_exists('WooCommerce') && !empty( $order_id )) {
				$order = wc_get_order( intval($order_id ) );
				if( !empty( $order ) ) {
					$order->update_status( 'completed' );
				}
			}

			update_post_meta( $milestone_id, '_status', 'completed' );
			update_post_meta( $milestone_id, '_completed_date', $completed_date );

			$project_title		= get_the_title($project_id);
			$project_link		= get_the_permalink($project_id);


			//Send email to freelancer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapMilestoneRequest')) {
					$email_helper = new WorkreapMilestoneRequest();
					$emailData = array();

					$emailData['freelancer_name'] 	= esc_html( $freelancer_name);
					$emailData['milestone_title'] 	= esc_html( $milestone_title);
					$emailData['project_title'] 	= esc_html( $project_title);
					$emailData['project_link'] 		= esc_html( $project_link);
					$emailData['email_to'] 			= esc_html( $user_email);

					$email_helper->send_completed_milestone_to_freelancer_email($emailData);

					//Push notification
					$push						= array();
					$push['freelancer_id']		= $profile_id;
					$push['project_id']			= $project_id;
					$push['type']				= 'milestone_completed';
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%milestone_title%']	= $emailData['milestone_title'];
					$push['%project_title%']	= $emailData['project_title'];
					$push['%project_link%']		= $emailData['project_link'];

					$push['%replace_milestone_title%']	= $emailData['milestone_title'];

					do_action('workreap_user_push_notify',array($profile_id),'','pusher_ml_completed_content',$push);

				}
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Milestone is completed successfully', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}
		
		/**
         * Create milestone
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function create_milestone($request){
			$json		= array();
			$required 	= array(
				'id'   				=> esc_html__('Proposal is required', 'workreap_api'),
				'title'   			=> esc_html__('Milestone title is required', 'workreap_api'),
				'due_date'  		=> esc_html__('Due date is required', 'workreap_api'),
				'price'  			=> esc_html__('Price is required', 'workreap_api')
			);
			
			foreach ($required as $key => $value) {
				if( empty( $request[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					return new WP_REST_Response($json, 203);
				}
			}

			$proposal_id	= !empty($request['id']) ? intval($request['id']) : '';
			$milstone_id	= !empty($request['milestone_id']) ? intval($request['milestone_id']) : '';
			$user_id		= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$project_id		= !empty($proposal_id) ? get_post_meta($proposal_id,'_project_id',true) : '';
			$price			= !empty($request['price']) ? $request['price'] : '';
			$due_date		= !empty($request['due_date']) ? $request['due_date'] : '';
			$title			= !empty($request['title']) ? $request['title'] : '';
			$description	= !empty($request['description']) ? $request['description'] : '';
			
			$proposal_price					= get_post_meta( $proposal_id, '_amount', true );
			$proposal_price					= !empty($proposal_price) ? $proposal_price : 0;
			$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
			$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
			$remaning_price	= ($proposal_price) > ($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;
			
			$remaning_price	= (string) $remaning_price;
			
			if( ( $price > $remaning_price) && empty($milstone_id) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');       
				return new WP_REST_Response($items, 203);
			} else if(!empty($milstone_id)){
				$old_price	= get_post_meta($milstone_id,'_price',true);
				$old_price	= !empty($old_price) ? $old_price : 0;
				$new_price	= $old_price+ $remaning_price;
				
				if( empty($remaning_price) && $price > $old_price ) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');        
					return new WP_REST_Response($json, 203);

				} else if($price > $new_price ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');        
					return new WP_REST_Response($json, 203);
				}
			}

			if(empty($milstone_id)) {
				$milestone_post = array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_status'   => 'pending',
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'wt-milestone',
				);

				$milstone_id    		= wp_insert_post( $milestone_post );
				update_post_meta( $milstone_id, '_status', 'pending' );
			} else if( !empty($milstone_id) ) {
				$milestone_post = array(
					'ID'			=> $milstone_id,
					'post_title'    => wp_strip_all_tags( $title ),
					'post_content'  => $description,
					'post_type'     => 'wt-milestone',
				);
				
				wp_update_post( $milestone_post );
			}
			
			if(!empty($milstone_id )){
				$freelancer_id			= get_post_field('post_author', $proposal_id);
				
				$fw_options	= array();
				$fw_options['projects']	= $project_id;
				$fw_options['price']	= $price;
				$fw_options['due_date']	= $due_date;
				fw_set_db_post_option($milstone_id, null, $fw_options);

				update_post_meta($milstone_id,'_freelancer_id',$freelancer_id);
				update_post_meta($milstone_id,'_propsal_id',$proposal_id);
				update_post_meta($milstone_id,'_project_id',$project_id);
				update_post_meta($milstone_id,'_price',$price);
				update_post_meta($milstone_id,'_due_date',$due_date);

			}

			if(!empty($milstone_id)){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('You have successfully updated/added milestone.','workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('There are some errors, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}

		/**
         * Milestone Request
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */

		public function milstone_request($request) {
			$json				= array();
			$proposal_id		= !empty($request['id']) ? intval($request['id']) : '';
			if( empty($proposal_id) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Proposal ID is required.','workreap_api');        
				return new WP_REST_Response($json, 203);
			}
			
			$project_id			= get_post_meta($proposal_id, '_project_id', true);
	
			$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);
	
			update_post_meta( $proposal_id, '_proposal_status', 'pending' );
			update_post_meta( $proposal_id, '_proposal_type', 'milestone' );
			
			$freelancer_id				= get_post_field('post_author', $proposal_id);
			$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
			$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
			$employer_id				= get_post_field('post_author', $project_id);
			$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
			$employer_name 				= workreap_get_username('', $employer_linked_profile);
			$employer_link 				= esc_url(get_the_permalink($employer_linked_profile));
			$project_title				= get_the_title($project_id);
			$project_link				= get_the_permalink($project_id);
			$proposed_duration  		= get_post_meta($proposal_id, '_proposed_duration', true);
			$duration_list				= worktic_job_duration_list();
			$duration					= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
			$profile_id					= workreap_get_linked_profile_id($freelancer_linked_profile, 'post');
			$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';
	
			//Send email to freelancer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapMilestoneRequest')) {
					$email_helper 	= new WorkreapMilestoneRequest();
					$emailData 		= array();
					
					$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
					$emailData['employer_name'] 	= esc_html( $employer_name);
					$emailData['employer_link'] 	= esc_html( $employer_link);
					$emailData['project_title'] 	= esc_html( $project_title);
					$emailData['project_link'] 		= esc_html( $project_link);
					$emailData['proposal_amount'] 	= workreap_price_format($proposed_amount, 'return');
					$emailData['proposal_duration'] = esc_html( $duration);
					$emailData['email_to'] 			= esc_html( $user_email);
	
					$email_helper->send_milestone_request_email($emailData);
					//Push notification
					$push						= array();
					$push['freelancer_id']		= $profile_id;
					$push['employer_id']		= $employer_id;
					$push['project_id']			= $project_id;
					$push['type']				= 'milestone_send_request';
					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%project_title%']	= $emailData['project_title'];
					$push['%project_link%']		= $emailData['project_link'];

					do_action('workreap_user_push_notify',array($profile_id),'','pusher_ml_rec_content',$push);
				}
			}
	
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Request sent successfully to the freelancer.', 'workreap_api');
			return new WP_REST_Response($json, 200);
		}

        /**
         * Get Job Milestone Detail
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_job_milestone_details($request){
			$json				= array();
			$items				= array();

			$proposal_id	= !empty($request['proposal_id']) ? $request['proposal_id'] : '';
			$date_format	= get_option('date_format');
			$project_id		= get_post_meta( $proposal_id, '_project_id', true );

			$total_price				= get_post_meta( $proposal_id, '_amount', true );
			$json['total_price']		= !empty($total_price) ? workreap_price_format($total_price,'return') : 0;
			$proposal_status			= get_post_meta($proposal_id,'_proposal_status',true);
			$json['proposal_status']	= !empty($proposal_status) ? $proposal_status : '';
			$completed_price			= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'completed','amount') :'';
			$json['completed_price']	= !empty($completed_price) ? workreap_price_format($completed_price,'return') : workreap_price_format(0,'return');

			$hired_price				= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'hired','amount') : 0;
			$json['hired_price']		= !empty($hired_price) ? workreap_price_format($hired_price,'return') : workreap_price_format(0,'return');
			
			$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
			$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
			$json['total_milestone_price']	= $total_milestone_price;
			$pending_price					= workreap_get_milestone_statistics($proposal_id,'pending');
			$json['pending_price']			= !empty($pending_price) ? workreap_price_format($pending_price,'return') : workreap_price_format(0,'return');
			$json['milestone_pending_price']		= intval($total_price) > intval($total_milestone_price) ? $total_price - $total_milestone_price : 0;
			
			$args 			= array(
				'posts_per_page' 	=> -1,
				'post_type' 		=> 'wt-milestone',
				'post_status' 		=> array('pending','publish'),
				'suppress_filters' 	=> false
			);
			
			$meta_query_args	= array();
			
			$meta_query_args[] 	= array(
					'key' 		=> '_propsal_id',
					'value' 	=> $proposal_id,
					'compare' 	=> '='
				);
			
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($args);
			$miletone_array		= array();
			
			if( $query->have_posts() ){
				$milestone	= array();
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$milestone['milstone_title']		= get_the_title($post->ID);
					$milestone['milstone_content']		= get_post_field('post_content',$post->ID);

					$milstone_price_single		= get_post_meta( $post->ID, '_price', true );
					$milstone_date				= get_post_meta( $post->ID, '_due_date', true );

					$milestone['milstone_date']	= $milstone_date;
					$milstone_due_date			= !empty($milstone_date) ? date($date_format, strtotime($milstone_date)) : '';
					$milestone['milstone_date_formate']	= $milstone_due_date;
					$milstone_price				= !empty($milstone_price_single) ? workreap_price_format($milstone_price_single,'return') : '';
					$milestone['price_formate']	= $milstone_price;
					$milstone_status			= get_post_status($post->ID);
					$milestone['price']			= $milstone_price_single;
					$milestone['updated_status']	= !empty($milstone_status) ? $milstone_status : '';

					$order_id	= get_post_meta( $post->ID, '_order_id', true );
					$milestone['order_id']	= !empty($order_id) ? intval($order_id) : 0;
					$order_url	= '';
				
					if( !empty( $milestone['order_id'] ) ){
						if( class_exists('WooCommerce') ) {
							$order		= wc_get_order($milestone['order_id']);
							$order_url	= $order->get_view_order_url();
						}
					}
				
					$milestone['order_url']	= $order_url;
					$miletone_array[]		= $milestone;
				endwhile;
				
			}

			$json['milestones']	= $miletone_array;
			$json['type'] 		= 'success';
			$items[] 			= $json;
			return new WP_REST_Response($items, 200);
		}
		
		/**
         * Cancel Milestone
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function cancel_milestone_request($request) {
			$json				= array();
			$items				= array();
			
			if( function_exists('workreap_is_demo_site') ) { 
				workreap_is_demo_site() ;
			}; //if demo site then prevent

			$user_id				= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$proposal_id			= !empty($request['proposal_id']) ? intval($request['proposal_id']) : '';
			$project_id				= get_post_meta($proposal_id, '_project_id', true);
			$cancelled_reason		= !empty($request['cancelled_reason']) ? ($request['cancelled_reason']) : '';
			$json					= array();
			$update_post			= array();

			if(empty($proposal_id)){
				$json['type'] = 'error';
				$json['message'] = esc_html__('Proposal ID is required', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			if(empty($cancelled_reason)){
				$json['type'] = 'error';
				$json['message'] = esc_html__('Cancelled reason is required', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			if(!empty($proposal_id) && !empty($cancelled_reason)) {
				update_post_meta( $proposal_id, '_cancelled_reason', $cancelled_reason );
				update_post_meta( $proposal_id, '_proposal_status', 'cancelled' );
				update_post_meta( $proposal_id, '_cancelled_user_id', $user_id );
				$update_post	= array(
									'ID'    		=>  $proposal_id,
									'post_status'   =>  'cancelled'
								);	
				
				wp_update_post($update_post);

				$freelancer_id				= get_post_field('post_author', $proposal_id);
				$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
				$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile );
				$freelancer_link 		    = esc_url(get_the_permalink($freelancer_linked_profile));
				$employer_id				= get_post_field('post_author', $project_id);
				$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
				$employer_name 				= workreap_get_username('', $employer_linked_profile );
				$project_title				= get_the_title($project_id);
				$project_link				= get_the_permalink($project_id);
				$profile_id					= workreap_get_linked_profile_id($employer_linked_profile, 'post');
				$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';

				//Send email to employer
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapMilestoneRequest')) {
						$email_helper 					= new WorkreapMilestoneRequest();
						$emailData 						= array();
						$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
						$emailData['freelancer_link'] 	= esc_html( $freelancer_link);
						$emailData['employer_name'] 	= esc_html( $employer_name);
						$emailData['project_title'] 	= esc_html( $project_title);
						$emailData['project_link'] 		= esc_html( $project_link);
						$emailData['reason'] 			= esc_html( $cancelled_reason);
						$emailData['email_to'] 			= esc_html( $user_email);
						$email_helper->send_milestone_request_rejected_email($emailData);
						//Push notification
						$push						= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $employer_id;
						$push['service_id']			= $project_id;
						$push['type']				= 'milestone_cancelled';
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['project_title'];
						$push['%project_link%']		= $emailData['project_link'];
						$push['%replace_message%']	= wp_strip_all_tags($emailData['reason']);
						
						do_action('workreap_user_push_notify',array($employer_id),'','pusher_ml_req_rej_content',$push);

					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Settings Updated.', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
		}
		
		/**
         * Approve Milestone
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function approve_milestone_request($request) {
			$json				= array();
			$items				= array();
			
			if( function_exists('workreap_is_demo_site') ) { 
				workreap_is_demo_site() ;
			}; //if demo site then prevent

			$proposal_id		= !empty($request['proposal_id']) ? intval($request['proposal_id']) : '';
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$status				= !empty($request['status']) ? $request['status'] : '';
			
			$meta_query_args	= array();
			if(!empty($status) && $status === 'approved' ){
				$args 			= array(
									'posts_per_page' 	=> -1,
									'post_type' 		=> 'wt-milestone',
									'suppress_filters' 	=> false
								);
				
				$meta_query_args[] = array(
									'key' 		=> '_propsal_id',
									'value' 	=> $proposal_id,
									'compare' 	=> '='
								);
				$query_relation 	= array('relation' => 'AND',);
				$args['meta_query'] = array_merge($query_relation, $meta_query_args);
				$query 				= new WP_Query($args);

				while ($query->have_posts()) : $query->the_post();
					global $post;
					update_post_meta( $post->ID, '_status', 'pay_now' );
				endwhile;

				wp_reset_postdata();

				$project_id	= get_post_meta( $proposal_id, '_project_id', true );
				if(!empty($proposal_id) && !empty($project_id)){
					workreap_hired_freelancer_after_payment($project_id, $proposal_id);
				}

				$freelancer_id				= get_post_field('post_author', $proposal_id);
				$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
				$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
				$freelancer_link 		    = esc_url(get_the_permalink($freelancer_linked_profile));
				$employer_id				= get_post_field('post_author', $project_id);
				$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
				$employer_name 				= workreap_get_username('', $employer_linked_profile );
				$project_title				= get_the_title($project_id);
				$project_link				= get_the_permalink($project_id);
				$profile_id					= workreap_get_linked_profile_id($employer_linked_profile, 'post');
				$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';
			}

			update_post_meta( $proposal_id, '_proposal_status', $status );

			//Send email to freelancer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapMilestoneRequest')) {
					$email_helper 					= new WorkreapMilestoneRequest();
					$emailData 						= array();
					$emailData['freelancer_name'] 	= esc_html($hired_freelancer_title);
					$emailData['freelancer_link'] 	= esc_html($freelancer_link);
					$emailData['employer_name'] 	= esc_html($employer_name);
					$emailData['project_title'] 	= esc_html($project_title);
					$emailData['project_link'] 		= esc_html($project_link);
					$emailData['email_to'] 			= esc_html( $user_email);

					$email_helper->send_milestone_request_approved_email($emailData);

					$push						= array();
					$push['freelancer_id']		= $freelancer_id;
					$push['employer_id']		= $employer_id;
					$push['project_id']			= $project_id;
					$push['type']				= 'milestone_request_approved';
					$push['%freelancer_link%']	= $emailData['freelancer_link'];
					$push['%freelancer_name%']	= $emailData['freelancer_name'];
					$push['%employer_name%']	= $emailData['employer_name'] ;
					$push['%employer_link%']	= $emailData['employer_link'];
					$push['%project_title%']	= $emailData['project_title'];
					$push['%project_link%']		= $emailData['project_link'];
					
					do_action('workreap_user_push_notify',array($employer_id),'','pusher_ml_req_appr_content',$push);
				}
			}
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('You have successfully update proposal request.', 'workreap_api');
			$items[] 			= $json;
			return new WP_REST_Response($items, 200);
		}
		
		/**
         * Send Request Milestone
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function send_milestone_request($request) {
			$json				= array();
			$items				= array();
			
			if( function_exists('workreap_is_demo_site') ) { 
				workreap_is_demo_site() ;
			}; //if demo site then prevent

			$proposal_id		= !empty($request['id']) ? intval($request['id']) : '';
			
			if(empty($proposal_id)){
				$json['type'] = 'success';
				$json['message'] = esc_html__('Proposal ID is required.', 'workreap_api');

				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}

			$project_id			= get_post_meta($proposal_id, '_project_id', true);
			$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);

			update_post_meta( $proposal_id, '_proposal_status', 'pending' );
			update_post_meta( $proposal_id, '_proposal_type', 'milestone' );

			$freelancer_id				= get_post_field('post_author', $proposal_id);
			$freelancer_linked_profile	= workreap_get_linked_profile_id($freelancer_id);
			$hired_freelancer_title 	= workreap_get_username('', $freelancer_linked_profile);
			$employer_id				= get_post_field('post_author', $project_id);
			$employer_linked_profile	= workreap_get_linked_profile_id($employer_id);
			$employer_name 				= workreap_get_username('', $employer_linked_profile);
			$employer_link 				= esc_url(get_the_permalink($employer_linked_profile));
			$project_title				= get_the_title($project_id);
			$project_link				= get_the_permalink($project_id);
			$proposed_duration  		= get_post_meta($proposal_id, '_proposed_duration', true);
			$duration_list				= worktic_job_duration_list();
			$duration					= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
			$profile_id					= workreap_get_linked_profile_id($freelancer_linked_profile, 'post');
			$user_email 				= !empty( $profile_id ) ? get_userdata( $profile_id )->user_email : '';
			
			//Send email to freelancer
			if (class_exists('Workreap_Email_helper')) {
				if (class_exists('WorkreapMilestoneRequest')) {
					$email_helper 					= new WorkreapMilestoneRequest();
					$emailData 						= array();
					$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
					$emailData['employer_name'] 	= esc_html( $employer_name);
					$emailData['employer_link'] 	= esc_html( $employer_link);
					$emailData['project_title'] 	= esc_html( $project_title);
					$emailData['project_link'] 		= esc_html( $project_link);
					$emailData['proposal_amount'] 	= workreap_price_format($proposed_amount, 'return');
					$emailData['proposal_duration'] = esc_html( $duration);
					$emailData['email_to'] 			= esc_html( $user_email);

					$email_helper->send_milestone_request_email($emailData);
				}
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Request sent successfully to the freelancer.', 'workreap_api');
			$items[] 			= $json;
			return new WP_REST_Response($items, 200);
		}
		
		
		/**
         * List Milestone
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function list_milestone($request) {
			$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			
			$json				= array();
			$item				= array();
			$items				= array();
			$user_identity 		= $user_id;
			$url_identity 	 	= $user_identity;
			$linked_profile  	= workreap_get_linked_profile_id($user_identity);
			$post_id 		 	= $linked_profile;

			$date_format			= get_option('date_format');
			$proposal_id			= !empty($request['id']) ? intval($request['id']) : '';
			$project_id				= get_post_meta( $proposal_id, '_project_id', true );
			$project_status			= get_post_status($project_id);
			$post_author			= get_post_field('post_author', $project_id);
			$hired_freelancer_id	= get_post_field('post_author', $proposal_id);
			$post_status			= get_post_status($proposal_id);
			$hired_freelance_id		= !empty( $hired_freelancer_id ) ? intval( $hired_freelancer_id ) : '';
			$hire_linked_profile	= workreap_get_linked_profile_id($hired_freelance_id); 
			$hired_freelancer_title	= get_the_title( $hire_linked_profile );
			$job_statuses			= worktic_job_statuses();
			$proposal_price			= get_post_meta( $proposal_id, '_amount', true );
			$proposal_price			= !empty($proposal_price) ? $proposal_price : 0;
			$user_type				= apply_filters('workreap_get_user_type', $user_identity );

			$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
			$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
			$meta_array	= array(
							array(
								'key'		=> '_propsal_id',
								'value'   	=> $proposal_id,
								'compare' 	=> '=',
								'type' 		=> 'NUMERIC'
							),
							array(
								'key'		=> '_status',
								'value'   	=> 'completed',
								'compare' 	=> '=',
							)
						);
			
			$completed	= workreap_get_post_count_by_meta('wt-milestone','publish',$meta_array);
			$completed	= !empty($completed) ? intval($completed) : 0;

			$remaning_price	= intval($proposal_price) > intval($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;

			$hired_freelancer_avatar 	= apply_filters(
				'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
			);

			$proposal_status				= get_post_meta($proposal_id,'_proposal_status',true);
			$proposal_status				= !empty($proposal_status) ? $proposal_status : '';

			$order 			 = 'DESC';
			$sorting 		 = 'ID';
			$meta_query_args = array();
				
			$args 			= array(
								'posts_per_page' 	=> -1,
								'post_type' 		=> 'wt-milestone',
								'orderby' 			=> $sorting,
								'order' 			=> $order,
								'post_status' 		=> array('pending','publish'),
								'suppress_filters' 	=> false
							);
			
							$meta_query_args[] = array(
								'key' 		=> '_propsal_id',
								'value' 	=> $proposal_id,
								'compare' 	=> '='
							);
			
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($args);
			
			if( $query->have_posts() ){
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$milstone_title		= get_the_title($post->ID);
					$milstone_content	= get_post_field('post_content',$post->ID);
					$milstone_price_single		= get_post_meta( $post->ID, '_price', true );
					$milstone_date		= get_post_meta( $post->ID, '_due_date', true );
					$milstone_due_date	= str_replace('/','-',$milstone_date); 
					$milstone_due_date	= !empty($milstone_due_date) ? date_i18n($date_format, strtotime($milstone_due_date)) : '';
								
					$milstone_price		= !empty($milstone_price_single) ? html_entity_decode(workreap_price_format($milstone_price_single,'return')) : '';

					$milstone_status	= get_post_status($post->ID);
					$edit_price			= $remaning_price+$milstone_price_single;

					$updated_status	= get_post_meta($post->ID,'_status',true);
					$updated_status	= !empty($updated_status) ? $updated_status : '';
					$status_class	= '';
					$status_text	= '';
					$status_option	= '';

					$order_id	= get_post_meta( $post->ID, '_order_id', true );
					$order_id	= !empty($order_id) ? intval($order_id) : 0;
					$order_url	= '';
				
					if( !empty( $order_id ) ){
						if( class_exists('WooCommerce') ) {
							$order		= wc_get_order($order_id);
							$order_url	= $order->get_view_order_url();
						}
					}
					$option	= '';
					if(!empty($updated_status) && !empty($user_type) && $user_type === 'employer'){
						if( ($updated_status === 'pay_now' || $updated_status === 'pending') && ( !empty($proposal_status) && $proposal_status === 'approved' && empty($order_id) )  ) {
							$status_text	= esc_html__( 'Pay Now', 'workreap_api' );
							$status_class	= 'green';
							$option	= 'pay_now';
						} else if($updated_status === 'pending') {
							$status_text	= 'pending';
							$option	= 'pending';
						} else if($updated_status === 'hired') {
							$status_text	= esc_html__( 'Hired', 'workreap_api' );
							$option	= 'hired';
						} else if($updated_status === 'completed') {
							$option	= 'completed';
							$status_text	= esc_html__( 'Completed', 'workreap_api' );
							$status_class	= '';
						}
					} else if(!empty($updated_status) && !empty($user_type) && $user_type === 'freelancer'){
						if( ($updated_status === 'pay_now' || $updated_status === 'pending') && ( !empty($proposal_status) && $proposal_status === 'approved')  ) {
							$status_text	= esc_html__( 'Pending', 'workreap_api' );
							$status_class	= 'pending';
						} else if($updated_status === 'pending') {
							$status_text	= esc_html__( 'Pending', 'workreap_api' );
							$status_class	= 'pending';
						} else if($updated_status === 'hired') {
							$status_text	= esc_html__( 'Hired', 'workreap_api' );
							$status_class	= 'green';
						} else if($updated_status === 'completed') {
							$status_class	= 'green';
							$status_text	= esc_html__( 'Completed', 'workreap_api' );
						}
					}

					$json['milestone_id']			= intval($post->ID);
					$json['milestone_price']		= $milstone_price;
					$json['milestone_title']		= $milstone_title;
					$json['milestone_due_date']		= $milstone_due_date;
					$json['updated_status']			= $updated_status;
					$json['status_class']			= $status_class;
					$json['milestone_option']		= $option;
					$json['order_id']				= $order_id;
					$json['milestone_content']		= $milstone_content;
					$json['status_text']			= $status_text;
					$json['milestone_date']			= $milstone_date;
					$json['milestone_price_single']	= $milstone_price_single;
					$json['proposal_id']			= $proposal_id;
						
					$item[]	= $json;
					endwhile;
					wp_reset_postdata();
				}
			
			$items				    = maybe_unserialize($item);	
			return new WP_REST_Response($items, 200);
		}
		
		/**
         * Add Milestone
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function add_milestone($request) {
			$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			
			$json				= array();
			$items				= array();
			
			if( function_exists('workreap_is_demo_site') ) { 
				workreap_is_demo_site() ;
			}; //if demo site then prevent


			$required = array(
				'id'   				=> esc_html__('Proposal ID is required', 'workreap_api'),
				'title'   			=> esc_html__('Milestone title is required', 'workreap_api'),
				'due_date'  		=> esc_html__('Due date is required', 'workreap_api'),
				'price'  			=> esc_html__('Price is required', 'workreap_api')
			);

			foreach ($required as $key => $value) {
				if( empty( $request[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			}

			$proposal_id	= !empty($request['id']) ? intval($request['id']) : '';
			$milstone_id	= !empty($request['milestone_id']) ? intval($request['milestone_id']) : '';
			$project_id		= !empty($proposal_id) ? get_post_meta($proposal_id,'_project_id',true) : '';
			$price			= !empty($request['price']) ? $request['price'] : '';
			$due_date		= !empty($request['due_date']) ? $request['due_date'] : '';
			$title			= !empty($request['title']) ? $request['title'] : '';
			$description	= !empty($request['description']) ? $request['description'] : '';

			$proposal_price					= get_post_meta( $proposal_id, '_amount', true );
			$proposal_price					= !empty($proposal_price) ? $proposal_price : 0;
			$total_milestone_price			= workreap_get_milestone_statistics($proposal_id,array('pending','publish'));
			$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
			$remaning_price					= ($proposal_price) > ($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;

			$remaning_price	= (string) $remaning_price;

			if( ( $price > $remaning_price ) && empty($milstone_id) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');       
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			} else if(!empty($milstone_id)){
				$old_price	= get_post_meta($milstone_id,'_price',true);
				$old_price	= !empty($old_price) ? $old_price : 0;
				$new_price	= $old_price+ $remaning_price;

				if( empty($remaning_price) && $price > $old_price ) {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');        
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);

				} else if($price > $new_price ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Price is greater then remaining price','workreap_api');        
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			}

			if(empty($milstone_id)) {
				$milestone_post = array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_status'   => 'pending',
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'wt-milestone',
				);

				$milstone_id    		= wp_insert_post( $milestone_post );
				update_post_meta( $milstone_id, '_status', 'pending' );
			} else if( !empty($milstone_id) ) {
				$milestone_post = array(
					'ID'			=> $milstone_id,
					'post_title'    => wp_strip_all_tags( $title ),
					'post_content'  => $description,
					'post_type'     => 'wt-milestone',
				);

				wp_update_post( $milestone_post );
			}

			if(!empty($milstone_id )){
				$freelancer_id			= get_post_field('post_author', $proposal_id);

				$fw_options	= array();
				$fw_options['projects']	= $project_id;
				$fw_options['price']	= $price;
				$fw_options['due_date']	= $due_date;
				fw_set_db_post_option($milstone_id, null, $fw_options);

				update_post_meta($milstone_id,'_freelancer_id',$freelancer_id);
				update_post_meta($milstone_id,'_propsal_id',$proposal_id);
				update_post_meta($milstone_id,'_project_id',$project_id);
				update_post_meta($milstone_id,'_price',$price);
				update_post_meta($milstone_id,'_due_date',$due_date);

			}

			if(!empty($milstone_id)){
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('You have successfully update/added the milestone.','workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('There are some errors, please try again later', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}
		}
		
		/**
         * Milestone price details
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function milestones_details($request) {
			$json				= array();
			$items				= array();
			$proposal_id		= !empty( $request['proposal_id'] ) ? intval( $request['proposal_id'] ) : '';
			$post_status		= get_post_status($proposal_id);
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$project_id		= get_post_meta( $proposal_id, '_project_id', true );
			$total_price	= get_post_meta( $proposal_id, '_amount', true );

			$proposal_status				= get_post_meta($proposal_id,'_proposal_status',true);
			$proposal_status				= !empty($proposal_status) ? $proposal_status : '';
			$milestone_offer				= false;
			if( !empty($post_status) && $post_status != 'cancelled' && !empty($proposal_status) && $proposal_status === 'pending' ){
				$milestone_offer				= true;
			}

			$json['milestone_offer']= $milestone_offer;
			$json['total_price']	= !empty($total_price) ? html_entity_decode(workreap_price_format($total_price,'return')) : 0;
			$completed_price		= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'completed','amount') :'';
			$json['completed_price']= !empty($completed_price) ? html_entity_decode(workreap_price_format($completed_price,'return')) : html_entity_decode(workreap_price_format(0,'return'));
			$hired_price			= !empty($project_id) ? workreap_get_sum_earning_milestone($project_id,'hired','amount') : 0;
			$json['hired_price']	= !empty($hired_price) ? html_entity_decode(workreap_price_format($hired_price,'return')) : html_entity_decode(workreap_price_format(0,'return'));
			$pending_price			= workreap_get_milestone_statistics($proposal_id,'pending');
			$json['pending_price']	= !empty($pending_price) ? html_entity_decode(workreap_price_format($pending_price,'return')) : workreap_price_format(0,'return');
			$json['total_budget']	= !empty($milestone['enable']['total_budget']['url']) ? $milestone['enable']['total_budget']['url'] : get_template_directory_uri() . '/images/budget.png';
			$json['in_escrow']		= !empty($milestone['enable']['in_escrow']['url']) ? $milestone['enable']['in_escrow']['url'] : get_template_directory_uri() . '/images/escrow.png';
			$json['milestone_paid']	= !empty($milestone['enable']['milestone_paid']['url']) ? $milestone['enable']['milestone_paid']['url'] : get_template_directory_uri() . '/images/paid.png';
			$json['remainings']		= !empty($milestone['enable']['remainings']['url']) ? $milestone['enable']['remainings']['url'] : get_template_directory_uri() . '/images/remainings.png';
			return new WP_REST_Response($json, 200);
		}
    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetMilestoneRoutes;
	$controller->register_routes();
});
