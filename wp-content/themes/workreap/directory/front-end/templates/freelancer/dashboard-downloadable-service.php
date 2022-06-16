<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);

$post_id 					= $linked_profile;

$edit_id 					= !empty($_GET['id']) ? intval($_GET['id']) : '';
$is_downloadable			= !empty( $edit_id ) ? get_post_meta($edit_id,'_downloadable',true) : '';
$is_downloadable			= empty( $is_downloadable ) || $is_downloadable === 'no' ? 'style="display:none;"' : '';

$downloadable_files 		= !empty( $edit_id ) ? get_post_meta($edit_id,'_downloadable_files',true) : '';
$downloadable_files			= !empty( $downloadable_files ) ? $downloadable_files : array();
?>
<div class="wt-addprojectsholder services-holder-wrap wt-tabsinfo" <?php echo do_shortcode( $is_downloadable );?> >
	<div class="wt-tabscontenttitle wt-addnew">
		<h2><?php esc_html_e('Add Your Files', 'workreap'); ?></h2>
		<span class="wt-add-files"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add File', 'workreap'); ?></a></span>
	</div>
	<ul class="wt-experienceaccordion accordion">
		<?php 
		if( !empty( $downloadable_files ) && is_array($downloadable_files) ) {		
			$counter = 0;
			foreach ($downloadable_files as $key => $value) {
				$counter++;
				$count_rand = rand(89878, 2222);
				$title   	= !empty( $value['name'] ) ? $value['name'] : '';
				$link    	= !empty( $value['url'] ) ? $value['url'] : '#';
				$attachment_id   	= !empty( $value['attachment_id'] ) ? $value['attachment_id'] : '';
				$file_detail         = Workreap_file_permission::getDecrpytFile($value);
				$name                = $file_detail['filename'];
				
				if( !empty( $title ) || !empty( $link )){
			?>
			<li id="wt-files-<?php echo esc_attr( $count_rand ); ?>" data-id="<?php echo esc_attr( $count_rand ); ?>" class="wt-placehoder-img">
				<div class="wt-accordioninnertitle">
					<div class="wt-projecttitle collapsed" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $count_rand ); ?>">											
						<h3><span class="head-title"><?php echo esc_html( $name ); ?></span></h3>
					</div>
					<div class="wt-rightarea">
						<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $count_rand ); ?>"><i class="lnr lnr-pencil"></i></a>
						<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
					</div>
				</div>
				<div class="wt-collapseexp collapse" id="innertitle<?php echo esc_attr( $count_rand ); ?>" aria-labelledby="accordioninnertitle" data-parent="#accordion">
					<div class="wt-formtheme wt-userform wt-formprojectinfo">
						<fieldset>
							<div class="form-group form-group-half elm-display-none">
								<input type="text" name="service[downloadable_files][<?php echo esc_attr( $count_rand ); ?>][name]" id="wt-file-title-<?php echo esc_attr( $count_rand ); ?>" class="wt-input-title form-control" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php echo esc_attr_e('File name', 'workreap'); ?>">
								<input type="hidden" id="wt-file-attachment_id-<?php echo esc_attr( $count_rand ); ?>" name="service[downloadable_files][<?php echo esc_attr( $count_rand ); ?>][attachment_id]" class="wt-input-title form-control" value="<?php echo intval( $attachment_id ); ?>">
							</div>
							<div class="form-group form-group-half elm-display-none">
								<input type="text" id="wt-file-url-<?php echo esc_attr( $count_rand ); ?>" name="service[downloadable_files][<?php echo esc_attr( $count_rand ); ?>][url]" class="wt-input-subtitle form-control" value="<?php echo esc_attr( $link ); ?>" placeholder="<?php esc_attr_e('File URL', 'workreap'); ?>">
							</div>
							<div class="form-group form-group-label" id="wt-files-container-<?php echo esc_attr( $count_rand ); ?>">
								<div class="wt-labelgroup" id="award-drag-<?php echo esc_attr( $count_rand ); ?>">
									<label for="file" class="wt-files-file">
										<span class="wt-btn" id="award-btn-<?php echo esc_attr( $count_rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>								
									</label>
									<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
									<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
								</div>
							</div>												
						</fieldset>
					</div>
				</div>
				<?php
					$inline_script = 'jQuery(document).on("ready", function() { init_files_uploader("' . esc_js( $count_rand ). '", "services"); });';
					wp_add_inline_script( 'workreap-user-dashboard', $inline_script, 'after' );
				?>
			</li>
		<?php } } } ?>
	</ul>
</div>
<script type="text/template" id="tmpl-load-files">
<li id="wt-files-{{data.counter}}" data-id="{{data.counter}}" class="wt-placehoder-img">
	<div class="wt-accordioninnertitle">
		<div class="wt-projecttitle collapsed" data-toggle="collapse" data-target="#innertitle-{{data.counter}}">
			<h3><span class="head-title" id="wt-head-title-{{data.counter}}"><?php esc_html_e('File Name', 'workreap'); ?></span></h3>
		</div>
		<div class="wt-rightarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" data-toggle="collapse" data-target="#innertitle-{{data.counter}}"><i class="lnr lnr-pencil"></i></a>
			<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
		</div>
	</div>
	<div class="wt-collapseexp collapse" id="innertitle-{{data.counter}}" aria-labelledby="accordioninnertitle" data-parent="#accordion">
		<div class="wt-formtheme wt-userform wt-formprojectinfo">
			<fieldset>
				<div class="form-group form-group-half">
					<input type="text" name="service[downloadable_files][{{data.counter}}][name]" id="wt-file-title-{{data.counter}}" class="wt-input-title form-control" placeholder="<?php esc_attr_e('File Name', 'workreap'); ?>">
					<input type="hidden" name="service[downloadable_files][{{data.counter}}][attachment_id]" id="wt-file-attachment_id-{{data.counter}}" class="wt-input-title form-control">
				</div>
				<div class="form-group form-group-half">
					<input type="text" name="service[downloadable_files][{{data.counter}}][url]" id="wt-file-url-{{data.counter}}" class="wt-input-subtitle form-control" placeholder="<?php esc_attr_e('File URL', 'workreap'); ?>">
				</div>
				<div class="form-group form-group-label" id="wt-files-container-{{data.counter}}">
					<div class="wt-labelgroup" id="award-drag-{{data.counter}}">
						<label for="file">
							<span class="wt-btn" id="award-btn-{{data.counter}}"><?php esc_html_e('Select file', 'workreap'); ?></span>	
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
						<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>						
			</fieldset>
		</div>
	</div>
</li>
</script>