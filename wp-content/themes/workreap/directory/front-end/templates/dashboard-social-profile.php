<?php
/**
 *
 * The template part for displaying social profile 
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$user_type			= apply_filters('workreap_get_user_type', $user_identity );

if(!empty($user_type)){
	if($user_type === 'employer') {
		$socialmediaurls	= array();
		if( function_exists('fw_get_db_settings_option')  ){
			$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
		}
		
		$socialmediaurl 		= !empty($socialmediaurls) ? $socialmediaurls['gadget'] : '';

	} else if($user_type === 'freelancer') {
		$socialmediaurls	= array();
		
		if( function_exists('fw_get_db_settings_option')  ){
			$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
		}
		
		$socialmediaurl 		= !empty($socialmediaurls) ? $socialmediaurls['gadget'] : '';
	}
	
	$social_settings    = function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('no') : array();
?>

<div class="wt-yourdetails wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Social profile items', 'workreap'); ?></h2>
	</div>
	<div class="wt-formtheme wt-userform">
		<fieldset>
			<?php
				if(!empty($social_settings)) {
					foreach($social_settings as $key => $val ) {
						$icon		= !empty( $val['icon'] ) ? $val['icon'] : '';
						$classes	= !empty( $val['classses'] ) ? $val['classses'] : '';
						$placeholder	= !empty( $val['placeholder'] ) ? $val['placeholder'] : '';
						$color			= !empty( $val['color'] ) ? $val['color'] : '#484848';

						$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
						if( !empty($enable_value) && $enable_value === 'enable' ){ 
							$social_url	= '';
							if( function_exists('fw_get_db_post_option') ){
								$social_url	= fw_get_db_post_option($linked_profile, $key, null);
							}
							$social_url	= !empty($social_url) ? $social_url : '';
							?>
								<div class="form-group  wt-inputwithicon <?php echo esc_attr( $classes );?>">
									<i class="wt-icon <?php echo esc_attr( $icon );?>" style="color:<?php echo esc_attr( $color );?>"></i>
									<input type="text" name="basics[<?php echo esc_attr($key);?>]" class="form-control" value="<?php echo esc_attr($social_url); ?>" placeholder="<?php echo esc_attr($placeholder); ?>">
								</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
		</fieldset>
	</div>
</div>
<?php }