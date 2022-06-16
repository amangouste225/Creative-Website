"use strict";
var review_pagi		= 2;
var services_pagi	= 2;
var eonearea_pop;
var signin_reset;
var signup_reset;
var forgot_reset;
var password_reset;

var loader_html = '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';

jQuery(document).on('ready', function() {
    var is_loggedin      = scripts_vars.is_loggedin;
	var user_type      	 = scripts_vars.user_type;
    var wishlist_message = scripts_vars.wishlist_message;
    var proposal_message = scripts_vars.proposal_message;
    var proposal_amount  = scripts_vars.proposal_amount;
    var proposal_error   = scripts_vars.proposal_error;
	var proposal_max_val = scripts_vars.proposal_max_val;
    var uploadSize       = scripts_vars.data_size_in_kb;
    var document_title   = scripts_vars.document_title;
	var feature_connects  	= scripts_vars.feature_connects; 
	var connects_pkg  		= scripts_vars.connects_pkg; 
	var hire_service  		= scripts_vars.hire_service; 
	var hire_service_message= scripts_vars.hire_service_message;
	var is_rtl  			= scripts_vars.is_rtl;
	var proposal_price_type = scripts_vars.proposal_price_type;
	var login_register_type = scripts_vars.login_register_type;

    var loader_html = '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';   

	//Click to backtop
	jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();
		if (scroll >= 200) {
			jQuery("body").addClass("click-btnscrolltop");
		}else{
			jQuery("body").removeClass("click-btnscrolltop");
		}
	}); 
	
	var _wt_btnscrolltop = jQuery("#wt-btnscrolltop");
	_wt_btnscrolltop.on('click', function(){
		var _scrollUp = jQuery('html, body');
		_scrollUp.animate({ scrollTop: 0 }, 'slow');
	});
	
	//reset filters
	jQuery(document).on('click', '.clear-this-filters', function (e) {
        var _this 			= jQuery(this);
		var redirect_url	= _this.data('action');
		window.location.replace(redirect_url);
	});
						
	//Format amount in thousand's, comma seperated
	function formatAmount(amount) {
		var formattedAmount = amount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return formattedAmount;
	}
	
	//Read notification
    jQuery(document).on('click', '.viewinfo-notification', function (e) {
        e.preventDefault();
        var _this 			= jQuery(this);
		var _id				= _this.data('id');
		var _counter		= parseInt( jQuery('.notify-counter').html() );
		jQuery('body').append(loader_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action	: 'workreap_read_notification',
				id		: _id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					if(_counter && _counter > 0){
						_counter = _counter - 1;
					}
					
					var noticification_size	= jQuery('.notification-items').size();
					jQuery(".notify-counter").html(_counter);
					jQuery("#wt-notification-detail").html("");
					
					if( noticification_size > 1 ){
						jQuery(".notification-menu").find('#notify-'+_id).remove();
					} else {
						jQuery(".notification-menu").find('#notify-'+_id).html('<h5><span>'+scripts_vars.empty_noticification+'</span></h5>');
						jQuery(".notification-menu").find('#notify-'+_id).removeClass('notification-items');
						jQuery(".notification-menu").find('#notify-'+_id).addClass('wt-notification-empty');
					}
					
					_this.parents('tr').find('span.bt-content .fa-envelope').replaceWith("<i class='fa fa-envelope-open'></i>");
					jQuery("#wt-notification-detail").html(response.content);
					jQuery("#wt-notification-modal").modal();
					jQuery('#wt-notification-detail').linkify();
					
				}
			}
		});
	});
	
	//tabs
	jQuery(document).on('click', '.wt-navarticletab li', function (e) {
		var _this		= jQuery(this);
		jQuery('.portfolio-submit').submit();
	});
	
	jQuery(document).on('click', '.page-template-services-search .wt-job-search .wt-searchgbtn', function (e) {
		e.preventDefault();
		var _this		= jQuery(this);
		_this.parents('.wt-job-search').find('form').submit();
	});
	
	//Dropdown outside click
	jQuery(document).mouseup(function(e){
		var container = jQuery(".wt-radioholder");
		if(!container.is(e.target) && container.has(e.target).length === 0){
			container.hide();
		}
	});
	
	//packages change
	jQuery("#wt-package-switch").on('change', function() {
		var _this = jQuery(this);
		if (_this.is(':checked')) {
		  _this.attr('value', 'employer');
		} else {
		  _this.attr('value', 'freelancer');
		}
		
		var checkboxVal = jQuery('#wt-package-switch').val();

		if( checkboxVal === 'freelancer' ) {
			jQuery(".employer-packages").hide();
			jQuery(".freelancer-packages").show();
		} else {
			jQuery(".freelancer-packages").hide();
			jQuery(".employer-packages").show();
		}
	});

	// Submit home slider v5
	jQuery('.search-form-submit').click(function(){
		jQuery('.search-form').submit();
	})

	setTimeout(function() {
		if(jQuery('#wt-portfolioslider').length > 0){
			portfolioslider();
		}
	}, 1000);
	
	jQuery(".wt-numeric, .ca-maxprice, .ca-minprice, .job-cost-input").numeric({ decimal : ".",  negative : false });
	jQuery(document).on('click', '.wt-filterholder-three a', function (e) {
		var _this		= jQuery(this);
		jQuery('.wt-order-freelancer').val('');
		jQuery('.wt-order-rating').val('');
		jQuery('.search-freelancersform').submit();
	});
	
	jQuery(document).on('click', '.wt-filterholder-three .wt-tag-radiobox input:radio', function (e) {
		var _this		= jQuery(this);
		var class_name	= _this.attr('id');
		var order_val	= _this.val();
		jQuery('.'+class_name).val(order_val);
		jQuery('.'+class_name).closest('form').submit();

	});
	
	jQuery(document).on('click', '.renew-package-shortcode', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        var _id = _this.data('key');
        var dataString 	= 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=workreap_update_cart';

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
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed });
                                    window.location.replace(response.checkout_url);
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
                    }	
                }
            }
        });
    });
	
	//tabs
	jQuery(document).on('click', '.wt-please-login', function (e) {
		e.preventDefault();
		var _this		= jQuery(this);
		jQuery.sticky(scripts_vars.login_first, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
	});
	
	
	//Newsletter form submit 
	jQuery(document).on('click', '.subscribe_me', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+_this.parents('form').serialize() + '&action=workreap_subscribe_mailchimp',
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
	
	//collapse filters
	jQuery(document).on('click', '.wt-collapse-filter .wt-widget+.wt-widget .wt-widgettitle', function (e) {
		var _this 			= jQuery(this);
		_this.parents('.wt-widget').toggleClass('wt-displayfilter');
	});
	
	//Remove menu item
	jQuery('li.hide-post-menu').remove();
	
	//filter dropdown
	jQuery(document).on('click', '.custom-price-edit', function (event) {
		var _this = jQuery(this);
		_this.parents('.wt-effectiveholder').find('.offer-filter').toggle();
	});
	
	
	jQuery('.wt-searchgbtn').on('click', function() {
		var _this = jQuery(this);
		
	});
	
	//geo dropdown
	jQuery('.dc-docsearch').on('click', function() {
		var _this = jQuery(this);
		jQuery('.dynamic-column-holder').toggleClass('dynamic-column-holdershow');
	});

	//filter dropdown
	jQuery('.mmobile-floating-apply,.wt-mobile-close').on('click', function() {
		var _this = jQuery(this);
		_this.parents('aside.wt-sidebar').toggleClass('show-mobile-filter');
	});
	
	jQuery(document).on('click', '.geodistance', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		_this.next('.geodistance_range').toggle();
	});
	
	/* FIXED SIDEBAR */
	jQuery('.wt-btndemotoggle').on('click', function() {
		var _this = jQuery(this);
		_this.parents('.wt-demo-sidebar').toggleClass('wt-demoshow');
	});
	
	if(jQuery('#wt-verticalscrollbar-demos').length > 0){
		jQuery('#wt-verticalscrollbar-demos').mCustomScrollbar({
			axis:"y",
		});
	}
	
	//Toolip init
	function tipso_init(){
		if(jQuery('.wt-tipso').length > 0){
			jQuery('.wt-tipso').tipso({
				tooltipHover	  : true,
				useTitle		  : false,
				background        : scripts_vars.tip_content_bg,
				titleBackground   : scripts_vars.tip_title_bg,
				color             : scripts_vars.tip_content_color,
				titleColor        : scripts_vars.tip_title_color,
			});
		}

		if(jQuery('.hover-tipso-tooltip').length > 0){
			jQuery('.hover-tipso-tooltip').tipso({
				position: 'right',
				background: '#000',
				useTitle: false,
				width: false,
				tooltipHover: true,
				contentElementId: 'sub-menu-items',
				onBeforeShow: function(element,elementData,obj){
					obj.tipso_bubble.addClass('tispo-menu-items-styles')
				}
			});
		}

		if(jQuery('.hover-tipso-portfolio').length > 0){
			jQuery('.hover-tipso-portfolio').tipso({
				position: 'right',
				background: '#000',
				useTitle: false,
				width: false,
				tooltipHover: true,
				contentElementId: 'sub-menu-portfolio',
				onBeforeShow: function(element,elementData,obj){
					obj.tipso_bubble.addClass('tispo-menu-items-styles')
				}
			});
		}

		if(jQuery('.hover-tipso-projects').length > 0){
			jQuery('.hover-tipso-projects').tipso({
				position: 'right',
				background: '#000',
				useTitle: false,
				width: false,
				tooltipHover: true,
				contentElementId: 'sub-menu-projects',
				onBeforeShow: function(element,elementData,obj){
					obj.tipso_bubble.addClass('tispo-menu-items-styles')
				}
			});
		}

		if(jQuery('.hover-tipso-services').length > 0){
			jQuery('.hover-tipso-services').tipso({
				position: 'right',
				background: '#000',
				useTitle: false,
				width: false,
				tooltipHover: true,
				contentElementId: 'sub-menu-services',
				onBeforeShow: function(element,elementData,obj){
					obj.tipso_bubble.addClass('tispo-menu-items-styles')
				}
			});
		}
	}
	
	tipso_init();
	
	if( scripts_vars.sticky_header == 'enable' ){
		var winwidth	= jQuery(window).width();
		if(jQuery('.wt-header').length > 0){
			if( winwidth > 768 ){
				var win = jQuery(window);    
				var header = jQuery(".wt-header");
				var headerOffset = header.offset().top || 0;
				var flag = true;
				var triger_once = true;
				jQuery(window).scroll(function() {
					if (win.scrollTop() > headerOffset) {
						if (flag){
							flag = false;
							jQuery("#wt-wrapper").addClass("wt-sticky");
							jQuery(window).trigger('resize').trigger('scroll');
							setTimeout(
							  function() 
							  {
								jQuery(window).trigger('resize.px.parallax');
							  }, 300);
						}

					} else {
						if (!flag) {
							flag = true;
							jQuery("#wt-wrapper").removeClass("wt-sticky");
							jQuery(window).trigger('resize').trigger('scroll');
							setTimeout(
							  function() 
							  {
								jQuery(window).trigger('resize.px.parallax');
							  }, 300);
						}
					}
				});
			}
		}
	}

	
    //respnsive Search Form
	jQuery(document).on('click','.wt-search-remove', function($){
		var _this = jQuery(this);
		_this.parents('.wt-search-have').removeClass('show-sform');
	});

	jQuery(document).on('click','.wt-respsonsive-search .wt-searchbtn', function($){
		var _this = jQuery(this);
		_this.parents('.wt-search-have').addClass('show-sform');
	});

	//init chosen
	var config = {
			'.chosen-select'           : {rtl:is_rtl,no_results_text:scripts_vars.nothing},
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:scripts_vars.nothing},
			'.chosen-select-width'     : {width:"95%"}
	}

	for (var selector in config) {
		jQuery(selector).chosen(config[selector]);
	}
	
	jQuery('.chosen-search-input').attr('placeholder',scripts_vars.start_typing);
	
	//send offer
	jQuery(document).on('click','.wt-send-offers', function($){
		var _this = jQuery(this);
		
		if (scripts_vars.user_type == 'employer') {
			jQuery("#chatmodal").modal();
		} else{
			jQuery('.wt-preloader-section').remove();
            jQuery.sticky(scripts_vars.service_access, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
		}
		 
	});
	
	//send offer
	jQuery(document).on('click','.wt-send-offer', function (e) {       
		 var _this 			= jQuery(this);
		 var receiver_id   	= _this.data('receiver_id');
		 var status   	  	= _this.data('status');
		 var url   	  		= _this.data('url');
		 var msg_type   	= _this.data('msgtype');
		 var project_id   	= _this.parents('form').find("#project_id :selected").val();
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
				project_id	: project_id,
				security	: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {  
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					_this.parents('form').get(0).reset();
					window.location.reload();
				}else{
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});               
	 });
	
	jQuery(document).on('click', '.wt-addons-checkbox', function (e) {
		var _this    		= jQuery(this);
		var _service_id		= _this.data('service-id');
		
		var _checked_vals = []; 
		
		jQuery("input:checkbox[name=addons]:checked").each(function() { 
			_checked_vals.push($(this).data('addons-id')); 
		});
		//alert( _checked_vals );
		jQuery('body').append(loader_html);
		jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
				action		 		: 'workreap_service_price_update',
				service_id			: _service_id ,
				addons_ids		 	: _checked_vals,
				security			: scripts_vars.ajax_nonce
			},
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
                if (response.type === 'success') {
					jQuery('.wt-ratingtitle h3').html(response.price);
					jQuery('.hire-service').attr('data-addons',_checked_vals);
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
		
	});
	
	//Login to download resume
    jQuery(document).on('click', '.wt-download-login', function (e) {
        e.preventDefault();        
		if (scripts_vars.is_loggedin == 'false') {
            jQuery.sticky(scripts_vars.loggedin_resume, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
		}
	});
		
	//Hire Service Now
    jQuery(document).on('click', '.hire-service', function (e) {
        e.preventDefault();        
        var _this    		= jQuery(this);
        var service_id   	= _this.data('id');
		
		var addons  		= _this.data('addons');
		if (scripts_vars.is_loggedin == 'false') {
			jQuery('.wt-preloader-section').remove();
            jQuery.sticky(scripts_vars.loggedin_message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
			jQuery( ".wt-loginbtn, .wt-loginoptionvtwo a" ).trigger( "click" );
			if( login_register_type === 'pages' ){
				jQuery('html, body').animate({scrollTop:0}, 'slow');
			}
            return false;
        }
		
		if (scripts_vars.user_type == 'freelancer') {
			jQuery('.wt-preloader-section').remove();
            jQuery.sticky(scripts_vars.service_access, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        }

		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_hire_service',
				addons			: addons,
				service_id		: service_id,
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
	
	//Submit load more reviews
	jQuery(document).on('click', '.load-more-reviews', function(e){
		e.preventDefault(); 
        var _this    	  = jQuery(this);  
		var _author_id    = _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_get_more_reviews',
				page			: review_pagi,
				author_id		: _author_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					review_pagi++;
					jQuery('.review-wrap').append(response.reviews);
				} else {
					jQuery('.load-more-reviews').hide();
				}
			}
		});	
	});
	
	//Submit load more reviews
	jQuery(document).on('click', '.wt-more-rating-service', function(e){
		e.preventDefault(); 
        var _this    	  	= jQuery(this);  
		var _service_id    = _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_more_rating_service',
				page			: services_pagi,
				service_id		: _service_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					services_pagi++;
					jQuery('.wt-reviews').append(response.reviews);
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
					jQuery('.wt-more-reviews').hide();
				}
			}
		});	
	});
	
	//Submit load more servics
	jQuery(document).on('click', '.load-more-services', function(e){
		e.preventDefault(); 
        var _this    	= jQuery(this);  
		var _user_id    = _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action		 	: 'workreap_more_service',
				page			: services_pagi,
				user_id			: _user_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					services_pagi++;
					jQuery('.services-wrap').append(response.services);
					jQuery('.wt-freelancers-services-'+response.flag).owlCarousel({
						items: 1,
						loop:true,
						nav:true,
						margin: 0,
						autoplay:false,
						navClass: ['wt-prev', 'wt-next'],
						navContainerClass: 'wt-search-slider-nav',
						navText: ['<span class=\"lnr lnr-chevron-left\"></span>', '<span class=\"lnr lnr-chevron-right\"></span>'],
					});
					
					if( response.show_btn === 'hide' ) {
						jQuery('.more-btn-services').hide();
					}
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
					jQuery('.more-btn-services').hide();
				}
			}
		});	
	});
	
	//MOBILE MENU
	function collapseMenu(){
		jQuery('.wt-navigation ul li.menu-item-has-children, .wt-navigation ul li.page_item_has_children, .wt-navdashboard ul li.menu-item-has-children, .wt-navigation ul li.menu-item-has-mega-menu,.wt-categories-navbar ul li.menu-item-has-children').prepend('<span class="wt-dropdowarrow"><i class="lnr lnr-chevron-right"></i></span>');
		
		jQuery('.wt-navigation ul li.menu-item-has-children span,.wt-navigation ul li.page_item_has_children span,.wt-categories-navbar ul li.menu-item-has-children span').on('click', function() {
			jQuery(this).parent('li').toggleClass('wt-open');
			jQuery(this).next().next().slideToggle(300);
		});
		
		jQuery('.wt-navigation ul li.menu-item-has-children > a, .wt-navigation ul li.page_item_has_children > a,.wt-categories-navbar > ul > li.menu-item-has-children > a').on('click', function() {
			if ( location.href.indexOf("#") != -1 ) {
				jQuery(this).parent('li').toggleClass('wt-open');
				jQuery(this).next().slideToggle(300);
			} else{
				//do nothing
			}
			
		});
		
		jQuery('.wt-navdashboard > ul > li.menu-item-has-children > a, .sp-top-menu .wt-usernav > ul > li.menu-item-has-children span').on('click', function() {
			jQuery(this).parents('li.menu-item-has-children').toggleClass('wt-open');
			jQuery(this).parents('li.menu-item-has-children').find('.sub-menu').slideToggle(300);
		});
		
	}
	collapseMenu();	
	
	//Auto adjust menu item for sub childs
	const loginJs 		= document.querySelector(".wt-userlogedin");
	if ( loginJs === null ){
		for(var i = 1; i<4; i++){
			jQuery('.nav-Js > li:nth-last-child('+i+') ul').css("left", "auto").addClass('menu-item-moved');
			jQuery('.nav-Js > li:nth-last-child('+i+') ul').css("right", "0");
			jQuery('body.rtl .nav-Js > li:nth-last-child('+i+') ul').css("left", "0").addClass('menu-item-moved');
			jQuery('body.rtl .nav-Js > li:nth-last-child('+i+') ul').css("right", "auto");
		} 
		for(var i =1; i<5; i++ ){
			jQuery('.wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("left", "auto").addClass('menu-item-moved');
			jQuery('.wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("right", "100%");
			jQuery('body.rtl .wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, body.rtl .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("right", "auto").addClass('menu-item-moved');
			jQuery('body.rtl .wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, body.rtl .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("left", "100%");
		}
	}else{
	  	for( var i = 1; i<3; i++ ){
	  		jQuery('.wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("left", "auto").addClass('menu-item-moved');

	  		jQuery('.wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("right", "100%");
			  
	  		jQuery('body.rtl .wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, body.rtl .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("left", "100%").addClass('menu-item-moved');
	  		jQuery('body.rtl .wt-navigation > ul > li.menu-item-has-children:nth-last-child('+i+') .sub-menu li .sub-menu, body.rtl .wt-navigation > ul > li.page_item_has_children:nth-last-child('+i+') .children li .children').css("right", "auto");
	  	}
	}
	
	//Filter search fields
	jQuery.expr[':'].contains = function(a, i, m) {
		return jQuery(a).text().toUpperCase()
		  .indexOf(m[3].toUpperCase()) >= 0;
	};

	//Apply contians filter
	jQuery('.wt-filter-field').on('keyup', function($){
		var content = jQuery(this).val();        
		jQuery(this).parents('fieldset').siblings('fieldset').find('.wt-checkbox:contains(' + content + ')').show();
		jQuery(this).parents('fieldset').siblings('fieldset').find('.wt-checkbox:not(:contains(' + content + '))').hide();
	}); 
	
	//read more skills
    jQuery(document).on('click', '.showmore_skills', function (e) {
        e.preventDefault();
		var _this	= jQuery(this);
		var id		= _this.data('id');
		jQuery(_this).hide();
		jQuery('.skills_'+id).css('display','');
    });
	
	//read more crafted projects
    jQuery(document).on('click', '.wt-loadmore-crprojects', function (e) {
        e.preventDefault();
		jQuery('.wt-crprojects').removeClass('d-none');
		jQuery('.wt-loadmore-crprojects').css('display','none');
	});
	
	//load more portfolios
    jQuery(document).on('click', '.wt-loadmore-portfolios', function (e) {
        e.preventDefault();
		jQuery('.wt-portfolios').css('display','inline-block');
		jQuery('.wt-loadmore-portfolios').css('display','none');
    });
	
	//read more crafted videos
    jQuery(document).on('click', '.wt-loadmore-videos', function (e) {
        e.preventDefault();
		jQuery('.wt-video-list').removeClass('d-none');
		jQuery('.wt-loadmore-videos').css('display','none');
    });
	
	//GET QR Code
    jQuery(document).on('click', '.wt-qrcodedetails', function (e) {
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this = jQuery(this);
		var id = _this.data('key'); 
		var type = _this.data('type');  
		var dataString = 'security='+scripts_vars.ajax_nonce+'&key=' + id + '&type=' + type + '&action=workreap_generate_qr_code';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {  
					jQuery('.wt-qrcodedata').attr('src', response.key);
					jQuery('.wt-qrscan figcaption').remove();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//follow employer
    jQuery(document).on('click', '.wt-follow-emp', function (e) {
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this = jQuery(this); 
		var _type = _this.data('type');  
		var _id   = _this.data('id');  
		var _text = _this.data('text');  
		var dataString = 'security='+scripts_vars.ajax_nonce+'&type=' + _type + '&id=' + _id + '&action=workreap_follow_employer';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					_this.removeClass('wt-follow-emp');
					_this.addClass('wt-clicksave');
					_this.find('i').addClass('fa');
					_this.find('span').html(_text);
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//follow freelancer
    jQuery(document).on('click', '.wt-savefreelancer', function (e) {
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this = jQuery(this);  
		var _id   = _this.data('id');  
		var _text = _this.data('text');  
		var _type = _this.data('type');  
		var dataString = 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=workreap_follow_freelancer';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					if ( _type == 'v2' ) {
						_this.removeClass('wt-savefreelancer');
						_this.addClass('wt-liked');
					}
					_this.removeClass('wt-savefreelancer');
					_this.find('span').html(_text);
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//Add to saved projects  
    jQuery(document).on('click', '.wt-add-to-saved_projects', function (e) { 
        e.preventDefault();
		jQuery('body').append(loader_html);
        
		if (scripts_vars.is_loggedin == 'false') {
			jQuery('.wt-preloader-section').remove();
            jQuery.sticky(scripts_vars.wishlist_message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        }
		
        var _this 	= jQuery(this);
        var id 		= _this.data('id') ;    
		var type 	= _this.data('type') ;    
		
        var dataString = 'security='+scripts_vars.ajax_nonce+'&project_id=' + id + '&action=workreap_add_project_to_wishlist';
		
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
               jQuery('.wt-preloader-section').remove();
               if (response.type === 'success') {
                    _this.removeClass('wt-add-to-saved_projects');
					_this.addClass('wt-clicksave');
					if(type == 'v3') {
						_this.removeClass('wt-clicksave');
						_this.addClass('liked');
					}
                    _this.find('em').html( response.text );
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed });                   
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });           
    });
	
	//switch user
    jQuery(document).on('click', '.wt-switch-user', function (e) {
		e.preventDefault();        
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&action=workreap_switch_user_account';
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
					window.location = response.switch_url;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
        
    });
	
	//follow service
    jQuery(document).on('click', '.wt-saveservice', function (e) {
		
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this = jQuery(this);  
		var _id   = _this.data('id');  
		var _text = _this.data('text');  
		var dataString = 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=workreap_follow_service';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					_this.removeClass('wt-saveservice');
					_this.find('i').addClass('fa');
					_this.find('span').html(_text);
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
	});
	
	//follow service listing save item
    jQuery(document).on('click', '.wt-saveservice-v2', function (e) {
		
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this = jQuery(this);  
		var _id   = _this.data('id');  
		var _text = _this.data('text');  
		var dataString = 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=workreap_follow_service';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					_this.removeClass('wt-saveservice-v2');
					_this.addClass('wt-likedv2');
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				}
			}
		});
    });
	
	//report user
    jQuery(document).on('click', '.wt-report-user', function (e) {
        e.preventDefault();
        jQuery('body').append(loader_html);
		var _this 	= jQuery(this); 
		var _type 	= _this.data('type');  
		var _id   	= _this.data('id'); 
		var _form	= _this.parents('.wt-formreport').serialize();
		var dataString = 'security='+scripts_vars.ajax_nonce+'&type=' + _type + '&id=' + _id + '&'+_form+'&action=workreap_report_user';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
					jQuery('.wt-formreport').get(0).reset();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
					if (response.loggin === 'false') {
						jQuery( ".wt-loginbtn, .wt-loginoptionvtwo a" ).trigger( "click" );
						if( login_register_type === 'pages' ){
							jQuery('html, body').animate({scrollTop:0}, 'slow');
						}
					}
				}
			}
		});
    });	
	
	//skill bar
	try {
		jQuery('#wt-ourskill').appear(function () {
			jQuery('.wt-skillholder').each(function () {
				jQuery(this).find('.wt-skillbar').animate({
					width: jQuery(this).attr('data-percent')
				}, 2500);
			});
		});
	} catch (err) {}
	
	
	//Load More Options
	_show_hide_list('.items-more-wrap-sk');
	_show_hide_list('.items-more-wrap-pr');
	_show_hide_list('.items-more-wrap-aw');
	_show_hide_list('.items-more-wrap-ed');
	_show_hide_list('.items-more-wrap-ex');
	var items_wrap = '.subcat-search-wrap';
	function _show_hide_list(items_wrap) {
		var size_li = jQuery(items_wrap + " .data-list .sp-load-item").size();
		var x = 10;
		
		jQuery(items_wrap + ' .data-list .sp-load-item:lt(' + x + ')').show();
		
		jQuery(document).on('click', items_wrap + ' .sp-loadMore', function(){
			x = (x + 10 <= size_li) ? x + 10 : size_li;
			jQuery(items_wrap + ' .data-list .sp-load-item:lt(' + x + ')').addClass('sp-disply');
			jQuery(items_wrap + ' .data-list .sp-load-item:lt(' + x + ')').show();
			var disply_size = jQuery(items_wrap + " .data-list .sp-disply").size();
			if( disply_size >= size_li ) {
				jQuery(items_wrap + " .sp-loadMore").hide();
			}
		});
		
		jQuery(document).on('click',items_wrap + ' sp-showLess', function(){
			x = (x - 10 < 0) ? 10 : x - 10;
			jQuery(items_wrap + ' .data-list .sp-load-item').not(':lt(' + x + ')').hide();
		});
		
		if (size_li <= 10) {
			jQuery(items_wrap + " .sp-loadMore").hide();
		}
	}
	
	//Proposal amount
    jQuery('input.wt-proposal-amount').on('keyup change',function(e){
		e.preventDefault();
		var _this 	= jQuery(this); 
		var TotalAmount = jQuery(this).val();
		var _id   		= _this.data('id'); 
		
		var dataString = 'security='+scripts_vars.ajax_nonce+'&proposed_amount=' + TotalAmount + '&project_id=' + _id + '&action=workreap_update_project_shares';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery( 'em.wt-service-fee' ).text('-' + formatAmount(response.admin_shares)); 
            	jQuery( '.wt-user-amount' ).text(formatAmount(response.freelancer_shares));
				jQuery( 'em.wt-project-proposed' ).text(formatAmount(TotalAmount)); 
			}
		});
		
    });

    //Init Plupupload for proposal documents
    if (jQuery(".wt-formproposal").length) {
		//Job add upload attachment
		
		var ProposalUploaderArguments = {
			browse_button: 'proposal-btn', // this can be an id of a DOM element or the DOM element itself
			file_data_name: 'file_name',
			container: 'wt-proposal-container',
			drop_element: 'proposal-drag',
			multipart_params: {
				"type": "file_name",
			},
			multi_selection: true,
			//chunk_size: 100,
			url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
			filters: {
				mime_types: [
					{title: scripts_vars.proposal_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,html,dwg"}
				],
				max_file_size: 50000000000,
				max_file_count: 1,
				prevent_duplicates: false
			}
		};

		var ProposalUploader = new plupload.Uploader(ProposalUploaderArguments);
		ProposalUploader.init();

		//bind
		ProposalUploader.bind('FilesAdded', function (up, files) {
			var _Thumb = "";

			plupload.each(files, function (file) {
				var load_thumb = wp.template('load-proposal-docs');
				var _size 	= bytesToSize(file.size);
				var data 	= {id: file.id,size:_size,name:file.name,percentage:file.percent};       
				load_thumb  = load_thumb(data);
				_Thumb 		+= load_thumb;
			});

			jQuery('.wt-formprojectinfo .uploaded-placeholder').append(_Thumb);
			jQuery('.wt-formprojectinfo .uploaded-placeholder').addClass('wt-infouploading');
			up.refresh();
			ProposalUploader.start();
		});

		//bind
		ProposalUploader.bind('UploadProgress', function (up, file) {
			var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
			jQuery('.wt-formprojectinfo .uploadprogressbar').replaceWith(_html);
		});

		//Error
		ProposalUploader.bind('Error', function (up, err) {
			plupload_error_display(err);
		});

		//display data
		ProposalUploader.bind('FileUploaded', function (up, file, ajax_response) {

			var response = $.parseJSON(ajax_response.response);
			if ( response.type === 'success' ) {
				jQuery('#thumb-'+file.id).removeClass('wt-uploading');
				jQuery('#thumb-'+file.id +' .attachment_url').val(response.thumbnail);
			} else {
				jQuery('#thumb-'+file.id).remove();
				jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
			}
		});

		//Delete Award Image
		jQuery(document).on('click', '.wt-remove-attachment', function (e) {
			e.preventDefault();
			var _this = jQuery(this);
			_this.parents('.wt-doc-parent').remove();
		});
		
		
        var uploaderDocumentArguments = {
            browse_button: 'wt-upload-doc', 
            file_data_name: 'file_name',
            container: 'wt-attachfile',
            drop_element: 'wt-attachfile',
            multipart_params: {
                "type": "file_name",
            },
            url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&nonce=" + scripts_vars.ajax_nonce,
            filters: {
                mime_types: [
                    {title: document_title, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,html,txt,dwg"}
                ],
                max_file_size: uploadSize,
                prevent_duplicates: false
            }
        };
        
        //uploader init
        var proposalDocumentUploader = new plupload.Uploader(uploaderDocumentArguments);
        proposalDocumentUploader.init();
		
        //Bind
        proposalDocumentUploader.bind('FilesAdded', function (up, files) {
            var _thumb = "";
            plupload.each(files, function (file) {
                _thumb += '<div class="wt-galleryimg ad-item ad-thumb-item" id="thumb-' + file.id + '">' + '' + '</div>';                
            });

            jQuery('.sp-profile-ad-photos .wt-galleryimages').append(_thumb);
            up.refresh();
            proposalDocumentUploader.start();
        });

        //Bind
        proposalDocumentUploader.bind('UploadProgress', function (up, file) {
            jQuery('body').append(loader_html);            
        });    

        //Error
        proposalDocumentUploader.bind('Error', function (up, err) {
            plupload_error_display(err);
        });


        //display data
        proposalDocumentUploader.bind('FileUploaded', function (up, file, ajax_response) {
            jQuery('body').find('.wt-preloader-section').remove();
            var response = $.parseJSON(ajax_response.response);
            if (response.type === 'success') {
                var load_proposal_docs = wp.template('load-proposal-docs');       
                var data = {name: response.name, url:response.thumbnail, size:response.size};        
                load_proposal_docs = load_proposal_docs(data);                      
                jQuery('#wt-attachfile').append(load_proposal_docs);                               
            } else {
                jQuery.sticky(response.message, {classList: 'important',position:'top-right', speed: 200, autoclose: scripts_vars.sticky_speed});
                jQuery("#thumb-" + file.id).remove();
            }
                 
        });
    
    }

	
    //Delete proposal files
    jQuery(document).on('click','.wt-remove-proposal-doc', function(){        
        jQuery(this).parents('.wt-doc-parent').remove();
    });
   
    //Send proposal
    jQuery(document).on('click', '.wt-process-proposal', function (e) {
        var _this    			= jQuery(this);         
        var _id      			= parseInt(_this.data('id'));
        var _post_id 			= parseInt(_this.data('post'));
		var proposalLink 		= jQuery(this).attr('href');
		var max_val				= parseInt(jQuery('.wt-proposal-amount').attr('max'));
		var p_amount			= parseInt(jQuery('.wt-proposal-amount').val());
		
        if (is_loggedin == 'false') {
            jQuery.sticky(proposal_error, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        } 
		
		if(feature_connects === false) {
			jQuery.sticky(connects_pkg, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
		}
		
        if( _id == '' || _id == 0) {
            jQuery.sticky(proposal_message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        }
		
		if( proposal_price_type === 'budget' ){
			if ( p_amount > max_val) {
				jQuery.sticky(proposal_max_val, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
				return false;
			} 
		}

        jQuery('body').append(loader_html);
        var _form   = _this.parents('.wt-send-project-proposal').serialize();
		var dataString = 'security='+scripts_vars.ajax_nonce+'&post_id=' + _post_id + '&id=' + _id + '&'+_form+'&action=workreap_process_project_proposal';  

        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.wt-preloader-section').remove();
				
                if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.sticky_speed});
                    window.location.href = response.return;
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
        });
    });            
	
    //Send proposal error
    jQuery('.wt-submit-proposal').on('click', function(e){
        e.preventDefault();
        var proposalLink = jQuery(this).attr('href');
        if (is_loggedin == 'false') {
            jQuery.sticky(proposal_error, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
            return false;
        } else {
            window.location.href = proposalLink;
        }
    });

	//scrollbar
	if(jQuery('.wt-filterscroll').length > 0){
		jQuery('.wt-filterscroll').mCustomScrollbar({
			axis:"y",
		});
	} 
	/* THEME VERTICAL SCROLLBAR */
	if(jQuery('.wt-verticalscrollbar').length > 0){
		var _wt_verticalscrollbar = jQuery('.wt-verticalscrollbar');
		_wt_verticalscrollbar.mCustomScrollbar({
			axis:"y",
		});
	}
	
	//OPEN CLOSE
	jQuery('#wt-loginbtn, .wt-loginheader a').on('click', function(event){
		event.preventDefault();
		jQuery('.wt-loginarea .wt-loginformhold').slideToggle();
	});
	
	jQuery('.wt-loginheaderclick a').on('click', function(event){
		event.preventDefault();
		jQuery('.wt-loginarea .wt-loginformhold').slideToggle();
		jQuery('html, body').animate({scrollTop:0}, 'slow');
	});
	
	//OPEN CLOSE
	jQuery('.wt-loginfor-offer').on('click', function(event){
		event.preventDefault();
		var _this = jQuery(this);
		
		if(_this.data('url')){
			window.location.replace(_this.data('url'));
		}
		
		jQuery( ".wt-loginoptionvtwo a" ).trigger( "click" );
		jQuery( ".wt-loginoption .wt-loginbtn" ).trigger( "click" );
		if( login_register_type === 'pages' ){
			jQuery('html, body').animate({scrollTop:0}, 'slow');
		}
	});
	
	//OPEN CLOSE
	jQuery('.wt-dropdown').on('click', function(event){
		event.preventDefault();
		var _this = jQuery(this);
		_this.parents('.wt-formbanner').find('.wt-radioholder').slideToggle();
	});	
	
	//DROPDOWN RADIO
	jQuery('input:radio[name="searchtype"]').on('change',function(){
			var _this = jQuery(this);
	        var _type = _this.data('title');
			var _url  = _this.data('url');
			
			jQuery('.wt-formbanner').attr('action', _url);
	        _this.parents('.wt-formbanner').find('.selected-search-type').html(_type);
			_this.parents('.wt-formbanner').find('.wt-radioholder').slideToggle();
			
	    }
    );
	
	//DROPDOWN select
	jQuery('select[name="searchtype"]').on('change',function(){
			var _this = jQuery(this);
			if(_this.val() == 'employer') {
				jQuery('.search-form').find('.wt-pricerange-group').hide();
			} else {
				jQuery('.search-form').find('.wt-pricerange-group').show();
			}
			var _url  = _this.find(':selected').data('url');
			_this.parents('.do-append-url').attr('action', _url);
	    }
	);
	
	// Add active class to the first element
	jQuery('.wt-categoryvtwo li:eq(1), .wt-latestjobs ul li:eq(1)').addClass('active');
	// Add class on hover
	jQuery('.wt-categoryvtwo li, .wt-latestjobs ul li').hover(function(){
		var _this = jQuery(this);
		_this.parents('ul').find('li').removeClass('active');
		_this.addClass('active');
	});

    //Slider
    if( jQuery("#wt-productrangeslider").length > 0 ){
        ageRangeslider();
    }

    //Switch hourly/fixed type
    jQuery('.wt-type').change(function (event) {
        var value = jQuery(this).val();
        if( value == 'hourly' ){
            jQuery('#wt-productrangeslider').removeClass('wt-none');
            jQuery('.wt-amountbox').removeClass('wt-none');
        } else {
            jQuery('#wt-productrangeslider').addClass('wt-none');
            jQuery('.wt-amountbox').addClass('wt-none');
        } 
    });
	
	//login POP
	jQuery('wt-searchbtn').on('click', function(event){
		event.preventDefault();
		jQuery('.wt-loginarea .wt-loginformhold').slideToggle();
	});

	
	/*OPEN CLOSE */
	jQuery('.wt-headerbtn').on('click', function(event){
		event.preventDefault();
		jQuery('.wt-headersearch .wt-loginformhold').slideToggle();
	});
	
	jQuery(document).on('click', '.wt-download-single-file', function (e) {
		var _this = jQuery(this);
		var attachment_id = _this.data('id');
		downloadAttachment(attachment_id);
	});

	// Remove single term filter
    jQuery(document).on('click', '.wt-clear-singleterm', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var taxonomy_name = _this.data('taxonomy');
        var term_value = _this.data('term_value');
		jQuery('input[name="' + taxonomy_name + '[]"][value="' + term_value + '"]').remove();
        _this.closest('li').remove();
       jQuery('#serach-projects').submit();
    });
	
	jQuery(document).on('click','.wt-term-remove-options', function(e) { 
        let _this = jQuery(this);
        _this.parents('li').remove();
		if(jQuery(".wt-term-remove-options").length == 0){
			jQuery('.wt-selected-skills').addClass('d-none');
		}
    });
	
});


