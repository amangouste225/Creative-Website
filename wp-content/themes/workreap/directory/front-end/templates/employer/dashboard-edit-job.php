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

$all_skills 	 	= workreap_get_all_skills();
$job_duration       = worktic_job_duration_list();
$english_level      = worktic_english_level_list();
$freelancer_level   = worktic_freelancer_level_list();
$project_level   	= workreap_get_project_level();
$job_type 		 	= workreap_get_job_type();
$cats			    = workreap_get_taxonomy_array('project_cat');
$languages		    = workreap_get_taxonomy_array('languages');
$experiences		= workreap_get_taxonomy_array('project_experience');

$job_options		= function_exists('workreap_get_job_option') ? workreap_get_job_option() : array();

if (function_exists('fw_get_db_settings_option')) {
    $job_option_setting         = fw_get_db_settings_option('job_option', $default_value = null);
	$multiselect_freelancertype = fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
	$job_experience__type  		= fw_get_db_settings_option('job_experience_option', $default_value = null);
	$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
	$milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
	$restrict_proposals   		= fw_get_db_settings_option('restrict_proposals');
	$remove_freelancer_type   	= fw_get_db_settings_option('remove_freelancer_type');
	$remove_languages   		= fw_get_db_settings_option('remove_languages');
	$remove_english_level   	= fw_get_db_settings_option('remove_english_level');
	$remove_project_level   	= fw_get_db_settings_option('remove_project_level');
	$remove_project_duration   	= fw_get_db_settings_option('remove_project_duration');
	$job_faq_option				= fw_get_db_settings_option('job_faq_option', $default_value = null);
	$remove_project_attachments = fw_get_db_settings_option('remove_project_attachments');
	$remove_location_job   		= fw_get_db_settings_option('remove_location_job');
}

$remove_location_job		= !empty($remove_location_job) ? $remove_location_job : 'no';
$multiselect_freelancertype = !empty($multiselect_freelancertype) && $multiselect_freelancertype === 'enable' ?  'multiple': '';
$job_experience_option 		= !empty($job_experience__type['enable']['multiselect_experience']) && $job_experience__type['enable']['multiselect_experience'] === 'multiselect' ?  'multiple class=chosen-select': '';

$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
$job_option_setting 		= !empty($job_option_setting) ? $job_option_setting : '';
$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';
$remove_project_level 			= !empty($remove_project_level) ? $remove_project_level : 'no';
$remove_project_duration 		= !empty($remove_project_duration) ? $remove_project_duration : 'no';

$description = '';
$name = 'job[description]';								
$settings = array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'=> array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );

$edit_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
$post_author = get_post_field('post_author', $edit_id);

if(!empty($restrict_proposals) && $restrict_proposals === 'yes'){
	$place_holder_date	=  esc_html__('Project Expiry Date (required)','workreap');
} else{
	$place_holder_date	=  esc_html__('Project Expiry Date (optional)','workreap');
}

$currency	 = workreap_get_current_currency();
$placeholder = !empty($currency['symbol'] ) ? $currency['symbol'] : '$';
$placeholder = ' ('.$placeholder.')';

