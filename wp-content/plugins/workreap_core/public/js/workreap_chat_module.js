//"use strict";
var chat_interval;
var eonearea;
var eonearea_pop;
var thread_page 	= 0;
var older_more 		= 'yes';
var loader_html 	= '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';

jQuery(document).on('ready', function() {
	var chatloader  		= scripts_vars.chatloader;
	var chatloader_single  	= scripts_vars.chatloader_single;
	var chat_settings   = scripts_vars.chat_settings;
	var chat_page   	= scripts_vars.chat_page;
	var chat_host   	= scripts_vars.chat_host;
	var chat_port   	= scripts_vars.chat_port;

	if( chat_settings === 'chat' ){
		var socket = io.connect(chat_host+":"+chat_port);
		socket.emit('add-user', { userId: parseInt( scripts_vars.current_user ) } );
	}
	
	/* THEME VERTICAL SCROLLBAR */
    jQuery('.wt-listverticalscrollbar').mCustomScrollbar({
		axis:"y",
		autoHideScrollbar: false,
	});

	//message holder
    jQuery(document).on('click', '.wt-ad', function(e){
        jQuery(this).parents('.wt-messages-holder').addClass('wt-openmsg');
    });
	
	//Back click
    jQuery(document).on('click', '.wt-back', function(e){
        jQuery(this).parents('.wt-messages-holder').removeClass('wt-openmsg');
    });
    
	//Apply user filter
	jQuery('.wt-filter-users').on('keyup', function($){
		var content = jQuery(this).val();           
		jQuery(this).parents('li').find('.wt-adcontent h3:contains(' + content + ')').parents('.wt-ad').show();
		jQuery(this).parents('li').find('.wt-adcontent h3:not(:contains(' + content + '))').parents('.wt-ad').hide(); 
	});
	
	// Case insenstive in Contains
	jQuery.expr[":"].contains = jQuery.expr.createPseudo(function(arg) {
		return function( elem ) {
			return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
	});
	
	//Load One to One Chat
    jQuery(document).on('click','.wt-load-chat', function(e){
		e.preventDefault();
		thread_page			= 0;
		older_more 			= 'yes';
        var _this 			= jQuery(this);
        var user_id 		= _this.data('userid');     
        var current_user_id = _this.data('currentid');   
        var msg_id 			= _this.data('msgid');  
        var ischat 			= _this.data('ischat');
		
		var chat_img 			= _this.data('img');
		var chat_url 			= _this.data('url');
		var chat_name 			= _this.data('name');
		thread_page			= thread_page;
		
		//load user info
		var load_message_sidebar = wp.template('load-user-details');
		var chat_user = {chat_img: chat_img,chat_url: chat_url,chat_name: chat_name};       
		load_message_sidebar = load_message_sidebar(chat_user); 
		jQuery('.chat-current-user').html(load_message_sidebar);
		
		jQuery('.load-wt-chat-message').html('');
		jQuery('.load-wt-chat-message').append(chatloader);
		
		if( chat_settings === 'chat' ) {
			socket.emit('add-user', { userId: parseInt( current_user_id ) } );
		}

        //Get chat
	    //var dataString = 'thread_page=' + thread_page + '&user_id=' + user_id + '&current_id=' + current_user_id + '&msg_id=' + msg_id + '&action=fetchUserConversation';
	    jQuery.ajax({
	        type: "POST",
	        url: scripts_vars.ajaxurl,
	        data:  {
				action			: 'fetchUserConversation',
				thread_page		: thread_page,
				user_id			: user_id,
				current_id		: current_user_id,
				msg_id			: msg_id,
				security		: scripts_vars.ajax_nonce
			},
	        dataType: "json",
	        success: function (response) {
				jQuery('.wt-preloader-section').remove();
				_this.addClass('wt-active').siblings().removeClass('wt-active');
				_this.removeClass('wt-dotnotification');

	           if (response.type === 'success') {
				    //Load Reply Box Template
                    var load_reply_box = wp.template('load-chat-replybox');                                  
                    var user_data = {receiver_id: response.chat_receiver_id, sender_data: response.chat_sender};        
                    load_reply_box = load_reply_box(user_data);
				   
                    //Load Messages Template
                    var load_message_temp = wp.template('load-chat-messagebox');
                    var chat_data = {chat_nodes: response.chat_nodes};        
                    load_message_temp = load_message_temp(chat_data); 
                    _this.parents('.wt-offersmessages').find('.load-wt-chat-message').html(load_reply_box);
                    _this.parents('.wt-offersmessages').find('.load-wt-chat-message .wt-messages').append(load_message_temp);
				    refreshScrollBarObject();
					eonearea = jQuery(".reply_msg").emojioneArea();
				    
				    //Socket Upload
				    if( chat_settings === 'chat' ) {
						var chat_uploader = new SocketIOFileUpload(socket);
						socketIOUploader(socket, chat_uploader, chat_settings, chat_page);
					}
	            } else {
	                jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
	            }
	        }
        });


		
	});
	
	//Load One to One Chat
    jQuery(document).on('click','.wt-load-chat-data', function(e){
		e.preventDefault();
		thread_page			= 0;
		older_more 			= 'yes';
        var _this 			= jQuery(this);
        var user_id 		= _this.data('userid');     
        var current_user_id = _this.data('currentid');   
        var msg_id 			= _this.data('msgid');  
        var ischat 			= _this.data('ischat');
		
		var chat_img 			= _this.data('img');
		var chat_url 			= _this.data('url');
		var chat_name 			= _this.data('name');
		thread_page				= thread_page;
		
		//load user info
		jQuery('.load-wt-chat-message').html('');
		jQuery('.load-wt-chat-message').append(chatloader);
		
		jQuery('.wt-load-chat-data').removeClass('wt-active');
		jQuery('tr').removeClass('active-message-row');

	    jQuery.ajax({
	        type: "POST",
			url: scripts_vars.ajaxurl,
			data:  {
				action			: 'fetchUserConversation',
				thread_page		: thread_page,
				user_id			: user_id,
				current_id		: current_user_id,
				msg_id			: msg_id,
				security		: scripts_vars.ajax_nonce
			},
	        dataType: "json",
	        success: function (response) {
				jQuery('.wt-preloader-section').remove();
				_this.addClass('wt-active').siblings().removeClass('wt-active');
				_this.parents('tr').addClass('active-message-row');
				_this.removeClass('wt-dotnotification');

	           if (response.type === 'success') {
				    //Load Reply Box Template
                    var load_reply_box = wp.template('load-chat-replybox');                                  
                    var user_data = {receiver_id: response.chat_receiver_id, sender_data: response.chat_sender};        
                    load_reply_box = load_reply_box(user_data);
				   	
				    var load_message_sidebar = wp.template('load-user-details');
					var chat_user = {sender_image: response.chat_sidebar.avatar,sender_name: response.chat_sidebar.username, receiver_image: response.chat_sidebar_second.avatar,receiver_name: response.chat_sidebar_second.username};       
					load_message_sidebar = load_message_sidebar(chat_user); 
					jQuery('.chat-current-user').html(load_message_sidebar);
				   
                    //Load Messages Template
                    var load_message_temp = wp.template('load-chat-messagebox');
                    var chat_data = {chat_nodes: response.chat_nodes};        
                    load_message_temp = load_message_temp(chat_data); 
                    jQuery('.wt-offersmessages').find('.load-wt-chat-message').html(load_reply_box);
                    jQuery('.wt-offersmessages').find('.load-wt-chat-message .wt-messages').append(load_message_temp);
				    refreshScrollBarObject();
					eonearea = jQuery(".reply_msg").emojioneArea();

	            } else {
					jQuery('.sp-chatspin').remove();
	                jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
	            }
	        }
        });

	});
	
	//delete message
    jQuery(document).on('click','.wt-delete-chat-data', function(e){
		e.preventDefault();
        var _this 	= jQuery(this); 
        var key 	= _this.data('key');   
        var msg_id 	= _this.data('message_id');  
		var userid 	= _this.data('userid');  
		var currentid 	= _this.data('currentid');  
		
		//load user info
		jQuery('.wt-featurescontent').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		jQuery('.wt-chatarea.load-wt-chat-message').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		
        //Get chat
	    var dataString = 'security='+scripts_vars.ajax_nonce+'&key=' + key + '&userid=' + userid + '&currentid=' + currentid + '&msg_id=' + msg_id + '&action=deleteChatMessage';
	    jQuery.ajax({
	        type: "POST",
	        url: scripts_vars.ajaxurl,
	        data: dataString,
	        dataType: "json",
	        success: function (response) {
			   jQuery('.wt-featurescontent').find('.inportusers').remove();
			   jQuery('.wt-chatarea.load-wt-chat-message').find('.inportusers').remove();
	           
			   if (response.type === 'success') {
				    if(key === 'single'){
						_this.parents('tr').fadeTo("slow",0.7, function(){
							_this.parents('tr').remove();
						});
						
						_this.parents('.wt-msg-thread').fadeTo("slow",0.7, function(){
							_this.parents('.wt-msg-thread').remove();
						});
						
					}else if(key === 'conversation'){
						_this.parents('tr').fadeTo("slow",0.7, function(){
							_this.parents('tr').remove();
						});
					} else{
						jQuery('.lx-chatlist').find('tr').remove();
						jQuery('.chat-load-data-wrapper').remove();
						
					}

	            } else {
	                jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
	            }
	        }
        });

	});
	
	//real time chat 
	if( chat_settings === 'chat' ){
		// receiving messages from server and show it to users
		socket.on('send_msg' , function(data){
			var chat_data 			= {chat_nodes: data.chat_nodes};
			var load_message_temp 	= wp.template('load-chat-messagebox');
			load_message_temp 		= load_message_temp(chat_data);
			jQuery(".load-wt-chat-message .mCSB_container").append(load_message_temp);
			refreshScrollBarObject();
		});

		socket.on('send_files' , function(data){
			var chat_data 			= {chat_nodes: data.chat_nodes, attachment_view:data.attachment_view};
			var load_message_temp 	= wp.template('load-chat-attachment-content');
			load_message_temp 		= load_message_temp(chat_data);

			jQuery(".load-wt-chat-message .mCSB_container").append(load_message_temp);
			
			//Load Attachment View
			if(data.mime_type != '' 
				&& data.mime_type == 'wt-jpg' 
				|| data.mime_type == 'wt-png' 
				|| data.mime_type == 'wt-gif') 
			{
				var chat_attachment_view 	= {attachment: data.attachment_view,name: data.chat_nodes[0].chat_filename,chat_hashname:data.chat_nodes[0].chat_hashname};
				var load_attachment_temp 	= wp.template('load-chat-attachment-view');
				load_attachment_temp 		= load_attachment_temp(chat_attachment_view);
				jQuery('.load-wt-chat-message').find('.wt-messages .mCSB_container .msg_content_'+data.last_msg_id+' .wt-attachment-viewer').append(load_attachment_temp);
			}
			refreshScrollBarObject();
		});
		
	}
	
	/* CHATBOX TOGGLE  */
	jQuery('#wt-getsupport').on('click', function(){
		jQuery('.wt-chatbox').slideToggle();
		var _this = jQuery(this);
		refreshScrollBarObject();
		var current_user_id = _this.data('currentid'); 

		socket.emit('add-user', { userId: parseInt( current_user_id ) } );
		
		//Socket Upload
		if( chat_settings === 'chat' ) {
			var chat_uploader = new SocketIOFileUpload(socket);
			socketIOUploader(socket, chat_uploader, chat_settings, chat_page);
		}
	});

	
	//Send User form Deatil Page Chat
    jQuery(document).on('click','.wt-send-single', function (e) {
        e.preventDefault();              
        var _this = jQuery(this);
        var receiver_id   = _this.data('receiver_id');
		var status   	  = _this.data('status');
		var msg_type   	  = _this.data('msgtype');
		var reply_msg 	  = _this.parents('.wt-replaybox').find('textarea.reply_msg').val();    
		
		jQuery('.wt-chatbox').addClass('slighloader');
		jQuery('.wt-chatbox').append(chatloader_single);

        //Send message  
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data:  {
				action	: 'sendUserMessage',
				status		: status,
				msg_type	: msg_type,
				message		: reply_msg,
				receiver_id	: receiver_id,
				security	: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {

				jQuery('.wt-chatbox').removeClass('slighloader');
				if (response.type === 'success') {  
					_this.parents('.wt-replaybox').find('textarea.reply_msg').val('');
					var load_message_temp = wp.template('load-chat-messagebox');
					var chat_data = {chat_nodes: response.chat_nodes};       
					
					load_message_temp = load_message_temp(chat_data); 
					jQuery('.wt-chatbox').find('.wt-messages .mCSB_container').append(load_message_temp); 
					jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id).attr('data-msgid', response.last_id);

					eonearea_pop[0].emojioneArea.setText(''); // clear input
					refreshScrollBarObject();
					
					if( chat_settings === 'chat' ){
						var chat_data = { user_id:receiver_id, chat_nodes: response.chat_nodes_receiver };
						socket.emit('send_msg' , chat_data );
					}
				}else{
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
            }
        });                    
    });

    //Send One to One Chat
    jQuery(document).on('click','.wt-send', function (e) {
        e.preventDefault();              
        var _this = jQuery(this);
        var receiver_id   = _this.data('receiver_id');
		var status   	  = _this.data('status');
		var msg_type   	  = _this.data('msgtype');
		
		var reply_msg 	  = _this.parents('.wt-replaybox').find('textarea.reply_msg').val();    
		
		jQuery('body').append(loader_html);
		
        //Send message  
        _this.parents('.wt-iconbox, .wt-iconboxv').addClass('sp-chatsendspin'); 
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data:  {
				action	: 'sendUserMessage',
				status		: status,
				msg_type	: msg_type,
				message		: reply_msg,
				receiver_id	: receiver_id,
				security	: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				_this.parents('.wt-iconbox, .wt-iconboxv').removeClass('sp-chatsendspin');
				if (response.type === 'success') {  
					_this.parents('.wt-replaybox').find('textarea.reply_msg').val('');
					if(response.msg_type === 'modal'){
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					} else {
						
						var load_message_temp = wp.template('load-chat-messagebox');
						var chat_data = {chat_nodes: response.chat_nodes};       
						
						load_message_temp = load_message_temp(chat_data); 
						jQuery('.load-wt-chat-message').find('.wt-messages .mCSB_container').append(load_message_temp);
						jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id).attr('data-msgid', response.last_id);

						//last message
						var load_message_recent_data_temp = wp.template('load-chat-recentmsg-data');
						var chat_recent_data = {desc:response.replace_recent_msg}
						load_message_recent_data_temp = load_message_recent_data_temp(chat_recent_data);
						jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id+ ' .wt-adcontent .list-last-message').html(load_message_recent_data_temp);
						
						eonearea[0].emojioneArea.setText(''); // clear input 
						refreshScrollBarObject();
						
						if( chat_settings === 'chat' && chat_page === 'yes' ){
							var chat_data = { user_id:receiver_id, chat_nodes: response.chat_nodes_receiver };
							socket.emit('send_msg' , chat_data );
						}
					}
				}else{
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
            }
        });               
    });  

	
    //Delete One to One Chat Message
    jQuery(document).on('click','.wt-delete-message', function (e) {
        e.preventDefault();            
        var _this = jQuery(this);
        var messageId   = _this.data('id');
        var userId      = _this.data('user');

        //Delete message  
        jQuery('body').append(loader_html);       
        var dataString = 'security='+scripts_vars.ajax_nonce+'&msgid=' + messageId + '&user_id=' + userId + '&action=deleteChatMessage';
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            jQuery('.wt-preloader-section').remove();
               if (response.type === 'success') {
                    _this.parents('.wt-msg-thread').remove();                                          
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });                   
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });               
    });
});


