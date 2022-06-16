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
$post_id 		= $linked_profile;
$awards 		= array();

if (function_exists('fw_get_db_post_option')) {
	$awards = fw_get_db_post_option($post_id, 'awards', true);	
	$frc_remove_awards = fw_get_db_settings_option('frc_remove_awards', 'no');
}

if(!empty($frc_remove_awards) && $frc_remove_awards === 'no'){
	$default_img = get_template_directory_uri().'/images/awards-65x65.jpg';
	?>
	<div class="wt-awardsdataholder wt-tabsinfo">
		<div class="wt-tabscontenttitle wt-addnew">
			<h2><?php esc_html_e('Add your awards/certifications', 'workreap'); ?></h2>
			<span class="wt-add-award"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add Award', 'workreap'); ?></a></span>
		</div>
		<ul class="wt-experienceaccordion accordion" data-id="<?php echo esc_url( $default_img ); ?>" id="awardssortable">
			<?php 
			if( !empty( $awards ) && is_array($awards) ) {
				$count = 0;
				foreach ($awards as $key => $value) {
					$rand = rand(999999, 99999);
					$count++;
					$title 		= !empty( $value['title'] ) ? stripslashes( $value['title'] ) : '';
					$date 		= !empty( $value['date'] ) ? str_replace('/','-',$value['date']) : '';
					$image 		= !empty( $value['image'] ) ? $value['image'] : array();
					$image_url 	= !empty( $image ) ? wp_get_attachment_image_src( $image['attachment_id'], array(100,100), true ) : '';				

					if( empty( $image_url[0] ) ){
						$image_data = $default_img;
					} else {
						$image_data = $image_url[0];
					}	

					$award_date 		= !empty( $date ) ? date_i18n('F Y', strtotime( $date ) ) : '';
					$file_size 			= !empty( $image ) ? filesize( get_attached_file( $image['attachment_id'] ) ) : '';	
					$document_name   	= !empty( $image ) ? esc_html( get_the_title( $image['attachment_id'] ) ) : '';
					$filetype        	= !empty( $image ) ? wp_check_filetype( $image['url'] ) : '';
					$extension       	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';					
					if( !empty( $title ) ){?>
					<li id="wt-award-<?php echo esc_attr( $rand ); ?>" data-id="<?php echo esc_attr( $rand ); ?>" class="wt-placehoder-img">
						<div class="wt-accordioninnertitle">
							<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
							<div class="wt-projecttitle">
								<figure class="award-thumb"><img src="<?php echo esc_url( $image_data ); ?>" alt="<?php echo esc_attr( $title ); ?>"></figure>
								<h3>
									<?php if( !empty( $title ) ){ ?>
										<span class="head-title">
											<?php echo esc_html( $title ); ?>
										</span>
									<?php } ?>
									<?php if( !empty( $award_date ) ){ ?>
										<span class="head-sub-title"><?php echo esc_html( $award_date ); ?></span>
									<?php } ?>
								</h3>
							</div>
							<div class="wt-rightarea">
								<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle1" data-toggle="collapse" data-target="#innertitle<?php echo esc_attr( $rand ); ?>" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
								<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
							</div>
						</div>
						<div class="wt-collapseexp collapse" id="innertitle<?php echo esc_attr( $rand ); ?>" aria-labelledby="accordioninnertitle1" data-parent="#accordion">
							<div class="wt-formtheme wt-userform wt-formprojectinfo">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="settings[awards][<?php echo esc_attr( $rand ); ?>][title]" class="wt-input-title form-control" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e('Award Title', 'workreap'); ?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="settings[awards][<?php echo esc_attr( $rand ); ?>][date]" class="form-control wt-date-pick" value="<?php echo esc_attr( $date ); ?>" placeholder="<?php esc_attr_e('Award Date', 'workreap'); ?>">
									</div>
									<div class="form-group form-group-label" id="wt-award-container-<?php echo esc_attr( $rand ); ?>">
										<div class="wt-labelgroup"  id="award-drag-<?php echo esc_attr( $rand ); ?>">
											<label for="file" class="wt-award-file">
												<span class="wt-btn" id="award-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'workreap'); ?></span>								
											</label>
											<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
											<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
										</div>
									</div>
									<div class="form-group uploaded-placeholder">
										<?php if( !empty( $image ) ){ ?>
											<ul class="wt-attachfile">
												<li class="wt-doc-parent">
													<span><?php echo esc_html( $document_name ); ?>.<?php echo esc_html( $extension ); ?></span>
													<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo esc_html( size_format($file_size, 2) ); ?><a href="#" onclick="event_preventDefault(event);" class="wt-remove-award-image lnr lnr-cross"></a></em>			
													<input type="hidden" name="settings[awards][<?php echo esc_attr( $rand ); ?>][image][attachment_id]" value="<?php echo esc_attr( $image['attachment_id'] ); ?>">
													<input type="hidden" name="settings[awards][<?php echo esc_attr( $rand ); ?>][image][url]" value="<?php echo esc_url( $image['url'] ); ?>">
												</li>
											</ul>
										<?php } ?>
									</div>												
								</fieldset>
							</div>
						</div>
						<?php
							$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader("' . esc_js( $rand ). '", "awards"); });';
							wp_add_inline_script( 'workreap-user-dashboard', $inline_script, 'after' );
						?>
					</li>		
			<?php } } } ?>													
		</ul>
	</div>
	<script type="text/template" id="tmpl-load-award">
	<li id="wt-award-{{data.counter}}" data-id="{{data.counter}}" class="wt-placehoder-img">
		<div class="wt-accordioninnertitle">
			<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
			<div class="wt-projecttitle">
				<figure class="award-thumb"><img src="<?php echo esc_url( $default_img ); ?>" alt="<?php esc_attr_e('Title', 'workreap'); ?>"></figure>
				<h3><span class="head-title"><?php esc_html_e('Award title here', 'workreap'); ?></span><span class="head-sub-title"><?php esc_html_e('01-01-2020', 'workreap'); ?></span></h3>
			</div>
			<div class="wt-rightarea">
				<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-skillsaddinfo" id="accordioninnertitle" data-toggle="collapse" data-target="#innertitle-{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
				<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
			</div>
		</div>
		<div class="wt-collapseexp collapse show" id="innertitle-{{data.counter}}" aria-labelledby="accordioninnertitle" data-parent="#accordion">
			<div class="wt-formtheme wt-userform wt-formprojectinfo">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="settings[awards][{{data.counter}}][title]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Award title', 'workreap'); ?>">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="settings[awards][{{data.counter}}][date]" class="wt-date-pick form-control" placeholder="<?php esc_attr_e('Award date', 'workreap'); ?>">
					</div>
					<div class="form-group form-group-label" id="wt-award-container-{{data.counter}}">
						<div class="wt-labelgroup" id="award-drag-{{data.counter}}">

							<label for="file">
								<span class="wt-btn" id="award-btn-{{data.counter}}"><?php esc_html_e('Select file', 'workreap'); ?></span>	
							</label>
							<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
							<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
						</div>
					</div>
					<div class="form-group uploaded-placeholder"></div>
				</fieldset>
			</div>
		</div>	
	</li>
	</script>
	<?php
	$script = "jQuery(document).ready(function (e) {
			addSortable(awardssortable);                    
		});";
		wp_add_inline_script('workreap-user-dashboard', $script, 'after');
}

?>