<?php
/**
 *
 * The template part for displaying job history
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 			= $current_user->ID;
$linked_profile  			= workreap_get_linked_profile_id($user_identity);

$edit_id					= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author				= get_post_field('post_author', $edit_id);
$hire_freelancer_id			= get_post_meta($edit_id,'_freelancer_id',true);
$hire_freelancer_id			= workreap_get_linked_profile_id($hire_freelancer_id,'post');

$employer_post_id   		= get_user_meta($post_author, '_linked_profile', true);
$job_status					= get_post_status( $edit_id );
$proposal_id				= get_post_meta( $edit_id, '_proposal_id', true);
$hired_freelance_id			= get_post_field('post_author',$proposal_id);
$hired_freelance_id			= !empty( $hired_freelance_id ) ? intval( $hired_freelance_id ) : '';
$hire_linked_profile		= workreap_get_linked_profile_id($hired_freelance_id); 
$hired_freelancer_title 	= esc_html(get_the_title( $hire_linked_profile ));
$hired_freelancer_avatar 	= apply_filters(
	'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $hire_linked_profile ), array( 'width' => 225, 'height' => 225 )
);
?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="wt-haslayout wt-job-proposals">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle">
				<h2><?php esc_html_e('Job Details', 'workreap'); ?></h2>
			</div>
			<div class="wt-dashboardboxcontent wt-rcvproposala">
			<?php 
			if ( intval($user_identity) === intval($hire_freelancer_id) ) {
					$employer_title = esc_html(get_the_title( $employer_post_id ));
				?>
				<div class="wt-tabscontenttitle">
					<h2><?php esc_html_e('Project Details', 'workreap'); ?></h2>
				</div>
				<div class="wt-userlistinghold wt-featured wt-userlistingvtwo">
					<?php do_action('workreap_project_print_featured', $edit_id); ?>
					<div class="wt-userlistingcontent">
						<div class="wt-contenthead">
							<div class="wt-title">
								<?php do_action( 'workreap_get_verification_check', $employer_post_id, $employer_title ); ?>
								<h2><a href="<?php echo esc_url( get_the_permalink($edit_id) ); ?>" target="_blank"><?php echo esc_html(get_the_title($edit_id)); ?></a></h2>
							</div>
							<ul class="wt-saveitem-breadcrumb wt-userlisting-breadcrumb">
								<?php do_action('workreap_project_print_project_level', $edit_id); ?>
								<?php do_action('workreap_print_location', $edit_id); ?>
								<?php do_action('workreap_print_project_type', $edit_id); ?>
								<li>
								<a href="#" onclick="event_preventDefault(event);" class="download-project-attachments" data-type="project" data-post-id="<?php echo esc_html($edit_id); ?>">
									<span><i class="fa fa-paperclip"></i>
									<?php esc_html_e('Project attachments', 'workreap'); ?></span>
								</a>
								</li>
							</ul>
						</div>
						<?php do_action( 'workreap_project_employer_html', $edit_id); ?>
					</div>	
				</div>
				<div class="wt-freelancerholder wt-rcvproposalholder">
						<?php get_template_part('directory/front-end/templates/dashboard', 'project-history-messages'); ?>
					<?php }	else { 
						Workreap_Prepare_Notification::workreap_info('', esc_html__('No permissions', 'workreap'));
					}
				?>
				</div>
			</div>
		</div>
	</div>
</div>	
<?php get_template_part('directory/front-end/templates/dashboard', 'cover-letter');?>