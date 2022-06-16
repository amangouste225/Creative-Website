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
$args_services = array(
					'author'        =>  $current_user->ID,
					'post_type'		=> 	'micro-services',
					'post_status'	=>  'publish',
					'orderby'       =>  'post_date',
					'order'         =>  'ASC',
					'posts_per_page' => -1
				);
$listings		= get_posts( $args_services );

$employers_list	= array();
if (function_exists('fw_get_db_settings_option')) {
	$chat_api = fw_get_db_settings_option('chat');
	if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
		$employers_list	= apply_filters('wpguppy_get_users_by_chat', $user_identity);
	}else {
		$employers_list = ChatSystem::getUsersThreadListData($user_identity, '', 'list_receivers', array(), '');
		if(!empty($employers_list) && is_array($employers_list)){
			$employers_list = wp_list_pluck( $employers_list, 'userId' );
		}
	}
}

$show_form	= false;
$title 		= '';
$desc_title = '';

if(empty($listings)){
	$show_form	= true; 
	$title 		= esc_html__('Service is required', 'workreap');
	$desc_title = esc_html__('It seems that you haven\'t posted any service yet. To send a quote to employers, you must post a service first.', 'workreap');
}else if(empty($employers_list)){
	$show_form	= true; 
	$title 		= esc_html__('No employers found', 'workreap');
	$desc_title = esc_html__('It seems that no one from employers has contacted you. To send a quote to employers, you must have a conversation with the employer.', 'workreap');
}

$description 		= '';
$name 				= 'quote[description]';								
$settings 			= array('media_buttons' => false,'textarea_name'=> $name,'editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'=> array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) ); 

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left">
	<div class="wt-haslayout wt-post-job-wrap wt-quote-wrapper">
		<?php if(!empty($show_form)){?>
			<div class="wt-emptydata-holder">
				<div class="tk-verification-section">
					<div class="tk-verification-content">
						<h5><?php  echo esc_html($title);?></h5>
						<p><?php  echo esc_html($desc_title);?></p>
					</div>
				</div>
			</div>
		<?php }else{?>
			<form class="post-quote-form wt-haslayout">
				<div class="wt-dashboardbox">
					<div class="wt-dashboardboxtitle">
						<h2><?php esc_html_e('Send a quote','workreap');?></h2>
					</div>
					<div class="wt-dashboardboxcontent">
						<div class="wt-jobdescription wt-tabsinfo">
							<div class="wt-formtheme wt-userform wt-userformvtwo">
								<fieldset>
									<?php if(!empty($employers_list) && is_array($employers_list)){?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects">
												<select name="quote[employer]" class="chosen-select">
													<option value=""><?php esc_html_e('Select employer','workreap');?></option>
													<?php 
														foreach( $employers_list as $key => $employer ){
															$username	= workreap_get_username( intval($employer) );
															$get_user_type	= apply_filters('workreap_get_user_type', $employer );
															?>
														<option value="<?php echo intval( $employer );?>"><?php echo esc_html( $username );?></option>
													<?php }?>
												</select>
											</span>
										</div>
									<?php }?>
									<?php if(!empty($listings)){?>
										<div class="form-group form-group-half wt-formwithlabel">
											<span class="wt-selects">
												<select name="quote[service]" class="chosen-select">
													<option value=""><?php esc_html_e('Select service','workreap');?></option>
													<?php 
														foreach( $listings as $service ){?>
														<option value="<?php echo intval( $service->ID );?>"><?php echo esc_html( $service->post_title );?></option>
													<?php }?>
												</select>
											</span>
										</div>
									<?php }?>
									<div class="form-group form-group-half wt-formwithlabel job-cost-input">
										<input type="text" class="wt-numeric" name="quote[price]" value="" placeholder="<?php esc_attr_e('Quote price','workreap');?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="quote[date]" class="form-control wt-date-pick-job" value="" placeholder="<?php esc_attr_e('Dilivery date', 'workreap'); ?>">
									</div>
									<div class="wt-jobdetails wt-tabsinfo">
										<div class="form-group">
											<?php wp_editor($description, 'description', $settings);?>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
				<div class="wt-updatall">
					<i class="ti-announcement"></i>
					<span><?php esc_html_e('Click the button to send the quote to selected employer','workreap');?></span>
					<button class="wt-btn wt-post-quote" data-type="add" type="submit"><?php esc_html_e('Send quote', 'workreap'); ?></button>
				</div>
			</form>
		<?php }?>
	</div>
</div>