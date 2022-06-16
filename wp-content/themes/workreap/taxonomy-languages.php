<?php
/**
 *
 * The template used for displaying default Skill result
 *
 * @package   workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
global $wp_query;
get_header();
$archive_show_posts    = get_option('posts_per_page');
$job_option_type	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$job_option_type	= fw_get_db_settings_option('job_option', $default_value = null);
}
?>
<div class="search-result-template wt-haslayout">
	<div class="wt-haslayout wt-lang-section">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 page-section float-left">
						<div class="wt-userlistingholder wt-userlisting wt-haslayout wt-skills-users">
						<?php 
							if( have_posts() ) {
								while ( have_posts() ) : the_post();
								global $post;

								if( $post->post_type === 'projects' ){
									$author_id 		= get_the_author_meta( 'ID' );  
									$linked_profile = workreap_get_linked_profile_id($author_id);
									$employer_title = esc_html( get_the_title( $linked_profile ));	
									$classFeatured	= apply_filters('workreap_project_print_featured', $post->ID,'yes');


									if (function_exists('fw_get_db_post_option')) {
										$db_project_type      = fw_get_db_post_option($post->ID,'project_type');
									}

									?>
									<div class="wt-userlistinghold <?php echo esc_attr($classFeatured);?> wt-userlistingholdvtwo">	
										<div class="wt-userlistingcontent">
											<?php do_action('workreap_project_print_featured', $post->ID); ?>
											<div class="wt-contenthead">
												<div class="wt-title">
													<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
													<h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
												</div>
												<div class="wt-description">
													<p><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>
												</div>
												<?php do_action( 'workreap_print_skills_html', $post->ID );?>										
											</div>
											<div class="wt-viewjobholder">
												<ul>
													<?php do_action('workreap_project_print_project_level', $post->ID); ?>
													<?php do_action('workreap_print_project_duration_html', $post->ID);?>
													<?php if(!empty($job_option_type) && $job_option_type === 'enable' ){ do_action('workreap_print_project_option_type', $post->ID); }?>
													<?php do_action('workreap_print_project_type', $post->ID); ?>
													<?php do_action('workreap_print_project_date', $post->ID);?>
													<?php do_action('workreap_print_location', $post->ID); ?>
													<li><?php  do_action('workreap_save_project_html', $post->ID, 'v2'); ?></li>
													<li class="wt-btnarea"><a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wt-btn"><?php esc_html_e( 'View Job', 'workreap' ) ?></a></li>
												</ul>
											</div>
										</div>
									</div>
								<?php } else if( $post->post_type === 'freelancers' ){
									$author_id 				= get_the_author_meta( 'ID' );  
									$linked_profile 		= workreap_get_linked_profile_id($author_id);
									$freelancer_title 		= esc_html( get_the_title( $linked_profile ));
									$tagline				= workreap_get_tagline($linked_profile);

									$freelancer_avatar = apply_filters(
										'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 100, 'height' => 100), $post->ID), array('width' => 100, 'height' => 100) 
									);

									$class	= apply_filters('workreap_featured_freelancer_tag',$author_id,'yes');
									$class	= !empty($class) ? $class : '';
									?>
									<div class="wt-userlistinghold <?php echo esc_attr($class);?>">
										<?php do_action('workreap_featured_freelancer_tag',$author_id);?>
										<figure class="wt-userlistingimg">
											<a href="<?php echo esc_url( get_the_permalink() );?>"><img src="<?php echo esc_url($freelancer_avatar); ?>" alt="<?php echo esc_attr($tagline); ?>"></a>
											<?php echo do_action('workreap_print_user_status',$author_id);?>
										</figure>
										<div class="wt-userlistingcontent">
											<div class="wt-contenthead">
												<div class="wt-title">
													<?php do_action( 'workreap_get_verification_check', $linked_profile, $freelancer_title ); ?>
													<h2><a href="<?php echo esc_url( get_the_permalink() );?>"><?php echo workreap_get_tagline($linked_profile); ?></a></h2>
												</div>
												<?php do_action('workreap_freelancer_breadcrumbs',$post->ID,'');?>	
											</div>
											<div class="wt-rightarea">
												<?php do_action('workreap_freelancer_get_reviews',$post->ID,'v2' );?>	
											</div>
										</div>
										<div class="wt-description">
											<p><?php echo wp_trim_words( do_shortcode(get_the_excerpt()), 25 ); ?></p>
										</div>
										<?php do_action( 'workreap_print_freelancer_skills', $post->ID,'', 6 ); ?>
									</div>
								<?php
								} 

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