//load new message
function loadNewMessage(senderid,receiverid){
	chat_interval 	 = setInterval(function(){
		var msgid  		 = document.getElementById('load-user-chat-'+receiverid);
		var msg_id 		 = msgid.dataset.msgid;
		var SP_Editor 	 = '';
		window.SP_Editor = msg_id;

		var dataString = 'security='+scripts_vars.ajax_nonce+'&sender_id=' + senderid + '&receiver_id=' + receiverid + '&last_msg_id=' + msg_id + '&action=getIntervalChatHistoryData';
		jQuery.ajax({
			type: 'POST',
			url: scripts_vars.ajaxurl,
			processData: false,

			data: dataString,
			dataType: 'json',
			success:function(response){
				if (response.type === 'success') {  
					window.SP_Editor = parseInt( response.last_id );
					var load_message_temp = wp.template('load-chat-messagebox');
					var chat_data = {chat_nodes: response.chat_nodes};        
					load_message_temp = load_message_temp(chat_data); 
					jQuery('.load-wt-chat-message').find('.wt-messages .mCSB_container').html(load_message_temp);
					jQuery('.wt-offersmessages').find('#load-user-chat-'+response.receiver_id).attr('data-msgid', response.last_id);

					//last message
					var load_message_recent_data_temp = wp.template('load-chat-recentmsg-data');
					var chat_recent_data = {desc:response.last_message}
					load_message_recent_data_temp = load_message_recent_data_temp(chat_recent_data);
					jQuery('.wt-offersmessages').find('#load-user-chat-'+response.receiver_id+ ' .wt-adcontent .list-last-message').html(load_message_recent_data_temp);

					refreshScrollBarObject();
				}
			}
		});
	},15000);
}

