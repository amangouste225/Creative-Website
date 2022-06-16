<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;

if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$chat_api 			= fw_get_db_settings_option('chat');
}

$is_cometchat 	= false;
$is_wpguppy 	= false;

if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') {
	$is_cometchat = true;
}elseif (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
	$is_wpguppy = true;
}

if( apply_filters('workreap_is_feature_allowed', 'wt_pr_chat', $user_identity ) === true ){?>
	<li class="toolip-wrapo <?php echo esc_attr( $reference === 'chat' ? 'wt-active' : ''); ?>">
		<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('chat', $user_identity); ?>">
			<i class="ti-email"></i>
			<span>
				<?php esc_html_e('Inbox','workreap');?>
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
			</span>
			<?php do_action('workreap_get_tooltip','element','chat');?>
		</a>
	</li>
<?php } ?>