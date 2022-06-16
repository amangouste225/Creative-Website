<?php
/**
 *
 * The template part for displaying account settings
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	= $current_user->ID;
$linked_profile = workreap_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;
$mode 			= (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$allow_freelancers_withdraw 	= fw_get_db_settings_option( 'allow_freelancers_withdraw', $default_value = null );
} 
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 pull-lg-3 float-left">
	<div class="wt-haslayout wt-account-settings">
		<div class="wt-dashboardbox wt-dashboardtabsholder wt-accountsettingholder wt-payout-holder">
			<div class="wt-dashboardtabs">
				<ul class="wt-tabstitle nav navbar-nav">
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'settings' ? 'active' : ''; ?>" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('payouts', $user_identity,'','settings'); ?>"><?php esc_html_e('Payouts Settings','workreap');?></a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'payments' ? 'active' : ''; ?>" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('payouts', $user_identity,'','payments'); ?>"><?php esc_html_e('Your Payouts','workreap');?></a>
					</li>
				</ul>
			</div>
			<div class="wt-tabscontent">
				<?php if ( ( isset($_GET['ref']) && $_GET['ref'] === 'payouts' )  && ( isset($mode) && $mode === 'settings' ) ) {?>			
					<div class="wt-securityhold" id="wt-account">
						<?php get_template_part('directory/front-end/templates/dashboard', 'payouts-settings'); ?>	
					</div>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'payouts' )  && ( isset($mode) && $mode === 'payments' ) ) {?>
					<div class="wt-securityhold" id="wt-account">
						<?php 
							if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'freelancers'){
								get_template_part('directory/front-end/templates/dashboard', 'widthdrawal'); 
							}else{
								get_template_part('directory/front-end/templates/dashboard', 'payments'); 
							}
						?>	
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	</div>
<?php }?>