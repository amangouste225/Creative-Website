<?php
/**
 *
 * The template part for displaying creating portfolio
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

$description 		= '';
$ppt_option			= '';
$total_limit		= '';
if( function_exists('fw_get_db_settings_option') ){
	$ppt_option		= fw_get_db_settings_option('ppt_template');
	$total_limit	= fw_get_db_settings_option('default_portfolio_images');
}

$total_limit		= !empty($total_limit) ? intval($total_limit) : 100;
$name 				= 'portfolio[description]';								
$settings 			= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'=> array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );
$all_tags = get_terms( array(
	'taxonomy' 		=> 'portfolio_tags',
	'hide_empty' 	=> false,
) );
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left">
	<div class="wt-haslayout wt-post-job-wrap">
		<form class="post-portfolio-form wt-haslayout">
			<div class="wt-dashboardbox">
				<div class="wt-dashboardboxtitle">
					<h2><?php esc_html_e('Add Portfolio','workreap');?></h2>
				</div>
				<div class="wt-dashboardboxcontent">
					<div class="wt-jobdescription wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Portfolio description', 'workreap');?></h2>
						</div>
						<div class="wt-formtheme wt-userform wt-userformvtwo">
							<fieldset>
								<div class="form-group form-group-half">
									<input type="text" name="portfolio[title]" class="form-control" placeholder="<?php esc_attr_e('Portfolio title','workreap');?>">
								</div>
								<div class="form-group form-group-half">
									<input type="text" name="portfolio[custom_link]" value="" placeholder="<?php esc_attr_e('Custom link(optional)','workreap');?>">
								</div>
							</fieldset>
						</div>
					</div>
					<div class="wt-category-holder wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Portfolio Categories','workreap');?></h2>
						</div>
						<div class="wt-divtheme wt-userform wt-userformvtwo">
							<div class="form-group">
								<?php do_action('workreap_get_cat_list', 'portfolio_categories', 'project_cat_multiselect', 'portfolio[categories][]', '');?>
							</div>
						</div>
					</div>
					<div class="wt-category-holder wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Portfolio Tags','workreap');?></h2>
						</div>
						<div class="wt-divtheme wt-userform wt-userformvtwo">
							<div class="form-group">
								<select name="tags[]" class="form-control portfolio-tags" multiple="multiple">
								<?php if( !empty($all_tags) ){
									foreach($all_tags as $key=> $tag){
										?>
										<option value="<?php echo esc_html($tag->slug);?>"><?php echo esc_html($tag->name);?></option>
									<?php }}?>
								</select>
							</div>
						</div>
					</div>
					<div class="wt-videosdataholder wt-tabsinfo wt-awardsholder" id="wt-videos">
						<div class="wt-tabscontenttitle wt-addnew">
							<h2><?php esc_html_e('Add Your Videos', 'workreap'); ?></h2>
							<span class="wt-add-portfolio-video"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add Video URL', 'workreap'); ?></a></span>
						</div>
						<ul class="wt-experienceaccordion" id="portfoliovideossortable"></ul>
					</div>
					<div class="wt-jobdetails wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Portfolio detail','workreap');?></h2>
						</div>
						<div class="wt-formtheme wt-userform wt-userformvtwo">
							<fieldset>
								<div class="form-group">
									<?php wp_editor($description, 'portfolio_details', $settings);?>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="wt-jobdetails wt-attachmentsholder wt-tabsinfo">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Upload Images','workreap');?></h2>
						</div>
						<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
							<p class="total-allowed-limit"><?php echo sprintf( __( "You are only allowed to upload <b>%s</b> images per service. if you will upload more images then first <b>%s</b> images will be attached to this service", "workreap" ), $total_limit,$total_limit);?></p>
							<fieldset>
								<div class="form-group form-group-label" id="wt-portfolio-container">
									<div class="wt-labelgroup" id="portfolio-drag">
										<label for="file" class="wt-job-file">
											<span class="wt-btn" id="portfolio-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>			
										</label>
										<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
										<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
									</div>
								</div>
								<div class="form-group">
									<ul class="wt-attachfile uploaded-placeholder porfolio-gallery"></ul>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="wt-jobdetails wt-attachmentsholder upload-documents documents-wrap">
						<div class="wt-tabscontenttitle">
							<h2><?php esc_html_e('Upload Documents','workreap');?></h2>
						</div>
						<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
							<fieldset>
								<div class="form-group form-group-label" id="wt-portfolio-documents-container">
									<div class="wt-labelgroup" id="portfolio-documents-drag">
										<label for="file" class="wt-job-file">
											<span class="wt-btn" id="portfolio-documents-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>			
										</label>
										<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
										<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
									</div>
								</div>
								<div class="form-group">
									<ul class="wt-attachfile uploaded-docs-placeholder"></ul>
								</div>
							</fieldset>
						</div>
					</div>
					<?php if( !empty($ppt_option) && $ppt_option === 'enable' ){ ?>
						<div class="wt-jobdetails wt-attachmentsholder upload-zip articulate-wrap">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Upload zip file', 'workreap');?></h2>
							</div>
							<p><?php esc_html_e( 'Add Articulate Content for your portfolios.', 'workreap' );?></p>
							<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
								<fieldset>
									<div class="form-group form-group-label" id="wt-portfolio-zip-container">
										<div class="wt-labelgroup" id="portfolio-zip-drag">
											<label for="file" class="wt-job-file">
												<span class="wt-btn" id="portfolio-zip-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>			
											</label>
											<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
											<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
										</div>
									</div>
									<div class="form-group">
										<ul class="wt-attachfile uploaded-zip-placeholder"></ul>
									</div>
								</fieldset>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="wt-updatall">
				<?php wp_nonce_field('wt_post_portfolio_nonce', 'post_portfolio'); ?>
				<i class="ti-announcement"></i>
				<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
				<a class="wt-btn wt-post-portfolio" data-id="" data-type="add" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
			</div>
		</form>
	</div>
</div>
<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	</div>
<?php }?>
<script type="text/template" id="tmpl-load-portfolio-attachments">
	<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
		<span class="uploadprogressbar uploadprogressbar-0"></span>
		<span>{{data.name}}</span>
		<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
		<input type="hidden" class="attachment_url" name="portfolio[gallery_imgs][]" value="{{data.url}}">	
	</li>
</script>
<script type="text/template" id="tmpl-load-portfolio-documents">
	<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
		<span class="uploadprogressbar uploadprogressbar-0"></span>
		<span>{{data.name}}</span>
		<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
		<input type="hidden" class="attachment_url" name="portfolio[documents][]" value="{{data.url}}">	
	</li>
</script>
<script type="text/template" id="tmpl-load-portfolio-zip-attachments">
	<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
		<span class="uploadprogressbar uploadprogressbar-0"></span>
		<span>{{data.name}}</span>
		<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
		<input type="hidden" class="ppt_template" name="ppt_template" value="{{data.value}}">	
	</li>
</script>	
<script type="text/template" id="tmpl-load-portfolio-videos">
	<li data-id="{{data.counter}}" class="wt-videos-item">
		<div class="wt-accordioninnertitle">
			<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
			<div class="form-group">
				<input type="text" name="portfolio[videos][]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Video URL', 'workreap'); ?>">
			</div>
			<div class="wt-rightarea">
				<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
			</div>
		</div>
	</li>
</script>
<?php
$script = "jQuery(document).ready(function (e) {
		addSortable(portfoliovideossortable);                    
	});";
	wp_add_inline_script('workreap-user-dashboard', $script, 'after');
?>