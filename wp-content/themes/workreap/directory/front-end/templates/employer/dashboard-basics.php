<?php 
/**
 *
 * The template part for displaying the freelancer profile basics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$first_name 	= get_user_meta($user_identity, 'first_name', true);
$last_name 		= get_user_meta($user_identity, 'last_name', true);
$display_name	= $current_user->display_name;

$post_id 		= $linked_profile;
$post_object 	= get_post( $post_id );
$content 	 	= !empty($post_object->post_content ) ? $post_object->post_content : '';
$tag_line 		= '';

$banner_image 	= array();
if (function_exists('fw_get_db_post_option')) {	
	$tag_line     	 	= fw_get_db_post_option($post_id, 'tag_line', true);	
}

$company_name	='';
if( function_exists('fw_get_db_settings_option')  ){
	$company_name	= fw_get_db_settings_option('company_name', $default_value = null);
}

$company_job_title	='';
if( function_exists('fw_get_db_settings_option')  ){
	$company_job_title	= fw_get_db_settings_option('company_job_title', $default_value = null);
}

$user_phone_number 		= '';
if (function_exists('fw_get_db_post_option')) {	
	$user_phone_number  = fw_get_db_post_option($post_id, 'user_phone_number');	
}
$user_phone_number	= !empty($user_phone_number) ? $user_phone_number : '';

$phone_option	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$phone_option	= fw_get_db_settings_option('phone_option', $default_value = null);
	$phone_option	= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
	
}

$settings 		= array('media_buttons' => false,'textarea_name'=> 'basics[content]','editor_class'=> 'customwp_editor','media_buttons','editor_height'=>300,'tinymce'       => array(
	'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,bullist,numlist,formatselect',
	'toolbar2'      => '',
	'toolbar3'      => '',
) );
?>
<div class="wt-yourdetails wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Your basics', 'workreap'); ?></h2>
	</div>
	<div class="wt-formtheme wt-userform">
		<fieldset>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" value="<?php echo esc_attr( $first_name ); ?>" name="basics[first_name]" class="form-control" placeholder="<?php esc_attr_e('First Name', 'workreap'); ?>">
				<?php do_action('workreap_get_tooltip','element','first_name');?>
			</div>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" value="<?php echo esc_attr( $last_name ); ?>" name="basics[last_name]" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'workreap'); ?>">
				<?php do_action('workreap_get_tooltip','element','last_name');?>
			</div>
			<?php if(!empty($phone_option) && $phone_option === 'enable' ){ ?>
				<div class="form-group toolip-wrapo">
					<input type="text" value="<?php echo esc_attr( $user_phone_number ); ?>" name="basics[user_phone_number]" class="form-control" placeholder="<?php esc_attr_e('Phone number', 'workreap'); ?>">
					<?php do_action('workreap_get_tooltip','element','user_phone_number');?>
				</div>
			<?php } ?>
			<div class="form-group toolip-wrapo">
				<input type="text" name="basics[tag_line]" class="form-control count_tagline" value="<?php echo esc_attr( stripslashes( $tag_line ) ); ?>" placeholder="<?php esc_attr_e('Add your tagline here', 'workreap'); ?>">
				<?php do_action('workreap_get_tooltip','element','tagline');?>
			</div>
			<div class="form-group toolip-wrapo">
				<input type="text" name="basics[display_name]" class="form-control" value="<?php echo esc_attr( $display_name ); ?>" placeholder="<?php esc_attr_e('Display name', 'workreap'); ?>">
				<?php do_action('workreap_get_tooltip','element','display_name');?>
			</div>
			<?php if(!empty($company_name) && $company_name === 'enable') { 
					$job_company_name	= '';
					if( function_exists('fw_get_db_post_option') ){
						$job_company_name	= fw_get_db_post_option($post_id, 'company_name', true);
					}
					
				?>
				<div class="form-group form-group-half toolip-wrapo">
					<input type="text" value="<?php echo esc_attr( $job_company_name ); ?>" name="basics[company_name]" class="form-control" placeholder="<?php esc_attr_e('Company name', 'workreap'); ?>">
					<?php do_action('workreap_get_tooltip','element','company_name');?>
				</div>
			<?php } ?>
			<?php 
				if(!empty($company_job_title) && $company_job_title === 'enable') {
					$job_title	= '';
					if( function_exists('fw_get_db_post_option') ){
						$job_title	= fw_get_db_post_option($post_id, 'company_job_title', true);
					}
				?>
				<div class="form-group form-group-half toolip-wrapo">
					<input type="text" value="<?php echo esc_attr( $job_title ); ?>" name="basics[company_name_title]" class="form-control" placeholder="<?php esc_attr_e('Job title', 'workreap'); ?>">
					<?php do_action('workreap_get_tooltip','element','comapny_job_title_name');?>
				</div>
			<?php } ?>
			
		</fieldset>
	</div>
</div>
<div class="wt-yourdetails wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Add brief description', 'workreap'); ?></h2>
	</div>
	<div class="form-group">
		<?php wp_editor($content, 'employer_details', $settings);?>
	</div>
</div>