//init nicescroll       
function refreshScrollBarObject() {
    jQuery('.wt-verticalscrollbar').mCustomScrollbar({
		axis:"y",
		scrollbarPosition: "outside",
		autoHideScrollbar: true,
		scrollTo:'bottom',
		setTop:"9999px",
		callbacks:{
			onTotalScrollBack:function(){ _add_older_messages(this) },
			onTotalScrollBackOffset:100,
			alwaysTriggerOffsets:false
		},
		advanced:{updateOnContentResize:false} //disable auto-updates (optional)
	});
	
	//update
	jQuery('.wt-verticalscrollbar').mCustomScrollbar("update");
	
	jQuery('.wt-msg-thread .wt-description').linkify();
	
	//scroll to bottom
	jQuery('.wt-verticalscrollbar').mCustomScrollbar('scrollTo','bottom');
}

// Load older messages
function _add_older_messages(el){
	if( older_more === 'yes' ){
		thread_page++;
		var _this 				= jQuery('.wt-offersmessages .wt-active');
		var chatloader  		= scripts_vars.chatloader;
		var oldContentHeight	= jQuery(".chat-history-wrap .mCSB_container").innerHeight();

		var user_id 			= _this.data('userid');     
		var current_user_id 	= _this.data('currentid');   
		var msg_id 				= _this.data('msgid');  
		var ischat 				= _this.data('ischat');

		var chat_img 			= _this.data('img');
		var chat_url 			= _this.data('url');
		var chat_name 			= _this.data('name');
		thread_page				= thread_page;
		
		jQuery('.load-wt-chat-message').addClass('slighloader');
		jQuery('.wt-dashboardboxcontent').find('.load-wt-chat-message').append(chatloader);
		jQuery('.wt-chatpopup').find('.load-wt-chat-message').append(scripts_vars.chatloader_single);
		
		//Get chat
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data:  {
				action			: 'fetchUserConversation',
				thread_page		: thread_page,
				user_id			: user_id,
				current_id		: current_user_id,
				msg_id			: msg_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
			   jQuery('.load-wt-chat-message').removeClass('slighloader');
			   jQuery('.sp-chatspin').remove();
			   if (response.type === 'success') {
				   
					//Load Messages Template
					var load_message_temp 	= wp.template('load-chat-messagebox');
					var chat_data 			= {chat_nodes: response.chat_nodes};        
					load_message_temp 		= load_message_temp(chat_data); 
					el.mcs.content.prepend(load_message_temp);
				    
				    jQuery('.wt-msg-thread .wt-description').linkify();
				   
				    var heightDiff	= jQuery(".chat-history-wrap .mCSB_container").innerHeight() - oldContentHeight;
					jQuery(".chat-history-wrap").mCustomScrollbar("update"); //update manually
					jQuery(".chat-history-wrap").mCustomScrollbar("scrollTo","-="+heightDiff,{scrollInertia:0,timeout:0}); //scroll-to
				   
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					older_more  = 'no';
					thread_page = 0;
				}
			}
		});
	}
}

