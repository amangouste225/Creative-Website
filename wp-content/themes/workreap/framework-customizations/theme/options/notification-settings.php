<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'notification_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Email Notification Settings', 'workreap'),
        'options' => array(
            'request' => array(
                'title' => esc_html__('Email Content - Request a Callback ( User Email )', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'request_subject' => array(
                        'type' => 'text',
                        'value' => 'Thank you for contacting us!',
                        'label' => esc_html__('Subject', 'workreap'),
                        'desc' => esc_html__('Please add subject for email', 'workreap'),
                    ),
                    'request_info' => array(
                        'type' => 'html',
                        'value' => '',
                        'attr' => array(),
                        'label' => esc_html__('Email Settings variables', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'html' => '%name% — To display the person\'s name. <br/>
						%phone% — To display the contact number.<br/>
						%topic% — To display the topic.<br/>
						%email% — To display the email.<br/>
						%availability% — To display the availability time.<br/>
						%logo% — To display site logo.<br/>',
                    ),
                    'request_content' => array(
                        'type' => 'wp-editor',
                        'value' => 'Hi, %name%!<br/>

										Thank you for contacting us. Our team will get back to you soon. 

										Sincerely,<br/>
										Workreap Team<br/>
										%logo%
									',
                        'attr' => array(),
                        'label' => esc_html__('Email Contents', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'size' => 'large', // small, large
                        'editor_height' => 400,
                    )
                )
            ),
            'request_admin' => array(
                'title' => esc_html__('Admin Email Content - Request a callback', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'admin_request_email' => array(
                        'type' => 'text',
                        'value' => 'info@example.com',
                        'label' => esc_html__('Admin email address', 'workreap'),
                        'desc' => esc_html__('Please add email addressm leave it empty to use email from WordPress Settings.', 'workreap'),
                    ),
                    'request_admin_subject' => array(
                        'type' => 'text',
                        'value' => 'A new reuquest for appointment received!',
                        'label' => esc_html__('Subject', 'workreap'),
                        'desc' => esc_html__('Please add subject for email', 'workreap'),
                    ),
                    'info_request_admin' => array(
                        'type' => 'html',
                        'value' => '',
                        'attr' => array(),
                        'label' => esc_html__('Email Settings variables', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'html' => '%name% — To display the person\'s name. <br/>
						%phone% — To display the contact number.<br/>
						%topic% — To display the topic.<br/>
						%email% — To display the email.<br/>
						%availability% — To display the availability time.<br/>
						%logo% — To display site logo.<br/>',
                    ),
                    'request_admin_content' => array(
                        'type' => 'wp-editor',
                        'value' => 'Hi<br/>

										A new request has received. The information is given below.
										<br/>
										Name : %name%<br/>
										Email Address : %email%<br/>
										Phone Number : %phone%<br/>
										Appointment type : %topic%<br/>
										Availability Time : %availability%<br/>
										
										Sincerely,<br/>
										Workreap Team<br/>
										%logo%
									',
                        'attr' => array(),
                        'label' => esc_html__('Email Contents', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'size' => 'large', // small, large
                        'editor_height' => 400,
                    )
                )
            ),
            'team_contact' => array(
                'title' => esc_html__('Email Content - Team Contact Form', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'team_request_subject' => array(
                        'type' => 'text',
                        'value' => 'Thank you for contacting us!',
                        'label' => esc_html__('Subject', 'workreap'),
                        'desc' => esc_html__('Please add subject for email', 'workreap'),
                    ),
                    'team_request_info' => array(
                        'type' => 'html',
                        'value' => '',
                        'attr' => array(),
                        'label' => esc_html__('Email Settings variables', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'html' => '%name% — To display the person\'s name. <br/>
						%email% — To display the email.<br/>
						%phone% — To display the phone number.<br/>
						%subject% — To display the subject.<br/>
						%message% — To display the message.<br/>
						%logo% — To display site logo.<br/>',
                    ),
                    'team_request_content' => array(
                        'type' => 'wp-editor',
                        'value' => 'Hi, %name%!<br/>

										Following visitor is contacted you through Team contact form.<br />
                                                                                Below is the visitor information :<br />
                                                                                Name : %name%<br />
                                                                                Email : %email%<br />
                                                                                Phone : %phone%<br />
                                                                                Subject : %subject%<br />
                                                                                Message : %message%<br />

										Sincerely,<br/>
										Workreap Team<br/>
										%logo%
									',
                        'attr' => array(),
                        'label' => esc_html__('Email Contents', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'size' => 'large', // small, large
                        'editor_height' => 400,
                    )
                )
            ),
        )
    )
);


