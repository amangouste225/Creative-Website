<?php
/**
 *
 * The template part for editing portfolio
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

if (!empty($_GET['identity'])) {
    $url_identity = $_GET['identity'];
}

$description	= '';
$name 			= 'portfolio[description]';											
$settings 		= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'=> array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );
$edit_id 		= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author 	= get_post_field('post_author', $edit_id);

$total_limit		= '';
if( function_exists('fw_get_db_settings_option') ){
	$ppt_option		= fw_get_db_settings_option('ppt_template');
	$total_limit	= fw_get_db_settings_option('default_portfolio_images');
}
$total_limit		= !empty($total_limit) ? intval($total_limit) : 100;
$all_tags = get_terms( array(
	'taxonomy' 		=> 'portfolio_tags',
	'hide_empty' 	=> false,
) );
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left">
	<div class="wt-haslayout wt-post-job-wrap">
		<?php 
			if (intval($url_identity) === intval($post_author)) {
				$args = array('posts_per_page' 		=> '-1',
								'post_type' 		=> 'wt_portfolio',
								'post__in' 			=> array($edit_id),
							  	'post_status' 		=> 'any',
								'suppress_filters' 	=> false
							);

				$query = new WP_Query($args);

				while ($query->have_posts()) : $query->the_post();
					global $post;
					$description		= get_the_content();
				
					$db_portfolio_cat   = wp_get_post_terms($post->ID, 'portfolio_categories');
					$db_portfolio_cat	= !empty( $db_portfolio_cat ) ? wp_list_pluck($db_portfolio_cat, 'term_id') : array();

					$db_portfolio_tag 	= wp_get_post_terms($post->ID, 'portfolio_tags');
					$db_portfolio_tag	= !empty( $db_portfolio_tag ) ? wp_list_pluck($db_portfolio_tag, 'term_id') : array();
				
					$gallery_imgs		= array();
					$doc_attachemnts	= array();
					$videos 			= array();
					$db_custom_link		= '';
					
					if( !empty($ppt_option) && $ppt_option ==='enable' ){
						$ppt_template_data			= get_post_meta( $post->ID, 'ppt_template', true );
						$ppt_template				= !empty($ppt_template_data) ? json_decode( $ppt_template_data ) : '';
					}

					if (function_exists('fw_get_db_post_option')) {
						$gallery_imgs   	= fw_get_db_post_option($post->ID, 'gallery_imgs');
						$doc_attachemnts   	= fw_get_db_post_option($post->ID, 'documents');
						$db_custom_link   	= fw_get_db_post_option($post->ID, 'custom_link');
						$videos 			= fw_get_db_post_option($post->ID, 'videos');
					}
				
					$tags	= wp_get_post_terms($post->ID, 'portfolio_tags',array( 'fields' => 'ids' ));
					?>
					<form class="post-portfolio-form wt-haslayout">
						<div class="wt-dashboardbox">
							<div class="wt-dashboardboxtitle">
								<h2><?php esc_html_e('Edit Portfolio','workreap');?></h2>
							</div>
							<div class="wt-dashboardboxcontent">
								<div class="wt-jobdescription wt-tabsinfo">
									<div class="wt-tabscontenttitle">
										<h2><?php esc_html_e('Portfolio description','workreap');?></h2>
									</div>
									<div class="wt-formtheme wt-userform wt-userformvtwo">
										<fieldset>
											<div class="form-group form-group-half wt-formwithlabel">
												<input type="text" name="portfolio[title]" value="<?php the_title();?>" class="form-control" placeholder="<?php esc_attr_e('Portfolio title','workreap');?>">
											</div>
											<div class="form-group form-group-half">
												<input type="text" name="portfolio[custom_link]" value="<?php echo esc_attr($db_custom_link); ?>" placeholder="<?php esc_attr_e('Custom link(optional)','workreap');?>">
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
											<?php do_action('workreap_get_cat_list', 'portfolio_categories', 'project_cat_multiselect', 'portfolio[categories][]', $db_portfolio_cat);?>
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
													$selected	='';
													if( !empty($tags) && is_array($tags) && in_array($tag->term_id,$tags)){
														$selected	='selected';
													}
													?>
													<option <?php echo esc_attr($selected);?> value="<?php echo esc_html($tag->slug);?>"><?php echo esc_html($tag->name);?></option>
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
									<ul class="wt-experienceaccordion" id="portfoliovideossortable">
										<?php 
										if( !empty( $videos ) && is_array($videos) ) {
											foreach ($videos as $key => $video) {
												$rand = rand(999999, 99999);
												?>
												<li class="wt-videos-item">
													<div class="wt-accordioninnertitle">
														<a href="#" onclick="event_preventDefault(event);" class="handle"><i class="fa fa-arrows-alt"></i></a>
														<div class="form-group">
															<input type="text" value="<?php echo esc_url( $video );?>" name="portfolio[videos][]" class="wt-input-title form-control" placeholder="<?php esc_attr_e('Video URL', 'workreap'); ?>">
														</div>
														<div class="wt-rightarea">
															<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-data"><i class="lnr lnr-trash"></i></a>
														</div>
													</div>
												</li>		
										<?php } } ?>													
									</ul>
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
												<ul class="wt-attachfile uploaded-placeholder porfolio-gallery">
													<?php 
													if( !empty( $gallery_imgs ) ){
														foreach( $gallery_imgs as $key => $doc ){
															$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
															$file_size 		= !empty( $doc) ? filesize(get_attached_file($attachment_id)) : '';
															$document_name	= !empty( $doc ) ? get_the_title( $attachment_id ) : '';
															$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
															?>
														<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
															<span><?php echo esc_html($document_name);?></span>
															<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
															<input type="hidden" class="attachment_url" name="portfolio[gallery_imgs][<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
															<input type="hidden" class="attachment_url" name="portfolio[gallery_imgs][<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_attr($doc_url);?>">
														</li>
														<?php
															}
														}
													?>													
												</ul>
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
												<ul class="wt-attachfile uploaded-docs-placeholder">
													<?php 
													if( !empty( $doc_attachemnts ) ){
														foreach( $doc_attachemnts as $key => $doc ){
															$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
															$file_size 		= !empty( $doc) ? filesize(get_attached_file($attachment_id)) : '';
															$document_name	= !empty( $doc ) ? get_the_title( $attachment_id ) : '';
															$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
															?>
														<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
															<span><?php echo esc_html($document_name);?></span>
															<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
															<input type="hidden" class="attachment_url" name="portfolio[documents][<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
															<input type="hidden" class="attachment_url" name="portfolio[documents][<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_attr($doc_url);?>">
														</li>
														<?php
															}
														}
													?>													
												</ul>
											</div>
										</fieldset>
									</div>
								</div>
								<?php if( !empty($ppt_option) && $ppt_option ==='enable' ){ ?>
									<div class="wt-jobdetails wt-attachmentsholder articulate-wrap">
										<div class="wt-tabscontenttitle">
											<h2><?php esc_html_e('Upload zip file','workreap');?></h2>
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
													<ul class="wt-attachfile uploaded-zip-placeholder">
														<?php 
														if( !empty( $ppt_template ) ){
																$attachment_id	= rand();
																$file_size 		= !empty( $ppt_template->target) ? workreap_foldersize($ppt_template->target) : '';
																$document_name	= !empty( $ppt_template->folder ) ? $ppt_template->folder : '';
																$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
																?>
																<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
																	<span><?php echo esc_html($document_name);?></span>
																	<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
																	<input type="hidden" class="ppt_template"  name="ppt_template" value="<?php echo esc_attr($ppt_template_data);?>">

																</li>
															<?php
															}
														?>													
													</ul>
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
							<a class="wt-btn wt-post-portfolio" data-id="<?php echo intval($edit_id);?>" data-type="update" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
						</div>
					</form>
				<?php 
				endwhile;
				wp_reset_postdata();
			} else { ?>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted Access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap')); ?>
			</div>
		<?php } ?>
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