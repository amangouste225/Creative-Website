"use strict";
jQuery(document).ready(function($) {
	var loader_html = '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader">'+localize_vars.spinner+'</div></div></div>'; 
	
	//Resolve Dispute Ajax
	jQuery(document).on('click', '.resolve-dispute-btn', function(event) {
		event.preventDefault();
		var _this = jQuery(this);
		var user_id     			= jQuery("input[name='user_id']:checked").val();
		var freelancer_msg 			= jQuery("#freelancer_msg"). val();
		var employer_msg 			= jQuery("#employer_msg"). val();
		var proj_serv_id			= _this.data('proj-serv-id');
		var dispute_id				= _this.data('dispute-id');
		var freelancer_id			= _this.data('freelancer-id');
		var employer_id				= _this.data('employer-id');
		var dispute_project_id		= _this.data('dispute-project-id');
		var feedback				= jQuery('#fw-option-feedback').val();
		
		jQuery('#TB_ajaxContent').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: {
				freelancer_id: freelancer_id,
				employer_id: employer_id,
				dispute_project_id: dispute_project_id,
				user_id: user_id,
				freelancer_msg: freelancer_msg,
				employer_msg: employer_msg,
				proj_serv_id : proj_serv_id,
				dispute_id : dispute_id,
				feedback : feedback,
				action	   : 'workreap_resolve_dispute',
				security : localize_vars.ajax_nonce
			},
			success: function(response) {
				jQuery('#TB_window').find('.inportusers').remove();
				if((freelancer_msg == '' || employer_msg == '') && response.type == 'error') {
					jQuery.sticky(localize_vars.add_message, {classList: 'danger', speed: 200, autoclose: 5000});
					return false;
				} else if(response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					window.location.reload();
				}
			}
		});
	});

	//collapse 
	jQuery(document).on('click', '.wt-historycontentcol .collapsed .wt-dateandmsg', function(event) {
		event.preventDefault();
		var _this 	= jQuery(this);
		if( _this.next('.wt-historydescription').hasClass('messageactive') ){
			_this.next('.wt-historydescription').removeClass('messageactive').hide();
		} else{
			_this.next('.wt-historydescription').addClass('messageactive').show();
		}
	});

	//Save settings
	jQuery(document).on('click', '.save-data-settings', function(event) {
		event.preventDefault();
		var serialize_data = jQuery('.save-settings-form').serialize();
		var dataString = 'security='+localize_vars.ajax_nonce+'&'+serialize_data + '&action=workreap_save_theme_settings';
		
		var _this = jQuery(this);
		jQuery('.wt-featurescontent').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery('.wt-featurescontent').find('.inportusers').remove();
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});

    });

	//veryfy profiles
	jQuery(document).on('click', '.do_verify_user', function() {
		var _this 		= jQuery(this);
		var _type		= _this.data('type'); 
		
		if( _type === 'reject' ){
			var localize_title = localize_vars.reject_account;
			var localize_vars_message = localize_vars.reject_account_message;
		}else{
			var localize_title = localize_vars.approve_account;
			var localize_vars_message = localize_vars.approve_account_message;
		}
		
		jQuery.confirm({
			title: localize_title,
			content: localize_vars_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			buttons: {
				yes: {
					text: localize_vars.yes,
					action: function () {
						var _id			= _this.data('id'); 
						var _user_id	= _this.data('user_id'); 
						var _type		= _this.data('type'); 
						var dataString = 'security='+localize_vars.ajax_nonce+'&type='+_type+'&id='+_id+'&user_id='+_user_id+'&action=workreap_approve_profile';
						var jc	= this; 
						jc.showLoading();
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('.inportusers').remove();
								if( response.type === 'success' ){
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
									window.location.reload();
								} else{
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
				
						return false;
					}
				},
				no: {
					close: {
						text: localize_vars.close
					}
				}
			}
		});

    });
	
	jQuery(document).on('click', '.do_verify_identity', function() {
		var _this 		= jQuery(this);
		var _type		= _this.data('type'); 
		
		if( _type === 'inprogress' ){
			var localize_title = localize_vars.approve_identity;
			var localize_vars_message = localize_vars.approve_identity_message;

		}else{
			var localize_title = localize_vars.reject_identity;
			var localize_vars_message = localize_vars.reject_identity_message;
		}
		
		var _id			= _this.data('id'); 
		var _user_id	= _this.data('user_id'); 

		jQuery.confirm({
			title: localize_title,
			content: localize_vars_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			onAction: function (btnName) {
				var jc	= this; 
				if(btnName === 'reject'){
					jc.showLoading();
					var formdata =	'<form class="reject-identity-form">' +
										'<div class="form-group jconfirm-buttons">' +
											'<p>'+localize_vars.reason+'</p>' +
											'<textarea class="form-control reason-content" required /></textarea>' +
											'<button type="submit" class="btn btn-red reject-identity">'+localize_vars.reject+'</button>' +
										'</div>' +
									'</form>';
					console.log(formdata);
					this.setContent(formdata);
					this.buttons.accept.hide();
					this.buttons.reject.hide();
					jc.hideLoading();
					
					jQuery(document).on('click', '.reject-identity', function(e) {
						e.preventDefault();
						jc.showLoading();
						var reason	= jQuery('.reason-content').val();
						var dataString  = 'security='+localize_vars.ajax_nonce+'&reason='+reason+'&type=reject&id='+_id+'&user_id='+_user_id+'&action=workreap_identity_verification';

						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jc.hideLoading();
								jc.$content.html(response.message);
								jc.buttons.accept.hide();
								jc.buttons.reject.hide();
								window.location.reload();
							}
						});

						return false;
					});
				}
			},

			buttons: {
				accept: {
					text: localize_vars.accept,
					action: function () {
						var jc	= this; 
						var dataString  = 'security='+localize_vars.ajax_nonce+'&type=approve&id='+_id+'&user_id='+_user_id+'&action=workreap_identity_verification';
						jc.showLoading();
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jc.hideLoading();
								jc.$content.html(response.message);
								jc.buttons.accept.hide();
								jc.buttons.reject.hide();
								window.location.reload();
							}
						});

						return false;
					}
				},
				reject: {
					text: localize_vars.reject,
					action: function () {
						return false;
					}
				},
			},
		});
	});
	
	jQuery(document).on('click', '.do_reject_post', function() {
		var _this 		= jQuery(this);
		var _type		= _this.data('type'); 
		
		if( _type === 'jobs' ){
			var localize_title = localize_vars.reject_job;
			var localize_vars_message = localize_vars.reject_job_message;

		}else{
			var localize_title = localize_vars.reject_service;
			var localize_vars_message = localize_vars.reject_service_message;
		}
		
		var _id		= _this.data('id'); 
		var _post	= _this.data('post'); 

		jQuery.confirm({
			title: localize_title,
			content: localize_vars_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			onAction: function (btnName) {
				var jc	= this; 
				if(btnName === 'reject'){
					jc.showLoading();
					var formdata =	'<form class="reject-identity-form">' +
										'<div class="form-group jconfirm-buttons">' +
											'<p>'+localize_vars.reject_reason_text+'</p>' +
											'<textarea class="form-control reason-content" required /></textarea>' +
											'<button type="submit" class="btn btn-red reject-post">'+localize_vars.reject+'</button>' +
										'</div>' +
									'</form>';
					console.log(formdata);
					this.setContent(formdata);
					this.buttons.no.hide();
					this.buttons.reject.hide();
					jc.hideLoading();
					
					jQuery(document).on('click', '.reject-post', function(e) {
						e.preventDefault();
						jc.showLoading();
						var reason	= jQuery('.reason-content').val();
						var dataString  = 'security='+localize_vars.ajax_nonce+'&reason='+reason+'&type=reject&id='+_id+'&_post='+_post+'&action=workreap_post_verification';

						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jc.hideLoading();
								jc.$content.html(response.message);
								jc.buttons.no.hide();
								jc.buttons.reject.hide();
								window.location.reload();
							}
						});

						return false;
					});
				}
			},

			buttons: {
				reject: {
					text: localize_vars.reject,
					action: function () {
						return false;
					}
				},
				no: {
					close: {
						text: localize_vars.close
					}
				},
			},
		});
    });
	
	//Update withdraw status
	jQuery(document).on('click', '.update-withdraw-status', function() {
		var _this 		= jQuery(this);
		var _status		= _this.data('status'); 		
		var _id			= _this.data('id'); 

		jQuery.confirm({
			title: localize_vars.withdraw_status,
			content: localize_vars.withdraw_status_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			onAction: function (btnName) {
				var jc	= this; 
			},

			buttons: {
				accept: {
					text: localize_vars.withdraw_status,
					action: function () {
						var jc	= this; 
						var dataString  = 'security='+localize_vars.ajax_nonce+'&status='+_status+'&id='+_id+'&action=workreap_update_withdraw_status';
						jc.showLoading();
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 50000});
								window.location.reload();
							}
						});

						return false;
					}
				}
			},
		});
    });
	
	//Show documents
	jQuery(document).on('click', '.do_download_identity', function() {
		var _this 		= jQuery(this);
		var post_id		= _this.data('user'); 
		
		jQuery.confirm({
			title: localize_vars.download,
			content: '',
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			onOpenBefore: function(data, status, xhr){
				var jc	= this; 
				jc.showLoading();
			},
			onContentReady: function () {
				var jc		= this; 
				var html	= ''; 
				console.log(jc);
				
				var dataString = 'security='+localize_vars.ajax_nonce+'&post_id='+post_id+'&action=workreap_view_identity_detail';

				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					dataType:"json",
					data: dataString,
					success: function(response) {
						if( response.type === 'success' ){
							html = response.html;
							console.log(html);
							jc.hideLoading();
							jc.setContent(html);
							
						} else{
							jc.hideLoading();
							jc.setContent(response.message);
						}
						
					}
				});
			},
			buttons: {
				close: {
					text: localize_vars.close
				}
			},
		});
	});
	
	//Approve Project
	jQuery(document).on('click', '.do_approve_post', function() {
		var _this 	= jQuery(this);
		var _post	= _this.data('post'); 
		var _id		= _this.data('id'); 
		var _type	= _this.data('type'); 
		
		if( _type === 'project' ){
			var localize_title = localize_vars.approve_project;
			var localize_vars_message = localize_vars.approve_project_message;
		}else{
			var localize_title = localize_vars.approve_service;
			var localize_vars_message = localize_vars.approve_service_message;
		}
		
		 jQuery.confirm({
			title: localize_title,
			content: localize_vars_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			buttons: {
				yes: {
					text: localize_vars.yes,
					action: function () {
						var jc	= this; 
						jc.showLoading();
						var dataString = 'security='+localize_vars.ajax_nonce+'&type='+_type+'&post_id='+_post+'&id='+_id+'&action=workreap_approve_post';

						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('.inportusers').remove();
								if( response.type === 'success' ){
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
									window.location.reload();
								} else{
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
				
						return false;
					}
				},
				no: {
					close: {
						text: localize_vars.close
					}
				}
			}
		});
    });
	
	//import dummy users
	jQuery(document).on('click', '.doc-import-users', function() {
		 jQuery.confirm({
			title: localize_vars.import,
			content: localize_vars.import_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			buttons: {
				yes: {
					text: localize_vars.yes,
					action: function () {
						var jc	= this; 
						jc.showLoading();
						var dataString = 'security='+localize_vars.ajax_nonce+'&action=workreap_import_users';
						var $this = jQuery(this);
						jQuery('#import-users').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('#import-users').find('.inportusers').remove();
								jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
								window.location.reload();
							}
						});
				
						return false;
					}
				},
				no: {
					close: {
						text: localize_vars.close
					}
				}
			}
		});
	});
	
	//Update mailchimp list
	jQuery(document).on('click', '.wt-latest-mailchimp-list', function(event) {
		event.preventDefault();
		var dataString = 'security='+localize_vars.ajax_nonce+'&action=workreap_mailchimp_array';
		
		var _this = jQuery(this);
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});
	});
	
	//Update mailchimp list
	jQuery(document).on('click', '.wt-update-profile-health', function(event) {
		event.preventDefault();
		var dataString = '&security='+localize_vars.ajax_nonce+'&action=workreap_update_profile_health';
		
		var _this = jQuery(this);
		
		jQuery.confirm({
			title: localize_vars.update_freelaners,
			content: localize_vars.update_freelaners_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return false; 
			},
			closeIcon: 'aRandomButton',
			buttons: {
				yes: {
					text: localize_vars.yes,
					action: function () {
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
					}
				},
				no: {
					close: {
						text: localize_vars.close
					}
				}
			}
		});
	});

	// Verify item purchase
	jQuery(document).on('click', '#workreap_verify_btn', function(e){
		e.preventDefault();
		let _this	= jQuery(this);
		let epv_purchase_code = jQuery('#workreap_purchase_code').val();
		jQuery('.at-content').append('<div class="inportusers">'+localize_vars.spinner+'</div>');

		if(epv_purchase_code == '' || epv_purchase_code == null){
			let epv_purchase_code_title = jQuery('#workreap_purchase_code').attr('title');
			jQuery.sticky(epv_purchase_code_title, {classList: 'important', speed: 200, autoclose: 3000});
			return false;
		} else {
			_this.attr('disabled', 'disabled');
		}

		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				purchase_code:	epv_purchase_code,
				security:	localize_vars.ajax_nonce,
				action:	'workreap_verifypurchase',
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.inportusers').remove();
				if (response.type === 'success') {	
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 3000});
					setTimeout(function(){ 
						window.location.reload();
					}, 2000);
				} else {
					_this.removeAttr("disabled");
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
	});

	//Remove license
	jQuery(document).on('click', '#workreap_remove_license_btn', function(e){
		e.preventDefault();
		let _this	= jQuery(this);
		let epv_purchase_code = jQuery('#workreap_purchase_code').val();

		if(epv_purchase_code == '' || epv_purchase_code == null){
			let epv_purchase_code_title = jQuery('#workreap_purchase_code').attr('title');
			jQuery.sticky(epv_purchase_code_title, {classList: 'important', speed: 200, autoclose: 5000});
			return false;
		} else {
			_this.attr('disabled', 'disabled');
		}

		jQuery('.at-content').append('<div class="inportusers">'+localize_vars.spinner+'</div>');

		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				purchase_code:	epv_purchase_code,
				security:	localize_vars.ajax_nonce,
				action:	'workreap_remove_license',
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.inportusers').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 3000});
					setTimeout(function(){ 
						window.location = response.redirect;
					}, 2000);
				} else {						
					_this.removeAttr("disabled");
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
	});
	
});