function _chat_bytes_size(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes == 0) return '0 Byte';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function currentEvent(event){
	if(event){
		return event.target.id;
	}
}

function socketIOUploader(socket, chat_uploader, chat_settings, chat_page){
	if(document.querySelector('#chat_file_input')){
		chat_uploader.listenOnInput(document.getElementById("chat_file_input"));
		chat_uploader.maxFileSize = 100000000;
		chat_uploader.resetFileInputs = true;

		chat_uploader.addEventListener("complete", function(event){
			if (event.success === true) {
				var receiver_id = document.getElementById("chat_file_input").getAttribute('data-receiver_id');
				let filename = event.detail.filename+event.detail.fileext;
				if( chat_settings === 'chat' ){
					let prepare_file_data = {
						file_name: filename,
						file_size: _chat_bytes_size(event.file.size),
						file_type: getMimeTypeExtClass(event.file.type)
					};
					jQuery.ajax({
						type: "POST",
						url: scripts_vars.ajaxurl,
						data:  {
							action			: 'sendUserAttachment',
							msg_type		: 'attachment',
							file_info		: prepare_file_data,
							receiver_id		: receiver_id
						},
						dataType: "json",
						success: function (response) {
							if (response.type === 'success') {
								jQuery(".load-wt-chat-message .mCSB_container div.wt-msg-thread").last().removeClass( "msg_content_" ).addClass("msg_content_"+response.last_id);
								jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id).attr('data-msgid', response.last_id);
								
								var load_message_recent_data_temp = wp.template('load-chat-recentmsg-data');
								var chat_recent_data = {desc:response.replace_recent_msg}
								load_message_recent_data_temp = load_message_recent_data_temp(chat_recent_data);
								jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id+ ' .wt-adcontent .list-last-message').html(load_message_recent_data_temp);
								
								//Replace Final Message On File Upload Success
								var chat_data_complete  = { chat_nodes: response.chat_nodes};
								var load_message_temp 	= wp.template('load-chat-attachment-content');
								load_message_temp 		= load_message_temp(chat_data_complete);
								jQuery('.load-wt-chat-message').find('.wt-messages .mCSB_container').append(load_message_temp);
								refreshScrollBarObject();

								//Load Attachment View
								var chat_data = {};
								var chat_data = { user_id:receiver_id, mime_type: response.mime_type, chat_nodes: response.chat_nodes_receiver, last_msg_id: response.last_id, attachment_view: response.chat_attachment };
								socket.emit('send_files' , chat_data );
								
							} else {
								jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
							}
						}
					});
				}
			}
		});

		chat_uploader.addEventListener("error", function(data){
			if (data.code === 1) {
				jQuery.sticky(data.message, {classList: 'important', speed: 200, autoclose: 5000});
			}
		});
	}		
}

/**
 * Return File Extension
 * @param {*} mime_type 
 */
function getMimeTypeExtClass(mime_type){
	if(mime_type != null) {
		switch (mime_type) {
			case 'image/jpeg':
			  	return 'wt-jpg';
			case 'image/png':
			  	return 'wt-png';
			case 'image/gif':
			  	return 'wt-gif';
			case 'video/x-msvideo':
			  	return 'wt-avi';
			case 'video/mpeg':
			  	return 'wt-mpg';
			case 'audio/mpeg':
			  	return 'wt-mp3';
			case 'text/html':
			  	return 'wt-html';
			case 'text/html':
			  	return 'wt-html';
			case 'text/csv':
			  	return 'wt-csv';
			case 'text/plain':
			  	return 'wt-txt';
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			  	return 'wt-xlsx';
			case 'application/vnd.ms-excel':
			  	return 'wt-xls';
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			  	return 'wt-docx';
			case 'application/msword':
			  	return 'wt-doc';
			case 'application/pdf':
			  	return 'wt-pdf';
			default:
				return 'wt-other';
		  }
	}
}