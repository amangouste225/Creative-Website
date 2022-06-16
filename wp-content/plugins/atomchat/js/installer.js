jqcc = jQuery;

jqcc(document).ready(function(){
	jqcc('#license').keyup(function(){
        jqcc("#error").hide();
    });
});

/**
 * atomchatInstall: validate license key and begin installation process
*/
function atomchatInstall(){

	var licensekey= jqcc("#license").val().trim();
	jqcc("#error").hide();

	if(licensekey == ''){
		alert("Please enter valid license key.");
		return;
	}
	atomchatCheckLicenseKey(licensekey);
};

/**
 * atomchatCheckLicenseKey
 * @param licenseKey
 * @return status of license and it's details
 */
function atomchatCheckLicenseKey(licensekey){
	var data = {
		'action': 'atomchat_action',
		'api': 'atomchatCheckLicenseKey',
		'licensekey': licensekey
	};

	jqcc.post(ajaxurl, data, function(response) {
		if(response.success == 1){
			if(response.hasOwnProperty('cloud') && response.cloud != 0){
				setCookie ("atomchat_cloud", response.cloud, 365);
				location.reload();
			}else{
				setCookie ("atomchat_cloud", 0, 365);
				jqcc("#license-form").fadeOut();
				jqcc("#installer-process").fadeIn(function(){
					jqcc("#progressbar").css('width','5%');
					response = JSON.parse(response['atomchat_api_response']);
				});
			}
		}else{
			jqcc("#error").show().html(response.error);
		}
	}).fail(function(data) {
	    jqcc("#error").show().html(data.message);
	});
}

/**
 * getCookie
 * @param cname = name of cookie
 * @return cookie value
 */
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

/**
 * setCookie
 * @param key = name , value = cookie value, days = expiry period of cookie
 * creating new cookie
 */
function setCookie (key, value, days) {
    var date = new Date();
    // Default at 365 days.
    days = days || 365;
    // Get unix milliseconds at current time plus number of days
    date.setTime(+ date + (days * 86400000)); //24 * 60 * 60 * 1000
    window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";
}