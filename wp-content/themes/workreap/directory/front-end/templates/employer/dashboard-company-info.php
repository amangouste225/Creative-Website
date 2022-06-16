<?php 
/**
 *
 * The template part for displaying the employer company info
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
$employees	    = '';
$department	    = '';

if (function_exists('fw_get_db_post_option')) {
	$department     	= fw_get_db_post_option($post_id, 'department', true);	
	$employees     	 	= fw_get_db_post_option($post_id, 'no_of_employees', true);
	$hide_departments   = fw_get_db_settings_option('hide_departments', $default_value = null);
}

$department	=  !empty( $department[0] ) ? $department[0] : '';
if( !empty( $hide_departments ) && $hide_departments !== 'both' && $hide_departments !== 'site' ){
?>
<div class="wt-tabcompanyinfo wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Company details', 'workreap'); ?></h2>
	</div>
	<div class="wt-formtheme wt-userform">
		<div class="wt-accordiondetails">
			<div class="wt-radioboxholder">
				<div class="wt-title">
					<h4><?php esc_html_e('Your Department?', 'workreap'); ?></h4>
				</div>
				<?php do_action('worktic_get_departments_list',$department); ?>				
			</div>
			<div class="wt-radioboxholder">
				<div class="wt-title">
					<h4><?php esc_html_e('No. of employees you have', 'workreap'); ?></h4>
				</div>
				<?php do_action('workreap_print_employees_list',$employees); ?>
			</div>
		</div>		
	</div>
</div>
<?php }
