"use strict";
jQuery(document).ready(function(e) {
	//Update avatar meta
	jQuery(document).on('click', '.wt-update-freelancers-meta', function(event) {
		event.preventDefault();
		var _this = jQuery(this);
		_this.next().append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		var dataString = 'security='+localize_vars.ajax_nonce+'&action=workreap_update_freelancers_metadata';
		
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
					jQuery.sticky(response.message, {classList: 'error', speed: 200, autoclose: 5000});
				}
				
			}
		});
	});
	
	
	jQuery(document).on('click', '.wt-payout-settings input[type="radio"]', function (e) {
        var _this 		= jQuery(this);
		_this.parents('.wt-payout-settings').find('.fields-wrapper').hide();
		_this.parents('.wt-checkboxholder').next('.fields-wrapper').show();
	});
	
    //Upload Avatar
    jQuery('#upload-user-avatar').on('click', function() {
        "use strict";
        var $ = jQuery;
        var $this = jQuery(this);
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Add File'
            },
            multiple: false
        })
			.on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				jQuery('#author_profile_avatar').val(attachment.url);
				jQuery('#avatar-src').attr('src', attachment.url);
				jQuery('#avatar-wrap').show();
				$this.parents('tr').next('tr').find('.backgroud-image').show();
				$this.parents('tr').next('tr').attr('class', '');
			}).open();

    });
	
	//Download attachments
	jQuery(document).on('click', '.wt-download-files-doenload', function(e){
		e.preventDefault();	
		var _this = jQuery(this);
		var _id = _this.data('id');
		//Send request
		var dataString 	  = 'security='+localize_vars.ajax_nonce+'&id='+_id+'&action=workreap_download_downloadable_files';
		jQuery.ajax({
			type: "POST",
			url: localize_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				if (response.type === 'success') {
					window.location = response.attachment;
				} else {
					
				}
			}
		});
	});
	
	//Download attachments
	jQuery(document).on('click', '.wt-download-attachment', function(e){
		e.preventDefault();	
		var _this = jQuery(this);
		var _comments_id = _this.data('id');
		//Send request
		var dataString 	  = 'security='+localize_vars.ajax_nonce+'&comments_id='+_comments_id+'&action=workreap_download_chat_attachments';
		jQuery.ajax({
			type: "POST",
			url: localize_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				if (response.type === 'success') {
					window.location = response.attachment;
				} else {
					
				}
			}
		});
	});
		
	//Delete Avatar
	jQuery(document).on('click', '.delete-auhtor-media', function(e) {
        jQuery(this).parents('.backgroud-image').find('img').attr('src', '');
        jQuery(this).parents('tr').prev('tr').find('.media-image').val('');
        jQuery(this).parents('.backgroud-image').hide();
    });

    //Modal box Window
	jQuery('.order-edit-wrap').on('click',".cus-open-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		jQuery(_this.data("target")).show();
		jQuery(_this.data("target")).addClass('in');
		jQuery('body').addClass('cus-modal-open');
	});
	
	jQuery('.order-edit-wrap, .withdrawal').on('click',".cus-close-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		
		jQuery(_this.data("target")).removeClass('in');
		jQuery(_this.data("target")).hide();
		jQuery('body').removeClass('cus-modal-open');
	});
	
    //Woocommerce Package Switcher Code
	if( jQuery( 'body' ).find( '.woocommerce_options_panel' ) ){
		var select_pack	= jQuery('.wt_package_type').val();
		if( select_pack !== null && ( select_pack === 'employer' || select_pack === 'trail_employer') ){
			jQuery('.wt_employer').parents('.form-field').show();
			jQuery('.wt_freelancer').parents('.form-field').hide();
			jQuery('.wt-common-field').parents('.form-field').show();
		}else if( select_pack !== null && ( select_pack === 'freelancer' || select_pack === 'trail_freelancer') ){
			jQuery('.wt_employer').parents('.form-field').hide();
			jQuery('.wt_freelancer').parents('.form-field').show();
			jQuery('.wt-common-field').parents('.form-field').show();
		} else{
			jQuery('.wt-all-field').parents('.form-field').hide();
		}
	}
	
	//Woocommerce Package Switcher type
	jQuery(document).on('change','.wt_package_type', function (e) {
		var _this	= jQuery(this);
		var _current	= _this.val();
		if( _current !== null && ( _current === 'employer' || _current === 'trail_employer' ) ){
			jQuery('.wt_employer').parents('.form-field').show();
			jQuery('.wt_freelancer').parents('.form-field').hide();
			jQuery('.wt-common-field').parents('.form-field').show();
			
		} else if( _current !== null && ( _current === 'freelancer' || _current === 'trail_freelancer') ){
			jQuery('.wt_employer').parents('.form-field').hide();
			jQuery('.wt_freelancer').parents('.form-field').show();
			jQuery('.wt-common-field').parents('.form-field').show();
		} else{
			jQuery('.wt-all-field').parents('.form-field').hide();
		}
	});
});