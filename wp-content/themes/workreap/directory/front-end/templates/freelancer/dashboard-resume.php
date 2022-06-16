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
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

if (function_exists('fw_get_db_post_option')) {
	$resume  = fw_get_db_post_option($post_id, 'resume', true);
	$upload_resume 	= fw_get_db_settings_option( 'upload_resume', $default_value = null );
}
$rand 			= rand(9999, 999);
if( !empty( $upload_resume ) && $upload_resume === 'yes' ){ ?>
<div class="wt-profilephoto wt-tabsinfo resume">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Upload Resume', 'workreap'); ?></h2>
	</div>
	<div class="wt-profilephotocontent">		
		<div class="form-group form-group-label">
			<div class="wt-formtheme wt-formresumeinfo wt-formcategory">
				<div class="" id="wt-resume-container">
					<div class="wt-labelgroup" id="resume-drag">
						<label for="file" class="wt-resume-file">
							<span class="wt-btn" id="resume-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
						<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
					<?php 
					if( !empty( $resume ) ){
						$document_name   = esc_html( get_the_title( $resume['attachment_id'] ));
						$file_size       = !empty( get_attached_file( $resume['attachment_id'] ) ) ? filesize( get_attached_file( $resume['attachment_id'] ) ) : '';
						$filetype        = wp_check_filetype( $resume['url'] );
						$extension       = !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
						$file_detail         = Workreap_file_permission::getDecrpytFile($resume);
						$name                = $file_detail['filename'];
						?>
						<ul class="wt-attachfile wt-resume wt-doc-parent">
							<li class="attachment-new-item">
								<span><?php echo esc_html( $name ); ?></span>
								<em><?php esc_html_e('File size:', 'workreap'); ?>&nbsp;<?php echo esc_html( size_format($file_size, 2) ); ?>&nbsp;<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
								<input type="hidden" class="attachment_url" name="basics[resume][attachment_id]" value="<?php echo intval( $resume['attachment_id'] ); ?>">	
								<input type="hidden" class="attachment_url" name="basics[resume][url]" value="<?php echo esc_url( $resume['url'] ); ?>">	
							</li>
						</ul>
					<?php }?>
				</div>											
			</div>
		</div>	
	</div>
</div>
<script type="text/template" id="tmpl-load-resume-attachments">
	<ul class="wt-attachfile uploaded-placeholder wt-resume wt-doc-parent">
		<li class="wt-uploading attachment-new-item" id="thumb-{{data.id}}">
			<span class="uploadprogressbar uploadprogressbar-0"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
			<input type="hidden" class="attachment_url" name="basics[resume]" value="{{data.url}}">	
		</li>
	</ul>
</script>
<?php }