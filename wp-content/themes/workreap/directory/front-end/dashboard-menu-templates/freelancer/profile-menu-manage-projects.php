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

$menuTipsoClass = '';
if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){
	$menuTipsoClass = 'hover-tipso-projects';
}

if( apply_filters('workreap_system_access','job_base') === true ){ ?>
	<li class="menu-item-has-children toolip-wrapo <?php echo esc_attr( $reference === 'projects' ? 'wt-open' : ''); ?>">
		<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>
		<a href="#" class="<?php echo esc_attr($menuTipsoClass);?>">
			<i class="ti-bag"></i>
			<span><?php esc_html_e('Manage projects','workreap');?></span>
			<?php do_action('workreap_get_tooltip','element','manage-projects');?>
		</a>
		
		<ul class="sub-menu" <?php echo esc_attr( $reference === 'projects' || $reference === 'proposals' ) ? 'style="display: block;"' : ''; ?>>
			<li class="<?php echo esc_attr( $reference === 'proposals' && $mode === 'projects' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_identity,'','projects'); ?>"><?php esc_html_e('Proposals','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing projects','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed projects','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled projects','workreap');?></a></li>
		</ul>
		<?php if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){?>
			<script type="text/template" id="sub-menu-projects">
				<ul class="tipso-menu-items" <?php echo esc_attr( $reference === 'projects' || $reference === 'proposals' ) ? 'style="display: block;"' : ''; ?>>
					<li class="<?php echo esc_attr( $reference === 'proposals' && $mode === 'projects' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('proposals', $user_identity,'','projects'); ?>"><?php esc_html_e('Proposals','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing projects','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed projects','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'projects' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('projects', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled projects','workreap');?></a></li>
				</ul>
			</script>
		<?php }?>
	</li>
<?php } ?>