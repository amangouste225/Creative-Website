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
$user_identity 	= $current_user->ID;
$link_id		= workreap_get_linked_profile_id( $user_identity );
$user_type		= apply_filters('workreap_get_user_type', $user_identity );

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';


if ( apply_filters('workreap_get_user_type', $user_identity) === 'employer' ){
if( apply_filters('workreap_system_access','job_base') === true ){?>
	<li class="create-post-menu"><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('post_job', $user_identity,'','post'); ?>"><i class="ti-pencil"></i><span><?php esc_html_e('Post a job', 'workreap'); ?></span></a></li>
<?php }}  elseif ( apply_filters('workreap_get_user_type', $user_identity) === 'freelancer' ) {
	if ( apply_filters('workreap_system_access', 'service_base') === true) { ?>
		<li class="create-post-menu"><a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('micro_service', $user_identity,'','post_service'); ?>"><i class="ti-pencil"></i><span><?php esc_html_e('Post a service', 'workreap'); ?></span></a></li>
<?php }} ?>
