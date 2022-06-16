<?php
/**
 * APP API to manage taxonomies
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Workreap APP
 *
 */
if (!class_exists('AndroidApp_Taxonomies_Route')) {

    class AndroidApp_Taxonomies_Route extends WP_REST_Controller{

        /**
         * Register the routes for the user.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'taxonomies';
			
			//get taxonomies
			register_rest_route($namespace, '/' . $base . '/get_taxonomies',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_taxonomies'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    )
                )
            );
			//get pakcage information
			register_rest_route($namespace, '/' . $base . '/get_pakckage_details',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_remaining_offers'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    )
                )
            );
			//get taxnomy
			register_rest_route($namespace, '/' . $base . '/get_taxonomy',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_taxonomy'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
			//get list
			register_rest_route($namespace, '/' . $base . '/get_list',
                array(
                    array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_list'),
                        'args' => array(),
						'permission_callback' => '__return_true',
                    ),
                )
            );
        }
		
		/**
         * Get user package informations
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function get_remaining_offers($request) {
			$user_id			= !empty( $request['user_id'] ) ?  $request['user_id'] : '';
			if( !empty($user_id) ) {
				$featured_jobs		= workreap_featured_job ( $user_id );
				if( $featured_jobs ) {
					$json['featured_job']	= 'yes';
					$json		= maybe_unserialize($json);
					return new WP_REST_Response($json, 200);
				} else {
					$json['featured_job']	= 'no';
					$json['type'] 		= "error";
					$json['message'] 	= esc_html__("Your featured job creation option is expired.", 'workreap_api');
					$json		= maybe_unserialize($json);
					return new WP_REST_Response($json, 203);
				}
				
			} 
		}
		
		/**
         * Get taxonomy
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function get_taxonomy($request) {
			$taxonomy			= !empty( $request['taxonomy'] ) ?  $request['taxonomy'] : '';
			if( !empty($taxonomy) ) {
				$terms		= workreap_get_taxonomy_array($taxonomy);
				$terms_data	= array();
				
				if( !empty($terms) ) {
					foreach ($terms as $key => $term) {
						$terms_data[$term->term_id]['id'] = $term->term_id;
						$terms_data[$term->term_id]['name'] = $term->name;
						$terms_data[$term->term_id]['slug'] = $term->slug;
					}
				}
				$item		= array_values($terms_data);
				
				return new WP_REST_Response($item, 200);
			} else {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("there are no taxonomies are found.", 'workreap_api');
				return new WP_REST_Response($json, 203);	
			}
		}
		
		/**
         * Get list
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
		public function get_list($request) {
			$list			= !empty( $request['list'] ) ?  $request['list'] : '';
			
			if( !empty($list) ) {
				if($list === 'rates') {
					$lists	= worktic_hourly_rate_list();
				} elseif( $list === 'english_levels' ) {
					$lists	= worktic_english_level_list();
				} elseif( $list === 'freelancer_level' ) {
					$lists	= worktic_freelancer_level_list();
				} elseif( $list === 'duration_list' ) {
					$lists	= worktic_job_duration_list();
				}elseif( $list === 'project_type' ) {
					$lists	= workreap_get_job_type();
				}elseif( $list === 'no_of_employes' ) {
					$lists	= worktic_get_employees_list();
				}elseif( $list === 'reason_type' ) {
					$lists	= workreap_get_report_reasons();
				}elseif( $list === 'project_level' ) {
					$lists	= workreap_get_project_level();
				}elseif( $list === 'experience' ) {
					$lists	= workreap_experience_years();
				}elseif( $list === 'woo_countries') {
					$lists		= array();
					if (class_exists('WooCommerce')) {
						$countries_obj	= new WC_Countries();
						$lists   		= $countries_obj->__get('countries');
					} 
				}else {
					$lists	= array();
				}
				
				if( !empty($lists) ) {
					$count_list	= 0;
					$lists_data	= array();
					foreach ($lists as $key => $val) {
						$count_list ++;
						if( $list === 'no_of_employes' ) {
							if( is_array($val) ) {
								foreach( $val as $k => $v ) {
									$lists_data[$count_list][$k]	= $v;
								}
							}
						} else {
							$lists_data[$count_list]['title'] 		= $val;
							$lists_data[$count_list]['value'] 		= $key;
						}
					}
					
					$item		= array_values($lists_data);
					$json		= maybe_unserialize($item);
					return new WP_REST_Response($json, 200);
				} else {
					$json['type'] 		= "error";
					$json['message'] 	= esc_html__("there are no taxonomies are found.", 'workreap_api');
					return new WP_REST_Response($json, 203);
				}
			} else {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("there are no taxonomies are found.", 'workreap_api');
				return new WP_REST_Response($json, 203);	
			}
		}
		
		/**
         * Get taxonomies
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_taxonomies() {
			
			$json	= array();
			$items	= array();
			
			$texanomies		= array(
								'skills' 		=> 'skills',
								'locations'		=> 'locations',
								'languages'		=> 'languages',
								'department'	=> 'department',
								'wt_industrial_experience'	=> 'wt-industrial-experience',
								'wt_specialization'	=> 'wt-specialization',
								'project_cat'	=> 'project_cat'
							);
			
			foreach( $texanomies as $key => $val) {
				$terms		= workreap_get_taxonomy_array($val);
				$terms_data	= array();
				
				if( !empty($terms) ) {
					foreach ($terms as $tkey => $term) {
						$terms_data[$term->term_id]['id'] = $term->term_id;
						$terms_data[$term->term_id]['name'] = wp_specialchars_decode($term->name);
						$terms_data[$term->term_id]['slug'] = $term->slug;
					}
				}
				$item[$key]	= array_values($terms_data);
			}
			
			$lists		= array(
							'rates' 				=> worktic_hourly_rate_list(),
							'english_levels' 		=> worktic_english_level_list(),
							'freelancer_level' 		=> worktic_freelancer_level_list(),
							'duration_list' 		=> worktic_job_duration_list()
						);
			
			foreach( $lists as $main_key => $list) {				
				if( !empty($list) ) {
					$count_list	= 0;
					foreach ($list as $key => $val) {
						$count_list ++;
						
						$lists_data[$count_list]['title'] 		= $val;
						$lists_data[$count_list]['value'] 		= $key;
					}
					$item[$main_key]	= array_values($lists_data);
				}
			}
			
			$item['no_of_employes']	= array_values(worktic_get_employees_list());
			
			if( !empty($item) ) {
				
				$items[]	= maybe_unserialize($item);
				return new WP_REST_Response($items, 200);
			} else {
				$json['type'] 		= "error";
				$json['message'] 	= esc_html__("there are no taxonomies are found.", 'workreap_api');
				return new WP_REST_Response($json, 203);	
			}
        }
    }
}

add_action('rest_api_init',
        function () {
    $controller = new AndroidApp_Taxonomies_Route;
    $controller->register_routes();
});
