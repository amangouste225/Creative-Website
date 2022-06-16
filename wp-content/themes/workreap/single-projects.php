<?php 
/**
 *
 * The template used for displaying projects post style
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();

while ( have_posts() ) { the_post(); 
global $post,$current_user;

do_action('workreap_restict_user_view_search'); //check user restriction
						
if( apply_filters('workreap_system_access','job_base') === true ){
	$current_time		= current_time( 'timestamp' );
	$author_id 			= get_the_author_meta( 'ID' );
	$company_profile_id	= workreap_get_linked_profile_id($author_id);
	$post_status		= get_post_status($post->ID);
	$employer_avatar 	= apply_filters(
		'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $author_id), array('width' => 100, 'height' => 100) 
	);    		

	$proposal_page 		= array();
	$restrict_proposals	= '';
	$job_faq_option		= '';
	if (function_exists('fw_get_db_post_option')) {
		$proposal_page = fw_get_db_settings_option('dir_proposal_page');
		$hide_proposal_on_project = fw_get_db_settings_option('hide_proposal_on_project');
		$expiry_date   		= fw_get_db_post_option($post->ID, 'expiry_date', true);
		$deadline_date   	= fw_get_db_post_option($post->ID, 'deadline', true);
		$db_english_level     = fw_get_db_post_option($post->ID,'english_level');
		$restrict_proposals   = fw_get_db_settings_option('restrict_proposals');
		$english_level      = worktic_english_level_list();
		$address   			= fw_get_db_post_option($post->ID, 'address', true);
		$longitude   		= fw_get_db_post_option($post->ID, 'longitude', true);
		$latitude   		= fw_get_db_post_option($post->ID, 'latitude', true);
		$show_project_map  	= fw_get_db_settings_option('show_project_map');
		$job_faq_option		= fw_get_db_settings_option('job_faq_option', $default_value = null);
	}
	
	
	$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
	$deadline_date	  = !empty($deadline_date) ? workreap_date_format_fix($deadline_date) : '';

	$proposal_page_id = !empty( $proposal_page[0] ) ? $proposal_page[0] : '';
	$submit_proposal  = !empty( $proposal_page_id ) ? get_the_permalink( $proposal_page_id ) : '';		
	$submit_proposal  = !empty( $submit_proposal ) ? add_query_arg( 'project_id', $post->ID, $submit_proposal ) : '';
	
	$db_project_type	= 'fixed';
	
	if (function_exists('fw_get_db_post_option')) {
		$db_project_type      = fw_get_db_post_option($post->ID,'project_type');
		$job_option     = fw_get_db_post_option($post->ID,'job_option');
	}
	
	$price_text			= '';
	$project_cost		= '';
	$estimated_hours	= '';
	$job_type_text		= '';

	
	$project_price	= workreap_project_price($post->ID);	
	$project_cost	= !empty($project_price['cost']) ? $project_price['cost'] : 0;
	$job_type_text	= !empty($project_price['text']) ? $project_price['text'] : '';
	$price_text		= !empty($project_price['price_text']) ? $project_price['price_text'] : '';
	$job_option		= !empty($job_option) ? $job_option : '';
	$user_type		= apply_filters('workreap_get_user_type', $current_user->ID );	
	$expired_time	= !empty($expiry_date) ? strtotime($expiry_date) : 0;
	$expiry_date_submission	= true;
	?>
	<div class="wt-haslayout wt-job-single">
		<div class="container">
			<div class="row">
				<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-left">
						<?php
							if( is_user_logged_in() && !empty($user_type) && $user_type == 'freelancer') {
								$proposal_args = array(
									'author'        =>  $current_user->ID, 
									'post_type'  => 'proposals',
									'meta_query' => array(
										array(
											'key'   => '_project_id',
											'value' => $post->ID,
										)
									)
								);
								
								$proposals 		= get_posts( $proposal_args );
								$proposal_count	= !empty($proposals) && is_array($proposals) ? count($proposals) : 0 ;
								if( $restrict_proposals == 'yes' ){
									
									if(  !empty($expired_time) && $current_time > $expired_time ){
										$expiry_date_submission	= false;
										Workreap_Prepare_Notification::workreap_warning(esc_html__('Info', 'workreap'), esc_html__('This job is expired', 'workreap'));
									}
								}
								
								if( !empty($proposal_count) && $proposal_count > 0 ){
									Workreap_Prepare_Notification::workreap_success(esc_html__('Info', 'workreap'), esc_html__('You have submitted a proposal for this job.', 'workreap'));
								}
							}
						?>
						<div class="wt-proposalholder">							
							<div class="wt-proposalhead">
								<h1><?php the_title(); ?></h1>
								<?php do_action( 'workreap_job_detail_header', $post->ID ) ?>
							</div>
							<?php if( !empty($post_status) && $post_status === 'completed' ){?>
								<div class="wt-btnarea project-status-btn-completed"><span class="wt-btn"><?php esc_html_e('Completed', 'workreap'); ?></span></div>
							<?php }elseif( !empty($post_status) && $post_status === 'hired' ){?>
								<div class="wt-btnarea project-status-btn-hired"><span class="wt-btn"><?php esc_html_e('Hired', 'workreap'); ?></span></div>
							<?php }else{
								if( is_user_logged_in() ) {
									if( !empty($user_type) && $user_type === 'freelancer' ) {
										if( $expiry_date_submission == true || $restrict_proposals == 'no' ) { ?>
											<div class="wt-btnarea"><a href="<?php echo esc_url( $submit_proposal ); ?>" class="wt-btn wt-submit-proposal"><?php esc_html_e('Send Proposal', 'workreap'); ?></a></div>
								<?php }}
								} else {?>
									<div class="wt-btnarea"><a href="javascript:void(0);" class="wt-btn wt-submit-proposal"><?php esc_html_e('Send Proposal', 'workreap'); ?></a></div>
								<?php }
							}
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-8 float-left">
						<div class="wt-projectdetail-holder">
							<?php if( '' !== $post->post_content ) {?>
								<div class="wt-title">
									<h3><?php esc_html_e( 'Project detail', 'workreap' ); ?></h3>
								</div>
								<div class="wt-projectdetail">
									<div class="wt-description"><?php the_content(); ?></div>
								</div>
							<?php }?>
							
							<?php if(!empty($show_project_map) && $show_project_map === 'show' 
									&& !empty($latitude) && !empty($longitude)
									&& $job_option !== 'remote'
								){?>
								<?php if(!empty($address)){?>
									<address><i class="fa fa-map-marker"></i>&nbsp;<?php echo do_shortcode( stripslashes( $address ) );?></address>
									<span class="wt-get-direction-link"><i class="fa fa-map-signs"></i>&nbsp;<a target="_blank"  href="http://www.google.com/maps/place/<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>/@<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>,17z"><?php esc_html_e('Get Directions', 'workreap'); ?></a></span>
								<?php }?>
								<div id="wt-map-pin"></div>
								<script> jQuery(document).ready(function () { workreap_init_map_single_page_script(<?php echo esc_js($latitude);?>,<?php echo esc_js($longitude);?>);});</script>
							<?php }?>
							<?php
								if(!empty($job_faq_option) && $job_faq_option == 'yes' ) {
									get_template_part('directory/front-end/templates/dashboard', 'front-faq',array('post_id'=>$post->ID,'title'=> esc_html__('Project frequently asked questions','workreap')));
								}
							?>
							<?php do_action( 'workreap_print_skills_html', $post->ID, esc_html__('Skills Required', 'workreap'),5000 ); ?>	
							<?php do_action( 'workreap_display_categories_html', $post->ID); ?>
							<?php do_action( 'workreap_display_langauges_html', $post->ID); ?>
							<?php do_action( 'workreap_display_required_freelancer_html', $post->ID); ?>
							<?php do_action('workreap_job_detail_documents', $post->ID); ?>
							<?php if( !empty($deadline_date) ){?>
								<div class="wt-skillsrequired">
									<div class="wt-title">
										<h3><?php esc_html_e('Project Completion deadline', 'workreap'); ?></h3>
									</div>
									<div class="wt-deadline wt-haslayout">
										<span><?php echo date_i18n( get_option('date_format'), strtotime($deadline_date));?></span>                     
									</div>
								</div>
							<?php }?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 col-xl-4 float-left">
						<aside id="wt-sidebar" class="wt-sidebar">
							<div class="wt-proposalsr">
								<div class="wt-proposalsrcontent sproject-price">
									<span class="wt-proposalsicon"><i class="fa fa-angle-double-down"></i><i class="fa fa-money"></i></span>
									<div class="wt-title">
										<h3><?php echo do_shortcode( $project_cost );?></h3>
										<span><?php  echo do_shortcode($price_text); ?><?php if( !empty( $job_type_text ) ) echo do_shortcode($job_type_text);?></span>
									</div>
								</div>
								<?php if( !empty( $expired_time ) ){
										if( current_time( 'timestamp' ) > $expired_time ){
											$status	=  esc_html__('Expired','workreap');
										} else{
											$status	=  date_i18n( get_option('date_format'), $expired_time);
										}
									?>
									<div class="wt-proposalsrcontent sproject-price">
										<span class="wt-proposalsicon"><i class="fa fa-angle-double-down"></i><i class="fa fa-hourglass-half"></i></span>
										<div class="wt-title">
											<h3><?php esc_html_e('Expiry Date', 'workreap'); ?></h3>
											<span><?php echo esc_html( $status); ?></span>
										</div>
									</div>	
								<?php }?>
								<?php if(!empty($english_level[$db_english_level])){?>
								<div class="wt-proposalsrcontent english-level-ico">
									<span class="wt-proposalsicon"><i class="fa fa-angle-double-down"></i><i class="fa fa-language"></i></span>
									<div class="wt-title">
										<h3><?php esc_html_e('English level', 'workreap'); ?></h3>
										<span><?php echo esc_html( $english_level[$db_english_level]); ?></span>
									</div>
								</div>
								<?php }?>	
								<?php if(!empty($hide_proposal_on_project) && $hide_proposal_on_project === 'no'){do_action( 'workreap_show_proposals_count', $post->ID);}?>
								<?php do_action( 'workreap_get_qr_code','project',intval( $post->ID ) );?>
								<div class="wt-clicksavearea">
									<span><?php esc_html_e('Project ID', 'workreap'); ?>:&nbsp;<?php echo sprintf('%08d', intval( $post->ID )); ?></span>
									<?php  do_action('workreap_save_project_html', $post->ID, 'v1'); ?>	
								</div>
							</div>
							<?php do_action('workreap_project_company_box', intval($company_profile_id)); ?>	
	
							<?php if (function_exists('workreap_prepare_project_social_sharing')) { workreap_prepare_project_social_sharing(false, esc_html__('Share this project', 'workreap'), 'true', '', $employer_avatar); }?>	
							<?php do_action('workreap_report_post_type_form',$post->ID,'project'); ?>
						</aside>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<?php } else { ?>
		<div class="container">
		  <div class="wt-haslayout page-data">
			<?php  Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap'));?>
		  </div>
		</div>
	<?php
	}
}
get_footer();
