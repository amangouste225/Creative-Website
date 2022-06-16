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

$post_id 		= !empty( $_GET['id'] ) ? $_GET['id'] : '';
$address 		= '';
$latitude 		= '51.5001524';
$longitude 		= '-0.1262362';
$location 		= '';
$hide_map 		= 'show';
$locations		= workreap_get_taxonomy_array('locations');

if ( function_exists('fw_get_db_settings_option') ) {
	$dir_latitude 	= fw_get_db_settings_option('dir_latitude');
	$dir_longitude 	= fw_get_db_settings_option('dir_longitude');
	$hide_map		= fw_get_db_settings_option('hide_map');
} 

$dir_latitude		= !empty( $dir_latitude ) ? $dir_latitude : $latitude; 
$dir_longitude		= !empty( $dir_longitude ) ? $dir_longitude : $longitude; 

$banner_image 	= array();
if(!empty($post_id)){
	if (function_exists('fw_get_db_post_option') ) {
		$address     	 	= fw_get_db_post_option($post_id, 'address', true);	
		$latitude     	 	= fw_get_db_post_option($post_id, 'latitude', true);	
		$longitude     	 	= fw_get_db_post_option($post_id, 'longitude', true);	
		$location 			= fw_get_db_post_option($post_id, 'country', true);
		$service_map 		= fw_get_db_post_option($post_id, 'service_map', true);	
	}
} else{
	$address     	 	= '';	
	$latitude     	 	= '';	
	$longitude     	 	= '';	
	$location 			= '';		
}

$service_map = !empty( $service_map ) ? $service_map : 'on';
//Get country
if( !empty( $location[0] ) ){
	$location = !empty( $location[0] ) ? $location[0] : '';
}

$latitude		= !empty( $latitude ) ? $latitude : $dir_latitude; 
$longitude		= !empty( $longitude ) ? $longitude : $dir_longitude; 

$location 		= !empty( $location ) ? intval($location) : '';
$rand 			= rand(9999, 999);
$banner_rand	= rand(999, 99999);
?>
<div class="wt-location wt-tabsinfo">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Your Location', 'workreap'); ?></h2>
	</div>
	<div class="wt-formtheme wt-userform">
		<fieldset>
			<div class="form-group form-group-half">
				<span class="wt-select wtloc">
					<?php do_action('worktic_get_locations_list','service[country]',$location);?>
				</span>
			</div>
			<?php if( isset( $hide_map ) && $hide_map === 'show' ){?>
				<div class="form-group form-group-half loc-icon">
					<input type="text" id="location-address-0" name="service[address]" class="form-control" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php esc_attr_e('Your Address', 'workreap'); ?>">
					<a href="#" onclick="event_preventDefault(event);" class="geolocate"><i class="fa fa-crosshairs"></i></a>
				</div>
				<div class="form-group wt-formmap">
					<div id="location-pickr-map" class="wt-locationmap location-pickr-map" data-latitude="<?php echo esc_attr( $latitude );?>" data-longitude="<?php echo esc_attr( $longitude );?>"></div>
				</div>
				<div class="form-group form-group-half toolip-wrapo">
					<input type="text" id="location-longitude-0" name="service[longitude]" class="form-control" value="<?php echo esc_attr( $longitude ); ?>" placeholder="<?php esc_attr_e('Enter Longitude', 'workreap'); ?>">
					<?php do_action('workreap_get_tooltip','element','longitude');?>
				</div>
				<div class="form-group form-group-half toolip-wrapo">
					<input type="text" id="location-latitude-0" name="service[latitude]" class="form-control" value="<?php echo esc_attr( $latitude ); ?>" placeholder="<?php esc_attr_e('Enter Latitude', 'workreap'); ?>">
					<?php do_action('workreap_get_tooltip','element','latitude');?>
				</div>
			<?php }?>
		</fieldset>
		
	</div>
</div>
<?php if( isset( $hide_map ) && $hide_map === 'show' ){?>
	<div class="job-cost-input">
		<div class="wt-tabscontenttitle">
			<h2><?php esc_html_e('Show map and get direction link on the service detail page','workreap');?></h2>
			<div class="wt-rightarea">
				<div class="wt-on-off float-right">
					<input type="hidden" value="off" name="service[service_map]">
					<input type="checkbox" <?php checked($service_map,'on');?> value="on" id="service_map-on" name="service[service_map]">
					<label for="service_map-on"><i></i></label>
				</div>
			</div>
		</div>
	</div>
	<?php
	$script = "jQuery(document).ready(function (e) {
				jQuery.workreap_init_profile_map(0,'location-pickr-map', ". esc_js($latitude) . "," . esc_js($longitude) . ");
			});";
	wp_add_inline_script('workreap-maps', $script, 'after');
}