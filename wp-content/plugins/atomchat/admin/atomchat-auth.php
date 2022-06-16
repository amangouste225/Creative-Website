<?php
	wp_enqueue_style("atomchat-admin", dirname(plugin_dir_url( __FILE__ )).'/css/atomchat-auth.css');
	wp_enqueue_script("atomchat-event", dirname(plugin_dir_url( __FILE__ )).'/js/event.js');
	wp_enqueue_script("atomchat-admin", dirname(plugin_dir_url( __FILE__ )).'/js/atomchat-admin.js');
?>

<!DOCTYPE html>
<html>
<head></head>
<body>
	<div class="atomchat">
		<div class="comet-locked-layout">
			<img class="atomchat-logo" src=<?php echo $atomchatAuthKey;?> />
		</div>
		<div class="comet-installation-successs">
			<div class="comet-content">
				<img class="atomchat-logo-image" src=<?php echo $atomchatLogo;?>>
	            <div id="atomchat_auth" class="tab">
		        	<div class="atomchat_auth_content">
						<h2>
							Enter Auth Key
						</h2>
						<p>
							<b>Note:</b> You can find your Auth Key in AtomChat Admin Panel -> API Keys (top-right button)
						</p>
						<p style="margin-top: 20px;">
							<input type="text" class="atomchat_auth_key" name="atomchat_auth_key" id="auth_key_token" value="<?php echo get_option('atomchat_auth_key');?>" style="width: 60%;" placeholder="Enter Auth Key">
						</p>

						<h2>
							Enter API Key
						</h2>
						<p>
							<b>Note:</b> You can find your API Key in AtomChat Admin Panel -> API Keys (top-right button)
						</p>
						<p style="margin-top: 20px;">
							<input type="text" class="atomchat_api_key" name="atomchat_api_key" id="api_key" value="<?php echo get_option('atomchat_api_key');?>" style="width: 60%;" placeholder="Enter API Key">
						</p>
		        	</div>
	        		<p style="margin-top: 20px;">
						<button type="submit" value="submit" class="button-primary" id ="update_auth_key" level="init">Update</button>
					</p>
		        	<div id = "success_auth" class = "successmsg"></div>
	            </div>
			</div>
		</div>
	</div>
</body>
</html>