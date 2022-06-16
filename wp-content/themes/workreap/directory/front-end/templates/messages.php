<?php
/**
 *
 * The template part for displaying the messages
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $post,$current_user;

$post_id 		= $post->ID; 
if(is_singular('micro-services')) {
	$service_author = get_post_field('post_author', $post_id);
	$post_id 		= workreap_get_linked_profile_id($service_author);
}

$user_id		= workreap_get_linked_profile_id( $post_id,'post' );
$freelancer_avatar 	= apply_filters(
					'workreap_freelancer_avatar_fallback', workreap_get_freelancer_avatar( array( 'width' => 100, 'height' => 100 ), $post_id ), array( 'width' => 100, 'height' => 100 )
				);

$active_profile_id		= workreap_get_linked_profile_id( $current_user->ID );
$wp_user_id				= !empty( $current_user->ID ) ? $current_user->ID : 0;
$sender_name			= get_the_title($active_profile_id);

?>
<div class="wt-chatpopup">
	<div class="wt-chatbox load-wt-chat-message">
		<div class="wt-messages wt-verticalscrollbar wt-dashboardscrollbar ">
			<?php do_action('fetch_single_users_threads', $user_id, $current_user->ID); ?>
		</div>
		<div class="wt-replaybox">
			<div class="form-group">
				<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type message here', 'workreap'); ?>"></textarea>
			</div>
			<div class="wt-iconbox">
				<?php if( apply_filters('workreap_chat_window_floating', 'disable') === 'enable' ){?>
					<div class="wt-fileoption">
						<a href="#" class="wt-fileoption-icon"></i>
							<label for="chat_file_input">
								<i class="fa fa-paperclip"></i>
							</label>
							<input 
								type="file"
								id="chat_file_input"
								data-status="unread"
								data-receiver_id="<?php echo intval( $user_id );?>"
								data-sender-avatar="<?php echo esc_html( $freelancer_avatar );?>"
								data-sender-name="<?php echo esc_html( $sender_name );?>"
								data-is-sender="yes"
							/>
						</a>
					</div>
				<?php }?>
				<a href="#" onclick="event_preventDefault(event);" class="wt-btnsendmsg wt-send-single" data-msgtype="normals" data-receiver_id="<?php echo intval( $user_id );?>" data-status="unread">
					<?php esc_html_e('Send','workreap');?>
				</a>
			</div>
		</div>
	</div>
	<?php if( !empty( $freelancer_avatar ) ){ ?>
		<div id="wt-getsupport" class="wt-themeimgborder" data-currentid="<?php echo esc_attr( $wp_user_id );?>">
			<img src="<?php echo esc_url( $freelancer_avatar );?>" alt="<?php esc_attr_e( 'freelancer', 'workreap' );?>">
		</div>
	<?php } ?>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'underscore');?>
<?php
	$inline_script_v = 'jQuery(document).on("ready", function() { 
		eonearea = jQuery(".reply_msg").emojioneArea();
		eonearea[0].emojioneArea.setText("");
		refreshScrollBarObject();
	});';
	wp_add_inline_script( 'workreap-callbacks', $inline_script_v, 'after' );
?>
<?php
	$inline_script_v = 'jQuery(document).on("ready", function() { 
		eonearea_pop = jQuery(".load-wt-chat-message .reply_msg").emojioneArea();
		eonearea_pop[0].emojioneArea.setText("");
		refreshScrollBarObject();
	});';
	wp_add_inline_script( 'workreap-callbacks', $inline_script_v, 'after' );
?>