<?php
/**
 *
 * Template Name: Search project
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $paged,$wp_query;
do_action('workreap_restict_user_view_search'); //check user restriction

if( apply_filters('workreap_system_access','job_base') === true ){
	$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
	$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
	//paged works on single pages, page - works on homepage
	$paged = max($pg_page, $pg_paged);
	$project_search_restrict	= array();
	if(function_exists('fw_get_db_settings_option')){
		$projects_per_page 			= fw_get_db_settings_option('projects_per_page');
		$project_search_restrict 	= fw_get_db_settings_option('project_search_restrict');
		$project_search_status		= fw_get_db_settings_option('project_search_status');
	}
	
	$show_posts = !empty( $projects_per_page ) ? $projects_per_page : get_option('posts_per_page');

	if( !empty($project_search_restrict['gadget']) && $project_search_restrict['gadget'] == 'disable' && !empty($project_search_restrict['disable']['search_numbers']) && !is_user_logged_in(  ) ){
		$show_posts	= intval($project_search_restrict['disable']['search_numbers']);
	}
	$freelancertype	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$freelancertype	= fw_get_db_settings_option('job_freelancer_type', $default_value = null);
	}

	$job_option_type	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$job_option_type	= fw_get_db_settings_option('job_option', $default_value = null);
	}

	//multiselect_freelancertype
	$job_price_option	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$job_price_option	= fw_get_db_settings_option('job_price_option', $default_value = null);
	}
		
	//Search parameters
	$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
	$languages 		= !empty( $_GET['language']) ? $_GET['language'] : array();
	$experiences 	= !empty( $_GET['experience']) ? $_GET['experience'] : array();
	$categories 	= !empty( $_GET['category']) ? $_GET['category'] : array();
	$locations 	 	= !empty( $_GET['location']) ? $_GET['location'] : array();
	$skills			= !empty( $_GET['skills']) ? $_GET['skills'] : array();
	$duration 		= !empty( $_GET['duration'] ) ? $_GET['duration'] : '';
	$type 			= !empty( $_GET['type'] ) ? $_GET['type'] : array();
	$option 		= !empty( $_GET['option'] ) ? $_GET['option'] : array();
	$project_type	= !empty( $_GET['project_type'] ) ? $_GET['project_type'] : '';
	$english_level  = !empty( $_GET['english_level'] ) ? $_GET['english_level'] : array();
	$minprice 		= !empty( $_GET['minprice']) ? intval($_GET['minprice'] ): 1;
	$maxprice 		= !empty( $_GET['maxprice']) ? intval($_GET['maxprice']) : '';
	
	$clearall	= false;
	if( !empty($keyword)
	   || !empty($languages)
	   || !empty($experiences)
	   || !empty($categories)
	   || !empty($locations)
	   || !empty($skills)
	   || !empty($duration)
	   || !empty($type)
	   || !empty($option)
	   || !empty($project_type)
	   || !empty($english_level)
	   || !empty($maxprice)){
		$clearall	= true;
	}
	
	$tax_query_args  = array();
	$meta_query_args = array();

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
	
	//cat page
	if (is_tax('project_cat') && empty( $categories )) {
		$cat = $wp_query->get_queried_object();
		if (!empty($cat->slug)) {
			$categories = array($cat->slug);
		}
	}
	
	//skills page
	if (is_tax('skills') && empty( $skills )) {
		$skill = $wp_query->get_queried_object();
		if (!empty($skill->slug)) {
			$skills = array($skill->slug);
		}
	}
	
	//skills page
	if (is_tax('locations') && empty( $locations )) {
		$location = $wp_query->get_queried_object();
		if (!empty($location->slug)) {
			$locations = array($location->slug);
		}
	}
	
	//skills page
	if (is_tax('languages') && empty( $languages )) {
		$language = $wp_query->get_queried_object();
		if (!empty($language->slug)) {
			$languages = array($language->slug);
		}
	}
	
	//Categories
	if ( !empty($categories[0]) && is_array($categories) ) {   
		$query_relation = array('relation' => 'OR',);
		$category_args  = array();

		foreach( $categories as $key => $cat ){
			$category_args[] = array(
					'taxonomy' => 'project_cat',
					'field'    => 'slug',
					'terms'    => $cat,
				);
		}

		$tax_query_args[] = array_merge($query_relation, $category_args);
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

	//skills
	if ( !empty($experiences[0]) && is_array($experiences) ) {    
		$query_relation = array('relation' => 'OR',);
		$experiences_args  = array();

		foreach( $experiences as $key => $experience ){
			$experiences_args[] = array(
					'taxonomy' => 'project_experience',
					'field'    => 'slug',
					'terms'    => $experience,
				);
		}
		$tax_query_args[] = array_merge($query_relation, $experiences_args);   
	}

	if(!empty($freelancertype) && $freelancertype === 'enable'){
		$optin_select	= 'multiselect';
		
		//Freelancer type Level
		if(!empty($optin_select) && $optin_select === 'multiselect' && !empty( $type ) ){
			if (!empty($type) && !empty($type[0]) && is_array($type)) {
		
				$query_relation = array('relation' => 'OR',);
				$sub_types_args = array();
				foreach ($type as $key => $value) {
					$sub_types_args[] = array(
						'key' 		=> '_freelancer_level',
						'value' 	=> strval($value),
						'compare' 	=> 'LIKE'
					);
				}
				$meta_query_args[] = array_merge($query_relation, $sub_types_args);
			}
		} else if ( !empty( $type ) ) {    
			$meta_query_args[] = array(
				'key' 		=> '_freelancer_level',
				'value' 	=> $type,
				'compare' 	=> 'IN'
			);    
		}
	}

	//Job option Level
	if ( !empty( $option ) ) {    
		$meta_query_args[] = array(
			'key' 		=> '_job_option',
			'value' 	=> $option,
			'compare' 	=> 'IN'
		);    
	}

	//Duration
	if ( !empty( $duration ) ) {    
		$duration_args[] = array(
			'key'		=> '_project_duration',
			'value' 	=> $duration,
			'compare' 	=> 'IN'
		);    

		$meta_query_args = array_merge($meta_query_args, $duration_args);
	}
	
	//English Level
	if ( !empty( $english_level ) ) {
		$query_relation = array('relation' => 'OR',);
		$english_level_args = array();
		foreach ($english_level as $key => $value) {
			$english_level_args[] = array(
				'key' 		=> '_english_level',
				'value' 	=> $value,
				'compare' 	=> 'LIKE'
			);
		}


		$meta_query_args[] = array_merge($query_relation, $english_level_args);  
	}
	
	//Radius Search
	if ( !empty($_GET['geo']) ) {

		$Latitude 	= '';
		$Longitude 	= '';
		$prepAddr 	= '';
		$minLat 	= '';
		$maxLat 	= '';
		$minLong 	= '';
		$maxLong 	= '';

		$address = sanitize_text_field($_GET['geo']);
		$prepAddr = str_replace(' ', '+', $address);


		if (isset($_GET['geo_distance']) && !empty($_GET['geo_distance'])) {
			$radius = $_GET['geo_distance'];
		} else {
			$radius = 300;
		}

		//Distance in miles or kilometers
		if (function_exists('fw_get_db_settings_option')) {
			$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
		} else {
			$dir_distance_type = 'mi';
		}

		if ($dir_distance_type === 'km') {
			$radius = $radius * 0.621371;
		}

		$Latitude	= isset( $_GET['lat'] ) ? esc_attr( $_GET['lat'] ) : '';
		$Longitude	= isset( $_GET['long'] ) ? esc_attr( $_GET['long'] ) : '';

		if( !empty( $Latitude ) && !empty( $Longitude ) ){
			$Latitude	 = $Latitude;
			$Longitude   = $Longitude;

		} else{
			$args = array(
				'timeout'     => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
			);

			$url	    = 'https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key='.$google_key;;
			$response   = wp_remote_get( $url, $args );
			$geocode	= wp_remote_retrieve_body($response);

			$output	  = json_decode($geocode);

			if( isset( $output->results ) && !empty( $output->results ) ) {
				$Latitude	 = $output->results[0]->geometry->location->lat;
				$Longitude   = $output->results[0]->geometry->location->lng;
			}
		}

		if( !empty( $Latitude ) && !empty( $Longitude ) ){
			$zcdRadius  = new RadiusCheck($Latitude, $Longitude, $radius);
			$minLat 	= $zcdRadius->MinLatitude();
			$maxLat 	= $zcdRadius->MaxLatitude();
			$minLong 	= $zcdRadius->MinLongitude();
			$maxLong 	= $zcdRadius->MaxLongitude();

			$meta_query_args = array(
				'relation' => 'AND',
				array(
					'key' 		=> '_latitude',
					'value' 	=> array($minLat, $maxLat),
					'compare' 	=> 'BETWEEN',
					'type' 		=> 'DECIMAL(20,10)',
				),
				array(
					'key' 		=> '_longitude',
					'value' 	=> array($minLong, $maxLong),
					'compare' 	=> 'BETWEEN',
					'type' 		=> 'DECIMAL(20,10)',
				)
			);

			if (isset($query_args['meta_query']) && !empty($query_args['meta_query'])) {
				$meta_query = array_merge($meta_query_args, $query_args['meta_query']);
			} else {
				$meta_query = $meta_query_args;
			}

			$query_args['meta_query'] = $meta_query;
		}
	}
	
	//Project Type
	if ( !empty( $project_type ) && ( $project_type === 'hourly' || $project_type === 'fixed' ) ) {   
		$meta_query_args[] = array(
			'key' 			=> '_project_type',
			'value' 		=> $project_type,
			'compare' 		=> '='
		);  
		
	}
	
	//Hourly Rate
	if( !empty( $project_type ) &&  $project_type === 'hourly' && !empty( $maxprice ) ) {
		$range_array 		= array($minprice, $maxprice);
		$price_args = array();
		if(!empty($job_price_option) && $job_price_option === 'enable') {
			$query_relation = array('relation' => 'OR',);
			
			$price_args[]  = array(
				'key' 			=> '_project_cost',
				'value' 		=> $range_array,
				'type'    		=> 'NUMERIC',
				'compare' 		=> 'BETWEEN'
			);
			
			$price_args[] = array(
					'key'     => '_max_price',
					'value'   => $range_array,
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				); 
			$meta_query_args[] = array_merge($query_relation, $price_args);
		} else {
			if( !empty( $range_array ) ) {
				$meta_query_args[] = array(
					'key'     => '_hourly_rate',
					'value'   => $range_array,
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				);  
			}
		}
	} else if( !empty( $project_type ) &&  $project_type === 'fixed' && !empty( $maxprice ) ) {
		$price_range 		= array($minprice, $maxprice);
		$price_args 		= array();
		
		if(!empty($job_price_option) && $job_price_option === 'enable') {
			$query_relation = array('relation' => 'OR',);
			$price_args[]  = array(
				'key' 			=> '_project_cost',
				'value' 		=> $price_range,
				'type'    		=> 'NUMERIC',
				'compare' 		=> 'BETWEEN'
			);
			
			$price_args[] = array(
					'key'     => '_max_price',
					'value'   => $price_range,
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				);
			$meta_query_args[] = array_merge($query_relation, $price_args);
		} else {
			$meta_query_args[]  = array(
				'key' 			=> '_project_cost',
				'value' 		=> $price_range,
				'type'    		=> 'NUMERIC',
				'compare' 		=> 'BETWEEN'
			);
		}
	} else if( !empty( $maxprice ) ) {
		$price_range 		= array($minprice, $maxprice);
		$price_args 		= array();
		
		if(!empty($job_price_option) && $job_price_option === 'enable') {
			$query_relation = array('relation' => 'OR',);
			$price_args[]  = array(
				'key' 			=> '_project_cost',
				'value' 		=> $price_range,
				'type'    		=> 'NUMERIC',
				'compare' 		=> 'BETWEEN'
			);
			
			$price_args[] = array(
					'key'     => '_max_price',
					'value'   => $price_range,
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				);
			$meta_query_args[] = array_merge($query_relation, $price_args);
		} else {
			$meta_query_args[]  = array(
				'key' 			=> '_project_cost',
				'value' 		=> $price_range,
				'type'    		=> 'NUMERIC',
				'compare' 		=> 'BETWEEN'
			);
		}
	}
	
	$project_search_status = !empty($project_search_status) ? $project_search_status : array('publish');
	
	//Main Query
	$query_args = array(
		'posts_per_page' 	  => $show_posts,
		'post_type' 	 	  => 'projects',
		'paged' 		 	  => $paged,
		'post_status' 	 	  => $project_search_status,
		'ignore_sticky_posts' => 1
	);

	//order by pro 
	$query_args['meta_key'] = '_featured_job_string';
	$query_args['orderby']	 = array(
		'meta_value' 	=> 'DESC', 
		'ID'      		=> 'DESC',
    ); 
		
	//keyword search
	if( !empty($keyword) ){
		add_filter('posts_where','workreap_advance_search_where_freelancers');
		add_filter('posts_join', 'workreap_advance_search_join');
		add_filter('posts_groupby', 'workreap_advance_search_groupby');
	}

	
	//Meta Query
	if (!empty($meta_query_args)) {
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$query_args['meta_query'] = $meta_query_args;
	}

	//Taxonomy Query
	if ( !empty( $tax_query_args ) ) {
		$query_relation = array('relation' => 'AND',);
		$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
	}

	$project_posts = new WP_Query($query_args); 
	$total_posts   = $project_posts->found_posts;
	$direction		= workreap_get_location_lat_long();

	if( function_exists('fw_get_db_post_option') ){
		$marker_default  = fw_get_db_settings_option('dir_map_marker');
		$freelancer_locations = fw_get_db_settings_option('freelancer_locations');
		$search_page_map = fw_get_db_settings_option('search_page_map');
	}
	
	//search page URL
	$action_url		= '#';
	if( function_exists('workreap_get_search_page_uri') ){
		$action_url		= workreap_get_search_page_uri('jobs');
	}
	?>
	
	<?php if( have_posts() & !is_tax() ) {?>
	<div class="wt-haslayout wt-haslayout page-data">
		<?php 
			while ( have_posts() ) : the_post();
				the_content();
				wp_link_pages( array(
									'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
									'after'       => '</ul></nav></div>',
								) );
			endwhile;
			wp_reset_postdata();
		?>
	</div>
	<?php }?>
	<div class="search-result-template wt-haslayout">
		<div class="wt-haslayout wt-job-search">
			<div class="container">
				<div class="row">
					<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
						<?php do_action('workreap_get_search_toggle_map','wt-mapvone'); ?>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
							<aside id="wt-sidebar" class="wt-sidebar">
								<div class="mmobile-floating-apply">
									<span><?php esc_html_e('Search and apply filters', 'workreap'); ?></span>
									<i class="fa fa-filter"></i>
								</div>
								<div class="floating-mobile-filter">
									<div class="wt-filter-scroll wt-collapse-filter">
										<?php if(!empty($clearall)){do_action('workreap_clear_all_filters');}?>
										<a class="wt-mobile-close" href="#" onclick="event_preventDefault(event);"><i class="lnr lnr-cross"></i></a>
										<form method="get" id="serach-projects" name="serach-projects" action="<?php echo esc_url($action_url);?>">
											<h2 class="filter-byhead"><?php esc_html_e('Filter Project By', 'workreap'); ?></h2>
											<?php do_action('workreap_geoloacation_search'); ?>
											<?php do_action('workreap_keyword_search','dnone-search-filter'); ?>
											<?php 
												if(!empty($job_option_type) && $job_option_type === 'enable' ){
													if( apply_filters('workreap_filter_settings','job','option_type') === 'enable' ){do_action('workreap_print_project_option', esc_html__('Job type', 'workreap') );} 
												}
											?>
											<?php if( apply_filters('workreap_filter_settings','job','english_level') === 'enable' ){do_action('workreap_print_freelancer_english_level');}?>
											<?php if( apply_filters('workreap_filter_settings','job','type') === 'enable' ){do_action('workreap_print_jobs_price_range');} ?>
											<?php if( apply_filters('workreap_filter_settings','job','length') === 'enable' ){do_action('workreap_print_project_time_html');} ?>
											<?php if( apply_filters('workreap_filter_settings','job','freelancer_type') === 'enable' ){do_action('workreap_print_project_freelancer_type', esc_html__('Freelancer type', 'workreap') );} ?>
											<?php if( apply_filters('workreap_filter_settings','job','exprience_type') === 'enable' ){do_action('workreap_job_exprience');} ?>	
											<?php if( apply_filters('workreap_filter_settings','job','categories') === 'enable' ){do_action('workreap_print_categories');} ?>
											<?php if( apply_filters('workreap_filter_settings','job','skills') === 'enable' ){do_action('workreap_filter_skills');} ?>
											<?php if( apply_filters('workreap_filter_settings','job','locations') === 'enable' ){do_action('workreap_print_locations');} ?>
											<?php if( apply_filters('workreap_filter_settings','job','languages') === 'enable' ){do_action('workreap_print_languages');} ?>	
											<?php do_action('workreap_add_custom_filters_jobs');?>
											<div class="wt-widget wt-effectiveholder">
												<div class="wt-widgetcontent">
													<div class="wt-applyfilters">
														<span><?php esc_html_e('Click “Apply Filter” to apply latest changes made by you.', 'workreap'); ?></span>
														<input type="submit" class="wt-btn" value="<?php esc_attr_e('Apply Filters', 'workreap'); ?>">
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</aside>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
							<div class="wt-userlistingholder wt-haslayout">
							<?php 
	
							$project_data		=  array();
							$project_content	=  array();
							$project_content['status'] = 'none';
							$project_content['lat']  = floatval ( $direction['lat'] );
							$project_content['long'] = floatval ( $direction['long'] );
	
							if ($project_posts->have_posts()) {
								$project_content['status'] = 'found';
								
								while ($project_posts->have_posts()) { 
									$project_posts->the_post();
									global $post;
									$author_id 		= get_the_author_meta( 'ID' );  
									$linked_profile = workreap_get_linked_profile_id($author_id);
									$employer_title = esc_html( get_the_title( $linked_profile ));	
									$classFeatured	= apply_filters('workreap_project_print_featured', $post->ID,'yes');


									if (function_exists('fw_get_db_post_option')) {
										$db_project_type      = fw_get_db_post_option($post->ID,'project_type');
									}

									$project_price	= workreap_project_price($post->ID);
									$project_cost	= !empty($project_price['cost']) ? $project_price['cost'] : 0;
									$job_type_text	= !empty($project_price['text']) ? $project_price['text'] : '';

									$project_data['latitude'] 		= get_post_meta($post->ID, '_latitude', true);
									$project_data['longitude'] 		= get_post_meta($post->ID, '_longitude', true);
									$project_data['project_name'] 	= get_the_title();
									$featured_val	= get_post_meta($post->ID, '_featured_job_string', true);

									$infoBox = '';
									$infoBox .= '<div class="wt-infoBox">';
										$infoBox .= '<div class="wt-serviceprovider">';
											$infoBox .= '<div class="wt-mapcompanycontent">';
												$infoBox .= '<div class="wt-title">';
													$infoBox .= '<h3><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
													$infoBox .= '<div class="wt-viewjobholder"><ul>';
													$infoBox .= '<li><span class="wt-budget"><i class="fa fa-money wt-viewjobtag"></i>'.esc_html__( "Budget", "workreap" ).':&nbsp;<em>'. $project_cost.'</em></span></li>';
													$infoBox .= '<li class="wt-btnarea"><a href="'.esc_url( get_the_permalink() ).'" class="wt-btn">'.esc_html__( 'View Job', 'workreap' ).'</a></li>';
													$infoBox .= '</div></ul>';
												$infoBox .= '</div>';
											$infoBox .= '</div>';
										$infoBox .= '</div>';
									$infoBox .= '</div>';

									if (!empty($marker_default['url'])) {
										$project_data['icon'] = $marker_default['url'];
									} else {
										$project_data['icon'] = get_template_directory_uri() . '/images/marker.png';
									}

									$project_data['html']['content'] = $infoBox;
									$project_content['project_list'][] 	 = $project_data;
									?>
									
									<div class="wt-userlistinghold wtget_url <?php echo esc_attr($classFeatured);?> wt-userlistingholdvtwo" data-url="<?php echo esc_url( get_the_permalink() ); ?>">	
										<div class="wt-userlistingcontent">
											<?php do_action('workreap_project_print_featured', $post->ID); ?>
											<div class="wt-contenthead">
												<div class="wt-title">
													<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
													<h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
												</div>
												<?php do_action( 'workreap_job_short_detail', $post->ID ) ?>
												<div class="wt-description">
													<p><?php echo wp_trim_words( stripslashes( get_the_excerpt() ), 30 ); ?></p>
												</div>
												<?php do_action( 'workreap_print_skills_html', $post->ID );?>										
											</div>
											<div class="wt-viewjobholder">
												<ul>
													<?php do_action('workreap_project_print_project_level', $post->ID); ?>
													<?php do_action('workreap_print_project_duration_html', $post->ID);?>
													<?php if(!empty($job_option_type) && $job_option_type === 'enable' ){do_action('workreap_print_project_option_type', $post->ID); }?>
													<?php do_action('workreap_print_project_type', $post->ID); ?>
													<?php do_action('workreap_print_project_date', $post->ID);?>
													<?php do_action('workreap_print_location', $post->ID); ?>
													<li><?php  do_action('workreap_save_project_html', $post->ID, 'v2'); ?></li>
													<li class="wt-btnarea"><a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wt-btn"><?php esc_html_e( 'View Job', 'workreap' ) ?></a></li>
												</ul>
											</div>
										</div>
									</div>
								<?php } wp_reset_postdata();

								} else{
									do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No projects found.', 'workreap' ));
								}
	
								if( !empty($project_search_restrict['gadget']) 
								   && $project_search_restrict['gadget'] == 'disable' 
								   && !empty($project_search_restrict['disable']['search_numbers']) 
								   && !is_user_logged_in(  ) ){
									do_action( 'workreap_signup_popup_search_results', $project_search_restrict['disable'] );
								} else {
									if (!empty($total_posts) && $total_posts > $show_posts) {?>
										<?php workreap_prepare_pagination($total_posts, $show_posts); ?>
									<?php } ?>
								<?php } ?>
								<?php if (isset($search_page_map) && $search_page_map === 'enable') { 
										$script	= "jQuery(document).ready(function ($) {workreap_init_map_script(".json_encode($project_content)."); });";
										wp_add_inline_script('workreap-gmaps', $script,'after');
								} ?> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }else { ?>
	<div class="container">
	  <div class="wt-haslayout page-data">
		<?php  Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You are not allowed to see results as admin has selected services base directory type. Please contact to administrator', 'workreap'));?>
	  </div>
	</div>
<?php	
}

get_footer();
