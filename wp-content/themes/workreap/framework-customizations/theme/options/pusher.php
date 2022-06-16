<?php
if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'pusher_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Notifications', 'workreap'),
        'options' => array(
            'pusher_general_templates' => array(
                'title' => esc_html__('General Templates', 'workreap'),
                'type' => 'tab',
                'options' => array(
                    'freelancers' => array(
                        'title' => esc_html__('Registration', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'freelancer_pusher' => array(
								'title' => esc_html__('Freelancer notification', 'workreap'),
								'type' => 'tab',
								'options' => array(
									'cus_regis_pusher' => array(
										'type' => 'html',
										'html' => esc_html__('Pusher template for freelancers', 'workreap'),
										'label' => esc_html__('', 'workreap'),
										'desc' => esc_html__('This notification will be sent to new registered freelancers', 'workreap'),
									),
									'freelancers_info' => array(
										'type' => 'html',
										'value' => '',
										'label' => esc_html__('Notification variables', 'workreap'),
										'html' => '%name% — To display the freelancer name. <br/>
											%email% — To display the freelancer email address.<br/>
											%password% — To display the password for login.<br/>
											%site% — To display the site name.<br/>',
									),
									'pusher_freelancers_content' => array(
										'type' => 'textarea',
										'value' => 'Hello %name%, Welcome to the workreap - A freelance marketplace.
										',
										'label' => esc_html__('Content', 'workreap'),
									),
								)
							),
							'employer_pusher' => array(
								'title' => esc_html__('Employer notification', 'workreap'),
								'type' => 'tab',
								'options' => array(
									'employer_pusher' => array(
										'type' => 'html',
										'html' => esc_html__('This notification template will be used for the employers registration', 'workreap'),
										'desc' => esc_html__('This notification will be sent to new registered employers.', 'workreap'),
									),
									'employer_info' => array(
										'type' => 'html',
										'value' => '',
										'label' => esc_html__('Notification variables', 'workreap'),
										'html' => '%name% — To display the freelancer name. <br/>
											%email% — To display the freelancer email address.<br/>
											%password% — To display the password for login.<br/>
											%site% — To display the site name.<br/>',
									),
									'pusher_employer_content' => array(
										'type' => 'textarea',
										'value' => 'Hello %name%, Welcome to the workreap - A freelance marketplace.',
										'label' => esc_html__('Content', 'workreap'),
									)
								)
							)
                        )
                    ),                    
                    'verify_code' => array(
                        'title' => esc_html__('Verification link', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'user_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Pusher template for user verifcation code', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to new registered users', 'workreap'),
                            ),
                            'user_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%name% — To display the user name. <br/>
											%email% — To display the user email address.<br/>
											%site% — To display the site name.<br/>',
                            ),
                            'pusher_verify_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
								Your account has created on %site%. Verification is required, To verify your account please check your email.',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            ),
                        ),                          
                    ),                    
                    'account_approve' => array(
                        'title' => esc_html__('Approve account', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'ap_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%name% — To display the person\'s name. <br/>
								%site_url% — To display the lost password link.<br/>',
                            ),
                            'pusher_user_approve_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
											Your account has been approved. You can now login to setup your profile.',
                                'label' => esc_html__('Lost Password?', 'workreap'),
                                
                            )
                        ),
                    ),
					'lp_pusher' => array(
                        'title' => esc_html__('Lost password', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'lp_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%name% — To display the person\'s name. <br/>
								%link% — To display the lost password link.<br/>
								%account_email% — To display user email address.<br/>',
                            ),
                            'pusher_lp_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %name%
											Lost Password reset
											Someone requested to reset the password of following account:
											Email Address: %account_email%
											If this was a mistake, just ignore this email and nothing will happen.
											To reset your password, click reset link below:
											%link%',
                                'label' => esc_html__('Lost Password?', 'workreap'),
                                
                            )
                        ),
                    ),
                    'rec_chat_notify' => array(
                        'title' => esc_html__('Receiver chat notifications', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'rec_chat_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%username% — To display message receiver name. <br/>
								%sender_name% — To display sender name.<br/>
								%message% — To display message.<br/>',
                            ),
                            'pusher_rec_chat_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %username%
											You have received a new message from %sender_name%, below is the message
											%message%',
                                'label' => esc_html__('Receiver chat message content', 'workreap'),
                            )
                        ),
                    ),
					'disput_user_notify' => array(
                        'title' => esc_html__('Dispute notify', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'dispute_hint' => array(
                                'type' => 'html',
                                'html' => esc_html__('Notify to user for the dispute', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('Notify to the user when a dispute is created.', 'workreap'),
                            ),
                            'dispute_user_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%dispute_author% — To display the name of user who created a dispute. <br/>
								%dispute_against% — To display the name of user against the dispute has created.<br/>
								%message% — To display message which the author of dispute has submitted.<br/>
								%project_link% — To display the link of project/service<br/>
								%project_title% — To display project/service title<br/>
											',
                            ),
                            'pusher_dispute_user_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %dispute_against%
											A new dispute for the project/service(%project_title%) has been submitted by %dispute_author%. We will now check this and update you on it.
											Message from the user is given below
											%message%',
                                'label' => esc_html__('Receiver chat message content', 'workreap'),
                            )
                        ),
                    ),
					'history_admin_feedback' => array(
                        'title' => esc_html__('Admin feedback', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'admin_feedback_hint' => array(
                                'type' => 'html',
                                'html' => esc_html__('Project or service history admin feedback', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('Admin can add his feedback into the ongoing, cancelled or completed projects and services. On add some comments both user will get an email that admin has left a feedback. To add comments you can go to Service Orders > Edit any order and then go to comment area. For the projects, Workreap > Proposal > Edit any proposal and then go to comment area', 'workreap'),
                            ),
                            'admin_feedback_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%username% — To display the name of user who created a dispute. <br/>
								%feedback% — To display message which will sent to both users.<br/>
								%link% — To display the link of project/service<br/>
								%title% — To display project/service title<br/>	',
                            ),
                            'pusher_admin_feedback_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %username%
											You have received the feedback from the admin on project/service(%title%). 
											%feedback%',
                                'label' => esc_html__('Feedback content', 'workreap'),
                                
                            )
                        ),
                    ),
					'identity_verify_approve' => array(
                        'title' => esc_html__('Approve identity verification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'identity_approve_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'identity verification' => esc_html__('', 'workreap'),
                                'html' => ' %user_name% — To display user who submit the identity verification<br/>
											%user_link% — To display the user link who send the identity verification<br/>
											%user_email% — To display the user email address who send the identity verification request',
                            ),
                            'pusher_pusher_identity_approve_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %user_name%
											Congratulations!
											Your submitted documents for the identity verification has been approved.
                                            ',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
	
					'identity_reject_verify' => array(
                        'title' => esc_html__('Reject identity verification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'identity_reject_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'identity verification' => esc_html__('', 'workreap'),
                                'html' => '%user_name% — To display user who submit the identity verification<br/>
											%user_link% — To display the user link who send the identity verification<br/>
											%user_email% — To display the user email address who send the identity verification request<br/>
											%admin_message% — To display admin rejection message to this user.',
                            ),
                            'pusher_identity_reject_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %user_name%
                                            You uploaded document for identity verification has been rejected.
											%admin_message%',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
                )
            ),
            'pusher_service_templates' => array(
                'title' => esc_html__('Service Templates', 'workreap'),
                'type' => 'tab',
                'options' => array(                                    
                    'service_post_freelancer' => array(
                        'title' => esc_html__('Service Posted', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'freelancer_service_post_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when new service posted by freelancer.', 'workreap'),
                            ),
                            'freelancer_service_post_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_name% — To display the freelancer name who posted the new service. <br/>
                                            %freelancer_link% — To display the freelancer profile link.<br/> 
                                            %service_title% — To display the service title. <br/>
											%service_link% — To display the service link. <br/>',
                            ),
                            'pusher_freelancer_service_post_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            Congratulation! Your service has been posted.
                                            Click below link to view the service. %service_link%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'pusher_admin_reject_service' => array(
                        'title' => esc_html__('Service rejection from admin', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'identity_reject_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Noticification Settings variables', 'workreap'),
                                'html' => '%user_name% — To display freelancer name<br/>
											%user_link% — To display the freelancer link<br/>
											%user_email% — To display the freelancer email address<br/>
                                            %admin_message% — To display admin rejection message to this user.<br/>
                                            %service_name%  — To display the service title.<br/>
                                            %service_link%  — To display the service link',
                            ),
                            'pusher_sevice_reject_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %user_name%
                                            Your service %service_name% is rejected.
											%admin_message%',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
					'purchase_service' => array(
                        'title' => esc_html__('Service purchased', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'service_buy_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when freelancer service will be purchased.', 'workreap'),
                            ),
                            'service_buy_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
											%freelancer_name% - To display freelancer name<br/>
											%employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_link% — To display the link of service<br/>
                                            %service_title% - To display service title',
                            ),
                            'pusher_service_buy_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
											Congratulations
											You have received new order for the following service %service_link% by the employer %employer_link%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            ),
	
							'service_employer_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when service will be purchased.', 'workreap'),
                            ),

                            'service_buy_info_employer' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
											%freelancer_name% - To display freelancer name<br/>
											%employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_link% — To display the link of service<br/>
                                            %service_title% - To display service title',
                            ),
                            'pusher_service_buy_content_employer' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
											Thank you for the order my service %service_link%',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
					'service_completed_freelancer' => array(
                        'title' => esc_html__('Service completed', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_service_complete_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer from employer when the service is complete.', 'workreap'),
                            ),
                            'frl_service_complete_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of service<br/>
                                            %freelancer_name% — To display freelancer name<br/>
                                            %employer_name% — To display employer name<br/>
                                            %employer_link% — To display employer profile<br/>
                                            %service_title% — To display service title<br/>
                                            %ratings% — To display the ratings<br/>
                                            %message% — To display info about complete service.',
                            ),
                            'pusher_frl_service_complete_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            The %employer_link% has confirmed the following service (%service_link%) is completed.
                                            You have received the following ratings from employer
                                            Message: %message% 
											Rating: %ratings% ',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'service_cancel_freelancer' => array(
                        'title' => esc_html__('Cancel service', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_cancel_service_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when service is cancelled by employer.', 'workreap'),
                            ),
                            'frl_cancel_service_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of service<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display service title<br/>
                                            %message% — To display info about cancel service.',
                            ),
                            'pusher_frl_cancel_service_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            Unfortunately %employer_name%< cancelled the %service_title% due to following below reasons.
                                            Job Cancel Reasons Below.
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'service_msg_freelancer' => array(
                        'title' => esc_html__('Service message freelancer', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_service_msg_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer notification', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when service message submitted', 'workreap'),
                            ),
                            'frl_service_msg_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display project title<br/>
                                            %message% — To display info about service.',
                            ),
                            'pusher_frl_service_msg_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
									You have received a new message!
									The %employer_name% has submitted a new message on this service %service_title%
									Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'service_msg_employer' => array(
                        'title' => esc_html__('Service message employer', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_service_msg_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when Service message submitted', 'workreap'),
                                
                            ),
                            'emp_service_msg_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %service_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_title% - To display project title<br/>
                                            %message% — To display info about service.',
                            ),
                            'pusher_emp_service_msg_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            %freelancer_name% has send you a new message on this service %service_title%
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'service_approved' => array(
                        'title' => esc_html__('Service approved', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'service_approved_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%name% — To display the user name. <br/>
											%service_title% — To display the service name<br/>
											%service_link% — To display the service link',
                            ),
                            'pusher_service_approved_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
											Congratulations! 
											Your Service %service_name% has been published.',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    )
                )
            ),
            'pusher_employer_templates' => array(
                'title' => esc_html__('Employer Templates', 'workreap'),
                'type' => 'tab',
                'options' => array(                                    
                    'employer_proposal' => array(
                        'title' => esc_html__('Proposal received', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_proposal_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when new proposal submitted by freelancer.', 'workreap'),
                            ),
                            'emp_proposal_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %employer_name% - To display freelancer name<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %proposal_amount% — To display the proposal amount<br/>
                                            %proposal_duration% — To display the proposal time<br/>
                                            %message% — To display message of user.',
                            ),
                            'pusher_emp_proposal_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            %freelancer_name% has sent a new proposal on the following project %project_title%
                                            Message is given below.
                                            Project Proposal Amount : %proposal_amount%
                                            Project Duration : %proposal_duration%
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'job_post_employer' => array(
                        'title' => esc_html__('Job posted', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_job_post_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when new job posted by employer.', 'workreap'),
                            ),
                            'emp_job_post_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %employer_name% — To display the employer name who posted the new job. <br/>
                                            %employer_link% — To display the employer profile link.<br/> 
                                            %job_title% — To display the job title. <br/>
											%job_link% — To display the job link. <br/>',
                            ),
                            'pusher_emp_job_post_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            Congratulation! Your job has been posted.
                                            Click below link to view the job. %job_title%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'pusher_admin_reject_job' => array(
                        'title' => esc_html__('Job rejectiob from admin', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'identity_reject_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Noticification Settings variables', 'workreap'),
                                'html' => '%user_name% — To display freelancer name<br/>
											%user_link% — To display the freelancer link<br/>
											%user_email% — To display the freelancer email address<br/>
                                            %admin_message% — To display admin rejection message to this user.<br/>
                                            %job_name%  — To display the job title.<br/>
                                            %job_link%  — To display the job link',
                            ),
                            'pusher_job_reject_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %user_name%
                                            Your job %project_title% is rejected.
											%admin_message%',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
                    'proposal_msg_employer' => array(
                        'title' => esc_html__('Proposal message', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_proposal_msg_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when proposal message submitted', 'workreap'),
                                
                            ),
                            'emp_proposal_msg_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.',
                            ),
                            'pusher_emp_proposal_msg_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            %freelancer_name% has send you a new message on this job %project_title%
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'package_subscribe_employer' => array(
                        'title' => esc_html__('Package subscription', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_package_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Employer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when package is purchased.', 'workreap'),
                            ),
                            'emp_package_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %package_name% — To display the package name. <br/>
											%invoice% — To display the invoice ID<br/>
											%amount% — To display the package amount<br/>
											%status% — To display the payment status<br/>
											%method% — To display the payment method<br/>
                                            %date% — To display the purchased date<br/>
                                            %expiry% — To display the package expiry<br/>
                                            %name% — To display employer name<br/>
                                            %link% — To display employer profile link',
                            ),
                            'pusher_emp_package_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
											Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

											Invoice ID: %invoice%
											Package Name: %package_name%
											Payment Amount: %amount%
											Payment status: %status%
											Payment Method: %method%
											Purchase Date: %date%
											Expiry Date: %expiry%,',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'job_approved' => array(
                        'title' => esc_html__('Job approved', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'job_approved_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %name% — To display the user name. <br/>
											%project_name% — To display the project name<br/>
											%link% — To display the project link',
                            ),
                            'pusher_job_approved_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
											Congratulations! 
											Your Project %project_name% has been published.',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					 'milestone_req_approved' => array(
                        'title' => esc_html__('Milestone request approved', 'workreap'),
                        'type' => 'tab',
						'ml_note_2' => array(
							'type' => 'html',
							'html' => esc_html__('Milestone notification', 'workreap'),
							'label' => esc_html__('', 'workreap'),
							'desc' => esc_html__('This notification will be sent to employer when freelancer will accept the milestone request', 'workreap'),
							'help' => esc_html__('', 'workreap'),
						),
                        'options' => array(
                            'ml_req_appr_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
                                            %freelancer_link% — To display the link of freelancer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name',
                            ),
                            'pusher_ml_req_appr_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            Your request for milestone on the project %project_title% has been approved
                                            by freelancer %freelancer_name%
                                            Please login to see the details of milestone',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'milestone_req_rejected' => array(
                        'title' => esc_html__('Milestone request rejected', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'ml_note_3' => array(
								'type' => 'html',
								'html' => esc_html__('Milestone notification', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('This notification will be sent to employer when freelancer will decline the milestone request', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
                            'ml_req_rej_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
                                            %freelancer_link% — To display the link of freelancer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %reason% - To display the reason<br/>',
                            ),
                            'pusher_ml_req_rej_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%
                                            Your request for milestone on the project %project_title% has been rejected
                                            by freelancer %freelancer_name%
                                            Reason : %reason%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'dispute_resolved' => array(
                        'title' => esc_html__('Dispute resolved', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'emp_dispute_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%employer_name% — To display the employer name<br/>
                                            %dispute_raised_by% - To display raised by user name<br/>
											%admin_message% — To display the admin message',
                            ),
                            'pusher_emp_dispute_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %employer_name%
                                            We have reached out to you regarding a dispute that was raised by %dispute_raised_by%
                                            %admin_message%
                                            Thanks',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'noty_send_offer' => array(
                        'title' => esc_html__('New quote', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'noty_send_offer_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_name% - To display service name<br/>
											%service_link% - To display service link<br/>',
                            ),
                            'pusher_emp_noty_send_offer' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%

                                You have received a new offer for the "%service_name%" from the freelancer "%freelancer_name%"
                                
                                You can accept or decline this
                                
                                Thank you',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'noty_update_offer' => array(
                        'title' => esc_html__('Update quote', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'noty_update_offer_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_name% - To display service name<br/>
											%service_link% - To display service link<br/>',
                            ),
                            'pusher_emp_noty_update_offer' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %employer_name%

                                Freelancer has updated the offer for the "%service_name%" from the freelancer "%freelancer_name%"
                                
                                You can accept or decline this
                                
                                Thank you',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                )
            ),
            'pusher_freelancer_templates' => array(
                'title' => esc_html__('Freelancer Templates', 'workreap'),
                'type' => 'tab',
                'options' => array(                                    
                    'proposal_submit_freelancer' => array(
                        'title' => esc_html__('Proposal submit', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_proposal_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when new proposal submitted by freelancer.', 'workreap'),
                            ),
                            'frl_proposal_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %proposal_amount% — To display the proposal amount<br/>
                                            %proposal_duration% — To display the proposal time<br/>
                                            %message% — To display message of proposal.',
                            ),
                            'pusher_frl_proposal_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            You have submitted the proposal against this job %project_title%
                                            Message is given below.
                                            Project Proposal Amount : %proposal_amount%
                                            Project Duration : %proposal_duration%
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'hire_freelancer' => array(
                        'title' => esc_html__('Hire freelancer', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_hire_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when freelancer hired.', 'workreap'),
                            ),
                            'frl_hire_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title',
                            ),
                            'pusher_frl_hire_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
											Congratulations
											You have hired for the following job %project_title% by the employer %employer_name%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'send_offer_freelancer' => array(
                        'title' => __('Send offer', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_sendoffer_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer notification', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when offer send by the employer.', 'workreap'),
                            ),
                            'frl_sendoffer_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
											%project_link% — To display the link of project<br/>
                                            %project_title% - To display project title<br/>
										    %employer_link% - To display employer profile<br/>
                                            %employer_name% - To display employer name<br/>
                                            %message% — To display info about cancel job.',
                            ),
                            'pusher_frl_sendoffer_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
											You have new invitation from an employer
											%employer_name% would like to invite you to consider working on the following project %project_title%
											Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'job_cancel_freelancer' => array(
                        'title' => esc_html__('Cancel Job', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_cancel_job_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when job is cancelled by employer.', 'workreap'),
                            ),
                            'frl_cancel_job_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.',
                            ),
                            'pusher_frl_cancel_job_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            Unfortunately %employer_name% cancelled the %project_title% due to following below reasons.
                                            Job Cancel Reasons Below.
                                            Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'proposal_msg_freelancer' => array(
                        'title' => esc_html__('Proposal message', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_proposal_msg_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when proposal message submitted', 'workreap'),
                            ),
                            'frl_proposal_msg_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %project_title% - To display project title<br/>
                                            %message% — To display info about cancel job.',
                            ),
                            'pusher_frl_proposal_msg_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
									You have received a new message!
									The %employer_name% has submitted a new message on this job %project_title%
									Message: %message%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'package_subscribe_freelancer' => array(
                        'title' => esc_html__('Package subscribe', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_package_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer when package is purchased.', 'workreap'),
                            ),
                            'frl_package_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %package_name% — To display the package name. <br/>
											%invoice% — To display the invoice ID<br/>
											%amount% — To display the package amount<br/>
											%status% — To display the payment status<br/>
											%method% — To display the payment method<br/>
                                            %date% — To display the purchased date<br/>
                                            %expiry% — To display the package expiry<br/>
                                            %name% — To display freelancer name<br/>
                                            %link% — To display freelancer profile link',
                            ),
                            'pusher_frl_package_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %name%
											Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:

											Invoice ID: %invoice%
											Package Name: %package_name%
											Payment Amount: %amount%
											Payment status: %status%
											Payment Method: %method%
											Purchase Date: %date%
											Expiry Date: %expiry%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					
                    'job_completed_freelancer' => array(
                        'title' => esc_html__('Job completed', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'frl_job_complete_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Freelancer Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to freelancer from employer when the job is complete.', 'workreap'),
                            ),
                            'frl_job_complete_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %project_link% — To display the link of project<br/>
                                            %freelancer_name% — To display freelancer name<br/>
                                            %employer_name% — To display employer name<br/>
                                            %employer_link% — To display employer profile<br/>
                                            %project_title% — To display project title<br/>
                                            %ratings% — To display the ratings<br/>
                                            %message% — To display info about cancel job.',
                            ),
                            'pusher_frl_job_complete_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            The "%employer_name%" has confirmed the following project (%project_title%) is completed
                                            You have received the following ratings from employer
                                            Message: %message% 
											Rating: %ratings% ',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					'milestone_received' => array(
                        'title' => esc_html__('Milestone notification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'ml_note_1' => array(
								'type' => 'html',
								'html' => esc_html__('Milestone notification', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('This notification will be sent to freelancer when freelancer will accept the milestone request from employer', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
                            'ml_rec_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%project_link% — To display the link of project<br/>
                                            %employer_link% — To display the link of employer<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %project_title% - To display project title',
                            ),
                            'pusher_ml_rec_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            Employer %employer_name% has created milestones for the project %project_title%. You can accept or reject the employer request for project.',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'hired_against_milestone' => array(
                        'title' => esc_html__('Hired against milestone', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'hired_ml_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %project_link% — To display the link of project<br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %project_title% - To display project title<br/>
                                            %milestone_title% - To display milestone title',
                            ),
                            'pusher_hired_ml_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%
                                            You have been hired for the milestone %milestone_title% against the project %project_title%
                                            Please login to see the details of milestone.% ',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'milestone_completed' => array(
                        'title' => esc_html__('Milestone completed', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'ml_completed_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%milestone_title% — To display the link of miletone<br/>
                                            %freelancer_name% - To display freelancer name<br/>
											%project_link% — To display the link of project<br/>
                                            %project_title% - To display project title',
                            ),
                            'pusher_ml_completed_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hello %freelancer_name%,
                                            Congratulations!!
                                            Milestone %milestone_title% for the project %project_title% has been completed!!',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'dispute_resolved' => array(
                        'title' => esc_html__('Dispute resolved', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'fr_dispute_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %dispute_raised_by% - To display raised by user name<br/>
											%admin_message% — To display the admin message',
                            ),
                            'pusher_fr_dispute_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %freelancer_name%
                                            We have reached out to you regarding a dispute that was raised by %dispute_raised_by%.
                                            %admin_message%
                                            Thanks %',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'earning_notify' => array(
                        'title' => esc_html__('Earning notification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'fr_earning_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %total_amount% - To display total amount',
                            ),
                            'pusher_fr_earning_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %freelancer_name%
                                            This is confirmation that your total earning has been calculated.
                                            Your payouts will be %total_amount%
                                            You will be informed when your payouts will be processed.',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
                    'payouts_notify' => array(
                        'title' => esc_html__('Payout notification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'fr_payouts_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%freelancer_name% — To display the freelancer name<br/>
                                            %total_amount% - To display total amount',
                            ),
                            'pusher_fr_payouts_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %freelancer_name%
                                            Congratulations
                                            Your payouts has been processed. Your total payouts was %total_amount%',
                                'label' => esc_html__('Content', 'workreap'),
                                
                            )
                        )
                    ),
					
					'proposal_accept' => array(
                        'title' => __('Proposal rejected', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'proposal_pusher' => array(
								'label' => esc_html__('Proposal rejected', 'workreap'),
								'type' => 'switch',
								'value' => 'enable',
								'desc' => esc_html__('When employer will accept one of proposal then all other freelancers will get this notification', 'workreap'),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
							),
                            'fr_proposal_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => '%freelancer_name% — To display the freelancer name<br/>
											%freelancer_link% — To display the freelancer link<br/>
                                            %project_title% - To display project title<br/>
											%project_link% - To display project link<br/>
											%employer_name% - To display employer title<br/>
											%employer_link% - To display employer link',
                            ),
                            'pusher_fr_proposal_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi %freelancer_name%
                                            We are sorry, your proposal has been rejected
                                            Employer %employer_name% has hire other freelancer for the project %project_title%
											Try to bid on other project to get hired',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),
					
					'job_cron_notifications' => array(
                        'title' => __('Job notification', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'job_notification_pusher' => array(
								'type' => 'html',
								'html' => esc_html__('Job Noticification Email', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('This notification will be sent to freelancers on daily base when cron is run.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'job_notification_info' => array(
								'type' => 'html',
								'value' => '',
								'attr' => array(),
								'label' => esc_html__('Notification variables', 'workreap'),

								'help' => esc_html__('', 'workreap'),
								'html' => ' %freelancer_name% — To display the link of freelancer Name. <br/>
											%search_job_link% - To display Job search page link',
							),
							'pusher_job_notification_content' => array(
								'type' => 'textarea',
								'value' => 'Hello %freelancer_name%
											There are some new jobs posted matching your skills, You can visit our site for more informations.
											%jobs_listings%
											%search_job_link%',
								'attr' => array(),
								'label' => esc_html__('Content', 'workreap'),

							)
						),
					),	
                    'quote_rejected' => array(
                        'title' => __('Quote rejected', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
							'quote_rejected_pusher' => array(
								'type' => 'html',
								'html' => esc_html__('Quote rejected', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('This notification will be sent to freelancers when employer will decline the service quote.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'quote_rejected_info' => array(
								'type' => 'html',
								'value' => '',
								'attr' => array(),
								'label' => esc_html__('Variables', 'workreap'),

								'help' => esc_html__('', 'workreap'),
								'html' => ' %freelancer_link% — To display the link of freelancer profile page. <br/>
                                            %freelancer_name% - To display freelancer name<br/>
                                            %employer_name% - To display employer name<br/>
                                            %employer_link% - To display employer profile<br/>
                                            %service_name% - To display service name<br/>
											%service_link% - To display service link<br/>',
							),
							'pusher_quote_rejected_content' => array(
								'type' => 'textarea',
								'value' => 'Hello %freelancer_name%
                                Your offer has been declined for the "%service_name% by the "%employer_name%"
                                You can review the comments and send it again
                                Thank you',
								'attr' => array(),
								'label' => esc_html__('Content', 'workreap'),

							)
						),
					),
                )
            ),
            'pusher_offline_notification_templates' => array(
                'title' => esc_html__('Offline Notification', 'workreap'),
                'type' => 'tab',
                'options' => array(
                    'offline_notify' => array (
                        'title' => esc_html__('Offline notifications', 'workreap'),
                        'type' => 'tab',
                        'options' => array(
                            'offline_order_notification_pusher' => array(
                                'type' => 'html',
                                'html' => esc_html__('Job/Services Noticification Email', 'workreap'),
                                'label' => esc_html__('', 'workreap'),
                                'desc' => esc_html__('This notification will be sent to employer when project/service is hired.', 'workreap'),
                            ),
                            'offline_order_notification_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('Notification variables', 'workreap'),
                                'html' => ' %employer_name% — To display Employer Name. <br/>
                                            %order_name% - To display Job/Service title',
                            ),
                            'pusher_offline_order_notification_content' => array(
                                'type' => 'textarea',
                                'value' => 'Hi, %employer_name%,
									We have received your order regarding the "%order_name%", Please send us your payment on the below details and let us know.',
                                'label' => esc_html__('Content', 'workreap'),
                            )
                        )
                    ),

				),
			),
        )
    ),
);