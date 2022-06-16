<?php
if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'email_settings' => array(
		'type' => 'tab',
		'title' => esc_html__( 'Email Settings', 'workreap' ),
		'options' => array(
			'email_general_settings' => array(
				'title' => esc_html__( 'Email Settings', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'email_logo' => array(
						'type' => 'upload',
						'label' => esc_html__( 'Email Logo', 'workreap' ),
						'desc' => esc_html__( 'Upload your email logo here.', 'workreap' ),
						'images_only' => true,
						'files_ext' => array( 'png', 'jpg', 'jpeg', 'gif' ),
						'extra_mime_types' => array( 'audio/x-aiff, aif aiff' )
					),
					'logo_email_x' => array(
						'type' => 'slider',
						'value' => 100,
						'properties' => array(
							'min' => 20,
							'max' => 500,
							'sep' => 5,
						),
						'label' => esc_html__( 'Logo width', 'workreap' ),
						'desc' => esc_html__( 'Please select logo width, leave it empty to use default', 'workreap' ),
					),
					'email_content_width' => array(
						'type' => 'slider',
						'value' => 600,
						'properties' => array(
							'min' => 300,
							'max' => 1500,
							'sep' => 5,
						),
						'label' => esc_html__( 'Email content width', 'workreap' ),
						'desc' => esc_html__( 'Please select email content width, leave it empty to use default which is 600', 'workreap' ),
					),
					'email_banner' => array(
						'type' => 'upload',
						'label' => esc_html__( 'Email Banner', 'workreap' ),
						'desc' => esc_html__( 'Upload your email banner here.', 'workreap' ),
						'images_only' => true,
						'files_ext' => array( 'png', 'jpg', 'jpeg', 'gif' ),
						'extra_mime_types' => array( 'audio/x-aiff, aif aiff' )
					),
					'email_sender_avatar' => array(
						'type' => 'upload',
						'label' => esc_html__( 'Email Sender Avatar', 'workreap' ),
						'desc' => esc_html__( 'Upload email sender picture here.', 'workreap' ),
						'images_only' => true,
						'files_ext' => array( 'png', 'jpg', 'jpeg', 'gif' ),
						'extra_mime_types' => array( 'audio/x-aiff, aif aiff' )
					),
					'email_sender_name' => array(
						'type' => 'text',
						'label' => esc_html__( 'Email Sender Name', 'workreap' ),
						'desc' => esc_html__( 'Add email sender name here like: Shawn Biyeam. Default your site name will be used.', 'workreap' ),
					),
					'email_sender_tagline' => array(
						'type' => 'text',
						'label' => esc_html__( 'Email Sender Tagline', 'workreap' ),
						'desc' => esc_html__( 'Add email sender tagline here like: Team Workreap. Default your site tagline will be used.', 'workreap' ),
					),
					'email_sender_url' => array(
						'type' => 'text',
						'label' => esc_html__( 'Email Sender URL', 'workreap' ),
						'desc' => esc_html__( 'Add email sender url here.', 'workreap' ),
					),
					'regards'  => array(
						'label' => esc_html__( 'Show regads', 'workreap' ),
						'type'  => 'select',
						'value' => 'yes',
						'desc' => esc_html__('Show regards word in the email signature', 'workreap'),
						'choices'	=> array(
							'no'  	=> esc_html__('No', 'workreap'),
							'yes'	=> esc_html__('Yes', 'workreap')
						)
					),
					'footer_bg_color' => array(
						'type' => 'color-picker',
						'value' => '#ff5851',
						'attr' => array(),
						'label' => esc_html__( 'Footer background color', 'workreap' ),
						'desc' => esc_html__( 'Add email footer background color', 'workreap' ),
						'help' => esc_html__( '', 'workreap' ),
					),
					'footer_text_color' => array(
						'type' => 'color-picker',
						'value' => '#FFF',
						'attr' => array(),
						'label' => esc_html__( 'Footer text color', 'workreap' ),
						'desc' => esc_html__( 'Add email footer text color', 'workreap' ),
						'help' => esc_html__( '', 'workreap' ),
					),
				)
			),
			'general_templates' => array(
				'title' => esc_html__( 'General Templates', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'freelancers' => array(
						'title' => esc_html__( 'Registration', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'freelancer_email' => array(
								'title' => esc_html__( 'Freelancer email', 'workreap' ),
								'type' => 'tab',
								'options' => array(
									'cus_regis_email' => array(
										'type' => 'html',
										'html' => esc_html__( 'Email template for freelancers', 'workreap' ),
										'label' => esc_html__( '', 'workreap' ),
										'desc' => esc_html__( 'This email will be sent to new registered freelancers', 'workreap' ),
										'help' => esc_html__( '', 'workreap' ),
										'images_only' => true,
									),
									'freelancers_subject' => array(
										'type' => 'text',
										'value' => 'Thank you for registering!',
										'label' => esc_html__( 'Subject', 'workreap' ),
										'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
									),
									'freelancers_info' => array(
										'type' => 'html',
										'value' => '',
										'label' => esc_html__( 'Email Settings variables', 'workreap' ),
										'html' => '%name% — To display the freelancer name. <br/>
											%email% — To display the freelancer email address.<br/>
											%password% — To display the password for login.<br/>
											%phone% — To display the phone number if enabled in registration.<br/>
											%site% — To display the site name.<br/>
											%signature% — To display site logo.<br/>',
									),
									'freelancers_content' => array(
										'type' => 'wp-editor',
										'value' => 'Hello %name%!<br/>
										
												Thanks for registering at %site%. You can now login to manage your account using the following credentials:<br/>
												Email: %email%<br/>
												Password: %password%<br/><br/>
												%signature%
										',
										'label' => esc_html__( 'Email Contents', 'workreap' ),
										'size' => 'large', // small, large
										'editor_height' => 400,
									),
								)
							),
							'employer_email' => array(
								'title' => esc_html__( 'Employer email', 'workreap' ),
								'type' => 'tab',
								'options' => array(
									'employer_email' => array(
										'type' => 'html',
										'html' => esc_html__( 'This email template will be used for the employers registration', 'workreap' ),
										'desc' => esc_html__( 'This email will be sent to new registered employers.', 'workreap' ),
										'help' => esc_html__( '', 'workreap' ),
										'images_only' => true,
									),
									'employer_subject' => array(
										'type' => 'text',
										'value' => 'Thank you for registering!',
										'label' => esc_html__( 'Subject', 'workreap' ),
										'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
									),
									'employer_info' => array(
										'type' => 'html',
										'value' => '',
										'label' => esc_html__( 'Email Settings variables', 'workreap' ),
										'html' => '%name% — To display the freelancer name. <br/>
											%email% — To display the freelancer email address.<br/>
											%password% — To display the password for login.<br/>
											%phone% — To display the phone number if enabled in registration.<br/>
											%site% — To display the site name.<br/>
											%signature% — To display site logo.<br/>',
									),
									'employer_content' => array(
										'type' => 'wp-editor',
										'value' => 'Hello %name%!<br/>
										
												Thanks for registering at %site%. You can now login to manage your account using the following credentials:<br/>
												Email: %email%<br/>
												Password: %password%<br/><br/>
												%signature%',
										'label' => esc_html__( 'Email Contents', 'workreap' ),
										'size' => 'large', // small, large
										'editor_height' => 400,
									)
								)
							)
						)
					),
					'verify_code' => array(
						'title' => esc_html__( 'Verification link', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'user_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Email template for user verifcation code', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to new registered users', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'verify_subject' => array(
								'type' => 'text',
								'value' => 'Email Verification',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'user_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'html' => '%name% — To display the user name. <br/>
											%email% — To display the user email address.<br/>
											%verification_link% — To display the verification link.<br/>
											%phone% — To display the phone number if enabled in registration.<br/>
											%site% — To display the site name.<br/>
											%signature% — To display site logo.<br/>',
							),
							'verify_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%!<br/>

								Your account has created on %site%. Verification is required, To verify your account please click below link:<br> 
								<a href="%verification_link%">Verify Now</a><br/>

								%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							),
						),
					),
					'account_approve' => array(
						'title' => esc_html__( 'Approve account', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'user_approve' => array(
								'type' => 'text',
								'value' => 'Account Approved',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for account approval.', 'workreap' ),
							),
							'ap_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings', 'workreap' ), 'html' => '%name% — To display the person\'s name. <br/>
								%site_url% — To display the lost password link.<br/>
								%signature% — To display site logo.<br/>',
							),
							'user_approve_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%<br/>
											Your account has been approved. You can now login to setup your profile.

											<a href="%site_url%">Sign In</a>

											%signature%',
								'label' => esc_html__( 'Lost Password?', 'workreap' ), 'size' => 'large',
								'editor_height' => 400,
							)
						),
					),
					'lp_email' => array(
						'title' => esc_html__( 'Lost password', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'lp_subject' => array(
								'type' => 'text',
								'value' => 'Forgot Password',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for lost password.', 'workreap' ),
							),
							'lp_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings', 'workreap' ), 'html' => '%name% — To display the person\'s name. <br/>
								%link% — To display the lost password link.<br/>
								%account_email% — To display user email address.<br/>
								%signature% — To display site logo.<br/>',
							),
							'lp_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %name%!<br/>

											<p><strong>Lost Password reset</strong></p>
											<p>Someone requested to reset the password of following account:</p>
											<p>Email Address: %account_email%</p>
											<p>If this was a mistake, just ignore this email and nothing will happen.</p>
											<p>To reset your password, click reset link below:</p>
											<p><a href="%link%">Reset</a></p><br />
											%signature%
											',
								'label' => esc_html__( 'Lost Password?', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						),
					),
					'rec_chat_notify' => array(
						'title' => esc_html__( 'Receiver chat notifications', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'receiver_chat_notify' => array(
								'type' => 'switch',
								'value' => 'disable',
								'attr' => array(),
								'label' => esc_html__( 'Receiver chat notifications', 'workreap' ),
								'desc' => esc_html__( 'Enable/Disable receiver chat notifications. If enabled message email will be sent to the receiver.', 'workreap' ),
								'left-choice' => array(
									'value' => 'disable',
									'label' => esc_html__( 'Disable', 'workreap' ),
								),
								'right-choice' => array(
									'value' => 'enable',
									'label' => esc_html__( 'Enable', 'workreap' ),
								),
							),
							'rec_chat_subject' => array(
								'type' => 'text',
								'value' => 'New message received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for receiver chat notifications.', 'workreap' ),
							),
							'rec_chat_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings', 'workreap' ), 'html' => '%username% — To display message receiver name. <br/>
								%sender_name% — To display sender name.<br/>
								%message% — To display message.<br/>
								%signature% — To display site logo.<br/>',
							),
							'rec_chat_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %username%!<br/>

											<p>You have received a new message from %sender_name%, below is the message</p>
											<p>%message%</p>
											%signature%
											',
								'label' => esc_html__( 'Receiver chat message content', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 200,
							)
						),
					),
					'disput_user_notify' => array(
						'title' => esc_html__( 'Dispute notify', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'dispute_hint' => array(
								'type' => 'html',
								'html' => esc_html__( 'Notify to user for the dispute', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'Notify to the user when a dispute is created.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'dispute_user_subject' => array(
								'type' => 'text',
								'value' => 'A dispute against you, has been created',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for dispute to inform the user', 'workreap' ),
							),
							'dispute_user_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings', 'workreap' ), 'html' => '%dispute_author% — To display the name of user who created a dispute. <br/>
								%dispute_against% — To display the name of user against the dispute has created.<br/>
								%message% — To display message which the author of dispute has submitted.<br/>
								%project_link% — To display the link of project/service<br/>
								%project_title% — To display project/service title<br/>
											
								%signature% — To display site logo.<br/>',
							),
							'dispute_user_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %dispute_against%!<br/>

											<p>A new dispute for the project/service(%project_title%) has been submitted by %dispute_author%. We will now check this and update you on it.</p>
											
											Message from the user is given below<br/>
											<p>%message%</p>
											%signature%
											',
								'label' => esc_html__( 'Receiver chat message content', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 200,
							)
						),
					),
					'history_admin_feedback' => array(
						'title' => esc_html__( 'Admin feedback', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_feedback_hint' => array(
								'type' => 'html',
								'html' => esc_html__( 'Project or service history admin feedback', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'Admin can add his feedback into the ongoing, cancelled or completed projects and services. On add some comments both user will get an email that admin has left a feedback. To add comments you can go to Service Orders > Edit any order and then go to comment area. For the projects, Workreap > Proposal > Edit any proposal and then go to comment area', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'admin_feedback_subject' => array(
								'type' => 'text',
								'value' => 'Admin feedback received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for admin feedback to inform the both users', 'workreap' ),
							),
							'admin_feedback_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings', 'workreap' ), 'html' => '%username% — To display the name of user who created a dispute. <br/>
								%feedback% — To display message which will sent to both users.<br/>
								%link% — To display the link of project/service<br/>
								%title% — To display project/service title<br/>	
								%signature% — To display site logo.<br/>',
							),
							'admin_feedback_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %username%!<br/>
											<p>You have received the feedback from the admin on project/service(%title%). </p>
											Message from the user is given below<br/>
											<p>%feedback%</p>
											%signature%
											',
								'label' => esc_html__( 'Feedback content', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 200,
							)
						),
					),
					'identity_verify_approve' => array(
						'title' => esc_html__( 'Approve identity verification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'identity_approve_subject' => array(
								'type' => 'text',
								'value' => 'Congratulation! your identity verification has been approved',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for identity verification.', 'workreap' ),
							),
							'identity_approve_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %user_name% — To display user who submit the identity verification<br/>
											%user_link% — To display the user link who send the identity verification<br/>
											%user_email% — To display the user email address who send the identity verification request<br/>
                                            %signature%',
							),
							'identity_approve_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %user_name%<br/>
                                            
											Congratulations!
											
											Your submitted documents for the identity verification has been approved.
											
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'identity_reject_verify' => array(
						'title' => esc_html__( 'Reject identity verification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'identity_reject_subject' => array(
								'type' => 'text',
								'value' => 'Your request for identity verification has rejected',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for identity verification rejection.', 'workreap' ),
							),
							'identity_reject_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => '%user_name% — To display user who submit the identity verification<br/>
											%user_link% — To display the user link who send the identity verification<br/>
											%user_email% — To display the user email address who send the identity verification request<br/>
											%admin_message% — To display admin rejection message to this user.<br/>
                                            %signature%',
							),
							'identity_reject_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %user_name%<br/>
										
                                            You uploaded document for identity verification has been rejected.
											
											%admin_message%
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
				)
			),
			'admin_templates' => array(
				'title' => esc_html__( 'Admin Templates', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'admin_email' => array(
						'title' => __( 'Admin email content - registration', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_email_section' => array(
								'type' => 'html',
								'html' => esc_html__( 'Admin Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to admin when new user register on your site.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
								'images_only' => true,
							),
							'admin_register_subject' => array(
								'type' => 'text',
								'value' => 'New Registration!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Add email subject.', 'workreap' ),
							),
							'admin_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%name% — To display new registered  user name. <br/>
									%email% — To display the email address of registered user.<br/>
									%signature% — To display site logo.<br/>',
							),
							'admin_register_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello!<br/>
									A new user "%name%" with email address "%email%" has registered on your website. Please login to check user detail.
									<br/>
									%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'delete_account' => array(
						'title' => esc_html__( 'Delete account', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'delete_hint' => array(
								'type' => 'html',
								'html' => esc_html__( 'Admin Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to admin when any user will delete their account.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'delete_subject' => array(
								'type' => 'text',
								'value' => 'Account Delete',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Add email subject.', 'workreap' ),
							),
							'delete_email' => array(
								'type' => 'text',
								'value' => 'info@domain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'delete_info' => array(
								'type' => 'html',
								'value' => '',
								'attr' => array(),
								'label' => __( 'Email Settings variables', 'workreap' ),
								'desc' => esc_html__( '', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
								'html' => '%reason% — Reason to leave  account.<br/>
								%username% — Username of deleted user.<br/>
								%email% — Email address of deleted users.<br/>
								%message% — message of deleted user.<br/>
								%signature% — To display site logo.<br/>',
							),
							'delete_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi,<br/>

											An existing user has deleted account due to following reason: 
											<br/>
											%reason%
											<br/><br/>
                                            %signature%,<br/>',
								'attr' => array(),
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'desc' => esc_html__( '', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'report_employer' => array(
						'title' => esc_html__( 'Report employer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'report_emp_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'report_emp_subject' => array(
								'type' => 'text',
								'value' => 'Employer Reported',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'report_emp_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %reported_employer% — To display the employer name who has been reported. <br/>
											%reported_by% — To display the name who reported. <br/>
											%employer_link% — To display the link of employer edit page. <br/>
											%user_link% — To display the link of user who report<br/>
											%reported_title% — To display report title<br/>
											%message% — To display message of user.<br/>
											%signature%',
							),
							'report_emp_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            An employer "%reported_employer%" has been reported by %reported_by%<br/>
                                            Message is given below. <br/>
                                            %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'report_project' => array(
						'title' => esc_html__( 'Report project', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'report_pro_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'report_pro_subject' => array(
								'type' => 'text',
								'value' => 'Project Reported',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'report_pro_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => __( 'Email Settings variables', 'workreap' ), 'html' => ' %reported_project% — To display the project name which is reported. <br/>
											%reported_by% — To display the name who reported. <br/>
											%project_link% — To display the link of project edit page. <br/>
											%user_link% — To display the link of user who report<br/>
											%reported_title% — To display report title<br/>
											%message% — To display message of user.<br/>
											%signature%',
							),
							'report_pro_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            A project "%reported_project%" has been reported by %reported_by%<br/>
                                            Message is given below. <br/>
                                            %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'report_service' => array(
						'title' => esc_html__( 'Report service', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'report_serv_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'report_serv_subject' => array(
								'type' => 'text',
								'value' => 'Service Reported',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'report_serv_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %reported_service% — To display the service name which is reported. <br/>
											%reported_by% — To display the name who reported. <br/>
											%project_link% — To display the link of project edit page. <br/>
											%user_link% — To display the link of user who report<br/>
											%reported_title% — To display report title<br/>
											%message% — To display message of user.<br/>
											%signature%',
							),
							'report_serv_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            A Service "%reported_service%" has been reported by %reported_by%<br/>
                                            Message is given below. <br/>
                                            %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'report_freelancer' => array(
						'title' => esc_html__( 'Report freelancer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'report_fre_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'report_fre_subject' => array(
								'type' => 'text',
								'value' => 'A freelancer has reported!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'report_fre_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %reported_freelancer% — To display the freelancer name who has been reported. <br/>
											%reported_by% — To display the name who reported. <br/>
											%freelancer_link% — To display the link of freelancer edit page. <br/>
											%user_link% — To display the link of user who report<br/>
											%reported_title% — To display report title
											%message% — To display message of user.<br/>
											%signature%',
							),
							'report_fre_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            A freelancer "%reported_freelancer%" has been reported by "%reported_by%"<br/>
                                            Message is given below. <br/>
                                            %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_post_admin' => array(
						'title' => esc_html__( 'Service posted', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_service_post_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_service_post_subject' => array(
								'type' => 'text',
								'value' => 'New service Posted',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'admin_service_post_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
							'html' => ' %freelancer_name% — To display the freelancer name who posted the new service. <br/>
                                            %freelancer_link% — To display the freelancer profile link. <br/>
                                            %service_title%  — To display the service title. <br/>
											%status% — To display the service status. <br/>
											%service_link% — To display the service link. <br/>
											%signature%',
							),
							'admin_service_post_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            A new service is posted by <a href="%freelancer_link%">%freelancer_name%</a>.<br/>
                                            Click to view the service link. <a href="%service_link%" target="_blank">%service_title%</a><br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_post_admin' => array(
						'title' => esc_html__( 'Job posted', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_job_post_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_job_post_subject' => array(
								'type' => 'text',
								'value' => 'New Job Posted',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'admin_job_post_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %employer_name% — To display the employer name who posted the new job. <br/>
                                            %employer_link% — To display the employer profile link. <br/>
                                            %job_title%  — To display the job title. <br/>
											%job_link% — To display the job link. <br/>
											%status% — To display the job status. <br/>
											%signature%',
							),
							'admin_job_post_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            A new job is posted by <a href="%employer_link%">%employer_name%</a>.<br/>
                                            Click to view the job link. <a href="%job_link%" target="_blank">%job_title%</a><br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_completed_admin' => array(
						'title' => esc_html__( 'Job completed', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_job_complete_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_job_complete_subject' => array(
								'type' => 'text',
								'value' => 'Job Completed',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for job complete.', 'workreap' ),
							),
							'admin_job_complete_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% — To display freelancer name<br/>
                                            %project_title% — To display project title<br/>
                                            %signature%',
							),
							'admin_job_complete_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello Admin<br/>
                                            The <a href=" %freelancer_link%">%freelancer_name%</a> has completed the following project (<a href="%project_link%">%project_title%</a>).<br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_completed_admin' => array(
						'title' => esc_html__( 'Service completed', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_service_complete_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_service_complete_subject' => array(
								'type' => 'text',
								'value' => 'Service has completed',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for service complete.', 'workreap' ),
							),
							'admin_service_complete_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
											%freelancer_name% — To display freelancer name<br/>
                                            %service_link% — To display the link of service<br/>
                                            %service_title% — To display service title<br/>
                                            %signature%',
							),
							'admin_service_complete_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello Admin<br/>
                                            The <a href=" %freelancer_link%">%freelancer_name%</a> has completed the following service (<a href="%service_link%">%service_title%</a>).<br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'contact_admin' => array(
						'title' => esc_html__( 'Help and support', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'help_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'help_subject' => array(
								'type' => 'text',
								'value' => 'Help & Support',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for help.', 'workreap' ),
							),
							'help_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %query_type% — To display query type<br/>
                                            %message% — To display the message<br/>
											%user_from% — To display the user link and name<br/>
                                            %signature%',
							),
							'help_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello Admin<br/>
                                            You have received a new query from the %user_from% <br/>
											Subject : %query_type%<br/>
											Message : %message%<br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'dispute_admin' => array(
						'title' => esc_html__( 'Dispute', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'dispute_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'dispute_subject' => array(
								'type' => 'text',
								'value' => 'You have received a new dispute',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for dispute.', 'workreap' ),
							),
							'dispute_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'dispute' => esc_html__( '', 'workreap' ),
								'html' => ' %user_name% — To display user who submit the dispute<br/>
											%user_link% — To display the user link who send the dispute<br/>
											%project_link% — To display project/service link<br/>
											%project_title% — To display project/service title<br/>
                                            %message% — To display the dispute message<br/>
											%dispute_subject% — To display dispute title<br/>
                                            %signature%',
							),
							'dispute_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello Admin<br/>
                                            You have received a new dispute from the %user_name%, detail is given below<br/>
											Subject : %dispute_subject%<br/>
											Message : %message%<br/>
											Project Link : %project_link%<br/>
											Project Title : %project_title%<br/>
											
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'identity_verify' => array(
						'title' => esc_html__( 'Identity verification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'identity_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'identity_subject' => array(
								'type' => 'text',
								'value' => 'You have received a new identity verification request',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for identity verification.', 'workreap' ),
							),
							'identity_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %user_name% — To display user who submit the identity verification<br/>
											%user_link% — To display the user link who send the identity verification<br/>
											%user_email% — To display the user email address who send the identity verification request<br/>
                                            %signature%',
							),
							'identity_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            You have received a new identity verification from the %user_name%br/>
											
											You can click <a href="%user_link%">this link</a> to verify this user identity
											
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'withdraw_request' => array(
						'title' => esc_html__( 'Withdrawal Request', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'withdraw_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'withdraw_subject' => array(
								'type' => 'text',
								'value' => 'New withdrawal request has received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for identity verification.', 'workreap' ),
							),
							'withdraw_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %user_name% — To display freelancer name who submit the withdrawal request<br/>
											%user_link% — To display the freelancer link who sends the withdrawal request<br/>
											%amount% — To display the withdrawal amount<br/>
											%detail% — To view the withdrawal request<br/>
                                            %signature%',
							),
							'withdraw_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>
                                            You have received a new withdrawal request from the %user_name% <br/>
											You can click <a href="%detail%">this link</a> to view the withdrawal details
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'contact_to_admin' => array(
						'title' => esc_html__( 'Contact to admin', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_contact_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => __( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'admin_contact_subject' => array(
								'type' => 'text',
								'value' => 'Contact to admin',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject.', 'workreap' ),
							),
							'withdraw_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %name% — To display freelancer name who submit the withdrawal request<br/>
											%email% — To display the freelancer link who sends the withdrawal request<br/>
											%description% — To view the withdrawal request<br/>
                                            %signature%',
							),
							'admin_contact_description' => array(
								'type' => 'wp-editor',
								'value' => 'Hello,<br/>A user "%name%" with the email address "%email%" has sent you a message.<br/>The message details is :<br/>"%description%"<br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'status_request' => array(
						'title' => esc_html__( 'Status update', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'status_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => esc_html__( 'Admin email address', 'workreap' ),
								'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'workreap' ),
							),
							'status_subject' => array(
								'type' => 'text',
								'value' => 'New status changed request has received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for identity verification.', 'workreap' ),
							),

							'status' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer profile status changed', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'Freelancer profiles status get pending once any changes happens. Admin will get an email to review and publish it from the back-end', 'workreap' ),
							),
							'status_info_freelancer' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables for freelancer profile', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %name% — To display freelancer name<br/>
											%post_link% — To display post and change status page<br/>
                                            %signature%',
							),
							'status_content_freelancer' => array(
								'type' => 'wp-editor',
								'value' => 'Hello, <br>
											Freelancer has made the changes in the profile. It is pending for review. Please review and publish it<br>
											To view the post please click below link. <br>
											<a href="%post_link%">View</a><br>
											Thanks ',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
							),

							'status_project' => array(
								'type' => 'html',
								'html' => esc_html__( 'Project status changed', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'Projects status get pending once any changes happens. Admin will get an email to review and publish it from the back-end', 'workreap' ),
							),
							'status_info_project' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables for project', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %name% — To display project name<br/>
											%post_link% — To display post and change status page<br/>
                                            %signature%',
							),
							'status_content_project' => array(
								'type' => 'wp-editor',
								'value' => 'Hello, <br>
											Employer has made the changes in the project, It is pending for review. Please review and publish it.<br>
											To view the post please click below link. <br>
											<a href="%post_link%">View</a><br>
											Thanks ',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
							),

							'status_service' => array(
								'type' => 'html',
								'html' => esc_html__( 'Service status changed', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'services status get pending once any changes happens. Admin will get an email to review and publish it from the back-end', 'workreap' ),
							),
							'status_info_service' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables for service', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => ' %name% — To display service name<br/>
											%post_link% — To display post and change status page<br/>
                                            %signature%',
							),
							'status_content_service' => array(
								'type' => 'wp-editor',
								'value' => 'Hello, <br>
											Freelancer has made the changes in the service. It is pending for review. Please review and publish it<br>
											To view the post please click below link. <br>
											<a href="%post_link%">View</a><br>
											Thanks ',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
							)
						)
					),
				)
			),

			//testing
			'service_templates' => array(
				'title' => esc_html__( 'Service Templates', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'service_post_freelancer' => array(
						'title' => esc_html__( 'Service Posted', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'freelancer_service_post_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when new service posted by freelancer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'freelancer_service_post_subject' => array(
								'type' => 'text',
								'value' => 'Congratulations! Your service Has Posted',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'freelancer_service_post_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_name% — To display the freelancer name who posted the new service. <br/>
                                            %freelancer_link% — To display the freelancer profile link.<br/> 
                                            %service_title% — To display the service title. <br/>
											%service_link% — To display the service link. <br/>
											%signature%',
							),
							'freelancer_service_post_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%,<br/>
                                            Congratulation! Your service has been posted.<br/>
                                            Click below link to view the service. <a href="%service_link%" target="_blank">%service_title%</a><br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'admin_rejected_service' => array(
						'title' => esc_html__( 'Reject service from admin', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_service_reject_subject' => array(
								'type' => 'text',
								'value' => 'Your request service has rejected',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for service rejection.', 'workreap' ),
							),
							'admin_service_rejected_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => '%user_name% — To display freelancer name<br/>
											%user_link% — To display the user link freelancer<br/>
											%user_email% — To display the user email address freelancer<br/>
											%service_title% — To display the service title<br/>
											%service_link% — To display the service link<br/>
											%admin_message% — To display admin rejection message to this user.<br/>
                                            %signature%',
							),
							'admin_service_rejected_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %user_name%<br/>
											Your service has rejected.
											
											%admin_message%
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'purchase_service' => array(
						'title' => esc_html__( 'Service purchased', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'service_buy_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when freelancer service will be purchased.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'service_buy_subject' => array(
								'type' => 'text',
								'value' => 'New Order has been received!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'service_buy_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
											%freelancer_name% - To display freelancer name<br/>
											%employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_link% — To display the link of service<br/>
                                            %service_title% - To display service title<br/>
											%service_price% - To display service price<br/>
                                            %signature%',
							),
							'service_buy_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/>
											Congratulations!<br/>
											You have received new order for the following service <a href="%service_link%">%service_title%</a> by the employer <a href="%employer_link%">%employer_name%</a><br/>
											%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							),

							'service_employer_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when service will be purchased.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'service_buy_subject_employer' => array(
								'type' => 'text',
								'value' => 'Service order confirmation',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'service_buy_info_employer' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
											%freelancer_name% - To display freelancer name<br/>
											%employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_link% — To display the link of service<br/>
                                            %service_title% - To display service title<br/>
											%service_price% - To display service price<br/>
                                            %signature%',
							),
							'service_buy_content_employer' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%<br/>
											
											Thank you for the order my service <a href="%service_link%">%service_title%</a><br/>
											%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_completed_freelancer' => array(
						'title' => esc_html__( 'Service completed', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_service_complete_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer from employer when the service is complete.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_service_complete_subject' => array(
								'type' => 'text',
								'value' => 'Service Complete',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for service complete.', 'workreap' ),
							),
							'frl_service_complete_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of service<br/>
                                            %freelancer_name% — To display freelancer name<br/>
                                            %employer_name% — To display employer name<br/>
                                            %employer_link% — To display employer profile<br/>
                                            %service_title% — To display service title<br/>
                                            %ratings% — To display the ratings<br/>
                                            %message% — To display info about complete service.<br/>
                                            %signature%',
							),
							'frl_service_complete_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/>
                                            The <a href=" %employer_link%">%employer_name%</a> has confirmed the following service (<a href="%service_link%">%service_title%</a>) is completed.<br/>
                                            You have received the following ratings from employer<br/>
                                            Message: %message% <br/>
											Rating: %ratings% <br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_cancel_freelancer' => array(
						'title' => esc_html__( 'Cancel service', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_cancel_service_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when service is cancelled by employer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_cancel_service_subject' => array(
								'type' => 'text',
								'value' => 'Service Cancelled',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'frl_cancel_service_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of service<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display service title<br/>
                                            %message% — To display info about cancel service.<br/>
                                            %signature%',
							),
							'frl_cancel_service_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello <a href="%freelancer_link%">%freelancer_name%</a>,<br/><br/>
                                            Unfortunately <a href=" %employer_link%">%employer_name%</a> cancelled the <a href="%service_link%">%service_title%</a> due to following below reasons.<br/>
                                            Job Cancel Reasons Below. <br/>
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_msg_freelancer' => array(
						'title' => esc_html__( 'Service message freelancer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_service_msg_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when service message submitted', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_service_msg_subject' => array(
								'type' => 'text',
								'value' => 'New Service Message Received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for service message.', 'workreap' ),
							),
							'frl_service_msg_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of the service<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display service title<br/>
                                            %message% — To display info about service.<br/>
                                            %signature%',
							),
							'frl_service_msg_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/><br/>
									You have received a new message!<br/><br/>
									The <a href=" %employer_link%">%employer_name%</a> has submitted a new message on this service <a href="%service_link%">%service_title%</a><br/><br/>
									Message: %message%<br/>
									%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_msg_employer' => array(
						'title' => esc_html__( 'Service message employer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_service_msg_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when Service message submitted', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
								'images_only' => true,
							),
							'emp_service_msg_subject' => array(
								'type' => 'text',
								'value' => 'New Message Received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for Service message.', 'workreap' ),
							),
							'emp_service_msg_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of the service<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display service title<br/>
                                            %message% — To display info about service.<br/>
                                            %signature%',
							),
							'emp_service_msg_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%<br/>
                                            <a href=" %freelancer_link%">%freelancer_name%</a> has send you a new message on this service <a href="%service_link%">%service_title%</a>.<br/>
                                            
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'service_approved' => array(
						'title' => esc_html__( 'Service approved', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'service_approved' => array(
								'type' => 'text',
								'value' => 'Your service has published!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for job approval by admin', 'workreap' ),
							),
							'service_approved_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),
								'html' => '%name% — To display the user name. <br/>
											%service_name% — To display the service name<br/>
											%link% — To display the service link<br/>
                                            %signature%',
							),
							'service_approved_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%
											
											Congratulations! 
											
											Your Service <a href="%link%">%service_name%</a> has been published.

											%signature%,',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					)
				)
			),
			'employer_templates' => array(
				'title' => esc_html__( 'Employer Templates', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'employer_proposal' => array(
						'title' => esc_html__( 'Proposal received', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_proposal_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when new proposal submitted by freelancer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'emp_proposal_subject' => array(
								'type' => 'text',
								'value' => 'A New Proposal Received!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'emp_proposal_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %employer_name% - To display freelancer name<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %proposal_amount% — To display the proposal amount<br/>
                                            %proposal_duration% — To display the proposal duration and will only be used for fixed project<br/>
                                            %message% — To display message of user.<br/>
                                            %signature%',
							),
							'emp_proposal_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%,<br/><br/>
                                            <a href="%freelancer_link%">%freelancer_name%</a> has sent a new proposal on the following project <a href="%project_link%">%project_title%</a>.<br/>
                                            Message is given below. <br/>
                                            Project Proposal Amount : %proposal_amount%<br/>
                                            Project Duration : %proposal_duration%<br/>
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							),
							'emp_proposal_email_hourly' => array(
								'type' => 'html',
								'html' => esc_html__( 'Hourly project', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email template will be sent to employer when new proposal submitted on the hourly project', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'emp_proposal_content_hourly' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%,<br/><br/>
                                            <a href="%freelancer_link%">%freelancer_name%</a> has sent a new proposal on the following project <a href="%project_link%">%project_title%</a>.<br/>
                                            Message is given below. <br/>
                                            Project Proposal Amount : %proposal_amount%<br/>
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_post_employer' => array(
						'title' => esc_html__( 'Job posted', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_job_post_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when new job posted by employer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'emp_job_post_subject' => array(
								'type' => 'text',
								'value' => 'Congratulations! Your Job Has Posted',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'emp_job_post_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %employer_name% — To display the employer name who posted the new job. <br/>
                                            %employer_link% — To display the employer profile link.<br/> 
                                            %job_title% — To display the job title. <br/>
											%job_link% — To display the job link. <br/>
											%signature%',
							),
							'emp_job_post_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%,<br/>
                                            Congratulation! Your job has been posted.<br/>
                                            Click below link to view the job. <a href="%job_link%" target="_blank">%job_title%</a><br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'admin_rejected_job' => array(
						'title' => esc_html__( 'Job rejection from admin', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'admin_job_reject_subject' => array(
								'type' => 'text',
								'value' => 'Your request job has rejected',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for job rejection.', 'workreap' ),
							),
							'admin_job_rejected_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),

								'identity verification' => esc_html__( '', 'workreap' ),
								'html' => '%user_name% — To display freelancer name<br/>
											%user_link% — To display the user link freelancer<br/>
											%user_email% — To display the user email address freelancer<br/>
											%job_title% — To display the job title<br/>
											%job_link% — To display the job link<br/>
											%admin_message% — To display admin rejection message to this user.<br/>
                                            %signature%',
							),
							'admin_job_rejected_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %user_name%<br/>
											Your project has rejected.
											
											%admin_message%
                                            <br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),

								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'proposal_msg_employer' => array(
						'title' => esc_html__( 'Proposal message', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_proposal_msg_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when proposal message submitted', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
								'images_only' => true,
							),
							'emp_proposal_msg_subject' => array(
								'type' => 'text',
								'value' => 'New Message Received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for proposal message.', 'workreap' ),
							),
							'emp_proposal_msg_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.<br/>
                                            %signature%',
							),
							'emp_proposal_msg_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%<br/>
                                            <a href=" %freelancer_link%">%freelancer_name%</a> has send you a new message on this job <a href="%project_link%">%project_title%</a>.<br/>
                                            
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'package_subscribe_employer' => array(
						'title' => esc_html__( 'Package subscription', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_package_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when package is purchased.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'emp_package_subject' => array(
								'type' => 'text',
								'value' => 'Thank you for purchasing the package!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for package subscribe.', 'workreap' ),
							),
							'emp_package_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),
								'html' => ' %package_name% — To display the package name. <br/>
											%invoice% — To display the invoice ID<br/>
											%amount% — To display the package amount<br/>
											%status% — To display the payment status<br/>
											%method% — To display the payment method<br/>
                                            %date% — To display the purchased date<br/>
                                            %expiry% — To display the package expiry<br/>
                                            %name% — To display employer name<br/>
                                            %link% — To display employer profile link<br/>
                                            %signature%',
							),
							'emp_package_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%
											Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

											Invoice ID: %invoice%
											Package Name: %package_name%
											Payment Amount: %amount%
											Payment status: %status%
											Payment Method: %method%
											Purchase Date: %date%
											Expiry Date: %expiry%

											%signature%,',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_approved' => array(
						'title' => esc_html__( 'Job approved', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'job_approved' => array(
								'type' => 'text',
								'value' => 'Your project has published!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for job approval by admin', 'workreap' ),
							),
							'job_approved_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ),
								'html' => ' %name% — To display the user name. <br/>
											%project_name% — To display the project name<br/>
											%link% — To display the project link<br/>
                                            %signature%',
							),
							'job_approved_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%
											
											Congratulations! 
											
											Your Project <a href="%link%">%project_name%</a> has been published.

											%signature%,',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'milestone_req_approved' => array(
						'title' => esc_html__( 'Milestone request approved', 'workreap' ),
						'type' => 'tab',
						'ml_note_2' => array(
							'type' => 'html',
							'html' => esc_html__( 'Milestone notification', 'workreap' ),
							'label' => esc_html__( '', 'workreap' ),
							'desc' => esc_html__( 'This email will be sent to employer when freelancer will accept the milestone request', 'workreap' ),
							'help' => esc_html__( '', 'workreap' ),
						),
						'options' => array(
							'ml_req_appr_subject' => array(
								'type' => 'text',
								'value' => 'Request for Milestones',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'ml_req_appr_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
                                            %freelancer_link% — To display the link of freelancer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %signature%',
							),
							'ml_req_appr_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%,<br/><br/>
                                            Your request for milestone on the project <a href="%project_link%">%project_title%</a> has been approved<br>
                                            by freelancer <a href="%freelancer_link%">%freelancer_name%</a>.
                                            Please login to see the details of milestone.<br/><br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'milestone_req_rejected' => array(
						'title' => esc_html__( 'Milestone request rejected', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'ml_note_3' => array(
								'type' => 'html',
								'html' => esc_html__( 'Milestone notification', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when freelancer will decline the milestone request', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'ml_req_rej_subject' => array(
								'type' => 'text',
								'value' => 'Milestone Request Declined',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'ml_req_rej_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
                                            %freelancer_link% — To display the link of freelancer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %reason% - To display the reason<br/>
                                            
                                            %signature%',
							),
							'ml_req_rej_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%,<br/><br/>
                                            Your request for milestone on the project <a href="%project_link%">%project_title%</a> has been rejected<br>
                                            by freelancer <a href="%freelancer_link%">%freelancer_name%</a>.
                                            Reason : %reason%
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'dispute_resolved' => array(
						'title' => esc_html__( 'Dispute resolved', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_dispute_subject' => array(
								'type' => 'text',
								'value' => 'Employer Dispute',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'emp_dispute_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%employer_name% — To display the employer name<br/>
                                            %dispute_raised_by% - To display raised by user name<br/>
											%admin_message% — To display the admin message<br/>
                                            %signature%',
							),
							'emp_dispute_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %employer_name%,<br/>
                                            We have reached out to you regarding a dispute that was raised by %dispute_raised_by%.<br/>
                                            %admin_message%<br/>
                                            Thanks <br/>
                                            %signature%<br/><br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'hire_freelancer_emp_email' => array(
						'title' => esc_html__( 'Freelancer hired', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'emp_hire_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Employer email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when any employer will hire the freelancer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'emp_hire_subject' => array(
								'type' => 'text',
								'value' => 'Hiring completed',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'emp_hire_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %signature%',
							),
							'emp_hire_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%<br/>
											Congratulations!<br/>
											You have hired the freelancer "%freelancer_name%" for the following job <a href="%project_link%">%project_title%</a><br/>
											%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),

					'send_offer' => array(
						'title' => esc_html__( 'Send offer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							
							'send_offer_subject' => array(
								'type' => 'text',
								'value' => 'New offer received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'send_offer_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_name% - To display service name<br/>
											%service_link% - To display service link<br/>
                                            %signature%',
							),
							'send_offer_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%

									You have received a new offer for the "%service_name%" from the freelancer "%freelancer_name%"
									
									You can accept or decline this
									
									Thank you',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
							)
						)
					),

					'update_offer' => array(
						'title' => esc_html__( 'Update offer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							
							'update_offer_subject' => array(
								'type' => 'text',
								'value' => 'Freelancer updated the offer',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'update_offer_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_name% - To display service name<br/>
											%service_link% - To display service link<br/>
                                            %signature%',
							),
							'update_offer_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %employer_name%

									Freelancer has updated the offer for the "%service_name%"
									
									You can accept or decline this
									
									Thank you',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
							)
						)
					),
				)
			),
			'freelancer_templates' => array(
				'title' => esc_html__( 'Freelancer Templates', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'proposal_submit_freelancer' => array(
						'title' => esc_html__( 'Proposal submit', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_proposal_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when new proposal submitted by freelancer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_proposal_subject' => array(
								'type' => 'text',
								'value' => 'New Proposal Submitted',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'frl_proposal_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %proposal_amount% — To display the proposal amount<br/>
                                            %proposal_duration% — To display the proposal time<br/>
                                            %message% — To display message of proposal.<br/>
                                            %signature%',
							),
							'frl_proposal_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello <a href="%freelancer_link%">%freelancer_name%</a>,<br/><br/>
                                            You have submitted the proposal against this job <a href="%project_link%">%project_title%</a>.
                                            Message is given below. <br/>
                                            Project Proposal Amount : %proposal_amount%<br/>
                                            Project Duration : %proposal_duration%<br/>
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'hire_freelancer' => array(
						'title' => esc_html__( 'Hire freelancer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_hire_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when freelancer hired.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_hire_subject' => array(
								'type' => 'text',
								'value' => 'Congratulations! You are hired!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'frl_hire_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %signature%',
							),
							'frl_hire_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/>
											Congratulations!<br/>
											You have hired for the following job <a href="%project_link%">%project_title%</a> by the employer <a href="%employer_link%">%employer_name%</a><br/>
											%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'send_offer_freelancer' => array(
						'title' => __( 'Send offer', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_sendoffer_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when offer send by the employer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_sendoffer_subject' => array(
								'type' => 'text',
								'value' => 'Offer received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'frl_sendoffer_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
											%project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
										    %employer_link% - To display employer profile<br/>
                                            %employer_name% - To display employer name<br/>
                                            %message% — To display info about cancel job.<br/>
                                            %signature%',
							),
							'frl_sendoffer_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/>
											You have new invitation from an employer<br/>
											<a href="%employer_link%">%employer_name%</a> would like to invite you to consider working on the following project <a href="%project_link%">%project_title%</a><br/>
											Message: %message%<br/><br/>
											%signature%,',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_cancel_freelancer' => array(
						'title' => esc_html__( 'Cancel Job', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_cancel_job_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when job is cancelled by employer.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_cancel_job_subject' => array(
								'type' => 'text',
								'value' => 'Job Cancelled',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'frl_cancel_job_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.<br/>
                                            %signature%',
							),
							'frl_cancel_job_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello <a href="%freelancer_link%">%freelancer_name%</a>,<br/><br/>
                                            Unfortunately <a href=" %employer_link%">%employer_name%</a> cancelled the <a href="%project_link%">%project_title%</a> due to following below reasons.<br/>
                                            Job Cancel Reasons Below. <br/>
                                            Message: %message%
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'proposal_msg_freelancer' => array(
						'title' => esc_html__( 'Proposal message', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_proposal_msg_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when proposal message submitted', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_proposal_msg_subject' => array(
								'type' => 'text',
								'value' => 'New Proposal Message Received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for proposal message.', 'workreap' ),
							),
							'frl_proposal_msg_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.<br/>
                                            %signature%',
							),
							'frl_proposal_msg_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/><br/>
									You have received a new message!<br/><br/>
									The <a href=" %employer_link%">%employer_name%</a> has submitted a new message on this job <a href="%project_link%">%project_title%</a><br/><br/>
									Message: %message%<br/>
									%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'package_subscribe_freelancer' => array(
						'title' => esc_html__( 'Package subscribe', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_package_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when package is purchased.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_package_subject' => array(
								'type' => 'text',
								'value' => 'Pakcage Purchased',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for package subscribe.', 'workreap' ),
							),
							'frl_package_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %package_name% — To display the package name. <br/>
											%invoice% — To display the invoice ID<br/>
											%amount% — To display the package amount<br/>
											%status% — To display the payment status<br/>
											%method% — To display the payment method<br/>
                                            %date% — To display the purchased date<br/>
                                            %expiry% — To display the package expiry<br/>
                                            %name% — To display freelancer name<br/>
                                            %link% — To display freelancer profile link<br/>
                                            %signature%',
							),
							'frl_package_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %name%
											Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

											Invoice ID: %invoice%
											Package Name: %package_name%
											Payment Amount: %amount%
											Payment status: %status%
											Payment Method: %method%
											Purchase Date: %date%
											Expiry Date: %expiry%

											%signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),

					'job_completed_freelancer' => array(
						'title' => esc_html__( 'Job completed', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'frl_job_complete_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Freelancer Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer from employer when the job is complete.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'frl_job_complete_subject' => array(
								'type' => 'text',
								'value' => 'Job Complete',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for job complete.', 'workreap' ),
							),
							'frl_job_complete_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% — To display freelancer name<br/>
                                            %employer_name% — To display employer name<br/>
                                            %employer_link% — To display employer profile<br/>
                                            %project_title% — To display project title<br/>
                                            %ratings% — To display the ratings<br/>
                                            %message% — To display info about cancel job.<br/>
                                            %signature%',
							),
							'frl_job_complete_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%<br/>
                                            The <a href=" %employer_link%">%employer_name%</a> has confirmed the following project (<a href="%project_link%">%project_title%</a>) is completed.<br/>
                                            You have received the following ratings from employer<br/>
                                            Message: %message% <br/>
											Rating: %ratings% <br/>
                                            <br/>
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'milestone_received' => array(
						'title' => esc_html__( 'Milestone notification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'ml_note_1' => array(
								'type' => 'html',
								'html' => esc_html__( 'Milestone notification', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to freelancer when freelancer will accept the milestone request from employer', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'ml_rec_subject' => array(
								'type' => 'text',
								'value' => 'Request for Milestones',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'ml_rec_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%project_link% — To display the link of project<br/>
                                            %employer_link% — To display the link of employer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %project_title% - To display project title<br/>
                                            %signature%',
							),
							'ml_rec_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%,<br/><br/>
                                            Employer <a href="%employer_link%">%employer_name%</a> has created milestones for the project <a href="%project_link%">%project_title%</a>. You can accept or reject the employer request for project.
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'hired_against_milestone' => array(
						'title' => esc_html__( 'Hired against milestone', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'hired_ml_subject' => array(
								'type' => 'text',
								'value' => 'Hired Against Milestone',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'hired_ml_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %milestone_title% - To display milestone title<br/>
                                            %signature%',
							),
							'hired_ml_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%,<br/><br/>

                                            You have been hired for the milestone <strong>%milestone_title%</strong> against the project <a href="%project_link%">%project_title%</a>.<br/>
                                            Please login to see the details of milestone.<br/><br/>
                                            
                                            %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'milestone_completed' => array(
						'title' => esc_html__( 'Milestone completed', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'ml_completed_subject' => array(
								'type' => 'text',
								'value' => 'Milestone Completed',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'ml_completed_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%milestone_title% — To display the link of miletone<br/>
                                            %freelancer_name% - To display freelancer name<br/>
											%project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
                                            %signature%',
							),
							'ml_completed_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%,<br/><br/>
                                            Congratulations!!<br/>
                                            Milestone %milestone_title% for the project <a href="%project_link%">%project_title%</a> has been completed!!<br/><br/>
                                            
                                            %signature%<br/><br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					
					'dispute_resolved' => array(
						'title' => esc_html__( 'Dispute resolved', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'fr_dispute_subject' => array(
								'type' => 'text',
								'value' => 'Freelancer Dispute',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'fr_dispute_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %dispute_raised_by% - To display raised by user name<br/>
											%admin_message% — To display the admin message<br/>
											%project_title% — To display the project/service title<br/>
                                            %signature%',
							),
							'fr_dispute_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %freelancer_name%,<br/>
                                            We have reached out to you regarding a dispute that was raised by %dispute_raised_by%.<br/>
                                            %admin_message%<br/>
                                            Thanks <br/>
                                            %signature%<br/><br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'earning_notify' => array(
						'title' => esc_html__( 'Earning notification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'fr_earning_subject' => array(
								'type' => 'text',
								'value' => 'Earning Notification',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'fr_earning_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %total_amount% - To display total amount<br/>
                                            %signature%',
							),
							'fr_earning_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %freelancer_name%,<br/>
                                            This is confirmation that your total earning has been calculated. <br/>
                                            Your payouts will be <strong>%total_amount%</strong><br/>
                                            You will be informed when your payouts will be processed.<br/>
                                            %signature%',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'payouts_notify' => array(
						'title' => esc_html__( 'Payout notification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'fr_payouts_subject' => array(
								'type' => 'text',
								'value' => 'Payout Notification',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'fr_payouts_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %total_amount% - To display total amount<br/>
                                            %signature%',
							),
							'fr_payouts_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %freelancer_name%,<br/>
                                            Congratulations!<br/>
                                            Your payouts has been processed. Your total payouts was <strong>%total_amount%</strong><br/>
                                            %signature%<br/><br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),

					'proposal_accept' => array(
						'title' => __( 'Proposal rejected', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'proposal_email' => array(
								'label' => esc_html__( 'Proposal rejected', 'workreap' ),
								'type' => 'switch',
								'value' => 'enable',
								'desc' => esc_html__( 'When employer will accept one of proposal then all other freelancers will get this email', 'workreap' ),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__( 'Enable', 'workreap' ),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__( 'Disable', 'workreap' ),
								),
							),
							'fr_proposal_subject' => array(
								'type' => 'text',
								'value' => 'Your proposal has been rejected',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Sorry, Your proposal has been rejected', 'workreap' ),
							),
							'fr_proposal_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => '%freelancer_name% — To display the freelancer name<br/>
											%freelancer_link% — To display the freelancer link<br/>
                                            %project_title% - To display project title<br/>
											%project_link% - To display project link<br/>
											%employer_name% - To display employer title<br/>
											%employer_link% - To display employer link<br/>
                                            %signature%',
							),
							'fr_proposal_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %freelancer_name%,<br/><br/>
                                            We are sorry, your proposal has been rejected<br/>
                                            Employer %employer_name% has hire other freelancer for the project %project_title% <br/>
											
											Try to bid on other project to get hired
											
                                            %signature%<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'job_cron_notifications' => array(
						'title' => __( 'Job notification', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'job_notification' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'label' => esc_html__( 'Enable Notification', 'workreap' ),
										'type' => 'switch',
										'value' => 'enable',
										'desc' => esc_html__( 'Job Notification to freelance enable/disable', 'workreap' ),
										'left-choice' => array(
											'value' => 'enable',
											'label' => esc_html__( 'Enable', 'workreap' ),
										),
										'right-choice' => array(
											'value' => 'disable',
											'label' => esc_html__( 'Disable', 'workreap' ),
										),
									)
								),
								'choices' => array(
									'enable' => array(
										'job_notification_email' => array(
											'type' => 'html',
											'html' => esc_html__( 'Job Noticification Email', 'workreap' ),
											'label' => esc_html__( '', 'workreap' ),
											'desc' => esc_html__( 'This email will be sent to freelancers on daily base when cron is run.', 'workreap' ),
											'help' => esc_html__( '', 'workreap' ),
										),
										'job_notification_subject' => array(
											'type' => 'text',
											'value' => 'New Jobs are Posted',
											'label' => esc_html__( 'Subject', 'workreap' ),
											'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
										),
										'job_notification_info' => array(
											'type' => 'html',
											'value' => '',
											'attr' => array(),
											'label' => esc_html__( 'Email Settings variables', 'workreap' ),

											'help' => esc_html__( '', 'workreap' ),
											'html' => ' %freelancer_name% — To display the link of freelancer Name. <br/>
														%jobs_listings% — To display the List of project<br/>
														%search_job_link% - To display Job search page link<br/>
														%signature%',
										),
										'job_notification_content' => array(
											'type' => 'wp-editor',
											'value' => 'Hello %freelancer_name%,<br/><br/>
														There are some new jobs posted matching your skills, You can visit our site for more informations.<br/>
														%jobs_listings%<br/>
														<a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block; font-family: \'Work Sans\', Arial, Helvetica, sans-serif;  text-decoration: none;" href="%search_job_link%">View All Jobs</a><br>
														%signature%,<br/>',
											'attr' => array(),
											'label' => esc_html__( 'Email Contents', 'workreap' ),

											'help' => esc_html__( '', 'workreap' ),
											'size' => 'large', // small, large
											'editor_height' => 400,
										)
									),
								),
							),
						),
					),
					'deleted_project_email' => array(
						'title' => __( 'Project deleted', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							
							'fr_delete_project_subject' => array(
								'type' => 'text',
								'value' => 'Project has been closed!',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'When employer will delete the project then email will goes to freelancers who sent the proposal on the job', 'workreap' ),
							),
							'fr_delete_project_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
								'html' => '%freelancer_name% — To display the freelancer name<br/>
											%freelancer_link% — To display the freelancer link<br/>
                                            %project_title% - To display project title<br/>
											%employer_name% - To display employer title<br/>
											%employer_link% - To display employer link<br/>
                                            %signature%',
							),
							'fr_delete_project_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi %freelancer_name%,<br/>
                                            Employer has closed the project with the name "%project_title%", your submission on that project is also closed
											
                                            %signature%<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 
								'editor_height' => 300,
							)
						)
					),

					'decline_offer' => array(
						'title' => __( 'Offer declined', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							
							'decline_offer_subject' => array(
								'type' => 'text',
								'value' => 'Your offer has been rejected',
								'label' => esc_html__( 'Subject', 'workreap' ),
							),
							'decline_offer_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 
								'html' => '%freelancer_name% — To display the freelancer name<br/>
											%freelancer_link% — To display the freelancer link<br/>
                                            %service_name% - To display service title<br/>
											%service_link% - To display service link<br/>
											%employer_name% - To display employer title<br/>
											%employer_link% - To display employer link<br/>
                                            %signature%',
							),
							'decline_offer_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hello %freelancer_name%

									Your offer has been declined for the "%service_name%" by the "%employer_name%"
									
									You can review the comments and send it again
									
									Thank you',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 
								'editor_height' => 300,
							)
						)
					),
				)
			),
			'offline_notification_templates' => array(
				'title' => esc_html__( 'Offline Notification', 'workreap' ),
				'type' => 'tab',
				'options' => array(
					'offline_notify' => array(
						'title' => esc_html__( 'Offline notifications', 'workreap' ),
						'type' => 'tab',
						'options' => array(
							'offline_order_notification_email' => array(
								'type' => 'html',
								'html' => esc_html__( 'Job/Services Noticification Email', 'workreap' ),
								'label' => esc_html__( '', 'workreap' ),
								'desc' => esc_html__( 'This email will be sent to employer when project/service is hired.', 'workreap' ),
								'help' => esc_html__( '', 'workreap' ),
							),
							'offline_order_notification_subject' => array(
								'type' => 'text',
								'value' => 'Offline order is received',
								'label' => esc_html__( 'Subject', 'workreap' ),
								'desc' => esc_html__( 'Please add subject for email', 'workreap' ),
							),
							'offline_order_notification_info' => array(
								'type' => 'html',
								'value' => '',
								'label' => esc_html__( 'Email Settings variables', 'workreap' ), 'html' => ' %employer_name% — To display Employer Name. <br/>
                                            %order_link% — To display project/Service link<br/>
                                            %order_name% - To display Job/Service title<br/>
                                            %signature%',
							),
							'offline_order_notification_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hi, %employer_name%,<br/><br/>
                                                We have received your order regarding the <a href="%order_link%">"%order_name%"</a>, Please send us your payment on the below details and let us know.<br/>
												Account Title : XXXXXXXXX
												Account No : XXXXXXXXX
												Bank Name : XXXXXXXXX
                                                %signature%,<br/>',
								'label' => esc_html__( 'Email Contents', 'workreap' ), 'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),

				),
			),
		)
	),
);