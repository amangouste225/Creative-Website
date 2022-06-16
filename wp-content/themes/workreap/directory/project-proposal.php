<?php 
/**
 * Template Name: Project Proposal
 *
 * @package Workreap
 * @since Workreap 1.0
 * @desc Template used for front end proposal submission.
 */
get_header();
global $current_user;
$project_id 		= !empty( $_GET['project_id'] ) ? $_GET['project_id'] : '';
$proposal_id 		= !empty( $_GET['proposal_id'] ) ? $_GET['proposal_id'] : '';
$allow_proposal_edit = '';
?>
<?php if( have_posts() ) {?>
	<div class="wt-haslayout wt-haslayout page-data">
		<?php 
			while ( have_posts() ) : the_post();
				the_content();
				wp_link_pages( array(
									'before'      => '<div class="wt-paginationvtwo"><nav class="wt-pagination"><ul>',
									'after'       => '</ul></nav></div>',
								) );
			endwhile;
			wp_reset_postdata();
		?>
	</div>
<?php }?>

<?php
if( !empty( $project_id ) && get_post_type( $project_id ) == 'projects' ){
	$expiry_date = '';
	$project_title 		= esc_html( get_the_title( $project_id ));
	$service_fee 		= '';
	$service_hint 		= '';
	$deduction_hint 	= '';
	$blog_name		 	= get_bloginfo( 'name' );	

	if (function_exists('fw_get_db_post_option')) {
		$service_fee_settings	= fw_get_db_settings_option('service_fee');
		$service_hint    		= fw_get_db_settings_option('hint_text');
		$deduction_hint    		= fw_get_db_settings_option('hint_text_two');
		$db_project_type    	= fw_get_db_post_option($project_id,'project_type');
		$allow_proposal_edit    = fw_get_db_settings_option('allow_proposal_edit');
		$expiry_date   			= fw_get_db_post_option($project_id, 'expiry_date', true);
		$restrict_proposals   	= fw_get_db_settings_option('restrict_proposals');
	} 
	
	$service_fee_settings	= !empty($service_fee_settings['gadget']) ? $service_fee_settings['gadget']  : 'none';
	$price_symbol		= workreap_get_current_currency();
	$price_symbol		= !empty($price_symbol['symbol']) ? $price_symbol['symbol'] : '$';
	
	$proposed_cost_text		= esc_html__('Your proposed amount', 'workreap');
	$employer_proposed_text	= esc_html__('Employer’s proposed amount', 'workreap');
	$after_deduction_text	= sprintf(__('Amount, You’ll receive after <strong>%s</strong> service fee deduction', 'workreap'),$blog_name);
	$amount_text			= esc_html__('Enter your proposal amount','workreap');
	$proposed_cost_text		= esc_html__('Your proposed amount', 'workreap');
	
	if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){
		$project_cost 			= !empty( $db_project_type['fixed']['project_cost'] ) ? $db_project_type['fixed']['project_cost'] : '';
	} else if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){
		$estimated_hours 		= !empty( $db_project_type['hourly']['estimated_hours'] ) ? $db_project_type['hourly']['estimated_hours'] : 0;
		$project_cost 			= !empty( $db_project_type['hourly']['hourly_rate'] ) ? $estimated_hours * ($db_project_type['hourly']['hourly_rate'] ) : 0;
		$proposed_cost_text		= esc_html__('Your proposed hourly rate', 'workreap');
		$amount_text			= esc_html__('Enter your per hour rate','workreap');
		$employer_proposed_text	= esc_html__('Employer’s proposed hours and hourly rate', 'workreap');
		$after_deduction_text	= sprintf(__('Hourly price, You’ll receive after <strong>%s</strong> service fee deduction', 'workreap'),$blog_name);
	}
	
	$project_price		= workreap_project_price($project_id);
	$service_fee		= workreap_commission_fee(0,'projects',$project_id);

	if( isset( $project_price['type'] ) && $project_price['type'] === 'hourly' ){
		$project_price_val	= !empty( $project_price['cost'] ) ? $project_price['cost'] : 0.0;
		$project_cost	= wp_sprintf(__('%s Per hour rate (for %s hours)','workreap'), $project_price_val,$estimated_hours);
	} else{
		$project_cost	= !empty($project_price['cost']) ? $project_price['cost'] : 0;
	}
	
	$max_val		= !empty($project_price['max_val']) ? $project_price['max_val'] : 1000000000;
	$amount_text	= !empty($project_price['amount_text']) ? $project_price['amount_text'] : '';

	$list              	= worktic_job_duration_list();

	//Get Project Skills	
	$project_skills 	= array();	
	$freelancer_skills 	= array();
	$skills_matched 	= array();
	$skills_string 		= '';
	$projects_skills = wp_get_post_terms($project_id, 'skills', true);		
	
	if( taxonomy_exists('skills') ) {
		if( !empty( $projects_skills ) ){
			foreach ( $projects_skills as $key => $value ) {
				$project_skills[] = $value->term_id;
				$skills_string	.= '“'.$value->name.'”&nbsp;';
			}

			//Get Freelancer Skills
			$post_id = workreap_get_linked_profile_id($current_user->ID);			
			if( !empty( $post_id ) ){
				$skills = wp_get_post_terms($post_id, 'skills', true);		
				if( !empty( $skills ) ){
					foreach ( $skills as $key => $value ) {
						$freelancer_skills[] = $value->term_id;				
					}
				}		
			}

			//Comparision		
			$skills_matched = array_intersect( $project_skills, $freelancer_skills );			
		} else {
			//If project skills not set
			$skills_matched[0] = 'set';
		} 
	}
	
	if (function_exists('fw_get_db_settings_option')) {
		$job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
	}
	
	$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
	
	if(!empty($job_price_option) && $job_price_option === 'enable') {
		$db_max_price      = fw_get_db_post_option($post->ID,'max_price');
		$place_holder	= esc_attr__('Minimum Price','workreap');
	} else{
		$place_holder	= esc_attr__('Project Price','workreap');
	}

	$is_visible	= 'yes';
	
	
	$proposal_docs		= '';
	$proposed_amount	= '';
	$estimeted_time		= '';
	$per_hour_amount	= '';
	$service_count		= '';
	$remaining_cost		= '0.0';
	$proposal_duration	= '';
	$proposal_content	= '';
	$proposal_update	= '';
	$proposed_cost		= '0.0';
	$proposal_docs		= array();
	$post_author		= '';
	if(!empty($proposal_id)){
		$post_author	= get_post_field( 'post_author', $proposal_id, true );
		$post_status	= get_post_field( 'post_status', $project_id, true );
		$post_author	= !empty($post_author) ? intval($post_author) : '';
	}

	if(!empty($proposal_id) && !empty($post_author) 
	   && ($current_user->ID == $post_author) 
	   && !empty( $post_status ) 
	   && $post_status != 'hired'
	   && $allow_proposal_edit == 'yes' 
	){
		$proposal_update	= $proposal_id;
		if (function_exists('fw_get_db_post_option')) {
			$proposed_amount 	= fw_get_db_post_option($proposal_id, 'proposed_amount', true);
			$proposal_duration 	= fw_get_db_post_option($proposal_id, 'proposal_duration', true);
			$proposal_docs 		= fw_get_db_post_option($proposal_id, 'proposal_docs');

			//hourly values
			$estimeted_time 	= fw_get_db_post_option($proposal_id, 'estimeted_time', true);
			$per_hour_amount 	= fw_get_db_post_option($proposal_id, 'per_hour_amount', true);
		}
		
		if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ) {
			$proposed_cost		= !empty($per_hour_amount) ? $per_hour_amount : 0.00;
			$service_fee		= workreap_commission_fee($proposed_cost,'projects',$project_id);
		}else{
			$proposed_cost		= !empty($proposed_amount) ? $proposed_amount : 0.00;
			$service_fee		= workreap_commission_fee($proposed_cost,'projects',$project_id);
		}

		$proposal_content	= get_post_field( 'post_content', $proposal_id, true );
		$proposal_content	= !empty($proposal_content) ? $proposal_content : '';

		$service_count	= !empty($service_fee['admin_shares']) ? $service_fee['admin_shares'] : 0.00;
		$remaining_cost	= !empty($proposed_cost) && !empty($service_count) ? $proposed_cost - $service_count : 0.00 ;
		
		if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' && !empty($per_hour_amount) ){
			$proposed_cost			= !empty($proposed_amount) ? $per_hour_amount : 0.00;
			$proposed_amount		= $per_hour_amount;
		}
		
		//Check if user already submitted proposal
		if(!empty($current_user->ID)){
			$proposals_sent = intval(0);
			$args = array(
				'post_type' => 'proposals',
				'author'    =>  $current_user->ID,
				'meta_query' => array(
					array(
						'key'     => '_project_id',
						'value'   => intval( $project_id ),
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query( $args );
			if( !empty( $query ) ){
			   $proposals_sent =  $query->found_posts;
			}
			
			if( $proposals_sent > 0 ){
				$proposal_message = esc_html__('You have already sumitted the proposal on this project', 'workreap');
			}
		}
		
	}
	
	
	?>
	<div class="wt-haslayout wt-proposal-single">	
		<div class="container">
			<div class="row justify-content-md-center">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
					<?php
					if( is_user_logged_in() ) {
						$user_type		= apply_filters('workreap_get_user_type', $current_user->ID );
						?>
						<div class="wt-jobalertsholder">
							<?php 
								if ( $user_type === 'freelancer' ) {
									if( empty($proposal_id) ){
										$title		= esc_html__('Info','workreap');
										if(  empty( $skills_matched ) ) {
											$message	= esc_html__('You’ve no skills of','workreap');
											$skills     = esc_html( $skills_string ); 
											$message_v2	= esc_html__('but still you can apply for this project.', 'workreap');
											$final 		= $message.' '.$skills.' '.$message_v2;
											Workreap_Prepare_Notification::workreap_warning($title, $final, '', '');								
										} 

										if( apply_filters('workreap_feature_connects', $current_user->ID) === false ){
											$link		= Workreap_Profile_Menu::workreap_profile_menu_link('package', $current_user->ID,true);
											$message	= esc_html__('You’ve consumed all your credits to apply on this job.','workreap');
											$title		= esc_html__('Alert :','workreap');
											Workreap_Prepare_Notification::workreap_warning($title, $message, $link, esc_html__("Buy Now",'workreap'));								
										}

										if(!empty($proposal_message) && empty($proposal_id)){
											Workreap_Prepare_Notification::workreap_warning('', $proposal_message, '', ''); 
										}

										if(!empty($restrict_proposals) && $restrict_proposals === 'yes' && !empty($expiry_date)){
											$expiry_date	  = !empty($expiry_date) ? workreap_date_format_fix($expiry_date) : '';
											if( !empty($expiry_date) && current_time( 'timestamp' ) > strtotime($expiry_date) ){
												Workreap_Prepare_Notification::workreap_warning($title, esc_html__("This job has been expired, you are not allowed to send proposal on this job",'workreap'), '', '');						
											}
										}
									}
									
								} else {
									$is_visible	= 'no';
									$message	= esc_html__('You are not allowed to submit the proposal on the job','workreap');
									$title		= esc_html__('Info','workreap');
									Workreap_Prepare_Notification::workreap_warning($title, $message, '', '');
								}
							?>
						</div>
					<?php }?>
					<?php if( isset( $is_visible ) && $is_visible === 'yes' ){?>
						<div class="wt-proposalholder">					
							<div class="wt-proposalhead">
								<h2><?php echo esc_html( $project_title ); ?></h2>
								<?php do_action( 'workreap_job_detail_header', $project_id ); ?>
							</div>
						</div>
						<form class="wt-proposalamount-holder wt-send-project-proposal">
							<div class="wt-title">
								<h2><?php esc_html_e('Proposal Amount', 'workreap'); ?></h2>
							</div>
							<div class="wt-proposalamount accordion">
								<div class="form-group">
									<span>(<i><?php echo esc_html($price_symbol);?></i> )</span>
									<input type="number" value="<?php echo esc_html($proposed_amount);?>" name="proposed_amount" class="form-control wt-proposal-amount" min="0" max="<?php echo intval($max_val);?>" placeholder="<?php echo esc_attr($amount_text); ?>" data-id="<?php echo esc_attr($project_id); ?>">
									<a href="#" onclick="event_preventDefault(event);" class="collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="lnr lnr-chevron-up"></i></a>
									<?php if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'hourly' ){ ?>
										<input type="number" value="<?php echo esc_html($estimeted_time);?>"  name="estimeted_time" class="form-control wt-estimated-hours" min="0" placeholder="<?php echo esc_attr_e('Estimated hours','workreap'); ?>">
									<?php } ?>
									<em><?php esc_html_e('Total amount the client will see on your proposal', 'workreap'); ?></em>
								</div>
								<ul class="wt-totalamount collapse show" id="collapseOne" aria-labelledby="headingOne">
									<?php if( !empty($project_cost) ){?>
									<li class="sp-project-cost">
										<h3>(<i><?php echo esc_html($price_symbol);?></i> ) <em class="wt-project-cost"><?php echo esc_html($project_cost); ?></em></h3>
										<span><strong><?php echo esc_html($employer_proposed_text); ?></strong></span>
									</li>
									<li class="sp-proposed-cost">
										<h3>(<i><?php echo esc_html($price_symbol);?></i> ) <em class="wt-project-proposed"><?php echo esc_html($proposed_cost); ?></em></h3>
										<span><strong><?php echo esc_html($proposed_cost_text); ?></strong></span>
									</li>
									<?php }?>
									<?php if( !empty( $service_fee ) && !empty($service_fee_settings) && $service_fee_settings !== 'none' ){?>
										<li class="sp-services-fee">
											<h3>(<i><?php echo esc_html($price_symbol);?></i> ) <em class="wt-service-fee"><?php echo esc_html('- '.$service_count); ?></em></h3>
											<span><strong><?php echo esc_html( $blog_name ); ?></strong> <?php esc_html_e('Service fee', 'workreap'); ?><?php if( !empty( $service_hint ) ){ ?><i class="fa fa-exclamation-circle template-content tipso_style wt-tipso" data-tipso="<?php echo esc_attr( $service_hint ); ?>"></i><?php } ?></span>
										</li>
										<li class="sp-after-services-fee">
											<h3>(<i><?php echo esc_html($price_symbol);?></i> ) <em class="wt-user-amount"><?php echo esc_html($remaining_cost); ?></em></h3>
											<span><?php echo do_shortcode($after_deduction_text);?><?php if( !empty( $deduction_hint ) ){ ?><i class="fa fa-exclamation-circle template-content tipso_style wt-tipso" data-tipso="<?php echo esc_attr( $deduction_hint ); ?>"></i><?php } ?></span>
										</li>
									<?php }?>
								</ul>
							</div>
							<div class="wt-formtheme wt-formproposal">
								<fieldset>
									<?php if( !empty( $db_project_type['gadget'] ) && $db_project_type['gadget'] === 'fixed' ){ ?>
										<div class="form-group">
											<span class="wt-select">
												<select name="proposed_time">
													<option value=""><?php esc_html_e('Select time period', 'workreap'); ?></option>
													<?php 
														foreach ($list as $key => $value) { 
															if(!empty($proposal_duration) && $proposal_duration === $key ){
																$selected	= 'selected';
															} else {
																$selected	= '';
															}
														?>
														<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr($selected);?>><?php echo esc_html( $value ); ?></option>
													<?php } ?>										
												</select>
											</span>
										</div>
									<?php } ?>
									<div class="form-group">
										<textarea name="proposed_content" class="form-control" placeholder="<?php esc_attr_e('Add description*', 'workreap'); ?>"><?php echo esc_html($proposal_content);?></textarea>
									</div>
								</fieldset>
								<div class="wt-formtheme wt-formprojectinfo wt-formcategory">
									<fieldset>
										<div class="form-group form-group-label" id="wt-proposal-container">
											<div class="wt-labelgroup" id="proposal-drag">
												<label class="wt-proposal-file">
													<span class="wt-btn" id="proposal-btn"><?php esc_html_e('Select File', 'workreap'); ?></span>						
												</label>
												<span><?php esc_html_e('Drop files here to upload', 'workreap'); ?></span>
											</div>
										</div>
										<div class="form-group">
											<ul class="wt-attachfile uploaded-placeholder">
												<?php 
													if( !empty($proposal_docs) ){
														foreach( $proposal_docs as $doc_key => $proposal_doc ){

															$attachment_id	= !empty($proposal_doc['attachment_id']) ? $proposal_doc['attachment_id'] : '';
															$name			= !empty( $proposal_doc['name'] ) ?  $proposal_doc['name'] : '';
															$url			= !empty( $proposal_doc['url'] ) ? $proposal_doc['url'] : '';
															$doc_file_size 	= !empty( $proposal_doc['attachment_id']) ? filesize(get_attached_file($proposal_doc['attachment_id'])) : '';
												?>
													<li class="attachment-new-item wt-doc-parent" id="thumb-<?php echo esc_attr($attachment_id);?>">
														<span class="uploadprogressbar uploadprogressbar-0"></span>
														<span><?php echo esc_html($name);?></span>
														<em><?php esc_html_e('File size:', 'workreap'); ?> <?php echo esc_html( size_format($doc_file_size, 2) ); ?><a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
														<input type="hidden" class="attachment_url" name="temp_items[<?php echo esc_attr($doc_key);?>][url]" value="<?php echo esc_url($url);?>">
														
														<input type="hidden" class="attachment_id" name="temp_items[<?php echo esc_attr($doc_key);?>][attachment_id]" value="<?php echo esc_attr($attachment_id);?>">	
														<input type="hidden" class="attachment_id" name="temp_items[<?php echo esc_attr($doc_key);?>][name]" value="<?php echo esc_attr($name);?>">
													</li>
													
													<?php } ?>
												<?php } ?>
											</ul>
										</div>
										<?php if(!empty($proposal_update)) { ?>
											<input type="hidden" name="proposal_id" value="<?php echo intval($proposal_update);?>" />
											<?php } ?>
										<div class="wt-btnarea">
											<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-process-proposal" data-id="<?php echo esc_attr( $current_user->ID ); ?>" data-post="<?php echo esc_attr( $project_id ); ?>"><?php esc_html_e('Send Now', 'workreap'); ?></a>
										</div>	
									</fieldset>
								</div>
							</div>
						</form>
					<?php }?>
				</div>
			</div>
		</div>
		<script type="text/template" id="tmpl-load-proposal-docs">
			<li class="wt-uploading attachment-new-item wt-doc-parent" id="thumb-{{data.id}}">
				<span class="uploadprogressbar uploadprogressbar-0"></span>
				<span>{{data.name}}</span>
				<em><?php esc_html_e('File size:', 'workreap'); ?> {{data.size}}<a href="#" onclick="event_preventDefault(event);" class="lnr lnr-cross wt-remove-attachment"></a></em>
				<input type="hidden" class="attachment_url" name="temp_items[]" value="{{data.url}}">	
			</li>
		</script>	
	</div>
	<?php } else { ?>
	<div class="wt-haslayout wt-main-section">	
		<div class="container">
			<div class="row justify-content-md-center">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 push-lg-2">
					<div class="wt-jobalertsholder">
						<ul class="wt-jobalerts">
							<li class="alert alert-warning alert-dismissible fade show">
								<span><em><?php esc_html_e('You’re Late:', 'workreap'); ?></em>&nbsp;<?php esc_html_e('We’re sorry but the job you want to apply is no longer available.', 'workreap'); ?></span>
								<a href="#" onclick="event_preventDefault(event);" class="close" data-dismiss="alert" aria-label="Close"></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }
get_footer();