"use strict";
jQuery(document).ready(function ($) {
	var current_user = scripts_vars.current_user;
	var instance_id = scripts_vars.instance_id;

	var currentUserId = 'private-user-' + scripts_vars.current_user; // Get this from your auth system
	const beamsClient = new PusherPushNotifications.Client({
		instanceId: instance_id,
	});

	console.log(scripts_vars.site_url + '/wp-json/api/v1/pusher_endpoint_token?user_id=' + scripts_vars.current_user);
	const beamsTokenProvider = new PusherPushNotifications.TokenProvider({
		url: scripts_vars.site_url + '/wp-json/api/v1/pusher_endpoint_token?user_id=' + scripts_vars.current_user,
		queryParams: { someQueryParam: 'parameter-content' },
		headers: { someHeader: 'header-content' },
	});

	console.log(beamsTokenProvider);

	// beamsClient
	// 	.start()
	// 	.then(() => beamsClient.setUserId(currentUserId, beamsTokenProvider))
	// 	.catch(console.error);




	beamsClient.getUserId()
		.then(userId => {

			console.log(userId);
			console.log(currentUserId);

			// Check if the Beams user matches the user that is currently logged in
			if (!userId) {
				setUserID();
			} else if (userId !== currentUserId) {
				return beamsClient.stop();
			} else {
				setUserID();
			}
			// if (userId !== currentUserId) {
			// 	console.log('A');
			// } else {
			// 	console.log('B');
			// }
		})
		.catch(console.error);
	// function test() {
	// }

	function setUserID() {
		beamsClient
			.start()
			.then(() => beamsClient.setUserId(currentUserId, beamsTokenProvider))
			.catch(console.error);
	}
});

