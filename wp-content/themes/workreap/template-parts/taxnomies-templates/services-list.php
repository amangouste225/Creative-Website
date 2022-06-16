<?php
/**
 *
 * The template part for displaying results in search pages.
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

$archive_show_posts    = get_option('posts_per_page');
$width			= 352;
$height			= 200;
$flag 			= rand(9999, 999999);
?>
<div class="search-result-template wt-haslayout">
	<div class="wt-haslayout wt-dp-section">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
						<div class="row">
							<div class="wt-freelancers-holder">
								<?php 
									if( have_posts() ) {
										while ( have_posts() ) : the_post();
											global $post;

											$author_id 				= get_the_author_meta( 'ID' );  
											$linked_profile 		= workreap_get_linked_profile_id($author_id);	
											$service_url			= get_the_permalink();
											$db_docs			= array();
											$delivery_time		= '';
											$order_details		= '';

											if (function_exists('fw_get_db_post_option')) {
												$db_docs   			= fw_get_db_post_option($post->ID,'docs');
												$delivery_time		= fw_get_db_post_option($post->ID,'delivery_time');
												$order_details   	= fw_get_db_post_option($post->ID,'order_details');

											}
											if( count( $db_docs )>1 ) {
												$class	= 'wt-freelancers-services-'.intval( $flag ).' owl-carousel';
											} else {
												$class	= '';
											}

										?>
										<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 float-left wt-services-grid">
											<div class="wt-freelancers-info">
												<?php if( !empty( $db_docs ) ) {?>
													<div class="wt-freelancers <?php echo esc_attr( $class );?>">
														<?php
															foreach( $db_docs as $key => $doc ){
																$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
																$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
																?>
																<figure class="item">
																	<a href="<?php echo esc_url( $service_url );?>">
																		<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Service ','workreap');?>" class="item">
																	</a>
																</figure>
														<?php } ?>
													</div>
												<?php } ?>
												<?php do_action('workreap_service_print_featured', $post->ID); ?>
												<?php do_action('workreap_service_shortdescription', $post->ID,$linked_profile); ?>
											</div>
										</div>
									<?php 
									endwhile;
									wp_reset_postdata();
									} else{
										do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No service found.', 'workreap' ));
									}?>
								</div>
								<?php if ( $wp_query->found_posts > $archive_show_posts) {?>
									<div class="col-12">
										<div class="theme-nav">
											<?php 
												if (function_exists('workreap_prepare_pagination')) {
													echo workreap_prepare_pagination($wp_query->found_posts , $archive_show_posts);
												}
											?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<aside id="wt-sidebar" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
							<div class="wt-sidebar">
								<?php get_sidebar(); ?>
							</div>
						</aside>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$script	= "jQuery('.wt-freelancers-services-".esc_js($flag)."').owlCarousel({
				items: 1,
				rtl: ".workreap_owl_rtl_check().",
				loop:true,
				nav:true,
				margin: 0,
				autoplay:false,
				navClass: ['wt-prev', 'wt-next'],
				navContainerClass: 'wt-search-slider-nav',
				navText: ['<span class=\"lnr lnr-chevron-left\"></span>', '<span class=\"lnr lnr-chevron-right\"></span>'],
			});
			
			";
	wp_add_inline_script( 'workreap-callbacks', $script, 'after' );
			