function downloadAttachment(attachment_id) {
	var loader_html = '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';
    jQuery('body').append(loader_html);
	
	jQuery.ajax({
		type: "POST",
		url: scripts_vars.ajaxurl,
		data: {
            action: 'workreap_fap_download_attachment',
            attachment_id: attachment_id,
			security	 : scripts_vars.ajax_nonce
        },
		dataType: "json",
		success: function (response) {
			jQuery('body').find('.wt-preloader-section').remove();
			console.log(response);
            if (response.type === 'success') {
                var filehref = atob(reverse(response.attachment));
                var link = document.createElement('a');
                var filename = filehref.split('/').pop();
				
                link.href = filehref;
                link.download = filename;
                link.click();
                link.remove();
            } else {
                if (response.url) {
                    location.href = response.url;
                } else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
                }
            }
		}
	});
}

function reverse(s) {
    if (s.length < 2)
        return s;
    var halfIndex = Math.ceil(s.length / 2);
    return reverse(s.substr(halfIndex)) +
        reverse(s.substr(0, halfIndex));
}
//recaptcha
var workreapCaptchaCallback = function() {
	if(jQuery('#recaptcha_signup').length > 0){
		signup_reset 	= grecaptcha.render('recaptcha_signup', {'sitekey' : scripts_vars.site_key });
	}
	
	if(jQuery('#recaptcha_signin').length > 0){
		signin_reset 	= grecaptcha.render('recaptcha_signin', {'sitekey' : scripts_vars.site_key });
	}
	
	if(jQuery('#recaptcha_forgot').length > 0){
		forgot_reset 	= grecaptcha.render('recaptcha_forgot', {'sitekey' : scripts_vars.site_key });
	}
};

