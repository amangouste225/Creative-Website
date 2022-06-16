<?php
/**
 *
 * The template used for displaying default project category result
 *
 * @package   workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
global $wp_query;
get_header();
$access_type	= workreap_return_system_access();
if (function_exists('fw_get_db_post_option') ) {
	$services_categories	= fw_get_db_settings_option('services_categories');
}

$services_categories	= !empty($services_categories) ? $services_categories : 'no';

if( !empty($access_type) && $access_type === 'service' ) {
	get_template_part("directory/services", "search");
} else{
	get_template_part("directory/project", "search");
}
