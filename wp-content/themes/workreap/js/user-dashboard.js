"use strict";
jQuery(document).on('ready', function() {
    var skillError      	= scripts_vars.skill_error;
    var deleteMessageError 	= scripts_vars.message_error;
	var emptyspec      		= scripts_vars.specification_value_error;  
	var specError      		= scripts_vars.specification_alert_value_error; 
	var skillError      	= scripts_vars.skill_error;

	var alreadySkill      	= scripts_vars.already_skill_value_error; 
    var uploadSize     		= scripts_vars.data_size_in_kb;
	var featured_skills     = scripts_vars.featured_skills;
	var package_update		= scripts_vars.package_update;
	var job_allowed     	= scripts_vars.job_allowed;
	var jobs_message		= scripts_vars.jobs_message;
    var loader_html 		= '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';
	var required_field      = scripts_vars.required_field;
	var is_rtl  		 	= scripts_vars.is_rtl;
	var calendar_locale  	= scripts_vars.calendar_locale;
	var emptySkill  		= scripts_vars.emptySkill;
	var chat_settings  		= scripts_vars.chat_settings;
	var chat_host   		= scripts_vars.chat_host;
	var chat_port   		= scripts_vars.chat_port;
	var chat_page   		= scripts_vars.chat_page;
	var counter_type   		= scripts_vars.counter_type;
	
	//Disable form for help and support
	jQuery('#search-help-support').on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) { 
		  e.preventDefault();
		  return false;
		}
	});

	//Delete project
    jQuery(document).on('click', '.delete-emp-project', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var _id				= _this.data('id');
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&id='+_id+ '&action=workreap_delete_project';
		
		jQuery.confirm({
            'title': scripts_vars.delete_project,
            'message': scripts_vars.delete_project_desc,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	// Nothing to do in this case. You can as well omit the action property.
                }
            }
        });
	});
	
	//Count limit on tagline, and titles
	if(counter_type.gadget !== 'disable'){
		counter_type	= counter_type;
		if(counter_type.gadget === 'character'){
			var loop	= counter_type.character;
			var limitText	= scripts_vars.characters_limit;
		}else{
			var loop	= counter_type.word;
			var limitText	= scripts_vars.word_limit;
		}

		for(const [key,value] of Object.entries(loop)){
			jQuery('.'+key).textcounter({
				type				  : counter_type.gadget,
				max					  : value,
				countDown			  : true,
				inputErrorClass       : "wt-error",     
				counterErrorClass     : "wt-error",     
				countContainerClass   : "wt-count-wrapper",
				textCountMessageClass : "wt-count-message",
				textCountClass        : "wt-count-character",
				countDownText         : limitText,
				displayErrorText      : true,
			});
		}
	}

	if( chat_settings === 'chat' && chat_page === 'yes' ){
		var socket = io.connect(chat_host+":"+chat_port);
	}
	
	if( calendar_locale  && calendar_locale != null){
		jQuery.datetimepicker.setLocale(calendar_locale);
		moment.locale(calendar_locale);
	}

	//Read notification
    jQuery(document).on('click', '.update-billing', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var _id				= _this.data('id');
		jQuery('body').append(loader_html);
		var _serialized   	= jQuery('.billing-user-form').serialize();
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&id='+_id+'&'+_serialized + '&action=workreap_update_billing';
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//TAGS INPUT
	if(jQuery('.portfolio-tags').length > 0){
		jQuery('.portfolio-tags').select2({
			tags: true,
			minimumResultsForSearch: -1,
			insertTag: function (data, tag) {
				data.push(tag);
			},
			"language": {
			   "noResults": function(){
				   return scripts_vars.nothing;
			   }
		    },
			createTag: function (params) {
				return {
				  id: params.term,
				  text: params.term
				}
			  }
		});
	}
	
	//milestone click
	jQuery(document).on('click', '.wt-milestonesingle__active--title, .wt-milestonesingle__active--date', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		jQuery('.collapse').hide();
		_this.parents().parents().next('.collapse').show();
	});
	
	//numeric field
	jQuery(".wt-numeric").numeric({ decimal : ".",  negative : false });

	//Request for withdraw
    jQuery(document).on('click', '.re-send-email .wt-alertbtn', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&action=workreap_resend_verification';
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//Request for withdraw
    jQuery(document).on('click', '.wt-add-withdraw', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var _serialized   	= jQuery('.wt-withdrawform').serialize();
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&'+_serialized + '&action=workreap_submit_withdraw';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//@Add dispute
    jQuery(document).on('click', '.wt-add-dispute', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var _serialized   	= jQuery('.wt-disputeform').serialize();
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&'+_serialized + '&action=workreap_submit_dispute';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//Show dispute content
	jQuery(document).on('click', '.viewinfo-dispute', function (e) {
        e.preventDefault();
		var _this    		= jQuery(this);
		var _dispute_id		= _this.data('id');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_get_dispute_feedback',
				dispute_id		: _dispute_id,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery("#dispute_contents").html("");
					jQuery("#dispute_contents").html(response.feedback);
					jQuery("#wt-dispute-feedback").modal();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Apply contians filter for Faq
	jQuery('.wt-filter-faqs').on('keyup', function($){
		var content = jQuery(this).val(); 
		jQuery(this).parents('.wt-dashboardbox').find('.faq-search .wt-accordiondetails:contains(' + content + ')').parents('.faq-search').show();
		jQuery(this).parents('.wt-dashboardbox').find('.faq-search .wt-accordiondetails:not(:contains(' + content + '))').parents('.faq-search').hide();           
		
	});
	
	//Responsive table
	jQuery('.wt-tablecategories').basictable({
		breakpoint: 767,
	});
	
	//Apply contians filter for Freelancer Earning
	jQuery('.wt-earning-freelancer').on('keyup', function($){
		var content = jQuery(this).val();
		jQuery('.wt-tablecategories').find('.wt-earning-contents .wt-earnig-single:contains(' + content + ')').parents('.wt-earning-contents').show();
		jQuery('.wt-tablecategories').find('.wt-earning-contents .wt-earnig-single:not(:contains(' + content + '))').parents('.wt-earning-contents').hide();           
		
	});
	
	//toggle dashboard menu
	if(jQuery('#wt-btnmenutoggle').length > 0){
		jQuery("#wt-btnmenutoggle").on('click', function(event) {
			
			event.preventDefault();
			jQuery('#wt-wrapper, body.elementor-header-used').toggleClass('wt-openmenu');
			jQuery('body').toggleClass('wt-noscroll');

			if(jQuery('.wt-navdashboard ul.sub-menu').css('display') === 'block'){
				//do nothing
				if( jQuery('.menu-item-has-children').hasClass('wt-openmenu') ){
					jQuery('.menu-item-has-children').find('.sub-menu').show();
				} else{
					//jQuery('.menu-item-has-children').find('.sub-menu').hide();
				}
			}
		});
	}
	
	//is elementor header
	if(jQuery('.elementor-location-header').length > 0){
		jQuery('body').addClass('elementor-header-used');
	}
	
	

	//widow width
	var _win_width = jQuery(window).width();
	if( _win_width <= 1680 ){
		jQuery('.dashboard-menu-left .menu-item-has-children').removeClass('wt-open');
		jQuery('.dashboard-menu-left .menu-item-has-children').find('.sub-menu').hide();
	}
	
	if( _win_width <= 480 ){
		jQuery(".dashboard-menu-top  > .menu-item-has-children > a").on('click', function(event) {
			event.preventDefault();
			var _this	= jQuery(this);
			jQuery('.menu-item-has-children').find('.sub-menu').hide();
			jQuery('.menu-item-has-children').removeClass('wt-open');
			_this.parents('li').addClass('wt-open');
			_this.next('.sub-menu').show();
		});
	}
	
	/* FIXED SIDEBAR */
	function fixedNav(){			
		$(window).scroll(function () {			
		var $pscroll = $(window).scrollTop();						
			if($pscroll > 76){
			 $('.wt-sidebarwrapper').addClass('wt-fixednav');
			}else{
			 $('.wt-sidebarwrapper').removeClass('wt-fixednav');
			}
		});
	}
	
	fixedNav();
	
	//Change Show Model Change project Status
	jQuery(document).on('click', '#btn-change-status', function (e) {
        e.preventDefault();
		var _project_status		= jQuery("#wt-change-project-status").val();
		if( _project_status	==	'cancelled' ){
			 jQuery("#wt-projectmodalbox-cancelled").modal();
		}else if( _project_status=='completed' ) {
			jQuery("#wt-projectmodalbox-complete").modal();
		}
	});
		
	jQuery(document).on('click', '.wt-payout-settings input[type="radio"]', function (e) {
        //e.preventDefault();
        var _this 		= jQuery(this);
		_this.parents('.wt-payout-settings').find('.fields-wrapper').hide();
		_this.parents('.wt-checkboxholder').next('.fields-wrapper').show();
	});

						
	//@Payout settings
    jQuery(document).on('click', '.wt-payrols-settings', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		jQuery('body').append(loader_html);

		var _serialized   	= jQuery('.wt-payout-settings').serialize();
		var dataString 	  	= 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_payrols_settings';
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//@FAQ
    jQuery(document).on('click', '.faq_submit', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _query_type	= jQuery(".query_type").val();
		var _details	= jQuery(".faq_message").val();
		jQuery('body').append(loader_html);
		
		if( _details == '' || _query_type == '') {
	        jQuery.sticky(required_field, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
			jQuery('body').find('.wt-preloader-section').remove();
	        return false;
	    } else{
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					action		 	: 'workreap_support_faq',
					query_type		: _query_type,
					details 		: _details,
					security		: scripts_vars.ajax_nonce
				},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.wt-preloader-section').remove();
					if (response.type === 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
						window.location.reload();
					} else {
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
					}
				}
			});
		}
		
	});
	
	//Filter search fields
	jQuery.expr[':'].contains = function(a, i, m) {
		return jQuery(a).text().toUpperCase()
		  .indexOf(m[3].toUpperCase()) >= 0;
	};
	
	//@renew package
    jQuery(document).on('click', '.renew-package', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        var _id = _this.data('key');
        var dataString = 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=workreap_update_cart';

        jQuery.confirm({
            'title': scripts_vars.order,
            'message': scripts_vars.order_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
                            type: "POST",
                            url: scripts_vars.ajaxurl,
                            data: dataString,
                            dataType: "json",
                            success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
                                if (response.type === 'success') {
                                    jQuery.sticky(response.message, {classList: 'success',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed });
                                    window.location.replace(response.checkout_url);
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	// Nothing to do in this case. You can as well omit the action property.
                }
            }
        });
    });
	
	//Hire Now
    jQuery(document).on('click', '.hire-now', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
        var _proposal_id   	= _this.data('id');
		var _job_post_id    = _this.data('post-id');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_hire_freelancer',
				job_post_id		: _job_post_id,
				proposal_id 	: _proposal_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else if (response.type === 'checkout') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location.href = response.checkout_url;
				}else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
        
    });
	
	//Remove All Save item
    jQuery(document).on('click', '.wt-clickremoveall', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _post_id    	= _this.data('post-id');
		var _item_type    	= _this.data('itme-type');
		
		$.confirm({
			'title': scripts_vars.remove_itme,
			'message': scripts_vars.remove_itme_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action		 	: 'workreap_remove_save_multipuleitems',
								post_id			: _post_id,
								item_type 		: _item_type,
								security		: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	//Remove Single Save item
    jQuery(document).on('click', '.wt-clickremove', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _post_id    	= _this.data('post-id');
		var _item_type    	= _this.data('itme-type');
		var _item_id    	= _this.data('item-id');
		$.confirm({
			'title': scripts_vars.remove_itme,
			'message': scripts_vars.remove_itme_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action		 	: 'workreap_remove_save_item',
								post_id			: _post_id,
								item_type 		: _item_type,
								item_id 		: _item_id,
								security		: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
	});
	
	//Complete Project with reviews 
	jQuery(document).on('click', '.compelete-btn', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _project_id		= _this.data('project-id');
		var _serialized   	= jQuery('.wt-formfeedback-complete').serialize();
		var dataString 	    = 'security='+scripts_vars.ajax_nonce+'&project_id='+_project_id+'&'+_serialized+'&action=workreap_complete_project';
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location = _string_replace_url(response.url);
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Change milestones by freelancer 
	jQuery(document).on('click', '.cancelled-btn-milestone', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _proposal_id		= _this.data('proposal-id');
        var _contents   	= jQuery('.cancelled-feedback').val();
		
		if( _contents == '' ) {
			 var emptyCancelReason 	= scripts_vars.emptyCancelReason;
	        jQuery.sticky(emptyCancelReason, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 		: 'workreap_cancelled_milestone',
				proposal_id			: _proposal_id ,
				cancelled_reason 	: _contents,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location.reload();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});

	//Change project Status
	jQuery(document).on('click', '.cancelled-btn', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _project_id		= _this.data('project-id');
        var _contents   	= jQuery('.cancelled-feedback').val();
		
		if( _contents == '' ) {
			 var emptyCancelReason 	= scripts_vars.emptyCancelReason;
	        jQuery.sticky(emptyCancelReason, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 		: 'workreap_cancel_project',
				project_id			: _project_id ,
				cancelled_reason 	: _contents,
				security			: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location = _string_replace_url(response.url);
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Cancel job
	jQuery(document).on('click', '.wt-cancel-job', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _project_id		= _this.data('id');
		
		$.confirm({
			'title': scripts_vars.cancel_job,
			'message': scripts_vars.cancel_job_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action		 		: 'workreap_cancel_job',
								project_id			: _project_id,
								security			: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
									window.location = _string_replace_url(response.url);
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});

	});
	
	//Reopen Project
    jQuery(document).on('click', '.project-reopen', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _job_post_id    = _this.data('post-id');
		$.confirm({
			'title': scripts_vars.job_reopen_title,
			'message': scripts_vars.job_reopen_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action		 	: 'workreap_job_reopen',
								project_id		: _job_post_id,
								security		: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	//Download Attachment
    jQuery(document).on('click', '.download-project-attachments', function (e) {
        e.preventDefault();
		var _this    		= jQuery(this);
		var _job_post_id    = _this.data('post-id');
		var _type    = _this.data('type');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 	: 'workreap_download_attachments',
				job_post_id		: _job_post_id,
				type			: _type,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
                    window.location = response.attachment;
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Complete Show Cover Letter
	jQuery(document).on('click', '.covert_letter', function (e) {
        e.preventDefault();
		var _this    		= jQuery(this);
		var _proposal_id	= _this.data('id');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_get_coverletter',
				proposal_id		: _proposal_id,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery("#covertletter_contents").html("");
					jQuery("#covertletter_contents").html(response.contents);
					jQuery("#wt-projectmodalbox-coverletter").modal();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//ADD Class
	jQuery(document).on('click', '.wt-myskills .wt-addinfo', function() {
	    var _this = jQuery(this);
	    _this.addClass('wt-update-info');
	    _this.siblings('a').removeClass('wt-delete-skill');
	    _this.parents('li').addClass('wt-skillsaddinfo');
	});

	//Add skills
	jQuery(document).on('click','.wt-myskills .wt-update-info', function() {
		var _this 	= jQuery(this);
		var type	= _this.data('display_type');
		if(type === 'year'){
			var _val = _this.parents('li').find('.skill-dynamic-field option:selected').val();
		} else {
			var _val = _this.parents('li').find('.skill-dynamic-field input').val();
		}
	    
	    if( _val == '' ) {
	        jQuery.sticky(emptySkill, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
	    if( _val > 100 ){
	        jQuery.sticky(emptySkill, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    } else {
	        _this.parents('li').find('.skill-dynamic-html .skill-val').html(_val);
	        _this.parents('li').removeClass('wt-skillsaddinfo');
	        _this.removeClass('wt-update-info');
	        _this.siblings('a').addClass('wt-delete-skill');
	    }
	});
	
	//Add skill to list
    jQuery(document).on('click', '.wt-add-skill-box', function(){
		var _this		= jQuery(this);
		var type		= _this.data('display_type');
        var skillTitle  = jQuery('.wt-skill-title').val();
        var skill       = jQuery('.wt-skill-val').val();
        var skillText   = jQuery('.wt-skill-title option:selected').text();
		var skillValue  = jQuery('.wt-skill-title option:selected').val();
		var skillcount  = jQuery( ".wt-listskill  .wt-skill-list" ).size();
		var returnKey	= false;
		
		if( skillcount >= featured_skills ){
			jQuery.sticky(package_update, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
                return false;
		}

		jQuery('#skills_sortable li').each( function(index){  
			if (jQuery(this).hasClass('dbskill-'+skillValue)){
				jQuery.sticky(scripts_vars.skill_already_added, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				returnKey	= true;
			}
		});

		if(returnKey === false){
			if( skillTitle !== '' && skillTitle !== 'undefined' && skillTitle !== '0' && skill !== '' && skill !== 'undefined' && skill !== '0' ){
				var counter 	= Math.floor((Math.random() * 999999) + 999);         
				var load_skill 	= wp.template('load-skill');                                  
				var data 		= {counter: counter, value: skill, name: skillTitle, text: skillText};        
				load_skill 		= load_skill(data);             

				jQuery('.wt-listskill ul').append(load_skill);    
				jQuery('.wt-skill-val').val('');
				jQuery('.wt-skill-title').val('');
				jQuery(".wt-skillsform-load-temp .chosen-select").attr("data-placeholder",scripts_vars.select_skills);
				jQuery(".wt-skillsform-load-temp .chosen-select").val('').trigger("chosen:updated");
				

				if(type === 'year'){
					jQuery('#skill-val-'+counter).val(skill).change();
				}
			} else {
				jQuery.sticky(skillError, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				return false;
			}
		}
	});
	
	jQuery(".wt-skill-val, .skill-dynamic-field input[type=number]").on("keypress keyup blur", function() {
		var val = parseInt(this.value);
		if (val < 0) this.value = 0;
    	if (val > 100) this.value = 100;
	});
	
	jQuery(document).on('click', '.wt-create-custom-skills', function(e){
        e.preventDefault();
        var _this	= jQuery(this);

        var custom_skill 	= wp.template('add-skill-custom');                                  
        jQuery('.wt-skillscontent-holder .wt-skillsform-load-temp').append(custom_skill); 
    });

    jQuery(document).on('click', '.wt-create-custom-specialization', function(e){
        e.preventDefault();
        var _this	= jQuery(this);

        var custom_spec 	= wp.template('add-spec-custom');                                  
        jQuery('.wt-skillscontent-holder .wt-specialization-form').append(custom_spec); 
    });

    jQuery(document).on('click', '.wt-add-custom-skill-box', function(){
		var _this	= jQuery(this);
		var type	= _this.data('display_type');
        var skillTitle = jQuery('.wt-custom-skill-title').val();
        var skill      = jQuery('.wt-custom-skill-val').val();
		var skillcount = jQuery( ".wt-listskill  .wt-skill-list" ).size();
		
		if( skillcount >= featured_skills ){
			jQuery.sticky(package_update, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
                return false;
		}
		
        if( skillTitle != '' && skill != '' ){
			
            var counter 	= Math.floor((Math.random() * 999999) + 999);         
            var load_skill 	= wp.template('load-custom-skill');                                  
            var data 		= {counter: counter, value: skill, name: skillTitle, text: skillTitle};        
			load_skill 		= load_skill(data);             
			
            jQuery('.wt-listskill ul').append(load_skill);    
			if(type === 'year'){
				jQuery('#skill-val-'+counter).val(skill).change();
            }
            
            _this.parents('.wt-custom-skillsform').remove();
        } else {
            jQuery.sticky(skillError, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        }
    });
	
	//Add specialization to list
    jQuery(document).on('click', '.wt-add-specialization-box', function(){
		var _this	= jQuery(this);
		var type	= _this.data('display_type');
        var specializationTitle = jQuery('#specialization-dp').val();
		var specText  			= jQuery('#specialization-dp option:selected').text();
		var spec      			= jQuery('.specialization-val').val();
		var matchValue  		= jQuery('.wt-specialization-title option:selected').val();
		var returnKeys		= false;
		
		jQuery('#specializations_sortable li').each( function(index){  
			if (jQuery(this).hasClass('dbskill-'+matchValue)){
				jQuery.sticky(scripts_vars.skill_already_added, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				returnKeys	= true;
			}
		});
		
		
		if(returnKeys === false){
			if( specializationTitle !== '' && specializationTitle !== '0' && specializationTitle !== 'undefined' && spec != '' && spec !== '0' && spec !== 'undefined' ){
				if( spec == '' ){
					jQuery.sticky(emptyspec, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
					return false;
				}

				var counter 	= Math.floor((Math.random() * 999999) + 999);       
				var load_spec 	= wp.template('load-spec');                                  
				var data 		= {counter: counter, value: spec, name: specializationTitle, text: specText};        
				load_spec 		= load_spec(data); 

				jQuery('.wt-myspecifications ul').append(load_spec);    
				jQuery('.specialization-val').val('');

				if(type === 'year'){
					jQuery('#specialization-val-'+counter).val(spec).change();
				}
			} else {
				jQuery.sticky(specError, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				return false;
			}
		}
	});

	//Add Industrial experience to list
    jQuery(document).on('click', '.wt-add-industrial-exp-box', function(){
		var _this	= jQuery(this);
		var type	= _this.data('display_type');

        var industrialTitle = jQuery("#experiences-dp").val();
        var experience      = jQuery(".industrial-val").val();
        var experienceText  = jQuery('#experiences-dp option:selected').text();
		var matchValue  	= jQuery('.wt-experiences-title option:selected').val();
		var returnKeye		= false;
		
		jQuery('#experiences_sortable li').each( function(index){  
			if (jQuery(this).hasClass('dbskill-'+matchValue)){
				jQuery.sticky(scripts_vars.skill_already_added, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				returnKeye	= true;
			}
		});
		
		if(returnKeye === false){
			if( industrialTitle !== '' && industrialTitle !== '0' && industrialTitle !== 'undefined' && experience != '' && experience !== '0' && experience !== 'undefined' ){
				if( experience == '' ){
					jQuery.sticky(scripts_vars.emptyexperience, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
					return false;
				}

				var counter = Math.floor((Math.random() * 999999) + 999);;          
				var load_experience = wp.template('load-industrial_experiences');                                  
				var data = {counter: counter, value: experience, name: industrialTitle, text: experienceText};        
				load_experience = load_experience(data);    
				jQuery('.wt-industrial-exprience ul').append(load_experience);    
				jQuery('.industrial-val').val('');

				if(type === 'year'){
					jQuery('#industrial-val-'+counter).val(experience).change();
				}
			} else {
				jQuery.sticky(specError, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
				return false;
			}
		}
	});
	
	jQuery(document).on('click','.wt-proposal-chat', function (e) {       
		 var _this 			= jQuery(this);
		 var receiver_id   	= _this.data('receiver_id');
		 var status   	  	= _this.data('status');
		 var url   	  		= _this.data('url');
		 var msg_type   	= _this.data('msgtype');
		 var reply_msg 	  	= _this.parents('.wt-formpopup').find('textarea.reply_msg').val(); 
		//Send message  
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data:  {
				action		: 'sendUserMessage',
				status		: status,
				msg_type	: msg_type,
				message		: reply_msg,
				receiver_id	: receiver_id,
				security	: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {  
					if(response.msg_type === 'normals'){
						var load_message_temp = wp.template('load-chat-messagebox');
						var chat_data = {chat_nodes: response.chat_nodes};
						
						load_message_temp = load_message_temp(chat_data); 
					
						jQuery('.load-wt-chat-message').find('.wt-messages').html(load_message_temp);
						jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id).attr('data-msgid', response.last_id);

						//last message
						var load_message_recent_data_temp = wp.template('load-chat-recentmsg-data');
						var chat_recent_data = {desc:response.replace_recent_msg}
						load_message_recent_data_temp = load_message_recent_data_temp(chat_recent_data);
						jQuery('.wt-offersmessages').find('#load-user-chat-'+response.chat_receiver_id+ ' .wt-adcontent .list-last-message').html(load_message_recent_data_temp);
						
						if( chat_settings === 'chat'  && chat_page === 'yes' ){
							var chat_data = { user_id:receiver_id, chat_nodes: response.chat_nodes_receiver };
							socket.emit('send_msg' , chat_data );
						}
						
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
						window.location = url;
					}else{
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
						window.location = url;
					}
				}else{
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});               
	 });
	//send proposal message
	jQuery(document).on('click','.chat-proposal-now', function($){
		var _this = jQuery(this);
		var id		= _this.data("id");
		if (scripts_vars.user_type == 'employer') {
			jQuery("#proposalchatmodal-"+id).modal();
		} else{
			jQuery('.wt-preloader-section').remove();
            jQuery.sticky(scripts_vars.service_access, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
		}
		 
	});

	//Key up skills
	jQuery('.wt-skills .skill-val').on('keydown keyup', function(e){
		if (jQuery(this).val() > 100 
			&& e.keyCode !== 46 // keycode for delete
			&& e.keyCode !== 8 // keycode for backspace
		   ) {
		   e.preventDefault();
		   jQuery(this).val(100);
		}
		
		if (jQuery(this).val() < 0 ) {
		   e.preventDefault();
		   jQuery(this).val(0);
		}
	});

    //Remove skill
    jQuery(document).on('click', '.wt-delete-skill', function(){
        var _this = jQuery(this);
        _this.parents('.wt-skill-list').remove();
    });
	
	//Load Award
    jQuery(document).on('click', '.wt-add-addons', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();          
        var load_addon = wp.template('load-service-addon');                                  
        var data = {counter: counter};        
        load_addon = load_addon(data);   
		jQuery('.wt-addonservices-content ul').prepend(load_addon);
		jQuery(".wt-numeric").numeric({ decimal : ".",  negative : false });
        
    });
	
	jQuery(document).on('click', '.wt-edit-addons', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        _this.parents('li').find('.addon-service-data').toggleClass('elm-display-none');
    });
	
	//Remove addon
	jQuery(document).on('click', '.wt-delete-addon', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        _this.parents('li').remove();
    });
	
	//Load Award
    jQuery(document).on('click', '.wt-add-award', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();          
        var load_award = wp.template('load-award');                                  
        var data = {counter: counter};        
        load_award = load_award(data);                    
        _this.parents('.wt-awardsdataholder').find('.wt-experienceaccordion').append(load_award);   		
		init_image_uploader(counter, 'awards');
        init_datepicker('wt-date-pick');
    });

    //Load Project
    jQuery(document).on('click', '.wt-add-project', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();     
        var load_project = wp.template('load-project');                                  
        var data = {counter: counter};        
        load_project = load_project(data);                    
        _this.parents('.wt-addprojectsholder').find('.wt-experienceaccordion').append(load_project);        
        init_image_uploader(counter, 'project');
    }); 
	
	//downloadable service
    jQuery(document).on('change', '.downloadable-select', function () { 
        var _this = jQuery(this);
        var _val = _this.val();
		if( _val === 'yes' ){
			jQuery('.services-holder-wrap').show();
		} else{
			jQuery('.services-holder-wrap').hide();
		}
    });
	
	jQuery(document).on('click', '.wt-add-files', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();     
        var load_files = wp.template('load-files');   
		console.log(load_files);
        var data = {counter: counter};        
        load_files = load_files(data);                    
        _this.parents('.wt-addprojectsholder').find('.wt-experienceaccordion').append(load_files);        
        init_files_uploader(counter, 'services');
    });


    //Load Experience
    jQuery('.wt-add-experience').on('click', function(e){        
        e.preventDefault();
        var _this = jQuery(this);
        var counter = Math.floor((Math.random() * 99999) + 999);         
        var load_experience = wp.template('load-experience');                                  
        var data = {counter: counter};        
        load_experience = load_experience(data);                    
        _this.parents('.wt-userexperience').find('.wt-experienceaccordion').append(load_experience);
        init_datepicker_max(counter,'wt-start-pick','wt-end-pick');                    
    });
	
	//Load Videos
    jQuery(document).on('click', '.wt-add-video', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();          
        var load_video = wp.template('load-videos');                                  
        var data = {counter: counter};        
        load_video = load_video(data);                    
        _this.parents('.wt-videosdataholder').find('.wt-experienceaccordion').append(load_video);   		
	});

	//Load Videos
    jQuery(document).on('click', '.wt-add-faq', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();          
        var load_faq = wp.template('load-faqs');                                  
        var data = {counter: counter};        
		load_faq = load_faq(data);     
        _this.parents('.wt-faqdataholder').find('.wt-experienceaccordion').append(load_faq);   		
	});
	
	//Load portfolio video template
    jQuery(document).on('click', '.wt-add-portfolio-video', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = get_random_number();          
        var load_video = wp.template('load-portfolio-videos');                                  
        var data = {counter: counter};        
        load_video = load_video(data);                    
        _this.parents('.wt-videosdataholder').find('.wt-experienceaccordion').append(load_video);   		
    });
	
    //Delete Experience
    jQuery(document).on('click', '.wt-delete-data', function(){        
        var _this = jQuery(this);
        _this.parents('li').remove();
    });

    //Load Education
    jQuery('.wt-add-education').on('click', function(e){
        e.preventDefault();
        var _this = jQuery(this);
        var counter = Math.floor((Math.random() * 99999) + 999);;          
        var load_education = wp.template('load-education');                                  
        var data = {counter: counter};        
        load_education = load_education(data);                    
        _this.parents('.wt-userexperience').find('.wt-experienceaccordion').append(load_education);
		init_datepicker_max(counter,'wt-start-pick','wt-end-pick');     
    });    

    //Change profile content
    jQuery(document).on('keyup', '.wt-input-title', function () { 
        var _this = jQuery(this);
        var _text = _this.val();
        _this.parents('li').find('h3 .head-title').text(_text);
    });

    //Change subtitle
    jQuery(document).on('keyup', '.wt-input-subtitle', function () { 
        var _this = jQuery(this);
        var _text = _this.val();
        _this.parents('li').find('h3 .head-sub-title').text(_text);
    });

    //Change section title
    jQuery(document).on('keyup', '.wt-head-input', function () { 
        var _this = jQuery(this);
        var _text = _this.val();
        _this.parents('li').find('span.wt-head-title').text(_text);
    });
    
    //init datepicker
    init_datepicker('wt-date-pick');
	init_datepicker_jobs('wt-date-pick-job');

    //Update freelancer Profile
    jQuery(document).on('click', '.wt-update-profile-freelancer', function (e) {
		e.preventDefault();        
		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = 'nonce='+scripts_vars.ajax_nonce+'&'+jQuery('.wt-user-profile').serialize();
        var dataString 	  = _serialized+'&action=workreap_update_freelancer_profile';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location.reload();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
    });
	
	//Update employer Profile
    jQuery(document).on('click', '.wt-update-profile-employer', function (e) {
        e.preventDefault();        
		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.wt-user-profile').serialize();
        var dataString 	  = 'nonce='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_update_employer_profile';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
    });
	
	//delete profile
    jQuery(document).on('click', '.delete-account', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.delete-user-form').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_delete_account';
		$.confirm({
			'title': scripts_vars.delete_account,
			'message': scripts_vars.delete_account_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location = response.redirect;
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	//update password
    jQuery(document).on('click', '.change-password', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.changepassword-user-form').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_change_user_password';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//update password
    jQuery(document).on('click', '.change-email', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.email-user-form').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_change_user_email';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	jQuery(document).on('click','.wt-save-milstone', function() {        
		var _this    		= jQuery(this);   
		var _id				= _this.data('id'); 
		var _milestone_id	= _this.data('milestone_id');                
        var _serialized   	= _this.parents('.wt-milestone-form').serialize();
		var dataString 	  	= 'security='+scripts_vars.ajax_nonce+'&milestone_id='+_milestone_id+'&id='+_id+'&'+_serialized+'&action=workreap_save_milstone';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	//complete milestone
	jQuery(document).on('click','.wt-milestone-completed', function() {        
		var _this    		= jQuery(this);   
		var _id				= _this.data('id');              
		var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&id='+_id+'&action=workreap_milestone_completed';
		
		$.confirm({
			'title': scripts_vars.milestone_completed,
			'message': scripts_vars.milestone_completed_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});

	jQuery(document).on('click','.wt-pay-milestone', function() {        
		var _this    		= jQuery(this);   
		var _id				= _this.data('id');  
		var _status			= _this.data('status');             
		var dataString 	    = 'security='+scripts_vars.ajax_nonce+'&status='+_status+'&id='+_id+'&action=workreap_milstone_checkout';
		
		$.confirm({
			'title': scripts_vars.milestone_checkout,
			'message': scripts_vars.milestone_checkout_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'checkout') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location = response.checkout_url;
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});

	jQuery(document).on('click','.wt-milstone-request-approved', function() {        
		var _this    		= jQuery(this);   
		var _id				= _this.data('id');  
		var _status			= _this.data('status');             
		var dataString 	  	= 'security='+scripts_vars.ajax_nonce+'&status='+_status+'&id='+_id+'&action=workreap_milstone_request_approved';
		
		$.confirm({
			'title': scripts_vars.milestone_request_approved,
			'message': scripts_vars.milestone_request_approved_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});

	jQuery(document).on('click','.wt-milstone-request', function() {        
		var _this    		= jQuery(this);   
		var _id				= _this.data('id');                 
		var dataString 	    = 'security='+scripts_vars.ajax_nonce+'&id='+_id+'&action=workreap_milstone_request';
		$.confirm({
			'title': scripts_vars.milestone_request,
			'message': scripts_vars.milestone_request_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});

	//Update account settings
    jQuery(document).on('click', '.save-account-settings', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.wt-save-account-settings').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_save_account_settings';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//Add skills
    jQuery(document).on('click','.add-job-skills', function() {
        var _this 	= jQuery(this);
        var _name   = jQuery('.wt-skill-title  option:selected').text();
        var _value  = jQuery('.wt-skill-title').val();
		
		var check_skill	= true;
		jQuery('.jobskills-wrap input').each( function(index){  
			var val = $(this).val();
			if(val == _value ) {
				check_skill	= false;
			}
		});
		
        if( _value == ''){
            jQuery.sticky(emptySkill, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        } else if(check_skill == false) {
			jQuery.sticky(alreadySkill, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
		}else {
            var load_skill  = wp.template('load-job-skill');
            var data        = {name:_name,value:_value};       
            load_skill      = load_skill(data);
            jQuery('.jobskills-wrap').append(load_skill);
        }
    });
	
	
	//Post Job
    jQuery(document).on('click', '.wt-post-job', function (e) {
		e.preventDefault();        
        var _this    = 	jQuery(this);  
		var _id		 = _this.data('id');
		var _type	 = _this.data('type');

		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _serialized   = jQuery('.post-job-form').serialize();
        var dataString 	  = 'nonce='+scripts_vars.ajax_nonce+'&id='+_id+'&submit_type='+_type+'&'+_serialized+'&action=workreap_post_job';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location = _string_replace_url(response.url);
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	
	//Add skills
    jQuery(document).on('change','.wt-job-type', function() {
        var _this 	= jQuery(this);
        var _value  = _this.val();

        if( _value === 'hourly' ){
            jQuery('.job-perhour-input').show();
			jQuery('.job-cost-input').hide();
        } else {
            jQuery('.job-perhour-input').hide();
			jQuery('.job-cost-input').show();
        }
    });
	
	//Job add upload attachment

	var JobUploaderArguments = {
		browse_button: 'job-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-job-container',
		drop_element: 'job-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.job_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mov,ai,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,psd,dwg,indd,txt,eps,prproj,aep"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var JobUploader = new plupload.Uploader(JobUploaderArguments);
	JobUploader.init();

	//bind
	JobUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb = wp.template('load-job-attachments');
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  = load_thumb(data);
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		JobUploader.start();
	});

	//bind
	JobUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	JobUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	JobUploader.bind('FileUploaded', function (up, file, ajax_response) {

		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Project chat files

	var projectChatUploaderArguments = {
		browse_button: 'project-chat-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-project-container',
		drop_element: 'project-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.job_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mov,ai,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,psd,dwg,indd,txt,eps,prproj,aep"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var ProjectChatUploader = new plupload.Uploader(projectChatUploaderArguments);
	ProjectChatUploader.init();

	//bind
	ProjectChatUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb = wp.template('load-project-chat-attachments');
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  = load_thumb(data);
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		ProjectChatUploader.start();
	});

	//bind
	ProjectChatUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	ProjectChatUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	ProjectChatUploader.bind('FileUploaded', function (up, file, ajax_response) {

		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Submit Project Chat
	if( chat_settings === 'chat'  && chat_page === 'yes' ){
		socket.emit('add-user', { userId: parseInt( scripts_vars.current_user ) } );
	}
	
	jQuery(document).on('click', '.wt-submit-project-chat', function(e){
		e.preventDefault();        
		
		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _this    	  = jQuery(this);                    
        var _id      	  = _this.data('id');
        var _serialized   = jQuery('.wt-project-chat-form').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+ '&id='+_id+'&action=workreap_submit_project_chat';
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {

					var counter = get_random_number();
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					var load_chat = wp.template('load-project-chat');
					var data = {user_id:parseInt( response.receiver_id ), message:response.content_message,is_files:response.is_files, counter:counter, comment_id:response.comment_id, img: response.img, name: response.user_name, date: response.date};       
					load_chat = load_chat(data);
            		jQuery('#accordion').append(load_chat);
            		jQuery('.wt-project-chat-form').get(0).reset();
					jQuery('.wt-project-chat-form ul li').remove();

					if( chat_settings === 'chat'  && chat_page === 'yes' ){
						socket.emit('send_history_msg' , data );
					}{
						window.location.reload();
					}
					
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});

	
	//Chat history messages
	if( chat_settings === 'chat' && chat_page === 'yes' ){
		socket.on('send_history_msg' , function(data){
			var history_data = {message: data.message,is_files: data.is_files, counter:data.counter, comment_id:data.comment_id, img: data.img, name: data.name, date: data.date};
			var load_chat = wp.template('load-project-chat');
			load_chat = load_chat(history_data);
			jQuery('#accordion').append(load_chat);

		});
	}
	
	//Resume files

	var resumeUploaderArguments = {
		browse_button: 'resume-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-resume-container',
		drop_element: 'resume-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: false,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.job_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mov,ai,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,psd,dwg,indd,txt,eps,prproj,aep"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var ResumeUploader = new plupload.Uploader(resumeUploaderArguments);
	ResumeUploader.init();

	//bind
	ResumeUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb = wp.template('load-resume-attachments');
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  = load_thumb(data);
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formresumeinfo .uploaded-placeholder').html(_Thumb);
		jQuery('.wt-formresumeinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		ResumeUploader.start();
	});

	//bind
	ResumeUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	ResumeUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	ResumeUploader.bind('FileUploaded', function (up, file, ajax_response) {

		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});
	
	//Identity Verification

	var IdentityUploaderArguments = {
		browse_button: 'identity-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-identity-container',
		drop_element: 'identity-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.job_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mov,ai,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,psd,dwg,indd,txt,eps,prproj,aep"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var IdentityUploader = new plupload.Uploader(IdentityUploaderArguments);
	IdentityUploader.init();

	//bind
	IdentityUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb = wp.template('load-identity-attachments');
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  = load_thumb(data);
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formidentityinfo .uploaded-placeholder').append(_Thumb);
		jQuery('.wt-formidentityinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		IdentityUploader.start();
	});

	//bind
	IdentityUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	IdentityUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	IdentityUploader.bind('FileUploaded', function (up, file, ajax_response) {

		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});
	
	jQuery(document).on('click', '.wt-save-identity', function (e) {
        e.preventDefault();        
        var _this    = 	jQuery(this);  
        var _serialized   = jQuery('.post-identity-form').serialize();
		var dataString 		= 'security='+scripts_vars.ajax_nonce+'&'+_serialized + '&action=workreap_send_verification_request';
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	jQuery(document).on('click', '.wt-cancel-identity', function (e) {
        e.preventDefault();        
        var _this    	  = 	jQuery(this);  

		$.confirm({
			'title': scripts_vars.cancel_verification,
			'message': scripts_vars.cancel_verification_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action		: 'workreap_cancel_verification_request',
								security	: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.wt-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});

	});
	
	//Service add upload attachment

	var ServiceUploaderArguments = {
		browse_button: 'service-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-service-container',
		drop_element: 'service-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.job_attachments, extensions: "jpg,jpeg,gif,png"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};
	
	var ServiceUploader = new plupload.Uploader(ServiceUploaderArguments);
	ServiceUploader.init();

	//bind
	ServiceUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb 	= wp.template('load-service-attachments');
			var _size 		= bytesToSize(file.size);
            var data 		= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  	= load_thumb(data);
			
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		ServiceUploader.start();
	});

	//bind
	ServiceUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	ServiceUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	ServiceUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Portfolio add upload attachment
	var PortfolioUploaderArguments = {
		browse_button: 'portfolio-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-portfolio-container',
		drop_element: 'portfolio-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.portfolio_attachments, extensions: "jpg,jpeg,gif,png"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};
	
	var PortfolioUploader = new plupload.Uploader(PortfolioUploaderArguments);
	PortfolioUploader.init();

	//bind
	PortfolioUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb 	= wp.template('load-portfolio-attachments');
			var _size 		= bytesToSize(file.size);
            var data 		= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  	= load_thumb(data);
			
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-placeholder').addClass('wt-infouploading');
		up.refresh();
		PortfolioUploader.start();
	});

	//bind
	PortfolioUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	PortfolioUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	PortfolioUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Portfolio add upload documents
	var PortfolioDocsUploaderArguments = {
		browse_button: 'portfolio-documents-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-portfolio-documents-container',
		drop_element: 'portfolio-documents-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};
	
	var PortfolioDocsUploader = new plupload.Uploader(PortfolioDocsUploaderArguments);
	PortfolioDocsUploader.init();

	//bind
	PortfolioDocsUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb 	= wp.template('load-portfolio-documents');
			var _size 		= bytesToSize(file.size);
            var data 		= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  	= load_thumb(data);
			
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-docs-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-docs-placeholder').addClass('wt-infouploading');
		up.refresh();
		PortfolioDocsUploader.start();
	});

	//bind
	PortfolioDocsUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	PortfolioDocsUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	PortfolioDocsUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Emploeyr add brochures attachemnts
	var EmployerBrochuresUploaderArguments = {
		browse_button: 'employer-brochures-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-employer-brochures-container',
		drop_element: 'employer-brochures-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};
	
	var EmployerBrochuresUploader = new plupload.Uploader(EmployerBrochuresUploaderArguments);
	EmployerBrochuresUploader.init();

	//bind
	EmployerBrochuresUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb 	= wp.template('load-employer-brochures');
			var _size 		= bytesToSize(file.size);
            var data 		= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  	= load_thumb(data);
			
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-brochures-placeholder').append(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-brochures-placeholder').addClass('wt-infouploading');
		up.refresh();
		EmployerBrochuresUploader.start();
	});

	//bind
	EmployerBrochuresUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	EmployerBrochuresUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	EmployerBrochuresUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Portfolio zip uploader
	var PortfolioZipUploaderArguments = {
		browse_button: 'portfolio-zip-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file',
		container: 'wt-portfolio-zip-container',
		drop_element: 'portfolio-zip-drag',
		multipart_params: {
			"chunk" : 0,
			"chunks" : 1
		},
		multi_selection: false,
		url: scripts_vars.ajaxurl + "?action=workreap_articulate_upload_form_data&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.portfolio_attachments, extensions: "rar,zip"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};
	
	var PortfolioZipUploader = new plupload.Uploader(PortfolioZipUploaderArguments);
	PortfolioZipUploader.init();

	//bind
	PortfolioZipUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		plupload.each(files, function (file) {
			var load_thumb 	= wp.template('load-portfolio-zip-attachments');
			var _size 		= bytesToSize(file.size);
            var data 		= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb  	= load_thumb(data);
			
            _Thumb 		+= load_thumb;
		});

		jQuery('.wt-formprojectinfo .uploaded-zip-placeholder').html(_Thumb);
		jQuery('.wt-formprojectinfo .uploaded-zip-placeholder').addClass('wt-infouploading');
		up.refresh();
		PortfolioZipUploader.start();
	});

	//bind
	PortfolioZipUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	PortfolioZipUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	PortfolioZipUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.OK == 1 ) {
			jQuery('#thumb-'+file.id).removeClass('wt-uploading');
			jQuery('#thumb-'+file.id +' .ppt_template').val(JSON.stringify(response));
			jQuery.sticky(response.info, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
		} else {
			if(response.OK == 0) {
				jQuery('#thumb-'+file.id).remove();
			}
			jQuery.sticky(response.info, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});
	
	//Post Service
    jQuery(document).on('click', '.wt-post-service', function (e) {
        e.preventDefault();        
        var _this    = 	jQuery(this);  
		var _id		 = _this.data('id');
		var _type	 = _this.data('type');

		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _serialized   = jQuery('.post-service-form').serialize();
        var dataString 	  = 'nonce='+scripts_vars.ajax_nonce+'&id='+_id+'&submit_type='+_type+'&'+_serialized+'&action=workreap_post_service';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location = _string_replace_url(response.url);
					
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});

	jQuery(document).on('click', '.wt-post-quote', function (e) {
        e.preventDefault();        
        var _this    = 	jQuery(this);  
		var quote    = _this.data('id');
		var _type	 = _this.data('type');

		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _serialized   = jQuery('.post-quote-form').serialize();
        var dataString 	  = 'submit_type='+_type+'&id='+quote+'&nonce='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_post_quote';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.href = response.url;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});

	jQuery(document).on('click', '.wt-quote-details', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var description		= _this.data('description');
		
		jQuery("#wt-quote-details").html("");
		jQuery("#wt-quote-details").html(description);
		jQuery("#wt-service-quote").modal();
	});

	//Hire for service quote
    jQuery(document).on('click', '.wt-accept-quote', function (e) {
        e.preventDefault();        
        var _this    	= jQuery(this);
        var quote   	= _this.data('id');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_hire_quote',
				quote			: quote,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.href = response.url;
				} else if (response.type === 'checkout') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location.href = response.checkout_url;
				}else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
        
    });

	//decline quote
	jQuery(document).on('click', '.decline-quote', function (e) {
        e.preventDefault();        
        var _this    	= jQuery(this);
		var quote_id	= jQuery.cookie('quote_id');

		jQuery('body').append(loader_html);

		var _serialized   = jQuery('.decline-quote-form').serialize();
        var dataString 	  = 'quote_id='+quote_id+'&nonce='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=workreap_decline_quote';

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
				}else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });

	//Delete quote by freelancer
	jQuery(document).on('click', '.wt-delete-quote', function (e) {
		e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		
		if( _id == '' ) {
			jQuery.sticky(scripts_vars.someerror, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
		} else {
			$.confirm({
				'title': scripts_vars.delete_quote,
				'message': scripts_vars.delete_quote_message,
				'buttons': {
					'Yes': {
						'class': 'blue',
						'action': function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'workreap_quote_remove',
									id		: _id,
									security		: scripts_vars.ajax_nonce
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.wt-preloader-section').remove();
									if (response.type === 'success') {
										jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
										window.location.reload();
									} else {
										jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
									}
								}
							});
						}
					},
					'No': {
						'class': 'gray',
						'action': function () {
							return false;
						}   // Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		}
    });

	//Quote ID
	jQuery(document).on('click', '.wt-decline-quote', function (e) {
        e.preventDefault();
		var quote_id	= jQuery(this).data('id');
		jQuery.cookie('quote_id', quote_id);
		document.getElementById("empty-reason").value = "";
	});     
	
	//Post Portfolio
    jQuery(document).on('click', '.wt-post-portfolio', function (e) {
        e.preventDefault();        
        var _this    = 	jQuery(this);  
		var _id		 = _this.data('id');
		var _type	= _this.data('type');

		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
		if(jQuery('.porfolio-gallery li').length < 1){
			jQuery.sticky(scripts_vars.portfolio_required, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
			return false;
		}
		
        var _serialized   = jQuery('.post-portfolio-form').serialize();
        var dataString 	  = 'nonce='+scripts_vars.ajax_nonce+'&id='+_id+'&submit_type='+_type+'&'+_serialized+'&action=workreap_add_portfolio';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					window.location.reload();
					
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//Post Addons Service
    jQuery(document).on('click', '.wt-post-addons-service', function (e) {
        e.preventDefault();        
        var _this    = 	jQuery(this);  
		var _id		 = _this.data('id');
		var _type	 = _this.data('type');
				
        var _serialized   = jQuery('.post-service-form').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&id='+_id+'&submit_type='+_type+'&'+_serialized+'&action=workreap_post_addons_service';
		
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					if( _type === 'add' ){
						 window.location = _string_replace_url(response.url);
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					} else{
						window.location = _string_replace_url(response.url);
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
					}
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//@change service status
    jQuery(document).on('click', '.wt-service-status', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		var	_status		= _this.closest('.form-group').find('.wt-select-status :selected').val();
		if( _id == '' ) {
			jQuery.sticky(required_field, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
		} else {
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					action	: 'workreap_service_status',
					id		: _id,
					status	: _status,
					security		: scripts_vars.ajax_nonce
				},	
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.wt-preloader-section').remove();
					if (response.type === 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
						window.location.reload();
					} else {
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
					}
				}
			});
		}
	});
	
	jQuery(document).on('click', '.wt-delete-addon-service', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		if( _id == '' ) {
			jQuery.sticky(required_field, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
		} else {
			$.confirm({
				'title': scripts_vars.delete_service,
				'message': scripts_vars.delete_service_message,
				'buttons': {
					'Yes': {
						'class': 'blue',
						'action': function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'workreap_addons_service_remove',
									id		: _id,
									security		: scripts_vars.ajax_nonce
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.wt-preloader-section').remove();
									if (response.type === 'success') {
										jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
										window.location.reload();
									} else {
										jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
									}
								}
							});
						}
					},
					'No': {
						'class': 'gray',
						'action': function () {
							return false;
						}   // Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		}
	});
	
	//@remove service
    jQuery(document).on('click', '.wt-delete-service', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		if( _id == '' ) {
			jQuery.sticky(required_field, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
		} else {
			$.confirm({
				'title': scripts_vars.delete_service,
				'message': scripts_vars.delete_service_message,
				'buttons': {
					'Yes': {
						'class': 'blue',
						'action': function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'workreap_service_remove',
									id		: _id,
									security		: scripts_vars.ajax_nonce
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.wt-preloader-section').remove();
									if (response.type === 'success') {
										jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
										window.location.reload();
									} else {
										jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
									}
								}
							});
						}
					},
					'No': {
						'class': 'gray',
						'action': function () {
							return false;
						}   // Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		}
	});

	//@remove portfolio
    jQuery(document).on('click', '.wt-delete-portfolio', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		if( _id == '' ) {
			jQuery.sticky(required_field, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
		} else {
			$.confirm({
				'title': scripts_vars.delete_portfolio,
				'message': scripts_vars.delete_portfolio_message,
				'buttons': {
					'Yes': {
						'class': 'blue',
						'action': function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'workreap_portfolio_remove',
									id		: _id,
									security		: scripts_vars.ajax_nonce
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.wt-preloader-section').remove();
									if (response.type === 'success') {
										jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed}); 
										window.location.reload();
									} else {
										jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
									}
								}
							});
						}
					},
					'No': {
						'class': 'gray',
						'action': function () {
							return false;
						}   // Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
		}
	});
	
	//Complete service with reviews 
	jQuery(document).on('click', '.compelete-service-btn', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _service_id		= _this.data('service-id');
		var _serialized   	= jQuery('.wt-formfeedback-complete').serialize();
		var dataString 	    = 'security='+scripts_vars.ajax_nonce+'&service_order_id='+_service_id+'&'+_serialized+'&action=workreap_complete_service_project';
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location = _string_replace_url(response.url);
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Cancelled service
	jQuery(document).on('click', '.cancelled-service-btn', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _service_id		= _this.data('service-id');
        var _contents   	= jQuery('.cancelled-feedback').val();
		
		if( _contents == '' ) {
			 var emptyCancelReason 	= scripts_vars.emptyCancelReason;
	        jQuery.sticky(emptyCancelReason, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 		: 'workreap_service_cancelled',
				service_id			: _service_id ,
				cancelled_reason 	: _contents,
				security			: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					window.location = _string_replace_url(response.url);
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Show service Cancel reason
	jQuery(document).on('click', '.wt-service-reason', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _service_id		= _this.data('id');
		
		if( _service_id == '' ) {
			 var emptyCancelReason 	= scripts_vars.emptyCancelReason;
	        jQuery.sticky(emptyCancelReason, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 	: 'workreap_service_reason',
				service_id		: _service_id,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery("#wt-service-reason-text").html("");
					jQuery("#wt-service-reason-text").html(response.feedback);
					jQuery("#wt-servicemodalbox").modal();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Show service complete rating
	jQuery(document).on('click', '.wt-rating-details', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
		var _service_id		= _this.data('id');
		
		if( _service_id == '' ) {
			 var emptyCancelReason 	= scripts_vars.emptyCancelReason;
	        jQuery.sticky(emptyCancelReason, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	        return false;
	    }
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 	: 'workreap_service_complete_rating',
				service_id		: _service_id,
				security		: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery("#wt-rating-details").html("");
					jQuery("#wt-rating-details").html(response.ratings);
					jQuery("#wt-service-ratings").modal();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
	});
	
	//Download Downloadable Files
	jQuery(document).on('click', '.wt-download-files-doenload', function(e){
		e.preventDefault();	
		var _this = jQuery(this);
		var _id = _this.data('id');
		
		//Send request
		var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&id='+_id+'&action=workreap_download_downloadable_files';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					window.location = response.attachment;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//Download attachments
	jQuery(document).on('click', '.wt-download-attachment', function(e){
		e.preventDefault();	
		var _this = jQuery(this);
		var _comments_id = _this.data('id');
		
		if( _comments_id == '' || _comments_id == 'undefined' || _comments_id == null ){
			jQuery.sticky(scripts_vars.message_error, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
			return false;
		}

		//Send request
		var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&comments_id='+_comments_id+'&action=workreap_download_chat_attachments';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type == 'success') {
					window.location = response.attachment;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});

	//get random ID
	function get_random_number() {
	  function s4() {
	    return Math.floor((1 + Math.random()) * 0x10000)
	      .toString(16)
	      .substring(1);
	  }
	  return s4();
	}

	//Delete Award Image
	jQuery(document).on('click', '.wt-remove-attachment', function (e) {
		e.preventDefault();
		var _this = jQuery(this);
		_this.parents('.wt-doc-parent').remove();
	});    


	//init chosen
	var config = {
			'.chosen-select'           : {rtl:is_rtl,no_results_text:scripts_vars.nothing,placeholder_text_single: scripts_vars.start_typing},
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:scripts_vars.nothing},
			'.chosen-select-width'     : {width:"95%"}
	}

	for (var selector in config) {
		jQuery(selector).chosen(config[selector]);
	}
	
	jQuery('.chosen-search-input').attr('placeholder',scripts_vars.start_typing);
	
	jQuery('.chosen-select').on('change', function(evt, params) {
		jQuery(document).find('.chosen-container').removeClass('workreap-custom-zindex');
	});
	
	//chosen overlap issue
	var zindex = jQuery('.chosen-container').length;
	jQuery('.chosen-container').each(function(i){
		jQuery(this).parents('span.wt-selects').css('z-index', zindex - i);
		jQuery(this).closest('div').addClass('workreap-custom-zindex');
	});
});

//Date picker
function init_datepicker_max(_counter, _start,_end){
    jQuery('.dateinit-'+_counter+' .'+_start).datetimepicker({
		format: scripts_vars.calendar_format,
		datepicker: true,
		maxDate:new Date(),
        timepicker:false,
		dayOfWeekStart:scripts_vars.startweekday,
    });
	
	jQuery('.dateinit-'+_counter+' .'+_end).datetimepicker({
		format: scripts_vars.calendar_format,
        datepicker: true,
		timepicker:false,
		maxDate:new Date(),
		dayOfWeekStart:scripts_vars.startweekday,
        onShow:function( ct ){
		   this.setOptions({
			   minDate: jQuery('.dateinit-'+_counter+' .'+_start).val() ? _change_date_format(  jQuery('.dateinit-'+_counter+' .'+_start).val() ):false
		   })
		  },
    });
}

//Date format
function _change_date_format(dateStr) {
    var calendar_format	= scripts_vars.calendar_format;
	if( calendar_format === 'd-m-Y' || calendar_format === 'd-m-Y H:i:s' ){
		var parts = dateStr.split("-");
		var _date	= parts[2]+'-'+parts[1]+'-'+parts[0];
		return _date;
	} else if( calendar_format === 'd/m/Y' || calendar_format === 'd/m/Y H:i:s' ){
		var parts 	= dateStr.split("/");
		var _date	= parts[2]+'/'+parts[1]+'/'+parts[0];
		return _date;
	} else {
		return dateStr;
	}
}

function init_datepicker(_class){
    jQuery('.'+_class).datetimepicker({
		format: scripts_vars.calendar_format,
        datepicker: true,
        timepicker: false,        
		dayOfWeekStart:scripts_vars.startweekday,
        maxDate: 0,
    });
}

//Sortables
function addSortable(id) {
	var winwidth	= jQuery(window).width();
	if( winwidth > 768 ){
		new Sortable(id, {
			animation: 150,
			handle: ".handle",
			ghostClass: 'blue-background-class'
		});
	}else{
		jQuery('.wt-accordioninnertitle .handle').remove();
	}
	
};

//Date picker
function init_datepicker_jobs(_class){
	var dateToday = new Date();
    jQuery('.'+_class).datetimepicker({
		format: scripts_vars.calendar_format,
        datepicker: true,
        timepicker: false,        
		dayOfWeekStart:scripts_vars.startweekday,
        minDate: dateToday,
    });
}
//Image uploader
function init_image_uploader(current_uploader, current_type) {
	var extensions_extra	= ''
	if( current_type == 'project'){
		extensions_extra	= ',pdf'
	}
    var uploadSize          = scripts_vars.data_size_in_kb;
    var awardImage          = scripts_vars.award_image;
    var award_image_title   = scripts_vars.award_image_title;
    var award_image_size    = scripts_vars.award_image_size;

	var uploaderArguments = {
		browse_button: 'award-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-award-container-' + current_uploader,
		drop_element: 'award-drag-' + current_uploader,
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: false,
		//chunk_size: 100,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: awardImage, extensions: "jpg,jpeg,gif,png,pdf"+extensions_extra}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var ImageUploader = new plupload.Uploader(uploaderArguments);
	ImageUploader.init();

	//bind
	ImageUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		var load_thumb = wp.template('load-image');
		plupload.each(files, function (file) {
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,type:current_type,name:file.name,percentage:file.percent,counter:current_uploader};       
            load_thumb  = load_thumb(data);
            _Thumb 		+= load_thumb;
		});

		jQuery('#wt-award-' + current_uploader + ' .uploaded-placeholder').html(_Thumb);
		jQuery('#award-drag-'+ current_uploader).addClass('wt-infouploading');
		up.refresh();
		ImageUploader.start();
	});
	
	//bind
	ImageUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#wt-award-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
	});

	//Error
	ImageUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			var load_thumb 		= wp.template('load-image');     
			var data 			= {id: file.id,size:response.size,type:current_type,name:file.name,percentage:file.percent,counter:current_uploader,url:response.thumbnail};       
            var load_thumb 		= load_thumb(data);

			jQuery('#wt-award-' + current_uploader + ' .uploaded-placeholder').html(load_thumb);
			
			jQuery("#thumb-" + file.id).removeClass('wt-uploading');
			jQuery('#award-drag-'+ current_uploader).removeClass('wt-infouploading');
			if( file.type == 'application/pdf'){
				response.thumbnail = scripts_vars.defult_pdf;
			}
			jQuery('#wt-award-' + current_uploader + ' .award-thumb').find('img').attr('src', response.thumbnail);
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Delete Award Image
	jQuery(document).on('click', '.wt-remove-award-image', function (e) {
		e.preventDefault();
		var _this = jQuery(this);
        var img   = _this.parents('.wt-experienceaccordion').data('id');
        _this.parents('.wt-placehoder-img').find('.award-thumb img').attr('src', img);
		_this.parents('ul.wt-attachfile').remove();
	});    

}

//Image uploader
function init_image_uploader_v2(current_uploader, current_type,role) {
    var uploadSize          = scripts_vars.data_size_in_kb;
    var awardImage          = scripts_vars.award_image;
    var award_image_title   = scripts_vars.award_image_title;
    var award_image_size    = scripts_vars.award_image_size;


    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'wt-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: false,
        url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: awardImage, extensions: "jpg,jpeg,gif,png"}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";

		if( role == 'employer' ){                
			var load_thumb = wp.template('load-default-employer-image');
		} else {
			var load_thumb = wp.template('load-default-image');
		}
		
        plupload.each(files, function (file) {
			var _size = bytesToSize(file.size);
            var data = {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb = load_thumb(data);
            imageThumb += load_thumb;
        });  


        jQuery('#wt-img-' + current_uploader + ' .uploaded-placeholder').html(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#wt-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        plupload_error_display(err);
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {

        var response = $.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {          
            if( current_type == 'banner' ){                
                var load_thumb = wp.template('load-banner-image');
            } else {
                var load_thumb = wp.template('load-profile-image');
            }

            var counter = current_uploader;        
            var data = {count: counter, name: response.name, url:response.thumbnail, size:response.size};       
            var load_thumb = load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);                    
            jQuery('#image-drag-'+ current_uploader).removeClass('wt-infouploading');
            jQuery('#wt-img-' + current_uploader + ' .img-thumb').find('img').attr('src', response.thumbnail);
        } else {
			jQuery('#thumb-'+file.id).remove();
            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
        }
    });    

    jQuery(document).on('click', '.wt-remove-image', function(){
        var _this = jQuery(this);
        _this.parents('ul').remove();
	});

	jQuery(document).on('click', '.wt-remove-gallery-image', function(){
        var _this = jQuery(this);
        _this.parents('li').remove();
    });
}

//Image uploader
function init_image_uploader_gallery(current_uploader, current_type,role) {
	
    var uploadSize          = scripts_vars.data_size_in_kb;
    var award_image_size    = scripts_vars.award_image_size;


    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'wt-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: true,
        url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: '', extensions: "jpg,jpeg,gif,png"}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";

        plupload.each(files, function (file) {
			var load_thumb = wp.template('load-gallery-image');
			var _size = bytesToSize(file.size);
            var data = {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb = load_thumb(data);
            imageThumb += load_thumb;
        });  


        jQuery('#wt-img-' + current_uploader + ' .wt-galler-images').append(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#wt-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        plupload_error_display(err);
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {

        var response = $.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {          
			if( current_type == 'gallery' ){                
                var load_thumb = wp.template('load-append-gallery-image');
            }

            var counter = current_uploader;        
            var data = {count: counter, name: response.name, url:response.thumbnail, size:response.size};    
			
            var load_thumb = load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);                    
            jQuery('#image-drag-'+ current_uploader).removeClass('wt-infouploading');
            jQuery('#wt-img-' + current_uploader + ' .img-thumb').find('img').attr('src', response.thumbnail);
        } else {
			jQuery('#thumb-'+file.id).remove();
            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
        }
    });    
	
}
//Files uploader
function init_files_uploader(current_uploader, current_type,role) {
	var uploadSize          = scripts_vars.data_size_in_kb;
    var awardImage          = scripts_vars.award_image;
    var award_image_title   = scripts_vars.award_image_title;
    var award_image_size    = scripts_vars.award_image_size;

	var uploaderArguments = {
		browse_button: 'award-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'wt-files-container-' + current_uploader,
		drop_element: 'award-drag-' + current_uploader,
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: false,
		url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				 {title: awardImage, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mov,ai,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,psd,dwg,indd,txt,eps,prproj,aep"}
			],
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var FileUploader = new plupload.Uploader(uploaderArguments);
	FileUploader.init();

	//bind
	FileUploader.bind('FilesAdded', function (up, files) {
		var _Thumb = "";
		
		up.refresh();
		FileUploader.start();
	});
	
	//bind
	FileUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#wt-files-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
	});

	//Error
	FileUploader.bind('Error', function (up, err) {
		plupload_error_display(err);
	});

	//display data
	FileUploader.bind('FileUploaded', function (up, file, ajax_response) {
		var response = $.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			var load_thumb 		= wp.template('load-files');
			var data 			= {id: file.id,size:response.size,type:current_type,name:file.name,percentage:file.percent,counter:current_uploader,url:response.thumbnail};       
            var load_thumb 		= load_thumb(data);
			
			jQuery('#wt-file-url-'+ current_uploader).val(response.thumbnail);
			jQuery('#wt-file-title-'+ current_uploader).val(file.name);
			jQuery('#wt-head-title-'+ current_uploader).html(file.name);
			jQuery('#wt-file-attachment_id-'+ current_uploader).val('');
			
			jQuery("#thumb-" + file.id).removeClass('wt-uploading');
			jQuery('#award-drag-'+ current_uploader).removeClass('wt-infouploading');
		} else {
			jQuery('#thumb-'+file.id).remove();
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
		}
	});

	//Delete Award Image
	jQuery(document).on('click', '.wt-remove-award-image', function (e) {
		e.preventDefault();
		var _this = jQuery(this);
        var img   = _this.parents('.wt-experienceaccordion').data('id');
		_this.parents('ul.wt-attachfile').remove();
	});    
}
