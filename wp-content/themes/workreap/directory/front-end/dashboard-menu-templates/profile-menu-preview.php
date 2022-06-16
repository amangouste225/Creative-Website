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
$user_identity 	 = $current_user->ID;
$link_id		 = workreap_get_linked_profile_id( $user_identity );
?>
<li class="toolip-wrapo">
	<a href="<?php echo esc_url(get_the_permalink($link_id));?>" target="_blank">
		<i class="ti-desktop"></i>
		<span><?php esc_html_e('View my profile','workreap');?></span>
		<?php do_action('workreap_get_tooltip','element','preview');?>
	</a>
</li>