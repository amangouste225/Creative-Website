<?php
/**
 *
 * The template part for displaying the dashboard.
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
get_header();
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);

$icon_ongoing		= 'lnr lnr-cloud-sync';
$icon_completed		= 'lnr lnr-checkmark-circle';
$icon_cancelled		= 'lnr lnr-cross-circle';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$total_completed_job_img 	= fw_get_db_settings_option( 'total_completed_job', $default_value = null );
	$total_completed_job_img	= !empty( $total_completed_job_img['url'] ) ? $total_completed_job_img['url'] : '';
}

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$total_ongoing_job_img 	= fw_get_db_settings_option( 'total_ongoing_job', $default_value = null );
	$total_ongoing_job_img	= !empty( $total_ongoing_job_img['url'] ) ? $total_ongoing_job_img['url'] : '';
}

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$total_cancelled_img 	= fw_get_db_settings_option( 'total_cancelled_job', $default_value = null );
	$total_cancelled_img	= !empty( $total_cancelled_img['url'] ) ? $total_cancelled_img['url'] : '';
}

$completed_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'completed');
$total_completed_jobs		= !empty($completed_jobs) && intval($completed_jobs) > 0 ? sprintf('%02d', intval($completed_jobs)) : 0;

$ongoing_jobs				= workreap_count_posts_by_meta( 'projects' ,'', '_freelancer_id', $linked_profile, 'hired');
$total_ongoing_jobs		= !empty($ongoing_jobs) && intval($ongoing_jobs) > 0? sprintf('%02d', intval($ongoing_jobs)) : 0;

$cancelled_jobs				= workreap_count_posts_by_meta( 'proposals' ,$user_identity, '', '', 'cancelled');
$total_cancelled_jobs		= !empty($cancelled_jobs) ? $cancelled_jobs : 0;
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-insighton">
		<figure>
			<?php if( !empty($total_ongoing_job_img) ) {?>
				<img src="<?php echo esc_url($total_ongoing_job_img);?>" alt="<?php esc_attr_e('Ongoing projects', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_html($icon_ongoing);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-title">
			<h3><?php echo esc_html($total_ongoing_jobs);?></h3>
			<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Total ongoing projects', 'workreap'); ?></a>
		</div>
	</div>
</div>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-insightcom">
		<figure>
			<?php if( !empty($total_completed_job_img) ) {?>
				<img src="<?php echo esc_url($total_completed_job_img);?>" alt="<?php esc_attr_e('Completed projects', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_html($icon_completed);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-title">
			<h3><?php echo esc_html($total_completed_jobs);?></h3>
			<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','completed'); ?>"><?php esc_html_e('Total Completed Projects', 'workreap'); ?></a>
		</div>	
	</div>
</div>