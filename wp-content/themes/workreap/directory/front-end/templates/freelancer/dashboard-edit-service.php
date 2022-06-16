<?php
/**
 *
 * The template part for displaying post a job
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

if (!empty($_GET['identity'])) {
    $url_identity = $_GET['identity'];
}

$args_addons = array(
					'author'        =>  $current_user->ID,
					'post_type'		=> 	'addons-services',
					'post_status'	=>  'publish',
					'orderby'       =>  'post_date',
					'order'         =>  'ASC',
					'posts_per_page' => -1
				);
$addons		= get_posts( $args_addons );

$deliveries	    	= workreap_get_taxonomy_array('delivery');
$languages		    = workreap_get_taxonomy_array('languages');
$response_time		= workreap_get_taxonomy_array('response_time');
$english_level      = worktic_english_level_list();
$description 		= '';
$name 				= 'service[description]';								
$settings 			= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'=> array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );

$edit_id 		= !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author 	= get_post_field('post_author', $edit_id);
$service_faq_option	= '';
if (function_exists('fw_get_db_post_option') ) {
	$remove_response_time			= fw_get_db_settings_option('remove_response_time');
	$remove_dilivery_time			= fw_get_db_settings_option('remove_dilivery_time');
	$remove_service_videos			= fw_get_db_settings_option('remove_service_videos');
	$remove_service_addon			= fw_get_db_settings_option('remove_service_addon');
	$remove_service_languages		= fw_get_db_settings_option('remove_service_languages');
	$remove_service_english_level	= fw_get_db_settings_option('remove_service_english_level');
	$remove_service_downloadable	= fw_get_db_settings_option('remove_service_downloadable');
	$services_categories			= fw_get_db_settings_option('services_categories');
	$total_limit					= fw_get_db_settings_option('default_service_images');
	$location_services				= fw_get_db_settings_option('remove_location_services');
	$service_faq_option				= fw_get_db_settings_option('service_faq_option', $default_value = null);
}

$remove_service_addon			= !empty($remove_service_addon) ? $remove_service_addon : 'no';
$remove_service_languages		= !empty($remove_service_languages) ? $remove_service_languages : 'no';
$remove_service_english_level	= !empty($remove_service_english_level) ? $remove_service_english_level : 'no';
$remove_service_downloadable	= !empty($remove_service_downloadable) ? $remove_service_downloadable : 'no';
$location_services				= !empty($location_services) ? $location_services : 'no';
$total_limit					= !empty($total_limit) ? $total_limit : 100;

$services_categories	= !empty($services_categories) ? $services_categories : 'no';
if( !empty($services_categories) && $services_categories === 'no' ) {
	$taxonomy	= 'project_cat';
}else{
	$taxonomy	= 'service_categories';
}

$currency	= workreap_get_current_currency();
$placeholder 		= !empty($currency['symbol'] ) ? $currency['symbol'] : '$';
$placeholder		= esc_html__('Service price','workreap').' ('.$placeholder.')';
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left">
	<div class="wt-haslayout wt-post-job-wrap">
		<?php 
			if (intval($url_identity) === intval($post_author)) {
				$args = array('posts_per_page' 		=> '-1',
								'post_type' 		=> 'micro-services',
								'post__in' 			=> array($edit_id),
							  	'post_status' 		=> 'any',
								'suppress_filters' 	=> false
							);

				$query = new WP_Query($args);

				while ($query->have_posts()) : $query->the_post();
					global $post;
					$author_id 		= get_the_author_meta( 'ID' );
					$description	= get_the_content();

					$is_featured   	= get_post_meta($post->ID,'_featured_service_string',true);
					$is_featured	= !empty( $is_featured ) ? 'on' : 'off';
					
					$db_project_cat = wp_get_post_terms($post->ID, $taxonomy);
					$db_location	= wp_get_post_terms($post->ID, 'locations');
					$db_languages	= wp_get_post_terms($post->ID, 'languages');
				
					$db_response_time	= wp_get_post_terms($post->ID, 'response_time');
					$db_delivery	= wp_get_post_terms($post->ID, 'delivery');
				
					$db_delivery 	= !empty( $db_delivery[0] ) ? $db_delivery[0]->term_id : '';
					$db_project_cat	= !empty( $db_project_cat ) ? wp_list_pluck($db_project_cat,'term_id') : array();
					$db_location	= !empty( $db_location ) ? wp_list_pluck($db_location,'term_id') : array();
					$db_languages	= !empty( $db_languages ) ? wp_list_pluck($db_languages,'term_id') : array();
					
					$db_response_time	= !empty( $db_response_time ) ? wp_list_pluck($db_response_time,'term_id') : array();
				
					$db_docs			= array();
					$db_price			= '';
					$db_downloadable	= 'no';
					$db_english_level	= '';
					$order_details		= '';
					$db_addons			= get_post_meta($post->ID,'_addons',true);
					$db_addons			= !empty( $db_addons ) ? $db_addons : array();
					
					if (function_exists('fw_get_db_post_option')) {
						$db_docs   			= fw_get_db_post_option($post->ID,'docs');
						$order_details   	= fw_get_db_post_option($post->ID,'order_details');
						$db_price   		= fw_get_db_post_option($post->ID,'price');
						$db_downloadable   	= fw_get_db_post_option($post->ID,'downloadable');
						$db_english_level   = fw_get_db_post_option($post->ID,'english_level');
					}
					?>
					<form class="post-service-form wt-haslayout">
						<div class="wt-dashboardbox">
							<div class="wt-dashboardboxtitle">
								<h2><?php esc_html_e('Edit a service','workreap');?></h2>
							</div>
							<div class="wt-dashboardboxcontent">
								<div class="wt-jobdescription wt-tabsinfo">
									<div class="wt-tabscontenttitle">
										<h2><?php esc_html_e('Service description','workreap');?></h2>
									</div>
									<div class="wt-formtheme wt-userform wt-userformvtwo">
										<fieldset>
											<div class="form-group wt-formwithlabel">
												<input type="text" name="service[title]" value="<?php the_title();?>" class="form-control count_service_title" placeholder="<?php esc_attr_e('Add service title','workreap');?>">
											</div>
											<?php if(!empty($remove_dilivery_time) && $remove_dilivery_time === 'no'){?>
												<div class="form-group form-group-half wt-formwithlabel">
													<span class="wt-selects">
														<select name="service[delivery_time]" class="chosen-select">
															<option value=""><?php esc_html_e('Select Service delivery','workreap');?></option>
															<?php 
															if( !empty( $deliveries ) ){
																foreach( $deliveries as $delivery ){?>
																	<option <?php selected($delivery->term_id,$db_delivery);?> value="<?php echo intval( $delivery->term_id );?>"><?php echo esc_html( $delivery->name );?></option>
															<?php }}?>
														</select>
													</span>
												</div>
											<?php }?>
											<div class="form-group form-group-half wt-formwithlabel job-cost-input">
												<input type="text" name="service[price]" class="wt-numeric" value="<?php echo floatval($db_price);?>" placeholder="<?php echo esc_attr($placeholder);?>">
											</div>
											<?php if(!empty($remove_service_downloadable) && $remove_service_downloadable === 'no'){?>
												<div class="form-group form-group-half wt-formwithlabel">
													<span class="wt-selects">
														<select name="service[downloadable]" class="downloadable-select chosen-select">
															<option value=""><?php esc_html_e('Select downloadable','workreap');?></option>
															<option value="no" <?php selected( $db_downloadable, 'no', true); ?>><?php esc_html_e('No','workreap');?></option>
															<option value="yes" <?php selected( $db_downloadable, 'yes', true); ?>><?php esc_html_e('Yes','workreap');?></option>
														</select>
													</span>
												</div>
											<?php }?>
										</fieldset>
									</div>
								</div>
								<?php get_template_part('directory/front-end/templates/freelancer/dashboard', 'downloadable-service'); ?>
								
								<?php if(!empty($remove_service_addon) && $remove_service_addon === 'no'){?>
								<div class="wt-addonsservices wt-tabsinfo">
									<div class="wt-tabscontenttitle wt-addnew">
										<h2><?php esc_html_e( 'Addons Services','workreap');?></h2>
										<span class="wt-add-addons"><a href="#" onclick="event_preventDefault(event);"><?php esc_html_e('+ Add New','workreap');?></a></span>
									</div>
									<div class="wt-addonservices-content">
										<ul>
										<?php if( !empty( $addons ) ){
											foreach( $addons as $addon ) { 
												$db_price			= 0;
												if (function_exists('fw_get_db_post_option')) {
													$db_price   = fw_get_db_post_option($addon->ID,'price');
												}

												$checked = '';
												if( in_array($addon->ID,$db_addons) ){
													$checked = 'checked';
												}
											?>
											<li>
												<div class="wt-checkbox">
													<input id="rate<?php echo intval($addon->ID);?>" type="checkbox" name="service[addons][]" value="<?php echo intval($addon->ID);?>" <?php echo esc_attr( $checked );?> >
													<label for="rate<?php echo intval($addon->ID);?>">
														<?php if( !empty( $addon->post_title ) ){?>
															<h3><?php echo esc_html( $addon->post_title );?></h3>
														<?php } ?>
														<?php if( !empty( $addon->post_excerpt ) ){?>
															<p><?php echo esc_html( $addon->post_excerpt);?></p>
														<?php } ?>
														<?php if( !empty( $db_price ) ){?>
															<strong><?php workreap_price_format($db_price);?></strong>
														<?php } ?>
													</label>
												</div>
											</li>
											<?php }} ?>
										</ul>
									</div>
								</div>
								<?php }?>
								<div class="wt-category-holder wt-tabsinfo">
									<div class="wt-tabscontenttitle">
										<h2><?php esc_html_e('Service Categories','workreap');?></h2>
									</div>
									<div class="wt-divtheme wt-userform wt-userformvtwo">
										<div class="form-group">
											<?php do_action('workreap_get_categories_list','service[categories][]',$db_project_cat);?>
										</div>
									</div>
								</div>
								<?php if(!empty($remove_response_time) && $remove_response_time === 'no'){?>
									<div class="wt-category-holder wt-tabsinfo">
										<div class="wt-tabscontenttitle">
											<h2><?php esc_html_e('Service Response Time','workreap');?></h2>
										</div>
										<div class="wt-divtheme wt-userform wt-userformvtwo">
											<div class="form-group">
												<select data-placeholder="<?php esc_attr_e('Select Response Time','workreap');?>" name="service[response_time]"  class="chosen-select">
													<?php if( !empty( $response_time ) ){?>
														<option value=""><?php esc_html_e('Select Response Time','workreap');?></option>
														<?php
															foreach ($response_time as $key => $item) {
																$term_id   = $item->term_id;
																$selected = '';
																if( in_array($term_id,$db_response_time) ){
																	$selected = 'selected';
																}
																?>
																<option <?php echo esc_attr( $selected );?> value="<?php echo intval( $term_id ); ?>"><?php echo esc_html( $item->name ); ?></option>
																<?php 
															}
														}
													?>		
												</select>
											</div>
										</div>
									</div>
								<?php }?>
								<?php if( ( !empty($remove_service_languages) && $remove_service_languages === 'no') || (!empty($remove_service_english_level) && $remove_service_english_level === 'no') ){?>
									<div class="wt-language-holder wt-tabsinfo wt-wp-language">
										<div class="wt-divtheme wt-userform">
											<?php if(!empty($remove_service_languages) && $remove_service_languages === 'no'){?>
												<div class="form-group form-group-half ">
													<div class="wt-tabscontenttitle">
														<h2><?php esc_html_e('Languages','workreap');?></h2>
													</div>
													<select data-placeholder="<?php esc_attr_e('Select languages','workreap');?>" multiple name="service[languages][]"  class="chosen-select">
														<?php 
															if( !empty( $languages ) ){							
																foreach ($languages as $key => $item) {
																	$term_id   = $item->term_id;	
																	$selected = '';
																	if( in_array($term_id,$db_languages) ){
																		$selected = 'selected';
																	}
																	?>
																	<option <?php echo esc_attr( $selected );?> value="<?php echo intval( $term_id ); ?>"><?php echo esc_html( $item->name ); ?></option>
																	<?php 
																}
															}
														?>		
													</select>
												</div>
											<?php }?>
											<?php if(!empty($remove_service_english_level) && $remove_service_english_level === 'no'){?>
												<div class="form-group form-group-half wt-formwithlabel">
													<div class="wt-tabscontenttitle">
														<h2><?php esc_html_e('English level','workreap');?></h2>
													</div>
													<span class="wt-selects">
														<select name="service[english_level]" class="chosen-select">
															<option value=""><?php esc_html_e('Select english level','workreap');?></option>
															<?php 
															if( !empty( $english_level ) ){
																foreach( $english_level as $key => $level ){?>
																<option <?php selected($db_english_level,$key);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
															<?php }}?>
														</select>
													</span>
												</div>
											<?php }?>
										</div>
									</div>
								<?php }?>
								<div class="wt-jobdetails wt-tabsinfo">
									<div class="wt-tabscontenttitle">
										<h2><?php esc_html_e('Service Details','workreap');?></h2>
									</div>
									<div class="wt-formtheme wt-userform wt-userformvtwo">
										<fieldset>
											<div class="form-group">
												<?php wp_editor($description, 'service_details', $settings);?>
											</div>
										</fieldset>
									</div>
								</div>
								<?php if(!empty($remove_service_videos) && $remove_service_videos === 'no'){ get_template_part('directory/front-end/templates/freelancer/dashboard', 'service-videos');} ?>
								
								<div class="wt-jobdetails wt-attachmentsholder">
									<div class="wt-tabscontenttitle">
										<h2><?php esc_html_e('Upload Images','workreap');?></h2>
									</div>
									<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
										<fieldset>
											<p class="total-allowed-limit"><?php echo sprintf( __( "You are only allowed to upload <b>%s</b> images per service. if you will upload more images then first <b>%s</b> images will be attached to this service", "workreap" ), $total_limit,$total_limit);?></p>
											<div class="form-group form-group-label" id="wt-service-container">
												<div class="wt-labelgroup" id="service-drag">
													<label for="file" class="wt-job-file">
														<span class="wt-btn" id="service-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>								
													</label>
													<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
													<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
												</div>
											</div>
											<div class="form-group">
												<ul class="wt-attachfile uploaded-placeholder">
													<?php 
													if( !empty( $db_docs ) ){
														foreach( $db_docs as $key => $doc ){
															$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
															$file_size 		= !empty( $doc) ? filesize(get_attached_file($attachment_id)) : '';
															$document_name	= !empty( $doc ) ? get_the_title( $attachment_id ) : '';
															$filetype       = !empty( $doc ) ? wp_check_filetype( $doc['url'] ) : '';
															$extension  	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
															$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
															?>
														<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
															<span><?php echo esc_html($document_name);?></span>
															<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
															<input type="hidden" class="attachment_url" name="service[service_documents][<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo intval($attachment_id);?>">
															<input type="hidden" class="attachment_url" name="service[service_documents][<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_attr($doc_url);?>">
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
								<?php if( apply_filters('workreap_is_service_posting_allowed','wt_services', $current_user->ID,'yes') === true ){
										if(apply_filters('workreap_is_listing_free',false,$current_user->ID) === false ){
									?>
									<div class="wt-attachmentsholder">
										<div class="wt-tabscontenttitle">
											<h2><?php esc_html_e('Featured service','workreap');?></h2>
											<div class="wt-rightarea">
												<div class="wt-on-off float-right">
													<input type="hidden" value="off" name="service[is_featured]">
													<input type="checkbox" <?php checked($is_featured,'on');?>  value="on" id="featured-on" name="service[is_featured]">
													<label for="featured-on"><i></i></label>
												</div>
											</div>
										</div>
									</div>
								<?php }}?>
								<?php 
									if(!empty($location_services) && $location_services == 'no' ) { get_template_part('directory/front-end/templates/freelancer/dashboard', 'service-location'); }
									if(!empty($service_faq_option) && $service_faq_option == 'yes' ) {
										get_template_part('directory/front-end/templates/dashboard', 'faq',array('post_id'=>$edit_id,'title'=> esc_html__('Service FAQ', 'workreap'),'add'=> esc_html__('+Add service faq', 'workreap')));
									}
								?>
								
							</div>
						</div>
						<div class="wt-updatall">
							<?php wp_nonce_field('wt_post_service_nonce', 'post_service'); ?>
							<i class="ti-announcement"></i>
							<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
							<a class="wt-btn wt-post-service" data-id="<?php echo intval($edit_id);?>" data-type="update" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
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
		<script type="text/template" id="tmpl-load-service-attachments">
			<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
				<span class="uploadprogressbar" style="width:0%"></span>
				<span>{{data.name}}</span>
				<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
				<input type="hidden" class="attachment_url" name="service[service_documents][]" value="{{data.url}}">	
			</li>
		</script>
		<script type="text/template" id="tmpl-load-service-addon">
			<li>
				<div class="wt-dashboardboxcontent addon-mainwrap">
					<div class="wt-jobdescription wt-tabsinfo">
						<div class="wt-accordioninnertitle">
							<div class="wt-projecttitle">
								<h3><span class="head-title"><?php esc_html_e('Addon service','workreap');?></span></h3>
							</div>
							<div class="wt-rightarea">
								<a href="#" onclick="event_preventDefault(event);" class="wt-addinfo wt-edit-addons"><i class="lnr lnr-pencil"></i></a>
								<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-addon"><i class="lnr lnr-trash"></i></a>
							</div>
						</div>
						<div class="wt-formtheme wt-userform wt-userformvtwo addon-service-data elm-display-none">
							<fieldset>
								<div class="form-group">
									<input type="text" name="addons_service[{{data.counter}}][title]" class="form-control wt-input-title" placeholder="<?php esc_attr_e('Addons service title','workreap');?>" value="">
								</div>
								<div class="form-group">
									<input type="text" class="wt-numeric" name="addons_service[{{data.counter}}][price]" value="" placeholder="<?php esc_attr_e('Service price','workreap');?>">
								</div>
								<div class="form-group">
									<textarea class="form-control" placeholder="<?php esc_attr_e('Addons service detail','workreap');?>" name="addons_service[{{data.counter}}][description]"></textarea>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</li>
		</script>	
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
	<?php if ( is_active_sidebar( 'sidebar-dashboard' ) ) {?>
		<div class="wt-haslayout wt-dashside">
			<?php dynamic_sidebar( 'sidebar-dashboard' ); ?>
		</div>
	<?php }?>
</div>