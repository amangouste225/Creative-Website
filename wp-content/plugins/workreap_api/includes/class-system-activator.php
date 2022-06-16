<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    AndroidApp
 * @subpackage AndroidApp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Elevator
 * @subpackage AndroidApp/includes
 * @author     Amento Tech <theamentotech@gmail.com>
 */
class AndroidApp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$post_tbl 		= $wpdb->prefix . "posts";
		$meta_tbl 		= $wpdb->prefix . "postmeta";
		$query_result   = $wpdb->get_row( "
				SELECT  p.ID
				FROM   ".$post_tbl." p 
				LEFT JOIN ".$meta_tbl." pm1 ON (pm1.post_id = p.ID  AND pm1.meta_key = '_wp_page_template') 
				WHERE post_status = 'publish'
				AND pm1.meta_value    = 'mobile-checkout.php'
				AND p.post_type = 'page'
			",ARRAY_A);

		if( empty( $query_result ) ){

			$page_exist = $wpdb->insert( 
                $post_tbl, 
                array( 
                    'post_title' 	=> 'Mobile Checkout', 
                    'post_name' 	=> 'mobile-checkout',
                    'guid' 			=> site_url()."/mobile-checkout",
                    'post_type' 	=> 'page',
                    'post_status' 	=> 'publish',
                    'post_author' 	=> 1,
                    'ping_status' 	=> 'closed',
                    'comment_status'=> 'closed',
                    'menu_order' 	=> 0
                ), 
                array( 
                    '%s',
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s',  
                    '%d',
                    '%s', 
                    '%s', 
                    '%d'
                ) 
            );
			
			$last_id  = $wpdb->insert_id;
			
			if( !empty( $last_id ) ){
				update_post_meta($last_id,'_wp_page_template','mobile-checkout.php');
			}
		}
		
		//Checkout creat temporary table
		$sql_query = "CREATE TABLE IF NOT EXISTS `" . MOBILE_APP_TEMP_CHECKOUT . "` (
					  `id` int(11) NOT NULL auto_increment,
					  `temp_data` text NOT NULL,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					   PRIMARY KEY (`id`)
					)ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );	


		if($wpdb->get_var("SHOW TABLES LIKE '".MOBILE_APP_TEMP_CHECKOUT."'") != MOBILE_APP_TEMP_CHECKOUT) {
			$wpdb->query($sql_query);
		}
	}
}