//Preloader
jQuery(window).load(function () {
	var loading_duration = scripts_vars.loading_duration;
    jQuery(".preloader-outer").delay(loading_duration).fadeOut();
    jQuery(".pins").delay(loading_duration).fadeOut("slow");
});

//get distance
function _get_distance(lat1, lon1, lat2, lon2, unit) {
    var radlat1 = Math.PI * lat1 / 180
    var radlat2 = Math.PI * lat2 / 180
    var theta = lon1 - lon2
    var radtheta = Math.PI * theta / 180
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist)
    dist = dist * 180 / Math.PI
    dist = dist * 60 * 1.1515
    if (unit == "K") {
        dist = dist * 1.609344
    }
    if (unit == "N") {
        dist = dist * 0.8684
    }
    return dist
}

// get rounded value
function _get_round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

// string replace URL
function _string_replace_url(url) {
	return url;
}

//Map styles
function workreap_get_map_styles(style) {

    var styles = '';
    if (style == 'view_1') {
        var styles = [{"featureType": "administrative.country", "elementType": "geometry", "stylers": [{"visibility": "simplified"}, {"hue": "#ff0000"}]}];
    } else if (style == 'view_2') {
        var styles = [{"featureType": "water", "elementType": "all", "stylers": [{"hue": "#7fc8ed"}, {"saturation": 55}, {"lightness": -6}, {"visibility": "on"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"hue": "#7fc8ed"}, {"saturation": 55}, {"lightness": -6}, {"visibility": "off"}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"hue": "#83cead"}, {"saturation": 1}, {"lightness": -15}, {"visibility": "on"}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"hue": "#f3f4f4"}, {"saturation": -84}, {"lightness": 59}, {"visibility": "on"}]}, {"featureType": "landscape", "elementType": "labels", "stylers": [{"hue": "#ffffff"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "off"}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"hue": "#ffffff"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "on"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"hue": "#bbbbbb"}, {"saturation": -100}, {"lightness": 26}, {"visibility": "on"}]}, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"hue": "#ffcc00"}, {"saturation": 100}, {"lightness": -35}, {"visibility": "simplified"}]}, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"hue": "#ffcc00"}, {"saturation": 100}, {"lightness": -22}, {"visibility": "on"}]}, {"featureType": "poi.school", "elementType": "all", "stylers": [{"hue": "#d7e4e4"}, {"saturation": -60}, {"lightness": 23}, {"visibility": "on"}]}];
    } else if (style == 'view_3') {
        var styles = [{"featureType": "water", "stylers": [{"saturation": 43}, {"lightness": -11}, {"hue": "#0088ff"}]}, {"featureType": "road", "elementType": "geometry.fill", "stylers": [{"hue": "#ff0000"}, {"saturation": -100}, {"lightness": 99}]}, {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"color": "#808080"}, {"lightness": 54}]}, {"featureType": "landscape.man_made", "elementType": "geometry.fill", "stylers": [{"color": "#ece2d9"}]}, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#ccdca1"}]}, {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"color": "#767676"}]}, {"featureType": "road", "elementType": "labels.text.stroke", "stylers": [{"color": "#ffffff"}]}, {"featureType": "poi", "stylers": [{"visibility": "off"}]}, {"featureType": "landscape.natural", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#b8cb93"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.sports_complex", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.medical", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.business", "stylers": [{"visibility": "simplified"}]}];
    } else if (style == 'view_4') {
        var styles = [{"elementType": "geometry", "stylers": [{"hue": "#ff4400"}, {"saturation": -68}, {"lightness": -4}, {"gamma": 0.72}]}, {"featureType": "road", "elementType": "labels.icon"}, {"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"hue": "#0077ff"}, {"gamma": 3.1}]}, {"featureType": "water", "stylers": [{"hue": "#00ccff"}, {"gamma": 0.44}, {"saturation": -33}]}, {"featureType": "poi.park", "stylers": [{"hue": "#44ff00"}, {"saturation": -23}]}, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"hue": "#007fff"}, {"gamma": 0.77}, {"saturation": 65}, {"lightness": 99}]}, {"featureType": "water", "elementType": "labels.text.stroke", "stylers": [{"gamma": 0.11}, {"weight": 5.6}, {"saturation": 99}, {"hue": "#0091ff"}, {"lightness": -86}]}, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"lightness": -48}, {"hue": "#ff5e00"}, {"gamma": 1.2}, {"saturation": -23}]}, {"featureType": "transit", "elementType": "labels.text.stroke", "stylers": [{"saturation": -64}, {"hue": "#ff9100"}, {"lightness": 16}, {"gamma": 0.47}, {"weight": 2.7}]}];
    } else if (style == 'view_5') {
        var styles = [{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffffff"}, {"lightness": 17}]}, {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]}, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 18}]}, {"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 16}]}, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#dedede"}, {"lightness": 21}]}, {"elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]}, {"elementType": "labels.text.fill", "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "geometry", "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]}, {"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#fefefe"}, {"lightness": 20}]}, {"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]}];
    } else if (style == 'view_6') {
        var styles = [{"featureType": "landscape", "stylers": [{"hue": "#FFBB00"}, {"saturation": 43.400000000000006}, {"lightness": 37.599999999999994}, {"gamma": 1}]}, {"featureType": "road.highway", "stylers": [{"hue": "#FFC200"}, {"saturation": -61.8}, {"lightness": 45.599999999999994}, {"gamma": 1}]}, {"featureType": "road.arterial", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 51.19999999999999}, {"gamma": 1}]}, {"featureType": "road.local", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 52}, {"gamma": 1}]}, {"featureType": "water", "stylers": [{"hue": "#0078FF"}, {"saturation": -13.200000000000003}, {"lightness": 2.4000000000000057}, {"gamma": 1}]}, {"featureType": "poi", "stylers": [{"hue": "#00FF6A"}, {"saturation": -1.0989010989011234}, {"lightness": 11.200000000000017}, {"gamma": 1}]}];
    } else {
        var styles = [{"featureType": "administrative.country", "elementType": "geometry", "stylers": [{"visibility": "simplified"}, {"hue": "#ff0000"}]}];
    }
    return styles;
}

