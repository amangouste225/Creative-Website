<?php
/**
 *
 * The template part for displaying the dashboard Available balance for freelancer
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-cart';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$available_balance_img 	= fw_get_db_settings_option( 'avalible_balance_img', $default_value = null );
	$available_balance_img	= !empty( $available_balance_img['url'] ) ? $available_balance_img['url'] : '';
}
$available_balance			= workreap_get_sum_earning_freelancer($user_identity,'hired','freelancer_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="wt-insightsitem wt-dashboardbox">
		<figure class="wt-userlistingimg">
			<?php if( !empty($available_balance_img) ) {?>
				<img src="<?php echo esc_url($available_balance_img);?>" alt="<?php esc_attr_e('Pending balance', 'workreap'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="wt-insightdetails">
			<div class="wt-title">
				<h3><?php workreap_price_format($available_balance);?></h3>
				<span><?php esc_html_e('Pending balance', 'workreap'); ?></span>
			</div>													
		</div>	
	</div>
</div>