<?php 
/**
 *
 * The template part for displaying the employer profile banner
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

if( apply_filters('workreap_is_feature_allowed', 'wt_banner', $user_identity) === true ){
	$banner_image 	= array();
	if (function_exists('fw_get_db_post_option')) {
		$banner_image       = fw_get_db_post_option($post_id, 'banner_image', true);	
	}

	//Banner image
	$banner_file_size 		= !empty( $banner_image['attachment_id']) ? filesize(get_attached_file($banner_image['attachment_id'])) : '';	
	$banner_document_name	= !empty( $banner_image['attachment_id'] ) ? esc_html( get_the_title( $banner_image['attachment_id'] )) : '';
	$banner_filetype        = !empty( $banner_image['attachment_id'] ) ? wp_check_filetype( $banner_image['url'] ) : '';
	$banner_extension  		= !empty( $banner_filetype['ext'] ) ? $banner_filetype['ext'] : '';
	$banner_image_url 		= !empty( $banner_image['attachment_id'] ) ? wp_get_attachment_image_src( $banner_image['attachment_id'], 'workreap_freelancer', true ) : '';

	$banner_rand	= rand(999, 99999);
	?>
	<div class="wt-profilephoto wt-tabsinfo wt-profile-employer-banner">
		<div class="wt-tabscontenttitle">
			<h2><?php esc_html_e('Banner Photo', 'workreap'); ?></h2>
		</div>
		<div class="wt-profilephotocontent">		
			<div class="wt-formtheme wt-formprojectinfo wt-formcategory" id="wt-img-<?php echo esc_attr( $banner_rand ); ?>">
				<fieldset>
					<div class="form-group form-group-label" id="wt-image-container-<?php echo esc_attr( $banner_rand ); ?>">
						<div class="wt-labelgroup"  id="image-drag-<?php echo esc_attr( $banner_rand ); ?>">
							<label for="file" class="wt-image-file">
								<span class="wt-btn" id="image-btn-<?php echo esc_attr( $banner_rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>								
							</label>
							<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
							<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
						</div>
					</div>
					<div class="form-group uploaded-placeholder">
						<?php if( !empty( $banner_image_url[0] ) ){ ?>
							<ul class="wt-attachfile wt-attachfilevtwo">						
								<li class="wt-uploadingholder wt-companyimg-user">
									<div class="wt-uploadingbox">
										<figure><img class="img-thumb" src="<?php echo esc_url( $banner_image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
										<div class="wt-uploadingbar">
											<span class="uploadprogressbar"></span>
											<span><?php echo esc_html( $banner_document_name ); ?></span>
											<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo esc_html( size_format($banner_file_size, 2) ); ?><a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>
										</div>	
										<input type="hidden" name="basics[banner][attachment_id]" value="<?php echo esc_attr( $banner_image['attachment_id'] ); ?>">	
										<input type="hidden" name="basics[banner][url]" value="<?php echo esc_url( $banner_image['url'] ); ?>">	
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
		$inline_script_v = 'jQuery(document).on("ready", function() { init_image_uploader_v2("' . esc_js( $banner_rand ). '", "banner"); });';
		wp_add_inline_script( 'workreap-user-dashboard', $inline_script_v, 'after' );
	?>
	<script type="text/template" id="tmpl-load-default-image">
		<ul class="wt-attachfile wt-attachfilevtwo">
			<li class="award-new-item wt-uploadingholder wt-doc-parent" id="thumb-{{data.id}}">
				<div class="wt-uploadingbox">
					<figure><img class="img-thumb" src="<?php echo esc_url( get_template_directory_uri());?>/images/profile.jpg" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"></figure>
					<div class="wt-uploadingbar wt-uploading">
						<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
						<span>{{data.name}}</span>
						<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>	
					</div>	
				</div>
			</li>
		</ul>	
	</script>
	<script type="text/template" id="tmpl-load-banner-image">
		<div class="wt-uploadingbox">
			<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
			<div class="wt-uploadingbar">
				<span class="uploadprogressbar"></span>
				<span>{{data.name}}</span>
				<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="wt-remove-image lnr lnr-cross"></a></em>
				<input type="hidden" name="basics[banner]" value="{{data.url}}">	
			</div>	
		</div>	
	</script>
<?php } ?>