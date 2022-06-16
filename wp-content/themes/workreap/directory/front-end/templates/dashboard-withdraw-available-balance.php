<?php
/**
 *
 * The template part for displaying the dashboard current balance for freelancer
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-gift';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$disable_payouts = fw_get_db_settings_option('disable_payouts');
	$current_balance_img 	= fw_get_db_settings_option( 'current_balance_img', $default_value = null );
	$current_balance_img	= !empty( $current_balance_img['url'] ) ? $current_balance_img['url'] : '';
}

$payment_method	= !empty( $_POST['withdraw']['gateway'] ) ? esc_html( $_POST['withdraw']['gateway'] ) : '';
$amount			= !empty( $_POST['withdraw']['amount'] ) ? floatval( $_POST['withdraw']['amount'] ) : 0;
$user_id		= !empty( $current_user->ID ) ? intval( $current_user->ID ) : '';
$disable_payouts	=  !empty($disable_payouts) ?  $disable_payouts : 'no';

$total_pending	= workreap_sum_freelancer_withdraw(array('publish','pending'));
$total_pending	= !empty($total_pending) ? floatval($total_pending) : 0;

$totalamount    	= workreap_sum_user_earning('completed', 'freelancer_amount', $current_user->ID);

$current_balance	= 0;
if(!empty($totalamount->total_amount)){
	$balance_remaining	= floatval($totalamount->total_amount ) - floatval( $total_pending );
	$current_balance    = !empty( $balance_remaining ) && $balance_remaining > 0  ? floatval( $totalamount->total_amount ) - floatval( $total_pending ) : 0;
}

$colum	= !empty($args['wrapper']) ? 'col-12' : 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3'
?>
<div class="<?php echo esc_attr($colum);?>">
	<div class="wt-insightsitem wt-dashboardbox wt-withdraw-bl">
		<div class="wt-withdraw-user">
			<figure class="wt-userlistingimg">
				<?php if( !empty($current_balance_img) ) {?>
					<img src="<?php echo esc_url($current_balance_img);?>" alt="<?php esc_attr_e('Available balance', 'workreap'); ?>">
				<?php } else {?>
						<span class="<?php echo esc_attr($icon);?>"></span>
				<?php }?>
			</figure>
			<div class="wt-insightdetails">
				<div class="wt-title">
					<span><?php esc_html_e('Available balance', 'workreap'); ?></span>
					<h3><?php workreap_price_format($current_balance);?></h3>
				</div>													
			</div>	
		</div>
		<?php if( !empty($disable_payouts) && $disable_payouts === 'no'  ){?>
			<div class="wt-btnarea">
				<a href="#" onclick="event_preventDefault(event);" data-toggle="modal" data-target=".wt-withdraw-form" class="wt-btn wt-withdraw-now"><?php esc_html_e('Withdraw now', 'workreap'); ?></a>
			</div>
		<?php }?>
	</div>
</div>