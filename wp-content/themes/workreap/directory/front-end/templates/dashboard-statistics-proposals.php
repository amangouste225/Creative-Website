<?php
/**
 *
 * The template part for displaying the proposal statistics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-layers';
$user_type			= workreap_get_user_type($user_identity);
$user_type			= !empty($user_type) ? $user_type : '';

if( !empty($user_type) && $user_type == 'freelancer' ){
	$proposal_url	= Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_identity,true);
} else {
	$proposal_url	= Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,true,'posted');
}

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$latest_proposals_img 	= fw_get_db_settings_option( 'latest_proposals', $default_value = null );
	$latest_proposals_img	= !empty( $latest_proposals_img['url'] ) ? $latest_proposals_img['url'] : '';
}
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox">
		<figure class="wt-userlistingimg">
			<?php if( !empty($latest_proposals_img) ) {?>
				<img src="<?php echo esc_url($latest_proposals_img);?>" alt="<?php esc_attr_e('Latest Proposals', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-insightdetails">
			<div class="wt-title">
				<h3><?php esc_html_e('Latest Proposals', 'workreap'); ?></h3>
				<a href="<?php echo esc_url($proposal_url); ?>"><?php esc_html_e('Click To View', 'workreap'); ?></a>
			</div>													
		</div>	
	</div>
</div>	