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
if (function_exists('fw_get_db_post_option') ) {
	$remove_service_addon		= fw_get_db_settings_option('remove_service_addon');
	$service_quote		= fw_get_db_settings_option('service_quote');	
}

$remove_service_addon	= !empty($remove_service_addon) ? $remove_service_addon : 'no';
$service_quote			= !empty($service_quote) ? $service_quote : 'no';

$menuTipsoClass = '';
if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){
	$menuTipsoClass = 'hover-tipso-services';
}

$parent_active	= array('micro_service','addons_service','services');
if( apply_filters('workreap_system_access','service_base') === true ){ ?>
	<li class="menu-item-has-children toolip-wrapo <?php echo esc_attr( in_array($reference,$parent_active) ? 'wt-open' : ''); ?>">
		<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>
		<a href="#" class="<?php echo esc_attr($menuTipsoClass);?>">
			<i class="ti-pencil-alt"></i>
			<span><?php esc_html_e('Manage services','workreap');?></span>
			<?php do_action('workreap_get_tooltip','element','manage-services');?>
		</a>
		
		<ul class="sub-menu" <?php echo in_array($reference,$parent_active) ? 'style="display: block;"' : ''; ?>>
			<?php if ( apply_filters('workreap_system_access', 'service_base') === true) { ?><li class="<?php echo esc_attr( $mode === 'post_service' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity,'','post_service'); ?>"><?php esc_html_e('Post a service','workreap');?></a></li><?php } ?>

			<?php if ( !empty($service_quote) && $service_quote === 'yes' ) { ?>
				<li class="<?php echo esc_attr( $mode === 'post_quote' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity,'','post_quote'); ?>"><?php esc_html_e('Send a quote','workreap');?></a></li>
				<li class="<?php echo esc_attr( $mode === 'quote_listing' || $mode === 'edit' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','quote_listing'); ?>"><?php esc_html_e('Quote listing','workreap');?></a></li>
			<?php } ?>

			<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','posted'); ?>"><?php esc_html_e('Posted services','workreap');?></a></li>
			<?php if(!empty($remove_service_addon) && $remove_service_addon === 'no'){?>
				<li class="<?php echo esc_attr( $reference === 'addons_service' && $mode === 'listing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('addons_service', $user_identity,'','listing'); ?>"><?php esc_html_e('Addons Services','workreap');?></a></li>
			<?php }?>
			<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing services','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed services','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled services','workreap');?></a></li>
		</ul>

		<?php if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){?>
			<script type="text/template" id="sub-menu-services">
				<ul class="tipso-menu-items" <?php echo in_array($reference,$parent_active) ? 'style="display: block;"' : ''; ?>>
					<?php if ( apply_filters('workreap_system_access', 'service_base') === true) { ?><li class="<?php echo esc_attr( $mode === 'post_service' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity,'','post_service'); ?>"><?php esc_html_e('Post a service','workreap');?></a></li><?php } ?>

					<?php if ( !empty($service_quote) && $service_quote === 'yes' ) { ?>
						<li class="<?php echo esc_attr( $mode === 'post_quote' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity,'','post_quote'); ?>"><?php esc_html_e('Send a quote','workreap');?></a></li>
						<li class="<?php echo esc_attr( $mode === 'quote_listing' || $mode === 'edit' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','quote_listing'); ?>"><?php esc_html_e('Quote listing','workreap');?></a></li>
					<?php } ?>

					<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','posted'); ?>"><?php esc_html_e('Posted services','workreap');?></a></li>
					<?php if(!empty($remove_service_addon) && $remove_service_addon === 'no'){?>
						<li class="<?php echo esc_attr( $reference === 'addons_service' && $mode === 'listing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('addons_service', $user_identity,'','listing'); ?>"><?php esc_html_e('Addons Services','workreap');?></a></li>
					<?php }?>

					<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing services','workreap');?></a></li>

					<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed services','workreap');?></a></li>
					
					<li class="<?php echo esc_attr( $reference === 'services' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('services', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled services','workreap');?></a></li>
				</ul>
			</script>
		<?php }?>
	</li>
<?php }?>