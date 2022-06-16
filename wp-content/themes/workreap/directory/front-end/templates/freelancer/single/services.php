<?php
/**
 *
 * The template used for displaying freelancer services
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 					= $post->ID;
$user_id					= workreap_get_linked_profile_id($post_id,'post');
$service_video_option		= '';
if ( function_exists('fw_get_db_post_option' )) {
	$default_service_banner    	= fw_get_db_settings_option('default_service_banner');
	$service_video_option 			= fw_get_db_settings_option('service_video_option');
}

if(empty($user_id)){return;}
$show_posts		= 3;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);
$order 			= 'DESC';
$sorting 		= 'ID';
$width			= 352;
$height			= 200;
$args 			= array(
						'posts_per_page' 	=> $show_posts,
						'post_type' 		=> 'micro-services',
						'orderby' 			=> $sorting,
						'order' 			=> $order,
						'author' 			=> $user_id,
						'paged' 			=> $paged,
						'suppress_filters' 	=> false
					);
$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
$flag 			= rand(9999, 999999);
if( $query->have_posts() ){?>
<div class="container">
	<div class="row">	
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
			<div class="wt-services-holder">
				<div class="wt-title">
					<h2><?php esc_html_e('Services','workreap');?></h2>
				</div>
				<div class="wt-services-content">
					<div class="row services-wrap">
						<?php
							while ($query->have_posts()) : $query->the_post();
								global $post;
								$project_rating			= get_post_meta($post->ID, 'user_rating', true);
								$freelancer_title 		= get_the_title( $post_id );	
								$service_url			= get_the_permalink();
								$random = rand(1,9999);
								$db_docs			= array();
								$db_price			= '';
								$delivery_time		= '';
								$order_details		= '';

								if (function_exists('fw_get_db_post_option')) {
									$db_docs   			= fw_get_db_post_option($post->ID,'docs');
									$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
									$order_details   	= fw_get_db_post_option($post->ID,'order_details');
									$db_price   		= fw_get_db_post_option($post->ID,'price');
								}
								
							   //default banner set
								if( empty($db_docs) && !empty($default_service_banner) ){
									$db_docs[0]	= $default_service_banner;
								}

								if( count( $db_docs )>1 ) {
									$class	= 'wt-freelancers-services-'.intval( $flag ).' owl-carousel';
								} else {
									$class	= '';
								}
								if( count( $db_docs ) === 0 ) {
									$empty_image_class	= 'wt-empty-service-image';
									$is_featured		= workreap_service_print_featured( $post->ID, 'yes');
									$is_featured    = !empty( $is_featured ) ? 'wt-featured-service' : '';
								} else {
									$empty_image_class	= '';
									$is_featured		= '';
								}
	
								$freelancer_avatar = apply_filters(
									'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $post_id), array('width' => 100, 'height' => 100) 
								);

								$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
								wp_add_inline_script( 'splide', $script, 'after' );
							?>
								<div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
									<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?> <?php echo esc_attr( $is_featured );?>">
										<?php if( !empty( $db_docs ) ) {?>
											<div class="wtsplide-wrapper wtsplide-<?php echo esc_attr($random);?> wt-freelancers">
												<div class="splide__track">
													<div class="splide__list">
														<?php 
															if( !empty($service_video_option) && $service_video_option == 'yes' ){
																do_action( 'workreap_services_videos', $post->ID ,352,200);
															}
														?>
														<?php
														foreach( $db_docs as $key => $doc ){
															$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
															$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
															if (strpos($thumbnail,'media/default.png') === false) { ?>
																<div class="splide__slide"><figure class="item">
																	<a href="<?php echo esc_url( $service_url );?>">
																		<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service ','workreap');?>" class="item">
																	</a>
																</figure></div>
														<?php } }?>
													</div>
												</div>
											</div>
										<?php } ?>
										<?php do_action('workreap_service_print_featured', $post->ID); ?>
										<?php do_action('workreap_service_shortdescription', $post->ID,$post_id); ?>
									</div>
								</div>
							<?php
								endwhile;
								wp_reset_postdata();
							?>
						</div>
					<?php
						if (!empty($count_post) && $count_post > $show_posts) {?>
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left more-btn-services">
								<div class="wt-btnarea">
									<a href="#" onclick="event_preventDefault(event);" class="wt-btn load-more-services" data-id="<?php echo intval($user_id);?>"><?php esc_html_e('Load More','workreap');?></a>
								</div>
							</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }