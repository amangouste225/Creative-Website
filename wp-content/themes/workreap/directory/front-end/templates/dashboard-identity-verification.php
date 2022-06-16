<?php 
/**
 *
 * The template part for displaying the freelancer profile avatar
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
if ( function_exists('fw_get_db_post_option' )) {
	$identity_verified_icon    	= fw_get_db_settings_option('identity_verified_image');
}

$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$identity_verified  = get_post_meta($post_id, 'identity_verified', true);
$verification_attachments  = get_post_meta($post_id, 'verification_attachments', true);
$identity_verified_icon = !empty($identity_verified_icon['url']) ? $identity_verified_icon['url'] : get_template_directory_uri().'/images/identity_verified_color.svg';
$user_type		= apply_filters('workreap_get_user_type', $user_identity );
$rand 			= rand(9999, 999);
if(!empty($user_type) && $user_type === 'employer'){
	$identity_message = esc_html__('Congratulation! your identity has been verified, You are ready to post a job', 'workreap');
}else{
	$identity_message = esc_html__('Congratulation! your identity has been verified, You are ready to apply on the jobs or post a service to get orders', 'workreap');
}
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-6 float-left">
	<form class="post-identity-form wt-haslayout wt-attachmentsholder">
		<div class="wt-dashboardbox">
			<div class="wt-dashboardboxtitle wt-titlewithsearch">
				<h2><?php esc_html_e('Upload Identity Information', 'workreap'); ?></h2>
			</div>
			<div class="wt-dashboardboxcontent">
				<div class="wt-helpsupportcontents">
					<?php if(empty($identity_verified) && !empty($verification_attachments) ){?>
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Identity Verification inprogress', 'workreap'); ?></h2>
						</div>
						<div class="wt-description">
							<p><?php esc_html_e('Thank you so much for submitting your identity documents, we will review and send you an email very soon.', 'workreap'); ?></p>
						</div>
						<a class="wt-btn wt-cancel-identity" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Cancel & Re-Upload', 'workreap'); ?></a>
					<?php }else if(!empty($identity_verified) && $identity_verified === '1' && !empty($verification_attachments) ){?>
						<div class="wt-identity-verified wt-haslayout">
							<img src="<?php echo esc_url($identity_verified_icon);?>">
							<p><?php  echo esc_attr($identity_message);?></p>
						</div>
					<?php }else{?>
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Upload identity documents', 'workreap'); ?></h2>
						</div>
						<div class="wt-description">
							<div class="wt-experienceaccordion">
							<p><?php esc_html_e('Please upload your National Identity Card, Passport or Driving License to verifiy your identity, You will not able to apply on a job or post services before verification', 'workreap'); ?></p></div>
						</div>
						<div class="form-group form-group-label">
							<div class="wt-formtheme wt-formidentityinfo wt-formprojectinfo wt-formcategory wt-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" value="" name="basics[name]" class="form-control" placeholder="<?php esc_attr_e('Your name', 'workreap'); ?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" value="" name="basics[contact_number]" class="form-control" placeholder="<?php esc_attr_e('Contact number', 'workreap'); ?>">
									</div>
									<div class="form-group">
										<input type="text" value="" name="basics[verification_number]" class="form-control" placeholder="<?php esc_attr_e('National identity card, passport or driving license number', 'workreap'); ?>">
									</div>
									<div class="form-group">
										<textarea name="basics[address]" class="form-control" placeholder="<?php esc_attr_e('Add address', 'workreap'); ?>"></textarea>
									</div>
									<div class="form-group">
										<div id="wt-identity-container">
											<div class="wt-labelgroup" id="identity-drag">
												<label for="file" class="wt-identity-file">
													<span class="wt-btn" id="identity-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>
												</label>
												<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
												<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
											</div>
										</div>
									</div>
									<div class="form-group">
										<ul class="wt-attachfile uploaded-placeholder"></ul>
									</div>
								</fieldset>											
							</div>
						</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php if(empty($identity_verified) && !empty($verification_attachments) ){?>
			<!--do nothing-->
		<?php }else if(!empty($identity_verified) && $identity_verified === '1' && !empty($verification_attachments) ){?>
			<!--do nothing-->
		<?php }else{?>
			<div class="wt-updatall">
				<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
				<a class="wt-btn wt-save-identity" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
			</div>
		<?php }?>
	</form>
</div>
<script type="text/template" id="tmpl-load-identity-attachments">
	<li class="wt-uploading attachment-new-item" id="thumb-{{data.id}}">
		<span class="uploadprogressbar uploadprogressbar-0"></span>
		<span>{{data.name}}</span>
		<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
		<input type="hidden" class="attachment_url" name="identity[]" value="{{data.url}}">	
	</li>
</script>