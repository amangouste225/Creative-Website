<?php 
$upload_dir = wp_upload_dir();
$upload_dir_path = $upload_dir['baseurl'];
if ( function_exists('fw_get_db_post_option' )) {
	$chat_settings    	= fw_get_db_settings_option('chat');
}
$chat_gadget			=  !empty( $chat_settings['gadget'] ) ?  $chat_settings['gadget'] : 'inbox';
?>
<script type="text/template" id="tmpl-load-chat-replybox">
<div class="wt-messages wt-verticalscrollbar wt-dashboardscrollbar chat-history-wrap"></div>
<div class="wt-replaybox">
	<div class="form-group">
		<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type message here', 'workreap'); ?>"></textarea>
	</div>
	<div class="wt-iconbox">
		<?php if( !empty($chat_gadget) && $chat_gadget === 'chat' ){?>
			<div class="wt-fileoption">
				<a href="#" class="wt-fileoption-icon"></i>
					<label for="chat_file_input">
						<i class="fa fa-paperclip"></i>
					</label>
					<input 
						type="file"
						id="chat_file_input"
						data-status="unread"
						data-receiver_id="{{data.receiver_id}}"
						data-sender-avatar="{{data.sender_data.avatar}}"
						data-sender-name="{{data.sender_data.username}}"
						data-is-sender="yes"
					/>
				</a>
			</div>
		<?php }?>
		<a href="#" onclick="event_preventDefault(event);" class="wt-btnsendmsg wt-send" data-status="unread" data-receiver_id="{{data.receiver_id}}"><?php esc_html_e('Send', 'workreap'); ?></a>
	</div>
</div>
</script>
<script type="text/template" id="tmpl-load-chat-messagebox">
<# if( !_.isEmpty(data.chat_nodes) ) { #>
<#
_.each( data.chat_nodes , function( element, index ) { 
	var chat_class = 'wt-offerermessage wt-msg-thread';
	if(element.chat_is_sender === 'yes'){
		chat_class = 'wt-memessage wt-readmessage wt-msg-thread';
	}
#>
<div class="{{chat_class}}" data-id="{{element.chat_id}}">
	<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
	<div class="wt-description">
		<# if(element.chat_message) { #>
			<p>{{element.chat_message}}</p>
		<# } else { #>
			<div class="wt-messagesfile">
				<div class="wt-messagescontent">
					<figure class="wt-meassagesfig {{element.chat_filetype}}">
						<img src="<?php echo esc_url(get_template_directory_uri() . '/images/file-ext-sprite.jpg'); ?>">
					</figure>
					<div class="wt-messagesfile__title">
						<# if(element.chat_filename) { #>
							<a href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download>{{element.chat_filename}}</a>
						<# } else { #>
							<a href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download>{{element.chat_filename}}</a>
						<# } #>
						<em>file size: {{element.chat_filesize}}</em>
					</div>
					<# if(element.chat_filename) { #>
						<a class="wt-messagesfile__uploader" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download></a>
					<# } else { #>
						<a class="wt-messagesfile__uploader" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download></a>
					<# } #>
				</div> 
			</div>
		<# } #>
		<div class="clearfix"></div>
		<time datetime="2017-08-08">{{element.chat_date}}</time>
		<div class="clearfix"></div>
		<# if(element.chat_is_sender === 'yes'){ #>
		<!-- <a href="#" onclick="event_preventDefault(event);" class="wt-delete-message" data-id="{{element.chat_id}}" data-user="{{element.chat_current_user_id}}">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a> -->
		<# } #>
	</div>
</div>
<# }); #>
<# } #>
</script>
<script type="text/template" id="tmpl-load-chat-recentmsg-data">
	{{data.desc}}
</script>
<script type="text/template" id="tmpl-load-user-details">
<a href="#" onclick="event_preventDefault(event);" class="wt-back back-chat"><i class="ti-arrow-left"></i></a>
<div class="wt-userlogedin">
	<figure class="wt-userimg">
		<img src="{{data.chat_img}}" alt="{{data.chat_name}}">
	</figure>
	<div class="wt-username">
		<h3>{{data.chat_name}}</h3>
		<a target="_blank" href="{{data.chat_url}}" class="wt-viewprofile"><?php esc_html_e('View profile', 'workreap'); ?></a>
	</div>
</div>
<a href="{{data.chat_url}}" class="wt-viewprofile wt-viewprofile-icon wt-btn"><?php esc_html_e('View profile detail', 'workreap'); ?></i></a>
</script>
<script type="text/template" id="tmpl-load-chat-uploader-progress">
<div class="circle-wrap">
  <div class="circle">
    <div class="mask full">
      <div class="fill"></div>
    </div>
    <div class="mask half">
      <div class="fill"></div>
    </div>
    <div class="inside-circle">
		{{data.file_percent}}%
    </div>
  </div>
</div>
<!-- <div class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated {{data.finish_progress}}" role="progressbar" style="width: {{data.file_percent}}%" aria-valuenow="{{data.file_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
</div> -->
</script>
<script type="text/template" id="tmpl-load-chat-attachment-content">
<# if( !_.isEmpty(data.chat_nodes) ) { #>
<# 
_.each( data.chat_nodes , function( element, index ) { 
	var chat_class = 'wt-offerermessage wt-msg-thread';
	if(element.chat_is_sender === 'yes'){
		chat_class = 'wt-memessage wt-readmessage wt-msg-thread';
	}
#>
<div class="{{chat_class}} msg_content_{{element.chat_id}}" data-id="{{element.chat_id}}">
	<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
	<div class="wt-description">
		<div class="wt-attachment-viewer"></div>
		<div class="wt-messagesfile">
			<div class="wt-messagescontent">
				<figure class="wt-meassagesfig {{element.chat_filetype}}">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/images/file-ext-sprite.jpg'); ?>">
				</figure>
				<div class="wt-messagesfile__title">
					<# if(element.chat_filename) { #>
						<a href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download>{{element.chat_filename}}</a>
					<# } else { #>
						<a href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download>{{element.chat_filename}}</a>
					<# } #>
					<em>file size: {{element.chat_filesize}}</em>
				</div>
				<div class="show_progress"></div>
				<# if(element.chat_filename) { #>
					<a class="wt-messagesfile__uploader" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download></a>
				<# } else { #>
					<a class="wt-messagesfile__uploader" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>{{element.chat_filename}}" download></a>
				<# } #>
			</div> 
		</div>
		<time datetime="2017-08-08">{{element.chat_date}}</time>
		<div class="clearfix"></div>
	</div>
</div>
<# }) #>
<# } #>
</script>
<script type="text/template" id="tmpl-load-chat-attachment-view">
<div class="wt-messageslink">
	<div class="wt-messagescontent">
		<figure class="wt-meassagesfig">
			<# if(data.chat_filename) { #>
				<img src="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>/{{data.chat_filename}}">
			<# } else { #>
				<img src="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>/{{data.name}}">
			<# } #>
			<# if(data.chat_filename) { #>
				<a target="_blank" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>/{{data.chat_filename}}"><i class="ti-share"></i>{{data.name}}</a>
			<# } else { #>
				<a target="_blank" href="<?php echo esc_url($upload_dir_path."/chat_attachments/"); ?>/{{data.name}}"><i class="ti-share"></i>{{data.name}}</a>
			<# } #>
			
		</figure>
	</div>
</div>
</script>