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
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_type	= apply_filters('workreap_get_user_type', $user_identity );

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$delete_account_hide = fw_get_db_settings_option('delete_account_hide');
}

$delete_account_hide = !empty($delete_account_hide) ?  $delete_account_hide : 'no';
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 pull-lg-3 float-left">
	<div class="wt-haslayout wt-account-settings">
		<div class="wt-dashboardbox wt-dashboardtabsholder wt-accountsettingholder">
			<div class="wt-dashboardtabs">
				<ul class="wt-tabstitle nav navbar-nav">
					<li class="nav-item wtmanage-account">
						<a class="<?php echo esc_attr( $mode === 'manage' ? 'active' : ''); ?>" href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'manage'); ?>"><?php esc_html_e('Manage Account','workreap');?></a>
					</li>
					<li class="nav-item wtaccount-settings"><a class="<?php echo esc_attr( $mode === 'billing' ? 'active' : ''); ?>"  href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'billing'); ?>"><?php esc_html_e('Billing address','workreap');?></a></li>
					<li class="nav-item wtaccount-settings"><a class="<?php echo esc_attr( $mode === 'password' ? 'active' : ''); ?>"  href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'password'); ?>"><?php esc_html_e('Password','workreap');?></a></li>
					<li class="nav-item wtemail-settings"><a class="<?php echo esc_attr( $mode === 'emails' ? 'active' : ''); ?>"  href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'emails'); ?>"><?php esc_html_e('Email Notifications','workreap');?></a></li>
					<?php if( !empty($delete_account_hide) && $delete_account_hide === 'no' ){?>
						<li class="nav-item wtdelete-account"><a class="<?php echo esc_attr( $mode === 'delete' ? 'active' : ''); ?>"  href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('account-settings', $user_identity,false,'delete'); ?>"><?php esc_html_e('Delete Account','workreap');?></a></li>
					<?php }?>
				</ul>
			</div>
			<div class="wt-tabscontent">
				<?php if ( ( isset($_GET['ref']) && $_GET['ref'] === 'account-settings' )  && ( isset($_GET['mode']) && $_GET['mode'] === 'manage' ) ) {?>			
					<div class="wt-securityhold" id="wt-account">
						<?php get_template_part('directory/front-end/templates/'.$user_type.'/dashboard', 'manage-account'); ?>	
					</div>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'account-settings' )  && ( isset($_GET['mode']) && $_GET['mode'] === 'password' ) ) {?>	
					<div class="wt-passwordholder" id="wt-password">
						<?php get_template_part('directory/front-end/templates/dashboard', 'reset-password'); ?>
					</div>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'account-settings' )  && ( isset($_GET['mode']) && $_GET['mode'] === 'emails' ) ) {?>	
					<div class="wt-emailnotiholder" id="wt-emailnoti">
						<?php get_template_part('directory/front-end/templates/dashboard', 'email-notifications'); ?>
					</div>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'account-settings' )  && ( isset($_GET['mode']) && $_GET['mode'] === 'delete' ) ) {?>	
					<?php if( !empty($delete_account_hide) && $delete_account_hide === 'no' ){?>
						<div class="wt-accountholder" id="wt-deleteaccount">
							<?php get_template_part('directory/front-end/templates/dashboard', 'delete-account'); ?>	
						</div>
					<?php }?>
				<?php }else if ( ( isset($_GET['ref']) && $_GET['ref'] === 'account-settings' )  && ( isset($_GET['mode']) && $_GET['mode'] === 'billing' ) ) {?>	
					<div class="wt-accountholder" id="wt-deleteaccount">
						<?php get_template_part('directory/front-end/templates/dashboard', 'billing-details'); ?>	
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	</div>
<?php }?>

