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
if ( function_exists('fw_get_db_post_option' )) {
	$identity_verification    	= fw_get_db_settings_option('identity_verification');
}

$user_identity  = $current_user->ID;
$post_id		= workreap_get_linked_profile_id( $user_identity );

$user_type	= apply_filters('workreap_get_user_type', $user_identity );
if( !empty($user_type) && $user_type === 'employer' ){
	if ( function_exists('fw_get_db_post_option' )) {
		$identity_verification    	= fw_get_db_settings_option('employer_identity_verification');
	}
}


if(!empty($identity_verification) && $identity_verification === 'yes'){
	$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
	$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
	
	$is_verified	= get_post_meta($post_id, 'identity_verified', true);
	
	$icon	= 'ti-check';
	if( empty( $is_verified ) ){
		$icon	= 'ti-close';
	}
				
	?>
	<li class="toolip-wrapo <?php echo esc_attr( $reference === 'identity' ? 'wt-active' : ''); ?>">
		<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('identity', $user_identity); ?>">
			<i class="ti-check-box"></i>
			<span><?php esc_html_e('Identity verification','workreap');?></span>
			<em class="wtunread-count wtidentity-verified <?php echo esc_attr($icon);?>"></em>
		</a>
	</li>
<?php }