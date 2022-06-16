<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-bubble';

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$new_messages_img 	= fw_get_db_settings_option( 'new_messages', $default_value = null );
	$chat_api 			= fw_get_db_settings_option('chat');
	$new_messages_img	= !empty( $new_messages_img['url'] ) ? $new_messages_img['url'] : '';
}

$is_cometchat 	= false;
$is_wpguppy 	= false;

if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') {
	$is_cometchat = true;
}elseif (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
	$is_wpguppy = true;
}

if( apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', intval($user_identity)) === true ){?>
	<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
		<div class="wt-insightsitem wt-dashboardbox">
			<figure class="wt-userlistingimg">
				<?php if( !empty($new_messages_img) ) {?>
					<img src="<?php echo esc_url($new_messages_img);?>" alt="<?php esc_attr_e('New Messages', 'workreap'); ?>">
				<?php } else {?>
						<span class="<?php echo esc_attr($icon);?>"></span>
				<?php }?>
			</figure>
			<div class="wt-insightdetails">
				<div class="wt-title">
					<h3><?php esc_html_e('New Messages', 'workreap'); ?></h3>
					<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('chat', $user_identity); ?>"><?php esc_html_e('Click To View', 'workreap'); ?></a>
				</div>													
			</div>
			<em class="wtunread-count">
				<?php
				if ($is_cometchat) {
					do_action('workreap_get_unread_msgs', $user_identity );
				}else if ($is_wpguppy) {
					echo apply_filters('wpguppy_count_all_unread_messages', $user_identity );
				} else {
					do_action('workreap_chat_count', $user_identity );
				}
				?>
			</em>
		</div>
	</div>
<?php } ?>