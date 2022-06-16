<?php
/**
 * APP API to upload media
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_uploadmedia')) {

    class AndroidApp_uploadmedia extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'media';

            register_rest_route($namespace, '/' . $base . '/upload_avatar',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array($this, 'upload_avatar'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			register_rest_route($namespace, '/' . $base . '/upload_banner',
                array(
                    array(
                        'methods' => WP_REST_Server::CREATABLE,
                        'callback' => array($this, 'upload_banner'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
        }

		
		/**
         * upload avatar from base64
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function upload_avatar($request){
			$user_id			= !empty( $request['id'] ) ? intval( $request['id'] ) : '';
			$profile_base64		= !empty( $request['profile_base64'] ) ?  $request['profile_base64'] : '';
			$json = array();
			//upload avatar
			if( !empty( $user_id ) && !empty($profile_base64) ){
				
				$profile_id	= workreap_get_linked_profile_id($user_id);
				$avatar_id = $this->upload_media($profile_base64);

				if( !empty($avatar_id) ){
					$thumnail_id	= get_post_thumbnail_id($profile_id);
					wp_delete_attachment($thumnail_id);
					set_post_thumbnail($profile_id,$avatar_id);
					update_post_meta($profile_id, '_have_avatar', 1);
					do_action('workreap_update_profile_strength','avatar',true);
					
					$json['type']       = 'success';
					$json['message']    = esc_html__('profile image updated', 'workreap_api');
					return new WP_REST_Response($json, 200); 
				} else {
					update_post_meta($post_id, '_have_avatar', 0);
					do_action('workreap_update_profile_strength','avatar',false);
					
					$json['type']		= 'error';
					$json['message']	= esc_html__('Some error occur, please try again later.','workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else{
				$json['type']		= 'error';
				$json['message']	= esc_html__('user id and image is required fields.','workreap_api');
				return new WP_REST_Response($json, 203);
			}
			
		}
		
		/**
         * upload banner from base64
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function upload_banner($request){
			$user_id		= !empty( $request['id'] ) ? intval( $request['id'] ) : '';
			$profile_base64	= !empty( $request['profile_base64'] ) ?  $request['profile_base64']  : '';
			$json 			= array();
			
			if( apply_filters('workreap_is_feature_allowed', 'wt_banner', $user_id) === true ){
				//upload avatar
				if( !empty( $profile_base64 ) && !empty( $user_id ) ){
					$profile_id	= workreap_get_linked_profile_id($user_id);
					$avatar_id	= AndroidApp_uploadmedia::upload_media($profile_base64);
					$avatar_id	= !empty($avatar_id) ? intval($avatar_id) : "";

					if( !empty($avatar_id) ) {

						$post_banner	= fw_get_db_post_option($profile_id, 'banner_image', $default_value = null);
						$thumnail_id	= !empty($post_banner['attachment_id']) ? intval($post_banner['attachment_id']) : "";

						if( !empty($thumnail_id) ) {
							wp_delete_attachment($thumnail_id);
						}

						$fw_options	= array(
										'attachment_id' => $avatar_id,
										'url'			=> wp_get_attachment_url($avatar_id)
									);
						fw_set_db_post_option($profile_id, 'banner_image', $fw_options);
						
						$json['type']       = 'success';
						$json['message']    = esc_html__('banner image updated', 'workreap_api');
						return new WP_REST_Response($json, 200); 
					}else {
						$json['type']		= 'error';
						$json['message']	= esc_html__('Some error occur, please try again later.','workreap_api');
						return new WP_REST_Response($json, 203);
					}
				} else{
					$json['type']		= 'error';
					$json['message']	= esc_html__('image and profile id is required.','workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('You have no permission to change banner picture.','workreap_api');
				return new WP_REST_Response($json, 203);
			}
		}
		
		/**
         * upload media from base64
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function upload_media($basestring){
			$upload_dir       = wp_upload_dir();
			$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
			$img 			  = $basestring['base64_string'];
			$decoded          = base64_decode( $img ) ;
			$filename         = $basestring['name'];
			$hashed_filename  = rand(1,9999) . '_' . $filename;
			// @new
			$image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

			//HANDLE UPLOADED FILE
			if( !function_exists( 'wp_handle_sideload' ) ) {
			  require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			// Without that I'm getting a debug error!?
			if( !function_exists( 'wp_get_current_user' ) ) {
			  require_once( ABSPATH . 'wp-includes/pluggable.php' );
			}

			// @new
			$file             = array();
			$file['error']    = '';
			$file['tmp_name'] = $upload_path . $hashed_filename;
			$file['name']     = $hashed_filename;
			$file['type']     = $basestring['type'];
			$file['size']     = filesize( $upload_path . $hashed_filename );

			// upload file to server
			$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );

			$filename 	= $file_return['file'];
			$attachment = array(
				 'post_mime_type' 	=> $file_return['type'],
				 'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename($filename)),
				 'post_content' 	=> '',
				 'post_status' 		=> 'inherit',
				 'guid' 			=> $wp_upload_dir['url'] . '/' . basename($filename)
			);
			
			$attach_id 		= wp_insert_attachment( $attachment, $filename, 0 );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data 	= wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			
			return $attach_id;
		}

    }
}

add_action('rest_api_init',
    function () {
        $controller = new AndroidApp_uploadmedia;
        $controller->register_routes();
    });
