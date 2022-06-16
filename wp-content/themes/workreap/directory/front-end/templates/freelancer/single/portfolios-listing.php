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
$post_id 		= $post->ID;
$user_id		= workreap_get_linked_profile_id($post_id,'post');
if(empty($user_id)){return;}
$order 			= 'DESC';
$sorting 		= 'ID';
$width			= 352;
$height			= 200;
$args 			= array(
						'posts_per_page' 	=> -1,
						'post_type' 		=> 'wt_portfolio',
						'orderby' 			=> $sorting,
						'order' 			=> $order,
						'author' 			=> $user_id,
						'suppress_filters' 	=> false
					);
$query 			= new WP_Query($args);
$count_post 	= $query->found_posts;
$flag 			= rand(9999, 999999);

if( $query->have_posts() ){?>
<div class="wt-haslayout wt-portfolio-wrap">
	<div class="row">	
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
			<div class="wt-services-holder">
				<div class="wt-usertitle">
					<h2><?php esc_html_e('Portfolios','workreap');?></h2>
				</div>
				<div class="wt-services-content">
					<div class="row portfolios-innerwrap">
					<?php
						while ($query->have_posts()) : $query->the_post();
							global $post;
							$random = rand(1,9999);
							$portfolio_url			= get_the_permalink();
							$author_id 				= get_the_author_meta( 'ID' );  
							$linked_profile 		= workreap_get_linked_profile_id($author_id);
							$user_link				= get_the_permalink( $linked_profile );
							$freelancer_title 		= get_the_title( $linked_profile );	

							$db_portfolio_cats 		= wp_get_post_terms($post->ID, 'portfolio_categories');
							$db_portfolio_tags 		= wp_get_post_terms($post->ID, 'portfolio_tags');
							$date					= date( get_option('date_format'), strtotime(get_the_date()));

							$portfolio_views		= get_post_meta($post->ID, 'portfolio_views', true);
							$portfolio_views		= !empty( $portfolio_views ) ? $portfolio_views : 0;

							$gallery_imgs			= array();
						    $videos					= array();
						   
							if (function_exists('fw_get_db_post_option')) {
								$gallery_imgs   	= fw_get_db_post_option($post->ID,'gallery_imgs');
								$videos 			= fw_get_db_post_option($post->ID, 'videos');
							}
						   	
						   	$default_thumbnail 		= get_template_directory_uri().'/images/portfolio.jpg';
						   
							$empty_image_class = '';
							$total_data	= count($gallery_imgs) + count($videos);
							if( !empty($total_data) && $total_data < 1 ) {
								$empty_image_class	= 'wt-empty-service-image';
							} 

							$script	= "new Splide( '.wtsplide-".$random."',{direction:'".workreap_splide_rtl_check()."'} ).mount();";
							wp_add_inline_script( 'splide', $script, 'after' );

							?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left wt-verticalmiddle">
								<div class="wt-freelancers-info <?php echo esc_attr( $empty_image_class );?>">
									<?php if( !empty( $gallery_imgs ) || !empty( $videos ) ) {?>
										<div class="wt-freelancers wtsplide-<?php echo esc_attr($random);?>">
											<div class="splide__track">
												<div class="splide__list">
													<?php if( !empty( $gallery_imgs ) ) {
														foreach( $gallery_imgs as $key => $item ){
															$attachment_id	= !empty( $item['attachment_id'] ) ? $item['attachment_id'] : '';
															$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
															if (strpos($thumbnail,'media/default.png') === false) {?>
																<div class="splide__slide"><figure class="item">
																	<a href="<?php echo esc_url( $portfolio_url );?>">
																		<img src="<?php echo esc_url($thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
																	</a>
																</figure></div>
													<?php }}}?>
													<?php if( !empty( $videos ) ){
														foreach( $videos as $key => $vid ){
														?>
														<div class="splide__slide"><figure class="item">
															<a href="<?php echo esc_url( $portfolio_url );?>">
																<img src="<?php echo esc_url($default_thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
															</a>
														</figure></div>
													<?php }}?>
												</div>
											</div>
										</div>
									<?php }else{?>
										<div class="wt-freelancers">
											<div class="splide__slide"><figure class="item">
												<a href="<?php echo esc_url( $portfolio_url );?>">
													<img src="<?php echo esc_url($default_thumbnail);?>" alt="<?php esc_attr_e('Portfolio ','workreap');?>" class="item">
												</a>
											</figure></div>
										</div>
									<?php }?>
									<?php do_action('workreap_portfolio_shortdescription', $post->ID,$post_id); ?>
								</div>
							</div>
							<?php
							endwhile;
							wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}	