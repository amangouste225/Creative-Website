<?php 
/**
 *
 * The template used for displaying employer post style
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();
global $post;

do_action('workreap_restict_user_view_search'); //check user restriction

while ( have_posts() ) { the_post(); 
	global $post;
	$post_id 			= $post->ID;	
	$emp_title			= workreap_get_username('',$post_id);				
	$employer_banner = apply_filters(
						'workreap_employer_banner_fallback', workreap_get_employer_banner(array('width' => 1140, 'height' => 400), $post_id), array('width' => 1140, 'height' => 400) 
					);
	$employer_avatar = apply_filters(
						'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $post_id), array('width' => 100, 'height' => 100) 
					);	
	$job_option_type	= '';
	if( function_exists('fw_get_db_settings_option')  ){
		$job_option_type	= fw_get_db_settings_option('job_option', $default_value = null);
	}
	
	$project_status	= array('publish');
	if( function_exists('fw_get_db_settings_option')  ){
		$project_status_db	= fw_get_db_settings_option('employer_project_status', $default_value = null);
	}
	
	$project_status	= !empty($project_status_db) ? $project_status_db : $project_status;
						
						
	$user_id 	= get_post_field('post_author', $post_id);

	$order 		= 'DESC';
	$sorting	= 'ID';

	$args 		= array(
					'posts_per_page' 	=> -1,//$show_posts,
					'post_type' 		=> 'projects',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> $project_status,
					'author' 			=> $user_id
				); 
	$projects 		= get_posts($args);
	$total_posts 	= !empty($projects) && is_array($projects) ? count($projects) : 0;//  $query->found_posts;

	$brochures	 = array();
	if (function_exists('fw_get_db_post_option')) {
		$tag_line      = fw_get_db_post_option($post->ID, 'tag_line');
		$brochures     = fw_get_db_post_option($post->ID, 'brochures');
	}

	$socialmediaurls	= array();
	if( function_exists('fw_get_db_settings_option')  ){
		$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
		$hide_brochures       = fw_get_db_settings_option('hide_brochures', 'no');
	}
						
	$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';

	$social_settings	= array();
	if(function_exists('workreap_get_social_media_icons_list')){
		$social_settings	= workreap_get_social_media_icons_list('no');
	}
	
?>
<div class="wt-haslayout wt-employer-single">
	<div class="wt-haslayout">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-left">
						<div class="wt-comsingleimg">
							<figure><img src="<?php echo esc_url( $employer_banner );?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>"></figure>
							
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
						<aside id="wt-sidebar" class="wt-sidebar">
							<div class="wt-proposalsr wt-proposalsrvtwo">
								<div class="wt-widgetcontent wt-companysinfo">
									<figure>
										<img src="<?php echo esc_url( $employer_avatar );?>" alt="<?php esc_attr_e('employer', 'workreap'); ?>">
										<?php echo do_action('workreap_print_user_status',$user_id);?>
									</figure>
									<div class="wt-title">
										<?php do_action('workreap_get_verification_check',$post_id,esc_html__('Verified Employer','workreap'));?>
										<?php if( !empty( $tag_line ) ){?><h2><?php echo esc_html(stripslashes($tag_line)); ?></h2><?php }?>
										<?php if(!empty($social_settings) && !empty($socialmediaurl) && $socialmediaurl === 'enable') {?>
											<ul class="wt-socialiconssimple">
												<?php
												foreach($social_settings as $key => $val ) {
													$icon		= !empty( $val['icon'] ) ? $val['icon'] : '';
													$color		= !empty( $val['color'] ) ? $val['color'] : '#484848';

													$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
													if( !empty($enable_value) && $enable_value === 'enable' ){ 
														
														
														$social_url	= '';
														if( function_exists('fw_get_db_post_option') ){
															$social_url	= fw_get_db_post_option($post->ID, $key, true);
														}
														
														if( $key === 'whatsapp' ){
															if ( !empty( $social_url ) ){
																$social_url	= 'https://api.whatsapp.com/send?phone='.$social_url;
															} else {
																$social_url	= '';
															}
														} else if( $key === 'skype' ){
															if ( !empty( $social_url ) ){
																$social_url	= 'skype:'.$social_url.'?call';
															} else {
																$social_url	= '';
															}
														} else{
															$social_url	= esc_url($social_url);;
														}
														
														if(!empty($social_url)) {?>
															<li><a href="<?php echo esc_attr($social_url); ?>" target="_blank">
																<i class="wt-icon <?php echo esc_attr( $icon );?>" style="color:<?php echo esc_attr( $color );?>"></i>
															</a></li>
														<?php } ?>
													<?php } ?>
												<?php } ?>
											</ul>
										<?php } ?>
									</div>
								</div>
								<?php  do_action('workreap_get_qr_code','employer',intval( $post_id ));?>
								<div class="wt-clicksavearea">
									<span><?php esc_html_e('Company ID', 'workreap'); ?>:&nbsp; <?php echo sprintf('%08d', intval( $post_id ));?></span>
									<?php do_action('workreap_follow_employer_html','v1',$post_id);?>
								</div>
							</div>
							<?php if(!empty($hide_brochures) && $hide_brochures == 'no'){
								if( !empty( $brochures ) ){ ?>
								<div class="wt-portfolio-details">
									<div class="wt-widgettitle">
										<h2><?php esc_html_e('Brochures', 'workreap'); ?></h2>
									</div>
									<ul class="wt-service-info">
										<?php foreach ($brochures as $doc) {
											$attachment_id		= !empty($doc['attachment_id']) ? $doc['attachment_id'] : 0;
											$filename      		= basename(get_attached_file($attachment_id));
											$attchment_url 		= wp_get_attachment_url($attachment_id);
											$file_detail        = Workreap_file_permission::getDecrpytFile($doc);
											$name               = $file_detail['filename'];
											?>
											<li>
												<div class="wt-portfolio-docs wt-service-tag">
													<i class="fa fa-file iconcolor1"></i>
													<a href="javascript:void(0);" class="wt-download-single-file" data-id="<?php echo esc_attr( $attachment_id ); ?>"><?php echo esc_html($name); ?></a>
												</div>
											</li>
										<?php }  ?>
									</ul>
								</div>
							<?php }} ?>
							<?php do_action('workreap_employer_followers',$post_id);?>
							<?php if (function_exists('workreap_prepare_project_social_sharing')) { workreap_prepare_project_social_sharing(false, esc_html__('Share This Company', 'workreap'), 'true', '', $employer_avatar); }?>
							<?php do_action('workreap_report_post_type_form',$post_id,'employer');?>
						</aside>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
						<div class="wt-userlistingholder wt-haslayout">
							<div class="wt-comcontent">
								<div class="wt-title">
									<h3><?php esc_html_e('About', 'workreap'); ?>&nbsp;“<?php echo esc_html($emp_title);?>”</h3>
								</div>
								<div class="wt-description">
									<?php the_content();?>
								</div>
							</div>
							<div class="wt-comcontent wo-postedprj" id="posted-projects">
								<div class="wt-title">
									<h3><?php esc_html_e('Posted projects', 'workreap'); ?></h3>
								</div>
							</div>
							<?php 
								if( !empty($projects) ){
									foreach($projects as $project){
										$author_id 		= get_post_field( 'post_author', $project );
										$linked_profile = workreap_get_linked_profile_id($author_id);
										$employer_title = esc_html( get_the_title( $linked_profile ) );
										$classFeatured	= apply_filters('workreap_project_print_featured', $project->ID,'yes');
										if (function_exists('fw_get_db_post_option')) {
											$db_project_type      = fw_get_db_post_option($project->ID,'project_type');
										}

										$project_cost = !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : 0;
										?>
										<div class="wt-userlistinghold <?php echo esc_attr($classFeatured);?> wt-userlistingholdvtwo">	
											<div class="wt-userlistingcontent">
												<?php do_action('workreap_project_print_featured', $project->ID); ?>
												<div class="wt-contenthead wt-employer-jobs">
													<div class="wt-title">
														<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
														<h2><a href="<?php echo esc_url( get_the_permalink($project) ); ?>"><?php echo get_the_title($project); ?></a></h2>
													</div>
													<?php do_action( 'workreap_job_short_detail', $project->ID ) ?>
													<div class="wt-description">
														<p><?php echo wp_trim_words( do_shortcode(get_the_excerpt($project->ID)), 25 ); ?></p>
													</div>
													<?php do_action( 'workreap_print_skills_html', $project->ID );?>										
												</div>
												<div class="wt-viewjobholder">
													<ul>
													<?php do_action('workreap_project_print_project_level',  $project->ID); ?>
														<?php if(!empty($job_option_type) && $job_option_type === 'enable' ){ do_action('workreap_print_project_option_type', $post->ID); }?>
														<?php do_action('workreap_print_project_duration_html', $project->ID);?>
														<?php do_action('workreap_print_project_date', $project->ID);?>
														<?php  do_action('workreap_print_project_type', $project->ID); ?>	
														<li><?php  do_action('workreap_save_project_html', $project->ID, 'v2'); ?></li>
														<li class="wt-btnarea"><a href="<?php echo esc_url( get_the_permalink($project) ); ?>" class="wt-btn"><?php esc_html_e( 'View Job', 'workreap' ) ?></a></li>
													</ul>
												</div>
											</div>
										</div>
									<?php	
									}
								} else{
									do_action('workreap_empty_records_html','wt-empty-projects',esc_html__( 'No projects posted yet by this employer.', 'workreap' ));
								} 
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
get_footer();
