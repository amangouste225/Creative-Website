var googleUser = {};
var auth2 = '';
var loader_html = '<div class="wt-preloader-section"><div class="wt-preloader-holder"><div class="wt-loader"></div></div></div>';
var workreap_gconnect_app = function() {
	gapi.load('auth2', function(){
	  auth2 = gapi.auth2.init({
		client_id: scripts_vars.gclient_id,
		cookiepolicy: 'none',
	  });
		
	  attachSignin(document.getElementById('wt-gconnect'));
	  attachSignin(document.getElementById('wt-gconnect-reg'));
	  
	});
  };

  function attachSignin(element) {
	auth2.attachClickHandler(element, {},
		function(googleUser) {
			jQuery('body').append(loader_html);
			var profile = googleUser.getBasicProfile();

			var dataString = 'security='+scripts_vars.ajax_nonce+'&login_type=google&'+'picture=' + profile.getImageUrl()+'&email=' + profile.getEmail() +'&id=' + profile.getId() + '&name=' + profile.getName() + '&action=workreap_js_social_login';

			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: dataString,
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.wt-preloader-section').remove();
					if (response.type === 'success') {  
						jQuery('#loginpopup').modal('hide');
						
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
						window.location.replace(response.redirect);
					} else {
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});

		}, function(error) {
			//jQuery.sticky(JSON.stringify(error, undefined, 2), {classList: 'important', speed: 200, autoclose: 5000});
		});
  }
