<?php
/**
 *
 * The template used for displaying default Departments result
 *
 * @package   workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
global $wp_query;
get_header();
$archive_show_posts    = get_option('posts_per_page');
?>
<div class="search-result-template wt-haslayout">
	<div class="wt-haslayout wt-dp-section">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 page-section float-left">
						<div class="wt-userlistingholder wt-userlisting wt-haslayout wt-skills-users">
						<?php 
							if( have_posts() ) {
								while ( have_posts() ) : the_post();
								global $post; 
								if( $post->post_type === 'employers' ){
									$post_id	= $post->ID;
									$employer_banner = apply_filters(
										'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 1140, 'height' => 400), $post->ID), array('width' => 1140, 'height' => 400) 
									);

									$employer_avatar = apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 1140, 'height' => 400), $post->ID), array('width' => 1140, 'height' => 400) 
									);
									
									if (function_exists('fw_get_db_post_option')) {
										$tag_line      = fw_get_db_post_option($post->ID,'tag_line');
									}
									?>
									<div class="wt-companysdetails">
										<figure class="wt-companysimg">
											<img src="<?php echo esc_url($employer_banner); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>">
										</figure>
										<div class="wt-companysinfo">
											<figure><img src="<?php echo esc_url($employer_avatar); ?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>"></figure>
											<div class="wt-title">
												<?php do_action('workreap_get_verification_check',$post_id,esc_html__('Verified Employer','workreap'));?>
												<?php if( !empty( $tag_line ) ){?><h2><?php echo esc_html(stripslashes($tag_line)); ?></h2><?php }?>
											</div>
											<ul class="wt-postarticlemeta">
												<li><a href="<?php echo esc_url(get_the_permalink());?>?#posted-projects"><span><?php esc_html_e('Open Jobs','workreap');?></span></a></li>
												<li><a href="<?php echo esc_url(get_the_permalink());?>"><span><?php esc_html_e('Full Profile','workreap');?></span></a></li>
												<li><?php do_action('workreap_follow_employer_html','v2',$post_id);?></li>
											</ul>
										</div>
									</div>
							<?php }

							endwhile;
							wp_reset_postdata();
							$qrystr = '';
							if ( $wp_query->found_posts > $archive_show_posts) {?>
								<div class="theme-nav">
									<?php 
										if (function_exists('workreap_prepare_pagination')) {
											echo workreap_prepare_pagination($wp_query->found_posts , $archive_show_posts);
										}
									?>
								</div>
							<?php }}?>
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
get_footer();