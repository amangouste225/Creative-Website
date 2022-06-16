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
$icon_sales			= 'lnr lnr-menu-circle';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$total_completed_services_img 	= fw_get_db_settings_option( 'total_completed_services', $default_value = null );
	$total_cancelled_img 	= fw_get_db_settings_option( 'total_cancelled_services', $default_value = null );
	$total_ongoing_services_img 	= fw_get_db_settings_option( 'total_ongoing_services', $default_value = null );
}
$total_completed_services_img	= !empty( $total_completed_services_img['url'] ) ? $total_completed_services_img['url'] : '';
$total_cancelled_img			= !empty( $total_cancelled_img['url'] ) ? $total_cancelled_img['url'] : '';
$total_ongoing_services_img		= !empty( $total_ongoing_services_img['url'] ) ? $total_ongoing_services_img['url'] : '';


$completed_services				= workreap_count_posts_by_meta( 'services-orders' ,$user_identity, '', '', 'completed');
$total_completed_services		= !empty($completed_services) && intval($completed_services) > 0 ? sprintf('%02d', intval($completed_services)) : 0;

$ongoing_services				= workreap_count_posts_by_meta( 'services-orders' ,$user_identity, '', '', 'hired');
$total_ongoing_services		= !empty($ongoing_services) && intval($ongoing_services) > 0? sprintf('%02d', intval($ongoing_services)) : 0;

$cancelled_services				= workreap_count_posts_by_meta( 'services-orders' ,$user_identity, '', '', 'cancelled');
$total_cancelled_services		= !empty($cancelled_services) && intval($cancelled_services) > 0? sprintf('%02d', intval($cancelled_services)) : 0;

?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-insighton">
		<figure>
			<?php if( !empty($total_ongoing_services_img) ) {?>
				<img src="<?php echo esc_url($total_ongoing_services_img);?>" alt="<?php esc_attr_e('Ongoing projects', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon_ongoing);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-title">
			<h3><?php echo esc_html($total_ongoing_services);?></h3>
			<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Total Ongoing Services', 'workreap'); ?></a>
		</div>
	</div>
</div>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-insightcom">
		<figure>
			<?php if( !empty($total_completed_services_img) ) {?>
				<img src="<?php echo esc_url($total_completed_services_img);?>" alt="<?php esc_attr_e('Completed services', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon_completed);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-title">
			<h3><?php echo esc_html($total_completed_services);?></h3>
			<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','completed'); ?>"><?php esc_html_e('Total completed services', 'workreap'); ?></a>
		</div>	
	</div>
</div>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-insightcom">
		<figure>
			<?php if( !empty($total_cancelled_img) ) {?>
				<img src="<?php echo esc_url($total_cancelled_img);?>" alt="<?php esc_attr_e('Cancelled services', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon_cancelled);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-title">
			<h3><?php echo esc_html($total_cancelled_services);?></h3>
			<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Total Cancelled Services', 'workreap'); ?></a>
		</div>	
	</div>
</div>