//convert bytes to KB< MB,GB,TB
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

//validate amount
function validateAmount(_this) {
    if (isNaN(jQuery.trim(jQuery(_this).val()))) {
        jQuery(_this).val("");
    } else {
        var amt = jQuery(_this).val();
        if (amt != '') {
            if (amt.length > 16) {
                amt = amt.substr(0, 16);
                jQuery(_this).val(amt);
            }
            //amount = amt;
            return true;
        } else {
            //amount = gloAmount;
            return true;
        }
    }
}

//get random ID
function get_random_number() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4();
}

// Project Range Slider
function ageRangeslider(){
    var minVal = jQuery('#wt-consultationfeeamount').data('min');
    var maxVal = jQuery('#wt-consultationfeeamount').data('max');
    jQuery("#wt-productrangeslider").slider({
        range: true,
        min: 0,
        max: 150,
        values: [ minVal, maxVal ],
        slide: function( event, ui ) {
            jQuery( "#wt-consultationfeeamount" ).val( "$" + ui.values[ 0 ] + "- $" + ui.values[ 1 ] );
        }
    });
    jQuery( "#wt-consultationfeeamount" ).val( "$" + jQuery("#wt-productrangeslider").slider( "values", 0 ) + " - $" + jQuery("#wt-productrangeslider").slider( "values", 1 ));
}