/*
Sticky v2.1.2 by Andy Matthews
http://twitter.com/commadelimited

forked from Sticky by Daniel Raftery
http://twitter.com/ThrivingKings
*/
(function($){jQuery.sticky=jQuery.fn.sticky=function(note,options,callback){if(typeof options==='function')callback=options;var hashCode=function(str){var hash=0,i=0,c='',len=str.length;if(len===0)return hash;for(i=0;i<len;i++){c=str.charCodeAt(i);hash=((hash<<5)-hash)+c;hash&=hash}
return's'+Math.abs(hash)},o={position:'top-right',speed:'fast',allowdupes:!0,autoclose:5000,classList:''},uniqID=hashCode(note),display=!0,duplicate=!1,tmpl='<div class="sticky border-POS CLASSLIST" id="ID"><span class="sticky-close"></span><p class="sticky-note">NOTE</p></div>',positions=['top-right','top-center','top-left','bottom-right','bottom-center','bottom-left'];if(options)jQuery.extend(o,options);jQuery('.sticky').each(function(){if(jQuery(this).attr('id')===hashCode(note)){duplicate=!0;if(!o.allowdupes)display=!1}
if(jQuery(this).attr('id')===uniqID)uniqID=hashCode(note)});if(!jQuery('.sticky-queue').length){jQuery('body').append('<div class="sticky-queue '+o.position+'">')}else{jQuery('.sticky-queue').removeClass(positions.join(' ')).addClass(o.position)}
if(display){jQuery('.sticky-queue').prepend(tmpl.replace('POS',o.position).replace('ID',uniqID).replace('NOTE',note).replace('CLASSLIST',o.classList)).find('#'+uniqID).slideDown(o.speed,function(){display=!0;if(callback&&typeof callback==='function'){callback({'id':uniqID,'duplicate':duplicate,'displayed':display})}})}
jQuery('.sticky').ready(function(){if(o.autoclose){jQuery('#'+uniqID).delay(o.autoclose).fadeOut(o.speed,function(){jQuery(this).remove()})}});jQuery('.sticky-close').on('click',function(){jQuery('#'+jQuery(this).parent().attr('id')).dequeue().fadeOut(o.speed,function(){jQuery(this).remove()})})}})(jQuery)