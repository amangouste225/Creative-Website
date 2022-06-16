<?php
if (!class_exists('AndroidAppGetEmployersRoutes')) {

    class AndroidAppGetEmployersRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'listing';

            register_rest_route($namespace, '/' . $base . '/get_employers',
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
         * Get Listings employers
         *
         * @param WP_REST_Request $request Full data about the request.
         * @return WP_Error|WP_REST_Response
         */
        public function get_listing($request){
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$profile_id		= !empty( $request['profile_id'] ) ? intval( $request['profile_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;
			
			$json		= array();
			$items		= array();
			$item		= array();
			$today 		= time();
			$reviews	= array();
			
			$following_employers	= array();
			if( !empty($profile_id) ) {
				$following_employers	= get_post_meta($profile_id,'_following_employers',true);
			}
						
			$duration_list 				= worktic_job_duration_list();
			if (function_exists('fw_get_db_settings_option')) {
					$featured_image		= fw_get_db_settings_option('featured_job_img');
					$featured_bg_color	= fw_get_db_settings_option('featured_job_bg');
				}

			$tag		  = !empty( $featured_image['url'] ) ? $featured_image['url'] : $defult;
			$color		  = !empty( $featured_bg_color ) ? $featured_bg_color : '#f1c40f';
			if( $request['listing_type'] === 'single' ){
				
				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'employers',
					'post__in' 		 	  	=> array($profile_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			}else if( $request['listing_type'] === 'featured' ){
				
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'employers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);
				
				//order by pro member
				$query_args['orderby']  	= 'meta_value_num';
				$query_args['order'] 		= 'DESC';
				$query_args['meta_key'] 	= '_featured_timestamp';
				$meta_query_args[] = array(
					'key' 		=> '_featured_timestamp',
					'compare' 	=> '>=',
					'value'		=> $today
				);


				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;

			} elseif( $request['listing_type'] === 'latest' ){
				$order		 = 'DESC';
				$query_args = array(
					'posts_per_page' 	  	=> $limit,
					'post_type' 	 	  	=> 'employers',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=>'ID',
					'orderby'				=>$order,
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			} elseif( $request['listing_type'] === 'favorite' ){
				$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_following_employers',true);
				$wishlist			= !empty($wishlist) ? $wishlist : array();
				if( !empty($wishlist) ) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'employers',
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
					$json['message']	= esc_html__('You have no employers in your favorite list.','workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
				
			} elseif( $request['listing_type'] === 'search' ){
				
				//Search parameters
				$keyword 		= !empty( $request['keyword']) ? $request['keyword'] : '';
				$employees 		= !empty( $request['employees']) ? $request['employees'] : '';
				$departments 	= !empty( $request['department']) ? $request['department'] : array();
				$locations 	 	= !empty( $request['location']) ? $request['location'] : array();


				$tax_query_args  = array();
				$meta_query_args = array();

				//departments
				if ( !empty($departments[0]) && is_array($departments) ) {   
					$query_relation = array('relation' => 'OR',);
					$department_args  = array();

					foreach( $departments as $key => $department ){
						$department_args[] = array(
								'taxonomy' => 'department',
								'field'    => 'slug',
								'terms'    => $department,
							);
					}

					$tax_query_args[] = array_merge($query_relation, $department_args);
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

				//no of employees
				if ( !empty( $employees ) ) {  
					$meta_query_args[] = array(
						'key' 				=> '_employees',
						'value' 			=> $employees,
						'type' 				=> 'NUMERIC',
						'compare' 			=> '='
					);    
				}

				//default
				$meta_query_args[] = array(
						'key' 			=> '_profile_blocked',
						'value' 		=> 'off',
						'compare' 		=> '='
					); 

				$query_args = array(
					'posts_per_page'      => $limit,
					'paged'			      => $page_number,
					'post_type' 	      => 'employers',
					'post_status'	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				//keyword search
				if( !empty($keyword) ){
					$query_args['s']	=  $keyword;
				}

				//Taxonomy Query
				if ( !empty( $tax_query_args ) ) {
					$query_relation = array('relation' => 'AND',);
					$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
				}

				//Meta Query
				if (!empty($meta_query_args)) {
					$query_relation = array('relation' => 'AND',);
					$meta_query_args = array_merge($query_relation, $meta_query_args);
					$query_args['meta_query'] = $meta_query_args;
				}

				$query 			= new WP_Query($query_args);
				$count_post   = $query->found_posts;

			// end search query
				
			}else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please provide api type','workreap_api');
				return new WP_REST_Response($json, 203);
			}
			
			//Start Query working
			
			if ($query->have_posts()) {
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
					
					if( !empty($following_employers)  &&  in_array($post->ID,$following_employers)) {
						$item['favorit']			= 'yes';
					} else {
						$item['favorit']			= '';
					}
					if( function_exists( 'workreap_get_linked_profile_id' ) ) {
						$user_id	= workreap_get_linked_profile_id( $post->ID ,'post');
					} else {
						$user_id	= get_post_field( 'post_author', $post->ID );
					}
					$user_id			= !empty( $user_id ) ?  $user_id  : '';
					$url				= !empty( get_the_permalink($post->ID) ) ? esc_url(get_the_permalink($post->ID)) : '';
					$item['name']		= !empty(get_the_title()) ? get_the_title() : '';
					$item['user_id']	= $user_id;
					$item['employ_id']	= $user_id;
					$item['profile_id']	= $post->ID;
					$item['company_link']= $url;
					$employer_banner	= apply_filters(
												'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 350, 'height' => 172), $post->ID), array('width' => 350, 'height' => 172) 
											);

					$employer_avatar 	= apply_filters(
											'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $post->ID), array('width' => 100, 'height' => 100) 
										);
					
					$item['profile_img'] 	= !empty($employer_avatar) ? esc_url($employer_avatar) : '';
					$item['User_profileID'] = $post->ID;
					$item['banner_img'] 	= !empty($employer_banner) ? esc_url($employer_banner) : '';
					$item['employer_des'] 	= get_the_content();
					$item['link']			= esc_url(get_the_permalink());
					
					if (function_exists('fw_get_db_post_option')) {
						$address	= fw_get_db_post_option($post->ID, 'address', true);
						$longitude	= fw_get_db_post_option($post->ID, 'longitude', true);
						$latitude	= fw_get_db_post_option($post->ID, 'latitude', true);
						$tag_line	= fw_get_db_post_option($post->ID, 'tag_line', true);
					}
					
					$item['_longitude'] 	= !empty($longitude) ? $longitude : '';
					$item['_latitude'] 		= !empty($latitude) ? $latitude : '';
					$item['_address'] 		= !empty($address) ? $address : '';
					$item['_tag_line'] 		= !empty($tag_line) ? $tag_line : '';
					$is_verified			= get_post_meta($post->ID,'_is_verified',true);
					$item['_is_verified'] 	= !empty($is_verified) ? $is_verified : '';
					$_featured_timestamp	= get_post_meta($post->ID,'_featured_timestamp',true);
					
					if( intval($_featured_timestamp) >= $today ) {
						$item['_featured_timestamp']['class']	= 'wt-featured';
					} 
					$item['_featured_timestamp']['class']	= !empty( $item['_featured_timestamp']['class'] ) ? $item['_featured_timestamp']['class'] : '';
					
					if( function_exists( 'workreap_get_location' ) ) {
						$country	= workreap_get_location( $post->ID );
					}
					$item['location']['flag']		= !empty($country['flag']) ? $country['flag']	: '';
					$item['location']['_country']	= !empty($country['_country']) ? $country['_country']	: '';
					
					$item['count_totals']   = !empty($count_post) ? intval($count_post) : 0;
					$items[]			= maybe_unserialize($item);
				}

				return new WP_REST_Response($items, 200);
			}else{
				$json['type']		= 'error';
				$json['message']	= esc_html__('employers are not found.','workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			} 
        }
    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetEmployersRoutes;
	$controller->register_routes();
});
