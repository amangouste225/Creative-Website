<?php
if (!class_exists('AndroidAppGetPaymentsRoutes')) {

    class AndroidAppGetPaymentsRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'payment';
			register_rest_route($namespace, '/' . $base . '/add_payment',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::CREATABLE,
                        'callback' 	=> array(&$this, 'add_payment'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			
			// For package info
			register_rest_route($namespace, '/' . $base . '/available_payment_methods',
                array(
                  array(
                        'methods' 	=> WP_REST_Server::READABLE,
                        'callback' 	=> array(&$this, 'available_payment_methods'),
                        'args' 		=> array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
        }
		
		/**
		* Get Active payment methods
		*
		* @param WP_REST_Request $request Full data about the request.
		* @return WP_Error|WP_REST_Request
		*/
		
        public function available_payment_methods() {
			$json  			= array();
			$active_array	= array();
			if ( class_exists('WooCommerce') ) {
				$available_payment_methods = WC()->payment_gateways->get_available_payment_gateways();

				if ( $available_payment_methods ){

					foreach($available_payment_methods as $key => $values){
						$values	= get_object_vars($values); 
						$active_array['payments'][$key]['title']		= $values['title'];
						$active_array['payments'][$key]['description']	= $values['description'];
					}	

				}
				$json						= $active_array;
				$json['type'] 				= 'success';
				return new WP_REST_Response($json, 200);
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please active Woocommerce plugin.','workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}
		}
		
		/**
         * Add Service
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function add_payment($request) {
			$token_id		= !empty( $request['token_id'] ) ? (  $request['token_id'] ) : '';
			$amount			= !empty( $request['amount'] ) ? (  $request['amount'] ) : '';
			
			$json				= array();
			$items				= array();
			$service_files		= array();
			$submitted_files	= array();
			$publishable_key	= '';
			$secret_key			= '';
			$price_symbol		= 	workreap_get_current_currency();
			
			if( class_exists( 'WorkreapGlobalSettings' ) ) {		 
				require_once( WorkreapGlobalSettings::get_plugin_path().'/libraries/stripe/init.php');
			 } else{
				$json['type']     = 'error';
				$json['message']  = esc_html__('Stripe API not found.','workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			 }
			if ( class_exists('WooCommerce') ) {
				$available_payment_methods = WC()->payment_gateways->get_available_payment_gateways();
				if( !empty( $available_payment_methods['stripe'] ) ){
					$stripe_infor	= $available_payment_methods['stripe'];
					if( !empty( $stripe_infor->secret_key ) && !empty( $stripe_infor->publishable_key  )) {
						$secret_key			= $stripe_infor->secret_key;
						$publishable_key	= $stripe_infor->publishable_key;
					} else {
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Please set stripe Payment method keys in Woocommerce plugin.','workreap_api');
						$items[] 			= $json;
						return new WP_REST_Response($items, 203);
					}
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Please active stripe Payment method in Woocommerce plugin.','workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please active Woocommerce plugin.','workreap_api');
				$items[] 			= $json;
				return new WP_REST_Response($items, 203);
			}
			
			if( !empty( $token_id ) && !empty( $secret_key ) && !empty( $publishable_key ) ) {
				$stripe = array(
					"secret_key"      => $secret_key,
					"publishable_key" => $publishable_key
				  );

				  \Stripe\Stripe::setApiKey($stripe['secret_key']);
				  $charge = \Stripe\Charge::create(array(
					'amount'   		=> $amount,
					'currency' 		=> $price_symbol['code'],
					'source'  		=> $token_id,
					'description' 	=> 'test des',
				  ));
				if ($charge->status == 'succeeded') {
					$json['status']	= $charge->status;
					if( !empty( $charge->source->id ) ){
						$transaction_id	= $charge->source->id;
					} else{
						$transaction_id	= docdirect_unique_increment(10);
					}
					$items[] 		= $json;
				
					return new WP_REST_Response($items, 200);
				}
			}
			
		}
    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetPaymentsRoutes;
	$controller->register_routes();
});
