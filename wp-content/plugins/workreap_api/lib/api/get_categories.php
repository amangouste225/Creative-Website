<?php
if (!class_exists('AndroidAppGetCategoriesRoutes')) {

    class AndroidAppGetCategoriesRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'list';

            register_rest_route($namespace, '/' . $base . '/get_categories',
                array(
                  array(
                        'methods' => WP_REST_Server::READABLE,
                        'callback' => array(&$this, 'get_listing'),
                        'args' => array(),
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
        public function get_listing($request){
			$limit			= !empty( $request['show_number'] ) ? intval( $request['show_number'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$offset 		= ($page_number - 1) * $limit;
			$json			= array();
			$items			= array();
			
			$term_args 		= array(
								'number' => $limit,
								'offset' => $offset,
								'hide_empty' => false
							);
			
			if (function_exists('fw_get_db_post_option') ) {
				$services_categories	= fw_get_db_settings_option('services_categories');
			}

			$services_categories	= !empty($services_categories) ? $services_categories : 'no';
			
			if( !empty($services_categories) && $services_categories === 'no' ) {
				$categories		= get_terms('project_cat', $term_args);
			}else{
				$categories		= get_terms('service_categories', $term_args);
			}

			if( !empty($categories) ) {
				foreach ($categories as $category) {
					$item					= array();
					$item['icon_class']		= '';
					$item['image']			= '';
					if( function_exists( 'fw_get_db_term_option' ) ) {
                        $icon          		= fw_get_db_term_option($category->term_id, 'project_cat');
                        $category_icon 		= !empty($icon['category_icon']) ? $icon['category_icon'] : array();
						
						if ( !empty($category_icon) && $category_icon['type'] === 'icon-font') {
							$item['icon_class']	= $category_icon['icon-class'];
							$item['image']		= '';
						} elseif (!empty($category_icon['type']) && $category_icon['type'] === 'custom-upload') {
							$item['icon_class']	=	'';
							if (!empty($category_icon['url'])) {
								$item['image']	= $category_icon['url'];
							} else {
								$item['image']		= '';
							}
						} else if( !empty($category_icon['type']) && $category_icon['type'] === 'none'){
							if (!empty($icon['category_image']['url'])) {
								$item['image']	= $icon['category_image']['url'];
							} else {
								$item['image']		= '';
							}
						}
                    } else {
						$item['icon_class']	= '';
						$item['image']		= '';
					} 
					$item['link']			= !empty(get_term_link($category)) ? esc_url(get_term_link($category)) : '';
					$item['name']			= !empty($category->name) ? esc_attr($category->name) : '';
					$item['slug']			= !empty($category->slug) ? esc_attr($category->slug) : '';
					$item['term_id']		= !empty($category->term_id) ? intval($category->term_id) : '';
					$item['description']	= !empty($category->description) ? ($category->description) : '';
					$items[] 				= $item;
				}
				return new WP_REST_Response($items, 200);
			} else{
				$json['type']		= 'error';
				$json['message']	= esc_html__('Some error occur, please try again later','workreap_api');
				return new WP_REST_Response($items, 203);
			}
        }

    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetCategoriesRoutes;
	$controller->register_routes();
});
