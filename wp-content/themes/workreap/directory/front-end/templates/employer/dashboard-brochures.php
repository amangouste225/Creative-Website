<?php 
/**
 *
 * The template part for adding employer brochures
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;

$brochures = array();
if (function_exists('fw_get_db_post_option')) {
	$brochures	= fw_get_db_post_option($post_id, 'brochures');
	$hide_brochures       = fw_get_db_settings_option('hide_brochures', 'no');
}

if(!empty($hide_brochures) && $hide_brochures == 'no'){
?>
<div class="wt-profilephoto wt-tabsinfo wt-profile-brochures">
	<div class="wt-profilephotocontent">		
		<div class="wt-jobdetails wt-attachmentsholder upload-documents documents-wrap">
			<div class="wt-tabscontenttitle">
				<h2><?php esc_html_e('Upload Brochures','workreap');?></h2>
			</div>
			<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
				<fieldset>
					<div class="form-group form-group-label" id="wt-employer-brochures-container">
						<div class="wt-labelgroup" id="employer-brochures-drag">
							<label for="file" class="wt-job-file">
								<span class="wt-btn" id="employer-brochures-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>								
							</label>
							<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
							<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
						</div>
					</div>
					<div class="form-group">
						<ul class="wt-attachfile uploaded-brochures-placeholder">
							<?php 
							if( !empty( $brochures ) ){
								foreach( $brochures as $key => $doc ){
									$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
									$file_size 		= !empty( $doc) ? filesize(get_attached_file($attachment_id)) : '';
									$document_name	= !empty( $doc ) ? get_the_title( $attachment_id ) : '';
									$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
									$file_detail         = Workreap_file_permission::getDecrpytFile($doc);
									$name                = $file_detail['filename'];
									?>
								<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
									<span><?php echo esc_html($name);?></span>
									<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
									<input type="hidden" class="attachment_url" name="basics[brochures][<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
									<input type="hidden" class="attachment_url" name="basics[brochures][<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_attr($doc_url);?>">
								</li>
							<?php } } ?>													
						</ul>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="tmpl-load-employer-brochures">
	<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
		<span class="uploadprogressbar" style="width:0%"></span>
		<span>{{data.name}}</span>
		<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
		<input type="hidden" class="attachment_url" name="basics[brochures][]" value="{{data.url}}">	
	</li>
</script>
<?php }