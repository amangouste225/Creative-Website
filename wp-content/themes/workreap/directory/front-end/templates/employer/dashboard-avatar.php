<?php 
/**
 *
 * The template part for displaying the employer profile avatar
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

if( has_post_thumbnail($post_id) ){
	$attachment_id 			= get_post_thumbnail_id($post_id);
	$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'workreap_freelancer', true ) : '';
	$file_size 				= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
	$document_name   		= !empty( $attachment_id ) ? esc_html( get_the_title( $attachment_id ) ) : '';
	$filetype        		= !empty( $image_url[0] ) ? wp_check_filetype( $image_url[0] ) : '';
	$extension       		= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
}

$rand 			= rand(9999, 999);
?>
<div class="wt-profilephoto wt-tabsinfo wt-profile-employer-avatar">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Profile Photo', 'workreap'); ?></h2>
	</div>
	<div class="wt-profilephotocontent">		
		<div class="wt-formtheme wt-formprojectinfo wt-formcategory" id="wt-img-<?php echo esc_attr( $rand ); ?>">
			<fieldset>
				<div class="form-group form-group-label" id="wt-image-container-<?php echo esc_attr( $rand ); ?>">
					<div class="wt-labelgroup"  id="image-drag-<?php echo esc_attr( $rand ); ?>">
						<label for="file" class="wt-image-file">
							<span class="wt-btn" id="image-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
						<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
					<?php if( !empty( $image_url[0] ) ){ ?>
						<ul class="wt-attachfile wt-attachfilevtwo">						
							<li class="wt-uploadingholder wt-companyimg-user">
								<div class="wt-uploadingbox">
									<figure><img class="img-thumb" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
									<div class="wt-uploadingbar">
										<span class="uploadprogressbar"></span>
										<span><?php echo esc_html( $document_name ); ?></span>
										<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo esc_html( size_format($file_size, 2) ); ?><a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>
									</div>	
									<input type="hidden" name="basics[avatar][attachment_id]" value="<?php echo esc_attr( $attachment_id ); ?>">	
								</div>
							</li>						
						</ul>						
					<?php } ?>
				</div>		
			</fieldset>
		</div>
	</div>
</div>

<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader_v2("' . esc_js( $rand ). '", "profile"); });';
	wp_add_inline_script( 'workreap-user-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-default-image">
	<ul class="wt-attachfile wt-attachfilevtwo">
		<li class="award-new-item wt-uploadingholder wt-doc-parent" id="thumb-{{data.id}}">
			<div class="wt-uploadingbox">
				<figure><img class="img-thumb" src="<?php echo esc_url( get_template_directory_uri());?>/images/profile.jpg" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
				<div class="wt-uploadingbar wt-uploading">
					<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
					<span>{{data.name}}</span>
					<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>	
				</div>	
			</div>
		</li>
	</ul>	
</script>
<script type="text/template" id="tmpl-load-profile-image">
	<div class="wt-uploadingbox">
		<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="wt-uploadingbar">
			<span class="uploadprogressbar"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>
			<input type="hidden" name="basics[avatar]" value="{{data.url}}">	
		</div>	
	</div>	
</script>