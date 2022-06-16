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
$portfolio_settings	= apply_filters('workreap_portfolio_settings','gadget');
if( isset($portfolio_settings) && $portfolio_settings == 'enable' ){
	$menuTipsoClass = '';
	if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){
		$menuTipsoClass = 'hover-tipso-portfolio';
	}

?>
<li class="menu-item-has-children toolip-wrapo <?php echo esc_attr( $reference === 'portfolios' ? 'wt-open' : ''); ?>">
	<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>
	<a href="#" class="<?php echo esc_attr($menuTipsoClass);?>">
		<i class="ti-pencil-alt"></i>
		<span><?php esc_html_e('Manage portfolios', 'workreap');?></span>
		<?php do_action('workreap_get_tooltip','element','manage-portfolios');?>
	</a>
	<ul class="sub-menu" <?php echo esc_attr( $reference ) === 'portfolios' ? 'style="display: block;"' : ''; ?>>
		<li class="<?php echo esc_attr( $reference === 'portfolios' && $mode === 'add' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $user_identity,'','add'); ?>"><?php esc_html_e('Add portfolio','workreap');?></a></li>
		<li class="<?php echo esc_attr( $reference === 'portfolios' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $user_identity,'','posted'); ?>"><?php esc_html_e('Portfolio listings','workreap');?></a></li>
	</ul>
	<?php if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){?>
		<script type="text/template" id="sub-menu-portfolio">
			<ul class="tipso-menu-items" <?php echo esc_attr( $reference ) === 'portfolios' ? 'style="display: block;"' : ''; ?>>
				<li class="<?php echo esc_attr( $reference === 'portfolios' && $mode === 'add' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $user_identity,'','add'); ?>"><?php esc_html_e('Add portfolio','workreap');?></a></li>
				<li class="<?php echo esc_attr( $reference === 'portfolios' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('portfolios', $user_identity,'','posted'); ?>"><?php esc_html_e('Portfolio listings','workreap');?></a></li>
			</ul>
		</script>
	<?php }?>
</li>
<?php }