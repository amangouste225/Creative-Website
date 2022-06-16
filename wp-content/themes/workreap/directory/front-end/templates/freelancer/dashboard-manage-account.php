<?php 
/**
 *
 * The template part for displaying the template to manage account
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
$user_type	 	 = apply_filters('workreap_get_user_type', $user_identity );
$settings		 = workreap_get_account_settings($user_type);  
?>
<form class="wt-formtheme wt-userform wt-save-account-settings">
	<div class="wt-securitysettings wt-tabsinfo wt-delete-account wt-haslayout">
		<div class="wt-tabscontenttitle">
			<h2><?php esc_html_e('Manage Account', 'workreap'); ?></h2>
		</div>
		<div class="wt-settingscontent">
			<div class="wt-description">
				<p><?php esc_html_e('To hide your profile all over the site you can disable your profile temporarily','workreap');?></p>
			</div>

			<div class="wt-formtheme wt-userform">
				<?php if( !empty( $settings ) ){?>
				<ul class="wt-accountinfo">
					<?php 
					foreach( $settings as $key => $value ){
						$db_val 	= get_post_meta($linked_profile, $key, true);
						$db_val 	= !empty( $db_val ) ?  $db_val : 'off';
					?>
					<li>
						<div class="wt-on-off">
							<input type="hidden" name="settings[<?php echo esc_attr($key); ?>]" value="off">
							<input type="checkbox" <?php checked( $db_val, 'on' ); ?>  value="on" id="<?php echo esc_attr( $key );?>" name="settings[<?php echo esc_attr( $key );?>]">
							<label for="<?php echo esc_attr( $key );?>"><i></i></label>
						</div>
						<span><?php echo esc_html( $value );?></span>
					</li>
					<?php }?>
				</ul>
				<?php }?>
			</div>
			
		</div>
	</div>
	<div class="form-group form-group-half wt-btnarea">
		<?php wp_nonce_field('wt_account_save_nonce', 'account_save'); ?>
		<a href="#" onclick="event_preventDefault(event);" class="wt-btn save-account-settings"><?php esc_html_e('Save Account Settings','workreap');?></a>
	</div>
</form>