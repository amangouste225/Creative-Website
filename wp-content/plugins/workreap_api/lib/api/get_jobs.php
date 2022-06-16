<?php
if (!class_exists('AndroidAppGetJobsRoutes')) {

    class AndroidAppGetJobsRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'listing';

            register_rest_route($namespace, '/' . $base . '/get_jobs',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_listing'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/update_job',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'update_job'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );

			register_rest_route($namespace, '/' . $base . '/get_proposals_listing',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_proposals_listing'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );

			/* delete employer job */
            register_rest_route($namespace, '/' . $base . '/delete_listing',
                array(
                    array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'delete_listing'),
                        'args' 		=> array(),
                        'permission_callback' => '__return_true',
                    ),
                )
            );

        }
		
		/**
         * Get Proposal Listing
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function get_proposals_listing($request) {
			$json				= array();
			$items				= array();
			$project_id			= !empty($request['project_id']) ? intval($request['project_id']) : '';
			$user_id			= !empty($request['user_id']) ? intval($request['user_id']) : '';
			
			$user_identity 	 = $user_id;
			$url_identity 	 = $user_identity;
			$linked_profile  = workreap_get_linked_profile_id($user_identity);
			$post_id 		 = $linked_profile;
			$meta_query_args = array();

			$show_posts 			= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
			$edit_id				= $project_id;
			$post_author			= get_post_field('post_author', $edit_id);
			$hired_freelancer_id	= get_post_meta($edit_id,'_freelancer_id',true);

			$job_status				= get_post_status( $edit_id );
			$milestone				= array();
			
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$milestone				= !empty($milestone['gadget']) ? $milestone['gadget'] : '';


			$offline_package		= worrketic_hiring_payment_setting();
			$offline_package		= !empty($offline_package['type']) ? $offline_package['type'] : '';
			
			$query_args = array('posts_per_page' => $show_posts,
				'post_type' 			=> 'proposals',
				'paged' 		 	  	=> $paged,
				'suppress_filters' 		=> false,
			);

			$meta_query_args[] = array(
				'key' 			=> '_project_id',
				'value' 		=> $edit_id,
				'compare' 		=> '='
			);
			$query_relation = array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);    


			$pquery = new WP_Query($query_args);
			$count_post = $pquery->found_posts;

			if( $pquery->have_posts() ){
				while ($pquery->have_posts()) : $pquery->the_post();
					global $post;
					$author_id 			= get_the_author_meta( 'ID' );  
					$linked_profile 	= workreap_get_linked_profile_id($author_id);
					$freelancer_title 	= esc_html(get_the_title( $linked_profile ));

					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
					} else {
						$proposal_docs	= '';
					}

					$proposal_docs = !empty( $proposal_docs ) && is_array( $proposal_docs ) ?  count( $proposal_docs ) : 0;
					$freelancer_avatar = apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
						);
				
					$order_id	= get_post_meta( $post->ID, '_order_id', true );
					$order_id	= !empty($order_id) ? intval($order_id) : 0;
					$order_url	= '';
					if( !empty( $order_id ) ){
						if( class_exists('WooCommerce') ) {
							$order		= wc_get_order($order_id);
							$order_url	= $order->get_view_order_url();
						}
					}
				
					$chat_option	= array();
					if( function_exists('fw_get_db_settings_option')  ){
						$chat_option	= fw_get_db_settings_option('proposal_message_option', $default_value = null);
					}
					
					if(!empty($chat_option) && $chat_option === 'enable' ){
						$json['linked_profile']	 = $linked_profile;
					}
				
				
					if(!empty($milestone) && $milestone === 'enable') {
						$_milestone   	= get_post_meta($edit_id,'_milestone',true);
						$is_milestone	= !empty( $_milestone ) ? $_milestone : 'off';
						if(!empty($is_milestone) && $is_milestone ==='on' ){
							$json['milestone_enabled']	 = 'yes';
						} else if( empty($order_id) ){
							$json['milestone_enabled']	 = 'no';
						}
					} else if( empty($order_id) ){
						$json['milestone_enabled']	 = 'no';
					}
				
				
				$project_type    	= fw_get_db_post_option($project_id,'project_type');
				$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
				$total_amount		= '';
				$json['proposed_amount'] = apply_filters('workreap_price_format',$proposed_amount,'return');
				
				if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'fixed' ) { 
					$proposed_duration  = get_post_meta($job_id, '_proposed_duration', true);
					$duration_list		= worktic_job_duration_list();
					$duration			= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
					
					if( !empty( $duration ) ) {
						$json['per_hour_amount'] = $duration;
					}
				} 
				
				if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'hourly' ) { 
					$estimeted_time		= get_post_meta($job_id,'_estimeted_time',true);
					$per_hour_amount	= get_post_meta($job_id,'_per_hour_amount',true);
					$estimeted_time		= !empty( $estimeted_time ) ? $estimeted_time : 0;
					$per_hour_amount	= !empty( $per_hour_amount ) ? $per_hour_amount : 0;
					$total_amount		= apply_filters('workreap_price_format',$per_hour_amount,'return');

					if( !empty( $estimeted_time ) ){
						$json['estimated_hours']	 = esc_html__('Estimated hours','workreap_api').' ('.$estimeted_time.')';
					} 
					
					if( !empty( $per_hour_amount ) ){
						$json['per_hour_amount']	 = esc_html__('Amount per hour','workreap_api').' ('.$total_amount.')';
					} 
				}
				
				if (function_exists('fw_get_db_post_option')) {
					$proposal_docs = fw_get_db_post_option($job_id, 'proposal_docs');
				}

				$proposal_docs = !empty( $proposal_docs ) ?  count( $proposal_docs ) : 0;
			
				if( !empty ( $linked_profile ) ) {
					$reviews_data 	= get_post_meta( $linked_profile , 'review_data');
					$reviews_rate	= !empty( $reviews_data[0]['wt_average_rating'] ) ? floatval( $reviews_data[0]['wt_average_rating'] ) : 0 ;
					$total_rating	= !empty( $reviews_data[0]['wt_total_rating'] ) ? intval( $reviews_data[0]['wt_total_rating'] ) : 0 ;
				} else {
					$reviews_rate	= 0;
					$total_rating	= 0;
				}
				
				$round_rate 		= number_format((float) $reviews_rate, 1);
				$rating_average		= ( $round_rate / 5 )*100;
				
				
				if ( function_exists('fw_get_db_post_option' )) {
					$identity_verification    	= fw_get_db_settings_option('identity_verification');
					$email_verify_icon    		= fw_get_db_settings_option('email_verify_icon');
					$identity_verify_icon    	= fw_get_db_settings_option('identity_verify_icon');
				}

				$is_verified 		= get_post_meta($linked_profile, '_is_verified', true);
				$identity_verified 	= get_post_meta($linked_profile, 'identity_verified', true);

				$json['proposal_id']	 	 = $post->ID;
				$json['freelancer_avatar']	 = $freelancer_avatar;
				$json['freelancer_title']	 = $freelancer_title;
				$json['job_status']	 		 = $job_status;
				$json['hired_freelancer_id'] = $hired_freelancer_id;
				$json['proposal_author_id']	 = $author_id;
				$json['reviews_rate']	 	= $reviews_rate;
				$json['total_rating']	 	= $total_rating;
				$json['round_rate']	 	 	= $round_rate;
				$json['rating_average']	 	= $rating_average;
				$json['cover_latter']	 	= nl2br( stripslashes( get_the_content('',true,$post->ID) ) );;
				$json['proposal_docs']	 	= $proposal_docs;
				$json['is_verified']	 	= $is_verified;
				$json['identity_verified']	= $identity_verified;

				endwhile;
				wp_reset_postdata();
			}else{
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('No porposal listing found', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}
			
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Porposal listing', 'workreap_api');
			$items[] 			= $json;
			return new WP_REST_Response($items, 200);
		}
		
		/**
         * Get Listings
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function update_job($request) {
			$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			
			$json				= array();
			$items				= array();
			$job_files			= array();
			$submitted_files	= array();
			$hide_map 			= 'show';

			if (function_exists('fw_get_db_settings_option')) {
				$hide_map				= fw_get_db_settings_option('hide_map');
				$job_status				= fw_get_db_settings_option('job_status');
				$remove_freelancer_type   	= fw_get_db_settings_option('remove_freelancer_type');
				$remove_english_level   	= fw_get_db_settings_option('remove_english_level');
				$remove_project_level   	= fw_get_db_settings_option('remove_project_level');
				$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
				$project_mandatory			= fw_get_db_settings_option('project_required');
			}

			$job_status	=  !empty( $job_status ) ? $job_status : 'publish';
			$current 	= !empty($request['id']) ? intval($request['id']) : '';

			if( apply_filters('workreap_is_job_posting_allowed','wt_jobs', $user_id) === false && empty($current) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('You’ve consumed all you points to add new job.','workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if( isset( $hide_map ) && $hide_map === 'show' ){
				$required = array(
					'title'   			=> esc_html__('Job title is required', 'workreap_api'),
					'project_level'  	=> esc_html__('Project level is required', 'workreap_api'),
					'project_duration'  => esc_html__('Project duration is required', 'workreap_api'),
					'english_level'   	=> esc_html__('English level is required', 'workreap_api'),
					'project_type' 		=> esc_html__('Please select job type.', 'workreap_api'),
                    'categories' 		=> esc_html__('Please select at-least one category', 'workreap_api'),
					'address'   		=> esc_html__('Address is required', 'workreap_api'),
					'country'   		=> esc_html__('Country is required', 'workreap_api'),
				);
			} else{
				$required = array(
					'title'   			=> esc_html__('Job title is required', 'workreap_api'),
					'project_level'  	=> esc_html__('Project level is required', 'workreap_api'),
					'project_duration'  => esc_html__('Project duration is required', 'workreap_api'),
					'english_level'   	=> esc_html__('English level is required', 'workreap_api'),
					'project_type' 		=> esc_html__('Please select job type.', 'workreap_api'),
                    'categories' 		=> esc_html__('Please select at-least one category', 'workreap_api'),
					'country'   		=> esc_html__('Country is required', 'workreap_api'),
				);
			}

			$required	= apply_filters('workreap_filter_post_job_fields',$required);

			//remove english level
			if(!empty($remove_english_level) && $remove_english_level === 'yes' ){
				unset( $required['english_level']);
			}

			if(!empty($remove_project_level) && $remove_project_level === 'yes' ){
				unset( $required['project_level']);
			}
			if(!empty($remove_project_duration) && $remove_project_duration === 'yes' ){
				unset( $required['project_duration']);
			}
			
			if (function_exists('fw_get_db_settings_option')) {
				$job_option_setting         = fw_get_db_settings_option('job_option', $default_value = null);
				$multiselect_freelancertype = fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
				$job_experience_single  	= fw_get_db_settings_option('job_experience_option', $default_value = null);
				$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
				$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}

			$multiselect_freelancertype = !empty($multiselect_freelancertype) ?  $multiselect_freelancertype: '';
			$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
			$job_option_setting 		= !empty($job_option_setting) ? $job_option_setting : '';
			$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

			if(!empty($job_option_setting) && $job_option_setting === 'enable' ){
				$required['job_option']	= esc_html__('Project location type is required', 'workreap_api');
			}

			foreach ($required as $key => $value) {
				if( empty( $request[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					return new WP_REST_Response($json, 203);
				}

				if( $key === 'project_type' && $request['project_type'] === 'hourly' && empty( $request['hourly_rate'] )  ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Per hour rate is required', 'workreap_api');        
					return new WP_REST_Response($json, 203);
				} else if( $key === 'project_type' && $request['project_type'] === 'hourly' && empty( $request['estimated_hours'] )  ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Estimated hours is required', 'workreap_api');        
					return new WP_REST_Response($json, 203);
				} else if( $key == 'project_type' && $request['project_type'] === 'hourly' && !empty( $request['max_price'] ) && $request['max_price'] < $request['hourly_rate'] ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap_api');        
					return new WP_REST_Response($json, 203);
				} else if( $key == 'project_type' && $request['project_type'] === 'fixed' && !empty( $request['max_price'] ) && $request['max_price'] < $request['project_cost'] ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Maximum project cost should not be less than minimum project cost', 'workreap_api');        
					return new WP_REST_Response($json, 203);
				} else if( $key == 'project_type' && $request['project_type'] === 'fixed' && empty( $request['project_cost'] )  ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Project cost is required', 'workreap_api');        
					return new WP_REST_Response($json, 203);
				}

			}

			//extract the job variables
			$title				= !empty( $request['title'] ) ? esc_attr( $request['title'] ) : rand(1,999999);
			$description		= !empty( $request['description'] ) ? $request['description'] : '';
			$project_level		= !empty( $request['project_level'] ) ? $request['project_level'] : '';
			$project_duration	= !empty( $request['project_duration'] ) ? $request['project_duration'] : '';
			$english_level		= !empty( $request['english_level'] ) ? $request['english_level'] : '';
			$project_type		= !empty( $request['project_type'] ) ? $request['project_type'] : '';
			$freelancer_level	= !empty( $request['freelancer_level'] ) ? $request['freelancer_level'] : '';
			$hourly_rate		= !empty( $request['hourly_rate'] ) ? $request['hourly_rate'] : '';
			$project_cost		= !empty( $request['project_cost'] ) ? $request['project_cost'] : '';
			$expiry_date        = !empty( $request['expiry_date'] ) ? $request['expiry_date'] : '';
			$total_attachments 	= !empty( $request['size'] ) ? $request['size'] : 0;
			$current 			= !empty( $request['id']) ? intval($request['id']) : '';
			$show_attachments   = !empty( $request['show_attachments'] ) ? $request['show_attachments'] : 'off';
            $max_price          = !empty( $request['max_price'] ) ? $request['max_price'] : '';
            $estimated_hours    = !empty( $request['estimated_hours'] ) ? $request['estimated_hours'] : '';


			if( isset( $request['submit_type'] ) && $request['submit_type'] === 'update' ){
				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;
				$status 	 = get_post_status($post_id);

				if( intval( $post_author ) === intval( $user_id ) ){
					$article_post = array(
						'ID' 			=> $current,
						'post_title' 	=> $title,
						'post_content' 	=> $description,
						'post_status' 	=> $status,
					);

					wp_update_post($article_post);
				} else{
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}

				//change status on update
				do_action('workreap_update_post_status_action',$post_id,'project'); //Admin will get an email to publish it

			} else{
				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_status'   => $job_status,
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'projects',
				);

				$post_id    		= wp_insert_post( $user_post );
				update_post_meta( $post_id, '_featured_job_string',0 );

				//update api key data
				if( apply_filters('workreap_filter_user_promotion', 'disable') === 'enable' ){	
					do_action('workreap_update_users_marketing_attributes',$user_id,'posted_projects');
				}

				//update jobs
				$remaning_jobs	= workreap_get_subscription_metadata( 'wt_jobs',intval($user_id) );
				$remaning_jobs	= !empty( $remaning_jobs ) ? intval($remaning_jobs) : 0;

				if( !empty( $remaning_jobs ) && $remaning_jobs >= 1 ) {
					$update_jobs	= intval( $remaning_jobs ) - 1 ;
					$update_jobs	= intval($update_jobs);

					$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
					$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();

					$wt_subscription['wt_jobs'] = $update_jobs;
					
					update_user_meta( intval($user_id), 'wt_subscription', $wt_subscription);
				}

				$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',$user_id );

				if( !empty($expiry_string) ) {
					update_post_meta($post_id, '_expiry_string', $expiry_string);
				}
			}


			if( $post_id ){
				//Upload files from temp folder to uploads
				$job_files			= array();
				if( !empty( $_FILES ) && $total_attachments != 0 ){
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					
					$counter	= 0;
					for ($x = 0; $x < $total_attachments; $x++) {
						$submitted_files = $_FILES['project_documents'.$x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 			=> $uploaded_image['url'],
							'post_mime_type' => $file_type['type'],
							'post_title' 	=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 	=> '',
							'post_status' 	=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
			
						$job_files[]					= $attachments;
					}
				}

				$languages               = !empty( $request['languages'] ) ? json_decode($request['languages'], true) : array();
				$categories              = !empty( $request['categories'] ) ? json_decode($request['categories'], true) : array();
				$skills              	 = !empty( $request['skills'] ) ? json_decode($request['skills'], true) : array();
				$expiry_date             = !empty( $request['expiry_date'] ) ? $request['expiry_date'] : '';
				$deadline             	 = !empty( $request['deadline'] ) ? $request['deadline'] : '';
				$is_featured             = !empty( $request['is_featured'] ) ? $request['is_featured'] : '';

				if( !empty($is_featured) ){
					if( $is_featured === 'on'){
						$is_featured_job	= get_post_meta($post_id,'_featured_job_string',true); 
						if(empty($is_featured_job)){
							$featured_jobs	= workreap_featured_job( $user_id );
							if( $featured_jobs ) {
								update_post_meta($post_id, '_featured_job_string', 1);
								$remaning_featured_jobs		= workreap_get_subscription_metadata( 'wt_featured_jobs',intval($user_id) );
								$remaning_featured_jobs  	= !empty( $remaning_featured_jobs ) ? intval($remaning_featured_jobs) : 0;

								if( !empty( $remaning_featured_jobs) && $remaning_featured_jobs >= 1 ) {
									$update_featured_jobs	= intval( $remaning_featured_jobs ) - 1 ;
									$update_featured_jobs	= intval( $update_featured_jobs );
									$wt_subscription 	= get_user_meta(intval($user_id), 'wt_subscription', true);
									$wt_subscription	= !empty( $wt_subscription ) ?  $wt_subscription : array();
									$wt_subscription['wt_featured_jobs'] = $update_featured_jobs;

									update_user_meta( intval($user_id), 'wt_subscription', $wt_subscription);
								}
							} else{
								update_post_meta( $post_id, '_featured_job_string',0 );
							}
						}
					} else {
						update_post_meta( $post_id, '_featured_job_string',0 );
					}
				} else{
					update_post_meta( $post_id, '_featured_job_string',0 );
				}

				//update langs
				wp_set_post_terms( $post_id, $languages, 'languages' );

				//update cats
				wp_set_post_terms( $post_id, $categories, 'project_cat' );

				//update skills
				wp_set_post_terms( $post_id, $skills, 'skills' );

				// price range
				if(!empty($job_price_option) && $job_price_option === 'enable' ){
					update_post_meta($post_id, '_max_price', workreap_wmc_compatibility( $max_price));
				}

				// update projec expriences
				if(!empty($job_experience_single['gadget']) && $job_experience_single['gadget'] === 'enable' ){
					$experiences		= !empty( $request['experiences'] ) ? $request['experiences'] : array();
					wp_set_post_terms( $post_id, $experiences, 'project_experience' );
				}

				//update
				update_post_meta($post_id, '_expiry_date', $expiry_date);
				update_post_meta($post_id, 'deadline', $deadline);
				update_post_meta($post_id, '_project_type', $project_type);
				update_post_meta($post_id, '_project_duration', $project_duration);
				update_post_meta($post_id, '_english_level', $english_level);

				update_post_meta($post_id, '_estimated_hours', $estimated_hours);
				update_post_meta($post_id, '_hourly_rate', workreap_wmc_compatibility( $hourly_rate));
				update_post_meta($post_id, '_project_cost', workreap_wmc_compatibility( $project_cost));

				$project_data	= array(); 
				$project_data['gadget']	= !empty( $request['project_type'] ) ? $request['project_type'] : 'fixed';
				$project_data['hourly']['hourly_rate']		= !empty( $request['hourly_rate'] ) ? $request['hourly_rate'] : '';
				$project_data['hourly']['estimated_hours']	= !empty( $request['estimated_hours'] ) ? $request['estimated_hours'] : '';
				$project_data['fixed']['project_cost']		= !empty( $request['project_cost'] ) ? workreap_wmc_compatibility( $request['project_cost']) : '';
				$project_data['hourly']['max_price']		= !empty( $request['max_price'] ) ? workreap_wmc_compatibility( $request['max_price']) : '';
				$project_data['fixed']['max_price']			= !empty( $request['max_price'] ) ? workreap_wmc_compatibility( $request['max_price']) : '';

				//update location
				$address    = !empty( $request['address'] ) ? $request['address'] : '';
				$country    = !empty( $request['country'] ) ? $request['country'] : '';
				$latitude   = !empty( $request['latitude'] ) ? $request['latitude'] : '';
				$longitude  = !empty( $request['longitude'] ) ? $request['longitude'] : '';

				update_post_meta($post_id, '_address', $address);
				update_post_meta($post_id, '_country', $country);
				update_post_meta($post_id, '_latitude', $latitude);
				update_post_meta($post_id, '_longitude', $longitude);
				
				//Set country for unyson
				$locations = get_term_by( 'slug', $country, 'locations' );
				$location = array();
				if( !empty( $locations ) ){
					$location[0] = $locations->term_id;

					if( !empty( $location ) ){
						wp_set_post_terms( $post_id, $location, 'locations' );
					}

				}

				//update unyson meta
				$fw_options = array();

				if(!empty($job_price_option) && $job_price_option === 'enable' ){
					$fw_options['max_price']         	 = workreap_wmc_compatibility( $max_price );
				}

				$freelancer_level	= !empty( $request['freelancer_level'] ) ? $request['freelancer_level']  : array();
				if(!empty($multiselect_freelancertype) && $multiselect_freelancertype === 'enable' ){
					$fw_options['freelancer_level']      = $freelancer_level;
				} else {
					$freelancer_level					= !empty($freelancer_level[0]) ? $freelancer_level[0] : '';
					$fw_options['freelancer_level'][0]  = $freelancer_level;
				}

				if( !empty($milestone) && $milestone ==='enable' && !empty($project_data['gadget']) && $project_data['gadget'] ==='fixed' ){
					$is_milestone    			= !empty( $request['is_milestone'] ) ? $request['is_milestone'] : 'off';
					$project_data['project_type']['fixed']['milestone']  	= $is_milestone;
					update_post_meta($post_id, '_milestone', $is_milestone);
				}

				// update post option
				if( !empty($job_option_setting) && $job_option_setting === 'enable' ){
					$job_option_text						= !empty( $request['job_option'] ) ? $request['job_option'] : '';
					$fw_options['job_option']    			= $job_option_text;
					update_post_meta($post_id, '_job_option', $job_option_text);
				}

				update_post_meta($post_id, '_freelancer_level', $freelancer_level);
				$job_faq_option		= fw_get_db_settings_option('job_faq_option');
				if(!empty($job_faq_option) && $job_faq_option == 'yes' ) {
					$faq 					= !empty( $request['faq'] ) ? $request['faq'] : array();
					$fw_options['faq']      = $faq;
				}
				$fw_options['expiry_date']         	 = $expiry_date;
				$fw_options['deadline']         	 = $deadline;
				$fw_options['project_level']         = $project_level;
				$fw_options['project_type']          = $project_data;
				$fw_options['project_duration']      = $project_duration;
				$fw_options['english_level']         = $english_level;
				$fw_options['show_attachments']      = $show_attachments;
				$fw_options['project_documents']     = $job_files;
				$fw_options['address']            	 = $address;
				$fw_options['longitude']          	 = $longitude;
				$fw_options['latitude']           	 = $latitude;
				$fw_options['country']            	 = $location;


				//Update User Profile
				fw_set_db_post_option($post_id, null, $fw_options);

				if( isset( $request['submit_type'] ) && $request['submit_type'] === 'update' ){
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your job has been updated', 'workreap_api');
				} else{
					//Send email to users
					if (class_exists('Workreap_Email_helper')) {
						if (class_exists('WorkreapJobPost')) {
							$email_helper = new WorkreapJobPost();
							$emailData 	  = array();

							$employer_name 		= workreap_get_username($user_id);
							$employer_email 	= get_userdata( $user_id )->user_email;
							$employer_profile 	= get_permalink($user_id);
							$job_title 			= esc_html( get_the_title($post_id) );
							$job_link 			= get_permalink($post_id);


							$emailData['employer_name'] 	= esc_html( $employer_name );
							$emailData['employer_email'] 	= sanitize_email( $employer_email );
							$emailData['employer_link'] 	= esc_url( $employer_profile );
							$emailData['status'] 			= esc_html( $job_status );
							$emailData['job_link'] 			= esc_url( $job_link );
							$emailData['job_title'] 		= esc_html( $job_title );

							$email_helper->send_admin_job_post($emailData);
							$email_helper->send_employer_job_post($emailData);
						}
					}

					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your job has been posted.', 'workreap_api');
				}

				//add custom data
				do_action('workreap_post_job_extra_data',$request,$post_id);

				//Prepare Params
				$params_array['user_identity'] 	= $user_id;
				$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id );
				$params_array['type'] 			= 'project_create';

				do_action('wt_process_job_child', $params_array);

			} else {
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
			}

			return new WP_REST_Response($json, 200);
		}

		
        /**
         * Get Listings
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_listing($request){
			
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 10;
			$job_id			= !empty( $request['job_id'] ) ? intval( $request['job_id'] ) : '';
			$author_id		= !empty( $request['company_id'] ) ? intval( $request['company_id'] ) : '';
			$profile_id		= !empty( $request['profile_id'] ) ? intval( $request['profile_id'] ) : '';
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$listing_type	= !empty( $request['listing_type'] ) ? esc_attr( $request['listing_type'] ) : '';
			
			
			$offset 		= ($page_number - 1) * $limit;
			
			$json			= array();
			$items			= array();
			$today 			= time();
			
			if( !empty($profile_id) ) {
				$saved_projects	= get_post_meta($profile_id,'_saved_projects',true);
			}else {
				$saved_projects	= array();
			}
			$job_faq_option		= fw_get_db_settings_option('job_faq_option');
			$defult				= get_template_directory_uri().'/images/featured.png';
			
			$json['type']		= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
			if( $request['listing_type'] === 'single' ){
				
				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'projects',
					'post__in' 		 	  	=> array($job_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}else if( !empty($listing_type) && $listing_type === 'featured' ){
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'projects',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);
				
				//order by pro member
				$query_args['meta_key'] = '_featured_job_string';
				$query_args['orderby']	 = array( 
					'ID'      		=> 'DESC',
					'meta_value' 	=> 'DESC', 
				); 

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				

			} elseif( !empty($listing_type) && $listing_type === 'single' ){
				$post_id		= !empty( $request['job_id'] ) ? $request['job_id'] : '';
				$query_args = array(
					'post_type' 	 	  	=> 'any',
					'p'						=> $post_id
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} elseif( !empty($listing_type) && !empty($author_id) && $listing_type === 'company'  ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	=> -1,
									'post_type' 	 	=> 'projects',
									'post_status' 	 	=> array('publish','pending'),
									'author' 			=> $author_id,
									'suppress_filters' 	=> false
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}elseif( !empty($listing_type) && $listing_type === 'latest' ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	  	=> $limit,
									'post_type' 	 	  	=> 'projects',
									'paged' 		 	  	=> $page_number,
									'post_status' 	 	  	=> 'publish',
									'order'					=> 'ID',
									'orderby'				=> $order,
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} elseif( !empty($listing_type) && $listing_type === 'favorite' ){
				$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_saved_projects',true);
				$wishlist			= !empty($wishlist) ? $wishlist : array();
				if( !empty($wishlist) ) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'projects',
						'post__in'				=> $wishlist,
						'paged' 		 	  	=> $page_number,
						'post_status' 	 	  	=> 'publish',
						'order'					=> 'ID',
						'orderby'				=> $order,
						'ignore_sticky_posts' 	=> 1
					);
					$query 			= new WP_Query($query_args);
					$count_post 	= $query->found_posts;
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('You have no project in your favorite list.','workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
				
			}elseif( !empty($listing_type) && $listing_type === 'search' ){
				//Search parameters
				$keyword 		= !empty( $request['keyword']) ? $request['keyword'] : '';
				$languages 		= !empty( $request['language']) ? array($request['language']) : array();
				$categories 	= !empty( $request['category']) ? array($request['category']) : array();
				$locations 	 	= !empty( $request['location']) ? array($request['location']) : array();
				$skills			= !empty( $request['skills']) ? array($request['skills']) : array();
				$duration 		= !empty( $request['duration'] ) ? $request['duration'] : '';
				$type 			= !empty( $request['type'] ) ? array($request['type']) : array();
				$project_type	= !empty( $request['project_type'] ) ? $request['project_type'] : '';
				$english_level  = !empty( $request['english_level'] ) ? array($request['english_level']) : array();

				$minprice 		= !empty($request['minprice']) ? intval($request['minprice'] ): 0;
				$maxprice 		= !empty($request['maxprice']) ? intval($request['maxprice']) : '';
				
				$tax_query_args  = array();
				$meta_query_args = array();

				//Languages
				if ( !empty($languages[0]) && is_array($languages) ) {   
					$query_relation = array('relation' => 'OR',);
					$lang_args  	= array();

					foreach( $languages as $key => $lang ){
						$lang_args[] = array(
								'taxonomy' => 'languages',
								'field'    => 'slug',
								'terms'    => $lang,
							);
					}

					$tax_query_args[] = array_merge($query_relation, $lang_args);   
				}
				
				if ( !empty($categories) ) {    
					$query_relation = array('relation' => 'OR',);
					$category_args  = array();

					foreach( $categories as $key => $cat ){
						$category_args[] = array(
								'taxonomy' => 'project_cat',
								'field'    => 'slug',
								'terms'    => $cat,
							);
					}

					$tax_query_args[] = array_merge($query_relation, $category_args);
				}

				//Locations
				if ( !empty($locations[0]) && is_array($locations) ) {    
					$query_relation = array('relation' => 'OR',);
					$location_args  = array();

					foreach( $locations as $key => $loc ){
						$location_args[] = array(
								'taxonomy' => 'locations',
								'field'    => 'slug',
								'terms'    => $loc,
							);
					}

					$tax_query_args[] = array_merge($query_relation, $location_args);
				}

				//skills
				if ( !empty($skills[0]) && is_array($skills) ) {    
					$query_relation = array('relation' => 'OR',);
					$skills_args  = array();

					foreach( $skills as $key => $skill ){
						$skills_args[] = array(
								'taxonomy' => 'skills',
								'field'    => 'slug',
								'terms'    => $skill,
							);
					}
				}

				//Freelancer Skill Level
				if ( !empty( $type ) ) {    
					$meta_query_args[] = array(
						'key' 		=> '_freelancer_level',
						'value' 	=> $type,
						'compare' 	=> 'IN'
					);    
				}

				//Duration
				if ( !empty( $duration ) ) {    
					$duration_args[] = array(
						'key'		=> '_project_duration',
						'value' 	=> $duration,
						'compare' 	=> 'IN'
					);    

					$meta_query_args = array_merge($meta_query_args, $duration_args);
				}

				//Project Type
				if ( !empty( $project_type ) && $project_type === 'hourly' || $project_type === 'fixed' ) {    
					$project_args[] = array(
						'key' 			=> '_project_type',
						'value' 		=> $project_type,
						'compare' 		=> '='
					);  

					$meta_query_args = array_merge($meta_query_args, $project_args);
				}
				
				//Hourly Rate
				if( !empty( $project_type ) &&  $project_type === 'hourly' && !empty( $maxprice ) ) {
					$range_array 		= array($minprice, $maxprice);
					if( !empty( $range_array ) ) {
						$meta_query_args[] = array(
							'key'     => '_hourly_rate',
							'value'   => $range_array,
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);  
					}
				} else if( !empty( $project_type ) &&  $project_type === 'fixed' && !empty( $maxprice ) ) {
						$price_range 		= array($minprice, $maxprice);
						$meta_query_args[]  = array(
							'key' 			=> '_project_cost',
							'value' 		=> $price_range,
							'type'    		=> 'NUMERIC',
							'compare' 		=> 'BETWEEN'
						);
				} else if( empty( $project_type ) && !empty( $maxprice ) ) {
					$price_range 		= array($minprice, $maxprice);
					$query_relation = array('relation' => 'OR',);
					$price_args = array();
					$price_args[]  = array(
						'key' 			=> '_project_cost',
						'value' 		=> $price_range,
						'type'    		=> 'NUMERIC',
						'compare' 		=> 'BETWEEN'
					);

					$price_args[] = array(
							'key'     => '_hourly_rate',
							'value'   => $price_range,
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						); 

					$meta_query_args[] = array_merge($query_relation, $price_args);
				}

				//Main Query
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'projects',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => array('publish'),
					'ignore_sticky_posts' => 1
				);

				//keyword search
				if( !empty($keyword) ){
					$query_args['s']	=  $keyword;
				}

				//order by pro member
				$query_args['meta_key'] = '_featured_job_string';
				$query_args['orderby']	 = array( 
					'ID'      		=> 'DESC',
					'meta_value' 	=> 'DESC', 
				); 

				//Taxonomy Query
				if ( !empty( $tax_query_args ) ) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
				}

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation 			= array('relation' => 'AND',);
					$meta_query_args 			= array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] 	= $meta_query_args;
				}

				$query 			= new WP_Query($query_args); 
				$count_post 	= $query->found_posts;		
				
			} else {
				if(!empty($count_post) && $count_post ) {
					$json['type']		= 'error';
					$json['message']	= esc_html__('Please provide api type','workreap_api');
					return new WP_REST_Response($json, 203);
				} else {
					$json['type']		= 'error';
					$json['message']	= esc_html__('Please provide api type','workreap_api');
					return new WP_REST_Response($json, 203);
				}
			}

			//Start Query working.
			if ($query->have_posts()) {
				$duration_list 			= worktic_job_duration_list();
				if (function_exists('fw_get_db_settings_option')) {
					$featured_image		= fw_get_db_settings_option('featured_job_img');
					$featured_bg_color	= fw_get_db_settings_option('featured_job_bg');
					$tag		  		= !empty( $featured_image['url'] ) ? $featured_image['url'] : $defult;
					$color		  		= !empty( $featured_bg_color ) ? $featured_bg_color : '#f1c40f';
				} else {
					$color	= '';
					$tag	= '';
				}
				
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
					$project_id						= $post->ID;
					
					if( !empty($saved_projects)  &&  in_array($project_id,$saved_projects)) {
						$item['favorit']			= 'yes';
					} else {
						$item['favorit']			= '';
					}
					
					//Featured Jobs
					$featured_job	= get_post_meta($project_id,'_featured_job_string',true);
					if( !empty($featured_job) && !empty($color) && !empty($tag) ) {
						$item['featured_url']		= workreap_add_http($tag);
						$item['featured_color']		= $color;
					} else {
						$item['featured_url']		= '';
						$item['featured_color']		= '';
					}

					$item['location']		= workreap_get_location($project_id);
					$author_id				= get_the_author_meta( 'ID' );  
					$linked_profile			= workreap_get_linked_profile_id($author_id);
					$item['job_id']			= $project_id;
					$item['job_link']		= get_the_permalink($project_id);
					$is_verified			= get_post_meta($linked_profile,'_is_verified',true);
					$item['_is_verified'] 	= !empty($is_verified) ? $is_verified : '';
					$item['project_level']		= apply_filters('workreap_filter_project_level',$project_id);
					
					$job_option	= get_post_meta($project_id, '_job_option', true);
					$job_option	= !empty($job_option) ? workreap_get_job_option($job_option) : '';
					
					if (function_exists('fw_get_db_post_option')) {
						$project_type 		= fw_get_db_post_option($project_id, 'project_type', true);
						$project_duration   = fw_get_db_post_option($project_id, 'project_duration', true);
						$project_documents  = fw_get_db_post_option($project_id, 'project_documents', true);
						$db_project_type 	= fw_get_db_post_option($project_id, 'project_type', true);
						$expiry_date 		= fw_get_db_post_option($project_id, 'expiry_date', true);
						$deadline_date   	= fw_get_db_post_option($project_id, 'deadline', true);
						$project_cost 		= !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : '';
						$hourly_rate 		= !empty( $db_project_type['hourly']['hourly_rate'] ) ? $db_project_type['hourly']['hourly_rate'] : '';
						$estimated_hours	= !empty( $db_project_type['hourly']['estimated_hours'] ) ? $db_project_type['hourly']['estimated_hours'] : '';
						
					}

					$deadline_date	  = !empty($deadline_date) ? workreap_date_format_fix($deadline_date) : '';
					$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';

					$item['faq']		= array(); 
					if( !empty($job_faq_option) && $job_faq_option == 'faq' ){
						$faq 					= fw_get_db_post_option($project_id,'faq');
						$item['faq']			= !empty($faq) ? $faq : array();
					}
            		$item['project_type']   	= !empty( $project_type['gadget'] ) ? ucfirst($project_type['gadget']) : '';
            		$item['project_duration']	= !empty( $project_duration ) ? $duration_list[$project_duration] : '';
					$item['project_cost']		= !empty( $project_cost ) ? apply_filters('workreap_price_format',$project_cost,'return') : '';
					$item['hourly_rate']		= !empty( $hourly_rate ) ? apply_filters('workreap_price_format',$hourly_rate,'return') : '';
					$item['estimated_hours']	= !empty( $estimated_hours ) ? $estimated_hours : '';
					$item['expiry_date']		= !empty( $expiry_date ) ? $expiry_date : '';
					$item['deadline_date']		= !empty( $deadline_date ) ? $deadline_date : '';
					
					$docs						= array();
					if( !empty( $project_documents ) ){ 
						$docs_count	= 0;
						foreach ( $project_documents as $key => $value ) {
							$docs_count ++;
							$docs[$docs_count]['document_name']   	= !empty( get_the_title( $value['attachment_id'] ) ) ? get_the_title( $value['attachment_id'] ) : '';
							$docs[$docs_count]['file_size']			= !empty(filesize( get_attached_file( $value['attachment_id'] ) )) ? size_format(filesize( get_attached_file( $value['attachment_id']) ),2) : '';
							$docs[$docs_count]['filetype']        	= wp_check_filetype( $value['url'] );
							$docs[$docs_count]['extension']       	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
							$docs[$docs_count]['url']				= workreap_add_http($value['url']);
						}
					}
					$item['attanchents']	= array_values($docs);
					
					$terms 					= wp_get_post_terms( $project_id, 'skills');
					$skills					= array();
					if( !empty( $terms ) ){
						$sk_count	= 0;
						foreach ( $terms as $key => $term ) {
							$sk_count ++;
							$term_link 							= get_term_link( $term->term_id, 'skills' );	
							$skills[$sk_count]['skill_link']	= $term_link;
							$skills[$sk_count]['skill_name']	= $term->name;
						}
					}
					$item['skills']				= array_values($skills);
					
					$item['employer_name']		= get_the_title( $linked_profile );
					$item['employer_avatar']	= $avatar = apply_filters(
							'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
						);
					$item['project_title']		= get_the_title( $project_id );
					$item['project_content']	= get_the_content( $project_id );
					$item['count_totals']       = !empty($count_post) ? intval($count_post) : 0;
					$item['job_type']       	= $job_option;
					$items[]				    = maybe_unserialize($item);					
				}
				
				return new WP_REST_Response($items, 200);
				//end query
				
			}else{
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			} 
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
						$email_helper = new WorkreapMilestoneRequest();
						$emailData = array();

						$emailData['freelancer_name'] 	= esc_html( $hired_freelancer_title);
						$emailData['freelancer_link'] 	= esc_html( $freelancer_link);
						$emailData['employer_name'] 	= esc_html( $employer_name);
						$emailData['project_title'] 	= esc_html( $project_title);
						$emailData['project_link'] 		= esc_html( $project_link);
						$emailData['reason'] 			= esc_html( $cancelled_reason);

						$emailData['email_to'] 			= esc_html( $user_email);

						$email_helper->send_milestone_request_rejected_email($emailData);

					}
				}

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Settings Updated.', 'workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 200);
			}
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
					$milstone_due_date	= !empty($milstone_date) ? date($date_format, strtotime($milstone_date)) : '';
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

					if(!empty($updated_status)){
						if( ($updated_status === 'pay_now' || $updated_status === 'pending') && ( !empty($proposal_status) && $proposal_status === 'approved' && empty($order_id) )  ) {
							$status_text	= esc_html__( 'Pay Now', 'workreap_api' );
							$status_class	= 'green';
						} else if($updated_status === 'pending') {
							$status_text	= 'pending';
						} else if($updated_status === 'hired') {
							$status_text	= esc_html__( 'Hired', 'workreap_api' );
						} else if($updated_status === 'completed') {
							$status_text	= esc_html__( 'Completed', 'workreap_api' );
							$status_class	= '';
						}
					}

				$json['milestone_id']			= intval($post->ID);
				$json['milestone_price']		= $milstone_price;
				$json['milestone_title']		= $milstone_title;
				$json['milestone_due_date']		= $milstone_due_date;
				$json['updated_status']			= $updated_status;
				$json['status_class']			= $status_class;
				$json['order_id']				= $order_id;
				$json['milestone_content']		= $milstone_content;
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
			$remaning_price	= ($proposal_price) > ($total_milestone_price) ? $proposal_price - $total_milestone_price : 0;

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
         * Delete Employer job
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function delete_listing($request){
            $json				= array();
            $items				= array();
            $project_id			= !empty($request['project_id']) ? intval($request['project_id']) : '';
            $current_user_id	= !empty($request['current_user_id']) ? intval($request['current_user_id']) : '';
            $post_id	        = workreap_get_linked_profile_id($current_user_id);

            if( function_exists('workreap_is_demo_site') ) {
                workreap_is_demo_site() ;
            } //if demo site then prevent

            if(empty($project_id)){
                $json['type']       = 'error';
                $json['message']    = esc_html__('Project ID is required', 'workreap_api');
                $items[] 			= $json;
                return new WP_REST_Response($items, 203);
            }

            if(empty($current_user_id)){
                $json['type']           = 'error';
                $json['message']        = esc_html__('User ID is required', 'workreap_api');
                $items[] 			    = $json;
                return new WP_REST_Response($items, 203);
            }

            /* check current user is author/owner of this project */
            $post_author_id = get_post_field( 'post_author', $project_id );
            if($post_author_id != $current_user_id){
                $json['type']           = 'error';
                $json['message']        = esc_html__('You are not authorized', 'workreap_api');
                $items[] 			    = $json;
                return new WP_REST_Response($items, 203);
            }

            if( !empty($project_id) && !empty($current_user_id) ){
                $output = wp_delete_post($project_id,true);
                if($output != false){
                    //Send email to user on project delete
                    if (class_exists('Workreap_Email_helper')) {
                        if (class_exists('WorkreapJobPost')) {
                            $email_helper 		= new WorkreapJobPost();
                            $emailData 			= array();
                            $meta_query_args 	= array();

                            $query_args = array('posts_per_page' => -1,
                                'post_type' 		=> 'proposals',
                                'suppress_filters' 	=> false,
                            );

                            $meta_query_args[] = array(
                                'key' 			=> '_project_id',
                                'value' 		=> $project_id,
                                'compare' 		=> '='
                            );
                            $query_relation = array('relation' => 'AND',);
                            $query_args['meta_query'] = array_merge($query_relation, $meta_query_args);

                            $proposals = get_posts($query_args);
                            foreach( $proposals as $key => $proposal ){
                                $freelance_id			= get_post_field('post_author',$proposal->ID);

                                if(!empty($freelance_id)){
                                    $author_data    = get_userdata( $freelance_id );
                                    $email_to       = $author_data->data->user_email;
                                    $freelancer_post_id	= workreap_get_linked_profile_id($current_user_id);

                                    $emailData['email_to'] 			= esc_html( $email_to );
                                    $emailData['project_title'] 	= esc_html( get_the_title($project_id) );
                                    $emailData['employer_name'] 	= workreap_get_username( $current_user_id );
                                    $emailData['employer_link'] 	= esc_html( get_the_permalink($post_id) );
                                    $emailData['freelancer_name'] 	= workreap_get_username( $freelance_id );
                                    $emailData['freelancer_link'] 	= esc_html( get_the_permalink($freelancer_post_id) );

                                    $email_helper->send_delete_job_email($emailData);
                                    wp_delete_post($proposal->ID,true);
                                }
                            }
                        }
                    }
                    $json['type']		= 'success';
                    $json['message']	= esc_html__('Project deleted successfully!','workreap_api');
                    $items[] = $json;
                    return new WP_REST_Response($items, 200);
                } else {
                    $json['type']		= 'error';
                    $json['message']	= esc_html__('Project not delete, please try again later','workreap_api');
                    $items[] = $json;
                    return new WP_REST_Response($items, 203);
                }
            }else{
                $json['type']		= 'error';
                $json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
                $items[] = $json;
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
			
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone	= fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$project_id		= get_post_meta( $proposal_id, '_project_id', true );
			$total_price	= get_post_meta( $proposal_id, '_amount', true );
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
	$controller = new AndroidAppGetJobsRoutes;
	$controller->register_routes();
});
