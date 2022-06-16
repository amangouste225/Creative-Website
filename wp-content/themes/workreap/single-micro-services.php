<?php
/**
 *
 * The template used for displaying single service
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
global $post;
do_action('workreap_post_views', $post->ID,'services_views');
get_header();

do_action('workreap_restict_user_view_search'); //check user restriction

if( function_exists('fw_get_db_settings_option')  ){
	$login_register = fw_get_db_settings_option('enable_login_register');
}

$login_page	= '';
if (!empty($login_register['enable']['login_page'][0]) && !empty($login_register['enable']['login_signup_type']) && $login_register['enable']['login_signup_type'] == 'pages' ) {
	$login_page = get_the_permalink($login_register['enable']['login_page'][0]);
}

if( apply_filters('workreap_system_access','service_base') === true ){
	while ( have_posts() ) {
		the_post();
		global $post;
		
		$services_views_count   = get_post_meta($post->ID, 'services_views', true);
		$author_id 				= get_the_author_meta( 'ID' );  
		$linked_profile 		= workreap_get_linked_profile_id($author_id);
		$user_link				= !empty($linked_profile) ? get_the_permalink( $linked_profile ) : '';
		$freelancer_title 		= !empty($linked_profile) ? get_the_title( $linked_profile ) :'';	
		$service_url			= get_the_permalink();
		
		$post_name				= !empty($linked_profile) ? workreap_get_slug( $linked_profile ) : '';
		$english_level      	= worktic_english_level_list();
			
		$service_faq_option     = '';
		if(function_exists('fw_get_db_settings_option')){
			$services_categories	= fw_get_db_settings_option('services_categories');
			$default_service_banner = fw_get_db_settings_option('default_service_banner');
			$service_faq_option		= fw_get_db_settings_option('service_faq_option', $default_value = null);
			$show_project_map  		= fw_get_db_settings_option('show_service_map');
			$remove_service_languages		= fw_get_db_settings_option('remove_service_languages');
		}

		$services_categories	= !empty($services_categories) ? $services_categories : 'no';
		$remove_service_languages		= !empty($remove_service_languages) ? $remove_service_languages : 'no';
		
		if( !empty($services_categories) && $services_categories === 'no' ) {
			$taxonomy_type	= 'project_cat';
		}else{
			$taxonomy_type	= 'service_categories';
		}
			
		$db_project_cat 		= wp_get_post_terms($post->ID, $taxonomy_type);
		$db_delivery_time 		= wp_get_post_terms($post->ID, 'delivery');
		$db_response_time 		= wp_get_post_terms($post->ID, 'response_time');
		
		$db_addons				= get_post_meta($post->ID,'_addons',true);
		$db_addons				= !empty( $db_addons ) ? $db_addons : array();
		
		$queu_services			= workreap_get_services_count('services-orders',array('hired'),$post->ID);
		$completed_services		= workreap_get_services_count('services-orders',array('completed'),$post->ID);
		$completed_services		= !empty( $completed_services ) ? $completed_services : 0;
		
		$service_views			= get_post_meta($post->ID,'services_views',true);
		$service_views			= !empty( $service_views ) ? $service_views : 0;
		$db_docs			= array();
		$db_price			= '';
		$order_details		= '';
		$service_map		= 'on';
				
		if (function_exists('fw_get_db_post_option')) {
			$db_docs   			= fw_get_db_post_option($post->ID,'docs');
			$order_details   	= fw_get_db_post_option($post->ID,'order_details');
			$db_price   		= fw_get_db_post_option($post->ID,'price');
			$db_videos   		= fw_get_db_post_option($post->ID,'videos');
			$db_downloadable   	= fw_get_db_post_option($post->ID,'downloadable');
			$address   		= fw_get_db_post_option($post->ID, 'address', true);
			$longitude   	= fw_get_db_post_option($post->ID, 'longitude', true);
			$latitude   	= fw_get_db_post_option($post->ID, 'latitude', true);
			$service_map 	= fw_get_db_post_option($post->ID, 'service_map', true);	
			$db_english_level   = fw_get_db_post_option($post->ID,'english_level');
		}
		
		//default banner set
		if( empty($db_docs) && !empty($default_service_banner) ){
			$db_docs[0]	= $default_service_banner;
		}
			
		$db_downloadable	= !empty( $db_downloadable ) && $db_downloadable !== 'no' ? $db_downloadable : '';
		
		if(!empty($linked_profile)){
			$freelancer_avatar = apply_filters(
			'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
		);
		
			$freelancer_banner = apply_filters(
										'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner(array('width' => 352, 'height' => 200), $linked_profile), array('width' => 352, 'height' => 200) 
									);
		}
		
		$width			= 100;
		$height			= 100;
		
		$full_width			= 714;
		$full_height		= 410;
		
		$flag 				= rand(9999, 999999);
		$slider_images		= count( $db_docs );
		$slider_videos		= !empty( $db_videos ) && is_array($db_videos) ? count( $db_videos ) : 0;
		$slider_images		= intval($slider_videos) + intval($slider_images);
			
		if( $slider_images > 1 ){
			$owl_class	='owl-carousel';
		} else {
			$owl_class	='';
		}
			
		$search_url			= '';
		if( function_exists('workreap_get_search_page_uri') ){
			$search_url			= workreap_get_search_page_uri('services');
		}
			
		$full_images	= '';

		$total_count		= 0;
	?>
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
						<div class="wt-usersingle wt-servicesingle-holder">
							<div class="wt-servicesingle">
								<?php do_action('workreap_service_print_featured', $post->ID); ?>
								<div class="wt-servicesingle-title">
									<?php if( !empty( $db_project_cat ) ){?>
										<div class="wt-service-tag">
											<?php foreach ( $db_project_cat as $cat ) {?>
												<a href="<?php echo esc_url($search_url);?>?category[]=<?php echo esc_html($cat->slug);?>"><?php echo esc_html($cat->name);?></a>
											<?php }?>
										</div>
									<?php  }?>
									<div class="wt-title">
										<h1><?php the_title();?></h1>
									</div>
									<ul class="wt-userlisting-breadcrumb">
										<?php do_action('workreap_service_get_reviews',$post->ID,'v1');?>
										<?php do_action('workreap_save_services_html',$post->ID);?>
									</ul>
								</div>
								<?php if( !empty( $db_docs ) || !empty( $db_videos ) ) {?>
									<div class="wt-freelancers-info">
										<div id="wt-splide" class="wt-servicesslider wt-splide splide">
											<div class="splide__track">
												<ul class="splide__list">
												<?php if( !empty( $db_videos ) ){
													foreach( $db_videos as $key => $vid ){
														$total_count++;
														if(!empty($vid)){
															$full_images	.= '<li class="splide__slide"><figure><a class="wt-video-icon"><i class="fa fa-play"></i></a></figure></li>';
														?>
														<li class="splide__slide">
															<?php
																$vid_width		= 714;
																$vid_height		= 402;
																$url 			= parse_url( $vid );

																if ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com') {
																	echo '<figure class="wt-classimg wt-media-single">';
																	$content_exp  = explode("/" , $vid);
																	$content_vimo = array_pop($content_exp);
																	echo '<iframe width="' . esc_attr( $vid_width ) . '" height="' . esc_attr( $vid_height ) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
																></iframe>';
																	echo '</figure>';
																} elseif ($url['host'] == 'soundcloud.com') {
																	$video  = wp_oembed_get($vid , array (
																		'height' => $vid_height ));
																	$search = array (
																		'webkitallowfullscreen' ,
																		'mozallowfullscreen' ,
																		'frameborder="0"' );
																	echo '<figure class="wt-classimg wt-media-single">';
																	echo str_replace($search , '' , $video);
																	echo '</figure>';
																} else if($url['host'] == 'youtu.be') {
																	$path	= str_replace('/','',$url['path']);
																	echo '<figure class="wt-classimg wt-media-single">';
																	echo preg_replace(
																		"/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
																		"<iframe width='" . esc_attr( $vid_width ) ."' height='" . esc_attr( $vid_height ) . "' src=\"//www.youtube.com/embed/$2\" frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",
																		$vid
																	);
																	echo '</figure>';
																} else {
																	echo '<figure class="wt-classimg wt-media-single">';
																	$content = str_replace(array (
																		'watch?v=' ,
																		'http://www.dailymotion.com/' ) , array (
																		'embed/' ,
																		'//www.dailymotion.com/embed/' ) , $vid);
																	echo '<iframe width="' . esc_attr( $vid_width ) . '" height="' . esc_attr( $vid_height ) . '" src="' . esc_url( $content ) . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
																	echo '</figure>';
																}
															?>
														</li>
													<?php }}}?>
													<?php
														foreach( $db_docs as $key => $doc ){
															$total_count++;
															$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
															$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
															$full_thumbnail = workreap_prepare_image_source($attachment_id, $full_width, $full_height);
															$full_pic 		= workreap_prepare_image_source($attachment_id, 'full', 'full');
															if ( strpos( $thumbnail,'media/default.png' ) === false ) {
																if( !empty( $full_thumbnail ) && !empty( $thumbnail ) ) {
																	$full_images	.= '<li class="splide__slide"><figure><img src="'.esc_url($thumbnail).'" alt="'.get_the_title().'"></figure></li>'; ?>
																	<li class="splide__slide">
																		<figure>
																			<a class="wt-venobox" data-gall="gall" href="<?php echo esc_url($full_pic);?>">
																				<img src="<?php echo esc_url( $full_thumbnail );?>" alt="<?php the_title();?>" class="item">
																			</a>
																		</figure>
																	</li>
															<?php } } ?>
													<?php } ?>
												</ul>
											</div>
										</div>
										<?php if( !empty( $full_images ) ){?>
											<div id="wt-splidethumbsnail" class="wt-servicesgallery wt-splide_thumbnail splide wt-slider-count-<?php echo esc_attr($total_count);?>">
												<div class="splide__track">
													<ul class="splide__list">
														<?php echo do_shortcode($full_images);?>
													</ul>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php }?>
								<div class="wt-service-details">
									<div class="wt-title">
										<h3><?php  echo _x('Description', 'Description for title', 'workreap' );?></h3>
									</div>
									<div class="wt-description">
										<?php the_content();?>
									</div>
									<?php if(!empty($show_project_map) && $show_project_map === 'show' 
											&& !empty($latitude) && !empty($longitude)
											&& !empty($service_map) && $service_map === 'on'
										){?>
										<div class="service-detail-map wt-haslayout">
											<?php if(!empty($address)){?>
												<address><i class="fa fa-map-marker"></i>&nbsp;<?php echo do_shortcode( stripslashes( $address ) );?></address>
												<span class="wt-get-direction-link"><i class="fa fa-map-signs"></i>&nbsp;<a target="_blank"  href="http://www.google.com/maps/place/<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>/@<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>,17z"><?php esc_html_e('Get Directions', 'workreap'); ?></a></span>
											<?php }?>
											<div id="wt-map-pin"></div>
											<script> jQuery(document).ready(function () { workreap_init_map_single_page_script(<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>);});</script>
										</div>
									<?php }?>
									<?php do_action( 'workreap_display_service_langauges_html', $post->ID); ?>
									
								</div>
							</div>
							<?php
								if(!empty($service_faq_option) && $service_faq_option == 'yes' ) {
									get_template_part('directory/front-end/templates/dashboard', 'front-faq',array('post_id' => $post->ID,'title'=> esc_html__('Service frequently asked questions','workreap')));
								}
							?>
							<?php get_template_part('directory/front-end/templates/freelancer/single/service_feedback'); ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
						<aside id="wt-sidebar" class="wt-sidebar">
							<?php if(!empty( $linked_profile) ) {?>
								<div class="wt-userrating wt-userratingvtwo">
									<?php if( !empty( $db_price ) ){?>
										<div class="wt-ratingtitle">
											<h3><?php echo workreap_price_format($db_price);?></h3>
											<div class="toolip-wrapo wt-service-hint"><?php esc_html_e('Starting from:','workreap');?><?php do_action('workreap_get_tooltip','element','starting_price');?></div>
										</div>
									<?php } ?>
									<div class="wt-rating-info">
										<ul class="wt-service-info">
											<?php if( !empty( $db_delivery_time[0] ) ) {?>
												<li>
													<span><i class="fa fa-calendar-check-o iconcolor1"></i>
													<strong><?php echo esc_html($db_delivery_time[0]->name);?></strong>&nbsp;<?php esc_html_e('Delivery time','workreap');?></span>
												</li>
											<?php }?>
											<li><span><i class="fa fa-search iconcolor2"></i><strong><?php echo intval( $service_views );?></strong>&nbsp;<?php esc_html_e('Views','workreap');?></span></li>
											<li><span><i class="fa fa-shopping-basket iconcolor3"></i><strong><?php echo intval( $completed_services );?></strong>&nbsp;<?php esc_html_e('Sales','workreap');?></span>
											</li>
											<?php if( !empty( $db_downloadable ) ) {?>
												<li><span><i class="fa fa-download iconcolor5"></i><strong><?php esc_html_e('Downloadable','workreap');?></strong></span></li>
											<?php }?>
											<?php if( !empty( $db_response_time[0] ) ) {?>
												<li>
													<span><i class="fa fa-clock-o iconcolor4"></i><strong><?php echo esc_html($db_response_time[0]->name);?></strong>&nbsp;<?php esc_html_e('Response time','workreap');?></span>
												</li>
											<?php }?>
											<?php if(!empty($remove_service_languages) && $remove_service_languages === 'no'){?>
												<?php if(!empty($english_level[$db_english_level])){?><li><span><i class="fa fa-language"></i><strong><?php echo esc_html($english_level[$db_english_level]);?></strong>&nbsp;<?php esc_html_e('English level','workreap');?></span></li><?php }}?>
											<?php do_action('workreap_print_location', $post->ID); ?>
										</ul>
									</div>
									<?php if( !empty( $db_addons ) ){ ?>
									<div class="wt-addonstwo">
										<div class="wt-addonsservices wt-tabsinfo">
											<div class="wt-widgettitle">
												<h2><?php esc_html_e( 'Addons services','workreap');?></h2>
											</div>
											<div class="wt-addonservices-content addon-list-items">
												<ul>
												<?php 
													foreach( $db_addons as $addon ) { 
														$db_price			= 0;
														if (function_exists('fw_get_db_post_option')) {
															$db_price   = fw_get_db_post_option($addon,'price');
														}
														$addon_title	= get_the_title( $addon );
														$addon_excerpt	= get_the_excerpt( $addon );
														if( !empty( $addon_title ) ){
														?>
														<li>
															<div class="wt-checkbox">
																<input data-service-id="<?php echo intval($post->ID);?>" data-addons-id="<?php echo intval($addon);?>" id="rate<?php echo intval($addon);?>" type="checkbox" name="addons" class="wt-addons-checkbox" value="<?php echo intval($addon);?>" >
																<label for="rate<?php echo intval($addon);?>">
																	<?php if( !empty( $addon_title ) ){?>
																		<h3><?php echo esc_html( $addon_title );?></h3>
																	<?php } ?>

																	<?php if( !empty( $db_price ) ){?>
																		<strong><?php workreap_price_format($db_price);?></strong>
																	<?php } ?>
																</label>
																<?php if( !empty( $addon_excerpt ) ){?>
																	<p><?php echo esc_html( $addon_excerpt );?></p>
																<?php } ?>
															</div>
														</li>
														<?php } ?>
													<?php } ?>
												</ul>
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="wt-ratingcontent">
										<p><em>*</em> <?php esc_html_e('This price is not as accurate as mentioned above It vary as per work nature','workreap');?></p>
										<a href="#" onclick="event_preventDefault(event);" class="hire-service wt-btn" data-addons="" data-id="<?php echo intval( $post->ID );?>">
											<?php esc_html_e('Buy now','workreap');?>
										</a>
										<?php if( is_user_logged_in() ) {?>
											<a class="wt-btn wt-send-offers" href="#" onclick="event_preventDefault(event);">
												<?php esc_html_e('Contact to seller','workreap');?>
											</a>
										<?php } else {?>
											<a class="wt-btn wt-loginfor-offer" data-url="<?php echo esc_url($login_page);?>" href="#" onclick="event_preventDefault(event);">
												<?php esc_html_e('Contact to seller','workreap');?>
											</a>
										<?php } ?>	
									</div>
									
								</div>
								<div class="wt-widget wt-user-service">
									<div class="wt-companysdetails">
										<?php if( !empty( $freelancer_banner ) ){?>
											<figure class="wt-companysimg">
												<img src="<?php echo esc_url( $freelancer_banner );?>" alt="<?php esc_attr_e('user banner','workreap');?>">
											</figure>
										<?php }?>
										<div class="wt-companysinfo">
										<?php if( !empty( $freelancer_avatar ) ){?>
											<figure><img src="<?php echo esc_url( $freelancer_avatar );?>" alt="<?php esc_attr_e('profile image','workreap');?>"></figure>
										<?php } ?>
											<div class="wt-userprofile">
												<div class="wt-title">
													<h3><?php do_action('workreap_get_verification_check',$linked_profile,$freelancer_title);?></h3>
													<span class="wtmember-since"><?php esc_html_e('Member since','workreap');?>&nbsp;<?php echo get_the_date( get_option('date_format'), $linked_profile );?></span>
													<a href="<?php echo esc_url($user_link);?>" class="wt-btn"><?php esc_html_e('View profile','workreap');?></a>

												</div>
											</div>
										</div>
									</div>
								</div>
								<?php  do_action('workreap_get_qr_code','service',intval( $post->ID ));?>

								<?php 
									if (function_exists('workreap_prepare_project_social_sharing')) {
										workreap_prepare_project_social_sharing(false, esc_html__('Share this service','workreap'), 'true', '', $freelancer_avatar);
									}
								?>
							<?php }?>
							<?php do_action('workreap_report_post_type_form',$post->ID,'service');?>
						</aside>
					</div>
				</div>
			</div>
		</div>
		<?php 
			if ( is_user_logged_in() && $author_id != $current_user->ID && apply_filters('workreap_get_user_type', $current_user->ID ) === 'employer' ) {
				if( apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $author_id) === true ){
					if( apply_filters('workreap_chat_window_floating', 'disable') === 'enable' ){
						get_template_part('directory/front-end/templates/messages');
					}
				}
			} 
		?>
	<?php

	do_action('workreap_chat_modal',$author_id, '','no');

		if( !empty($full_images) ){
			$script	= "

			var wt_splide = document.getElementById('wt-splide')
			if (wt_splide != null) {
				var secondarySlider = new Splide( '#wt-splidethumbsnail', {
					rewind      : true,
					fixedWidth  : 75,
					fixedHeight : 75,
					isNavigation: true,
					gap         : 0,
					pagination  : false,
					arrows     : false,
					focus  : 'left',
					updateOnMove: true,
					
				} ).mount();
				var primarySlider = new Splide( '#wt-splide', {
					type       : 'fade',
					autoHeight : true,
					pagination : false,
					cover      : true,
					video      : {
						loop: true,
					},
				} )
				primarySlider.sync( secondarySlider ).mount(); 
			}";
			
			wp_add_inline_script( 'splide', $script, 'after' );

			$script	= "jQuery('.wt-venobox').venobox();";
			wp_add_inline_script( 'venobox', $script, 'after' );
		}
	} 
} else { ?>
	<div class="container">
	  <div class="wt-haslayout page-data">
		<?php  Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap'));?>
	  </div>
	</div>
<?php
}
get_footer();?>
