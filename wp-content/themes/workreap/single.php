<?php
/**
 *
 * The template used for displaying default post style
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
do_action('workreap_post_views', get_the_ID(),'article_views');
get_header();
global $post;

$sidebar_type = 'full';
$sd_sidebar	   	  = '';
$section_width    = 'col-12 col-sm-12 col-md-12 col-lg-12 float-left';
if (function_exists('workreap_sidebars_get_current_position')) {
    $current_position = workreap_sidebars_get_current_position($post->ID);
    if ( !empty($current_position['sd_layout']) && $current_position['sd_layout'] !== true && $current_position['sd_layout'] !== 'default' ) {
        $sidebar_type  		= !empty($current_position['sd_layout']) ? $current_position['sd_layout'] : 'full';
		$sd_sidebar	   		= !empty($current_position['sd_sidebar']) ? $current_position['sd_sidebar'] : '';
		if(!empty($sd_sidebar)){
        	$section_width      = 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
		}
    }else{
		if (function_exists('fw_get_db_settings_option')) {
			$sd_layout_posts    = fw_get_db_settings_option('sd_layout_posts');
			$sd_sidebar_posts   = fw_get_db_settings_option('sd_sidebar_posts');
			$sidebar_type  		= !empty($sd_layout_posts) ? $sd_layout_posts : 'full';
			$sd_sidebar	   		= !empty($sd_sidebar_posts) ? $sd_sidebar_posts : '';
			
			if(!empty($sd_sidebar)){
				$section_width 		= 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
			}
		}
	}
}

if (!empty($sidebar_type) && $sidebar_type === 'right') {
    $aside_class   = 'pull-right';
    $content_class = 'pull-left';
} else {
    $aside_class   = 'pull-left';
    $content_class = 'pull-right';
}
?>
<div class="wt-haslayout single-main-section">
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="wt-articlesingle-holder wt-bgwhite">
				<div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
					<?php
					while (have_posts()) : the_post();
						global $post, $thumbnail, $post_video, $blog_post_gallery;
						$height    = intval(400);
						$width     = intval(1140);
						$user_ID   = get_the_author_meta('ID');
						$user_url  = get_author_posts_url($user_ID);
						$thumbnail = workreap_prepare_thumbnail($post->ID, $width, $height);

						$udata      = get_userdata($user_ID);
						$registered = $udata->user_registered;

						$enable_author     = '';
						$enable_comments   = 'enable';
						$enable_categories = 'enable';
						$post_settings     = '';
						
						$title_show	= 'true';
				
						if(function_exists('fw_get_db_settings_option')){
							$titlebar_type = fw_get_db_post_option($post->ID, 'titlebar_type', true);
							if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'default' 
							){
								$title_show	= 'false';
							} else if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'none' 
							){
								$title_show	= 'true';
							} else if(  isset( $titlebar_type['gadget'] ) 
								&& $titlebar_type['gadget'] === 'custom' 
							){
								$title_show	= 'true';
							} else{
								$title_show	= 'false';
							}

						} else{
							$title_show	= 'false';
						}
					
						if (function_exists('fw_get_db_post_option')) {

							$enable_author      = fw_get_db_post_option($post->ID, 'enable_author', true);
							$enable_comments    = fw_get_db_post_option($post->ID, 'enable_comments', true);
							$enable_categories  = fw_get_db_post_option($post->ID, 'enable_categories', true);
							$enable_sharing     = fw_get_db_post_option($post->ID, 'enable_sharing', true);

							$post_settings      = fw_get_db_post_option($post->ID, 'post_settings', true);
							$enable_comments    = $enable_comments == 1 ? 'enable' : $enable_comments;
						}

						$blog_post_gallery = array();
						$post_video        = '';

						if (!empty($post_settings['gallery']['blog_post_gallery'])) {
							$blog_post_gallery = $post_settings['gallery']['blog_post_gallery'];
						}

						if (!empty($post_settings['video']['blog_video_link'])) {
							$post_video = $post_settings['video']['blog_video_link'];
						}
						?>
						<div class="wt-articlesingle-content">
							<?php
								if (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'image' && !empty($thumbnail)
								) {
									get_template_part('/template-parts/single-templates/image-single');
								} elseif (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'gallery' && !empty($blog_post_gallery)
								) {
									get_template_part('/template-parts/single-templates/gallery-single');
								} elseif (!empty($post_settings['gadget']) && $post_settings['gadget'] === 'video' && !empty($post_video)
								) {
									get_template_part('/template-parts/single-templates/video-single');
								} else if (!empty($thumbnail)) {
									get_template_part('/template-parts/single-templates/image-single');
								}
							?>
							<?php if( $title_show === 'true' ){?>
								<div class="wt-title">
									<h2><?php workreap_get_post_title($post->ID); ?></h2>
								</div>
							<?php }?>
							<ul class="wt-postarticlemeta">
								<li><?php workreap_get_post_date($post->ID); ?></li>
								<?php if (!empty($enable_author) && $enable_author === 'enable') { ?>
									<li><?php workreap_get_post_author( $user_ID , 'linked', $post->ID ); ?></li>
								<?php } ?>
								<?php if (!empty($enable_categories) && $enable_categories === 'enable') { ?>
									<li><?php workreap_get_post_categories($post->ID, '', 'category', ''); ?></li>
								<?php } ?>
							</ul>
							<div class="wt-description">
								<?php 
									the_content();
									wp_link_pages( array(
										'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
										'after'       => '</ul></nav></div>',
									) );
								?>
							</div>
							<?php if (( has_tag() ) || ( !empty($enable_sharing['gadget']) && $enable_sharing['gadget'] === 'enable' )) {?>
								<div class="wt-tagsshare">
									<?php
										if(has_tag()) {
											workreap_get_post_tags($post->ID, 'tag', 'yes');
										}
									?>
									<?php
										if (!empty($enable_sharing['gadget']) 
											&& $enable_sharing['gadget'] === 'enable'
											&& function_exists('workreap_prepare_social_sharing')
									) {
											workreap_prepare_social_sharing(false, $enable_sharing['enable']['share_title'], 'true', '', $thumbnail);
										}
									?>
								</div>
							<?php } ?>
							<?php if (!empty($enable_author) && $enable_author === 'enable') {
								$post_author_id	= get_the_author_meta('ID');
								$user_type 		= workreap_get_user_type($post_author_id);
								if( !empty($user_type) && ($user_type == 'freelancer' || $user_type == 'employer')){
									$profile_id = workreap_get_linked_profile_id($post_author_id);
									$url        = get_permalink($profile_id);
								} else {
									$url    = get_author_posts_url($post_author_id);
								}
								?>
								<div class="wt-author">
									<div class="wt-authordetails">
										<figure><a href="<?php echo esc_url($url); ?>">  <?php echo get_avatar($user_ID, 80); ?></a></figure>
										<div class="wt-authorcontent">
											<div class="wt-authorhead">
												<div class="wt-boxleft">
													<h3><a href="<?php echo esc_url($url); ?>"><?php echo get_the_author(); ?></a></h3>
													<span><?php esc_html_e('Author Since', 'workreap'); ?>:&nbsp;<?php echo date_i18n(get_option('date_format'), strtotime($registered)); ?></span> 
												</div>
												<?php
												$facebook  = get_the_author_meta('facebook', $user_ID);
												$twitter   = get_the_author_meta('twitter', $user_ID);
												$pinterest = get_the_author_meta('pinterest', $user_ID);
												$linkedin  = get_the_author_meta('linkedin', $user_ID);
												$tumblr    = get_the_author_meta('tumblr', $user_ID);
												$google    = get_the_author_meta('google', $user_ID);
												$instagram = get_the_author_meta('instagram', $user_ID);
												$skype     = get_the_author_meta('skype', $user_ID);

												if (!empty($facebook) || 
													!empty($twitter) || 
													!empty($pinterest) || 
													!empty($linkedin) || 
													!empty($tumblr) || 
													!empty($google) || 
													!empty($instagram) 
													|| !empty($skype) ) {
													?>
													<div class="wt-boxright">
														<ul class="wt-socialiconssimple">
															<?php if (!empty($facebook)) { ?>
																<li class="wt-facebook">
																	<a href="<?php echo esc_url(get_the_author_meta('facebook', $user_ID)); ?>">
																		<i class="fa fa-facebook-f"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($twitter)) { ?>
																<li class="wt-twitter">
																	<a href="<?php echo esc_url(get_the_author_meta('twitter', $user_ID)); ?>">
																		<i class="fa fa-twitter"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($pinterest)) { ?>
																<li class="wt-dribbble">
																	<a href="<?php echo esc_url(get_the_author_meta('pinterest', $user_ID)); ?>">
																		<i class="fa fa-pinterest-p"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($linkedin)) { ?>
																<li class="wt-linkedin">
																	<a href="<?php echo esc_url(get_the_author_meta('linkedin', $user_ID)); ?>">
																		<i class="fa fa-linkedin"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($tumblr)) { ?>
																<li class="wt-tumblr">
																	<a href="<?php echo esc_url(get_the_author_meta('tumblr', $user_ID)); ?>">
																		<i class="fa fa-tumblr"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($google)) { ?>
																<li class="wt-googleplus">
																	<a href="<?php echo esc_url(get_the_author_meta('google', $user_ID)); ?>">
																		<i class="fa fa-google"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($instagram)) { ?>
																<li class="wt-dribbble">
																	<a href="<?php echo esc_url(get_the_author_meta('instagram', $user_ID)); ?>">
																		<i class="fa fa-instagram"></i>
																	</a>
																</li>
															<?php } ?>
															<?php if (!empty($skype)) { ?>
																<li  class="wt-skype">
																	<a href="<?php echo esc_url(get_the_author_meta('skype', $user_ID)); ?>">
																		<i class="fa fa-skype"></i>
																	</a>
																</li>
															<?php } ?>
														</ul>
													</div>
												<?php } ?>
											</div>
											<div class="wt-description">
												<p><?php echo nl2br(get_the_author_meta('description', $user_ID)); ?></p>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php 
							if (!empty($enable_comments) && $enable_comments === 'enable') {
								if (comments_open() || get_comments_number()) :
									comments_template();
								endif;
							}
						?>
						</div>
					<?php endwhile; ?>
				</div>
				<?php
					if (function_exists('workreap_sidebars_get_current_position')) {
						if (isset($sidebar_type) && $sidebar_type != 'full' && !empty($sd_sidebar)) {?>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 <?php echo sanitize_html_class($aside_class); ?>">
								<aside id="wt-sidebar" class="wt-sidebar">
									<?php dynamic_sidebar( $sd_sidebar );?>
								</aside>
							</div>
							<?php
						}
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
