<?php

wp_enqueue_style("atomchat-admin", dirname(plugin_dir_url( __FILE__ )).'/css/atomchat-admin.css');
wp_enqueue_script("atomchat-event", dirname(plugin_dir_url( __FILE__ )).'/js/event.js');
wp_enqueue_script("atomchat-admin", dirname(plugin_dir_url( __FILE__ )).'/js/atomchat-admin.js');
wp_enqueue_script("atomchat-clipboard", dirname(dirname(dirname(dirname(plugin_dir_url( __FILE__ ))))).'/wp-includes/js/clipboard.min.js');

$isBuddyPressActive = $show_username = $show_nickname = $show_displayname = $show_fname_lname = '';
if(!is_plugin_active('buddypress/bp-loader.php') && !is_plugin_active('buddyboss-platform/bp-loader.php')){
	$isBuddyPressActive = 'style="display:none;"';
}
$isMyCredActive = '';
if(!is_plugin_active('mycred/mycred.php')){
	$isMyCredActive = 'style="display:none;"';
}

switch (get_option("show_name_in_chat")) {
	case 'username':
		$show_username = "checked=checked";
		break;
	case 'nickname':
		$show_nickname = "checked=checked";
		break;
	case 'fname_lname':
		$show_fname_lname = "checked=checked";
		break;
	case 'display_name':
		$show_displayname = "checked=checked";
		break;
	default:
		$show_fname_lname = "checked=checked";
		break;
}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
	<div class="tabs">
		<h1>AtomChat Settings</h1>
		<ul class="tab-links" id = "submenu">
			<li data-rel="atomchat_adminpanel" class="active menus"><a href="#atomchat_adminpanel">Admin Panel</a></li>
			<li data-rel="atomchat_layoutsettings" class="menus"><a href="#atomchat_layoutsettings">General Settings</a></li>
			<li data-rel="atomchat_settings" class="menus"><a href="#atomchat_settings" <?php echo esc_attr($isBuddyPressActive); ?>>BuddyPress/BuddyBoss Settings</a></li>
			<li data-rel="atomchat_auth" class="menus auth"><a href="#atomchat_auth">Authentication Settings</a></li>
			<li data-rel="atomchat_MyCred" class="menus"><a href="#atomchat_MyCred" <?php echo esc_attr($isMyCredActive); ?>>MyCred Settings</a></li>
		</ul>

		<div class="tab-content">
			<div id="atomchat_adminpanel" class="tab active">
				<div class="atomchat_admin_content">
					<h2>
						AtomChat Admin Panel
					</h2>
					<p>
						To Change the layout or further customize AtomChat please visit admin panel.
					</p>
					<p>
						<b>Note: </b>If you are already logged in to AtomChat client area, You will be redirected to AtomChat Admin Panel
					</p>
					<p style="margin-top: 20px;">
						<button type="button" class="button-primary" onclick="cometGOPanel('<?php echo $atomchatAdminPanelurl; ?>');">
							Launch Client Area
						</button>
					</p>
				</div>
			</div>

			<div id="atomchat_layoutsettings" class="tab">
				<table cellspacing="1" style="margin-top:20px;">
					<tr style="margin-top:20px;">
						<td width="550" style="padding-top: 20px;">
							<p class="atomchat-go-para">
								Add Docked Layout on all pages
							</p>
							<p>
								Please check this option to add AtomChat’s docked layout to ALL pages. If you wish to include this layout only on select pages, please uncheck this option and add the shortcode [ShortCode Here] on the desired page/s.
							</p>
							<p>
								Docked Layout Shortcode -
								<div class="codebox"><pre data-keep-tags="highlight" class=" language-php"><code class=" language-php"><span class="token punctuation">[</span>atomchat layout<span class="token operator">=</span><span class="token string">'docked'</span><span class="token punctuation">]</span></code></pre><button id="copy_docked_shortcode" class="copy">COPY</button></div>
							</p>
						</td>
						<td valign="top" style="padding-top: 30px;">
							<input type = "checkbox" class="show_docked_layout_on_all_pages" value="show_docked_layout_on_all_pages" name="show_docked_layout_on_all_pages" <?php if(get_option('show_docked_layout_on_all_pages') === 'true') echo 'checked="checked"';?> /> Yes
						</td>
					</tr>
					<tr>
						<td>
							<p class="atomchat-go-para">Display user's name in chat as </p>

							<input type="radio" id="name1" class="show_name_in_chat" name="chat_username" value="username" <?php echo $show_username?>>
							<label for="name1">Username</label><br><br>
							<input type="radio" id="name2" class="show_name_in_chat" name="chat_username" value="nickname" <?php echo $show_nickname?>>
							<label for="name2">Nickname</label><br><br>
							<input type="radio" id="name3" class="show_name_in_chat" name="chat_username" value="fname_lname" <?php echo $show_fname_lname?>>
							<label for="name3">First name + Last name</label><br><br>
							<input type="radio" id="name4" class="show_name_in_chat" name="chat_username" value="display_name" <?php echo $show_displayname?>>
							<label for="name4">Display name</label><br><br>

							<p><b>Note: </b>If selected name is not set for any user then Username will be displayed as the name of the user in chat</p>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;">
							<button type="submit" value = "submit" id = "update_layout_setting" class = "button-primary">Save Settings</button>
						</td>
					</tr>
				</table>
				<div id = "success_layout" class = "successmsg"></div>
			</div>

			<div id="atomchat_settings" class="tab">
				<p class="atomchat-go-para">
					Extend AtomChat for BuddyPress/BuddyBoss!
				</p>
				<p>
					We’ve detected that you’re using BuddyPress/BuddyBoss. Here are some additional settings that you can configure:
				</p>
				<table cellspacing="1" style="margin-top:20px;">
					<tr style="margin-top:20px;">
						<td width="550" style="padding-top: 20px;">
							<p class="atomchat-go-para">
								Show only Friends in Contacts list?
							</p>
							<p>
								If you tick this option, then when a user logs in, he will be able to see only his friends in the Contacts list. Note that, friends are synchronized only after they login atleast once to your site (after adding AtomChat).
							</p>
						</td>
						<td valign="top" style="padding-top: 30px;">
							<input type = "checkbox" class="atomchat_show_friends" value="atomchat_show_friends" name="atomchat_show_friends" <?php if(get_option('atomchat_show_friends') === 'true') echo 'checked="checked"';?> /> Yes
						</td>
					</tr>
					<tr style="margin-top:20px;">
						<td width="550" style="padding-top: 20px;">
							<p class="atomchat-go-para">
								Synchronize BuddyPress/BuddyBoss Groups with AtomChat
							</p>
							<p>
								If you tick this option, we will create equivalent chat groups in AtomChat and add only those users who are part of your BuddyPress/BuddyBoss Group to it.
							</p>
							<span class="atomchat-go-para">
								Note :
							</span>
							<span>
								If you are facing trouble in syncing old BuddyPress/BuddyBoss Groups with AtomChat, please Deactivate the AtomChat plugin and Activate again.
							</span>
						</td>
						<td valign="top" style="padding-top: 30px;">
							<input type = "checkbox" class="atomchat_bp_group_sync" value="atomchat_bp_group_sync" name="atomchat_bp_group_sync" <?php if(get_option('atomchat_bp_group_sync') === 'true') echo 'checked="checked"';?> /> Yes
							<td>
							</tr>
							<tr>
								<td style="padding-top: 20px;">
									<button type="submit" value = "submit" id = "save" class = "button-primary">Save Settings</button>
								</td>
							</tr>
						</table>
						<div id = "success" class = "successmsg"></div>
					</div>

					<div id="atomchat_auth" class="tab">
						<div class="atomchat_auth_content">
							<h2>
								Enter Auth Key
							</h2>
							<p>
								<b>Note:</b> You can find your Auth Key in AtomChat Admin Panel -> API Keys (top-right button)
							</p>
							<p style="margin-top: 20px;">
								<input type="text" class="atomchat_auth_key" name="atomchat_auth_key" id="auth_key_token" value="<?php echo get_option('atomchat_auth_key');?>" style="width: 25%;" placeholder="Enter Auth Key">
							</p>

							<h2>
								Enter API Key
							</h2>
							<p>
								<b>Note:</b> You can find your API Key in AtomChat Admin Panel -> API Keys (top-right button)
							</p>
							<p style="margin-top: 20px;">
								<input type="text" class="atomchat_api_key" name="atomchat_api_key" id="api_key" value="<?php echo get_option('atomchat_api_key');?>" style="width: 25%;" placeholder="Enter API Key">
							</p>

						</div>
						<p style="margin-top: 20px;">
							<button type="submit" value="submit" class="button-primary" id ="update_auth_key">Update</button>
						</p>
						<div id = "success_auth" class = "successmsg"></div>
					</div>

				<div id="atomchat_MyCred" class="tab">
					<div id="atomchat_mycred_settings" >
						<div id="atomchat_enable_mycred">
							<h2> Integrate MyCred with AtomChat</h2><br>
							<h2 style="display: inline-block; width: 360px;"> Enable MyCred With AtomChat           </h2>
							<input style="display: inline-block;" type = "checkbox" class="atomchat_enable_mycred" value="atomchat_enable_mycred" name="atomchat_enable_mycred" <?php if(get_option('atomchat_enable_mycred') === 'true') echo 'checked="checked"';?> /> Yes
						</div>
						<?php if(get_option('atomchat_enable_mycred') === 'true') {  $style = "display:block;";  }else{ $style = "display:none;"; }
						?>

						<div id="atomchat_roles" style=<?php echo $style; ?>>
							<?php
							$roles = $wp_roles->get_names();
							foreach($roles as $value) {
								$role = $value;
								$role_data = (!empty(get_option("atomchat_".$value))) ? unserialize(get_option("atomchat_".$value)) : array('creditToDeduct'=> 0,'creditOnMessage'=>0,'creditToDeductAudio'=>0,'creditToDeductAudioOnMinutes'=>0,'creditToDeductVideo'=>0,'creditToDeductVideoOnMinutes'=>0);
								$creditToDeduct = empty((int) $role_data['creditToDeduct']) ? 0 : $role_data['creditToDeduct'];
								$creditOnMessage = empty((int) $role_data['creditOnMessage']) ? 0 : $role_data['creditOnMessage'];
								$creditToDeductAudio = empty((int) $role_data['creditToDeductAudio']) ? 0 : $role_data['creditToDeductAudio'];
								$creditToDeductAudioOnMinutes = empty((int) $role_data['creditToDeductAudioOnMinutes']) ? 0 : $role_data['creditToDeductAudioOnMinutes'];
								$creditToDeductVideo = empty((int) $role_data['creditToDeductVideo']) ? 0 : $role_data['creditToDeductVideo'];
								$creditToDeductVideoOnMinutes = empty((int) $role_data['creditToDeductVideoOnMinutes']) ? 0 : $role_data['creditToDeductVideoOnMinutes'];
								?>
								<hr>
								<div class="atomchat_role" id=<?php echo $value; ?>>
									<h2><?php echo $value; ?></h2>
								</div>
								<div style="display: none;" id=<?php echo "atomchat_content_".$value ?>>
									<table cellspacing="1" style="margin-top:20px;">
										<tr style="margin-top:0;">
											<td width="200" style="padding-top: 20px;">
												<p>Text Chat (on messages)  Charge</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditToDeduct" name="creditToDeduct" value="<?php echo $creditToDeduct; ?>" style="width: 93%;" id=<?php echo "creditToDeduct_".$role; ?>>
											</td>
											<td width="90" style="padding-top: 20px;">
												<p>credits for</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditOnMessage" name="creditOnMessage" value="<?php echo $creditOnMessage;?>" style="width: 93%;" id=<?php echo "creditOnMessage_".$role; ?>>
												<td width="90" style="padding-top: 20px;">
													<p>Messages</p>
												</td>
											</td>
										</tr>
										<tr style="margin-top:0;">
											<td width="200" style="padding-top: 20px;">
												<p>Audio Chat  Charge</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditToDeductAudio" name="creditToDeductAudio" value="<?php echo $creditToDeductAudio;?>" style="width: 93%;" id=<?php echo "creditToDeductAudio_".$role; ?>>
											</td>
											<td width="90" style="padding-top: 20px;">
												<p>credits every</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditToDeductAudioOnMinutes" name="creditToDeductAudioOnMinutes" value="<?php echo $creditToDeductAudioOnMinutes; ?>" style="width: 93%;"width="90" style="padding-top: 20px;" id=<?php echo "creditToDeductAudioOnMinutes_".$role; ?>>
											</td>
											<td width="90" style="padding-top: 20px;">
												<p>Minutes</p>
											</td>
										</tr>
										<tr style="margin-top:0;">
											<td width="200" style="padding-top: 20px;">
												<p>Audio/Video Chat  Charge</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditToDeductVideo" name="creditToDeductVideo"  value="<?php echo $creditToDeductVideo; ?>" style="width: 93%;" id=<?php echo "creditToDeductVideo_".$role; ?>>
											</td>
											<td width="90" style="padding-top: 20px;">
												<p>credits every</p>
											</td>
											<td width="150" style="padding-top: 20px;">
												<input type="text" class="creditToDeductVideoOnMinutes" name="creditToDeductVideoOnMinutes" value="<?php echo  $creditToDeductVideoOnMinutes; ?>" style="width: 93%;" id=<?php echo "creditToDeductVideoOnMinutes_".$role; ?>>
											</td>
											<td width="90" style="padding-top: 20px;">
												<p>Minutes</p>
											</td>
										</tr>
										<tr>
											<td width="90" style="padding-top: 20px;">
												<div type="submit" value="submit" class="button-primary" name="edit_credit" id=<?php echo "atomchat_edit_credits_".$value; ?>>Update Credits					</div>
												<div id=<?php echo "atomchat_update_credeits_role_".$role; ?>></div>
												</td>
											</tr>
										</table>
									</div>
								</hr>
							<?php	} ?>
						</div>
						<div>
							<hr>
							<button type="submit" value = "submit" id = "atomchat_update_credeits" class = "button-primary">Save Settings</button>
							<div id="success_mycred">
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</body>
</html>