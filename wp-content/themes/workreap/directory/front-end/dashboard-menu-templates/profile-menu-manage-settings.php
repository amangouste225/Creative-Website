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

global $current_user, $wp_roles, $userdata, $post;
$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity = $current_user->ID;

$parent_active	= array('profile','payouts','account-settings');

$display_payout	= 'yes';
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$hide_payout_employers = fw_get_db_settings_option('hide_payout_employers');
	$user_type	= apply_filters('workreap_get_user_type', $user_identity );
	if( !empty($user_type) && $user_type === 'employer' && $hide_payout_employers === 'yes' ){
		$display_payout	= 'no';
	}

	$disable_payouts = fw_get_db_settings_option('disable_payouts');
	if( !empty($disable_payouts) && $disable_payouts === 'yes'  ){$display_payout	= 'no';}
}

$menuTipsoClass = '';
if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){
	$menuTipsoClass = 'hover-tipso-tooltip';
}
?>
<li class="menu-item-has-children toolip-wrapo <?php echo esc_attr( in_array($reference,$parent_active) ? 'wt-open' : ''); ?>">
	<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>
	<a href="#" class="<?php echo esc_attr($menuTipsoClass);?>">
		<i class="ti-settings"></i>
		<span><?php esc_html_e('Settings','workreap');?></span>
	</a>
	<ul class="sub-menu" <?php echo in_array($reference,$parent_active) ? 'style="display: block;"' : ''; ?>>
		<li class="toolip-wrapo <?php echo esc_attr( $reference === 'profile' ? 'wt-active' : ''); ?>">
			<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_identity, '','settings'); ?>">
				<span><?php esc_html_e('Edit my profile','workreap');?></span>
				<?php do_action('workreap_get_tooltip','element','profile-settings');?>
			</a>
		</li>
		<?php if(!empty($display_payout) && $display_payout === 'yes'){?>
			<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'payouts' ? 'wt-active' : ''); ?>">
				<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('payouts', $user_identity,'','settings'); ?>">
					<span><?php esc_html_e('Payouts settings','workreap');?></span>
					<?php do_action('workreap_get_tooltip','element','payouts-settings');?>
				</a>
			</li>
		<?php }?>
		<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'account-settings' ? 'wt-active' : ''); ?>">
			<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'manage'); ?>">
				<span><?php esc_html_e('Account settings','workreap');?></span>
				<?php do_action('workreap_get_tooltip','element','account-settings');?>
			</a>
		</li>
	</ul>
	<?php if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){?>
		<script type="text/template" id="sub-menu-items">
			<ul class="tipso-menu-items" <?php echo in_array($reference,$parent_active) ? 'style="display: block;"' : ''; ?>>
				<li class="toolip-wrapo <?php echo esc_attr( $reference === 'profile' ? 'wt-active' : ''); ?>">
					<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('profile', $user_identity, '','settings'); ?>">
						<span><?php esc_html_e('Edit my profile','workreap');?></span>
						<?php do_action('workreap_get_tooltip','element','profile-settings');?>
					</a>
				</li>
				<?php if(!empty($display_payout) && $display_payout === 'yes'){?>
					<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'payouts' ? 'wt-active' : ''); ?>">
						<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('payouts', $user_identity,'','settings'); ?>">
							<span><?php esc_html_e('Payouts settings','workreap');?></span>
							<?php do_action('workreap_get_tooltip','element','payouts-settings');?>
						</a>
					</li>
				<?php }?>
				<li class="toolip-wrapo <?php echo esc_attr( $reference  === 'account-settings' ? 'wt-active' : ''); ?>">
					<hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'manage'); ?>">
						<span><?php esc_html_e('Account settings','workreap');?></span>
						<?php do_action('workreap_get_tooltip','element','account-settings');?>
					</a>
				</li>
			</ul>
		</script>
	<?php }?>
</li>

