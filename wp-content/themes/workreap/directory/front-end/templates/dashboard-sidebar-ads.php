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
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
?>
<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
	<div class="wt-haslayout wt-dashside">
		<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
	</div>
<?php }?>