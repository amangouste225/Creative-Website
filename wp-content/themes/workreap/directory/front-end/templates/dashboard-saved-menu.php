<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 			= $current_user->ID;
$save_service_url 		= Workreap_Profile_Menu::workreap_profile_menu_link('saved', $user_identity, true,'service');
$save_jobs_url	 		= Workreap_Profile_Menu::workreap_profile_menu_link('saved', $user_identity, true,'');
$saved_compaines_url	= Workreap_Profile_Menu::workreap_profile_menu_link('saved', $user_identity, true,'employer');
$saved_freelancer_url	= Workreap_Profile_Menu::workreap_profile_menu_link('saved', $user_identity, true,'freelancer');
$mode 			 		= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'jobs';
?>
<div class="wt-dashboardtabs">
	<ul class="wt-tabstitle nav navbar-nav">
		<?php if( apply_filters('workreap_system_access','job_base') === true ){ ?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'jobs' ? 'active' : '';?>" href="<?php echo esc_url( $save_jobs_url );?>">
					<?php esc_html_e('Saved Jobs', 'workreap'); ?>
				</a>
			</li>
		<?php }?>
		<?php if( apply_filters('workreap_system_access','service_base') === true ){ ?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'service' ? 'active' : '';?>" href="<?php echo esc_url( $save_service_url );?>">
					<?php esc_html_e('Saved Services', 'workreap'); ?>
				</a>
			</li>
		<?php } ?>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'employer' ? 'active' : '';?>" href="<?php echo esc_url( $saved_compaines_url );?>">
				<?php esc_html_e('Followed Companies', 'workreap'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'freelancer' ? 'active' : '';?>" href="<?php echo esc_url( $saved_freelancer_url );?>">
				<?php esc_html_e('Liked Freelancers', 'workreap'); ?>
			</a>
		</li>
	</ul>
</div>