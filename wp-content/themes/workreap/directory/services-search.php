<?php
/**
 *
 * Template Name: Search services
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $paged,$query_args,$show_posts,$flag;

do_action('workreap_restict_user_view_search'); //check user restriction

if( apply_filters('workreap_system_access','service_base') === true ){
	$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
	$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

	//paged works on single pages, page - works on homepage
	$paged 		= max($pg_page, $pg_paged);
	$services_search_restrict	= array();
	if(function_exists('fw_get_db_settings_option')){
		$services_per_page 				= fw_get_db_settings_option('services_per_page');
		$services_categories			= fw_get_db_settings_option('services_categories');
		$services_search_restrict 		= fw_get_db_settings_option('services_search_restrict');
	}
	
	$show_posts 			= !empty( $services_per_page ) ? $services_per_page : get_option('posts_per_page');
	$services_categories	= !empty($services_categories) ? $services_categories : 'no';
	
	if( !empty($services_search_restrict['gadget']) && $services_search_restrict['gadget'] == 'disable' && !empty($services_search_restrict['disable']['search_numbers']) && !is_user_logged_in(  ) ){
		$show_posts	= intval($services_search_restrict['disable']['search_numbers']);
	}

	if( !empty($services_categories) && $services_categories === 'no' ) {
		$taxonomy_type	= 'project_cat';
	}else{
		$taxonomy_type	= 'service_categories';
	}
	
	//Search parameters
	$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
	$categories 	= !empty( $_GET['category']) ? $_GET['category'] : array();
	$locations 	 	= !empty( $_GET['location']) ? $_GET['location'] : array();
	$delivery 		= !empty( $_GET['service_duration'] ) ? $_GET['service_duration'] : array();
	$response_time	= !empty( $_GET['response_time'] ) ? $_GET['response_time'] : array();
	$english_level  = !empty( $_GET['english_level'] ) ? $_GET['english_level'] : array();
	$languages 		= !empty( $_GET['language']) ? $_GET['language'] : array();
	$minprice 		= !empty($_GET['minprice']) ? intval($_GET['minprice'] ): 0;
	$maxprice 		= !empty($_GET['maxprice']) ? intval($_GET['maxprice']) : '';
	
	$clearall	= false;
	if( !empty($keyword)
	   || !empty($categories)
	   || !empty($locations)
	   || !empty($delivery)
	   || !empty($response_time)
	   || !empty($english_level)
	   || !empty($languages)
	   || !empty($minprice)
	   || !empty($maxprice)){
		$clearall	= true;
	}
	
	$tax_query_args  = array();
	$meta_query_args = array();
	
	//Category seearch
	if (is_tax('project_cat') ) {
		$sub_cat = $wp_query->get_queried_object();
		if (!empty($sub_cat->slug)) {
			$categories = array($sub_cat->slug);
		}
	} elseif (is_tax('service_categories') ) {
		$sub_cat = $wp_query->get_queried_object();
		if (!empty($sub_cat->slug)) {
			$categories = array($sub_cat->slug);
		}
	}
	
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

	//Delivery
	if ( !empty($delivery[0]) && is_array($delivery) ) {   
		$query_relation = array('relation' => 'OR',);
		$delv_args  	= array();

		foreach( $delivery as $key => $del ){
			$delv_args[] = array(
					'taxonomy' => 'delivery',
					'field'    => 'slug',
					'terms'    => $del,
				);
		}

		$tax_query_args[] = array_merge($query_relation, $delv_args);   
	}

	//Delivery
	if ( !empty($response_time[0]) && is_array($response_time) ) {   
		$query_relation = array('relation' => 'OR',);
		$reponse_args  	= array();

		foreach( $response_time as $key => $res ){
			$reponse_args[] = array(
					'taxonomy' => 'response_time',
					'field'    => 'slug',
					'terms'    => $res,
				);
		}

		$tax_query_args[] = array_merge($query_relation, $reponse_args);   
	}

	//Categories
	if ( !empty($categories[0]) && is_array($categories) ) {   
		$query_relation = array('relation' => 'OR',);
		$category_args  = array();

		foreach( $categories as $key => $cat ){
			$category_args[] = array(
					'taxonomy' => $taxonomy_type,
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

	if (!empty($maxprice)) {
		$price_range 		= array($minprice, $maxprice);
		$meta_query_args[]  = array(
			'key' 			=> '_price',
			'value' 		=> $price_range,
			'type'    		=> 'NUMERIC',
			'compare' 		=> 'BETWEEN'
		);
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
	
	//Main Query
	$query_args = array(
		'posts_per_page' 	  => $show_posts,
		'post_type' 	 	  => 'micro-services',
		'paged' 		 	  => $paged,
		'ignore_sticky_posts' => 1
	);

	//keyword search
	if( !empty($keyword) ){
		add_filter('posts_where','workreap_advance_search_where_freelancers');
		add_filter('posts_join', 'workreap_advance_search_join');
		add_filter('posts_groupby', 'workreap_advance_search_groupby');
	}

	$query_args['orderby']  	= 'ID';
	$query_args['order'] 		= 'DESC';
	
	//order by pro member
	$query_args['meta_key'] = '_featured_service_string';
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
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$query_args['meta_query'] = $meta_query_args;
	}

	$flag 			= rand(9999, 999999);
	$default_view = 'two';
	
	if (function_exists('fw_get_db_post_option')) {
		$services_layout = fw_get_db_settings_option('services_layout');
	}
	
	$services_layout	= !empty( $services_layout ) ? $services_layout : 'two';
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
						<?php get_template_part('directory/front-end/services-layout/services', $services_layout.'-column',array('clearall' => $clearall));?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$script	= "
			var owl_services	= jQuery('.wt-freelancers-services').owlCarousel({
				items: 1,
				loop:false,
				nav:false,
				margin: 0,
				autoplay:false,
				lazyLoad:false,
				rtl: ".workreap_owl_rtl_check().",
				navClass: ['wt-prev', 'wt-next'],
				navContainerClass: 'wt-search-slider-nav',
				navText: ['<span class=\"lnr lnr-chevron-left\"></span>', '<span class=\"lnr lnr-chevron-right\"></span>'],
			});

			setTimeout(function(){owl_services.trigger('refresh.owl.carousel');}, 3000);
			jQuery(window).load(function() {
				owl_services.trigger('refresh.owl.carousel');
				setTimeout(function(){owl_services.trigger('refresh.owl.carousel');}, 2000);
			});
			
			";
	wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
	
}else { ?>
	<div class="container">
	  <div class="wt-haslayout page-data">
		<?php  Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap'));?>
	  </div>
	</div>
<?php
	
}
get_footer();