// Init Full Width Sections
builder_full_width_section(); //Init Sections
var $ = window.jQuery;
$(window).off("resize.sectionSettings").on("resize.sectionSettings", builder_full_width_section);
function builder_full_width_section() {
    var $sections = jQuery('.main-page-wrapper .stretch_section');
    jQuery.each($sections, function (key, item) {
        var _sec = jQuery(this);
        var _sec_full = _sec.next(".section-current-width");
        _sec_full.length || (_sec_full = _sec.parent().next(".section-current-width"));
        var _sec_margin_left = parseInt(_sec.css("margin-left"), 10);
        var _sec_margin_right = parseInt(_sec.css("margin-right"), 10);
        var offset = 0 - _sec_full.offset().left - _sec_margin_left;
        var width = jQuery(window).width();
        if (_sec.css({
            position: "relative",
            left: offset,
            "box-sizing": "border-box",
            width: jQuery(window).width()
        }), !_sec.hasClass("stretch_data")) {
            var padding = -1 * offset;
            0 > padding && (padding = 0);
            var paddingRight = width - padding - _sec_full.width() + _sec_margin_left + _sec_margin_right;
            0 > paddingRight && (paddingRight = 0), _sec.css({
                "padding-left": padding + "px",
                "padding-right": paddingRight + "px"
            })
        }
    });
}

