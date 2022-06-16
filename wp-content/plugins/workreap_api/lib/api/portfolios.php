<?php
if (!class_exists('AndroidAppGetPortfoliosRoutes')) {

    class AndroidAppGetPortfoliosRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'portfolios';

            register_rest_route($namespace, '/' . $base . '/get_portfolios',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_portfolios'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			
			register_rest_route($namespace, '/' . $base . '/update_portfolio',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'update_portfolio'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
						
			register_rest_route($namespace, '/' . $base . '/delete_portfolio',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'delete_portfolio'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );

			register_rest_route($namespace, '/' . $base . '/update_post_status',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'update_post_status'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );	
        }
		
		/**
         * Update protfolio status
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function update_post_status($request) {
			$user_id	= !empty($request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$json		= array();		
			if ( empty( $user_id ) ) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'You must login before changing this portfolio status.', 'workreap_api' );
				return new WP_REST_Response($json, 203);
			}
			
			$required = array(
				'id'   			=> esc_html__('Portfolio ID is required', 'workreap_api'),
				'status'  		=> esc_html__('Portfolio status is required', 'workreap_api')
			);
			
			foreach ($required as $key => $value) {
				if( empty( $request[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					return new WP_REST_Response($json, 203);
				}
			}
			
			$post_id 			= !empty( $request['id'] ) ? esc_attr( $request['id'] ) : '';
			$status				= !empty( $request['status'] ) ? esc_attr( $request['status'] ) : '';
			
			$update_post			= array();
			$update					= workreap_save_service_status($post_id,$status);
			if( $update ) {
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Successfully! update portfolio status', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Portfolio status is not updated.', 'workreap_api' );
				return new WP_REST_Response($json, 203);
			}
			
		}

		/**
         * Add/Update portfolio
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function update_portfolio($request) {

			$user_id			= !empty($request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$linked_profile  	= workreap_get_linked_profile_id($user_id);
			do_action('workreap_check_post_author_status', $linked_profile);
			do_action('workreap_check_post_author_identity_status', $linked_profile);
			
			$ppt_option		= '';
			$total_limit	= '';

			$json			= array();
			$params_array	= array();

			if( function_exists('fw_get_db_settings_option') ){
				$ppt_option		= fw_get_db_settings_option('ppt_template');
				$total_limit	= fw_get_db_settings_option('default_portfolio_images');
			}
			
			$total_limit	= !empty($total_limit) ? intval($total_limit) : 100;
			$required 		= array(
								'title'	=> esc_html__('Portfolio title is required', 'workreap_api')
							);

			$required		= apply_filters('workreap_filter_portfolio_required_fields', $required);
			
			foreach ($required as $key => $value) {
				if( empty( $required[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $value;        
					return new WP_REST_Response($json, 203);
				}
			}

			if( empty($_FILES['gallery_imgs0']) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('At-least one portfolio image is required', 'workreap_api');        
				return new WP_REST_Response($json, 203);
			}

			if( function_exists('workreap_check_video_url') ){
				if( !empty($request['videos']) ){
					foreach( $request['videos'] as $video_url ){
						$check_video = workreap_check_video_url($video_url);
						if( empty($check_video) || $check_video === false ){
							$json['type'] 		= 'error';
							$json['message'] 	= esc_html__('Please add valid video URL','workreap_api');        
							return new WP_REST_Response($json, 203);
						}
					}
				}
			}

			$title			= !empty( $request['title'] ) ? $request['title'] : '';
			$description	= !empty( $request['description'] ) ?  $request['description'] : '';
			$categories		= !empty( $request['categories'] ) ?  $request['categories'] : array();
			$videos			= !empty( $request['videos'] ) ? $request['videos'] : array();

			if( isset( $request['submit_type'] ) && $request['submit_type'] === 'update' ){
				$current 	= !empty($request['id']) ? esc_attr($request['id']) : '';
				
				$post_author = get_post_field('post_author', $current);
				$post_id 	 = $current;
				$status 	 = get_post_status($post_id);
				
				if( intval( $post_author ) === intval( $user_id ) ){
					$portfolio_post = array(
						'ID' 			=> $current,
						'post_title' 	=> $title,
						'post_content' 	=> $description,
						'post_status' 	=> $status,
					);

					wp_update_post($portfolio_post);
				} else{
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occur, please try again later', 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
				
			} else {
				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_status'   => 'publish',
					'post_content'  => $description,
					'post_author'   => $user_id,
					'post_type'     => 'wt_portfolio',
				);

				$post_id    		= wp_insert_post( $user_post );

				//Prepare Params
				$params_array['user_role'] 		= apply_filters('workreap_get_user_type', $user_id );
				$params_array['type'] 			= 'portfolio_upload';
				$params_array['user_identity'] 	= $user_id;
				//child theme : update extra settings
				do_action('wt_process_portfolio_upload', $params_array);
			}
					
			if( $post_id ){
				if( !empty($ppt_option) && $ppt_option === 'enable' ){
					$ppt_template		= !empty($request['ppt_template']) ? $request['ppt_template'] : '';
					update_post_meta( $post_id, 'ppt_template', $ppt_template );
				}

				$gallery_imgs		= array();
				$documents			= array();
				$zip_files			= array();

				$total_gallery 		= !empty($request['gallery_size']) ? $request['gallery_size'] : 0;
				if( !empty( $_FILES ) && $total_gallery != 0 ){
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					
					$counter	= 0;
					for ($x = 0; $x < $total_gallery; $x++) {
						$submitted_files = $_FILES['gallery_imgs'.$x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$gallery_imgs[]					= $attachments;
					}
				}
				
				if( !empty( $gallery_imgs [0]['attachment_id'] ) ){
					set_post_thumbnail( $post_id, $gallery_imgs[0]['attachment_id']);
				}

				$total_documents 		= !empty($request['documents_size']) ? $request['documents_size'] : 0;
				if( !empty( $_FILES ) && $total_documents != 0 ){
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					
					$counter	= 0;
					for ($x = 0; $x < $total_documents; $x++) {
						$submitted_files = $_FILES['documents'.$x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$documents[]					= $attachments;
					}
				}

				$total_zip_files 		= !empty($request['zip_files_size']) ? $request['zip_files_size'] : 0;
				if( !empty( $_FILES ) && $total_zip_files != 0 ){
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						require_once( ABSPATH . 'wp-includes/pluggable.php' );
					}
					
					$counter	= 0;
					for ($x = 0; $x < $total_zip_files; $x++) {
						$submitted_files = $_FILES['zip_files'.$x];
						$uploaded_image  = wp_handle_upload($submitted_files, array('test_form' => false));
						$file_name		 = basename($submitted_files['name']);
						$file_type 		 = wp_check_filetype($uploaded_image['file']);

						// Prepare an array of post data for the attachment.
						$attachment_details = array(
							'guid' 				=> $uploaded_image['url'],
							'post_mime_type' 	=> $file_type['type'],
							'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($file_name)),
							'post_content' 		=> '',
							'post_status' 		=> 'inherit'
						);

						$attach_id 		= wp_insert_attachment($attachment_details, $uploaded_image['file']);
						$attach_data 	= wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$attachments['attachment_id']	= $attach_id;
						$attachments['url']				= wp_get_attachment_url($attach_id);
						$zip_files[]					= $attachments;
					}
				}

				$custom_link	= !empty( $request['custom_link'] ) ? $request['custom_link'] : '';

				if( !empty( $categories ) ){
					wp_set_post_terms( $post_id, $categories, 'portfolio_categories' );
				}

				if( !empty( $request['tags'] ) ) {
					wp_set_post_terms( $post_id, $request['tags'], 'portfolio_tags' );
				}

				//update unyson meta
				$fw_options 					= array();
				$fw_options['custom_link']  	= $custom_link;
				$fw_options['gallery_imgs']    	= $gallery_imgs;
				$fw_options['documents']    	= $documents;
				$fw_options['zip_attachments']  = $zip_files;
				$fw_options['videos']    		= $videos;

				fw_set_db_post_option($post_id, null, $fw_options);
				

				if( isset( $request['submit_type'] ) && $request['submit_type'] === 'update' ){
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your portfolio has been updated', 'workreap_api');
				} else{
					$json['type'] 		= 'success';
					$json['message'] 	= esc_html__('Your portfolio has been added.', 'workreap_api');
				}

				return new WP_REST_Response($json, 200);
			} else{
				$json['type'] = 'error';
				$json['message'] = esc_html__('Some error occur, please try again later', 'workreap_api');
				return new WP_REST_Response($json, 200);
			}
			
		}
				
		/**
         * Delete service
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function delete_portfolio($request){
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$portfolio_id	= !empty( $request['id'] ) ?  $request['id'] : '';
			$items			= array();
			$itm			= array();
			
			if(empty($portfolio_id)){
				
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Portfolio ID is required', 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			$post_author	= get_post_field('post_author',$portfolio_id);

			if( !empty($post_author) && $post_author != $user_id){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__("You're not allowed to remove this portfolio", 'workreap_api');
				return new WP_REST_Response($json, 203);
			}
			if( !empty( $portfolio_id ) ){
				wp_delete_post($portfolio_id);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Portfolio removed successfully.', 'workreap_api');
				return new WP_REST_Response($json, 200);
			} 
			
		}
		
        /**
         * Get Listings
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_portfolios($request){
			
			$portfolio_id	= !empty( $request['portfolio_id'] ) ? intval( $request['portfolio_id'] ) : '';
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
			$listing_type	= !empty( $request['listing_type'] ) ? esc_attr( $request['listing_type'] ) : '';
			$json			= array();
			$items			= array();
			$count_post			= 0;
			$json['type']		= 'error';
			$json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
			if( $listing_type === 'single' ){
				
				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'wt_portfolio',
					'post__in' 		 	  	=> array($portfolio_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif( !empty($listing_type) && $listing_type === 'latest' ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	  	=> -1,
									'post_type' 	 	  	=> 'wt_portfolio',
									'post_status' 	 	  	=> 'publish',
									'author'				=> $user_id
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} elseif( !empty($listing_type) && $listing_type === 'listing' ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	  	=> -1,
									'post_type' 	 	  	=> 'wt_portfolio',
									'post_status' 	 	  	=> array('draft','publish'),
									'author'				=> $user_id
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} 
			
			//Start Query working.
			
			if ($query->have_posts()) {
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
					
					do_action('workreap_post_views', $post->ID,'portfolio_views');
					
					$gallery_imgs			= array();
					$documents				= array();
					$db_videos				= array();
					$custom_link			= '';
					$zip_attachments		= array();
					if (function_exists('fw_get_db_post_option')) {
						$gallery_imgs   	= fw_get_db_post_option($post->ID, 'gallery_imgs');
						$zip_attachments   	= fw_get_db_post_option($post->ID, 'zip_attachments');
						$documents   		= fw_get_db_post_option($post->ID, 'documents');
						$db_videos   		= fw_get_db_post_option($post->ID,'videos');
						$custom_link   		= fw_get_db_post_option($post->ID,'custom_link');
					}
					$item['post_author']	= get_post_field( 'post_author', $post->ID ) ;
					$item['title']			= get_the_title($post->ID);
					$item['ID']				= $post->ID;
					$item['status']			= get_post_status($post->ID);
					$item['documents']		= $documents;
					$item['db_videos']		= $db_videos;
					$item['custom_link']	= $custom_link;
					$item['gallery_imgs']	= $gallery_imgs;
					$item['zip_attachments']= $zip_attachments;

					$items[]				= maybe_unserialize($item);					
				}
				return new WP_REST_Response($items, 200);
				//end query
				
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			} 
        }

    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetPortfoliosRoutes;
	$controller->register_routes();
});
