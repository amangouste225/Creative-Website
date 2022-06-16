<?php 
/**
 *
 * The template part for displaying the template to delete account
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
$reasons		 = workreap_get_account_delete_reasons();
?>
<div class="wt-yourdetails wt-tabsinfo wt-delete-account">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Delete Account', 'workreap'); ?></h2>
	</div>
	<div class="wt-formtheme">
		<form class="wt-formtheme wt-userform delete-user-form">
			<fieldset>
				<div class="form-group form-group-half">
					<input type="password" name="delete[password]" class="form-control" placeholder="<?php esc_attr_e('Enter Password','workreap');?>">
				</div>
				<div class="form-group form-group-half">
					<input type="password" name="delete[retype]" class="form-control" placeholder="<?php esc_attr_e('Retype Password','workreap');?>">
				</div>
				<div class="form-group">
					<span class="wt-select">
						<select name="delete[reason]">
							<option value=""><?php esc_html_e('Select Reason to Leave','workreap');?></option>
							<?php foreach( $reasons as $key => $value ){?>
								<option value="<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></option>
							<?php }?>
						</select>
					</span>
				</div>
				<div class="form-group">
					<textarea name="delete[description]" class="form-control" placeholder="<?php esc_attr_e('Description (Optional)','workreap');?>"></textarea>
				</div>
				<div class="form-group form-group-half wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn delete-account"><?php esc_html_e('Delete Account','workreap');?></a>
				</div>
			</fieldset>
		</form>
	</div>
</div>
