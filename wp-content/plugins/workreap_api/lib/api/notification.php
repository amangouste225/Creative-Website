<?php
/**
 * APP API to manage chat
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_Notication')) {

    class AndroidApp_Notication extends WP_REST_Controller{

        /**
         * Register the routes for the chat.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'notification';

			register_rest_route($namespace, '/' . $base . '/get_notification',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'get_notification'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			register_rest_route($namespace, '/' . $base . '/view_notification',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'view_notification'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
        }
		
        /**
         * Get notification
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_notification($request) {
			$user_id		= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
            $limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$listing_type	= !empty( $request['listing_type'] ) ? esc_attr( $request['listing_type'] ) : 'listing';
            
			$notification_id    = !empty( $request['id'] ) ? intval( $request['id'] ) : 0;
			$offset 		= ($page_number - 1) * $limit;
			
			$json			= array();
			$items			= array();
			$today 			= time();
            if( !empty($listing_type) && $listing_type === 'listing' ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	  	=> $limit,
									'post_type' 	 	  	=> 'push_notifications',
									'paged' 		 	  	=> $page_number,
									'post_status' 	 	  	=> array('publish','pending','draft'),
									'order'					=> 'DESC',
                                    'author' 			    => $user_id,
									'orderby'				=> 'ID',
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} else if( !empty($listing_type) && $listing_type === 'single' ){
				$order		 	= 'DESC';
				$query_args 	= array(
									'posts_per_page' 	  	=> 1,
									'post_type' 	 	  	=> 'push_notifications',
									'post_status' 	 	  	=> array('publish','pending','draft'),
                                    'author' 			    => $user_id,
                                    'post__in' 		 	  	=> array($notification_id),
								);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			}
            if ($query->have_posts()) {
                while ($query->have_posts()) { 
                    $query->the_post();
                    global $post;
                    $post_status	= get_post_status($post->ID );
                    $date			= get_the_date( get_option( 'date_format' ), $post->ID );
                    $time			= get_post_time('U',false,$post->ID,true );
                    $human_time		= human_time_diff( $time, current_time('timestamp') );
                    $content		= apply_filters('workreap_push_notification_excerpt',$post->ID,false,'',true);

                    $item['post_status']    = !empty($post_status) ? esc_attr($post_status) : '';
                    $item['content']        = !empty($content) ? normalize_whitespace(wpautop($content)) : '';
                    $item['human_time']     = !empty($human_time) ? esc_html($human_time) : '';
                    $item['ID']             = !empty($post->ID) ? intval($post->ID) : '';
                    $item['date']           = !empty($date) ? esc_html($date) : '';
                    
					$items[]				= maybe_unserialize($item);	
                }
                
                return new WP_REST_Response($items, 200);
            } else{
                return new WP_REST_Response($items, 200);
            }
        }

        /**
         * View notification
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
        */
        public function view_notification($request) {
			$notify_id      = !empty( $request['notify_id'] ) ? intval( $request['notify_id'] ) : '';
            $notify_post = array(
                'ID'           => $notify_id,
                'post_status'  => 'publish',
            );
            wp_update_post( $notify_post );
            $json           = array();
            $json['type'] 	= "success";
			$json['message']= esc_html__("Successfuly view this notification", 'workreap_api');
            return new WP_REST_Response($json, 200);
        }
    }
}

add_action('rest_api_init',
        function () {
			$controller = new AndroidApp_Notication;
			$controller->register_routes();
});
