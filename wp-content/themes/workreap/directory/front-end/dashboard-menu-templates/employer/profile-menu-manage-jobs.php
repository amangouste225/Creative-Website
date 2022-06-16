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
if( apply_filters('workreap_system_access','job_base') === true ){
	$menuTipsoClass = '';
	if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){
		$menuTipsoClass = 'hover-tipso-projects';
	}

?>
	<li class="menu-item-has-children <?php echo esc_attr( $reference === 'jobs' || $reference === 'post_job' ? 'wt-open' : ''); ?>">
		<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>
		<a href="#" class="<?php echo esc_attr($menuTipsoClass);?>">
			<i class="ti-bag"></i>
			<span><?php esc_html_e('Manage jobs','workreap');?></span>
			<?php do_action('workreap_get_tooltip','element','manage-jobs');?>
		</a>
		<ul class="sub-menu" <?php echo esc_attr( $reference === 'jobs' || $reference === 'post_job' ) ? 'style="display: block;"' : ''; ?>>
			<li class="<?php echo esc_attr( $mode === 'post' && $mode === 'post' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_identity,'','post'); ?>"><?php esc_html_e('Post a job','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','posted'); ?>"><?php esc_html_e('Posted jobs','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing jobs','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed jobs','workreap');?></a></li>
			<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled jobs','workreap');?></a></li>
		</ul>

		<?php if( !empty($args['menu_type']) && $args['menu_type'] === 'dashboard-menu-left'  ){?>
			<script type="text/template" id="sub-menu-projects">
				<ul class="tipso-menu-items" <?php echo esc_attr( $reference === 'jobs' || $reference === 'post_job' ) ? 'style="display: block;"' : ''; ?>>
					<li class="<?php echo esc_attr( $mode === 'post' && $mode === 'post' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_identity,'','post'); ?>"><?php esc_html_e('Post a job','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'posted' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','posted'); ?>"><?php esc_html_e('Posted jobs','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'ongoing' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','ongoing'); ?>"><?php esc_html_e('Ongoing jobs','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'completed' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','completed'); ?>"><?php esc_html_e('Completed jobs','workreap');?></a></li>
					<li class="<?php echo esc_attr( $reference === 'jobs' && $mode === 'cancelled' ? 'wt-active' : ''); ?>"><hr><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('jobs', $user_identity,'','cancelled'); ?>"><?php esc_html_e('Cancelled jobs','workreap');?></a></li>
				</ul>
			</script>
		<?php }?>
	</li>
<?php } ?>