?>
<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 float-left">
	<div class="wt-haslayout wt-post-job-wrap">
	<?php 
		if ( intval($url_identity) === intval($post_author) ) {
			$args = array('posts_per_page' => -1,
                    'post_type' 		=> 'projects',
                    'post__in' 			=> array($edit_id),
					'post_status' 		=> 'any',
                    'suppress_filters'  => false
                );
			
			$query = new WP_Query($args);

			while ($query->have_posts()) : $query->the_post();
				global $post;
				$post_id		= $post->ID; 
				$author_id 		= get_the_author_meta( 'ID' );  
				$linked_profile = workreap_get_linked_profile_id($author_id);
				$employer_title = esc_html( get_the_title( $linked_profile ));
				$description	=  get_the_content();
			
				$is_featured   	= get_post_meta($post->ID,'_featured_job_string',true);
				$is_featured	= !empty( $is_featured ) ? 'on' : 'off';

				$_milestone   	= get_post_meta($post->ID,'_milestone',true);
				$is_milestone	= !empty( $_milestone ) ? $_milestone : 'off';
			
				$db_project_cat = wp_get_post_terms($post->ID, 'project_cat');
				$db_skills 		= wp_get_post_terms($post->ID, 'skills');
				$db_location	= wp_get_post_terms($post->ID, 'locations');
				$db_languages	= wp_get_post_terms($post->ID, 'languages');

				if(has_term('','project_experience',$post->ID)){
					$db_experience	= wp_get_post_terms($post->ID, 'project_experience');
				}
			
				$db_project_cat	= !empty( $db_project_cat ) ? wp_list_pluck($db_project_cat,'term_id') : array();
				$db_location	= !empty( $db_location ) ? wp_list_pluck($db_location,'term_id') : array();
				$db_languages	= !empty( $db_languages ) ? wp_list_pluck($db_languages,'term_id') : array();
				$db_experience	= !empty( $db_experience ) ? wp_list_pluck($db_experience,'term_id') : array();

			
				$db_project_level     = '';
				$db_project_duration  = '';
				$db_english_level     = '';
				$db_freelancer_level  = '';
				$db_project_type      = '';
				$db_address     	  = '';
				$db_latitude     	  = '';
				$db_longitude     	  = '';
				$db_country     	  = '';
				$db_expiry_date		  = '';
				$deadline		  	  = '';
			
				if (function_exists('fw_get_db_post_option')) {
					$db_project_level     = fw_get_db_post_option($post->ID,'project_level');
					$db_project_duration  = fw_get_db_post_option($post->ID,'project_duration');
					$db_english_level     = fw_get_db_post_option($post->ID,'english_level');
					$db_freelancer_level  = fw_get_db_post_option($post->ID,'freelancer_level');
					$db_project_type      = fw_get_db_post_option($post->ID,'project_type');

					if(!empty($job_price_option) && $job_price_option === 'enable') {
						$db_max_price      = fw_get_db_post_option($post->ID,'max_price');
						$place_holder	= esc_attr__('Minimum Price','workreap');
					} else{
						$place_holder	= esc_attr__('Project Price','workreap');
					}

					if(!empty($job_option_setting) && $job_option_setting === 'enable') {
						$db_job_option      = fw_get_db_post_option($post->ID,'job_option');
					}

					$db_address     	  = fw_get_db_post_option($post->ID,'address');
					$db_latitude     	  = fw_get_db_post_option($post->ID,'latitude');
					$db_longitude     	  = fw_get_db_post_option($post->ID,'longitude');
					$db_country     	  = fw_get_db_post_option($post->ID,'project_documents');
					$show_attachments   	= fw_get_db_post_option($post->ID,'show_attachments');
					$db_project_documents   = fw_get_db_post_option($post->ID,'project_documents');
					$db_expiry_date     	= fw_get_db_post_option($post->ID,'expiry_date');
					$db_deadline     		= fw_get_db_post_option($post->ID,'deadline');
				}
				
			
			
				$db_job_option	= !empty($db_job_option) ? $db_job_option : '';
				
				if( empty($multiselect_freelancertype) ) {
					if(empty($db_freelancer_level[0]) ) {
						$db_freelancer_level[]	= $db_freelancer_level;
					}
				}

				$db_job_type 	 		= !empty( $db_project_type['gadget'] ) ? $db_project_type['gadget'] : '';
				$db_hourly_rate  		= !empty( $db_project_type['hourly']['hourly_rate'] ) ? $db_project_type['hourly']['hourly_rate'] : '';
				$db_estimated_hours  	= !empty( $db_project_type['hourly']['estimated_hours'] ) ? $db_project_type['hourly']['estimated_hours'] : '';
				$db_project_cost 		= !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : '';

				// classes
				if( $db_job_type === 'hourly' ){
					$hourlyClass = '';
					$fixedClass  = 'elm-none';
				}
			
				if( $db_job_type === 'fixed' ){
					$fixedClass  = '';
					$hourlyClass = 'elm-none';
				}
			?>
			<form class="post-job-form wt-haslayout">
				<div class="wt-dashboardbox">
					<div class="wt-dashboardboxtitle">
						<h2><?php esc_html_e('Edit Job','workreap');?></h2>
					</div>
					<div class="wt-dashboardboxcontent">
						<div class="wt-jobdescription wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Job Description','workreap');?></h2>
							</div>
							<div class="wt-formtheme wt-userform wt-userformvtwo">
								<fieldset>
									<div class="form-group">
										<input type="text" value="<?php the_title();?>" name="job[title]" class="form-control count_project_title" placeholder="<?php esc_attr_e('Project title','workreap');?>">
									</div>
									<?php if(!empty($remove_project_level) && $remove_project_level === 'no' ){ ?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[project_level]" class="chosen-select">
													<option value=""><?php esc_html_e('Select project level','workreap');?></option>
													<?php 
													if( !empty( $project_level ) ){
														foreach( $project_level as $key => $level ){
														?>
														<option <?php selected($db_project_level,$key);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
													<?php }}?>
												</select>
												<?php do_action('workreap_get_tooltip','element','project_level');?>
											</span>
										</div>
									<?php } ?>
									<?php if(!empty($remove_project_duration) && $remove_project_duration === 'no' ){ ?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[project_duration]" class="chosen-select">
													<option value=""><?php esc_html_e('Select job duration','workreap');?></option>
													<?php 
													if( !empty( $job_duration ) ){
														foreach( $job_duration as $key => $level ){?>
														<option <?php selected($db_project_duration,$key);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
													<?php }}?>
												</select>
												<?php do_action('workreap_get_tooltip','element','project_duration');?>
											</span>
										</div>
									<?php } ?>
									<?php if(!empty($remove_freelancer_type) && $remove_freelancer_type === 'no' ){ ?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[freelancer_level][]" data-placeholder="<?php esc_attr_e('Select freelancer type','workreap');?>" <?php echo esc_attr( $multiselect_freelancertype );?>  class="chosen-select">
													<?php 
													if( !empty( $freelancer_level ) ){
														foreach( $freelancer_level as $key => $level ){
															$selected = '';
															if( is_array($db_freelancer_level) && in_array($key,$db_freelancer_level) ){
																$selected = 'selected';
															} else if($key == $db_freelancer_level) {
																$selected = 'selected';
															}
														?>
														<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
													<?php }}?>
												</select>
												<?php do_action('workreap_get_tooltip','element','freelancer_level');?>
											</span>
										</div>
									<?php }?>
									<?php if(!empty($remove_english_level) && $remove_english_level === 'no' ){ ?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[english_level]" class="chosen-select">
													<option value=""><?php esc_html_e('Select english level','workreap');?></option>
													<?php 
													if( !empty( $english_level ) ){
														foreach( $english_level as $key => $level ){?>
														<option <?php selected($db_english_level,$key);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
													<?php }}?>
												</select>
												<?php do_action('workreap_get_tooltip','element','english_level');?>
											</span>
										</div>
									<?php } ?>
									<?php if(!empty($job_option_setting) && $job_option_setting === 'enable' ){ ?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[job_option]" class="chosen-select">
													<option value=""><?php esc_html_e('Project location type','workreap');?></option>
													<?php 
													if( !empty( $job_options ) ){
														foreach( $job_options as $key => $val ){
															$selected	= '';
															if($db_job_option === $key ) $selected	='selected';
															?>
														<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
													<?php }}?>
												</select>
												<?php do_action('workreap_get_tooltip','element','job_option');?>
											</span>
										</div>
									<?php } ?>
									<?php if(!empty($job_experience__type['gadget']) && $job_experience__type['gadget'] === 'enable' ){?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects toolip-wrapo">
												<select name="job[experiences][]"  data-placeholder="<?php esc_attr_e('Select project experience','workreap');?>" <?php echo esc_attr( $job_experience_option );?>  class="chosen-select">
													<option value=""><?php esc_html_e('Select project experience','workreap');?></option>
													<?php 
													if( !empty( $experiences ) ){
														foreach ($experiences as $key => $item) {
															$term_id   = $item->term_id;
															$selected = '';
															if( is_array($db_experience) && in_array($term_id,$db_experience) ){
																$selected = 'selected';
															} else if( $db_experience == $key ){
																$selected = 'selected';
															}
															?>
																<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_html( $item->name ); ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												<?php do_action('workreap_get_tooltip','element','experiences');?>
											</span>
										</div>
									<?php } ?>
									<div class="form-group wt-formwithlabel form-group-half job-expirydate-input toolip-wrapo">
										<input type="text" name="job[expiry_date]" autocomplete="off" class="form-control wt-date-pick-job" value="<?php echo esc_attr( $db_expiry_date );?>" placeholder="<?php echo esc_attr($place_holder_date);?>">
										<?php do_action('workreap_get_tooltip','element','expiry_date');?>
									</div>
									<div class="form-group wt-formwithlabel form-group-half job-expirydate-input toolip-wrapo">
										<input type="text" name="job[deadline]" class="form-control wt-date-pick-job" autocomplete="off" value="<?php echo esc_attr( $db_deadline );?>" placeholder="<?php esc_attr_e('Project deadline date (optional)','workreap');?>">
										<?php do_action('workreap_get_tooltip','element','deadline');?>
									</div>
								</fieldset>
							</div>
						</div>
						<div class="wt-category-holder wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Prices','workreap');?></h2>
							</div>
							<div class="wt-formtheme wt-userform wt-userformvtwo">
								<fieldset>
									<div class="form-group form-group-half wt-formwithlabel">
										<span class="wt-selects">
											<select name="job[project_type]" class="wt-job-type chosen-select">
												<option value=""><?php esc_html_e('Select job type','workreap');?></option>
												<?php if( !empty( $job_type ) ){
													foreach( $job_type as $key => $level ){?>
														<option <?php selected($db_job_type,$key);?> data-key="<?php echo esc_attr( $key );?>" value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $level );?></option>
												<?php }}?>
											</select>
										</span>
									</div>
									
									<div class="form-group form-group-half wt-formwithlabel job-perhour-input <?php echo esc_attr($hourlyClass);?>">
										<input type="text" class="wt-numeric" name="job[hourly_rate]" value="<?php echo esc_attr( $db_hourly_rate );?>" placeholder="<?php esc_attr_e('Minimum Price','workreap');?><?php echo esc_html( $placeholder );?>">
									</div>
									<div class="form-group form-group-half wt-formwithlabel job-cost-input <?php echo esc_attr($fixedClass);?>">
										<input type="text" class="wt-numeric" name="job[project_cost]" value="<?php echo esc_attr( $db_project_cost );?>" placeholder="<?php echo esc_attr($place_holder);?><?php echo esc_html( $placeholder );?>">
									</div>
									<?php if(!empty($job_price_option) && $job_price_option === 'enable') {?>
										<div class="form-group form-group-half wt-formwithlabel">
											<input type="text" name="job[max_price]"  class="form-control wt-numeric" placeholder="<?php esc_attr_e('Maximum price','workreap');?><?php echo esc_html( $placeholder );?>" value="<?php echo esc_attr( $db_max_price );?>">
										</div>
									<?php } ?>
									<div class="form-group  wt-formwithlabel form-group-half  job-perhour-input <?php echo esc_attr($hourlyClass);?>">
										<input type="text" class="wt-numeric" name="job[estimated_hours]" value="<?php echo esc_attr( $db_estimated_hours );?>" placeholder="<?php esc_attr_e('Estimated hours','workreap');?>">
									</div>
									
								</fieldset>
							</div>
						</div>	
						<?php if(!empty($milestone) && $milestone ==='enable' ){ ?>
							<div class="job-cost-input <?php echo esc_attr($fixedClass);?>">
								<div class="wt-tabscontenttitle">
									<h2><?php esc_html_e('Will this project require milestone payments?','workreap');?></h2>
									<div class="wt-rightarea">
										<div class="wt-on-off float-right">
											<input type="hidden" value="off" name="job[is_milestone]">
											<input type="checkbox" <?php checked($is_milestone,'on');?> value="on" id="milestone-on" name="job[is_milestone]">
											<label for="milestone-on"><i></i></label>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="wt-category-holder wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Job Categories','workreap');?></h2>
							</div>
							<div class="wt-divtheme wt-userform wt-userformvtwo">
								<div class="form-group">
									<?php do_action('workreap_get_project_categories_list','job[categories][]',$db_project_cat);?>	
								</div>
							</div>
						</div>
						<?php if(!empty($remove_languages) && $remove_languages === 'no' ){ ?>	
							<div class="wt-language-holder wt-tabsinfo">
								<div class="wt-tabscontenttitle">
									<h2><?php esc_html_e('Languages','workreap');?></h2>
								</div>
								<div class="wt-divtheme wt-userform wt-userformvtwo">
									<div class="form-group">
										<select data-placeholder="<?php esc_attr_e('Select languages','workreap');?>" multiple name="job[languages][]"  class="chosen-select">
											<?php 
												if( !empty( $languages ) ){							
													foreach ($languages as $key => $item) {
														$term_id   = $item->term_id;	
														$selected = '';
														if( in_array($term_id,$db_languages) ){
															$selected = 'selected';
														}
														?>
														<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_html( $item->name ); ?></option>
														<?php 
													}
												}
											?>		
										</select>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="wt-jobdetails wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Job Details','workreap');?></h2>
							</div>
							<div class="wt-formtheme wt-userform wt-userformvtwo">
								<fieldset>
									<div class="form-group">
										<?php wp_editor($description, 'job_text_area', $settings);?>
									</div>
								</fieldset>
							</div>
						</div>	
						<div class="wt-jobskills wt-tabsinfo">
							<div class="wt-tabscontenttitle">
								<h2><?php esc_html_e('Skills required','workreap');?></h2>
							</div>
							<div class="wt-divtheme wt-userform wt-userformvtwo">
								<div class="form-group">
									<span class="wt-selects">
										<?php do_action('worktic_get_skills_list','skills','');?>
									</span>
								</div>
								<div class="form-group wt-btnarea">
									<a href="#" onclick="event_preventDefault(event);" class="wt-btn add-job-skills"><?php esc_html_e('Add skills','workreap');?></a>
								</div>
								<div class="form-group wt-myskills">
									<ul class="jobskills-wrap wt-haslayout">
										<?php 
											if( !empty( $db_skills ) ){
												foreach( $db_skills as $key => $skill ){
												?>
												<li class="wt-skill-list">
													<div class="wt-dragdroptool">
														<a href="javascript:" class="lnr lnr-menu"></a>
													</div>
													<span class="skill-dynamic-html"><?php echo esc_html( $skill->name );?></span>
													<span class="skill-dynamic-field">
														<input type="text" name="job[skills][]" value="<?php echo esc_attr( $skill->term_id );?>">
													</span>
													<div class="wt-rightarea">
														<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
													</div>
												</li>
												<?php
												}
											}
										?>
									</ul>
								</div>
							</div>
						</div>
						<div class="wt-attachmentsholder">
							<?php if(!empty($remove_project_attachments) && $remove_project_attachments === 'no' ){ ?>
								<div class="wt-tabscontenttitle">
									<h2><?php esc_html_e('Upload Relevant Project Files','workreap');?></h2>
								</div>
								<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
									<fieldset>
										<div class="form-group form-group-label" id="wt-job-container">
											<div class="wt-labelgroup" id="job-drag">
												<label for="file" class="wt-job-file">
													<span class="wt-btn" id="job-btn"><?php esc_html_e('Select file', 'workreap'); ?></span>								
												</label>
												<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
												<em class="wt-fileuploading"><?php esc_html_e('Uploading', 'workreap'); ?><i class="fa fa-spinner fa-spin"></i></em>
											</div>
										</div>
										<div class="form-group">
											<ul class="wt-attachfile uploaded-placeholder">
												<?php 
												if( !empty( $db_project_documents ) ){
													foreach( $db_project_documents as $key => $doc ){
														$attachment_id	= !empty( $doc['attachment_id'] ) ? $doc['attachment_id'] : '';
														$file_size 		= !empty( $doc) ? filesize(get_attached_file($attachment_id)) : '';
														$document_name	= !empty( $doc ) ? esc_html( get_the_title( $attachment_id ) ) : '';
														$filetype       = !empty( $doc ) ? wp_check_filetype( $doc['url'] ) : '';
														$extension  	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
														$doc_url 		= !empty( $doc['url'] ) ? $doc['url'] : '';
														
														$file_detail         = Workreap_file_permission::getDecrpytFile($doc);
														$name                = $file_detail['filename'];
														?>
													<li class="wt-doc-parent" id="thumb-<?php echo intval($attachment_id);?>">
														<span><?php echo esc_html($name);?></span>
														<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo size_format($file_size);?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
														<input type="hidden" class="attachment_url" name="job[project_documents][<?php echo intval($attachment_id);?>][attachment_id]" value="<?php echo esc_attr($attachment_id);?>">
														<input type="hidden" class="attachment_url" name="job[project_documents][<?php echo intval($attachment_id);?>][url]" value="<?php echo esc_attr($doc_url);?>">
													</li>
													<?php
														}
													}
												?>
											</ul>
										</div>
									</fieldset>
								</div>
								<div class="wt-tabscontenttitle">
									<h2><?php esc_html_e('Attachments','workreap');?></h2>
									<div class="wt-rightarea">
										<div class="wt-on-off float-right">
											<input type="hidden" value="off" name="job[show_attachments]">
											<input type="checkbox" <?php checked($show_attachments,'on');?> value="on" id="hide-on" name="job[show_attachments]">
											<label for="hide-on"><i></i></label>
										</div>
										<span><?php esc_html_e('Show “attachments” on job detail page','workreap');?></span>
									</div>
								</div>
							<?php }?>
							<?php if( apply_filters('workreap_is_job_posting_allowed','wt_jobs', $current_user->ID,'yes') === true ){
								if(apply_filters('workreap_is_listing_free',false,$current_user->ID) === false ){
								?>
								<div class="wt-tabscontenttitle">
									<h2><?php esc_html_e('Featured job','workreap');?></h2>
									<div class="wt-rightarea">
										<div class="wt-on-off float-right">
											<input type="hidden" value="off" name="job[is_featured]">
											<input type="checkbox" <?php checked($is_featured,'on');?> value="on" id="featured-on" name="job[is_featured]">
											<label for="featured-on"><i></i></label>
										</div>
									</div>
								</div>
							<?php }}?>
						</div>
						<?php 
							if(!empty($remove_location_job) && $remove_location_job !== 'yes' ) { get_template_part('directory/front-end/templates/employer/dashboard', 'job-location'); }
							if(!empty($job_faq_option) && $job_faq_option == 'yes' ) {
								get_template_part('directory/front-end/templates/dashboard', 'faq',array('post_id'=>$post_id,'title'=> esc_html__('Job FAQ', 'workreap'),'add'=> esc_html__('+Add job faq', 'workreap')));
							}
							do_action('workreap_add_fields');
						?>

					</div>
				</div>
				<div class="wt-updatall">
					<?php wp_nonce_field('wt_post_job_nonce', 'post_job'); ?>
					<input type="hidden" name="submit_type" value="update">
					<i class="ti-announcement"></i>
					<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'workreap'); ?></span>
					<a class="wt-btn wt-post-job" data-id="<?php echo intval($post->ID);?>" data-type="update"  href="#" onclick="event_preventDefault(event);"><?php esc_html_e('Save &amp; Update', 'workreap'); ?></a>
				</div>
			</form>
			<?php 
			endwhile;
            wp_reset_postdata();
		} else { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php Workreap_Prepare_Notification::workreap_warning(esc_html__('Restricted access', 'workreap'), esc_html__('You have not any privilege to view this page.', 'workreap')); ?>
            </div>
        <?php } ?>
		<script type="text/template" id="tmpl-load-job-skill">
			<li class="wt-skill-list">
				<div class="wt-dragdroptool">
					<a href="javascript:" class="lnr lnr-menu"></a>
				</div>
				<span class="skill-dynamic-html">{{data.name}}</span>
				<span class="skill-dynamic-field">
					<input type="text" name="job[skills][]" value="{{data.value}}">
				</span>
				<div class="wt-rightarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-deleteinfo wt-delete-skill"><i class="lnr lnr-trash"></i></a>
				</div>
			</li>
		</script>
		<script type="text/template" id="tmpl-load-job-attachments">
			<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
				<span class="uploadprogressbar uploadprogressbar-0"></span>
				<span>{{data.name}}</span>
				<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
				<input type="hidden" class="attachment_url" name="job[project_documents][]" value="{{data.url}}">	
			</li>
		</script>	
	</div>
</div>