//Currency Positions
function currency_pos(currency,val) {
	var currency_p  		= scripts_vars.currency_pos;
	if( currency_p === 'right' ){
		return val+currency;
	}else if( currency_p === 'right_space' ){
		return val+' '+currency;
	}else if( currency_p === 'left_space' ){
		return currency+' '+val;
	} else{
		return currency+val;
	}
}

//portfolio slider
function portfolioslider(str=''){
	if(str){
		var sync1 = jQuery('#wt-portfolioslider-'+str);
		var sync2 = jQuery('#wt-portfoliogallery-'+str);
	}else{
		var sync1 = jQuery('#wt-portfolioslider');
		var sync2 = jQuery('#wt-portfoliogallery');
	}
	var slidesPerPage = 5;
	var syncedSecondary = true;
	
	sync1.owlCarousel({
		items : 1,
		loop: true,
		autoHeight:true,
		nav: false,
		rtl: false,
		dots: false,
		autoplay: false,
		slideSpeed : 2000,
		video:true,
		lazyLoad: true,
		videoHeight: 570,
		videoWidth: 870,
		onInitialized: autoOwlHeight,
		onResized: autoOwlHeight,
		onTranslated: autoOwlHeight,
		navClass: ['wt-prev', 'wt-next'],
		navContainerClass: 'wt-search-slider-nav',
		navText: ['<span class=\"lnr lnr-chevron-left\"></span>', '<span class=\"lnr lnr-chevron-right\"></span>'],
		responsiveRefreshRate : 200,
	}).on('changed.owl.carousel', syncPosition);
	
	sync2.on('initialized.owl.carousel', function () {
		console.log('testing js live')
		// sync2.find('.owl-item').eq(0).addClass('current');
	}).owlCarousel({
		items:5,
		dots: false,
		nav: false,
		margin:10,
		smartSpeed: 200,
		rtl: false,
		slideSpeed : 500,
		slideBy: slidesPerPage,
		responsiveClass:true,
		responsive:{
			0:{items:2,},
			420:{items:3,},
			575:{items:4,},
			768:{items:5,}
		},
		responsiveRefreshRate : 100,
	}).on('changed.owl.carousel', syncPosition2);
	
	function autoOwlHeight(event) {
		var maxHeight = 513;
		jQuery('.owl-item.active').each(function () {
			var thisHeight = parseInt( jQuery(this).height() );
			maxHeight	=( maxHeight >= thisHeight ? maxHeight : thisHeight );
		});

		jQuery('.wt-servicesslider.owl-carousel').css('height', maxHeight );
		jQuery('.wt-servicesslider .owl-stage-outer').css('height', maxHeight );
	}
	
	function syncPosition(el) {
		var count = el.item.count-1;
		var current = Math.round(el.item.index - (el.item.count/2) - .5);
		if(current < 0) {
			current = count;
		}
		if(current > count) {
			current = 0;
		}
		console.log(current)
		console.log(sync2)
		sync2
		.find('.owl-item')
		.removeClass('current')
		.eq(current)
		.addClass('current')
		var onscreen = sync2.find('.owl-item.active').length - 1;
		var start = sync2.find('.owl-item.active').first().index();
		var end = sync2.find('.owl-item.active').last().index();
		if (current > end) {
			sync2.data('owl.carousel').to(current, 100, true);
		}
		if (current < start) {
			sync2.data('owl.carousel').to(current - onscreen, 100, true);
		}
	}
	
	function syncPosition2(el) {
		if(syncedSecondary) {
			var number = el.item.index;
			sync1.data('owl.carousel').to(number, 100, true);
		}
	}
	
	sync2.on('click', '.owl-item', function(e){
		e.preventDefault();
		var number = jQuery(this).index();
		sync1.data('owl.carousel').to(number, 100, true);
	});
}

