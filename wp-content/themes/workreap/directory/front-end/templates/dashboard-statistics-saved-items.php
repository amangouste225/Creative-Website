<?php
/**
 *
 * The template part for displaying the Save item statistics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-heart';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$saved_items_img 	= fw_get_db_settings_option( 'saved_items', $default_value = null );
	$saved_items_img	= !empty( $saved_items_img['url'] ) ? $saved_items_img['url'] : '';
}
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox wt-stat-saved">
		<figure class="wt-userlistingimg">
			<?php if( !empty($saved_items_img) ) {?>
				<img src="<?php echo esc_url($saved_items_img);?>" alt="<?php esc_attr_e('Save Items', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-insightdetails">
			<div class="wt-title">
				<h3><?php esc_html_e('View Saved Items', 'workreap'); ?></h3>
				<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('saved', $user_identity); ?>"><?php esc_html_e('Click To View', 'workreap'); ?></a>
			</div>													
		</div>
	</div>
</div>