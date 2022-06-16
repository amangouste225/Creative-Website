<?php 
/**
 *
 * The template part for displaying the template reset password
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
$account_types_permissions	= '';
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$account_types_permissions 	= fw_get_db_settings_option( 'account_types_permissions', $default_value = null );
}
?>
<div class="wt-yourdetails wt-tabsinfo wt-reset-password">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Reset Password', 'workreap'); ?></h2>
	</div>
	<?php 
		if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
			$switch_user_id	= get_user_meta($current_user->ID, 'switch_user_id', true); 
			$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
			if(!empty($switch_user_id)){ ?>
				<div class="wt-description">
					<p><?php esc_html_e('If you will change the password, please note this will be updated for your both accounts, if you have switched the profiles','workreap');?></p>
				</div>
			<?php } ?>
		<?php } ?>
	<form class="wt-formtheme wt-userform changepassword-user-form">
		<fieldset>
			<div class="form-group form-group-half">
				<input type="password" name="password" class="form-control" placeholder="<?php esc_attr_e('Your current password', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="password" name="retype" class="form-control" placeholder="<?php esc_attr_e('New Password', 'workreap'); ?>">
			</div>
			<div class="form-group form-group-half wt-btnarea">
				<a href="#" onclick="event_preventDefault(event);" class="wt-btn change-password"><?php esc_html_e('Change Password','workreap');?></a>
			</div>
		</fieldset>
	</form>
</div>