function parseURLParams(url) {
    var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

    if (query === url || query === "") return;

    for (i = 0; i < pairs.length; i++) {
        nv = pairs[i].split("=", 2);
        n = decodeURIComponent(nv[0]);
        v = decodeURIComponent(nv[1]);

        if (!parms.hasOwnProperty(n)) parms[n] = [];
        parms[n].push(nv.length === 2 ? v : null);
    }
    return parms;
}


//Plupload error throw
function plupload_error_display(err){
	var Error = '';
	if(err.code == '-600'){
		Error = scripts_vars.pluploadSize;
	} else if(err.code == '-601'){
		Error = scripts_vars.pluploadExtension;
	} else if(err.code == '-602'){
		Error = scripts_vars.pluploadDuplicate;
	} else {
		Error = scripts_vars.pluploadError;
	}

	jQuery.sticky(Error, {classList: 'important', speed: 200, autoclose: scripts_vars.sticky_speed});
}

/*
 Sticky v2.1.2 by Andy Matthews
 http://twitter.com/commadelimited
 */
!function(e){e.sticky=e.fn.sticky=function(t,s,i){"function"==typeof s&&(i=s);var a=function(e){var t=0,s=0,i=e.length;if(0===i)return t;for(s=0;s<i;s++)t=(t<<5)-t+e.charCodeAt(s),t&=t;return"s"+Math.abs(t)},r={position:"top-right",speed:"fast",allowdupes:!0,autoclose:5e3,classList:""},c=a(t),n=!0,o=!1;if(s&&e.extend(r,s),e(".sticky").each(function(){e(this).attr("id")===a(t)&&(o=!0,r.allowdupes||(n=!1)),e(this).attr("id")===c&&(c=a(t))}),scripts_vars.sm_success)var l=scripts_vars.sm_success;else l=r.position;e(".sticky-queue").length?e(".sticky-queue").removeClass(["top-right","top-center","top-left","bottom-right","bottom-center","bottom-left","middle-left","middle-right","middle-center"].join(" ")).addClass(l):e("body").append('<div class="sticky-queue '+l+'">'),n&&e(".sticky-queue").prepend('<div id="ID" class="jf-alert alert-dismissible border-POS CLASSLIST" role="alert"><button type="button" class="jf-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lnr lnr-cross"></i></span></button><div class="jf-description"><p><i class="lnr lnr-bullhorn"></i>NOTE</p></div></div>'.replace("POS",l).replace("ID",c).replace("NOTE",t).replace("CLASSLIST",r.classList)).find("#"+c).slideDown(r.speed,function(){n=!0,i&&"function"==typeof i&&i({id:c,duplicate:o,displayed:n})}),e(".sticky").ready(function(){r.autoclose&&e("#"+c).delay(r.autoclose).fadeOut(r.speed,function(){e(this).remove()})}),e(".jf-close").on("click",function(){var t=e(this);t.parents(".jf-alert").hasClass("sp-cacheit")?jQuery.confirm({title:scripts_vars.cache_title,message:scripts_vars.cache_message,buttons:{Yes:{class:"blue",action:function(){t.parents(".jf-alert").hasClass("cache-verification")?e.cookie("sp_cache_verification_"+scripts_vars.current_user_id,"true",{expires:365}):t.parents(".jf-alert").hasClass("cache-deactivation")&&e.cookie("sp_cache_deactivation_"+scripts_vars.current_user_id,"true",{expires:365}),e("#"+t.parents(".jf-alert").attr("id")).dequeue().fadeOut(r.speed,function(){t.remove()})}},No:{class:"gray",action:function(){return!1}}}}):e("#"+t.parents(".jf-alert").attr("id")).dequeue().fadeOut(r.speed,function(){t.remove()})})}}(jQuery);
           
/**Mega Menu*/
jQuery(function(n){function t(n){return n.offset().left+n.width()}jQuery(window).width();jQuery(".wt-navigation .menu-item-has-mega-menu").hover(function(){var e=n(this),i=e.closest(".wt-navigation"),a=e.find(".mega-menu"),o=t(i)-e.offset().left;jQuery(window).width()>768&&(a.width(Math.min(t(i),325*function(t){var e=0;return t.children(".mega-menu-row").each(function(){e=Math.max(e,n(this).children(".mega-menu-col").length)}),e}(a))),a.css("left",Math.min(0,o-a.width())),a.fadeIn("fast").css("display","block"))},function(){if(jQuery(window).width()>768){var t=n(this);t.closest(".wt-navigation"),t.find(".mega-menu").fadeOut("fast").css("display","none")}})});

