<?php

/**
 *
 * Class used for the pusher endpoint ULR
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
	
if (!class_exists('Pusher_Endpoint')) {

    class Pusher_Endpoint extends WP_REST_Controller{

        /**
         * Register the routes for the user.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
			
			//add Proposal
            register_rest_route($namespace, '/pusher_endpoint_token',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'pusher_endpoint_token'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
			);
		}
		
		public function pusher_endpoint_token($request){
			if (function_exists('fw_get_db_settings_option')) {
				$enable_pusher		= fw_get_db_settings_option('enable_pusher');
				$instance_id	= fw_get_db_settings_option('pusher_instance_id');
				$secret_key		= fw_get_db_settings_option('pusher_secret_key');
			}
			
			if( !empty($instance_id) && !empty($secret_key) && $enable_pusher === 'yes'){
				$beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
				   "instanceId" 	=> $instance_id,
					"secretKey" 	=> $secret_key,
				));

				$user_id	= !empty($request['user_id']) ? 'private-user-'. intval($request['user_id']) : '';
				$beamsToken = $beamsClient->generateToken($user_id);
				return $beamsToken;
			}
			
			return;
		}
	}
	
	add_action('rest_api_init',
        function () {
		$controller = new Pusher_Endpoint;
		$controller->register_routes();
	});

}