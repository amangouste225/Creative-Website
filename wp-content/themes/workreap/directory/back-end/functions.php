<?php
/**
 *
 * Functions
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */


/**
 * @get settings
 * @return {}
 */
if (!function_exists('workreap_profile_backend_settings')) {
	function  workreap_profile_backend_settings(){
		if(current_user_can('administrator')) {
			$list	= array(
				'payments'	 	=> 'payments',
			);
			return $list;
		}
		
		return array();
	}
}
