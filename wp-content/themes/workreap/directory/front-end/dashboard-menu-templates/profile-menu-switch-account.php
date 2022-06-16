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
$hide_switch_account	= '';
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$hide_switch_account 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
} 
$switch_user_id	= get_user_meta($user_identity, 'switch_user_id', true); 
$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
$switch_class	= '';
if(!empty($switch_user_id) ){
	$switch_class	= 'wt-switch-user-profile-menu';
}
if( !empty( $user_type ) && $hide_switch_account === 'yes' ){?>
	<li class="toolip-wrapo wt-switch-user-menu <?php echo esc_attr( $reference === 'switch' ? 'wt-active' : ''); ?>">
		<a href="#" onclick="event_preventDefault(event);" class="wt-switch-user <?php echo esc_attr($switch_class);?>">
			<?php 
				if(!empty($switch_user_id) ){
					$switch_user_type		= apply_filters('workreap_get_user_type', $switch_user_id );
					$switch_link_id			= workreap_get_linked_profile_id( $switch_user_id );
					$username 				= workreap_get_username($switch_user_id);
					$avatar					= '';
					if ( $switch_user_type === 'employer' ){
						$role	= esc_html__('Employer','workreap');
						$avatar = apply_filters(
												'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 50, 'height' => 50), $switch_link_id), array('width' => 50, 'height' => 50) 
											);
					} else{
						$role	= esc_html__('Freelancer','workreap');
						$avatar = apply_filters(
												'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar(array('width' => 50, 'height' => 50), $switch_link_id), array('width' => 50, 'height' => 50) 
											);
					}
			?>
				<figure class="wt-userimg wt-notify-<?php do_action('workreap_count_unread_push_notification',$switch_user_id);?>">
					<img src="<?php echo esc_url($avatar); ?>" alt="<?php esc_attr_e('Profile Avatar', 'workreap'); ?>">
					<em class="wtunread-count"><?php do_action('workreap_count_unread_push_notification',$switch_user_id);?></em>
				</figure>
				<span>
					<?php echo esc_html($username);?>
					<?php if( !empty($switch_user_type) ){?><em><?php echo esc_html($role);?></em><?php } ?>
				</span>
			<?php } else {?>
				<i class="ti-control-shuffle"></i>
				<span><?php esc_html_e('Switch account','workreap');?></span>
			<?php } ?>
		</a>
		<?php
			if(!empty($switch_user_id) ){
				do_action('workreap_get_tooltip','element','switch-account-user');
			} else {
				do_action('workreap_get_tooltip','element','switch-account');
			}
		?>
	</li>
<?php }
