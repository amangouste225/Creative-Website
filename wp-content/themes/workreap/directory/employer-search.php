<?php
/**
 *
 * Template Name: Employer Search
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $paged;

$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

do_action('workreap_restict_user_view_search'); //check user restriction

if(function_exists('fw_get_db_settings_option')){
	$employers_per_page 	= fw_get_db_settings_option('employers_per_page');
	$employers_search_restrict 	= fw_get_db_settings_option('employers_search_restrict');
}

$show_posts = !empty( $employers_per_page ) ? $employers_per_page : get_option('posts_per_page');
if( !empty($employers_search_restrict['gadget']) && $employers_search_restrict['gadget'] == 'disable' && !empty($employers_search_restrict['disable']['search_numbers']) && !is_user_logged_in(  ) ){
	$show_posts	= intval($employers_search_restrict['disable']['search_numbers']);
}

//Search parameters
$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
$employees 		= !empty( $_GET['employees']) ? $_GET['employees'] : '';
$departments 	= !empty( $_GET['department']) ? $_GET['department'] : array();
$locations 	 	= !empty( $_GET['location']) ? $_GET['location'] : array();

$clearall	= false;
if( !empty($keyword)
   || !empty($employees)
   || !empty($departments)
   || !empty($locations)
   || !empty($locations)){
	$clearall	= true;
}


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

$meta_query_args[] = array(
	'key' 			=> '_is_verified',
	'value' 		=> 'yes',
	'compare' 		=> '='
); 

$query_args = array(
    'posts_per_page'      => $show_posts,
    'paged'			      => $paged,
    'post_type' 	      => 'employers',
    'post_status'	 	  => 'publish',
    'ignore_sticky_posts' => 1
);

$query_args['orderby']  	= 'ID';
$query_args['order'] 		= 'DESC';

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

$employer_data = new WP_Query($query_args);
$total_posts   = $employer_data->found_posts;

if (function_exists('fw_get_db_post_option')) {
	$hide_departments = fw_get_db_settings_option('hide_departments', $default_value = null);
}

//search page URL
$action_url		= '#';
if( function_exists('workreap_get_search_page_uri') ){
	$action_url		= workreap_get_search_page_uri('employer');
}
?>
<?php 
	if( have_posts() ) {
		while ( have_posts() ) : the_post();
			the_content();
			wp_link_pages( array(
								'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
								'after'       => '</ul></nav></div>',
							) );
		endwhile;
		wp_reset_postdata();
	}
?>
<div class="search-result-template wt-haslayout">
	<div class="wt-haslayout wt-main-empsearch">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
						<aside id="wt-sidebar" class="wt-sidebar">
							<div class="mmobile-floating-apply">
								<span><?php esc_html_e('Apply Filters', 'workreap'); ?></span>
								<i class="fa fa-filter"></i>
							</div>
							<div class="floating-mobile-filter">
								<div class="wt-filter-scroll wt-collapse-filter">
									<?php if(!empty($clearall)){do_action('workreap_clear_all_filters');}?>
									<a class="wt-mobile-close" href="#" onclick="event_preventDefault(event);"><i class="lnr lnr-cross"></i></a>
									<form method="get" name="serach-projects" action="<?php echo esc_url($action_url);?>">
										<h2 class="filter-byhead"><?php esc_html_e('Filter Employers By', 'workreap'); ?></h2>
										<?php do_action('workreap_keyword_search','dnone-search-filter'); ?>
										<?php 
											if( !empty( $hide_departments ) && $hide_departments !== 'site'){
												if( apply_filters('workreap_filter_settings','employer','department') === 'enable' ){do_action('workreap_filter_departments');}
												if( apply_filters('workreap_filter_settings','employer','employees') === 'enable' ){do_action('workreap_filter_no_of_employees');}
											}
										?>
										<?php do_action('workreap_add_custom_filters_employers');?>
										<?php if( apply_filters('workreap_filter_settings','employer','locations') === 'enable' ){do_action('workreap_print_locations');} ?>
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
							<div class="wt-companysinfoholder">
								<div class="row">
									<?php 
									if ($employer_data->have_posts()) {
										while ($employer_data->have_posts()) { 
											$employer_data->the_post();
											global $post;
											$post_id	= $post->ID;
											$linked_profile	= workreap_get_linked_profile_id($post_id, 'post');
											$employer_banner = apply_filters(
												'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 352, 'height' => 200), $post->ID), array('width' => 352, 'height' => 200) 
											);

											$employer_avatar = apply_filters(
												'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $post->ID), array('width' => 100, 'height' => 100) 
											);
											
											if (function_exists('fw_get_db_post_option')) {
												$tag_line      = fw_get_db_post_option($post_id,'tag_line');
											}
											?>
											<div class="col-12 col-sm-12 col-md-12 col-lg-6">
												<div class="wt-companysdetails">
													<figure class="wt-companysimg">
														<img src="<?php echo esc_url($employer_banner); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>">
													</figure>
													<div class="wt-companysinfo">
														<figure>
															<img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>">
															<?php echo do_action('workreap_print_user_status',$linked_profile);?>
														</figure>
														<div class="wt-title emp-title">
															<?php do_action('workreap_get_verification_check',$post_id,esc_html__('Verified Employer','workreap'));?>
															<?php if( !empty( $tag_line ) ){?><h2><a href="<?php echo esc_url(get_the_permalink());?>"><?php echo esc_html(stripslashes($tag_line)); ?></a></h2><?php }?>
														</div>
														<ul class="wt-postarticlemeta">
															<li><a href="<?php echo esc_url(get_the_permalink());?>?#posted-projects"><span><?php esc_html_e('Open Jobs','workreap');?></span></a></li>
															<li><a href="<?php echo esc_url(get_the_permalink());?>"><span><?php esc_html_e('Full Profile','workreap');?></span></a></li>
															<li><?php do_action('workreap_follow_employer_html','v2',$post_id);?></li>
														</ul>
													</div>
												</div>
											</div>
										<?php } wp_reset_postdata(); 

									} else{
										do_action('workreap_empty_records_html','wt-empty-person',esc_html__( 'No employers found.', 'workreap' ));
									}
								?>
								</div>
							</div>
							<?php if( !empty($employers_search_restrict['gadget']) 
								&& $employers_search_restrict['gadget'] == 'disable' 
								&& !empty($employers_search_restrict['disable']['search_numbers']) 
								&& !is_user_logged_in() ){
								do_action( 'workreap_signup_popup_search_results', $employers_search_restrict['disable'] );
							} else {
								if ( !empty($total_posts) && $total_posts > $show_posts ) {?>
									<?php workreap_prepare_pagination($total_posts, $show_posts); ?>
								<?php } ?>	
							<?php } ?>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
 get_footer();
