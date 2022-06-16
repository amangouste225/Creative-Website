//facebook connect
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// initialize the facebook sdk
window.fbAsyncInit = function() {
	if( is_loggedin === 'false' ){
		FB.init({
		  appId      : fbapp_id,
		  cookie     : true, 
		  xfbml      : true,
		  version    : 'v3.1'
		});
	}
}

// add event listener on the logout button
function facebookLogin() {
	jQuery('body').append(loader_html);
	FB.getLoginStatus(function(response) {
	   statusChangeCallback(response);
	});
}

//Status change callback
function statusChangeCallback(response) {
	 if(response.status === "connected") {
		fetchUserProfile();
	 } else{
		 // Logging the user to Facebook by a Dialog Window
		 facebookLoginByDialog();
	 }
}

//Fetch Profile Data
function fetchUserProfile() {
   FB.api('/me?fields=id,first_name,last_name,middle_name,picture,short_name,name,email,gender', function(response) { 
		var dataString = 'security='+scripts_vars.ajax_nonce+'&email=' + response.email +'&id=' + response.id + '&name=' + response.name + '&action=workreap_js_social_login';   
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wt-preloader-section').remove();
				if (response.type === 'success') {  
					jQuery('#loginpopup').modal('hide');
					if( typeof(response.html) != "undefined" && response.html !== null && response.html !== '' ) {
						jQuery('.modal-post-wrap').html(response.html);
						jQuery('#taskpopup').modal('show');
						jQuery('.wt-registration-content-model').html(response.html);
						jQuery('.wt-registration-parent-model').modal('show');
						var is_rtl  = scripts_vars.is_rtl;

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
					} else {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
						window.location.reload();
					}
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
   });
}

//Facebook dialog
function facebookLoginByDialog() {
	jQuery('body').find('.wt-preloader-section').remove();
	FB.login(function(response) {
	   statusChangeCallback(response);

	}, {scope: 'public_profile,email'});
}

// logging out the user from Facebook
function facebookLogout() {
	FB.logout(function(response) {
	   statusChangeCallback(response);
	});
}