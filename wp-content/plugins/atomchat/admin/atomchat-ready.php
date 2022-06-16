<?php
	wp_enqueue_style("atomchat-admin", dirname(plugin_dir_url( __FILE__ )).'/css/atomchat-ready.css');
	wp_enqueue_script("atomchat-event", dirname(plugin_dir_url( __FILE__ )).'/js/event.js');
?>

<!DOCTYPE html>
<html>
<head></head>
<body>
	<div class="atomchat">
		<div class="comet-locked-layout">
			<img class="atomchat-logo" src=<?php echo $atomchatDockedLayout;?> />
		</div>
		<div class="comet-installation-successs">
			<div class="comet-content">
				<img class="atomchat-logo-image" src=<?php echo $atomchatLogo;?>>
				<h2>AtomChat Docked Layout</h2>
				<p style="font-weight: 700;">AtomChat has been successfully installed on your site. </p>
				<p>We have pre-enabled our Docked Layout for your convenience. </p>
				<div>
					<button type="submit" value = "submit" id = "save" class = "button-primary" onclick="cometGOPanel('<?php echo $atomchatAdminPanelurl; ?>');">Launch Admin Panel</button>
					<button type="submit" value = "submit" id = "save" class = "button-primary" onclick="cometGoSettings();">Go To Settings</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>