<?php
if (!class_exists('AndroidAppGetFreelancersRoutes')) {

    class AndroidAppGetFreelancersRoutes extends WP_REST_Controller{

        /**
         * Register the routes for the objects of the controller.
         */
        public function register_routes() {
            $version 	= '1';
            $namespace 	= 'api/v' . $version;
            $base 		= 'listing';

            register_rest_route($namespace, '/' . $base . '/get_freelancers',
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
			$limit			= !empty( $request['show_posts'] ) ? intval( $request['show_posts'] ) : 10;
			$page_number	= !empty( $request['page_number'] ) ? intval( $request['page_number'] ) : 1;
			$profile_id		= !empty( $request['profile_id'] ) ? intval( $request['profile_id'] ) : '';
			$offset 		= ($page_number - 1) * $limit;

			$json		= array();
			$item		= array();
			$items		= array();
			$today 		= time();
			$reviews	= array();
			
			$saved_freelancers	= array();
			
			if( !empty($profile_id) ) {
				$saved_freelancers	= get_post_meta($profile_id,'_saved_freelancers',true);
			}
			
			$date_formate	= get_option('date_format');
			if( $request['listing_type'] === 'single' ){

				$query_args = array(
					'posts_per_page' 	  	=> 1,
					'post_type' 	 	  	=> 'freelancers',
					'post__in' 		 	  	=> array($profile_id),
					'post_status' 	 	  	=> 'publish',
					'ignore_sticky_posts' 	=> 1
				);
				
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
				
			}else if( $request['listing_type'] === 'featured' ){
                $hide_profiles  = array();
                if( function_exists('fw_get_db_settings_option')  ) {
                    $hide_profiles = fw_get_db_settings_option('hide_profiles', $default_value = 'no');
                }
				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'freelancers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

                /* query for featured */
                $meta_query_args[] = array(
                    'key'         => '_featured_timestamp',
                    'value'       => 1,
                    'compare'     => '=',
                );

                //Profile strength
                if(!empty($hide_profiles['gadget']) && $hide_profiles['gadget'] === 'yes'){
                    if(!empty( $hide_profiles['yes']['define_percentage'])){
                        $meta_query_args[] = array(
                            'key' 			=> '_profile_health_filter',
                            'value' 		=> intval($hide_profiles['yes']['define_percentage']),
                            'compare' 		=> '>=',
                            'type'			=> 'NUMERIC'
                        );
                    }
                }


                //order by pro member
                $query_args['meta_key'] = '_featured_timestamp';
				$query_args['orderby']	 = array( 
					'meta_value' 	=> 'DESC', 
					'ID'      		=> 'DESC'
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
					'post_type' 	 	  	=> 'freelancers',
					'paged' 		 	  	=> $page_number,
					'post_status' 	 	  	=> 'publish',
					'order'					=> 'ID',
					'orderby'				=> $order,
					'ignore_sticky_posts' 	=> 1
				);
				$query 			= new WP_Query($query_args);
				$count_post 	= $query->found_posts;
			} elseif( $request['listing_type'] === 'favorite' ){
				$user_id			= !empty( $request['user_id'] ) ? intval( $request['user_id'] ) : '';
				$linked_profile   	= workreap_get_linked_profile_id($user_id);
				$wishlist 			= get_post_meta($linked_profile, '_saved_freelancers', true);
				$wishlist			= !empty($wishlist)  && is_array( $wishlist ) ? $wishlist : array();
				if( !empty($wishlist) ) {
					$order		 = 'DESC';
					$query_args = array(
						'posts_per_page' 	  	=> $limit,
						'post_type' 	 	  	=> 'freelancers',
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
					$json['message']	= esc_html__('You have no freelancer in your favorite list.','workreap_api');
					$items[] 			= $json;
					return new WP_REST_Response($items, 203);
				}
			} elseif( $request['listing_type'] === 'search' ){
				$item 				= array();
				$items 				= array();
				$meta_query_args 	= array();
				//Search parameters
				$keyword 		= !empty( $request['keyword']) ? $request['keyword'] : '';
				$languages 		= !empty( $request['language']) ? $request['language'] : array();
				$locations 	 	= !empty( $request['location']) ? $request['location'] : array();
				$skills			= !empty( $request['skills']) ? $request['skills'] : array();
				$duration 		= !empty( $request['duration'] ) ? $request['duration'] : '';
				$type 			= !empty( $request['type'] ) ? $request['type'] : array();
				$english_level  = !empty( $request['english_level'] ) ? $request['english_level'] : array();
				$hourly_rate    = !empty( $request['hourly_rate'] ) ? explode('-',$request['hourly_rate']) : '';
                $hide_profiles  = array();
                if( function_exists('fw_get_db_settings_option')  ) {
                    $hide_profiles = fw_get_db_settings_option('hide_profiles', $default_value = 'no');
                }
				$hourly_rate_start = 0;
				$hourly_rate_end   = 1000;

				if( !empty($hourly_rate) ){
					$hourly_rate_start = isset($hourly_rate[0]) ? intval( $hourly_rate[0] ) : 0;
					$hourly_rate_end   = !empty($hourly_rate[1]) ? intval( $hourly_rate[1] ) : 1000;
				} 

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

                //Profile strength
                if(!empty($hide_profiles['gadget']) && $hide_profiles['gadget'] === 'yes'){
                    if(!empty( $hide_profiles['yes']['define_percentage'])){
                        $meta_query_args[] = array(
                            'key' 			=> '_profile_health_filter',
                            'value' 		=> intval($hide_profiles['yes']['define_percentage']),
                            'compare' 		=> '>=',
                            'type'			=> 'NUMERIC'
                        );
                    }
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

					$tax_query_args[] = array_merge($query_relation, $skills_args);
				}

				//Freelancer Skill Level
				if ( !empty( $type ) ) {    
					$meta_query_args[] = array(
						'key' 		=> '_freelancer_type',
						'value' 	=> $type,
						'compare' 	=> 'IN'
					);    
				}

				//English Level
				if ( !empty( $english_level ) ) {    
					$meta_query_args[] = array(
						'key' 			=> '_english_level',
						'value' 		=> $english_level,
						'compare' 		=> 'IN'
					);    

				}

				//Hourly Rate
				if ( !empty( $hourly_rate ) ) {  
					$meta_query_args[] = array(
						'key' 				=> '_perhour_rate',
						'value' 			=> array( $hourly_rate_start, $hourly_rate_end ),
						'type' 				=> 'NUMERIC',
						'compare' 			=> 'BETWEEN'
					);    
				}

				$meta_query_args[] = array(
						'key' 			=> '_profile_blocked',
						'value' 		=> 'off',
						'compare' 		=> '='
					); 

				$query_args = array(
					'posts_per_page' 	  => $limit,
					'post_type' 	 	  => 'freelancers',
					'paged' 		 	  => $page_number,
					'post_status' 	 	  => 'publish',
					'ignore_sticky_posts' => 1
				);

				//keyword search
				if( !empty($keyword) ){
					$query_args['s']	=  $keyword;
				}

				//order by pro member
				$query_args['meta_key'] = '_featured_timestamp';
				$query_args['orderby']	 = array( 
					'meta_value' 	=> 'DESC', 
					'ID'      		=> 'DESC'
				); 

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
				$count_post 	= $query->found_posts;
				
			} else {
				$json['type']		= 'error';
				$json['message']	= esc_html__('Please provide api type','workreap_api');
				return new WP_REST_Response($json, 203);
			}

			if ($query->have_posts()) {
				while ($query->have_posts()) { 
					$query->the_post();
					global $post;
					
					if( !empty($saved_freelancers)  &&  in_array($post->ID,$saved_freelancers)) {
						$item['favorit']			= 'yes';
					} else {
						$item['favorit']			= '';
					}
					$is_featured = get_post_meta($post->ID,'_featured_timestamp', true);
					$is_featured = !empty($is_featured) ? $is_featured : '';
					if( function_exists( 'workreap_get_linked_profile_id' ) ) {
						$user_id	= workreap_get_linked_profile_id( $post->ID ,'post' );
					} else {
						$user_id	= get_post_field( 'post_author', $post->ID );
					}
					
					$user_id				= !empty( $user_id ) ? intval( $user_id ) : '';
					$url					= !empty( get_the_permalink($post->ID) ) ? esc_url(get_the_permalink($post->ID)) : '';
					$item['name']			= !empty(get_the_title()) ? get_the_title() : '';
					$item['user_id']		= $user_id;
					$item['profile_id']		= $post->ID;
					
					$item['content']		= get_the_content();
					$item['member_since']	= get_the_date($date_formate,$post->ID);
					$item['freelancer_link']= $url;
					$item['profile_img'] 	= apply_filters(
													'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $post->ID ), array( 'width' => 100, 'height' => 100 )
												);
					$item['banner_img'] 	= apply_filters(
												'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner(array('width' => 350, 'height' => 172), $post->ID), array('width' => 350, 'height' => 172) 
												);
					
					$featured_id	= workreap_is_feature_value( 'wt_badget',intval($user_id) ); 
					$featured_id	= !empty($featured_id) ? intval($featured_id) : '';
					if( empty($featured_id) ) {
						$item['badge']['badget_url']		= '';
						$item['badge']['badget_color']		= '';
					} elseif( !empty($featured_id) ) {
						$term	= get_term( $featured_id );
						if( !empty($term) ) {
							$badge_icon  = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_icon');
							$badge_color = fw_get_db_term_option($term->term_id, 'badge_cat', 'badge_color');
							if( !empty( $badge_icon['url'] ) ){
								$color = !empty( $badge_color ) ? $badge_color : '#ff5851';
								$item['badge']['badget_url']		= workreap_add_http($badge_icon['url']);
								$item['badge']['badget_color']		= esc_attr($color);
							}else{
								$item['badge']['badget_url']		= '';
								$item['badge']['badget_color']		= '';
							}
							
						}else{
							$item['badge']['badget_url']		= '';
							$item['badge']['badget_color']		= '';
						}
					} 
					
					$earnings						= workreap_get_sum_payments_freelancer($user_id,'completed','amount');
					$earnings						= !empty($earnings) ?   $earnings : 0;
					
					if( function_exists( 'workreap_price_format' ) ) {
						$item['total_earnings']		= workreap_price_format($earnings,'return');
					} else {
						$item['total_earnings']		= $earnings;
					}
					
					$is_verified					= get_post_meta($post->ID,'_is_verified',true);
					$item['_is_verified'] 			= !empty($is_verified) ? $is_verified : '';
					$featured_timestamp				= get_post_meta($post->ID,'_featured_timestamp',true);
                    $featured_expiry                = get_post_meta($post->ID,'_expiry_string', true);
                    $featured_expiry                = !empty($featured_expiry) ? $featured_expiry : 0;
                    $item['_featured_timestamp'] 	= !empty($featured_timestamp) && $featured_expiry > $today ? 'wt-featured' : array();

					
					$rating_filter					= get_post_meta($post->ID,'rating_filter',true);
					$item['rating_filter'] 			= !empty($rating_filter) ? $rating_filter : '';
					
					$review_data					= get_post_meta($post->ID,'review_data',true);
					$review_data 					= !empty($review_data) ? $review_data : array();
					
					if(!empty( $review_data )) {
						$item['wt_average_rating']		= !empty( $review_data['wt_average_rating'] ) ? $review_data['wt_average_rating'] : 0;
						$item['wt_total_rating']		= !empty( $review_data['wt_total_rating'] ) ? $review_data['wt_total_rating'] : 0;
						$item['wt_total_percentage']	= !empty( $review_data['wt_total_percentage'] ) ? $review_data['wt_total_percentage'] : 0;
					} else {
						$item['wt_average_rating']		= 0;
						$item['wt_total_rating']		= 0;
						$item['wt_total_percentage']	= 0;
					}
					
					if( function_exists( 'fw_get_db_term_option' ) ) {
						$education 	= fw_get_db_post_option($post->ID, 'education',true);
						$experience = fw_get_db_post_option($post->ID, 'experience',true);
						$awards		= fw_get_db_post_option($post->ID, 'awards',true);
						$projects	= fw_get_db_post_option($post->ID, 'projects',true);
                        $specialization = fw_get_db_post_option($post->ID, 'specialization', true);
						
						$address	= fw_get_db_post_option($post->ID, 'address');
						$longitude	= fw_get_db_post_option($post->ID, 'longitude');
						$latitude	= fw_get_db_post_option($post->ID, 'latitude');
						$tag_line	= fw_get_db_post_option($post->ID, 'tag_line');
						$gender		= fw_get_db_post_option($post->ID, 'gender');
						$rates		= fw_get_db_post_option($post->ID, '_perhour_rate');
						$eng_level	= fw_get_db_post_option($post->ID, '_english_level');
						
						$item['_longitude'] 	= !empty($longitude) ? $longitude : '';
						$item['_latitude'] 		= !empty($latitude) ? $latitude : '';
						$item['_address'] 		= !empty($address) ? $address : '';
						$item['_tag_line'] 		= !empty($tag_line) ? stripslashes($tag_line) : '';
						$item['_gender'] 		= !empty($gender) ? $gender : '';
						$item['_perhour_rate'] 	= !empty($rates) ? html_entity_decode( workreap_price_format($rates,'return') ) : '';
						$item['_english_level'] = !empty($eng_level) ? $eng_level : '';
						
						$edu	= array();
						$exp	= array();
						$awd	= array();
						$proj	= array();
						
						if( !empty( $education ) && is_array($education) ){
							foreach ($education as $keys => $values) {
								foreach($values as $key_main => $val ){
									if($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$edu[$keys][$key_main]	= date_i18n($date_formate,strtotime($val));
									} else {
										$edu[$keys][$key_main]	= $val;
									}
								}
							}
							$item['_educations']	= $edu;
						} else {
							$item['_educations']	= $edu;
						}
						
						if( !empty( $experience ) && is_array($experience) ){
							foreach ($experience as $keys => $values) {
								foreach($values as $key_main => $val ){
									if($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$exp[$keys][$key_main]	= date_i18n($date_formate,strtotime($val));
									} else {
										$exp[$keys][$key_main]	= $val;
									}
								}
							}
							$item['_experience']	= $exp;
						} else {
							$item['_experience']	= $exp;
						}
						
						if( !empty( $awards ) && is_array($awards) ){
							foreach ($awards as $keys => $values) {
								if( empty( $values['image']['url'] )) {
									$values['image']	= array();
									$values['image']['url'] 			= '';
									$values['image']['attachment_id'] 	= '';
								}
								
								foreach($values as $key_main => $val ){
									if($key_main === 'startdate' || $key_main === 'enddate' || $key_main === 'date') {
										$awd[$keys][$key_main]	= date_i18n($date_formate,strtotime($val));
									} elseif($key_main === 'image') {
										$awd[$keys][$key_main]['url']			=  !empty( $val['url'] ) ? workreap_add_http($val['url']) : '';
										$awd[$keys][$key_main]['attachment_id']	= $val['attachment_id'];
									} else {
										$awd[$keys][$key_main]	= $val;
									}
								}
							}
							
							$item['_awards']	= $awd;
						} else {
							$item['_awards']	= $awd;
						}
						
						if( !empty( $projects ) && is_array($projects) ){
							foreach ($projects as $keys => $values) {
								if( empty( $values['image'] )) {
									$values['image']	= array();
									$values['image']['url'] 			= '';
									$values['image']['attachment_id'] 	= '';
								}
								foreach($values as $key_main => $val ){
									
									if($key_main === 'image') {
										$pro[$keys][$key_main]['url']			= !empty( $val['url'] ) ? workreap_add_http($val['url']) : '';
										$pro[$keys][$key_main]['attachment_id']	= $val['attachment_id'];
									} else {
										$pro[$keys][$key_main]	= $val;
									}
								}
							}
							$item['_projects']	= $pro;
						} else {
							$item['_projects']	= $proj;
						}
						
						$args = array();
								
						$terms 						= wp_get_post_terms( $post->ID, 'locations', $args );
						$countries					= !empty( $terms[0]->term_id ) ? intval( $terms[0]->term_id ) : '';
						$locations_name				= !empty( $terms[0]->name ) ?  $terms[0]->name  : '';
						if(!empty($locations_name) ) {
							$item['location']['_country']			= $locations_name;
						} else {
							$item['location']['_country']			= '';
						}
						$icon          				= !empty($countries) ? fw_get_db_term_option($countries,'locations', 'image') : '';
						$item['location']['flag'] 	= !empty($icon['url']) ? workreap_add_http($icon['url']) : '';
					}
					
					// freelancer Skills
                    $item['skills'] = array();
                    if (function_exists('fw_get_db_post_option')) {
                        $freela_skills  = fw_get_db_post_option($post->ID, 'skills', true);
                    } else {
                        $freela_skills	= array();
                    }
                    $display_type	    = '';
                    if( function_exists('fw_get_db_settings_option')  ){
                        $display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
                    }
                    $field_type		    = !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years','workreap');
                    if(!empty($freela_skills)){
                        $fre_skills = $freelancer_skils = array();
                        foreach( $freela_skills as $key => $item_ ){
                            if( !empty( $item_['skill'][0] ) ){
                                $freelan_skill	= get_term_by('id', $item_['skill'][0], 'skills');
                                $item_val	    = !empty( $item_['value'] ) ? intval($item_['value']) : 0;
                                if( !empty( $display_type ) && $display_type === 'number' ){
                                    $percentage	= $item_val;
                                } else{
                                    if($item_val>10){
                                        $item_val	= 10;
                                    }
                                    $percentage	= $item_val*10;
                                }
                                if( !empty($freelan_skill->name) ) {
                                    $fre_skills['skill_val']        = $item_val;
                                    $fre_skills['skill_name']       = $freelan_skill->name;
                                    $fre_skills['skill_percent']    = $percentage;
                                }
                            }
                            $freelancer_skils[] = $fre_skills;
                        }
                        $item['skills']         = $freelancer_skils;
                    }

                    // industrial experience
                    $item['industrial_exper'] = array();
                    if (function_exists('fw_get_db_post_option')) {
                        $industrial_experiences_    = fw_get_db_post_option($post->ID, 'industrial_experiences');
                    } else {
                        $industrial_experiences_	= array();
                    }
                    $display_type	= '';
                    if( function_exists('fw_get_db_settings_option')  ){
                        $display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
                    }
                    $field_type		    = !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years','workreap');
                    if( !empty( $industrial_experiences_ ) && is_array($industrial_experiences_) ){
                        $exper = $exper_ = array();
                        foreach( $industrial_experiences_ as $key => $item_ ){
                            if( !empty( $item_['exp'][0] ) ){
                                $industrial_experi		= get_term_by('id', $item_['exp'][0], 'wt-industrial-experience');
                                $item_val	= !empty( $item_['value'] ) ? intval( $item_['value'] ) : 0;
                                if( !empty( $display_type ) && $display_type === 'number' ){
                                    $percentage	    = $item_val;
                                } else{
                                    if($item_val>10){
                                        $item_val	= 10;
                                    }
                                    $percentage	= $item_val*10;
                                }
                                if( !empty($industrial_experi->name) ) {
                                    $exper['name']          = $industrial_experi->name;
                                    $exper['percent']       = intval( $percentage );
                                    $exper['item_val']      = $item_val;
                                    $exper['field_type']    = $field_type;
                                }
                            }
                            $exper_[]                       = $exper;
                        }
                        $item['industrial_exper']           = $exper_;
                    }

                    //languages
                    $remove_languages		    = fw_get_db_settings_option('frc_remove_languages', $default_value = 'no');
                    $db_languages				= wp_get_post_terms($post->ID, 'languages');
                    $item['languages'] = array();
                    if( !empty( $db_languages ) && (!empty($remove_languages) && $remove_languages === 'no' )){
                        $item['languages'] = $db_languages;
                    }

                    //english level
                    $item['english_level']          = '';
                    $english_level_list             = worktic_english_level_list();
                    $english_level	                = fw_get_db_post_option($post->ID, 'english_level');
                    $frc_english_level			    = fw_get_db_settings_option('frc_english_level', $default_value = 'no');
                    if( !empty( $english_level_list[$english_level] ) &&  (!empty($frc_english_level) && $frc_english_level === 'no')  ) {
                        $item['english_level'] = $english_level_list[$english_level];
                    }

                    //specialization
                    $item['specializaton']  = array();
                    $experience_type	    = fw_get_db_settings_option('display_type', $default_value = 'number');
                    $field_type		        = !empty($experience_type) && ($experience_type === 'number') ? '%' : esc_html__('Years','workreap_api');
                    if( !empty( $specialization ) && is_array($specialization) ){
						$specilize		= $specilize_ =	 array();
                        foreach( $specialization as $spec_item ){
                            if( !empty( $spec_item['spec'][0] ) ){
                                $skill		= get_term_by('id', $spec_item['spec'][0], 'wt-specialization');
                                $item_val	= !empty( $spec_item['value'] ) ? intval( $spec_item['value'] ) : 0;
                                if( !empty( $experience_type ) && $experience_type === 'number' ){
                                    $percentage	= $item_val;
                                } else{
                                    if($item_val>10){
                                        $item_val	= 10;
                                    }
                                    $percentage	= $item_val*10;
                                }
                                if( !empty($skill->name) ) {
                                    $specilize['specilize_name']        = $skill->name;
                                    $specilize['specilize_percent_val'] = $percentage;
                                    $specilize['specilize_type_']       = $field_type;
                                    $specilize['tem_val']               = $item_val;
                                }
                            }
                            $specilize_[]       =  $specilize;
                        }
                        $item['specializaton']  = $specilize_;
                    }

                    //Social profiles
                    $socialmediaurls            = $item['socials_profile']	= array();
                    if( function_exists('fw_get_db_settings_option')  ){
                        $post_type			    = get_post_type($post->ID);
                        if( !empty( $post_type ) && $post_type ==='employers'){
                            $socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
                        } else {
                            $socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
                        }
                    }
                    $socialmediaurl 		    = !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';
                    $social_settings	        = array();
                    if(function_exists('workreap_get_social_media_icons_list')){
                        $social_settings	    = workreap_get_social_media_icons_list('no');
                    }
                    $social_available = 'no';
                    if(!empty($social_settings) && is_array($social_settings) ) {
                        foreach($social_settings as $key => $val ) {
                            $social_url	= '';
                            if( function_exists('fw_get_db_post_option') ){
                                $social_url	= fw_get_db_post_option($post->ID, $key, null);
                            }
                            if(!empty($social_url)){
                                $social_available = 'yes';
                                break;
                            }
                        }
                    }
                    if(!empty($socialmediaurl) && $socialmediaurl === 'enable' && $social_available === 'yes') {
                        foreach($social_settings as $key => $val ) {
                            $icon		    = !empty( $val['icon'] ) ? $val['icon'] : '';
                            $color		    = !empty( $val['color'] ) ? $val['color'] : '#484848';
                            $enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
                            if( !empty($enable_value) && $enable_value === 'enable' ){
                                $social_url	= '';
                                if( function_exists('fw_get_db_post_option') ){
                                    $social_url	= fw_get_db_post_option($post->ID, $key, null);
                                }
                                $social_url	= !empty($social_url) ? $social_url : '';
                                if($social_url!=''){
                                    if ($key === 'whatsapp') {
                                        if (!empty($social_url)) {
                                            $social_url = 'https://api.whatsapp.com/send?phone=' . $social_url;
                                            $social['social_profile_type']      = $key;
                                            $social['social_profile_url']       = esc_attr($social_url);
                                            $social['social_profile_icon']      = esc_attr($icon);
                                        }
                                    } else if ($key === 'skype') {
                                        if (!empty($social_url)) {
                                            $social_url = 'skype:' . $social_url . '?call';
                                            $social['social_profile_type']      = $key;
                                            $social['social_profile_url']       = esc_attr($social_url);
                                            $social['social_profile_icon']      = esc_attr($icon);
                                        }
                                    } else {
                                        $social_url = esc_url($social_url);
                                        $social['social_profile_type']      = $key;
                                        $social['social_profile_url']       = esc_attr($social_url);
                                        $social['social_profile_icon']      = esc_attr($icon);
                                    }
                                    $profil_social_[] = $social;
                                }
                                $profil_social_[] = $social;
                            }
                        }
                        $item['socials_profile'] = $profil_social_;
                    }

                    //Social shares
                    $item['social_shares']  = array();
                    if (function_exists('fw_get_db_post_option')) {
                        $social_facebook    = fw_get_db_settings_option('social_facebook');
                        $social_twitter     = fw_get_db_settings_option('social_twitter');
                        $social_pinterest   = fw_get_db_settings_option('social_pinterest');
                        $social_linkedin    = fw_get_db_settings_option('social_linkedin');
                        $twitter_username   = !empty($social_twitter['enable']['twitter_username']) ? $social_twitter['enable']['twitter_username'] : '';
                    } else {
                        $social_facebook 	= 'enable';
                        $social_twitter 	= 'enable';
                        $social_pinterest 	= 'enable';
                        $social_linkedin 	= 'enable';
                        $twitter_username 	= '';
                    }
                    if (function_exists('fw_get_db_post_option')) {
                        $twitter_username   = !empty($social_twitter['enable']['twitter_username']) ? $social_twitter['enable']['twitter_username'] : '';
                        $hide_hideshares    = fw_get_db_settings_option('hide_hideshares');
                    } else {
                        $twitter_username   = 'twitter';
                    }
                    if(!empty( $hide_hideshares ) && $hide_hideshares === 'no'){
                        $permalink          = get_the_permalink();
                        $title              = get_the_title();
                        $social_share = $social = array();
                        if (isset($social_linkedin) && $social_linkedin == 'enable') {
                            $social_share['social_type']    = 'linkedin';
                            $social_share['social_color']   = '#0077b5';
                            $social_share['social_icon']    = 'fa fa-linkedin';
                            $social_share['social_url']     = 'https://www.linkedin.com/shareArticle?mini=true&url='.urlencode(esc_url($permalink));
                            $social_share['social_title']   = esc_html__("Share on linkedin", "workreap_api");
                            $social[] = $social_share;
                        }
                        if (isset($social_facebook) && $social_facebook == 'enable') {
                            $social_share['social_type']    = 'facebook';
                            $social_share['social_color']   = '#3b5999';
                            $social_share['social_icon']    = 'fa fa-facebook';
                            $social_share['social_url']     = '//www.facebook.com/sharer.php?u='.urlencode(esc_url($permalink));
                            $social_share['social_title']   = esc_html__("Share on Facebook", "workreap_api");
                            $social[] = $social_share;
                        }
                        if (isset($social_twitter['gadget']) && $social_twitter['gadget'] == 'enable') {
                            $social_share['social_type']    = 'twitter';
                            $social_share['social_color']   = '#55acee';
                            $social_share['social_icon']    = 'fa fa-twitter';
                            $social_share['social_url']     = '//twitter.com/intent/tweet?text='.urlencode(esc_url($permalink));
                            $social_share['social_title']   = esc_html__("Share on Twitter", "workreap_api");
                            $social[] = $social_share;
                        }
                        if (isset($social_pinterest) && $social_pinterest == 'enable') {
                            $social_share['social_type']    = 'pinterest';
                            $social_share['social_color']   = '#bd081c';
                            $social_share['social_icon']    = 'fa fa-pinterest-p';
                            $social_share['social_url']     = '//pinterest.com/pin/create/button/?url='.urlencode(esc_url($permalink));
                            $social_share['social_title']   = esc_html__("Share on Pinterest", "workreap_api");
                            $social[] = $social_share;
                        }
                        $item['social_shares']              = $social;
                    }

                    //FAQ's
                    $item['faqs'] = $faqs = array();
                    if (function_exists('fw_get_db_post_option') && !empty($post->ID) ) {
                        $faqs = fw_get_db_post_option($post->ID, 'faq', true);
                    }
                    if( !empty( $faqs ) ) {
                        foreach ( $faqs as $faq ) {
                            if(!empty($faq['faq_question'])) {
                                $single_faq['faq_question']     = esc_html($faq['faq_question']);
                                $single_faq['faq_answer']       = esc_html($faq['faq_answer']);
                            }
                            $single_faq_[] = $single_faq;
                        }
                        $item['faqs'] = $single_faq_;
                    }

                    //profile health
                    $item['profile_health_percent']     = '';
                    $get_profile_data	                = get_post_meta($post->ID, 'profile_strength',true);
                    if(isset($get_profile_data) && $get_profile_data!=''){
                        $total_percentage	            = !empty( $get_profile_data['data'] ) ? array_sum( $get_profile_data['data'] ) : 0;
                        $item['profile_health_percent'] = $total_percentage;
                    }

                    //freelancer report
                    $report_reason['report_title']  = esc_html__('Report this freelancer', 'workreap_api');
                    $report_reason['reasons']     = workreap_get_report_reasons();
                    $item['report_freelancer']      = $report_reason;

										
					//project statastics
					$completed_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $post->ID, 'completed');
					$item['completed_jobs']		= !empty($completed_jobs) ? $completed_jobs : 0;

					$ongoning_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $post->ID, 'hired');
					$item['ongoning_jobs']		= !empty($ongoning_jobs) ? $ongoning_jobs : 0;

					$cancelled_jobs				= workreap_count_posts_by_meta( 'proposals' ,$user_id, '', '', 'cancelled');
					$item['cancelled_jobs']		= !empty($cancelled_jobs) ? $cancelled_jobs : 0;
					
					//Project Reviews
					$reviews		= array();
					$args_reviews	= array(
										'posts_per_page' 	=> -1,
										'post_type' 		=> 'reviews',
										'order' 			=> 'ID',
										'author' 			=> $user_id,
										'suppress_filters' 	=> false
									);
					$query_reviews 	= new WP_Query($args_reviews);
					$count_posts 	= $query_reviews->found_posts;
					
					$count	= 0;
					
					if( $query_reviews->have_posts() ){
						
						while ($query_reviews->have_posts()) : $query_reviews->the_post();
							global $post;
							$count ++;
							$project_id			= get_post_meta($post->ID, '_project_id', true);
							$project_rating		= get_post_meta($post->ID, 'user_rating', true);
							$employer_id		= get_post_field('post_author',$project_id);
							$company_profile 	= workreap_get_linked_profile_id($employer_id);
							$employer_title 	= get_the_title( $company_profile );
							$project_title		= get_the_title($project_id);
							$company_avatar 	= apply_filters(
													'workreap_employer_avatar_fallback', workreap_get_employer_avatar( array( 'width' => 100, 'height' => 100 ), $company_profile ), array( 'width' => 225, 'height' => 225 )
												);
							
							$reviews[$count]['project_title']		= $project_title;
							$reviews[$count]['post_date']			= get_the_date($date_formate,$project_id);
							$reviews[$count]['employer_image']		= $company_avatar;
							$reviews[$count]['_is_verified']		= get_post_meta($company_profile,"_is_verified",true);
							$reviews[$count]['employer_name']		= $employer_title;
						
							if (function_exists('fw_get_db_post_option')) {
								$project_level          	= fw_get_db_post_option($project_id, 'project_level', true);
								$project_level			= !empty($project_level) ? esc_attr($project_level) : '';
								if(!empty($project_level)) {
									$reviews[$count]['level_title']		= workreap_get_project_level($project_level);
									if( $project_level === 'basic' ){
										$reviews[$count]['level_sign']	= 1;
									} elseif( $project_level === 'medium' ){ 
										$reviews[$count]['level_sign']	= 2;
									} elseif( $project_level === 'expensive'){ 
										$reviews[$count]['level_sign']	= 3;
									}
								}
								
							} else {
								$reviews[$count]['level_title']		= '';
								$reviews[$count]['level_sign']		= 0;
							}
						
							$reviews[$count]['project_location']	= get_post_meta($project_id,'_country',true);
						
							$reviews[$count]['project_rating']		= $project_rating;
							$reviews[$count]['review_content']		= get_the_content($post->ID);
							
						endwhile;
						wp_reset_postdata();
					} 
					
                    $item['reviews']	    = array_values($reviews);
                    $item['count_totals']   = !empty($count_post) ? intval($count_post) : 0;
					$items[]			    = maybe_unserialize($item);
				}
                
				return new WP_REST_Response($items, 200);
			}else{
				$json['type']		= 'error';
				$json['message']	= esc_html__('No listing found','workreap_api');
				$items[] = $json;
				return new WP_REST_Response($items, 203);
			} 
        }

    }
}

add_action('rest_api_init',
function () {
	$controller = new AndroidAppGetFreelancersRoutes;
	$controller->register_routes();
});
