<?php 
/**
 * Messaging template
 **/
global $current_user;
$user_identity = $current_user->ID;

$is_cometchat 	= false;
$is_wpguppy 	= false;
if (function_exists('fw_get_db_settings_option')) {
	$chat_api = fw_get_db_settings_option('chat');
	if (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'cometchat') {
		$is_cometchat = true;
	}elseif (!empty($chat_api['gadget']) && $chat_api['gadget'] === 'guppy') {
		$is_wpguppy = true;
	}
}
?>
<section class="wt-haslayout am-chat-module">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
	    <div class="wt-dashboardbox wt-messages-holder">
	        <div class="wt-dashboardboxtitle">
	           <h2><?php esc_html_e('Messages', 'workreap'); ?></h2>
			</div>
			<?php if ($is_cometchat) { ?>
				<?php echo do_shortcode("[atomchat layout='embedded' width='1290' height='980']");?>
			<?php } else if ($is_wpguppy) {?>
				<div class="wt-haslayout"><?php echo do_shortcode("[getGuppyConversation]");?></div>
			<?php }else{?>
				<div class="wt-dashboardboxtitle wt-titlemessages chat-current-user"></div>
				<div class="wt-dashboardboxcontent wt-dashboardholder wt-offersmessages">	
					<?php
						if (isset($_GET['ref']) && $_GET['ref'] == 'chat' && $_GET['identity'] == $user_identity) {
							do_action('fetch_users_threads', $user_identity);
						}
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<?php get_template_part('directory/front-end/templates/dashboard', 'underscore');?>