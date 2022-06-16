<?php 
/**
 *
 * The template part for displaying the template to display email settings
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
<div class="wt-tabsinfo wt-email-settings">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Email Notifications', 'workreap'); ?></h2>
	</div>
	<div class="wt-settingscontent">
		<div class="wt-description">
			<?php 
			if( !empty($account_types_permissions) && $account_types_permissions == 'yes' ){
				$switch_user_id	= get_user_meta($current_user->ID, 'switch_user_id', true); 
				$switch_user_id	= !empty($switch_user_id) ? intval($switch_user_id) : '';
				if(!empty($switch_user_id)){ ?>
					<p><?php esc_html_e('On update the email address, please note this email will be changed for your both accounts, if you have switched the profiles','workreap');?></p>
				<?php } ?>
			<?php } ?>
			<p><?php esc_html_e('All the emails will be sent to the below email address','workreap');?></p>
		</div>
		<form class="wt-formtheme wt-userform email-user-form">
			<fieldset>
				<div class="form-group form-disabeld">
					<input type="email" name="useremail" value="<?php echo esc_attr($current_user->user_email);?>" class="form-control" placeholder="<?php echo esc_attr($current_user->user_email);?>">
				</div>
				<div class="form-group form-group-half wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn change-email"><?php esc_html_e('Change Email','workreap');?></a>
				</div>
			</fieldset>
		</form>
	</div>
</div>
