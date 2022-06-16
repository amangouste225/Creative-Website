<?php
/**
 *
 * Service two column layout
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $paged, $query_args, $show_posts,$flag,$wp_query;
$service_data = new WP_Query($query_args); 
$total_posts  = $service_data->found_posts;
$clearall 		=  !empty($args['clearall']) ? $args['clearall'] : '';
$width			= 352;
$height			= 200;
$service_video_option		= '';
$service_restrict	= array();
if ( function_exists('fw_get_db_post_option' )) {
	$default_service_banner    		= fw_get_db_settings_option('default_service_banner');
	$service_restrict 		= fw_get_db_settings_option('services_search_restrict');
	$service_video_option 			= fw_get_db_settings_option('service_video_option');
}

//search page URL
$action_url		= '#';
if( function_exists('workreap_get_search_page_uri') ){
	$action_url		= workreap_get_search_page_uri('services');
}
?>
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
				<form method="get" name="serach-projects" class="services-two-column" action="<?php echo esc_url($action_url);?>">
					<h2 class="filter-byhead"><?php esc_html_e('Filter Services By', 'workreap'); ?></h2>
					<?php do_action('workreap_keyword_search'); ?>
					<?php do_action('workreap_print_service_categories'); ?>
					<?php if( apply_filters('workreap_filter_settings','services','price') === 'enable' ){do_action('workreap_print_price_range');} ?>
					<?php if( apply_filters('workreap_filter_settings','services','locations') === 'enable' ){do_action('workreap_print_locations');} ?>
					<?php if( apply_filters('workreap_filter_settings','services','dilivery') === 'enable' ){do_action('workreap_print_service_duration');} ?>
					<?php if( apply_filters('workreap_filter_settings','services','response') === 'enable' ){do_action('workreap_print_response_time');}?>
					<?php if( apply_filters('workreap_filter_settings','services','languages') === 'enable' ){do_action('workreap_print_languages');} ?>
					<?php if( apply_filters('workreap_filter_settings','services','english_level') === 'enable' ){do_action('workreap_print_freelancer_english_level');}?>
					<?php do_action('workreap_add_custom_filters_services');?>
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
<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
	<?php if ($service_data->have_posts()) {?>
	<div class="row">
		<div class="wt-freelancers-holder two-column-holder">
			<?php 
				while ($service_data->have_posts()) { 
					$service_data->the_post();
					global $post;
					$random = rand(1,9999);
					$author_id 			= get_the_author_meta( 'ID' );  
					$linked_profile 	= workreap_get_linked_profile_id($author_id);	
					$service_url		= get_the_permalink();
					$db_docs			= array();
					$db_videos			= array();
					$delivery_time		= '';
					$order_details		= '';

					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID,'docs');
						$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
						$order_details   	= fw_get_db_post_option($post->ID,'order_details');
						$db_videos   		= fw_get_db_post_option($post->ID,'videos');
					}

					//default banner set
					if( empty($db_docs) && !empty($default_service_banner) ){
						$db_docs[0]	= $default_service_banner;
					}

					$images_count	= !empty($db_docs) && is_array($db_docs) ? count($db_docs) : 0;
					if( empty($images_count) ){
						$images_count	= !empty($db_videos) && is_array($db_videos) ? count($db_videos) : 0;
					}

					$is_featured		= workreap_service_print_featured( $post->ID, 'yes');
					$is_featured    	= !empty( $is_featured ) ? 'wt-featured-service' : '';
					
					if( empty($db_docs) ) {
						$empty_image_class	= 'wt-empty-service-image';
					} else {
						$empty_image_class	= '';
					}

					$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
					wp_add_inline_script( 'splide', $script, 'after' );
				?>
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 wt-services-grid">
					<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?> <?php echo esc_attr( $is_featured );?>">
						<?php if( !empty( $images_count ) ) {?>
							<div class="wtsplide-wrapper wtsplide-<?php echo esc_attr($random);?> wt-freelancers">
								<div class="splide__track">
									<div class="splide__list">
										<?php
											if( !empty($service_video_option) && $service_video_option == 'yes' ){
												do_action( 'workreap_services_videos', $post->ID ,$width,$height);
											}
										?>
										<?php
											if( !empty($db_docs) ){
												foreach( $db_docs as $key => $doc ){
													$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
													$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
													if (strpos($thumbnail,'media/default.png') === false) {?>
													<div class="splide__slide"><figure class="item">
														<a href="<?php echo esc_url( $service_url );?>">
															<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service','workreap');?>" class="item">
														</a>
													</figure></div>
											<?php } 
											}
										}?>
									</div>
								</div>
							</div>
						<?php } ?>
						<?php do_action('workreap_service_print_featured', $post->ID); ?>
						<?php do_action('workreap_service_shortdescription', $post->ID,$linked_profile); ?>
						<?php do_action('workreap_service_type_html',$post->ID);?>
					</div>
				</div>
			<?php } wp_reset_postdata();?>
		</div>
		<?php 
			if( !empty($service_restrict['gadget']) 
			   && $service_restrict['gadget'] == 'disable' 
			   &&  !empty($service_restrict['disable']['search_numbers']) 
			   && !is_user_logged_in() ){
				do_action( 'workreap_signup_popup_search_results', $service_restrict['disable'] );
			} else {
				if (!empty($total_posts) && $total_posts > $show_posts) {?>
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 wp-pagination wt-service-pagination float-left">
					<?php workreap_prepare_pagination($total_posts, $show_posts); ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<?php } else{
		do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No service found.', 'workreap' ));
	}?>
</div>