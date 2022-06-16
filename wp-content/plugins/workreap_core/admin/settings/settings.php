<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Workreap
 * @subpackage Workreap/admin
 */
/**
 * @init            Theme Admin Menu init
 * @package         Amentotech
 * @subpackage      workreap_core/admin/partials
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if (!function_exists('workreap_core_admin_menu')) {
    add_action('admin_menu', 'workreap_core_admin_menu');

    function workreap_core_admin_menu() {
        $url = admin_url();
        add_submenu_page('edit.php?post_type=freelancers', 
						 esc_html__('Settings', 'workreap_core'), 
						 esc_html__('Settings', 'workreap_core'), 
						 'manage_options', 
						 'workreap_settings', 
						 'workreap_admin_page'
        );
		
    }
}


/**
 * @init            Settings Admin Page
 * @package         Workreap
 * @subpackage      Workreap/admin/partials
 * @since           1.0
 * @desc            This Function Will Produce All Tabs View.
 */
if (!function_exists('workreap_admin_page')) {

    function workreap_admin_page() {
		$settings	= workreap_get_theme_settings();

		$protocol = is_ssl() ? 'https' : 'http';
		$post_args	= array( '_builtin' => false, 
							 'publicly_queryable' => true, 
							 'show_ui' => true 
						 );

		$term_args	= array( '_builtin' => false, 
						 'publicly_queryable' => true, 
						 'show_ui' => true 
					 );

		$taxonomies = get_taxonomies( $term_args, 'objects' ); 
		$post_types = get_post_types( $post_args,'objects' );
        $protocol = is_ssl() ? 'https' : 'http';

        ob_start();
		
		if( isset( $_GET['tab'] ) && $_GET['tab'] === 'chat' ){
			$totalCount 			= ChatSystem::getUsersThreadListData( '', '', 'fetch_conversation_total', array(), '');
			
			$items_per_page 	= 10;
			$current_page       = isset( $_GET['current_page'] ) ? abs( (int) $_GET['current_page'] ) : 1;
			$offset         	= ( $current_page * $items_per_page ) - $items_per_page;
			$usersThreadUserList 	= ChatSystem::getUsersThreadListData( '', '', 'fetch_conversation', array(), '',$offset);
			$totalRecords      		= ceil($totalCount / $items_per_page);
		}

		if (function_exists('fw_get_db_settings_option')) {
            $chat = fw_get_db_settings_option('chat', $default_value = null);
		}

        ?>
        <div id="wt-main" class="wt-main wt-addnew">
            <div class="wrap">
                <div id="wt-tab1s" class="wt-tabs workreap-settings-page">
                    <div class="wt-tabscontent">
                        <div id="wt-main" class="wt-main wt-features settings-main-wrap">
						    <div class="wt-featurescontent">
                                <div class="wt-twocolumns">
                                <ul class="wt-tabsnav">
									<li class="<?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'welcome' ? 'wt-active-tab' : ''; ?>">
										<a href="<?php echo cus_prepare_final_url('welcome','settings'); ?>">
											<?php esc_html_e("What's New?", 'workreap_core'); ?>
										</a>
									</li> 
									<li class="<?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'settings'? 'wt-active-tab' : ''; ?>">
										<a href="<?php echo cus_prepare_final_url('settings','settings'); ?>">
											<?php esc_html_e('Settings', 'workreap_core'); ?>
										</a>
									</li>
									<?php if( isset( $chat['gadget'] ) && $chat['gadget'] === 'chat' ){?>
										<li class="<?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'chat'? 'wt-active-tab' : ''; ?>">
											<a href="<?php echo cus_prepare_final_url('chat','chat'); ?>">
												<?php esc_html_e('Users Chat', 'workreap_core'); ?>
											</a>
										</li>
										<?php if( isset( $_GET['tab'] ) && $_GET['tab'] === 'chat' && !empty($usersThreadUserList) ){?>
											<li class="wt-delete-messages">
												<a href="#" onclick="event_preventDefault(event);" class="wt-delete-chat-data" data-key="all"><?php esc_html_e('Delete All Messages', 'workreap_core'); ?></a>
											</li>
										<?php }?>
									<?php }?>
									
								</ul>
								<?php if( isset( $_GET['tab'] ) && $_GET['tab'] === 'chat' ){?>
									<?php if( isset( $chat['gadget'] ) && $chat['gadget'] === 'chat' ){?>
										<div class="settings-wrap workreap-chat-page">
											<div class="wt-boxarea">
												<div id="tabone">
													<div class="save-settings-form">
														<div class="wt-privacysetting">
															<table class="lx-chatlist wt-offersmessages">
																<thead>
																	<tr>
																		<th><?php esc_html_e('Messages', 'workreap_core'); ?></th>
																		<th><?php esc_html_e('Sender', 'workreap_core'); ?></th>
																		<th><?php esc_html_e('Receiver', 'workreap_core'); ?></th>
																		<th><?php esc_html_e('Actions', 'workreap_core'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php  if(!empty($usersThreadUserList)){
																		foreach($usersThreadUserList as $key => $message){
																			$message_data	= !empty($message['chat_message']) ?  $message['chat_message'] : '';
																			$message_id		= !empty($message['id']) ? intval($message['id']) :'';
																			$sender_id		= !empty($message['sender_id']) ? $message['sender_id'] :'';
																			$receiver_id	= !empty($message['receiver_id']) ? $message['receiver_id'] :'';
																			
																			$sender			= !empty($sender_id) ? workreap_get_username(intval($sender_id)) :'';
																			$receiver		= !empty($receiver_id) ? workreap_get_username(intval($receiver_id)) :'';
																			
																		?>
																		<tr>
																			<td data-label="<?php esc_html_e('Messages', 'workreap_core'); ?>"><?php echo esc_html($message_data);?></td>
																			<td data-label="<?php esc_html_e('Sender', 'workreap_core'); ?>"><?php echo esc_html($sender);?></td>
																			<td data-label="<?php esc_html_e('Receiver', 'workreap_core'); ?>"><?php echo esc_html($receiver);?></td>
																			<td data-label="<?php esc_html_e('Actions', 'workreap_core'); ?>">
																				<a href="#" onclick="event_preventDefault(event);" class="lx-chatlist__viewbtn wt-ad wt-load-chat-data" data-msgid="<?php echo intval($message_id);?>" data-userid="<?php echo intval($sender_id);?>" data-currentid="<?php echo intval($receiver_id);?>"><?php esc_html_e('View', 'workreap_core'); ?></a>
																				<a href="#" onclick="event_preventDefault(event);" class="lx-chatlist__delbtn wt-delete-chat-data"  data-key="single" data-message_id="<?php echo intval($message_id);?>" data-userid="<?php echo intval($sender_id);?>" data-currentid="<?php echo intval($receiver_id);?>"><?php esc_html_e('Delete', 'workreap_core'); ?></a>
																			</td>
																		</tr>
																	<?php }}else{?>
																		<tr><td><?php esc_html_e('No messages found', 'workreap_core'); ?></td></tr>
																	<?php }?>
																</tbody>
															</table>
															
														</div>
														<?php 
															if(!empty($totalRecords) && $totalRecords > 1){
																$customPagHTML     =  '<nav class="wt-pagination"><span>'.esc_html__('Page','workreap_core').' '.$current_page.' '.esc_html__('of','workreap_core').' '.$totalRecords.'</span>'.paginate_links( array(
																'base' 		=> add_query_arg( 'current_page', '%#%' ),
																'format' 	=> '',
																'prev_text' => esc_html__('&laquo;','workreap_core'),
																'next_text' => esc_html__('&raquo;','workreap_core'),
																'total' 	=> $totalRecords,
																'current' 	=> $current_page
																)).'</nav>';
																
																
																echo do_shortcode( $customPagHTML );
															} 
															
																								
														?>
													</div>
												</div>
											</div>
										</div>
									<?php }?>
								<?php }else if( isset( $_GET['tab'] ) && $_GET['tab'] === 'settings' ){?>
									<div class="settings-wrap">
									<div class="wt-boxarea">
										<div id="tabone">
											<div class="wt-titlebox">
												<h3><?php esc_html_e('Rewrite URL', 'workreap_core'); ?></h3>
											</div>
											<form method="post" class="save-settings-form">
												<?php if( !empty( $post_types ) ){
													foreach ($post_types as $key => $post_type) {?>
													<div class="wt-privacysetting">
														<span class="wt-tooltipbox">
															<i>?</i>
															<span class="tooltiptext"><?php esc_html_e('It will be used at post / Taxonomy detail page in URL as slug. Please use words without spaces.', 'workreap_core'); ?></span>
														</span>
														<span><?php echo esc_attr($post_type->label);?></span>
														<div class="sp-input-setting">
															<div class="form-group">
																<input type="text" name="settings[post][<?php echo esc_attr( $key );?>]" class="form-control" value="<?php echo  !empty( $settings['post'][$key] ) ?  esc_attr( $settings['post'][$key] ) : '';?>">
															</div>
														</div>
													</div>
												<?php }}?>
												<?php if( !empty( $taxonomies ) ){ 
												foreach ($taxonomies as $key => $term) {?>
													<div class="wt-privacysetting">
														<span class="wt-tooltipbox">
															<i>?</i>
															<span class="tooltiptext"><?php esc_html_e('It will be used at post / Taxonomy detail page in URL as slug. Please use words without spaces.', 'workreap_core'); ?></span>
														</span>
														<span><?php echo esc_attr($term->label);?></span>
														<div class="sp-input-setting">
															<div class="form-group">
																<input type="text" name="settings[term][<?php echo esc_attr( $key );?>]" class="form-control" value="<?php echo  !empty( $settings['term'][$key] ) ?  esc_attr( $settings['term'][$key] ) : '';?>">
															</div>
														</div>
													</div>
												<?php }}?>

												<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary save-data-settings" value="<?php esc_html_e('Save Changes', 'workreap_core'); ?>"></p>
											</form>
										</div>
									</div>
								</div>
								<?php }else{?>
									<div class="wt-content">
										<div class="wt-boxarea">
											<div class="wt-title">
												<h3><?php esc_html_e('Minimum System Requirements', 'workreap_core'); ?></h3>
											</div>
											<div class="wt-contentbox">
												<ul class="wt-liststyle wt-dotliststyle wt-twocolumnslist">
													<li><?php esc_html_e('PHP version should be 7.4','workreap_core');?></li>
													<li><?php esc_html_e('PHP Zip extension Should','workreap_core');?></li>
													<li><?php esc_html_e('max_execution_time = 300','workreap_core');?></li>
													<li><?php esc_html_e('max_input_time = 300','workreap_core');?></li>
													<li><?php esc_html_e('Please note due to a lot of Theme Options, your server should have max_input_vars = 3000 minimum, otherwise your last tabs settings will not be saved','workreap_core');?></li>
													<li><?php esc_html_e('memory_limit = 300','workreap_core');?></li>
													<li><?php esc_html_e('post_max_size = 100M','workreap_core');?></li>
													<li><?php esc_html_e('upload_max_filesize = 100M','workreap_core');?></li>
													<li><?php esc_html_e('Node.js for real-time chat','workreap_core');?></li>
													<li><?php esc_html_e('allow_url_fopen and allow_url_include must be on','workreap_core');?></li>
												</ul>
											</div>
										</div>
									</div>
									<aside class="wt-sidebar">
										<div class="wt-widgetbox wt-widgetboxquicklinks">
											<div class="wt-title">
												<h3><?php esc_html_e('Video Tutorial', 'workreap_core'); ?></h3>
											</div>
											<figure>
												<div style="position:relative;height:0;padding-bottom:56.25%">
													<iframe width="640" height="360" src="https://www.youtube.com/embed/EgeOgt6nqcU?controls=0" frameborder="0" style="position:absolute;width:100%;height:100%;left:0" allowfullscreen></iframe>
												</div>
											</figure>
										</div>

										<div class="wt-widgetbox wt-widgetboxquicklinks">
											<div class="wt-title">
												<h3><?php esc_html_e('Get Support', 'workreap_core'); ?></h3>
											</div>
											<a class="wt-btn" target="_blank" href="https://amentotech.ticksy.com/"><?php esc_html_e('Create support ticket', 'workreap_core'); ?></a>
										</div>
									</aside>
								<?php }?>	
                                </div>
                                <div class="wt-socialandcopyright">
                                    <span class="wt-copyright"><?php echo date('Y'); ?>&nbsp;<?php esc_html_e('All Rights Reserved', 'workreap_core'); ?> &copy; <a target="_blank"  href="https://themeforest.net/user/amentotech/"><?php esc_html_e('Amentotech', 'workreap_core'); ?></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if( isset( $_GET['tab'] ) && $_GET['tab'] === 'chat' ){?>
                    <section class="wt-haslayout am-chat-module chat-load-data-wrapper">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
							<div class="wt-dashboardbox wt-messages-holder">
								<div class="wt-dashboardboxtitle wt-titlemessages chat-current-user"></div>
								<div class="wt-dashboardboxcontent wt-dashboardholder wt-offersmessages">
									<ul>
										<li>
											<div class="wt-chatarea load-wt-chat-message">
												<div class="wt-chatarea wt-chatarea-empty">
													<figure class="wt-chatemptyimg">
														<img src="<?php echo esc_url(get_template_directory_uri() . '/images/message-img.png'); ?>" alt="<?php esc_attr_e('No Message Selected', 'workreap_core'); ?>">
														
													</figure>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</section>
						<script type="text/template" id="tmpl-load-chat-replybox">
							<div class="wt-messages wt-verticalscrollbar wt-dashboardscrollbar chat-history-wrap"></div>
						</script>
						<script type="text/template" id="tmpl-load-chat-messagebox">
							<# if( !_.isEmpty(data.chat_nodes) ) { #>
							<# console.log(data.chat_nodes);
							_.each( data.chat_nodes , function( element, index ) { 
								var chat_class = 'wt-offerermessage wt-msg-thread';
								if(element.chat_is_sender === 'yes'){
									chat_class = 'wt-memessage wt-readmessage wt-msg-thread';
								}

								load_message	= element.chat_message;
							#>
							<div class="{{chat_class}}" data-id="{{element.chat_id}}">
								<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
								<div class="wt-description">
									<a href="#" onclick="event_preventDefault(event);" class="wt-delete-chat-data"  data-key="single" data-message_id="{{element.chat_id}}" data-userid="{{element.chat_receiver_id}}" data-currentid="{{element.chat_current_user_id}}"><span class="chat-delete-message">x</span></a>
									
									<p>{{load_message}}</p>
									<div class="clearfix"></div>
									<time datetime="2017-08-08">{{element.chat_date}}</time>
									<div class="clearfix"></div>
								</div>
							</div>
							<# }); #>
							<# } #>
						</script>
						<script type="text/template" id="tmpl-load-user-details">
							<div class="wt-userlogedin-sender">
								<figure class="wt-userimg">
									<img src="{{data.sender_image}}" alt="{{data.sender_name}}">
								</figure>
								<div class="wt-username">
									<h3>{{data.sender_name}}</h3>
									<span><?php esc_html_e('Sender', 'workreap_core'); ?></span>
								</div>
							</div>
							<div class="wt-userlogedin-receiver">
								<figure class="wt-userimg">
									<img src="{{data.receiver_image}}" alt="{{data.receiver_name}}">
								</figure>
								<div class="wt-username">
									<h3>{{data.receiver_name}}</h3>
									<span><?php esc_html_e('Receiver', 'workreap_core'); ?></span>
								</div>
							</div>
						</script>
					<?php }?>
				</div>
			</div>
        </div>
        <?php
        echo ob_get_clean();
    }
}
