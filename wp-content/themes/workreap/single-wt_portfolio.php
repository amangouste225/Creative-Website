<?php
/**
 *
 * The template used for displaying single portfolio
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

do_action('workreap_post_views', $post->ID, 'portfolio_views');
get_header();

$ppt_option	= '';
if( function_exists('fw_get_db_settings_option') ){
	$ppt_option		= fw_get_db_settings_option('ppt_template');
}

while ( have_posts() ) {
	the_post();
	global $post;
	$author_id 				= get_the_author_meta( 'ID' );  
	$linked_profile 		= workreap_get_linked_profile_id($author_id);
	$user_link				= get_the_permalink( $linked_profile );
	$freelancer_title 		= get_the_title( $linked_profile );	

	$db_portfolio_cats 		= wp_get_post_terms($post->ID, 'portfolio_categories');
	$db_portfolio_tags 		= wp_get_post_terms($post->ID, 'portfolio_tags');
	$date					= get_the_date();
	$portfolio_views		= get_post_meta($post->ID, 'portfolio_views', true);
	$portfolio_views		= !empty( $portfolio_views ) ? $portfolio_views : 0;
	$ppt_template			= get_post_meta( $post->ID, 'ppt_template', true );
	$ppt_template			= !empty($ppt_template) ? json_decode( $ppt_template ) : '';
	

	$gallery_imgs			= array();
	$documents				= array();
	$db_videos				= array();
	$custom_link			= '';
	
	if (function_exists('fw_get_db_post_option')) {
		$gallery_imgs   	= fw_get_db_post_option($post->ID, 'gallery_imgs');
		$documents   		= fw_get_db_post_option($post->ID, 'documents');
		$db_videos   		= fw_get_db_post_option($post->ID,'videos');
		$custom_link   		= fw_get_db_post_option($post->ID,'custom_link');
	}
	

	$freelancer_avatar = apply_filters(
		'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
	);
	
	$freelancer_banner = apply_filters(
		'workreap_freelancer_banner_fallback', workreap_get_freelancer_banner(array('width' => 352, 'height' => 200), $linked_profile), array('width' => 352, 'height' => 200) 
	);
	
	$width			= 100;
	$height			= 100;
	$total_count		= 0;
	$full_width			= 1140;
	$full_height		= 400;
	
	$flag 				= rand(9999, 999999);
	$slider_images		= !empty( $gallery_imgs ) ? count( $gallery_imgs ) : 0; 
	$slider_videos		= !empty( $db_videos ) ? count( $db_videos ) : 0;
	$ppt_template_count	= !empty($ppt_template) && !empty($ppt_option) && $ppt_option ==='enable' ? 1 : 0;
	$slider_images			= $slider_videos + $slider_images + $ppt_template_count;

	$image_url	= get_the_post_thumbnail_url($post->ID, 'workreap_service_details');
	
	?>
	<div class="container">
		<div class="wt-haslayout wt-portfolio-hold">
			<div class="row">
				<div class="col-12">
					<div class="wt-servicesingle wt-portfoliosingle">
						<div class="wt-servicesingle-title">
							<div class="wt-title">
								<h2><?php the_title();?></h2>
							</div>
							<?php if( !empty( $db_portfolio_cats ) ){?>
								<div class="wt-service-tag">
									<?php foreach ( $db_portfolio_cats as $cat ) {?>
										<a href="<?php echo get_term_link($cat);?>"><?php echo esc_html($cat->name);?></a>
									<?php }?>
								</div>
							<?php  }?>
						</div>							
						<?php if( !empty( $gallery_imgs ) || !empty( $db_videos ) || !empty( $ppt_template->path ) ) {?>
							<div id="wt-splide" class="wt-servicesslider wt-splide splide">
								<div class="splide__track">
									<ul class="splide__list">
										<?php
											$full_images	= '';
											foreach( $gallery_imgs as $key => $doc ){
												$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
												$thumbnail      = workreap_prepare_image_source($attachment_id, $width, $height);
												$full_thumbnail = workreap_prepare_image_source($attachment_id, $full_width, $full_height);
												$full_pic 		= workreap_prepare_image_source($attachment_id, 'full', 'full');
												if ( strpos( $thumbnail,'media/default.png' ) === false ) {
													if( !empty( $full_thumbnail ) && !empty( $thumbnail ) ) {
														$total_count++;
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
										<?php if( !empty( $db_videos ) ){
											foreach( $db_videos as $key => $vid ){
												if(!empty($vid)){
													$total_count++;
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

										<?php if( !empty( $ppt_template->path ) && !empty($ppt_option) && $ppt_option === 'enable' ){
											$ppt_thum 		 = get_template_directory_uri().'/images/article-icon.jpg';
											$full_images	.= '<li class="splide__slide"><figure><img src="'.esc_url($ppt_thum).'" alt="'.get_the_title().'"></figure></li>';
										?>
										<li class="splide__slide">
											<figure>
												<iframe src='<?php echo esc_url($ppt_template->path);?>' width='100%' height='600px' frameborder='0'></iframe>
											</figure>
										</li>
									<?php }?>
									</ul>
								</div>
							</div>
							<?php if( !empty( $full_images ) && ( $slider_images >= 1  ) ){?>
								<div id="wt-splidethumbsnail" class="wt-servicesgallery wt-splide_thumbnail splide wt-slider-count-<?php echo esc_attr($total_count);?>">
									<div class="splide__track">
										<ul class="splide__list">
											<?php echo do_shortcode($full_images);?>
										</ul>
									</div>
								</div>
							<?php } ?>
						<?php }?>
					</div>
				</div>
				<div class="col-12 col-lg-7 col-xl-8 float-left">
					<div class="wt-usersingle wt-servicesingle-holder wt-portfolio-holder">
						<div class="wt-servicesingle">			
							<div class="wt-service-details">
								<div class="wt-description">
									<?php the_content();?>
								</div>
							</div>
						</div>
						<?php
							if (comments_open() || get_comments_number()) :
								comments_template();
							endif;
						?>
					</div>
				</div>
				<div class="col-12 col-lg-5 col-xl-4 float-right">
					<aside id="wt-sidebar" class="wt-sidebar wt-sidebar-portfolio">
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
											<h3><?php do_action('workreap_get_verification_check', $linked_profile, $freelancer_title);?></h3>
											<?php esc_html_e('Member since','workreap');?>&nbsp;<?php echo get_the_date( get_option('date_format'), $linked_profile );?>
											<a href="<?php echo esc_url($user_link);?>" class="wt-btn"><?php esc_html_e('View Profile','workreap');?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="wt-portfolio-details">
							<ul class="wt-service-info">
								<?php if( !empty( $db_portfolio_tags ) ){?>
									<li>
										<div class="wt-service-tag">
											<i class="fa fa-tag iconcolor1"></i>
											<?php foreach ( $db_portfolio_tags as $tag ) {?>
												<a href="<?php echo get_term_link($tag);?>"><?php echo esc_html($tag->name);?></a>
											<?php }?>
										</div>
									</li>
								<?php } ?>
								<li><span><i class="fa fa-eye iconcolor2"></i><strong><?php echo intval( $portfolio_views );?></strong>&nbsp;<?php esc_html_e('Views','workreap');?></span></li>
								<li><span><i class="fa fa-calendar iconcolor2"></i><strong><?php echo esc_html($date);?></strong></span></li>
								<?php if(!empty($custom_link)){?>
									<li><span><i class="fa fa-link iconcolor2"></i><strong><a href="<?php echo esc_url($custom_link);?>"><?php echo esc_url($custom_link);?></a></strong></span></li>
								<?php }?>
							</ul>
						</div>
						<?php if( !empty( $documents ) ){ ?>
							<div class="wt-portfolio-details">
								<ul class="wt-service-info">
									<?php foreach ($documents as $doc) {
										$filename      = basename(get_attached_file($doc['attachment_id']));
										$attchment_url = wp_get_attachment_url($doc['attachment_id']); ?>
										<li>
											<div class="wt-portfolio-docs wt-service-tag">
												<i class="fa fa-file iconcolor1"></i>
												<a href="<?php echo esc_url($attchment_url); ?>" download><?php echo esc_html($filename); ?></a>
											</div>
										</li>
									<?php }  ?>
								</ul>
							</div>
						<?php } ?>
						<?php  do_action('workreap_get_qr_code', 'portfolio', intval( $post->ID ));?>
						<div class="wt-portfolio">
							<?php 
								if (function_exists('workreap_prepare_project_social_sharing')) {
									workreap_prepare_project_social_sharing(false, '', false, '', $image_url);
								}
							?>	
						</div>
					</aside>
				</div>
			</div>
		</div>
	</div>
<?php
	if( !empty($slider_images) && $slider_images >= 1 ){
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
			} );

			primarySlider.sync( secondarySlider ).mount(); 
		}";
		
		wp_add_inline_script( 'splide', $script, 'after' );

		$script	= "jQuery('.wt-venobox').venobox();";
		wp_add_inline_script( 'venobox', $script, 'after' );
	}
} 
get_footer();