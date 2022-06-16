<?php
if (!class_exists('AndroidAppGetEmployersDashbord')) {

    class AndroidAppGetEmployersDashbord extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'dashboard';

			//manage employers
			register_rest_route($namespace, '/' . $base . '/manage_employer_jobs',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'manage_employer_jobs'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);

			//manage proposals
			register_rest_route($namespace, '/' . $base . '/manage_job_proposals',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'manage_job_proposals'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);

			//manage ongoing jobs
			register_rest_route($namespace, '/' . $base . '/manage_employer_ongoing_jobs',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'manage_employer_ongoing_jobs'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			//manage ongoing jobs
			register_rest_route($namespace, '/' . $base . '/job_details_information',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'job_details_information'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			register_rest_route($namespace, '/' . $base . '/get_employer_services',
				array(
				array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_employer_services'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			
			register_rest_route($namespace, '/' . $base . '/get_services_feedbacks',
				array(
				array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_services_feedbacks'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			register_rest_route($namespace, '/' . $base . '/complete_project',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'complete_project'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			register_rest_route($namespace, '/' . $base . '/cancelled_project',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'cancelled_project'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			register_rest_route($namespace, '/' . $base . '/complete_services',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'complete_services'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			register_rest_route($namespace, '/' . $base . '/cancelled_services',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'cancelled_services'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/update_employer_profile',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'update_employer_profile'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
        }
		
		/**
         * Update employer profile
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function update_employer_profile($request){
			$json		= array();
			$user_id	= !empty( $request['user_id'] ) ? intval($request['user_id']) : '';             
			$json 		= array();
			$post_id	= workreap_get_linked_profile_id($user_id); 
			$hide_map	= 'show';

			if (function_exists('fw_get_db_settings_option')) {
				$hide_map	= fw_get_db_settings_option('hide_map');
			}

			$phone_setting 		= '';
			$phone_mandatory	= '';
			$profile_mandatory	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$phone_option		= fw_get_db_settings_option('phone_option');
				$profile_mandatory	= fw_get_db_settings_option('employer_profile_required');
				$phone_setting		= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
				$phone_mandatory	= !empty($phone_option['enable']['phone_mandatory']) ? $phone_option['enable']['phone_mandatory'] : '';
			}

			if(!empty($profile_mandatory)) {
				$employer_required = workreap_employer_required_fields();
				foreach ($profile_mandatory as $key) {
					if( empty( $request['basics'][$key] ) ){
						 $json['type'] 		= 'error';
						 $json['message'] 	= $employer_required[$key];        
						 wp_send_json($json);
					}
				 }
			} 
			
			$company_job_title	= '';
			if( function_exists('fw_get_db_settings_option')  ){
				$company_job_title	= fw_get_db_settings_option('company_job_title', $default_value = null);
			}

			//Form data
			$first_name 	= !empty($request['basics']['first_name']) ? sanitize_text_field($request['basics']['first_name']) : '';
			$last_name  	= !empty($request['basics']['last_name'] ) ? sanitize_text_field($request['basics']['last_name']) : '';
			$tag_line   	= !empty($request['basics']['tag_line'] ) ? sanitize_text_field( $request['basics']['tag_line'] ) : '';
			$content    	= !empty($request['basics']['content'] ) ? wp_kses_post( $request['basics']['content'] ) : '';
			$address    	= !empty( $request['basics']['address'] ) ? $request['basics']['address'] : '';
			$country    	= !empty( $request['basics']['country'] ) ? $request['basics']['country'] : '';
			$latitude   	= !empty( $request['basics']['latitude'] ) ? $request['basics']['latitude'] : '';
			$longitude  	= !empty( $request['basics']['longitude'] ) ? $request['basics']['longitude'] : '';
			$employees  	= !empty( $request['employees'] ) ? $request['employees'] : '';
			$department  	= !empty( $request['department'] ) ? $request['department'] : '';
			$display_name  	= !empty( $request['basics']['display_name'] ) ? $request['basics']['display_name'] : '';
			$brochures     	= !empty( $request['basics']['brochures'] ) ? $request['basics']['brochures'] : array();
			//Update user meta
			update_user_meta($user_id, 'first_name', $first_name);
			update_user_meta($user_id, 'last_name', $last_name);
			
			if( !empty( $display_name ) ) {
				$post_title	= $display_name;
				$user_info	= array( 'ID' => $user_id, 'display_name' => $display_name );
				wp_update_user( $user_info );
			} else {
				$post_title	= esc_html( get_the_title( $post_id ));
			}
			
			
	
			//Update Freelancer Post        
			$freelancer_user = array(
				'ID'           => $post_id,
				'post_title'   => $post_title,
				'post_content' => $content,
			);
			wp_update_post( $freelancer_user );
			
			update_post_meta($post_id, '_tag_line', $tag_line);
			update_post_meta($post_id, '_address', $address);
			update_post_meta($post_id, '_country', $country);
			update_post_meta($post_id, '_latitude', $latitude);
			update_post_meta($post_id, '_longitude', $longitude);
			update_post_meta($post_id, '_employees', $employees);
			
			if( !empty( $department ) ){
				$department_term = get_term_by( 'term_id', $department, 'department' );
				if( !empty( $department_term ) ){
					wp_set_post_terms( $post_id, $department, 'department' );
					update_post_meta($post_id, '_department', $department_term->slug);
				}
			}
			
			//Profile avatar
			$profile_avatar = array();
			if( !empty( $request['basics']['avatar']['attachment_id'] ) ){
				$profile_avatar = $request['basics']['avatar'];
			} else {  
				$profile_base64		= !empty( $request['basics']['avatar_base64'] ) ?  $request['basics']['avatar_base64'] : '';                              
				if( !empty( $profile_base64 ) ){
					$avatar_id 		= AndroidApp_uploadmedia::upload_media($profile_base64);
					$avatar_id		= !empty($avatar_id) ? intval($avatar_id) : "";
				
					if( !empty($avatar_id) ){
						$thumnail_id	= get_post_thumbnail_id($post_id);
						wp_delete_attachment($thumnail_id);
						set_post_thumbnail($post_id,$avatar_id);
					}
				}
			}  
	
			//Profile avatar
			$profile_banner = array();
			if( !empty( $request['basics']['banner']['attachment_id']) ){
				$attachment_id = !empty($request['basics']['banner']['attachment_id']) ? intval($request['basics']['banner']['attachment_id']) : '';
				$profile_banner	= array(
					'attachment_id' => $attachment_id,
					'url'			=> wp_get_attachment_url($attachment_id)
				);
			} else {  
				$banner_base64		= !empty( $request['basics']['banner_base64'] ) ?  $request['basics']['banner_base64']  : '';                              
				if( !empty( $banner_base64 ) && apply_filters('workreap_is_feature_allowed', 'wt_banner', $user_id) === true ){
					$avatar_id 		= AndroidApp_uploadmedia::upload_media($banner_base64);
					$avatar_id		= !empty($avatar_id) ? intval($avatar_id) : "";
					$post_banner	= fw_get_db_post_option($post_id, 'banner_image', $default_value = null);
					$thumnail_id	= !empty($post_banner['attachment_id']) ? intval($post_banner['attachment_id']) : ""; 

					if( !empty($thumnail_id) ) {
						wp_delete_attachment($thumnail_id);
					}

					$profile_banner	= array(
										'attachment_id' => $avatar_id,
										'url'			=> wp_get_attachment_url($avatar_id)
									);
				}
			}

			//Set country for unyson
			$locations = get_term_by( 'slug', $country, 'locations' );
			$location = array();
			if( !empty( $locations ) ){
				$location[0] = $locations->term_id;
				wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
			}
			//Fw Options
			$fw_options = array();
			if( !empty($phone_setting) && $phone_setting == 'enable') {
				$user_phone_number  = !empty( $request['basics']['user_phone_number'] ) ? $request['basics']['user_phone_number'] : '';
				$fw_options['user_phone_number']           = $user_phone_number;
			}
			if(!empty($company_name) && $company_name === 'enable') { 
				$company_name  					= !empty( $request['basics']['company_name'] ) ? $request['basics']['company_name'] : '';
				$fw_options['company_name']     = $company_name;
			}
			if(!empty($company_job_title) && $company_job_title === 'enable') { 
				$job_title  						= !empty( $request['basics']['company_name_title'] ) ? $request['basics']['company_name_title'] : '';
				$fw_options['company_job_title']    = $job_title;
			}
			
			$socialmediaurls	= array();
			if( function_exists('fw_get_db_settings_option')  ){
				$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
			}
			
			$brochure_attachemnts	= array();
			if( !empty( $brochures ) ) {
				foreach ( $brochures as $key => $value ) {
					if( !empty( $value['attachment_id'] ) ){
						$fw_options[] = $value;
					} 	
				}            
			}	
			
			$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
			if(!empty($socialmediaurl) && $socialmediaurl  ==='enable'){
				$social_settings    	= function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();
				if(!empty($social_settings)) {
					foreach($social_settings as $key => $val ) {
						$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
						if( !empty($enable_value) && $enable_value === 'enable' ){
							$social_val			= !empty($request['basics'][$key]) ? esc_attr($request['basics'][$key]) : '';
							$fw_options[$key]   = $social_val;
						}
					}
				}
			}
						
			$fw_options['tag_line']           = $tag_line;
			$fw_options['address']            = $address;
			$fw_options['longitude']          = $longitude;
			$fw_options['latitude']           = $latitude;
			$fw_options['country']            = $location;
			$fw_options['department']         = array( $department );
			$fw_options['no_of_employees']    = $employees;
			$fw_options['banner_image']       = $profile_banner;

			//Update User Profile
			fw_set_db_post_option($post_id, null, $fw_options);
			
			//child theme : update extra settings
			do_action('workreap_update_employer_profile_settings',$request); 
			$json['type']   	= 'success';
			$json['message']    = esc_html__('Profile is updated successfully','workreap_api'); 
			return new WP_REST_Response($json, 200);
		}
		
		/**
         * Cancelled Project
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function cancelled_project($request){
			global	$wpdb;
			$json 				= array();
			$project_id			= !empty( $request['project_id'] ) ? intval($request['project_id']) : '';
			$cancelled_reason	= !empty( $request['cancelled_reason'] ) ? esc_html($request['cancelled_reason']) : '';
			if( empty( $project_id ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No kiddies please', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			} else {
				
				$proposal_id 			= get_post_meta( $project_id, '_proposal_id', true);
				$freelancer_id 			= get_post_meta( $project_id, '_freelancer_id', true);
				$hired_freelance_id		= get_post_field('post_author', $proposal_id);
				$user_id				= get_post_field('post_author', $project_id);
				
				delete_post_meta( $project_id, '_proposal_id', $proposal_id );
				delete_post_meta( $project_id, '_freelancer_id', $freelancer_id );
				add_post_meta( $proposal_id, '_cancelled_reason', $cancelled_reason );
				add_post_meta( $project_id, '_cancelled_proposal_id', $proposal_id );
				add_post_meta( $proposal_id, '_employer_user_id', $user_id );
				
				$project_post_data 	= array(
					'ID'            => $project_id,
					'post_status'   => 'cancelled',
				);
				wp_update_post( $project_post_data );
				$proposal_post_data 	= array(
					'ID'            => $proposal_id,
					'post_status'   => 'cancelled',
				);

				wp_update_post( $proposal_post_data );
				
				//update earnings
				
				$table_name = $wpdb->prefix . 'wt_earnings';
				$e_query		= $wpdb->prepare("SELECT * FROM `$table_name` where user_id = %d and project_id = %d",$hired_freelance_id,$project_id);
				$earnings		= $wpdb->get_results($e_query,OBJECT ); 
				
				if( !empty( $earnings ) ) {
					foreach($earnings as $earning ){
						$update		= array( 'status' => 'cancelled' );
						$where		= array( 'id' 	=> $earning->id );
						workreap_update_earning( $where, $update, 'wt_earnings');
						
						if ( class_exists('WooCommerce') ) {
							$order = wc_get_order( intval( $earning->order_id ) );
							if( !empty( $order ) ) {
								$order->update_status( 'cancelled' );
							}
						}
					}
				}
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapCancelJob')) {
						$email_helper 	= new WorkreapCancelJob();
						$emailData 		= array();

						$employer_name 		= workreap_get_username($user_id);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($user_id));
						$job_title 			= esc_html( get_the_title($project_id));
						$job_link 			= get_permalink($project_id);
						$freelancer_link 	= get_permalink(workreap_get_linked_profile_id($hired_freelance_id));
						$freelancer_title 	= esc_html( get_the_title(workreap_get_linked_profile_id($hired_freelance_id)) );
						$freelancer_email 	= get_userdata( $hired_freelance_id )->user_email;
						
						$emailData['employer_name'] 		= esc_html( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_name']       = esc_html( $freelancer_title );
						$emailData['email_to']      		= sanitize_email( $freelancer_email );
						$emailData['job_title'] 			= esc_html( $job_title );
						$emailData['job_link'] 				= esc_url( $job_link );
						$emailData['cancel_msg'] 			= esc_textarea($cancelled_reason);

						$email_helper->send_job_cancel_email($emailData);
						
						//Push notification
						$push						= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $user_id;
						$push['project_id']			= $project_id;
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['job_title'];
						$push['%project_link%']		= $emailData['job_link'];
						$push['%message%']			= wp_strip_all_tags( $emailData['cancel_msg'] );
						$push['type']				= 'project_cancelled';
						$push['%replace_message%']	= wp_strip_all_tags( $emailData['cancel_msg'] );

						do_action('workreap_user_push_notify',array($freelancer_id),'','pusher_frl_cancel_job_content',$push);
						
					}
				}
				
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Project has been cancelled.', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
		}

		/**
         * Complete Project
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function complete_project($request){
			global	$wpdb;
			$project_id				= !empty( $request['project_id'] ) ? intval($request['project_id']) : '';
			$contents 				= !empty( $request['feedback_description'] ) ? sanitize_textarea_field($request['feedback_description']) : '';
			$reviews 				= !empty( $request['feedback'] ) ? $request['feedback'] : array();
			$user_id				= !empty( $request['user_id'] ) ? intval($request['user_id']) : '';
			
			$json 					= array();
			$where					= array();
			$update					= array();

			if( empty( $contents ) || empty( $project_id ) ){
				$json['type'] 		= 'error';

				if( empty( $contents ) ) {
					$json['message'] 	= esc_html__('Feedback detail is required field', 'workreap_api');	
				} 

				wp_send_json($json);

			} else {
				$employer_id		= get_post_field('post_author',$project_id);
				$proposal_id		= get_post_meta( $project_id, '_proposal_id', true);
				$freelance_id		= get_post_field('post_author',$proposal_id);
				$review_title		= esc_html( get_the_title($proposal_id) );

				$user_reviews = array(
					'posts_per_page' 	=> 1,
					'post_type' 		=> 'reviews',
					'post_status' 		=> 'any',
					'author' 			=> $freelance_id,
					'meta_key' 			=> '_project_id',
					'meta_value' 		=> $project_id,
					'meta_compare' 		=> "=",
					'orderby' 			=> 'meta_value',
					'order' 			=> 'ASC',
				);

				$reviews_query = new WP_Query($user_reviews);
				$reviews_count = $reviews_query->post_count;

				if (isset($reviews_count) && $reviews_count > 0) {
					$json['type'] = 'error';
					$json['message'] = esc_html__('You have already submit a review.', 'workreap_api');
					wp_send_json($json);
				}

				$review_post = array(
					'post_title' 		=> $review_title,
					'post_status' 		=> 'publish',
					'post_content' 		=> $contents,
					'post_author' 		=> $freelance_id,
					'post_type' 		=> 'reviews',
					'post_date' 		=> current_time('Y-m-d H:i:s')
				);

				$post_id = wp_insert_post($review_post);

				/* Get the rating headings */
				$rating_evaluation 			= workreap_project_ratings();
				$rating_evaluation_count 	= !empty($rating_evaluation) ? workreap_count_items($rating_evaluation) : 0;

				$review_extra_meta = array();
				$rating 		= 0;
				$user_rating 	= 0;

				if (!empty($rating_evaluation)) {
					foreach ($rating_evaluation as $slug => $label) {
						if (isset($reviews[$slug])) {
							$review_extra_meta[$slug] = esc_attr($reviews[$slug]);
							update_post_meta($post_id, $slug, esc_attr($reviews[$slug]));
							$rating += (int) $reviews[$slug];
						}
					}
				}

				update_post_meta($post_id, '_project_id', $project_id);
				update_post_meta($post_id, '_proposal_id', $proposal_id);
				if( !empty( $rating ) ){
					$user_rating = $rating / $rating_evaluation_count;
				}

				$employer_profile_id 	= workreap_get_linked_profile_id( $employer_id );
				$freelance_profile_id 	= workreap_get_linked_profile_id( $freelance_id );

				$user_rating 			= number_format((float) $user_rating, 2, '.', '');
				$review_meta 			= array(
					'user_rating' 		=> $user_rating,
					'user_from' 		=> $employer_profile_id,
					'user_to' 			=> $freelance_profile_id,
					'review_date' 		=> current_time('Y-m-d H:i:s'),
				);

				$review_meta = array_merge($review_meta, $review_extra_meta);

				//Update post meta
				foreach ($review_meta as $key => $value) {
					update_post_meta($post_id, $key, $value);
				}

				$review_meta['user_from'] 	= array($employer_profile_id);
				$review_meta['user_to'] 	= array($freelance_profile_id);

				$new_values = $review_meta;
				if (isset($post_id) && !empty($post_id)) {
					fw_set_db_post_option($post_id, null, $new_values);
				}

				/* Update avarage rating in user table */

				$table_review = $wpdb->prefix . "posts";
				$table_meta   = $wpdb->prefix . "postmeta";

				$db_rating_query = $wpdb->get_row( "
					SELECT  p.ID,
					SUM( pm2.meta_value ) AS db_rating,
					count( p.ID ) AS db_total
					FROM   ".$table_review." p 
					LEFT JOIN ".$table_meta." pm1 ON (pm1.post_id = p.ID  AND pm1.meta_key = 'user_to') 
					LEFT JOIN ".$table_meta." pm2 ON (pm2.post_id = p.ID  AND pm2.meta_key = 'user_rating')
					WHERE post_status = 'publish'
					AND pm1.meta_value    = ".$freelance_profile_id."
					AND p.post_type = 'reviews'
				",ARRAY_A);

				$user_rating 	= '0';

				if( empty( $db_rating_query ) ){
					$user_db_reviews['wt_average_rating'] 			= 0;
					$user_db_reviews['wt_total_rating'] 			= 0;
					$user_db_reviews['wt_total_percentage'] 		= 0;
					$user_db_reviews['wt_rating_count'] 			= 0;
				} else{

					$rating			= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating']/$db_rating_query['db_total'] : 0;
					$user_rating 	= number_format((float) $rating, 2, '.', '');

					$user_db_reviews['wt_average_rating'] 			= $user_rating;
					$user_db_reviews['wt_total_rating'] 			= !empty( $db_rating_query['db_total'] ) ? $db_rating_query['db_total'] : '';
					$user_db_reviews['wt_total_percentage'] 		= $user_rating * 20;
					$user_db_reviews['wt_rating_count'] 			= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating'] : '';
				}

				update_post_meta($freelance_profile_id, 'review_data', $user_db_reviews);
				update_post_meta($freelance_profile_id, 'rating_filter', $user_rating);

				$project_post_data 	= array(
					'ID'            => $project_id,
					'post_status'   => 'completed',
				);

				wp_update_post( $project_post_data );

				$order_id			= get_post_meta($proposal_id,'_order_id',true);
				if ( class_exists('WooCommerce') && !empty( $order_id )) {
					$order = wc_get_order( intval($order_id ) );
					if( !empty( $order ) ) {
						$order->update_status( 'completed' );
					}
				}

				$proposal_id 	= get_post_meta( $project_id, '_proposal_id', true);
				update_post_meta($proposal_id, '_employer_user_id', $user_id);

				//update earning
				$where		= array('project_id' => $project_id, 'user_id' => $freelance_id);
				$update		= array('status' => 'completed');

				workreap_update_earning( $where, $update, 'wt_earnings');

				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapJobCompleted')) {
						$email_helper = new WorkreapJobCompleted();
						$emailData 	  = array();

						$job_title 			= esc_html( get_the_title($project_id) );
						$job_link 			= get_permalink($project_id);
						$employer_name 		= workreap_get_username($user_id);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($user_id));
						$freelancer_link 	= get_permalink($freelance_profile_id );
						$freelancer_title 	= esc_html( get_the_title($freelance_profile_id ) );
						$freelancer_email 	= get_userdata( $freelance_id )->user_email;


						$emailData['employer_name'] 		= esc_html( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['freelancer_name']       = esc_html( $freelancer_title );
						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_email']      = sanitize_email( $freelancer_email );
						$emailData['project_title'] 		= esc_html( $job_title );
						$emailData['ratings'] 				= esc_html( $user_rating );
						$emailData['project_link'] 			= esc_url( $job_link );
						$emailData['message'] 				= sanitize_textarea_field( $contents );

						$email_helper->send_job_completed_email_admin($emailData);
						$email_helper->send_job_completed_email_freelancer($emailData);

						//Push notification
						$push	= array();
						$push['freelancer_id']		= $freelance_id;
						$push['employer_id']		= $user_id;
						$push['project_id']			= $project_id;

						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%project_title%']	= $emailData['project_title'];
						$push['%project_link%']		= $emailData['project_link'];
						$push['%ratings%']			= $emailData['ratings'];
						$push['%message%']			= $emailData['message'];
						$push['type']				= 'project_completed';

						$push['%replace_ratings%']	= $emailData['ratings'];
						$push['%replace_message%']	= $emailData['message'];

						do_action('workreap_user_push_notify',array($freelance_id),'','pusher_frl_job_complete_content',$push);
					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Project completed successfully.', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
		}
		
		/**
         * Cancelled Service
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function manage_employer_ongoing_jobs($request){
			$limit			= !empty( $request['show_number'] ) ? intval( $request['show_number'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_identity	= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : 1;
			
			$order 	 = 'DESC';
			$sorting = 'ID';
			$items	= array();
			$args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'projects',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('hired'),
				'author' 			=> $user_identity,
				'paged' 			=> $page_number,
				'suppress_filters'  => false
			);
			
			$query = new WP_Query($args);
			if( $query->have_posts() ){
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$item					= array();
					$author_id 				= get_the_author_meta( 'ID' );  
					$linked_profile 		= workreap_get_linked_profile_id($author_id);
					$item['employer_title'] = esc_html(get_the_title( $linked_profile ));
					$milestone_option	= 'off';
					
					if( !empty($milestone) && $milestone ==='enable' ){
						$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
					}
				
					$item['milestone_option'] 	= $milestone_option;
					$item['employer_verified'] 	= get_post_meta($linked_profile, '_is_verified', true);
					$item['project_title'] 		= get_the_title( $post );
					$project_level = '';
					
					if (function_exists('fw_get_db_post_option')) {
						$project_level          = fw_get_db_post_option($post->ID, 'project_level', true);                
					}
				
					$item['project_level']	= workreap_get_project_level($project_level);
					$item['location_name']		= '';
					$item['location_flag']		= '';
				
					if( !empty( $post->ID ) ){ 
						$args = array();
						if( taxonomy_exists('locations') ) {
							$terms = wp_get_post_terms( $post->ID, 'locations', $args );
							if( !empty( $terms ) ){
								foreach ( $terms as $key => $term ) {    
									$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
									$item['location_name']		= !empty($term->name) ? $term->name : '';;
									$item['location_flag']		= !empty($country['url']) ? workreap_add_http( $country['url'] ) : '';;
								}
							}
						}
					}

					$project_type = '';   
					if (function_exists('fw_get_db_post_option')) {
						$project_type = fw_get_db_post_option($post->ID, 'project_type', true);
					}
					
					$project_type   		= !empty( $project_type['gadget'] ) ? $project_type['gadget'] : '';
					$item['project_type']	= isset( $project_type ) && $project_type == 'hourly' ?  esc_html__('Hourly', 'workreap_api') : esc_html__('Fixed', 'workreap_api');
					$item['project_ID']		= $post->ID;

					$proposal_id				= get_post_meta( $post->ID, '_proposal_id', true);
					$hired_freelance_id			= get_post_field('post_author',$proposal_id);
					$item['hire_linked_profile'] 		= $hired_freelance_id;
					$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
					$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id);
					$item['hired_freelancer_title'] 		= esc_html( get_the_title( $hire_linked_profile ));
					$item['hired_freelancer_img'] 			= apply_filters(
						'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 100, 'height' => 100 )
					);
					
					$items[] 				= $item;
				endwhile;
				wp_reset_postdata();
				return new WP_REST_Response($items, 200);
			} else {
				return new WP_REST_Response($items, 200);
			}
		}
		
		/**
         * function that return jobs details
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function job_details_information($request){
			$project_id		= !empty( $request['project_id'] ) ? intval( $request['project_id'] ) : '';
			$freelance_id	= !empty( $request['freelance_id'] ) ? intval( $request['freelance_id'] ) : '';
			$proposal_id	= !empty( $request['proposal_id'] ) ? intval( $request['proposal_id'] ) : '';
			$linked_profile	= workreap_get_linked_profile_id($freelance_id);
			$featured_id	= workreap_is_feature_value( 'wt_badget',$freelance_id );
			$featured_id	= !empty($featured_id) ? intval($featured_id) : '';
			$post_id		= get_post_meta( $linked_profile,'_featured_timestamp',true );
			$return_data				= array();
			$return_data['icon_color'] 	= '';
			$return_data['icon_url'] 	= '';
			if( !empty( $post_id ) ) {
				if( empty( $featured_id ) ){ return 'wt-featured';}
				$term	= get_term( $featured_id );
				if( !empty($term) ) {
					$badge_icon  	= fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_icon');
					$badge_color 	= fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_color');
					$return_data['icon_color'] 			= !empty( $badge_color ) ? $badge_color : '#ff5851';
					$return_data['icon_url'] 			= !empty( $badge_icon['url'] ) ? $badge_icon['url'] : '';
					
				}
			}
			$project_type    	= fw_get_db_post_option($project_id,'project_type');
			$proposed_amount  	= get_post_meta($proposal_id, '_amount', true);

			$return_data['proposal_price'] 	= workreap_price_format($proposed_amount,'return');
			$return_data['duration'] 		= '';
			$return_data['hourly_price']	= '';
			$return_data['estimeted_time']	= '';

			if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'fixed' ) {
				$proposed_duration  		= get_post_meta($proposal_id, '_proposed_duration', true);
				$duration_list				= worktic_job_duration_list();
				$return_data['duration']	= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
				
			} else if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'hourly' ) {

				$estimeted_time		= get_post_meta($proposal_id,'_estimeted_time',true);
				$per_hour_amount	= get_post_meta($proposal_id,'_per_hour_amount',true);
				$per_hour_amount	= !empty( $per_hour_amount ) ? $per_hour_amount : 0;

				$return_data['estimeted_time']	= !empty( $estimeted_time ) ? $estimeted_time : 0;
				$return_data['hourly_price']	= apply_filters('workreap_price_format',$per_hour_amount,'return');
				
			}
			if (function_exists('fw_get_db_post_option')) {
				$proposal_docs = fw_get_db_post_option($proposal_id, 'proposal_docs');
			}
			
			$return_data['proposal_docs']	= !empty( $proposal_docs ) ?  count( $proposal_docs ) : 0;
			$return_data['covert_letter']	= nl2br( stripslashes( get_the_content('',true,$proposal_id) ) );
			$items[] 						= $return_data;
			return new WP_REST_Response($items, 200);
		}
		/**
         * Cancelled Service
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function cancelled_services($request){
			global $wpdb;
			$json 				= array();
			$service_order_id	=  !empty( $request['service_order_id'] ) ? intval($request['service_order_id']) : '';
			$cancelled_reason	=  !empty( $request['cancelled_reason'] ) ? $request['cancelled_reason'] : '';
			$user_id 			= !empty( $request['user_id'] ) ? ($request['user_id']) : '';
			
			if( empty( $service_order_id ) || empty( $cancelled_reason ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No kiddies please', 'workreap_api');
				return new WP_REST_Response($json, 203);
			} else {
				$freelancer_id		= get_post_meta( $service_order_id, '_service_author', true);
				$service_id			= get_post_meta( $service_order_id, '_service_id', true);
				
				$service_cancelled	= workreap_save_service_status($service_order_id, 'cancelled');
				
				if( $service_cancelled ) {
					// update earnings
					if( function_exists( 'fw_set_db_post_option' ) ) {
						fw_set_db_post_option($service_order_id, 'feedback', $cancelled_reason);
					}
					
					$table_name 	= $wpdb->prefix . 'wt_earnings';
					$e_query		= $wpdb->prepare("SELECT * FROM $table_name where project_id = %d", $service_order_id);
					$earning		= $wpdb->get_row($e_query, OBJECT ); 
					if( !empty( $earning ) ) {
						$update		= array( 'status' 	=> 'cancelled' );
						$where		= array( 'id' 		=> $earning->id );
						workreap_update_earning( $where, $update, 'wt_earnings');

						if ( class_exists('WooCommerce') ) {
							$order = wc_get_order( intval( $earning->order_id ) );
							if( !empty( $order ) ) {
								$order->update_status( 'cancelled' );
							}
						}	
					}
					
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapCancelService')) {
							$email_helper = new WorkreapCancelService();
							$emailData 	  = array();

							$service_title 			= get_the_title($service_id);
							$service_link 			= get_permalink($service_id);
							$freelance_profile_id	= workreap_get_linked_profile_id( $freelancer_id );

							$employer_name 		= workreap_get_username($user_id);
							$employer_profile 	= get_permalink(workreap_get_linked_profile_id($user_id));
							$freelancer_link 	= get_permalink($freelance_profile_id );
							$freelancer_title 	= get_the_title($freelance_profile_id );
							$freelancer_email 	= get_userdata( $freelancer_id )->user_email;


							$emailData['employer_name'] 		= esc_attr( $employer_name );
							$emailData['employer_link'] 		= esc_url( $employer_profile );
							$emailData['freelancer_name']       = esc_attr( $freelancer_title );
							$emailData['freelancer_link']       = esc_url( $freelancer_link );
							$emailData['freelancer_email']      = esc_attr( $freelancer_email );
							$emailData['service_title'] 		= esc_attr( $service_title );
							$emailData['service_link'] 			= esc_url( $service_link );
							$emailData['message'] 				= esc_html( $cancelled_reason );

							$email_helper->send_service_cancel_email($emailData);
							//Push notification
								$push	= array();
								$push['freelancer_id']		= $freelancer_id;
								$push['employer_id']		= $user_id;
								$push['service_id']			= $service_id;

								$push['%freelancer_link%']	= $emailData['freelancer_link'];
								$push['%freelancer_name%']	= $emailData['freelancer_name'];
								$push['%employer_name%']	= $emailData['employer_name'] ;
								$push['%employer_link%']	= $emailData['employer_link'];
								$push['%service_title%']	= $emailData['service_title'];
								$push['%service_link%']		= $emailData['service_link'];
								$push['%message%']			= $emailData['message'];
								$push['type']				= 'cancel_service';

								$push['%replace_message%']	= $emailData['message'];

								do_action('workreap_user_push_notify',array($push['freelancer_id']),'','pusher_frl_cancel_service_content',$push);
								
						}
					}
					
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your order have been cancelled.', 'workreap_api');
					return new WP_REST_Response($json, 200);
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('No kiddies please', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}
		}
		/**
         * Complete Service
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function complete_services($request){
			$json				= array();
			$service_order_id		= !empty( $request['service_order_id'] ) ? intval($request['service_order_id']) : '';
			$contents 				= !empty( $request['feedback_description'] ) ? esc_attr($request['feedback_description']) : '';
			$reviews 				= !empty( $request['feedback'] ) ? $request['feedback'] : array();
			$user_id 				= !empty( $request['user_id'] ) ? $request['user_id'] : '';
			
			if( empty( $contents ) || empty( $service_order_id ) ){
				$json['type'] 		= 'error';
				
				if( empty( $contents ) ) {
					$json['message'] 	= esc_html__('Feedback detail is required field', 'workreap_api');	
				} 
				
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
				
			} else {
                /* re-arrange the reviews array according to our requirement */
                $new_reviews_array  = array();
                if(!empty($reviews) && is_array($reviews)){
                    foreach ($reviews as $val){
                        foreach ($val as $key => $val_){
                            $new_reviews_array[$key] = $val_;
                        }
                    }
                }
				workreap_save_service_rating($service_order_id,$new_reviews_array,'add');
				$freelancer_id	= get_post_meta( $service_order_id, '_service_author', true);
				$service_id		= get_post_meta( $service_order_id, '_service_id', true);
				
				if( function_exists( 'fw_set_db_post_option' ) ) {
					fw_set_db_post_option($service_order_id, 'feedback', $contents);
				}
				
				workreap_save_service_status( $service_order_id,'completed' );
				
				//update earning
				$where		= array('project_id' => $service_order_id, 'user_id' => $freelancer_id);
				$update		= array('status' 	=> 'completed');
				
				workreap_update_earning( $where, $update, 'wt_earnings');

				// complete service
				$order_id			= get_post_meta($service_order_id,'_order_id',true);
				if ( class_exists('WooCommerce') && !empty( $order_id )) {
					$order = wc_get_order( intval($order_id ) );
					if( !empty( $order ) ) {
						$order->update_status( 'completed' );
					}
				}
				
				$user_ratings	= get_post_meta( $service_order_id ,'_hired_service_rating', true );
				$user_ratings	= !empty( $user_ratings ) ? $user_ratings : 0;
				
				if( function_exists( 'fw_get_db_post_option' ) ) {
					$contents	= fw_get_db_post_option($service_order_id, 'feedback');
				}
				
				$contents		= !empty( $contents ) ? $contents : '';
				
				//Send email to users
				if (class_exists('Workreap_Email_helper')) {
					if (class_exists('WorkreapServiceCompleted')) {
						$email_helper = new WorkreapServiceCompleted();
						$emailData 	  = array();
						
						$freelance_profile_id	= workreap_get_linked_profile_id( $freelancer_id );
						$service_title 			= get_the_title($service_id);
						$service_link 			= get_permalink($service_id);
						
						$employer_name 		= workreap_get_username($user_id);
						$employer_profile 	= get_permalink(workreap_get_linked_profile_id($user_id));
						$freelancer_link 	= get_permalink($freelance_profile_id );
						$freelancer_title 	= get_the_title($freelance_profile_id );
						$freelancer_email 	= get_userdata( $freelancer_id )->user_email;	

							
						$emailData['employer_name'] 		= esc_attr( $employer_name );
						$emailData['employer_link'] 		= esc_url( $employer_profile );
						$emailData['freelancer_name']       = esc_attr( $freelancer_title );
						$emailData['freelancer_link']       = esc_url( $freelancer_link );
						$emailData['freelancer_email']      = esc_attr( $freelancer_email );
						$emailData['service_title'] 		= esc_attr( $service_title );
						$emailData['ratings'] 				= esc_attr( $user_ratings );
						$emailData['service_link'] 			= esc_url( $service_link );
						$emailData['message'] 				= esc_textarea( $contents );

						$email_helper->send_service_completed_email_admin($emailData);
						$email_helper->send_service_completed_email_freelancer($emailData);
						$push						= array();
						$push['freelancer_id']		= $freelancer_id;
						$push['employer_id']		= $user_id;
						$push['service_id']			= $service_id;
						
						
						$push['%freelancer_link%']	= $emailData['freelancer_link'];
						$push['%freelancer_name%']	= $emailData['freelancer_name'];
						$push['%employer_name%']	= $emailData['employer_name'] ;
						$push['%employer_link%']	= $emailData['employer_link'];
						$push['%service_title%']	= $emailData['service_title'];
						$push['%service_link%']		= $emailData['service_link'];
						$push['%ratings%']			= $emailData['ratings'];
						$push['%message%']			= $emailData['message'];
						$push['type']				= 'service_completed';
						
						$push['%replace_ratings%']	= $emailData['ratings'];
						$push['%replace_message%']	= $emailData['message'];
						
						do_action('workreap_user_push_notify',array($push['freelancer_id']),'','pusher_frl_service_complete_content',$push);
						
					}
				}
				
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Your service have been completed successfully.','workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
		}
		/**
         * Get feedbacks
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_services_feedbacks($request){
			$rating_titles 	= workreap_project_ratings('services_ratings');
			$items	= array();
			if( !empty( $rating_titles ) ) {
				$item	= array();
				foreach( $rating_titles as $slug => $label ) {
					$item['slug']	= $slug;
					$item['label']	= $label;
					$items[]		= $item;
				}
			}
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
		}

		/**
         * Get Listings
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_employer_services($request){
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 6;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$type			= !empty( $request['type'] ) ? ( $request['type'] ) : '';

			if( $type === 'completed' ) {
				$post_status	= array('completed');
			} else if( $type === 'hired' ){
				$post_status	= array('hired');
			} else if( $type === 'cancelled' ){
				$post_status	= array('cancelled');
			}

			$order 		= 'DESC';
			$sorting 	= 'ID';

			$args 			= array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'services-orders',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> $post_status,
				'paged' 			=> $page_number,
				'author' 			=> $user_id,
				'suppress_filters' 	=> false
			);
			$query 				= new WP_Query($args);
			$items	= array();
			if( $query->have_posts() ){
				$service_data	= array();
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$service_id			= get_post_meta($post->ID,'_service_id',true);
					$freelance_id		= get_post_meta ( $post->ID,'_service_author',true);
					$service_addons		= get_post_meta( $service_id, '_addons', true);
					$addon_array		= array();
					if( !empty( $service_addons ) ){
						$service_addons_array	= array();
						foreach( $service_addons as $key => $addon ) { 
							$db_price			= 0;

							if (function_exists('fw_get_db_post_option')) {
								$db_price   = fw_get_db_post_option($addon,'price');
							}
							$service_addons_array['title']	= get_the_title($addon);
							$service_addons_array['detail']	= get_the_excerpt($addon);
							$service_addons_array['price']	= workreap_price_format($db_price,'return');
							$addon_array[]					= $service_addons_array;
						}
					}

					$service_data['addons']				= $addon_array;
					$service_data['order_id']			= $post->ID;
					$profile_id							= workreap_get_linked_profile_id($freelance_id);
					$service_data['freelancer_title']		= get_the_title($profile_id);
					$service_data['freelancertagline']	= workreap_get_tagline($profile_id);
					$service_data['freelancer_avatar'] 	= apply_filters(
											'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 65, 'height' => 65), $profile_id), array('width' => 65, 'height' => 65) 
										);
					$service_data['freelancer_verified'] 	= get_post_meta($profile_id, '_is_verified', true);
					
				
					$service_data['ID']					= $service_id;
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   	= fw_get_db_post_option($service_id,'docs');
					}
					$service_data['service_title']	= get_the_title($service_id);
					$service_data['featured_img']	= get_the_post_thumbnail_url($service_id,'workreap_service_thumnail');
					$is_featured					= apply_filters('workreap_service_print_featured',$service_id,'yes');
					if(!empty($is_featured) && $is_featured === 'wt-featured'){
						$is_featured = 'yes';
					} else {
						$is_featured = 'no';
					}
					
					$db_price		= '';
					$avg_rating		= array();
				
					if (function_exists('fw_get_db_post_option')) {
						$db_price   = fw_get_db_post_option($service_id,'price');
					}
				
					$service_data['is_featured']		= $is_featured;

					if( $type === 'completed' ) {
						$feedback	 		= '';
						if (function_exists('fw_get_db_post_option')) {
							$feedback   = fw_get_db_post_option($post->ID, 'feedback');
						}

						$service_data['feedback']	= $feedback;

						$service_ratings	= get_post_meta($post->ID,'_hired_service_rating',true);
						$service_ratings	= !empty( $service_ratings ) ? $service_ratings : 0;
						$service_data['service_ratings']	= $service_ratings;
						$rating_headings 	= workreap_project_ratings('services_ratings');
						if( !empty($rating_headings) ){
							$rating_array		= array();
							foreach ( $rating_headings  as $key => $item ) {
								$saved_projects     = get_post_meta($post->ID, $key, true);
								if( !empty( $saved_projects ) ) {
									$percentage				= $saved_projects;
									$rating_array['title']	= $item;
									$rating_array['score']	= $percentage;
									$avg_rating[]			= $rating_array;
								}
							}
						}
					} else if( $type === 'cancelled' ) {
						$feedback	 				= fw_get_db_post_option($post->ID, 'feedback');
						$service_data['feedback']	= !empty($feedback) ? $feedback : '';
					}
				
					$service_data['rating_data']		= $avg_rating;
					$service_data['price']				= workreap_price_format($db_price,'return');
					$items[]	= $service_data;
				endwhile;
				wp_reset_postdata();

			}
			
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
		}
		/**
         * Manage proposals
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function manage_employer_jobs($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$type			= !empty( $request['type'] ) ? ( $request['type'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$json		= array();
			$item		= array();
			$items		= array();
			$proposals	= array();
			
			$order 			= 'DESC';
			$sorting 		= 'ID';

			if( $type === 'cancelled' ){
				$job_status	= array('cancelled');
			} elseif( $type === 'completed' ){
				$job_status	= array('completed');
			} elseif( $type === 'hired' ){
				$job_status	= array('hired');
			} else {
				$job_status	= array('publish','pending');
			}
			
			$args 			= array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'projects',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> $job_status,
				'paged' 			=> $page_number,
				'author' 			=> $user_id,
				'suppress_filters' 	=> false
			);
			$query 				= new WP_Query($args);
			if( $query->have_posts() ){
				while ($query->have_posts()) {
					$query->the_post();
					global $post;

					$item				= array();
					$author_id 			= $user_id;  
					$linked_profile 	= workreap_get_linked_profile_id($author_id);
					$employer_title 	= esc_html( get_the_title( $linked_profile ));
					$milestone_option	= 'off';

					if (function_exists('fw_get_db_settings_option')) {
						$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
                        $allow_delete_project       = fw_get_db_settings_option('allow_delete_project', $default_value = null);
					}

                    /* find job status () */
                    $job_status     = get_post_status( $post->ID );
                    $item['delete_job_status']	    	= 'no';
                    if( !empty($allow_delete_project) && $allow_delete_project === 'yes' && ($job_status === 'publish' )){
                        $item['delete_job_status']	    	= 'yes';
                    }

					$proposals  			= workreap_get_totoal_proposals($post->ID,'array',-1);
					$proposals_count		= !empty( $proposals ) ? count($proposals) : 0;
					$freelancer_imges		= array();
					if( !empty( $proposals ) ){
						foreach( $proposals as $key=> $proposal ){
							$author_id	= $proposal->post_author;
							$author_id 	= workreap_get_linked_profile_id( $author_id );
							$freelancer_avatar = apply_filters(
											'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $author_id ), array( 'width' => 225, 'height' => 225 )
										);
										$freelancer_imges[]['url']	= $freelancer_avatar;
						}
					}
					$item['freelancer_imges']	    			= $freelancer_imges;
					$title			= esc_html( get_the_title( $post->ID ));
					$is_featured	= get_post_meta( $post->ID, '_featured_job_string',true);
					$is_featured    = !empty( $is_featured ) ? intval( $is_featured ) : '';
					
					$defult	= get_template_directory_uri().'/images/featured.png';
					if (function_exists('fw_get_db_settings_option')) {
						$featured_image		= fw_get_db_settings_option('featured_job_img');
						$featured_bg_color	= fw_get_db_settings_option('featured_job_bg');
						
					}
					$project_type    		= fw_get_db_post_option($post->ID,'project_type');
					$item['project_type']	= !empty($project_type['gadget']) ? $project_type['gadget'] : '';
					$tag		  = !empty( $featured_image['url'] ) ? $featured_image['url'] : $defult;
					$color		  = !empty( $featured_bg_color ) ? $featured_bg_color : '#f1c40f';
					$item['tag']	    	= '';
					$item['color']	    	= '';
					if( !empty( $is_featured ) && $is_featured === 1 ) {
						$item['tag']	    	= $tag;
						$item['color']	    	= $color;
					}

					$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
					
					if( !empty($milestone) && $milestone === 'enable' ){
						$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
					}
					
					if( $type === 'hired' ){
						$proposal_id					= get_post_meta( $post->ID, '_proposal_id', true );
						$item['proposal_id']	    	= $proposal_id;
					} 
					
					
					$item['ID']	    	= $post->ID;
					$item['title']		= get_the_title($post->ID);
					$item['milestone_option']	   = $milestone_option;
					$item['proposals_count']	   = $proposals_count;
					

					$is_verified 	= get_post_meta($linked_profile, '_is_verified', true);
					$title			= $employer_title;
					if( function_exists('workreap_get_username') ){
						$title	= workreap_get_username('',$linked_profile);
					}
				
					$item['employer_avatar'] 				= apply_filters(
						'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $linked_profile ), array( 'width' => 100, 'height' => 100 )
					);
				

					$item['employer_verified']		= 'no';
					if( !empty( $is_verified ) && $is_verified === 'yes' ){
						$item['employer_verified']		= 'yes';
					}

					$item['employer_name']		= $title;

					//project level
					$project_level = '';
					if (function_exists('fw_get_db_post_option')) {
						$project_level          = fw_get_db_post_option($post->ID, 'project_level', true);                
					}

					$item['project_level']		= workreap_get_project_level($project_level);

					$item['hired_freelancer_title']	= '';
					$item['hired_freelancer_img']	= '';
					$item['hire_linked_profile'] 	= '';
					if( !empty($type) && ($type === 'completed' || $type === 'cancelled' || $type === 'hired' )){
						$proposal_id				= get_post_meta( $post->ID, '_proposal_id', true);
						$hired_freelance_id			= get_post_field('post_author',$proposal_id);
						$item['hire_linked_profile'] 		= $hired_freelance_id;
						$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
						$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id);
						$item['hired_freelancer_title'] 		= esc_html( get_the_title( $hire_linked_profile ));
						$item['hired_freelancer_img'] 			= apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 100, 'height' => 100 )
						);
						
					}
					//Location
					$item['location_name']		= '';
					$item['location_flag']		= '';
					if( !empty( $post->ID ) ){ 
						$args = array();
						if( taxonomy_exists('locations') ) {
							$terms = wp_get_post_terms( $post->ID, 'locations', $args );
							if( !empty( $terms ) ){
								foreach ( $terms as $key => $term ) {    
									$country = fw_get_db_term_option($term->term_id, 'locations', 'image');
									$item['location_name']		= !empty($term->name) ? $term->name : '';;
									$item['location_flag']		= !empty($country['url']) ? workreap_add_http( $country['url'] ) : '';;
								}
							}
						}

					}
				
					$items[]	= $item;
					
				}
			}
				
			$items		    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
		}

		/**
         * Manage proposals
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function manage_job_proposals($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : -1;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$job_id			= !empty( $request['job_id'] ) ? intval( $request['job_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$json		= array();
			$item		= array();
			$items		= array();
			$meta_query_args	= array();
			
			$query_args = array('posts_per_page' => $limit,
				'post_type' 		=> 'proposals',
				'paged' 		 	=> $page_number,
				'suppress_filters' 	=> false,
			);

			$meta_query_args[] = array(
				'key' 			=> '_project_id',
				'value' 		=> $job_id,
				'compare' 		=> '='
			);
			
			$query_relation = array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    
			
			
			$job_status				= get_post_status( $job_id );
			$hired_freelancer_id	= get_post_meta($job_id,'_freelancer_id',true);
			
			$milestone				= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			$is_milestone				= 'off';
			if(!empty($milestone) && $milestone === 'enable') {
				$_milestone   	= get_post_meta($job_id,'_milestone',true);
				$is_milestone	= !empty( $_milestone ) ? $_milestone : 'off';
			}

			$query = new WP_Query($query_args);
			if( $query->have_posts() ){
				while ($query->have_posts()) {
					$query->the_post();
					global $post;
					$proposals	= array();
					$author_id 			= get_the_author_meta( 'ID' );  
					$linked_profile 	= workreap_get_linked_profile_id($author_id);
					$project_type    	= fw_get_db_post_option($job_id,'project_type');
					$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
					$order_id			= get_post_meta( $post->ID, '_order_id', true );
					$total_amount		= '';

					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
					} else {
						$proposal_docs	= '';
					}
					
					$proposal_status			= get_post_meta($post->ID,'_proposal_status',true);
					$proposal_status			= !empty($proposal_status) ? $proposal_status : '';
					$proposal_post_status 		= get_post_status($post->ID);
					$proposal_hiring_status 	= $proposal_status;
					
					
					$proposals['job_hiring_status'] 			= 'pending';
					$proposals['job_hiring_proposal_status'] 	= 'pending';
					$proposals['send_milestone_request'] 		= 'no';
					$proposals['job_hiring_under_review'] 		= 'no';
					$proposals['add_new_milestone'] 			= 'no';
	
					if( $job_status == 'hired' && $hired_freelancer_id == $author_id ) {
						$proposals['hired_freelancer_id'] 		= $hired_freelancer_id;
					}else{
						$proposals['hired_freelancer_id'] 		= 0;
					}

					$proposals['order_id']	= !empty($order_id) ? intval($order_id) : 0;
					$proposals['order_url']	= '';
					if( !empty( $order_id ) ){
						if( class_exists('WooCommerce') ) {
							$order		= wc_get_order($order_id);
							$proposals['order_url']	= $order->get_view_order_url().'&platform=app';
						}
					}
					
					$proposals['proposal_id'] 		= $post->ID;
					$proposals['freelancer_id'] 	= $linked_profile;
					$proposals['user_id'] 			= $author_id;
					$proposals['project_id'] 		= $job_id;
					$proposals['milestone'] 		= $is_milestone;
					$proposals['proposed_amount'] 	= html_entity_decode( apply_filters('workreap_price_format',$proposed_amount,'return'));
					$proposals['freelancer_title'] 	= esc_html(get_the_title( $linked_profile ));
					$proposals['freelancer_title'] 	= esc_html(get_the_title( $linked_profile ));
					$proposals['email_verified'] 	= get_post_meta($linked_profile, '_is_verified', true);
					$proposals['identity_verified'] = get_post_meta($linked_profile, 'identity_verified', true);
					
					$proposals['duration']	= '';
					$proposals['estimated_hours']	= '';
					$proposals['price_per_hour']	= '';
					
					$proposals['chat_options']		= 'disabled';
					$proposals['hire_and_set_milestone']	= 'no';
					$proposals['hire_now']	= 'no';
					
					if( $job_status === 'hired' ) {
						$proposals['job_hiring_status'] 		= 'hired';
					} elseif( $job_status === 'completed' ) {
						$proposals['job_hiring_status'] 		= 'completed';
					}else{
					
						if( $job_status !== 'hired' ) { 
							$chat_option	= array();
							if( function_exists('fw_get_db_settings_option')  ){
								$chat_option	= fw_get_db_settings_option('proposal_message_option', $default_value = null);
							}

							if(!empty($chat_option) && $chat_option === 'enable' ){
								$proposals['chat_options']	= 'enabled';
							}

							if(!empty($milestone) && $milestone === 'enable') {

								if(!empty($proposal_post_status) && $proposal_post_status === 'cancelled' ){
									$proposals['job_hiring_proposal_status'] = 'cancelled';
								}

								$_milestone   	= get_post_meta($job_id,'_milestone',true);
								$is_milestone	= !empty( $_milestone ) ? $_milestone : 'off';
								if(!empty($is_milestone) && $is_milestone ==='on' ){
									$total_milestone_price			= workreap_get_milestone_statistics($post->ID,array('pending','publish'));
									$total_milestone_price			= !empty($total_milestone_price) ? $total_milestone_price : 0;
									
									if(!empty($proposal_status) && $proposal_status === 'pending' ){ 
										$proposals['job_hiring_under_review'] = 'yes';
									}

									if( empty($proposal_status) && intval($proposed_amount) == intval($total_milestone_price)) {
										$proposals['send_milestone_request'] = 'yes';
									}

									if( $proposed_amount > $total_milestone_price ){
										$proposals['add_new_milestone'] = 'yes';
									}
									
									$proposals['hire_and_set_milestone']	= 'yes';
								} else if( empty($order_id) ){
									$proposals['hire_now']	= 'yes';
								}
							} else if( empty($order_id) ){
								$proposals['hire_now']	= 'yes';
							} 
						}
					}
					$duration	= '';
					if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'fixed' ) { 
						$proposed_duration  = get_post_meta($post->ID, '_proposed_duration', true);
						$duration_list		= worktic_job_duration_list();
						$duration			= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
						if( !empty( $duration ) ) {
							$duration	= esc_html( $duration );
						}
					}
					
					if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'hourly' ) { 
						$estimeted_time		= get_post_meta($post->ID,'_estimeted_time',true);
						$per_hour_amount	= get_post_meta($post->ID,'_per_hour_amount',true);
						$estimeted_time		= !empty( $estimeted_time ) ? $estimeted_time : 0;
						$per_hour_amount	= !empty( $per_hour_amount ) ? $per_hour_amount : 0;
						$total_amount		= html_entity_decode( apply_filters('workreap_price_format',$per_hour_amount,'return'));
					
						if( !empty( $estimeted_time ) ){
							$proposals['estimated_hours'] = esc_html__('Estimated hours','workreap_api').' '.esc_html( $estimeted_time );
						} 
						if( !empty( $per_hour_amount ) ){
							$proposals['price_per_hour'] = esc_html__('Amount per hour','workreap_api').' '.esc_html( $total_amount );
						} 
					}

					$proposals['duration'] 			= $duration;
					$proposals['proposal_docs'] 	= !empty( $proposal_docs ) && is_array( $proposal_docs ) ?  count( $proposal_docs ) : 0;
					$proposals['freelancer_avatar'] = apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
						);

					$reviews_data 	= get_post_meta( $linked_profile, 'review_data');
					$proposals['freelancer_reviews_rate']	= !empty( $reviews_data[0]['wt_average_rating'] ) ? floatval( $reviews_data[0]['wt_average_rating'] ) : 0 ;
					$proposals['freelancer_total_rating']	= !empty( $reviews_data[0]['wt_total_rating'] ) ? intval( $reviews_data[0]['wt_total_rating'] ) : 0 ;
					$proposals['content']					= nl2br( stripslashes( get_the_content('',true,$post->ID) ) );
				
					$items[]	= $proposals;
					
				}
			}
				
			$items		    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
		}

    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetEmployersDashbord;
	$controller->register_routes();
});
