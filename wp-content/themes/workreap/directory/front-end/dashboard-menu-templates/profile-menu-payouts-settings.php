<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$disable_payouts = fw_get_db_settings_option('disable_payouts');
	if( !empty($disable_payouts) && $disable_payouts === 'yes'  ){$display_payout	= 'no';}

	$hide_payout_employers = fw_get_db_settings_option('hide_payout_employers');
	$user_type	= apply_filters('workreap_get_user_type', $user_identity );
	if( !empty($user_type) && $user_type === 'employer' && $hide_payout_employers === 'yes' ){
		return '';
	}
}
?>
<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'payouts' ? 'wt-active' : ''); ?>">
	<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('payouts', $user_identity,'','settings'); ?>">
		<i class="ti-credit-card"></i>
		<span><?php esc_html_e('Payouts settings','workreap');?></span>
		<?php do_action('workreap_get_tooltip','element','payouts-settings');?>
	</a>
</li>
