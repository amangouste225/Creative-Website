<?php
if (!class_exists('AndroidAppGetFreelancersDashbord')) {

    class AndroidAppGetFreelancersDashbord extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'dashboard';

            register_rest_route($namespace, '/' . $base . '/get_my_proposals',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_my_proposals'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			// get earnings
			register_rest_route($namespace, '/' . $base . '/get_my_earnings',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_my_earnings'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//Download attachment
			register_rest_route($namespace, '/' . $base . '/get_attachments',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'download_attachments'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//cancelled jobs
			register_rest_route($namespace, '/' . $base . '/get_freelancer_cancelled_jobs',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_freelancer_cancelled_jobs'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//completed jobs
			register_rest_route($namespace, '/' . $base . '/get_completed_jobs',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_completed_jobs'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//manage proposals
			register_rest_route($namespace, '/' . $base . '/manage_proposals',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'manage_proposals'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//manage ongoing jobs
			register_rest_route($namespace, '/' . $base . '/get_ongoing_jobs',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_ongoing_jobs'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//manage ongoing job detail
			register_rest_route($namespace, '/' . $base . '/get_ongoing_job_detail',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_ongoing_job_detail'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			//manage ongoing job chat
			register_rest_route($namespace, '/' . $base . '/get_ongoing_job_chat',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_ongoing_job_chat'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/get_download_chat_attachments',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_download_chat_attachments'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
			
			register_rest_route($namespace, '/' . $base . '/get_services',
				array(
				array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_services'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
			
			register_rest_route($namespace, '/' . $base . '/get_services_by_type',
				array(
				array(
						'methods' 	=> WP_REST_Server::READABLE,
						'callback' 	=> array(&$this, 'get_services_by_type'),
						'args' 		=> array(),
						'permission_callback' => '__return_true',
					),
				)
			);
	
        }
		
		/**
         * Get Listings
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_my_earnings($request){
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 6;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$earnings		= workreap_get_earning_freelancer($user_id,$limit);
			
			$date_formate		= get_option('date_format');
			$items	= array();
			if( $earnings ){
				$earning_data	= array();
				foreach( $earnings as $earning ) {

					$earning_data['project_title']	= !empty($earning->project_id) ? esc_html( get_the_title($earning->project_id)) :"";
					$earning_data['amount']			= !empty($earning->freelancer_amount) ? workreap_price_format( $earning->freelancer_amount,'return') :0;
					$earning_data['timestamp']		= !empty($earning->process_date) ? date($date_formate ,strtotime($earning->process_date)) :'';
					$items[]	= $earning_data;
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
        public function get_services($request){
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 6;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';

			$type			= !empty( $request['type'] ) ? ( $request['type'] ) : '';
			$offset 		= ($page_number - 1) * $limit;

			$order 		= 'DESC';
			$sorting 	= 'ID';

			$args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'micro-services',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('publish','draft','pending'),
				'author' 			=> $user_id,
				'paged' 			=> $page_number,
				'suppress_filters'  => false
			);

			$query 			= new WP_Query($args);
			$items	= array();
			if( $query->have_posts() ){
				$service_data	= array();
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$service_data['queu_services']		= workreap_get_services_count('services-orders',array('hired'), $post->ID);
					$service_data['post_status']		= get_post_status($post->ID);
					$service_data['ID']					= $post->ID;
					
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   	= fw_get_db_post_option($post->ID,'docs');
					}
				
					$service_data['service_title']	= get_the_title($post->ID);
					$service_data['featured_img']	= get_the_post_thumbnail_url($post->ID,'workreap_service_thumnail');
					$is_featured					= apply_filters('workreap_service_print_featured',$post->ID,'yes');
					
					if(!empty($is_featured) && $is_featured === 'wt-featured'){
						$is_featured = 'yes';
					} else {
						$is_featured = 'no';
					}
				
					$db_price		= '';

					if (function_exists('fw_get_db_post_option')) {
						$db_price   = fw_get_db_post_option($post->ID,'price');
					}
				
					$service_data['is_featured']	= $is_featured;
					$service_data['price']			= workreap_price_format($db_price,'return');
					$service_downloadable			= get_post_meta( $post->ID, '_downloadable', true);
					$items[]	= $service_data;
				endwhile;
				wp_reset_postdata();

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
        public function get_services_by_type($request){
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
				'suppress_filters' 	=> false
			);

			$meta_query_args[] = array(
								'key' 		=> '_service_author',
								'value' 	=> $user_id,
								'compare' 	=> '='
							);
			$query_relation 	= array('relation' => 'AND',);
			$args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($args);
			$items	= array();
			if( $query->have_posts() ){
				$service_data	= array();
				while ($query->have_posts()) : $query->the_post();
					global $post;
					$service_id			= get_post_meta($post->ID,'_service_id',true);
					$employer_id		= get_post_field ('post_author', $post->ID);
					$service_addons		= get_post_meta( $service_id, '_addons', true);
					$service_downloadable	= get_post_meta( $service_id, '_downloadable', true);
				
					$db_price		= 0;
					$addon_total	= 0;

					if (function_exists('fw_get_db_post_option')) {
						$db_price   = fw_get_db_post_option($service_id,'price');
					}
				
					$addon_array		= array();
					if( !empty( $service_addons ) ){
						
						$service_addons_array	= array();
						foreach( $service_addons as $key => $addon ) { 
							$db_addon_price			= 0;

							if (function_exists('fw_get_db_post_option')) {
								$db_addon_price   = fw_get_db_post_option($addon,'price');
							}
							
							$addon_total	= $db_addon_price + $addon_total;
							
							$service_addons_array['title']	= get_the_title($addon);
							$service_addons_array['detail']	= get_the_excerpt($addon);
							$service_addons_array['price']	= html_entity_decode( workreap_price_format($db_addon_price,'return') );
							$addon_array[]					= $service_addons_array;
						}
					}
					
					$order_total						= $db_price + $addon_total;
					$service_price						= workreap_price_format($db_price,'return');
					$service_data['addons']				= $addon_array;
					$service_data['order_id']			= $post->ID;
					$profile_id							= workreap_get_linked_profile_id($employer_id);
					$service_data['employer']['employer_title']		= get_the_title($profile_id);
					$service_data['employer']['employertagline']	= workreap_get_tagline($profile_id);
					$service_data['employer']['employer_avatar'] 	= apply_filters(
											'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 65, 'height' => 65), $profile_id), array('width' => 65, 'height' => 65) 
										);
					$service_data['employer']['employer_verified'] 	= get_post_meta($profile_id, '_is_verified', true);
					
				
					$service_data['ID']					= $service_id;
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   	= fw_get_db_post_option($service_id,'docs');
					}
					
					$service_data['service_title']	= get_the_title($service_id);
					$service_data['service_downloadable']	= $service_downloadable;
					$service_data['featured_img']	= get_the_post_thumbnail_url($service_id,'workreap_service_thumnail');
					$is_featured					= apply_filters('workreap_service_print_featured',$service_id,'yes');
					
					if(!empty($is_featured) && $is_featured === 'wt-featured'){
						$is_featured = 'yes';
					} else {
						$is_featured = 'no';
					}

					$service_data['is_featured']		= $is_featured;
					$avg_rating		= array();
				
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
					$service_data['price']				= html_entity_decode( workreap_price_format($db_price,'return'));
					$service_data['order_total']		= html_entity_decode( workreap_price_format($order_total,'return') );
				
					$items[]	= $service_data;
				
				endwhile;
				wp_reset_postdata();

			}
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
		}
		/**
         * Get completed jobs
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_ongoing_jobs($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         = fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			
			$json		= array();
			
			$items		= array();
			$meta_query_args	= array();
			
			$order 			= 'DESC';
			$sorting 		= 'ID';
			
			
			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'projects',
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('hired'),
				'paged' 			=> $page_number,
				'suppress_filters' 	=> false
			);
			
			$post_id 		 = workreap_get_linked_profile_id($user_id);
			
			$meta_query_args[] = array(
						'key' 		=> '_freelancer_id',
						'value' 	=> $post_id,
						'compare' 	=> '='
					);
			
			$query_relation 	= array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($query_args);
			$count_post 		= $query->found_posts;

			if( $query->have_posts() ){
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
						$item		= array();
						$author_id 		= get_the_author_meta( 'ID' );  
						$linked_profile = workreap_get_linked_profile_id($author_id);
						$employer_title = esc_html( get_the_title( $linked_profile ));
						$milestone_option	= 'off';

						if( !empty($milestone) && $milestone ==='enable' ){
							$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
						}
					
						$proposal_id	= get_post_meta( $post->ID, '_proposal_id', true );

						$item['ID']	    	= $post->ID;
						$item['proposal_id']	  = intval($proposal_id);
						$item['title']		= get_the_title($post->ID);
						$item['milestone_option']	   = $milestone_option;


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
				wp_reset_postdata();
			}
			
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
        }
				
		/**
         * Get ongoing job detail
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_ongoing_job_detail($request){
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$project_id		= !empty( $request['project_id'] ) ? intval( $request['project_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         = fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			
			$json		= array();
			$items		= array();
			$item		= array();
			
			$author_id  		= get_post_field( 'post_author', $project_id );
			$linked_profile 	= workreap_get_linked_profile_id($author_id);
			$employer_title 	= esc_html( get_the_title( $linked_profile ));
			
			$proposal_id	= get_post_meta( $project_id, '_proposal_id', true );

			$item['ID']	    		= $project_id;
			$item['proposal_id']	= $proposal_id;
			$item['freelance_id']	= $user_id;
			$item['title']		= get_the_title($project_id);

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
				$project_level          = fw_get_db_post_option($project_id, 'project_level', true);                
			}

			$item['project_level']		= workreap_get_project_level($project_level);

			//Location
			$item['location_name']		= '';
			$item['location_flag']		= '';
			if( !empty( $project_id ) ){ 
				$args = array();
				if( taxonomy_exists('locations') ) {
					$terms = wp_get_post_terms( $project_id, 'locations', $args );
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

			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
        }
		
		
		/**
         * Get ongoing job chat history
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_download_chat_attachments($request){
			$attachment_id		= !empty( $request['comment_id'] ) ? intval( $request['comment_id'] ) : 10;

			$item		= array();
			$items		= array();
			$item['attachment'] = '';
			
			if( !empty( $attachment_id ) ){

				$project_files = get_comment_meta( $attachment_id, 'message_files', true);
				if( !empty( $project_files ) ){
					if( class_exists('ZipArchive') ){
						$zip = new ZipArchive();
						$uploadspath	= wp_upload_dir();
						$folderRalativePath = $uploadspath['baseurl']."/downloades";
						$folderAbsolutePath = $uploadspath['basedir']."/downloades";
						wp_mkdir_p($folderAbsolutePath);
						$filename	= round(microtime(true)).'.zip';
						$zip_name = $folderAbsolutePath.'/'.$filename; 
						$zip->open($zip_name,  ZipArchive::CREATE);
						$download_url	= $folderRalativePath.'/'.$filename;

						foreach($project_files as $key => $value) {	
							$file_url	= $value['url'];
							$response	= wp_remote_get( $file_url );
							$filedata   = wp_remote_retrieve_body( $response );
							$zip->addFromString(basename( $file_url ), $filedata);
						}
						$zip->close();

						$item['attachment'] = $download_url;
					}
				}
			}

			$items[]			    = maybe_unserialize($item);
			
			return new WP_REST_Response($items, 200);
		}
		
		/**
         * Get ongoing job chat history
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_ongoing_job_chat($request){
			$user_identity 	 	= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_identity);
			$edit_id			= !empty( $request['id'] ) ? intval( $request['id'] ) : 1;
			$post_type			= get_post_type($edit_id);

			$user_type		= apply_filters('workreap_get_user_type', $user_identity );

			if( !empty( $post_type ) && $post_type === 'services-orders') {
				$employeer_id				= get_post_field('post_author', $edit_id);
				$freelancer_id				= get_post_meta( $edit_id, '_service_author', true);

				$service_id					= get_post_meta( $edit_id, '_service_id', true);
				$hire_linked_profile		= workreap_get_linked_profile_id($freelancer_id); 
				$hired_freelancer_title 	= get_the_title( $hire_linked_profile );
				$title						= esc_html__('Service History', 'workreap_api');
				$post_status				= get_post_field('post_status',$edit_id);
				$post_comment_id			= $edit_id;

			} else if( !empty( $post_type ) && $post_type === 'proposals') {	
				$proposal_id		= $edit_id;
				$title				= esc_html__('Project History', 'workreap_api');
				$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
				$project_id			= get_post_meta( $proposal_id, '_project_id', true);
				$post_status		= get_post_field('post_status',$project_id);

			} else {
				$proposal_id		= get_post_meta($edit_id,'_proposal_id',true);
				$title				= esc_html__('Project History', 'workreap_api');
				$post_comment_id	= !empty($proposal_id) ? intval($proposal_id) : '';
				$post_status		= get_post_field('post_status',$edit_id);

			}
			
			$item		= array();
			$items		= array();
			
			$item['ID'] 			= '';
			$item['sender_image'] 	= '';
			$item['date_sent'] 		= '';
			$item['message'] 		= '';
			$item['ID'] 			= '';
			
			$args 				= array('post_id' => $post_comment_id);
			$comments 			= get_comments( $args );
			
			if( !empty( $post_comment_id ) ) {
				$counter = 0;
				foreach ($comments as $key => $value) { 
					$counter++;
					$date 			= !empty( $value->comment_date ) ? $value->comment_date : '';
					$user_id 		= !empty( $value->user_id ) ? $value->user_id : '';
					$comments_ID 	= !empty( $value->comment_ID ) ? $value->comment_ID : '';
					$message 		= $value->comment_content;
					$date 			= !empty( $date ) ? date('F j, Y', strtotime($date)) : '';

					if ( apply_filters('workreap_get_user_type', $user_id) === 'employer' ){
						$employer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $employer_post_id), array('width' => 100, 'height' => 100) 
						);
					} else {
						$freelancer_post_id   		= workreap_get_linked_profile_id($user_id);
						$avatar = apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $freelancer_post_id), array('width' => 100, 'height' => 100) 
						);
					}  

					$username 		= workreap_get_username( $user_id );		
					$project_files  = get_comment_meta( $value->comment_ID, 'message_files', true);
					
					$item['ID'] 			= $comments_ID;
					$item['sender_image'] 	= $avatar;
					$item['date_sent'] 		= $date;
					$item['message'] 		= $message;
					$item['ID'] 			= $comments_ID;
					
					$items[]			    = $item;
				}
				
			}
			
			
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
		}
		
		/**
         * Get completed jobs
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_completed_jobs($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$milestone	= array();
			if (function_exists('fw_get_db_settings_option')) {
				$milestone         = fw_get_db_settings_option('job_milestone_option', $default_value = null);
			}
			
			$milestone			= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
			
			$json		= array();
			
			$items		= array();
			$meta_query_args	= array();
			
			$order 			= 'DESC';
			$sorting 		= 'ID';
			
			
			$query_args = array(
				'posts_per_page' => $limit,
				'post_type' 	 => 'projects',
				'orderby' 		 => $sorting,
				'order' 		 => $order,
				'post_status' 	 => array('completed'),
				'paged'			 => $page_number,
				'suppress_filters' => false
			);
			
			$post_id 		 = workreap_get_linked_profile_id($user_id);
			
			$meta_query_args[] = array(
						'key' 		=> '_freelancer_id',
						'value' 	=> $post_id,
						'compare' 	=> '='
					);
			
			$query_relation 	= array('relation' => 'AND',);
			$query_args['meta_query'] = array_merge($query_relation, $meta_query_args);
			$query 				= new WP_Query($query_args);
			$count_post 		= $query->found_posts;

			if( $query->have_posts() ){
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
						$item					= array();
						$author_id 				= get_the_author_meta( 'ID' );  
						$linked_profile 		= workreap_get_linked_profile_id($author_id);
						$employer_title 		= esc_html( get_the_title( $linked_profile ));
						$milestone_option		= 'off';
						$project_type    		= fw_get_db_post_option($post->ID,'project_type');
						$item['project_type']	= isset( $project_type ) && $project_type == 'hourly' ?  esc_html__('Hourly', 'workreap-api') : esc_html__('Fixed Price', 'workreap-api');
						
						if( !empty($milestone) && $milestone ==='enable' ){
							$milestone_option	= get_post_meta( $post->ID, '_milestone', true );
						}

						$project_duration_value   	= '';
						$project_duration   		= fw_get_db_post_option($post->ID, 'project_duration', true);
						$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
						
						if(!empty($remove_project_duration) && $remove_project_duration === 'no' ){ 
							$duration_list 			= worktic_job_duration_list();
							$project_duration_value = !empty( $duration_list[$project_duration] ) ? $duration_list[$project_duration] : '';
						}

						$item['project_duration']	= $project_duration_value;
						$proposal_docs 				= fw_get_db_post_option($post->ID, 'project_documents');
						$attachments				= array();
						
						if( !empty($proposal_docs) ){
							foreach($proposal_docs as $file) {
								$attachments[]		= !empty($file['url']) ? esc_url($file['url']) : '';
							}
						}

						$item['attachments']		= $attachments;
						$proposal_id				= get_post_meta( $post->ID, '_proposal_id', true );
						$item['ID']	    			= $post->ID;
						$item['proposal_id']		= $proposal_id;
						$item['freelance_id']		= $user_id;
						$item['title']				= get_the_title($post->ID);
						$item['milestone_option']	= $milestone_option;
						$is_verified 				= get_post_meta($linked_profile, '_is_verified', true);
						$title						= $employer_title;
						
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
				
				wp_reset_postdata();
			}
					
			
			$items			    = maybe_unserialize($items);
			
			return new WP_REST_Response($items, 200);
			
        }
		
		/**
         * Get cancelled jobs
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_freelancer_cancelled_jobs($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$json		= array();
			$item		= array();
			$items		= array();
			$proposals	= array();
			
			$order 			= 'DESC';
			$sorting 		= 'ID';
			
			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'proposals',
				'author' 			=> $user_id,
				'orderby' 			=> $sorting,
				'order' 			=> $order,
				'post_status' 		=> array('cancelled'),
				'paged' 			=> $page_number,
				'suppress_filters' 	=> false
			);

			$pquery = new WP_Query($query_args);
			$count_post = $pquery->found_posts;

			if( $pquery->have_posts() ){
				while ($pquery->have_posts()) { 
					$pquery->the_post();
					global $post;

					$item		= array();
					$project_id 	= get_post_meta( $post->ID, '_project_id', true);
					$author_id 		= get_post_field('post_author',$project_id);
					$linked_profile = workreap_get_linked_profile_id( $author_id );
					$employer_title = esc_html( get_the_title( $linked_profile ));
					
					
					
					$milestone_option	= 'off';

					if( !empty($milestone) && $milestone ==='enable' ){
						$milestone_option	= get_post_meta( $project_id, '_milestone', true );
					}
				
					$proposal_id	= get_post_meta( $project_id, '_proposal_id', true );

					$item['ID']	    		= $project_id;
					$item['proposal_id']	= $post->ID;
					$item['freelance_id']	= $user_id;
					$item['title']			= get_the_title($project_id);
					$item['milestone_option']	   = $milestone_option;


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
						$project_level          = fw_get_db_post_option($project_id, 'project_level', true);                
					}

					$item['project_level']		= workreap_get_project_level($project_level);

					//Location
					$item['location_name']		= '';
					$item['location_flag']		= '';
					if( !empty( $project_id ) ){ 
						$args = array();
						if( taxonomy_exists('locations') ) {
							$terms = wp_get_post_terms( $project_id, 'locations', $args );
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
				wp_reset_postdata();
			}
			return new WP_REST_Response($items, 200);
        }
		
        /**
         * Get my proposals
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_my_proposals($request){
			$limit			= !empty( $request['limit'] ) ? intval( $request['limit'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$json		= array();
			$items		= array();
			$proposals	= array();
			
			$query_args = array(
				'posts_per_page' 	=> $limit,
				'post_type' 		=> 'proposals',
				'orderby' 			=> "ID",
				'order' 			=> 'DESC',
				'post_status' 		=> array('publish'),
				'author' 			=> $user_id,
				'paged' 			=> $page_number,
				'suppress_filters'  => false
			);

			$pquery = new WP_Query($query_args);
			$count_post = $pquery->found_posts;

			if( $pquery->have_posts() ){
				while ($pquery->have_posts()) { 
				$pquery->the_post();
				global $post;
					$item		= array();
					$author_id 			= get_the_author_meta( 'ID' );  
					$project_id			= get_post_meta($post->ID,'_project_id', true);
					$_proposal_id 		= get_post_meta($project_id, '_proposal_id', true);
					$job_status			= '';

					$proposal_hiring_status	= get_post_meta($post->ID,'_proposal_status',true);
					$proposal_hiring_status	= !empty($proposal_hiring_status) ? $proposal_hiring_status : '';
					$project_status			= get_post_status($project_id);
					
					$project_type    	= fw_get_db_post_option($project_id,'project_type');
					$proposed_amount  	= get_post_meta($post->ID, '_amount', true);
					$total_amount		= '';
					
					if( !empty($_proposal_id) && ( intval($_proposal_id) === $post->ID ) ) {
						$job_status		= get_post_field('post_status',$project_id);
					} else if(!empty($_proposal_id)){
						$job_status		= 'cancelled';
					}else{
						$job_status		= 'pending';
					}

					$linked_profile 	= workreap_get_linked_profile_id($author_id);

					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs 	= fw_get_db_post_option($post->ID, 'proposal_docs', true);
						$allow_proposal_edit    = fw_get_db_settings_option('allow_proposal_edit');
					} else {
						$proposal_docs	= '';
						$allow_proposal_edit	= '';
					}

					$proposal_docs = !empty( $proposal_docs ) && is_array( $proposal_docs ) ?  count( $proposal_docs ) : 0;

					$freelancer_avatar = apply_filters(
							'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 225, 'height' => 225 ), $linked_profile ), array( 'width' => 225, 'height' => 225 )
						);

					$pargs	 = array( 'project_id' => $project_id, 'proposal_id' => $post->ID );
					$submit_proposal  = !empty( $submit_proposal ) ? add_query_arg( $pargs, $submit_proposal ) : '';

					$item['ID']	    	= $post->ID;
					$item['title']		= get_the_title($post->ID);
					$item['job_title']		= get_the_title($project_id);
					$item['proposal_edit']	    	= 'no';
					$item['proposal_milestone']	    = 'no';
					$item['budget']	    = workreap_price_format($proposed_amount,'return');
					
					$item['duration']	    = '';
					$item['per_hour_price']	    = '';

					if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'fixed' ) { 
						$proposed_duration  = get_post_meta($post->ID, '_proposed_duration', true);
						$duration_list		= worktic_job_duration_list();
						$duration			= !empty( $duration_list[$proposed_duration] ) ? $duration_list[$proposed_duration] : '';
						$item['duration']	    = $duration;
						$item['job_type']	    = 'fixed';
					}
					
					if( !empty( $project_type['gadget'] ) && $project_type['gadget'] === 'hourly' ) { 
						$estimeted_time		= get_post_meta($post->ID,'_estimeted_time',true);
						$per_hour_amount	= get_post_meta($post->ID,'_per_hour_amount',true);
						$estimeted_time		= !empty( $estimeted_time ) ? $estimeted_time : 0;
						$per_hour_amount	= !empty( $per_hour_amount ) ? $per_hour_amount : 0;
						$total_amount		= apply_filters('workreap_price_format',$per_hour_amount,'return');

						$item['duration']	    	= $total_amount;
						$item['per_hour_price']	    = $per_hour_amount;
						$item['budget']	    		= $total_amount;
						$item['job_type']	    	= 'hourly';
					} 
					
					//cover
					$item['cover']	    	= '';
					if( !empty($post->ID) ){
						$contents			= nl2br( stripslashes( get_the_content('',true,$post->ID) ) );
						$item['cover']	    = $contents;
					}
					
					if (function_exists('fw_get_db_post_option')) {
						$proposal_docs = fw_get_db_post_option($post->ID, 'proposal_docs');
					}

					$proposal_docs = !empty( $proposal_docs ) ?  count( $proposal_docs ) : 0;
					$item['proposal_documents']	    = $proposal_docs;
					
					if( $job_status === 'hired' ) {
						$item['status']	    = esc_html__('Hired','workreap_api');
						$item['status_key']	    = 'hired';
					}elseif( $job_status === 'completed' ) {
						$item['status']	    = esc_html__('Completed','workreap_api');
						$item['status_key']	    = 'completed';
					} else if( $job_status !== 'hired' ) {
						$item['status']	    = esc_html__('Pending','workreap_api');
						$item['status_key']	    = 'pending';
						if( !empty($allow_proposal_edit) && $allow_proposal_edit == 'yes' ){
							$item['proposal_edit']	    = 'yes';
						}
						if( !empty($proposal_hiring_status)  && $proposal_hiring_status === 'pending' && $project_status === 'publish' ) {
							$item['proposal_milestone']	    = 'yes';
						}
					}

					$proposals[]	= $item;
				
				}
				
				wp_reset_postdata();

			}

			$items			    = maybe_unserialize($proposals);
			
			return new WP_REST_Response($items, 200);
			
        }
		
		/**
         * Get proposal attachment
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function download_attachments($request){
			$job_id			= !empty( $request['id'] ) ? intval( $request['id'] ) : 0;
			$type			= !empty( $request['type'] ) ? $request['type'] : 0;

			$item		= array();
			$items		= array();
			$item['attachment'] = '';
			
			if( !empty($job_id) ){
				if (function_exists('fw_get_db_post_option')) {
					if(!empty($type) && $type === 'project'){
						$proposal_docs 			= fw_get_db_post_option($job_id, 'project_documents');
					}else{
						$proposal_docs 			= fw_get_db_post_option($job_id, 'proposal_docs');
					}
					
					if( !empty($proposal_docs) ) {
						$zip = new ZipArchive();
						$uploadspath			= wp_upload_dir();
						$folderRalativePath 	= $uploadspath['baseurl']."/downloades";
						$folderAbsolutePath 	= $uploadspath['basedir']."/downloades";
						wp_mkdir_p($folderAbsolutePath);
						
						$filename				= round(microtime(true)).'.zip';
						$zip_name 				= $folderAbsolutePath.'/'.$filename; 
						$zip->open($zip_name,  ZipArchive::CREATE);
						$download_url			= $folderRalativePath.'/'.$filename; 

						foreach($proposal_docs as $file) {
							$response			= wp_remote_get($file['url']);
							$filedata   		= wp_remote_retrieve_body( $response );
							$zip->addFromString(basename($file['url']), $filedata);
						}
						$zip->close();
						
						
						$item['attachment'] = $download_url;
					}
				}
			}
			
			$items[]			    = maybe_unserialize($item);
			
			return new WP_REST_Response($items, 200);
			
		}
		
		
    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetFreelancersDashbord;
	$controller->register_routes();
});
