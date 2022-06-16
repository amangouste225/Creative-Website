<?php

/**
 * atomchat_activation
 * Return create schedular
 * @param (type) no param
*/
if( !function_exists( 'atomchat_activation' ) ) { 
	function atomchat_activation() {
		if (! wp_next_scheduled ( 'atomchat_buddypress_groups_sync_scheduler' )) {
			wp_schedule_event(time(), 'hourly', 'atomchat_buddypress_groups_sync_scheduler');
		}
	}
}

/**
 * atomchat_deactivation
 * Return clear schedular
 * @param (type) no param
*/
if( !function_exists( 'atomchat_deactivation' ) ) { 
	function atomchat_deactivation() {
		wp_clear_scheduled_hook('atomchat_buddypress_groups_sync_scheduler');
		wp_clear_scheduled_hook('groups_group_create_complete');
	}
}
?>