// Confirm Box
!function(n){jQuery.confirm=function(i){if(n("#confirmOverlay").length)return!1;var o="";n.each(i.buttons,function(n,i){n="Yes"==n?scripts_vars.yes:"No"==n?scripts_vars.no:n,o+='<a href="#" class="button '+i.class+'">'+n+"<span></span></a>",i.action||(i.action=function(){})});var t=['<div id="confirmOverlay">','<div id="confirmBox">',"<h1>",i.title,"</h1>","<p>",i.message,"</p>",'<div id="confirmButtons">',o,"</div></div></div>"].join("");n(t).hide().appendTo("body").fadeIn();var r=n("#confirmBox .button"),c=0;n.each(i.buttons,function(n,i){r.eq(c++).on("click",function(){return i.action(),jQuery.confirm.hide(),!1})})},jQuery.confirm.hide=function(){n("#confirmOverlay").fadeOut(function(){n(this).remove()})}}(jQuery);

// Serialize Function
$.fn.serializeObject=function(){"use strict";var e={};return $.each(this.serializeArray(),function(a,r){var n=e[r.name];null!=n?$.isArray(n)?n.push(r.value):e[r.name]=[n,r.value]:e[r.name]=r.value}),e};

/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var n=/\+/g;function o(e){return t.raw?e:encodeURIComponent(e)}function i(e){return o(t.json?JSON.stringify(e):String(e))}function r(o,i){var r=t.raw?o:function(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return e=decodeURIComponent(e.replace(n," ")),t.json?JSON.parse(e):e}catch(e){}}(o);return e.isFunction(i)?i(r):r}var t=e.cookie=function(n,c,u){if(arguments.length>1&&!e.isFunction(c)){if("number"==typeof(u=e.extend({},t.defaults,u)).expires){var s=u.expires,a=u.expires=new Date;a.setMilliseconds(a.getMilliseconds()+864e5*s)}return document.cookie=[o(n),"=",i(c),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join("")}for(var d,f=n?void 0:{},p=document.cookie?document.cookie.split("; "):[],l=0,m=p.length;l<m;l++){var x=p[l].split("="),g=(d=x.shift(),t.raw?d:decodeURIComponent(d)),v=x.join("=");if(n===g){f=r(v,c);break}n||void 0===(v=r(v))||(f[g]=v)}return f};t.defaults={},e.removeCookie=function(n,o){return e.cookie(n,"",e.extend({},o,{expires:-1})),!e.cookie(n)}});

//Check Numeric Value only
!function(e){e.fn.numeric=function(t,i){"boolean"==typeof t&&(t={decimal:t}),void 0===(t=t||{}).negative&&(t.negative=!0);var n,a,r=!1===t.decimal?"":t.decimal||".",c=!0===t.negative;return i="function"==typeof i?i:function(){},"number"==typeof t.scale?0==t.scale?(r=!1,n=-1):n=t.scale:n=-1,a="number"==typeof t.precision?t.precision:0,this.data("numeric.decimal",r).data("numeric.negative",c).data("numeric.callback",i).data("numeric.scale",n).data("numeric.precision",a).keypress(e.fn.numeric.keypress).keyup(e.fn.numeric.keyup).blur(e.fn.numeric.blur)},e.fn.numeric.keypress=function(t){var i=e.data(this,"numeric.decimal"),n=e.data(this,"numeric.negative"),a=t.charCode?t.charCode:t.keyCode?t.keyCode:0;if(13==a&&"input"==this.nodeName.toLowerCase())return!0;if(13==a)return!1;var r=!1;if(t.ctrlKey&&97==a||t.ctrlKey&&65==a)return!0;if(t.ctrlKey&&120==a||t.ctrlKey&&88==a)return!0;if(t.ctrlKey&&99==a||t.ctrlKey&&67==a)return!0;if(t.ctrlKey&&122==a||t.ctrlKey&&90==a)return!0;if(t.ctrlKey&&118==a||t.ctrlKey&&86==a||t.shiftKey&&45==a)return!0;if(a<48||a>57){var c=e(this).val();if(0!==c.indexOf("-")&&n&&45==a&&(0===c.length||0===parseInt(e.fn.getSelectionStart(this),10)))return!0;i&&a==i.charCodeAt(0)&&-1!=c.indexOf(i)&&(r=!1),8!=a&&9!=a&&13!=a&&35!=a&&36!=a&&37!=a&&39!=a&&46!=a?r=!1:void 0!==t.charCode&&(t.keyCode==t.which&&0!==t.which?(r=!0,46==t.which&&(r=!1)):0!==t.keyCode&&0===t.charCode&&0===t.which&&(r=!0)),i&&a==i.charCodeAt(0)&&(r=-1==c.indexOf(i))}else if(e.data(this,"numeric.scale")>=0){var s=this.value.indexOf(i);s>=0?(decimalsQuantity=this.value.length-s-1,e.fn.getSelectionStart(this)>s?r=decimalsQuantity<e.data(this,"numeric.scale"):(integersQuantity=this.value.length-1-decimalsQuantity,r=integersQuantity<e.data(this,"numeric.precision")-e.data(this,"numeric.scale"))):r=!(e.data(this,"numeric.precision")>0)||this.value.replace(e.data(this,"numeric.decimal"),"").length<e.data(this,"numeric.precision")-e.data(this,"numeric.scale")}else r=!(e.data(this,"numeric.precision")>0)||this.value.replace(e.data(this,"numeric.decimal"),"").length<e.data(this,"numeric.precision");return r},e.fn.numeric.keyup=function(t){var i=e(this).val();if(i&&i.length>0){var n=e.fn.getSelectionStart(this),a=e.data(this,"numeric.decimal"),r=e.data(this,"numeric.negative");if(""!==a&&null!==a){var c=i.indexOf(a);0===c&&(this.value="0"+i),1==c&&"-"==i.charAt(0)&&(this.value="-0"+i.substring(1)),i=this.value}for(var s=[0,1,2,3,4,5,6,7,8,9,"-",a],u=i.length,l=u-1;l>=0;l--){var h=i.charAt(l);0!==l&&"-"==h?i=i.substring(0,l)+i.substring(l+1):0!==l||r||"-"!=h||(i=i.substring(1));for(var d=!1,o=0;o<s.length;o++)if(h==s[o]){d=!0;break}d&&" "!=h||(i=i.substring(0,l)+i.substring(l+1))}var m=i.indexOf(a);if(m>0){for(var f=u-1;f>m;f--){i.charAt(f)==a&&(i=i.substring(0,f)+i.substring(f+1))}e.data(this,"numeric.scale")>=0&&(i=i.substring(0,m+e.data(this,"numeric.scale")+1)),e.data(this,"numeric.precision")>0&&(i=i.substring(0,e.data(this,"numeric.precision")+1))}else e.data(this,"numeric.precision")>0&&(i=i.substring(0,e.data(this,"numeric.precision")-e.data(this,"numeric.scale")));this.value=i,e.fn.setSelection(this,n)}},e.fn.numeric.blur=function(){var t=e.data(this,"numeric.decimal"),i=e.data(this,"numeric.callback"),n=this.value;""!==n&&(new RegExp("^\\d+$|^\\d*"+t+"\\d+$").exec(n)||i.apply(this))},e.fn.removeNumeric=function(){return this.data("numeric.decimal",null).data("numeric.negative",null).data("numeric.callback",null).unbind("keypress",e.fn.numeric.keypress).unbind("blur",e.fn.numeric.blur)},e.fn.getSelectionStart=function(e){if(e.createTextRange){var t=document.selection.createRange().duplicate();return t.moveEnd("character",e.value.length),""===t.text?e.value.length:e.value.lastIndexOf(t.text)}return e.selectionStart},e.fn.setSelection=function(e,t){if("number"==typeof t&&(t=[t,t]),t&&t.constructor==Array&&2==t.length)if(e.createTextRange){var i=e.createTextRange();i.collapse(!0),i.moveStart("character",t[0]),i.moveEnd("character",t[1]),i.select()}else e.setSelectionRange&&(e.focus(),e.setSelectionRange(t[0],t[1]))}}(jQuery);

//SVG Render
jQuery("img.testimonialimg").each(function(){var t=jQuery(this),r=t.attr("id"),a=t.attr("class"),e=t.attr("src");jQuery.get(e,function(e){var i=jQuery(e).find("svg");void 0!==r&&(i=i.attr("id",r)),void 0!==a&&(i=i.attr("class",a+" replaced-svg")),i=i.removeAttr("xmlns:a"),t.replaceWith(i)},"xml")});