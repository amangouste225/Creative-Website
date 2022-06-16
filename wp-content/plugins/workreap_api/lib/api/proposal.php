<?php
/**
 * APP API to manage proposals
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_Proposal_Route')) {

    class AndroidApp_Proposal_Route extends WP_REST_Controller{

        /**
         * Register the routes for the user.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'proposal';
			
			//add Proposal
            register_rest_route($namespace, '/' . $base . '/add_proposal',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array(&$this, 'add_proposal'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);

			 //Send user message
             register_rest_route($namespace, '/' . $base . '/sendproposal_chat',
				array(                 
					array(
						'methods' 	=> WP_REST_Server::CREATABLE,
						'callback' 	=> array(&$this, 'sendproposal_chat'),
						'args' 		=> array(),
				 		'permission_callback' => '__return_true',
					),
				)
			);

			//Send user message
			register_rest_route($namespace, '/' . $base . '/commission_fee',
				array(                 
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'commission_fee_details'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);

			//proposal status
			register_rest_route($namespace, '/' . $base . '/proposal_options',
				array(                 
					array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'proposal_options'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
        }

		/**
         * proposal options
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function proposal_options($request){
			$proposal_id 		= !empty($request['proposal_id']) ? $request['proposal_id'] : ''; 
			$job_statuses		= worktic_job_statuses();
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

			$args 			= array(
				'posts_per_page' 	=> -1,
				'post_type' 		=> 'wt-milestone',
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
			$count_post 		= $query->found_posts;
			
			$project_id				= get_post_meta( $proposal_id, '_project_id', true );
			$project_status			= get_post_status($project_id);
			$status_array			= array();
			if( !empty( $job_statuses ) ) {
				foreach( $job_statuses as $key=> $status_v ){
					if( (!empty($key) && $key !=='completed') || (!empty($completed) && $completed == $count_post &&  $project_status ==='hired' ) ){
						$status_array[$key]	= $status_v;
					}
				}
			}
			$json						= array();
			$json['type']           	= 'success';
			$json['status_array']  		= $status_array;
			$json						= maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
		}
		/**
         * add new proposal
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function commission_fee_details($request){
			$proposed_amount 		= !empty($request['proposed_amount']) ? $request['proposed_amount'] : ''; 
			$post_id 				= !empty($request['post_id']) ? $request['post_id'] : ''; 
			$type 					= !empty($request['type']) ? $request['type'] : 'projects';
			
			$service_fee			= workreap_commission_fee($proposed_amount,$type,$post_id);
			if( !empty( $service_fee ) ){
				$admin_amount       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
				$freelancer_amount  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $proposed_amount;
			} else{
				$admin_amount       = 0;
				$freelancer_amount  = $proposed_amount;
			}

			$json['type']           	= 'success';
			$json['commission_type']    = !empty($service_fee['type']) ? $service_fee['type'] : '';
			$json['fixed_amount']    	= !empty($service_fee['fixed_amount']) ? $service_fee['fixed_amount'] : '';
			$json['percentage']    		= !empty($service_fee['percentage']) ? $service_fee['percentage'] : '';
			$json['admin_amount']   	= $admin_amount;
			$json['freelancer_amount']  = $freelancer_amount;
			$json						= maybe_unserialize($json);
			return new WP_REST_Response($json, 200);
		}
		/**
         * add new proposal
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function sendproposal_chat($request){

			$user_id 		= !empty($request['sender_id']) ? $request['sender_id'] : ''; 
			$user_email 	= !empty($user_id) ? get_userdata($user_id)->user_email : '';  
			$author 		= workreap_get_username($user_id);
			if (empty($user_id)) {
                $json['type']           = 'error';
                $json['message']        = esc_html__('No kiddies please.', 'workreap_api');
                $json					= maybe_unserialize($json);
                return new WP_REST_Response($json, 203);
            }	
			if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
				$employer_post_id   		= get_user_meta($user_id, '_linked_profile', true);
				$avatar = apply_filters(
					'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id), array('width' => 100, 'height' => 100) 
				);
			} else {
				$freelancer_post_id   		= get_user_meta($user_id, '_linked_profile', true);
				$avatar = apply_filters(
					'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id), array('width' => 100, 'height' => 100) 
				);
			}    	
			
			$json = array();

			//Form Validation
			if( empty( $request['id'] ) || empty( $request['chat_desc'] ) ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('Message is required.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$post_id 			= !empty( $request['id'] ) ? $request['id'] : '';     	
			$total_attachments 	= !empty( $request['size']) ? ($request['size']) : 0;
			$content 			= !empty( $request['chat_desc'] ) ? $request['chat_desc'] : ''; 
			
			$post_type	= get_post_type($post_id);

			if( !empty( $_FILES ) && $total_attachments != 0 ){
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once( ABSPATH . 'wp-includes/pluggable.php' );
				}
				
				$counter	= 0;
				for ($x = 0; $x < $total_attachments; $x++) {
					$submitted_files = $_FILES['project_files'.$x];
					$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
					$file_name		 = basename($submitted_files['name']);
					$file_type 		 = wp_check_filetype($uploaded_image['file']);

					// Prepare an array of post data for the attachment.
					$attachment_details = array(
						'guid' => $uploaded_image['url'],
						'post_mime_type' => $file_type['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					$attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
					$attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
					wp_update_attachment_metadata($attach_id, $attach_data);
					$attachments['attachment_id']	= $attach_id;
					$attachments['url']	= wp_get_attachment_url($attach_id);
		
					$project_files[]	= $attachments;
				}
			}
						
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

			$time = current_time('mysql');
							
			$data = array(
				'comment_post_ID' 		=> $post_id,
				'comment_author' 		=> $author,
				'comment_author_email' 	=> $user_email,
				'comment_author_url' 	=> 'http://',
				'comment_content' 		=> $content,
				'comment_type' 			=> '',
				'comment_parent' 		=> 0,
				'user_id' 				=> $user_id,
				'comment_date' 			=> $time,
				'comment_approved' 		=> 1,
			);

			$comment_id = wp_insert_comment($data);
			
			if( !empty( $comment_id ) ) {	
				$is_files	= 'no';
				if( !empty( $project_files )) {
					$is_files	= 'yes';
					add_comment_meta($comment_id, 'message_files', $project_files);		
				}
				
				if( isset( $post_type ) && $post_type === 'services-orders' ){
					if($user_type === 'employer'){
						$receiver_id = $hired_freelance_id;
					} else{
						$receiver_id = $employer_id;
					}

					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapServiceMessage')) {
							$email_helper = new WorkreapServiceMessage();
							$emailData = array();

							$employer_name 		= workreap_get_username($employer_id);
							$employer_profile 	= get_permalink(workreap_get_linked_profile_id($employer_id));

							$job_title 			= esc_html( get_the_title($project_id) );
							$job_link 			= get_permalink($project_id);

							$freelancer_link 	= get_permalink($freelancer_id);
							$freelancer_title 	= esc_html( get_the_title($freelancer_id));

							$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
							$employer_email 	= get_userdata( $employer_id )->user_email;


							$emailData['employer_name'] 		= esc_html( $employer_name );
							$emailData['employer_link'] 		= esc_url( $employer_profile );
							$emailData['employer_email'] 		= sanitize_email( $employer_email );

							$emailData['freelancer_link']       = esc_url( $freelancer_link );
							$emailData['freelancer_name']       = esc_html( $freelancer_title );
							$emailData['freelancer_email']      = sanitize_email( $freelancer_email );

							$emailData['service_title'] 		= esc_html( $job_title );
							$emailData['service_link'] 			= esc_url( $job_link );
							$emailData['service_msg']			= esc_textarea( $content );

							if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
								$email_helper->send_service_message_freelancer($emailData);
							} else{
								$email_helper->send_service_message_employer($emailData);
							}

							//Push notification
							$push						= array();
							$push['service_id']			= $project_id;
							$push['%freelancer_link%']	= $emailData['freelancer_link'];
							$push['%freelancer_name%']	= $emailData['freelancer_name'];
							$push['%employer_name%']	= $emailData['employer_name'] ;
							$push['%employer_link%']	= $emailData['employer_link'];
							$push['%service_title%']	= $emailData['service_title'];
							$push['%service_link%']		= $emailData['service_link'];
							$push['%message%']			= wp_strip_all_tags($emailData['service_msg']);

							$push['%replace_message%']	= wp_strip_all_tags($emailData['service_msg']);

							if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
								$email_helper->send_service_message_freelancer($emailData);
								$push['employer_id']		= $user_id;
								$push['freelancer_id']		= $hired_freelance_id;
								$push['service_id']			= $project_id;
								$push['type']				= 'service_message_freelancer';
								do_action('workreap_user_push_notify',array($hired_freelance_id),'','pusher_frl_service_msg_content',$push);
							} else{
								$email_helper->send_service_message_employer($emailData);
								$push['freelancer_id']		= $user_id;
								$push['employer_id']		= $employer_id;
								$push['service_id']			= $project_id;
								$push['type']				= 'service_message_employer';
								do_action('workreap_user_push_notify',array($employer_id),'','pusher_emp_service_msg_content',$push);
							}
						}
					}
					
				} else{
					if($user_type === 'employer'){
						$receiver_id = $hired_freelance_id;
					} else{
						$receiver_id = $employer_id;
					}
					
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapProposalMessage')) {
							$email_helper = new WorkreapProposalMessage();
							$emailData = array();

							$employer_name 		= workreap_get_username($employer_id);
							$employer_profile 	= get_permalink(workreap_get_linked_profile_id($employer_id));

							$job_title 			= esc_html( get_the_title($project_id) );
							$job_link 			= get_permalink($project_id);

							$freelancer_link 	= get_permalink($freelancer_id);
							$freelancer_title 	= esc_html( get_the_title($freelancer_id));

							$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
							$employer_email 	= get_userdata( $employer_id )->user_email;


							$emailData['employer_name'] 		= esc_html( $employer_name );
							$emailData['employer_link'] 		= esc_url( $employer_profile );
							$emailData['employer_email'] 		= sanitize_email( $employer_email );

							$emailData['freelancer_link']       = esc_url( $freelancer_link );
							$emailData['freelancer_name']       = esc_html( $freelancer_title );
							$emailData['freelancer_email']      = sanitize_email( $freelancer_email );

							$emailData['job_title'] 			= esc_html( $job_title );
							$emailData['job_link'] 				= esc_url( $job_link );
							$emailData['proposal_msg']			= $content;
							
							if ( apply_filters('workreap_get_user_type', $user_id) == 'employer' ){
								$email_helper->send_proposal_message_freelancer($emailData);
							} else{
								$email_helper->send_proposal_message_employer($emailData);
							}

						}
					}
				}
				
				$json['comment_id']			= $comment_id;
				$json['user_id']			= intval( $user_id );
				$json['receiver_id']		= intval( $receiver_id );
				$json['type'] 				= 'success';
				$json['message'] 			= esc_html__('Your message has sent.', 'workreap_api');
				$json['content_message'] 	= esc_html( wp_strip_all_tags( $content ) );
				$json['user_name'] 			= $author;
				$json['is_files'] 			= $is_files;
				$json['date'] 				= date(get_option('date_format'), strtotime($time));
				$json['img'] 				= $avatar;
				return new WP_REST_Response($json, 200);
			}
		}
		 /**
         * add new proposal
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Request
         */
        public function add_proposal($request){
			
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			$project_id			= !empty($request['project_id']) ? intval($request['project_id']) : '';

			$proposed_amount	= !empty($request['proposed_amount']) ? workreap_wmc_compatibility($request['proposed_amount']) : '';
			$proposed_time		= !empty($request['proposed_time']) ? esc_attr($request['proposed_time']) : '';
			$proposed_content	= !empty($request['proposed_content']) ? esc_attr($request['proposed_content']) : '';
			$total_attachments 	= !empty($request['size']) ? $request['size'] : 0;
			$submitted_file		= array();
			$json				= array();
			//Validation
			$validations = array(            
				'user_id'      			=> esc_html__('User ID field is required', 'workreap_api'),
				'project_id'      		=> esc_html__('Project ID field is required', 'workreap_api'),
				'proposed_amount'       => esc_html__('Amount field is required', 'workreap_api'),
				'proposed_time'         => esc_html__('Proposal time is required', 'workreap_api'),
				'proposed_content'      => esc_html__('Message field is required', 'workreap_api'),            
			);

			foreach ( $validations as $key => $value ) {
				if ( empty( $request[$key] ) ) {
					$json['type'] 		= 'error';
					$json['message'] 	= $value;
					return new WP_REST_Response($json, 203); 
				}                    
			}
			$linked_profile  	= workreap_get_linked_profile_id($user_id);

			$service_fee = array();
			if (function_exists('fw_get_db_settings_option')) {
				$restrict_proposals   = fw_get_db_settings_option('restrict_proposals', false);
				$service_fee  = fw_get_db_settings_option('service_fee');
			}
	
			if( !empty( $service_fee ) ){
				$admin_amount       = !empty($service_fee['admin_amount']) ? $service_fee['admin_amount'] : 0.0;
				$freelancer_amount  = !empty($service_fee['freelancer_amount']) ? $service_fee['freelancer_amount'] : $proposed_amount;
			} else{
				$admin_amount       = 0;
				$freelancer_amount  = $proposed_amount;
			}
			
			if(!empty($restrict_proposals) && $restrict_proposals === 'yes'){
				$expiry_date   = '';
				if (function_exists('fw_get_db_post_option')) {                               
					$expiry_date   = fw_get_db_post_option($project_id, 'expiry_date', true);
				}
				
				$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
				//if job has expired
				if( !empty($expiry_date) && $expiry_date !== 1 && current_time( 'timestamp' ) > strtotime($expiry_date) ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('We are sorry, but this job has been expired.','workreap_api');
					return new WP_REST_Response($json, 203); 
				}
			}

			if( apply_filters('workreap_feature_connects', $user_id) === false ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You’ve consumed all your credits to apply on a job. Please subscribe to a package to appy on this job','workreap_api');
				return new WP_REST_Response($json, 203);
			}
			
			$is_verified		= get_post_meta($linked_profile, '_is_verified', true);
			$profile_blocked	= get_post_meta($linked_profile, '_profile_blocked', true);
			
			if( empty( $is_verified ) || $is_verified === 'no' ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Your account is not verified, so you cannot post anything.','workreap_api');
				return new WP_REST_Response($json, 203);
			} else if( !empty( $profile_blocked ) && $profile_blocked === 'on' ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Your account is temporarily blocked, so you cannot post anything.','workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$post_type					= get_post_type( $project_id );
			$post_author				= get_post_field( 'post_author',$project_id,true );

			$account_types_permissions	= '';
			if ( function_exists( 'fw_get_db_settings_option' ) ) {
				$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
			}

			if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
				$switch_user_id	= get_user_meta($user_id, 'switch_user_id', true); 
				$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
				if(!empty($switch_user_id) && $post_author == $switch_user_id ){
					$json['type'] 		= 'error';
					if( !empty($post_type) && $post_type == 'projects'){
						$json['message'] 	= esc_html__('You are not allowed to send proposal on your job','workreap_api');
					} else {
						$json['message'] 	= esc_html__('You are not allowed to buy your own service','workreap_api');
					}
					return new WP_REST_Response($json, 203); 
				}
			}

			if ( function_exists('fw_get_db_post_option' )) {
				$identity_verification    	= fw_get_db_settings_option('identity_verification');
			}
			$user_type			= apply_filters('workreap_get_user_type', $user_id );
			if( !empty($user_type) && $user_type === 'employer' ){
				if ( function_exists('fw_get_db_post_option' )) {
					$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
				}
			}
	
			if(!empty($identity_verification) && $identity_verification === 'yes'){	
				if( empty( $is_verified ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Your identity is not verified, so you cannot post or buy anything.','workreap_api');
					return new WP_REST_Response($json, 203); 
				}
			}

			if( apply_filters('workreap_is_feature_allowed', 'packages', $user_id) === false ){	
				if( apply_filters('workreap_feature_connects', $user_id) === false ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('You’ve consumed all you points to apply new job.','workreap_api');
					return new WP_REST_Response($json, 203); 
				}
			}

			if( get_post_status( $project_id ) === 'hired' ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('This project has been assigned to one of the freelancer. You can\'t send proposals.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else if( get_post_status( $project_id ) === 'completed' ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('This project has been completed, so you can\'t send proposals', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}else if( get_post_status( $project_id ) === 'completed' ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('This project has been cancelled, when employer will re-open this project then you would be able to send proposal.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}else if( get_post_status( $project_id ) === 'pending' ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('This project is under review. You can\'t send proposals.', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$user_role 			= apply_filters('workreap_get_user_role', $user_id);
			if( $user_role !== 'freelancers' ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('You are not allowed to send  the proposals', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
	
			if( empty( $project_id ) ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some thing went wrong, try again', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}

			$proposals_sent = intval(0);
			$args = array(
				'post_type' => 'proposals',
				'author'    =>  $user_id,
				'meta_query' => array(
					array(
						'key'     => '_project_id',
						'value'   => intval( $project_id ),
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query( $args );
			if( !empty( $query ) ){
			   $proposals_sent =  $query->found_posts;
			}

			if( $proposals_sent > 0 ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You have already sent the proposal', 'workreap_api');
				return new WP_REST_Response($json, 203); 
			}

			//Check if project is open
			$project_status = get_post_status( $project_id );
			if( $project_status === 'closed' ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You can not send proposal for a closed project', 'workreap_api');
				return new WP_REST_Response($json, 203); 
			}  

			if (function_exists('fw_get_db_post_option')) {
				$db_project_type     = fw_get_db_post_option($project_id,'project_type');
				if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
					if( empty( $request['estimeted_time'])) {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Estimated Hours are required','workreap_api');
						return new WP_REST_Response($json, 203); 
					} else {
						$estimeted_time     = sanitize_text_field( $request['estimeted_time'] );
						$per_hour_amount	= $proposed_amount;
						$proposed_amount	= $proposed_amount * $estimeted_time;
					}
				} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
					if( empty( $proposed_time)) {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Proposal time is required','workreap_api');
						return new WP_REST_Response($json, 203); 
					} else {
						$proposed_time      = sanitize_text_field( $proposed_time );
					}
				}
			}

			//Calculate Service and Freelance Share
			$service_fee		= workreap_commission_fee($proposed_amount,'projects',$project_id);

			if( !empty( $service_fee ) ){
				$admin_amount       = !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.0;
				$freelancer_amount  = !empty($service_fee['freelancer_shares']) ? $service_fee['freelancer_shares'] : $proposed_amount;
			} else{
				$admin_amount       = 0;
				$freelancer_amount  = $proposed_amount;
			}
			
			$admin_amount 		= number_format($admin_amount,2,'.', '');
			$freelancer_amount 	= number_format($freelancer_amount,2,'.', '');

			//Create Proposal
			$username   = workreap_get_username( $user_id );
			$title      = get_the_title( $project_id );
			$title      = $title .' ' . '(' . $username . ')';

			$proposal_post = array(
				'post_title'    => wp_strip_all_tags( $title ), //proposal title
				'post_status'   => 'publish',
				'post_content'  => $proposed_content,
				'post_author'   => $user_id,
				'post_type'     => 'proposals',
			);

			$proposal_id    = wp_insert_post( $proposal_post );
			$fw_options 	= array();
			$attachments	= array();
			if( !is_wp_error( $proposal_id ) ) {
				if( !empty( $_FILES ) && $total_attachments != 0 ){
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					$counter	= 0;
					for ($x = 0; $x < $total_attachments; $x++) {
						$submitted_files = $_FILES['proposal_files'.$x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' => $uploaded_image['url'],
							'post_mime_type' => $file_type['type'],
							'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' => '',
							'post_status' => 'inherit'
						);

						$attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']	= wp_get_attachment_url($attach_id);
			
						$proposal_files[]	= $attachments;
					}
				}

				if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
					update_post_meta( $proposal_id, '_proposed_duration', $proposed_time );
					$fw_options['proposal_duration'] 	= $proposed_time;
				} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
					update_post_meta( $proposal_id, '_estimeted_time', $estimeted_time );
					update_post_meta( $proposal_id, '_per_hour_amount', $per_hour_amount );
					$fw_options['estimeted_time'] 	= $estimeted_time;
					$fw_options['per_hour_amount'] 	= $per_hour_amount;
				}

				//Update post meta
				update_post_meta( $proposal_id, '_send_by', $linked_profile);
				update_post_meta( $proposal_id, '_project_id', $project_id );
				update_post_meta( $proposal_id, '_amount', $proposed_amount);
				update_post_meta( $proposal_id, '_status', 'pending');
				update_post_meta( $proposal_id, '_admin_amount', $admin_amount);
				update_post_meta( $proposal_id, '_freelancer_amount', $freelancer_amount);
				
				//update connects
				if ( function_exists( 'fw_get_db_settings_option' ) ) {
					$proposal_connects 	= fw_get_db_settings_option( 'proposal_connects', $default_value = null );
					$proposal_connects	= !empty( $proposal_connects ) ? intval( $proposal_connects ) : 2;
				} 
				
				$remaning_connects		= workreap_get_subscription_metadata( 'wt_connects',intval($user_id) );
				$remaning_connects  	= !empty( $remaning_connects ) ? intval($remaning_connects) : '';
				
				if( !empty( $remaning_connects) && $remaning_connects >= $proposal_connects ) {
					$update_connects	= $remaning_connects - $proposal_connects ;
					$update_connects	= intval($update_connects);
					
					$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
					$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();
					$wt_subscription['wt_connects'] = $update_connects;
					update_user_meta( intval($user_id), 'wt_subscription', $wt_subscription);
				}
				
				
				if( !empty( $proposal_files ) ){
					update_post_meta( $proposal_id, '_proposal_docs', $proposal_files);
					$fw_options['proposal_docs'] = $proposal_files;
				}
				
				//update meta
				$fw_options['project']				= array($project_id);
				$fw_options['proposed_amount'] 		= $proposed_amount;
				
				//Update User Profile
				fw_set_db_post_option($proposal_id, null, $fw_options);
	
				//update more data hook 
				do_action('workreap_update_proposals_extra_data',$request,$proposal_id);
				
				//Send email to employer
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapProposalSubmit')) {
						
						if(empty($proposal_edit_id)){
							$freelancer_link        = esc_url( get_the_permalink( $linked_profile ));
							$project_link           = esc_url( get_the_permalink( $project_id ));
							$project_title          = esc_html( get_the_title( $project_id ));
							$duration_list          = worktic_job_duration_list();
							$project_duration_value = !empty( $duration_list ) ? $duration_list[$proposed_time] : '';
	
							$post_author_id         = get_post_field( 'post_author', $project_id );
							$current_user           = get_userdata( $user_id );
							$author_data            = get_userdata( $post_author_id );                    
							$email_to               = $author_data->user_email; 
							$employer_name          = workreap_get_username( $post_author_id );                 
	
							$email_helper           = new WorkreapProposalSubmit();
	
							$emailData = array();
							$emailData['employer_name']              = $employer_name;
							$emailData['freelancer_link']            = $freelancer_link;
							$emailData['freelancer_name']            = $username;
							$emailData['project_link']               = $project_link;
							$emailData['project_title']              = $project_title;
							$emailData['proposal_amount']            = workreap_price_format($proposed_amount,'return');;
							$emailData['proposal_duration']          = $project_duration_value;
							$emailData['proposal_message']           = $proposed_content;
							$emailData['employer_email']             = $email_to;
							$emailData['freelancer_email']           = $current_user->user_email;
							
							if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
								$emailData['project_type']             = 'fixed';
							}else{
								$emailData['project_type']             = 'hourly';
							}
							
							$email_helper->send_employer_proposal_submit($emailData);
							$email_helper->send_freelancer_proposal_submit($emailData);
							
							
							//Push notification
							$push	= array();
							$push['freelancer_id']		= $user_id;
							$push['employer_id']		= $post_author_id;
							$push['project_id']			= $project_id;
	
							$push['%freelancer_link%']	= $emailData['freelancer_link'];
							$push['%freelancer_name%']	= $emailData['freelancer_name'];
							$push['%employer_name%']	= $emailData['employer_name'] ;
							$push['%project_title%']	= $emailData['project_title'];
							$push['%project_link%']		= $emailData['project_link'];
							
							$push['%proposal_amount%']	= $emailData['proposal_amount'];
							$push['%proposal_duration%']= $emailData['proposal_duration'];
							$push['%message%']			= $emailData['proposal_message'];
							
							$push['%replace_proposal_amount%']		= $emailData['proposal_amount'];
							$push['%replace_proposal_duration%']	= $emailData['proposal_duration'];
							$push['%replace_message%']				= $emailData['proposal_message'];
							$push['type']							= 'proposal_received';
							
							//employer notification
							do_action('workreap_user_push_notify',array($post_author_id),'','pusher_emp_proposal_content',$push);
							
							//freelancer notification
							$push['type']					= 'submit_proposal';
							do_action('workreap_user_push_notify',array($user_id),'','pusher_frl_proposal_content',$push);
							
							
						}
					}
				}
				$json['type']       = 'success';
				$json['message']    = esc_html__('Proposal sent Successfully ', 'workreap_api');
				return new WP_REST_Response($json, 200); 
			}else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You can not send proposal for a closed project', 'workreap_api');
				return new WP_REST_Response($json, 203); 
			}
		}
    }
}

add_action('rest_api_init',
        function () {
    $controller = new AndroidApp_Proposal_Route;
    $controller->register_routes();
});
