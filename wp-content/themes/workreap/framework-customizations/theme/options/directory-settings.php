<?php

if (!defined('FW')) {
    die('Forbidden');
}

$schedules_list	= array();

if( function_exists('workreap_cron_schedule') ) {
	$schedules		= workreap_cron_schedule();
	
	if( !empty( $schedules ) ) {
		foreach ( $schedules as $key => $val ) {
			$schedules_list[$key]	= $schedules[$key]['display'];
		}
	}
}

$freelancer_required = workreap_freelancer_required_fields();
$employers_required  = workreap_employer_required_fields();
$job_required  = workreap_jobs_required_fields();

$limit_fields['count_tagline']	 = array(
	'type' 	=> 'slider',
	'value' => 200,
	'properties' => array(
		'min' => 1,
		'max' => 500,
		'sep' => 1,
	),
	'label' => esc_html__('Tagline limit', 'workreap'),
	'desc' => esc_html__('Set limit to add number character/words for the tageline', 'workreap'),
);
$limit_fields['count_project_title'] = array(
	'type' 	=> 'slider',
	'value' => 200,
	'properties' => array(
		'min' => 1,
		'max' => 500,
		'sep' => 1,
	),
	'label' => esc_html__('Project title limit', 'workreap'),
	'desc' => esc_html__('Set limit to add number character/words for the project title', 'workreap'),
);
$limit_fields['count_service_title'] = array(
	'type' 	=> 'slider',
	'value' => 200,
	'properties' => array(
		'min' => 1,
		'max' => 500,
		'sep' => 1,
	),
	'label' => esc_html__('Service title limit', 'workreap'),
	'desc' => esc_html__('Set limit to add number character/words for the service title', 'workreap'),
);
	   
$options = array(
    'directory' => array(
        'title' => esc_html__('Directory Settings', 'workreap'),
        'type' => 'tab',
        'options' => array( 
            'general-settings' => array(
                'title' => esc_html__('General Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'chat' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Real Time Chat?', 'workreap'),
								'type' => 'select',
								'value' => 'inbox',
								'desc' => esc_html__('Enable real time chat or use simple inbox system.', 'workreap'),
								'choices' => array(
									'inbox' => esc_html__('Inbox', 'workreap'),
									'chat' => esc_html__('Real Time Chat', 'workreap'),
									'cometchat' => esc_html__('Third Party Atomchat', 'workreap'),
									'guppy' => esc_html__('Third Party WP Guppy', 'workreap'),
								)
							)
						),
						'choices' => array(
							'chat' => array(
								'instant' => array(
									'type' => 'html',
									'html' => esc_html__('Realtime Chat Settings', 'workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => wp_kses( __( 'Please make sure Node.js has installed on your server.', 'workreap'),array(
														'a' => array(
															'href' => array(),
															'title' => array()
														),
														'br' => array(),
														'em' => array(),
														'strong' => array(),
													)),
									'help' => esc_html__('', 'workreap'),
								),
								'floating_chat' => array(
									'label' => esc_html__('Enable Chat window', 'workreap'),
									'type' => 'switch',
									'value' => 'disable',
									'desc' => esc_html__('Enable/Disable chat window on freelancer detail page and also on service details page.', 'workreap'),
									'left-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
								),
								'host' => array(
									'label' => esc_html__('Host?', 'workreap'),
									'value' => esc_html__('http://localhost', 'workreap'),
									'desc' 		=> wp_kses( __( 'Please add the host, default would be http://localhost<br/> 
												1) Host could be either http://localhost<br/>
												2) OR could be http://yourdomain.com<br/>
												', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
									'type' => 'text',
								),
								'port' => array(
									'type' => 'text',
									'value' => '81',
									'label' => esc_html__('Port', 'workreap'),
									'desc' 		=> wp_kses( __( 'Please add the available port for chat, default would be 81<br/>
												1) Some server uses 80, 81, 8080 or 3000<br/>
												2) Please consult with your hosting provider<br/>
												3) No need to change the port if your server is using port 81, <br/>
												if you will change this port then you have to change it in server.js located in themes > workreap > js > server.js at line no 3<br/>
												Your node server should run server.js located in theme for real-time chat. Please ask your hosting provider, how you can run this file.
												', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
									'help' => esc_html__('', 'workreap'),
								)
							),
							'cometchat' => array(
								'cometintro' => array(
									'type' => 'html',
									'value' => true,
									'html' => esc_html__('AtomChat Configurations', 'workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => wp_kses( __( 'Install the AtomChat plugin first. <a href="https://wordpress.org/plugins/atomchat/" target="_blank"> Get AtomChat Plugin </a><br />
													<a href="https://help.atomchat.com/installing-atomchat-on-wordpress">Set the api key and auth key in AtomChat Plugin settings.</a>
									', 'workreap'),array(
														'a' => array(
															'href' => array(),
															'title' => array()
														),
														'br' => array(),
														'em' => array(),
														'strong' => array(),
													)),
									'help' => esc_html__('', 'workreap'),
								)
							),
							'guppy' => array(
								'guppyintro' => array(
									'type' => 'html',
									'value' => true,
									'html' => esc_html__('WP Guppy configurations', 'workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => wp_kses( __( 'Install the WP Guppy plugin first. <a href="https://wp-guppy.com/" target="_blank">Get WP Guppy plugin</a>
									', 'workreap'),array(
														'a' => array(
															'href' => array(),
															'target' => array(),
															'title' => array()
														),
														'br' => array(),
														'em' => array(),
														'strong' => array(),
													)),
									'help' => esc_html__('', 'workreap'),
								)
							),

							'default' => array(),
						),
						
						'show_borders' => false,
					),
					'account_types_permissions'  => array(
						'label' => esc_html__( 'Switch account', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('By enable this, users will be able to switch profile and use both type of accounts with single registration.', 'workreap'),
						'choices'	=> array(
							'yes'  => esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					'delete_account_hide'  => array(
						'label' => esc_html__( 'Hide delete account', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('You can enable this settings to hide delete account settings from users dashboard', 'workreap'),
						'choices'	=> array(
							'yes'  => esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					
					'restict_user_view_search'  => array(
						'label' => esc_html__( 'Restrict users to view irrelevant pages', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('', 'workreap'),
						'choices'	=> array(
							'no'  	=> esc_html__('No', 'workreap'),
							'yes'	=> esc_html__('Yes', 'workreap')
						),
						'desc' 		=> wp_kses( __( 'Restrict users to view search result and detail pages<br/> 
												1) Restrict employers to views jobs and other employer search result pages and their detail pages<br/>
												2) Restrict freelancers to view services or other freelancer search result and their detail pages <br/>', 'workreap' ),array(
											'a' => array(
												'href' => array(),
												'title' => array()
											),
											'br' => array(),
											'em' => array(),
											'strong' => array(),
										)),
					),
					'system_access' => array(
						'type' 		=> 'select',
						'value' 	=> 'paid',
						'label' 	=> esc_html__('System Access type?', 'workreap'),
						'desc' 		=> wp_kses( __( 'Please select only one of the following options.<br/> 
												1) In "Paid Listings for both" means both employers and freelancers have to buy a package to access all the features of the site<br/>
												2) In "Free listings for employer" all features would be free for only employers not for freelancers. <br/>
												3) In "Free listings for freelancers" all features would be free for only freelancers not for employers. <br/>
												4) In "Free for both", In this settings all the site features would be free for both employers and freelancers', 'workreap' ),array(
																'a' => array(
																	'href' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
						'choices' => array(
							'paid' => esc_html__('Paid Listings for both', 'workreap'),
							'employer_free' => esc_html__('Free listings for employer', 'workreap'),
							'freelancer_free' => esc_html__('Free listings for freelancers', 'workreap'),
							'both' => esc_html__('Free for both', 'workreap'),
						),
					),
					'show_packages_if' => array(	
						'label' => esc_html__('Show packages', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Show packages after registration or if package get expired', 'workreap'),
						'choices' => array(
							'yes' 		=> esc_html__('Yes', 'workreap'),
							'no' 		=> esc_html__('No', 'workreap'),
						)
					),
					'application_access' => array(	
						'label' => esc_html__('Application Access', 'workreap'),
						'type' 	=> 'select',
						'value' => 'both',
						'desc' 	=> esc_html__('Enable Application Access?', 'workreap'),
						'choices' => array(
							'service_base' 	=> esc_html__('Service based application', 'workreap'),
							'job_base' 		=> esc_html__('Job based application', 'workreap'),
							'both' 			=> esc_html__('Both Service and Job based application', 'workreap'),
						)
					),
					
					'remove_saved' => array(	
						'label' => esc_html__('Remove saved items', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove saved items menu from dashboard', 'workreap'),
						'choices' => array(
							'yes' 		=> esc_html__('Yes', 'workreap'),
							'no' 		=> esc_html__('No', 'workreap'),
						)
					),
					'remove_chat' => array(	
						'label' => esc_html__('Remove chat from packages?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove chat options from packages and make it free for all users', 'workreap'),
						'choices' => array(
							'yes' 		=> esc_html__('Yes, remove from packages and make it free for all users', 'workreap'),
							'no' 		=> esc_html__('No', 'workreap'),
						)
					),
					'redirect_registration' => array(
						'label' => esc_html__('Registration redirect?', 'workreap'),
						'type' => 'select',
						'value' => 'settings',
						'desc' => esc_html__('You can select where to redirect after registration. Default would be dashboard', 'workreap'),
						'choices' => array(
							'package'   => esc_html__('Packages', 'workreap'),
							'settings'  => esc_html__('Profile Settings', 'workreap'),
							'insights' 	=> esc_html__('Dashboard', 'workreap'),
							'home' 		=> esc_html__('Home Page', 'workreap'),
						)
					),
					'redirect_login' => array(
						'label' => esc_html__('Login redirect for freelancer?', 'workreap'),
						'type' => 'select',
						'value' => 'settings',
						'desc' => esc_html__('You can select where to redirect after login. Default would be dashboard', 'workreap'),
						'choices' => array(
							'package'   => esc_html__('Packages', 'workreap'),
							'settings'  => esc_html__('Profile Settings', 'workreap'),
							'insights' 		=> esc_html__('Dashboard', 'workreap'),
							'home' 		=> esc_html__('Home Page', 'workreap'),
						)
					),
					'redirect_employer_login' => array(
						'label' => esc_html__('Login redirect for employer?', 'workreap'),
						'type' => 'select',
						'value' => 'settings',
						'desc' => esc_html__('You can select where to redirect after login. Default would be dashboard', 'workreap'),
						'choices' => array(
							'package'   	=> esc_html__('Packages', 'workreap'),
							'create_job'   	=> esc_html__('Create job', 'workreap'),
							'settings'  	=> esc_html__('Profile Settings', 'workreap'),
							'insights' 		=> esc_html__('Dashboard', 'workreap'),
							'home' 			=> esc_html__('Home Page', 'workreap'),
						)
					),

					'job_status'  => array(
						'label' => esc_html__( 'Review job', 'workreap' ),
						'type'  => 'select',
						'value' => 'publish',
						'desc' 		=> wp_kses( __( 'Review job before publish. Needs admin approval before going live..<br/> 
												1) In "Yes ( Pending )" job will be pending and admin will approve manually.<br/>
												2) In "No ( Published )" job will be published automatically.<br/>', 'workreap' ),array(
													'a' => array(
														'href' => array(),
														'title' => array()
													),
													'br' => array(),
													'em' => array(),
													'strong' => array(),
												)),
						'choices'	=> array(
							'pending'   => esc_html__('Yes ( Pending )', 'workreap'),
							'publish'	=> esc_html__('No ( Published )', 'workreap')
						)
					),
					'service_status'  => array(
						'label' => esc_html__( 'Review service', 'workreap' ),
						'type'  => 'select',
						'value' => 'publish',
						'desc' 		=> wp_kses( __( 'Review services before publish. Needs admin approval before going live..<br/> 
												1) In "Yes ( Pending )" service will be pending and admin will approve manually.<br/>
												2) In "No ( Published )" service will be published automatically.<br/>', 'workreap' ),array(
													'a' => array(
														'href' => array(),
														'title' => array()
													),
													'br' => array(),
													'em' => array(),
													'strong' => array(),
												)),
						'choices'	=> array(
							'pending'   => esc_html__('Yes ( Pending )', 'workreap'),
							'publish'	=> esc_html__('No ( Published )', 'workreap')
						)
					),
					
					'db_left_menu'  => array(
						'label' => esc_html__( 'Users Left menu', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('Hide users left menu?', 'workreap'),
						'choices'	=> array(
							'yes'  => esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					'price_filter_start' => array(
						'type' => 'text',
						'value' => 1,
						'label' => esc_html__('Price Filter Start', 'workreap'),
						'desc' => esc_html__('Select price filter starting value', 'workreap'),
					),
					'price_filter_end' => array(
						'type' => 'text',
						'value' => 1000,
						'label' => esc_html__('Price Filter End', 'workreap'),
						'desc' => esc_html__('Select price filter ending value', 'workreap'),
					),
					'services_per_page' => array(
						'type' => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Services per page', 'workreap'),
						'desc' => esc_html__('Select services per page to show', 'workreap'),
					),
					'projects_per_page' => array(
						'type' => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Projects per page', 'workreap'),
						'desc' => esc_html__('Select projects per page to show', 'workreap'),
					),
					'freelancers_per_page' => array(
						'type' => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Freelancers per page', 'workreap'),
						'desc' => esc_html__('Select freelancers per page to show', 'workreap'),
					),
					'employers_per_page' => array(
						'type' => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Employers per page', 'workreap'),
						'desc' => esc_html__('Select employers per page to show', 'workreap'),
					),
					'portfolios_per_page' => array(
						'type' => 'slider',
						'value' => 12,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Portfolios per page', 'workreap'),
						'desc' => esc_html__('Select portfolios per page to show', 'workreap'),
					),
					'default_skills' => array(
						'type' => 'slider',
						'value' => 50,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Default skills', 'workreap'),
						'desc' => esc_html__('Default skills to per freelancer', 'workreap'),
					),
					
					'remove_qrcode'  => array(
						'label' => esc_html__( 'Remove QR CODE', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('Remove QR code all over the site.', 'workreap'),
						'choices'	=> array(
							'yes'  => esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					'report_freelancer' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Freelancer report form', 'workreap'),
								'type' => 'select',
								'value' => 'no',
								'desc' => esc_html__('Hide report form, from the detail page?', 'workreap'),
								'choices' => array(
									'yes' => esc_html__('Yes', 'workreap'),
									'no' => esc_html__('No', 'workreap'),
								)
							)
						),
						'choices' => array(
							'no' => array(
								'report_options' => array(
									'type' => 'addable-option',
									'value' => array(esc_html__('This is the fake', 'workreap'), 
													 esc_html__('Other', 'workreap')
												),
									'desc' => esc_html__('Report form options for freelancers', 'workreap'),
									'label' => esc_html__('Add report options', 'workreap'),
									'option' => array('type' => 'text'),
									'add-button-text' => esc_html__('Add', 'workreap'),
									'sortable' => true,
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
	
					'report_employer' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Employer report form', 'workreap'),
								'type' => 'select',
								'value' => 'no',
								'desc' => esc_html__('Hide report form, from the detail page?', 'workreap'),
								'choices' => array(
									'yes' => esc_html__('Yes', 'workreap'),
									'no' => esc_html__('No', 'workreap'),
								)
							)
						),
						'choices' => array(
							'no' => array(
								'report_options' => array(
									'type' => 'addable-option',
									'value' => array(esc_html__('This is the fake', 'workreap'), 
													 esc_html__('Other', 'workreap')
												),
									'desc' => esc_html__('Report form options for employers', 'workreap'),
									'label' => esc_html__('Add report options', 'workreap'),
									'option' => array('type' => 'text'),
									'add-button-text' => esc_html__('Add', 'workreap'),
									'sortable' => true,
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
					
					'report_project' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Project report form', 'workreap'),
								'type' => 'select',
								'value' => 'no',
								'desc' => esc_html__('Hide report form, from the detail page?', 'workreap'),
								'choices' => array(
									'yes' => esc_html__('Yes', 'workreap'),
									'no' => esc_html__('No', 'workreap'),
								)
							)
						),
						'choices' => array(
							'no' => array(
								'report_options' => array(
									'type' => 'addable-option',
									'value' => array(esc_html__('This is the fake', 'workreap'), 
													 esc_html__('Other', 'workreap')
												),
									'desc' => esc_html__('Report form options for projects', 'workreap'),
									'label' => esc_html__('Add report options', 'workreap'),
									'option' => array('type' => 'text'),
									'add-button-text' => esc_html__('Add', 'workreap'),
									'sortable' => true,
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
					'report_service' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Service report form', 'workreap'),
								'type' => 'select',
								'value' => 'no',
								'desc' => esc_html__('Hide report form, from the detail page?', 'workreap'),
								'choices' => array(
									'yes' => esc_html__('Yes', 'workreap'),
									'no' => esc_html__('No', 'workreap'),
								)
							)
						),
						'choices' => array(
							'no' => array(
								'report_options' => array(
									'type' => 'addable-option',
									'value' => array(esc_html__('This is the fake', 'workreap'), 
													 esc_html__('Other', 'workreap')
												),
									'desc' => esc_html__('Report form options for services', 'workreap'),
									'label' => esc_html__('Add report options', 'workreap'),
									'option' => array('type' => 'text'),
									'add-button-text' => esc_html__('Add', 'workreap'),
									'sortable' => true,
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
					'gender_settings' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Gender type setting', 'workreap'),
								'type' => 'select',
								'value' => 'yes',
								'desc' => esc_html__('These options will be used in user registration form and also in users dashboard', 'workreap'),
								'choices' => array(
									'yes' => esc_html__('Yes', 'workreap'),
									'no' => esc_html__('No', 'workreap'),
								)
							)
						),
						'choices' => array(
							'yes' => array(
								'gender_options' => array(
									'type' => 'addable-option',
									'value' => array(esc_html__('Mr', 'workreap'), 
													 esc_html__('Miss', 'workreap')
												),
									'desc' 		=> esc_html__('', 'workreap'),
									'label' 	=> esc_html__('Add options here', 'workreap'),
									'option' 	=> array('type' => 'text'),
									'add-button-text' => esc_html__('Add', 'workreap'),
									'sortable' => true,
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),

					'hide_status' => array(	
						'label' => esc_html__('Hide status?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'show',
						'desc' 	=> esc_html__('Hide online/offline status from employers and freelancers profiles', 'workreap'),
						'choices' => array(
							'show' 	=> esc_html__('Show', 'workreap'),
							'hide' 	=> esc_html__('Hide', 'workreap'),
						)
					),
					'hide_map' => array(	
						'label' => esc_html__('Hide Map?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'show',
						'desc' 	=> esc_html__('Hide map from jobs, profile and services from posting forms', 'workreap'),
						'choices' => array(
							'show' 	=> esc_html__('Show', 'workreap'),
							'hide' 	=> esc_html__('Hide', 'workreap'),
						)
					),
					'show_project_map' => array(	
						'label' => esc_html__('Show project map?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'show',
						'desc' 	=> esc_html__('Show map on project detail page', 'workreap'),
						'choices' => array(
							'show' 	=> esc_html__('Show', 'workreap'),
							'hide' 	=> esc_html__('Hide', 'workreap'),
						)
					),
					'show_service_map' => array(	
						'label' => esc_html__('Show service map?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'show',
						'desc' 	=> esc_html__('Show map on service detail page', 'workreap'),
						'choices' => array(
							'show' 	=> esc_html__('Show', 'workreap'),
							'hide' 	=> esc_html__('Hide', 'workreap'),
						)
					),
                    'search_freelancer_tpl' => array(
						'label' 		=> esc_html__('Choose Freelancer Search Page', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'posts',
						'source' 		=> 'page',
						'desc' 			=> esc_html__('Choose freelancer search template page.', 'workreap'),
						'limit' => 1,
						'prepopulate' => 100,
					), 
					'search_employer_tpl' => array(
						'label' => esc_html__('Choose Employer Search Page', 'workreap'),
						'type' => 'multi-select',
						'population' => 'posts',
						'source' => 'page',
						'desc' => esc_html__('Choose employer search template page.', 'workreap'),
						'limit' => 1,
						'prepopulate' => 100,
					), 
					'search_job_tpl' => array(
						'label' => esc_html__('Choose Job Search Page', 'workreap'),
						'type' => 'multi-select',
						'population' => 'posts',
						'source' => 'page',
						'desc' => esc_html__('Choose job search template page.', 'workreap'),
						'limit' => 1,
						'prepopulate' => 100,
					), 
					'search_services_tpl' => array(
						'label' 		=> esc_html__('Choose Service Search Page', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'posts',
						'source' 		=> 'page',
						'desc' 			=> esc_html__('Choose Service search template page.', 'workreap'),
						'limit' 		=> 1,
						'prepopulate' 	=> 100,
					),
					'dashboard_tpl' => array(
						'label' => esc_html__('Choose Dashboard Page', 'workreap'),
						'type' => 'multi-select',
						'population' => 'posts',
						'source' => 'page',
						'desc' => esc_html__('Choose dashboard template page.', 'workreap'),
						'limit' => 1,
						'prepopulate' => 100,
					),
					'calendar_format'    => array(
						'label' => esc_html__( 'Calendar Date Format', 'workreap' ),
						'type'  => 'select',
						'value'  => 'Y-m-d',
						'desc' => esc_html__('Select your calendar date format.', 'workreap'),
						'choices'	=> array(
							'Y-m-d'	  => 'Y-m-d',
							'd-m-Y'	  => 'd-m-Y',
							'Y/m/d'	  => 'Y/m/d',
							'd/m/Y'	  => 'd/m/Y',
						)
					),
					'calendar_locale'    => array(
						'label' => esc_html__( 'Calendar Language', 'workreap' ),
						'type'  => 'text',
						'value'  => '',
						'desc' => wp_kses( __( 'Add 639-1 code. It will be two digit code like "en" for english. Leave it empty to use default. Click here to get code <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank"> Get Code </a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
					),
					'shortname_option' => array(
						'type' => 'switch',
						'value' => 'disable',
						'attr' => array(),
						'label' => esc_html__('Shortened names', 'workreap'),
						'desc' => esc_html__('Enable shortened names. If enabled then First name and last name Capital letter will show. For example first name is ABC and last name is XYZ then short name will be ABC X', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'default_portfolio_images' => array(
						'type' 	=> 'slider',
						'value' => 10,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Images per portfolio', 'workreap'),
						'desc' => esc_html__('Set limit to add number of photos in portfolio add/edit', 'workreap'),
					),
					'ppt_template' => array(
						'type' => 'switch',
						'value' => 'disable',
						'attr' => array(),
						'label' => esc_html__('Articulate Content for Portofolios', 'workreap'),
						'desc' => wp_kses( __( 'Enable Articulate Content for portfolios. If enabled then you need to activate plugin "Insert or Embed Articulate Content into WordPress". <a href="https://wordpress.org/plugins/insert-or-embed-articulate-content-into-wordpress/" target="_blank"> Get Plugin </a>', 'workreap' ),array(
							'a' => array(
								'href' => array(),
								'title' => array()
							),
							'br' => array(),
							'em' => array(),
							'strong' => array(),
						)),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'hide_hideshares'  => array(
						'label' => esc_html__( 'Hide sharing?', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('Hide employers and freelances profiles and jobs sharing', 'workreap'),
						'choices'	=> array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					'counter_type' => array(
						'type' 	=> 'multi-picker',
						'label' => false,
						'desc' 	=> '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__( 'Counter limit type', 'workreap' ),
								'type'  => 'select',
								'value' => 'disable',
								'desc' => esc_html__('Hide employers and freelances profiles and jobs sharing', 'workreap'),
								'choices'	=> array(
									'disable' 	=> esc_html__('Disable', 'workreap'),
									'word' 		=> esc_html__('Word', 'workreap'),
									'character'	=> esc_html__('Character', 'workreap')
								)
							)
						),
						'choices' => array(
							'word' => array(
								$limit_fields
							),
							'character' => array(
								$limit_fields
							),
						),
					),
					'invoice_address' => array(
						'type' 	=> 'textarea',
						'value' => '',
						'label' => esc_html__('Freelancer invoice address', 'workreap'),
						'desc' => esc_html__('Add from invoice address in the freelancers invoice detail page. Leave this empty to user employer address', 'workreap'),
					),
					'invoice_address_employer' => array(
						'type' 	=> 'textarea',
						'value' => '',
						'label' => esc_html__('Employer invoice address', 'workreap'),
						'desc' => esc_html__('Add "To" invoice address in the employers invoice detail page. Leave this empty to user default address', 'workreap'),
					),
					'invoice_text' => array(
						'type' 	=> 'textarea',
						'value' => 'This is not a tax receipt or invoice',
						'label' => esc_html__('Invoice info text', 'workreap'),
						'desc' => esc_html__('Add invoice info text on the detail page. It will be print on the bottom of invoice', 'workreap'),
					),

					'skills_typehead' => array(
						'label' => esc_html__('Skills search by typeahead', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('This option can be enabled if you have more than 500 skills, it will use the local storage to search the skills', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'hide_left_menus' => array(
						'label' 		=> esc_html__('Hide items from left menu', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'choices' => array(
							'insights'   	=> esc_html__('Dashboard', 'workreap'),
							'chat'   		=> esc_html__('Inbox', 'workreap'),
							'preview'  		=> esc_html__('View profile', 'workreap'),
							'identity-verification' => esc_html__('Identity verification', 'workreap'),
							'manage-settings'   	=> esc_html__('Settings', 'workreap'),
							'manage-portfolios'		=> esc_html__('Portfolios(for freelancers)', 'workreap'),
							'manage-projects'  		=> esc_html__('Projects(for freelancers)', 'workreap'),
							'manage-jobs'  			=> esc_html__('Projects(for employers)', 'workreap'),
							'manage-services'		=> esc_html__('Services(for freelancers)', 'workreap'),
							'manage-service'		=> esc_html__('Services(for employers)', 'workreap'),
							'saved'  		=> esc_html__('Saved items', 'workreap'),
							'invoices'		=> esc_html__('Invoices', 'workreap'),
							'disputes'  	=> esc_html__('Disputes', 'workreap'),
							'help'			=> esc_html__('Support', 'workreap'),
							'packages'  	=> esc_html__('Packages', 'workreap'),
							'logout'		=> esc_html__('Logout', 'workreap'),
						),
						'value' 		=> array('insights','chat','manage-settings','invoices','packages','logout'),
						'desc' 			=> esc_html__('You can select menus to hide from left panel of user dashboard', 'workreap'),
					),
					'hide_top_menus' => array(
						'label' 		=> esc_html__('Hide items from top menu', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'choices' => array(
							'insights'   	=> esc_html__('Dashboard', 'workreap'),
							'chat'   		=> esc_html__('Inbox', 'workreap'),
							'preview'  		=> esc_html__('View profile', 'workreap'),
							'identity-verification' => esc_html__('Identity verification', 'workreap'),
							'manage-settings'   	=> esc_html__('Settings', 'workreap'),
							'manage-portfolios'		=> esc_html__('Portfolios(for freelancers)', 'workreap'),
							'manage-projects'  		=> esc_html__('Projects(for freelancers)', 'workreap'),
							'manage-jobs'  			=> esc_html__('Projects(for employers)', 'workreap'),
							'manage-services'		=> esc_html__('Services(for freelancers)', 'workreap'),
							'manage-service'		=> esc_html__('Services(for employers)', 'workreap'),
							'saved'  		=> esc_html__('Saved items', 'workreap'),
							'invoices'		=> esc_html__('Invoices', 'workreap'),
							'disputes'  	=> esc_html__('Disputes', 'workreap'),
							'help'			=> esc_html__('Support', 'workreap'),
							'packages'  	=> esc_html__('Packages', 'workreap'),
							'logout'		=> esc_html__('Logout', 'workreap'),
						),
						'value' 		=> array('preview','identity-verification','manage-portfolios','manage-projects','manage-jobs','manage-services','manage-service','saved','help','disputes'),
						'desc' 			=> esc_html__('You can select menus to hide from top panel of user dashboard', 'workreap'),
					),
                ),
            ),
			'verification-settings' => array(
                'title' => esc_html__('Verification', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'verify_section' => array(
						'type' => 'html',
						'html' => esc_html__( 'Email verification', 'workreap' ),
						'label' => esc_html__( '', 'workreap' ),
					),
					'verify_user'  => array(
						'label' => esc_html__( 'Verify User', 'workreap' ),
						'type'  => 'select',
						'value' => 'verified',
						'desc' => esc_html__('Verify users( freelancer and employers ) before publicly available. Note: If you select "Need to verify, after registration" then user will not be shown in search result until user will be verified by site owner. If you select "Verify by email" then users will get an email for verification. After clicking link user will be verified and available at the website.', 'workreap'),
						'choices'	=> array(
							'verified'  => esc_html__('Verify by email', 'workreap'),
							'none'	=> esc_html__('Need to verify, after registration', 'workreap')
						)
					),
					'social_verify_user'  => array(
						'label' => esc_html__( 'Social account verification', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' => esc_html__('', 'workreap'),
						'choices'	=> array(
							'no'  	=> esc_html__('No, verification needed', 'workreap'),
							'yes'	=> esc_html__('Verify by email', 'workreap')
						)
					),
					'id_section' => array(
						'type' => 'html',
						'html' => esc_html__( 'ID verification', 'workreap' ),
						'label' => esc_html__( '', 'workreap' ),
						'desc' => esc_html__( '', 'workreap' ),
					),
					'identity_verification' => array(
						'label' => esc_html__('Freelancer identity verification', 'workreap'),
						'type' 	=> 'select',
						'value' => 'yes',
						'desc' 	=> esc_html__('Enable freelancer identity verification, if enabled then users must have to upload identity documents to get verified', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('Disable it', 'workreap'),
							'yes'  => esc_html__('Enable it', 'workreap'),
						)
					),
					'identity_verification_post' => array(
						'label' => esc_html__('Post anything', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Allow to post anything without identity verification', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes, Allow this', 'workreap'),
							'no'  => esc_html__('No, identity verification is required to post', 'workreap'),
						)
					),
					'freelancer_idv_title' => array(
						'type' 	=> 'text',
						'value' => 'Verification required',
						'label' => esc_html__('Title', 'workreap'),
						'desc' => esc_html__('This title will appear on the banner which will alert to freelancer', 'workreap'),
					),
					'freelancer_idv_description' => array(
						'type' 	=> 'textarea',
						'value' => 'You must verify your identity, please submit the required documents to get verified. As soon as you will be verified then you will be able to apply to the jobs and get hired.',
						'label' => esc_html__('Description', 'workreap'),
						'desc' => esc_html__('This title will appear on the banner which will alert to freelancer', 'workreap'),
					),

					'employer_identity_verification' => array(
						'label' => esc_html__('Employer identity verification', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Enable employer identity verification, if enabled then users must have to upload identity documents to get verified', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('Disable it', 'workreap'),
							'yes'  => esc_html__('Enable it', 'workreap'),
						)
					),
					'employer_identity_verification_post' => array(
						'label' => esc_html__('Post anything', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Allow to post anything without identity verification', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes, Allow this', 'workreap'),
							'no'    => esc_html__('No, identity verification is required to post', 'workreap'),
						)
					),
					'employer_idv_title' => array(
						'type' 	=> 'text',
						'value' => 'Verification required',
						'label' => esc_html__('Title', 'workreap'),
						'desc' => esc_html__('This title will appear on the banner which will alert to employer', 'workreap'),
					),
					'employer_idv_description' => array(
						'type' 	=> 'textarea',
						'value' => 'You must verify your identity, please submit the required documents to get verified. As soon as you will be verified then you will be able to hire the freelancers',
						'label' => esc_html__('Description', 'workreap'),
						'desc' => esc_html__('This title will appear on the banner which will alert to employer', 'workreap'),
					),
					'after_id_section' => array(
						'type' => 'html',
						'html' => esc_html__( 'After verification document submitted', 'workreap' ),
						'label' => esc_html__( '', 'workreap' ),
						'desc' => esc_html__( '', 'workreap' ),
					),
					'after_idv_title' => array(
						'type' 	=> 'text',
						'value' => 'Woohoo!',
						'label' => esc_html__('Title', 'workreap'),
						'desc' => esc_html__('This title will appear on the banner when user will send the identity verification to admin', 'workreap'),
					),
					'after_idv_description' => array(
						'type' 	=> 'textarea',
						'value' => 'You have successfully submitted your documents. We will verify and respond to your request soon.',
						'label' => esc_html__('Description', 'workreap'),
						'desc' => esc_html__('This description will appear on the banner when user will send the identity verification to admin', 'workreap'),
					),
				)
			),
            'images-settings' => array(
                'title' => esc_html__('Images Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
                    'default_freelancer_banner' => array(
						'label' => esc_html__('Upload freelancer banner', 'workreap'),
						'desc' => esc_html__('Upload default banner image for freelancer. leave it empty to hide use from theme directory\'ry. Upload minimum size 1920x450', 'workreap'),
						'type' => 'upload',
					),
					'default_employer_banner' => array(
						'label' => esc_html__('Upload employer banner', 'workreap'),
						'desc' => esc_html__('Upload default banner image for employer. leave it empty to hide use from theme directory. Upload minimum size 1140x400', 'workreap'),
						'type' => 'upload',
					),
					'default_freelancer_avatar' => array(
						'label' => esc_html__('Upload freelancer avatar', 'workreap'),
						'desc' => esc_html__('Upload default avatar image for freelancer. leave it empty to hide use from theme directory. Upload minimum size 225x225', 'workreap'),
						'type' => 'upload',
					),
					'default_employer_avatar' => array(
						'label' => esc_html__('Upload employer avatar', 'workreap'),
						'desc' => esc_html__('Upload default avatar image for employer. leave it empty to hide use from theme directory. Upload minimum size 100x100', 'workreap'),
						'type' => 'upload',
					),
					'dir_datasize' => array(
						'type' => 'text',
						'value' => '5242880',
						'attr' => array(),
						'label' => esc_html__('Add upload size', 'workreap'),
						'desc' => esc_html__('Maximum image upload size. Max 5MB, add in bytes. for example 5MB = 5242880 ( 1024x1024x5 )', 'workreap'),
						'help' => esc_html__('', 'workreap'),
					),
					'total_freelancers' => array(
						'label' => esc_html__('Dashboard  favorite freelancers insight', 'workreap'),
						'desc' => esc_html__('Upload default favorites freelancer insight image. leave it empty to hide use from theme directory\'ry. Upload minimum size 100x100', 'workreap'),
						'type' => 'upload',
					),
					'total_employers' => array(
						'label' => esc_html__('Dashboard favorite companies insight', 'workreap'),
						'desc' => esc_html__('Upload default favorites companies insight image. leave it empty to hide use from theme directory\'ry. Upload minimum size 100x100', 'workreap'),
						'type' => 'upload',
					),
					'total_jobs' => array(
						'label' => esc_html__('Dashboard favorites jobs insight', 'workreap'),
						'desc' => esc_html__('Upload default favorites Jobs insight image. leave it empty to hide use from theme directory\'ry. Upload minimum size 100x100', 'workreap'),
						'type' => 'upload',
					),
					'total_services' => array(
						'label' => esc_html__('Dashboard favorites services insight', 'workreap'),
						'desc' => esc_html__('Upload default favorites services insight image. leave it empty to hide use from theme directory\'ry. Upload minimum size 100x100', 'workreap'),
						'type' => 'upload',
					),
					'featured_job_img' => array(
						'label' => esc_html__('Dashboard featured jobs statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard featured jobs image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'new_messages' => array(
						'label' => esc_html__('Dashboard inbox statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard inbox statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'latest_proposals' => array(
						'label' => esc_html__('Dashboard latest proposal statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard latest proposal statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'package_expiry' => array(
						'label' => esc_html__('Dashboard package expiry statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard package expiry statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'saved_items' => array(
						'label' => esc_html__('Dashboard saved items statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard saved items statistic image', 'workreap'),
						'type' => 'upload',
					),
					'total_ongoing_job' => array(
						'label' => esc_html__('Dashboard ongoing jobs statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard ongoing jobs statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'total_completed_job' => array(
						'label' => esc_html__('Dashboard completed jobs statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard completed jobs statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'total_cancelled_job' => array(
						'label' => esc_html__('Dashboard cancelled jobs statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard cancelled jobs statistic image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'total_posted_job' => array(
						'label' => esc_html__('Total posted jobs statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard total posted jobs image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'avalible_balance_img' => array(
						'label' => esc_html__('Dashboard pending  balance statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard pending balance image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'current_balance_img' => array(
						'label' => esc_html__('Dashboard available balance statistic', 'workreap'),
						'desc' => esc_html__('Upload dashboard available balance image. leave it empty to hide use default icon', 'workreap'),
						'type' => 'upload',
					),
					'total_completed_services' => array(
						'label' => esc_html__('Dashboard completed services statistic', 'workreap'),
						'desc' 	=> esc_html__('Upload default completed services insight image. leave it empty to hide use default icon', 'workreap'),
						'type' 	=> 'upload',
					),
					'total_cancelled_services' => array(
						'label' => esc_html__('Dashboard cancelled services statistic', 'workreap'),
						'desc' 	=> esc_html__('Upload default cancelled services insight image. leave it empty to hide use default icon', 'workreap'),
						'type' 	=> 'upload',
					),
					'total_ongoing_services' => array(
						'label' => esc_html__('Dashboard ongoing statistic', 'workreap'),
						'desc' 	=> esc_html__('Upload default ongoing services insight image. leave it empty to hide use default icon', 'workreap'),
						'type' 	=> 'upload',
					),
					'total_sales_services' => array(
						'label' => esc_html__('Total sold services statistic', 'workreap'),
						'desc' 	=> esc_html__('Upload default sold services insight image. leave it empty to hide use default icon', 'workreap'),
						'type' 	=> 'upload',
					),
					'nrf_favorites' => array(
						'label' => esc_html__('Favorites listings', 'workreap'),
						'desc' 	=> esc_html__('This image will be used as background for favorites users listing. Size : 200x200', 'workreap'),
						'type' 	=> 'upload',
					),
					'nrf_messages' => array(
						'label' => esc_html__('Inbox', 'workreap'),
						'desc' 	=> esc_html__('This image will be used as background for inbox. Size : 200x200', 'workreap'),
						'type' 	=> 'upload',
					),
					'nrf_create' => array(
						'label' => esc_html__('Create record/articles/projects', 'workreap'),
						'desc' 	=> esc_html__('This image will be used as background for create listings. Size : 200x200', 'workreap'),
						'type' 	=> 'upload',
					),
					'nrf_found' => array(
						'label' => esc_html__('No record found', 'workreap'),
						'desc' 	=> esc_html__('This image will be used as background for no record found. Size : 200x200', 'workreap'),
						'type' 	=> 'upload',
					),
					'payout_bank' => array(
						'label' => esc_html__('Payouts Bank transfer', 'workreap'),
						'desc' 	=> esc_html__('Please upload payouts bank transfer image. Size : 100x30', 'workreap'),
						'type' 	=> 'upload',
					),
					'payout_paypal' => array(
						'label' => esc_html__('Payouts PayPal', 'workreap'),
						'desc' 	=> esc_html__('Please upload payouts PayPal image. Size : 100x30', 'workreap'),
						'type' 	=> 'upload',
					),
					'payout_payoneer' => array(
						'label' => esc_html__('Payouts Payoneer', 'workreap'),
						'desc' 	=> esc_html__('Please upload payouts Payoneer image. Size : 100x30', 'workreap'),
						'type' 	=> 'upload',
					),
					'email_verify_icon' => array(
						'label' => esc_html__('Email verify icon', 'workreap'),
						'desc' 	=> esc_html__('Upload email verify icon. Size should be 22x15', 'workreap'),
						'type' 	=> 'upload',
					),
					'identity_verify_icon' => array(
						'label' => esc_html__('Identity verify icon', 'workreap'),
						'desc' 	=> esc_html__('Upload identity verify icon. Size should be 22x15', 'workreap'),
						'type' 	=> 'upload',
					),
					'identity_verified_image' => array(
						'label' => esc_html__('Identity verified banner', 'workreap'),
						'desc' 	=> esc_html__('Upload identity verified banner. This will show in freelancer Identity verification section', 'workreap'),
						'type' 	=> 'upload',
					),
					'default_service_banner' => array(
						'label' => esc_html__('Service banner', 'workreap'),
						'desc' 	=> esc_html__('Upload default service banner image if user didn\'t uploaded any image while posting a service', 'workreap'),
						'type' 	=> 'upload',
					),
					'fr_project_placeholder' => array(
						'label' => esc_html__('Freelancer default projects', 'workreap'),
						'desc' 	=> esc_html__('Upload default image for the freelancer projects', 'workreap'),
						'type' 	=> 'upload',
					),
                ),
			),
			'jobs-settings' => array(
                'title' => esc_html__('Jobs Settings', 'workreap'),
                'type' 	=> 'tab',
                'options' => array(
					'job_add_edit' => array(
						'type' 	=> 'html',
						'html' 	=> esc_html__('Jobs add/edit fields options', 'workreap'),
						'label' => esc_html__('', 'workreap'),
						'desc' 	=> wp_kses( __( 'You can remove job options from the job add/edit forms.', 'workreap'),array(
											'a' => array(
												'href' => array(),
												'title' => array()
											),
											'br' 		=> array(),
											'em' 		=> array(),
											'strong' 	=> array(),
										)),
						'help' => esc_html__('', 'workreap'),
					),
					'update_status_project' => array(
						'label' => esc_html__('Under review project after changes', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Make the projects under review once employer made any changes in the project. Admin will get an email to publish it', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'remove_project_level' => array(
						'label' => esc_html__('Remove project level', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove project level from job posting and listing pages', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'remove_project_attachments' => array(
						'label' => esc_html__('Remove project attachments', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove project attachment from job posting and edit pages', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'remove_project_duration' => array(
						'label' => esc_html__('Remove project duration', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove project duration from job posting and listing pages', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					
                    'job_option' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Jobs location type', 'workreap'),
						'desc' 	=> esc_html__('Enable or disable job location type. On enable job location will show Onsite, Partial Onsite and Remote', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'job_experience_option' => array(
						'type' 	=> 'multi-picker',
						'label' => false,
						'desc' 	=> '',
						'picker' => array(
							'gadget' => array(
								'type' 	=> 'switch',
								'value' => 'disable',
								'attr' 	=> array(),
								'label' => esc_html__('Experience', 'workreap'),
								'desc' 	=> esc_html__('Enable or disable experience', 'workreap'),
								'left-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
							)
						),
						'choices' => array(
							'enable' => array( 
								'multiselect_experience' => array(
									'type' 	=> 'switch',
									'value' => 'disable',
									'attr' 	=> array(),
									'label' => esc_html__('Experience selection type', 'workreap'),
									'desc' 	=> esc_html__('Enable it to make experience type multiselect. Default would be single select', 'workreap'),
									'left-choice' => array(
										'value' => 'single',
										'label' => esc_html__('Single select', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'multiselect',
										'label' => esc_html__('Multi-select', 'workreap'),
									),
								),
							)
						)
					),
					'project_type_show' => array(
						'label' => esc_html__('Show project types', 'workreap'),
						'type' 	=> 'select',
						'value' => 'both',
						'desc' 	=> esc_html__('You can remove one of the project type while submitting a project or show both', 'workreap'),
						'choices' => array(
							'hourly'   	=> esc_html__('Remove hourly', 'workreap'),
							'fixed'  	=> esc_html__('Remove fixed', 'workreap'),
							'both'  	=> esc_html__('Show both', 'workreap'),
						)
					),
					'remove_english_level' => array(
						'label' => esc_html__('English level', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove english level from job add/edit', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_freelancer_type' => array(
						'label' => esc_html__('Freelancer type', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove freelancer_type from job add/edit', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_languages' => array(
						'label' => esc_html__('Languages', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove languages from job add/edit', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'multiselect_freelancertype' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Freelancer type selection', 'workreap'),
						'desc' 	=> esc_html__('Enable it to make freelancer type multiselect. Default would be single select', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Single select', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Multi-select', 'workreap'),
						),
					),
					'job_milestone_option' => array(
						'type' 	=> 'multi-picker',
						'label' => false,
						'desc' 	=> '',
						'picker' => array(
							'gadget' => array(
								'type' 	=> 'switch',
								'value' => 'disable',
								'attr' 	=> array(),
								'label' => esc_html__('Job milestone', 'workreap'),
								'desc' 	=> esc_html__('Enable or disable Job milestone', 'workreap'),
								'left-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
							)
						),
						'choices' => array(
							'enable' => array( 
								'total_budget'  => array(
									'label' => esc_html__( 'Budget image', 'workreap' ),
									'type'  => 'upload',
									'desc' 	=> esc_html__('Upload budget image', 'workreap')
								),
								'in_escrow'  => array(
									'label' => esc_html__( 'In escrow image', 'workreap' ),
									'type'  => 'upload',
									'desc' 	=> esc_html__('Upload in escrow image', 'workreap')
								),
								
								'milestone_paid'  => array(
									'label' => esc_html__( 'Milestone paid image', 'workreap' ),
									'type'  => 'upload',
									'desc' 	=> esc_html__('Upload milestone paid image', 'workreap')
								),
								
								'remainings'  => array(
									'label' => esc_html__( 'Remainings image', 'workreap' ),
									'type'  => 'upload',
									'desc' 	=> esc_html__('Upload remianings image', 'workreap')
								),
								
							)
						)
					),
					'job_level_img'  => array(
						'label' => esc_html__( 'Job level image', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload job level image', 'workreap')
					),
					'job_duration_img'  => array(
						'label' => esc_html__( 'Job duration', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload job duration', 'workreap')
					),
					
					'job_type_img'  => array(
						'label' => esc_html__( 'Job type', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload Job type', 'workreap')
					),
					
					'project_type_img'  => array(
						'label' => esc_html__( 'Project type', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload project type', 'workreap')
					),
					'job_save_img'  => array(
						'label' => esc_html__( 'Job save', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload job save', 'workreap')
					),
					'job_expiry_img'  => array(
						'label' => esc_html__( 'Project deadline', 'workreap' ),
						'type'  => 'upload',
						'desc' 	=> esc_html__('Upload project deadline', 'workreap')
					),
					'job_others' => array(
						'type' 	=> 'html',
						'html' 	=> esc_html__('Jobs other settings', 'workreap'),
						'label' => esc_html__('', 'workreap'),
						'desc' 	=> wp_kses( __( '', 'workreap'),array(
											'a' => array(
												'href' 	=> array(),
												'title' => array()
											),
											'br' 		=> array(),
											'em' 		=> array(),
											'strong' 	=> array(),
										)),
						'help' => esc_html__('', 'workreap'),
					),
					'job_price_option' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Jobs price range', 'workreap'),
						'desc' 	=> esc_html__('Enable/Disable jobs price range options', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'attachment_display' => array(
						'label' => esc_html__('Job attachment display', 'workreap'),
						'type' 	=> 'select',
						'value' => 'list',
						'desc' 	=> esc_html__('You can either show attachment in list view or in grid view on the job detail page', 'workreap'),
						'choices' => array(
							'list'   	=> esc_html__('List', 'workreap'),
							'grid'  	=> esc_html__('Grid', 'workreap'),
						)
					),
					'cron_job_interval' => array(
						'label'   		=> esc_html__( 'Cron job interval', 'workreap' ),
						'desc'   		=> esc_html__( 'Select interval for job alerts.', 'workreap' ),
						'type'    		=> 'select',
						'value'    		=> 'basic',
						'choices' 		=> $schedules_list
					),
					'job_faq_option' => array(
						'label' => esc_html__('FAQ on job posting', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Option for FAQ on project', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'remove_location_job' => array(
						'label' => esc_html__('Remove job location', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove location options while posting a job', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'project_required' => array(
						'label' 		=> esc_html__('Required fields in project', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'value' 		=> array( 'skills'),
						'choices' 		=> $job_required,
						'desc' 			=> esc_html__('Select to make the fields mandatory in project add/edit', 'workreap'),
						'prepopulate' 	=> 100,
					),
					'project_search_status' => array(
						'label' 		=> esc_html__('Project status', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'choices' => array(
							'publish'   => esc_html__('Published', 'workreap'),
							'completed'	=> esc_html__('Completed', 'workreap'),
							'hired'  	=> esc_html__('Hired', 'workreap')
						),
						'value' 		=> array('publish'),
						'desc' 			=> esc_html__('Select project status to show in the search result page', 'workreap'),
					),
	
					'allow_delete_project' => array(
                        'label' => esc_html__('Allow delete projects', 'workreap'),
                        'type' 	=> 'select',
                        'value' => 'no',
                        'desc' 	=> esc_html__('Allow delete projects from the front-end, if project status is published(not hired). Also this will delete the proposals and send an email to freelancers who applied on the project', 'workreap'),
                        'choices' => array(
                            'yes'   => esc_html__('Yes', 'workreap'),
                            'no'  	=> esc_html__('No', 'workreap'),
                        )
                    ),
                ),
			), 
			'company-settings' => array(
                'title' => esc_html__('Company Settings', 'workreap'),
                'type' 	=> 'tab',
                'options' => array(
					'company_name' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Company name', 'workreap'),
						'desc' 	=> esc_html__('Enable/Disable company name in employers profile', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'company_job_title' => array(
						'type' => 'switch',
						'value' => 'disable',
						'attr' => array(),
						'label' => esc_html__('Job title', 'workreap'),
						'desc' => esc_html__('Enable/ the job title/field of work option', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'hide_brochures' => array(	
						'label' => esc_html__('brochures?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Hide brochures from user dashboard?', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 		=> esc_html__('No', 'workreap'),
						)
					),
					'hide_departments' => array(	
						'label' => esc_html__('Hide departments and employees?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Hide departments and employees from signup, employer dashboard and from search filters?', 'workreap'),
						'choices' => array(
							'signup' 	=> esc_html__('Remove only from signup form.', 'workreap'),
							'both' 		=> esc_html__('Remove from signup form and dashboard', 'workreap'),
							'site' 		=> esc_html__('Remove all over the site', 'workreap'),
							'no' 		=> esc_html__('Donot remove', 'workreap'),
						)
					),
					'hide_payout_employers' => array(	
						'label' => esc_html__('Hide payouts from employers account?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'yes',
						'desc' 	=> esc_html__('This is for the refund purpose, you can show to employers their available balance or hide it', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Hide it', 'workreap'),
							'no' 		=> esc_html__('Show it', 'workreap'),
						)
					),
					'hide_emp_detail' => array(	
						'label' => esc_html__('Hide employer detail page', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('On enable this options, no one user can see the employer detail page', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Hide it', 'workreap'),
							'no' 		=> esc_html__('Show it', 'workreap'),
						)
					),
					'employer_insights' => array(
						'label' => esc_html__('What insights to hide?', 'workreap'),
						'type' => 'multi-select',
						'population' => 'array',
						'value' => array(),
						'choices' => array(
							'messages' 			=> esc_html__('Hide message box', 'workreap'),
							'latest_proposal' 	=> esc_html__('Latest proposals', 'workreap'),
							'expiry_box' 		=> esc_html__('Package expiry box', 'workreap'),
							'saved_items' 		=> esc_html__('Saved items', 'workreap'),
							'available_balance' => esc_html__('Available balance', 'workreap'),
							'jobs' 				=> esc_html__('Job related boxes', 'workreap'),
							'services' 			=> esc_html__('Services related boxes', 'workreap'),
							'ongoing_projects' 	=> esc_html__('Ongoing projects', 'workreap'),
						),
						'desc' => esc_html__('You can select what insights on the dashboard do you want to hide?', 'workreap'),
						'prepopulate' => 100,
					),
					'employer_project_status' => array(
						'label' 		=> esc_html__('Select project statuses', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'value' 		=> array('published'),
						'choices' => array(
							'publish' 		=> esc_html__('Published', 'workreap'),
							'hired' 			=> esc_html__('Hired', 'workreap'),
							'completed' 		=> esc_html__('Completed', 'workreap'),
							'pending' 			=> esc_html__('Pending', 'workreap')
						),
						'desc' => esc_html__('You can select project status that project list on employer detail page', 'workreap'),
						'prepopulate' => 100,
					),
					'employer_profile_required' => array(
						'label' 		=> esc_html__('Required fields in profile', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'value' 		=> array( 'first_name','last_name','display_name','country', 'tag_line' ),
						'choices' 		=> $employers_required,
						'desc' 			=> esc_html__('Choose profile fields those are required in profile submission', 'workreap'),
						'prepopulate' 	=> 100,
					),
				),
			),
			'freelancer-settings' => array(
                'title' => esc_html__('Freelancer Settings', 'workreap'),
                'type' 	=> 'tab',
                'options' => array(
					'update_status_freelancer' => array(
						'label' => esc_html__('Under review freelancer after changes', 'workreap'),
						'type'	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Make the freelancers profiles under review once freelancer made any changes in the profile. Admin will get an email to publish it', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'freelancer_price_option' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Per hour rate range', 'workreap'),
						'desc' 	=> esc_html__('Enable Per hour rate range fields', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'freelancer_stats' => array(	
						'label' => esc_html__('Hide freelancer stats', 'workreap'),
						'type' 	=> 'select',
						'value' => 'show',
						'desc' 	=> esc_html__('Hide freelancer stats on freelancer detail page', 'workreap'),
						'choices' => array(
							'show' 	=> esc_html__('Show', 'workreap'),
							'hide' 	=> esc_html__('Hide', 'workreap'),
						)
					),
					
					'hide_freelancer_earning' => array(	
						'label' => esc_html__('Hide freelancer earning', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Hide freelancer earning stat', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'freelancer_gallery_option' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Gallery', 'workreap'),
						'desc' 	=> esc_html__('Enable or disable gallery.', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'frc_remove_freelancer_type' => array(	
						'label' => esc_html__('Remove freelancer type', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove freelancer type in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'frc_remove_awards' => array(	
						'label' => esc_html__('Remove awards', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove awards in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'frc_remove_experience' => array(	
						'label' => esc_html__('Remove experience', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove experience in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'frc_remove_education' => array(	
						'label' => esc_html__('Remove education', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove education in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'freelancertype_multiselect' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Freelancer type', 'workreap'),
						'desc' 	=> esc_html__('Enable it to make freelancer type multiselect. Default would be single select', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Single', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Multiselect', 'workreap'),
						),
					),
					'freelancer_industrial_experience' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Freelancer industrial experience', 'workreap'),
						'desc' 	=> esc_html__('Enable or disable freelancer industrial experience settings', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'freelancer_specialization' => array(
						'type' 	=> 'switch',
						'value' => 'disable',
						'attr' 	=> array(),
						'label' => esc_html__('Freelancer specialization', 'workreap'),
						'desc' 	=> esc_html__('Enable or disable freelancer specialization.', 'workreap'),
						'left-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
					),
					'frc_remove_languages' => array(	
						'label' => esc_html__('Remove languages options', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove language options in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'frc_english_level' => array(	
						'label' => esc_html__('Remove english level', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can remove english level in profile settings', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						)
					),
					'display_type' => array(	
						'label' => esc_html__('Display type for skills, specialization and industrial experience', 'workreap'),
						'type' 	=> 'select',
						'value' => 'any',
						'desc' 	=> esc_html__('Select display type for skills, specialization and industrial experience', 'workreap'),
						'choices' => array(
							'number' 	=> esc_html__('Percentage', 'workreap'),
							'year' 		=> esc_html__('Years', 'workreap'),
						)
					),
					'freelancer_profile_required' => array(
						'label' 		=> esc_html__('Required fields in profile', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'value' 		=> array( 'first_name','last_name','display_name','country', 'tag_line' ),
						'choices' 		=> $freelancer_required,
						'desc' 			=> esc_html__('Choose profile fields those are required in profile submission', 'workreap'),
						'prepopulate' 	=> 100,
					),
	
					'freelancer_insights' => array(
						'label' => esc_html__('What insights to hide?', 'workreap'),
						'type' => 'multi-select',
						'population' => 'array',
						'value' => array(),
						'choices' => array(
							'messages' 			=> esc_html__('Hide message box', 'workreap'),
							'latest_proposal' 	=> esc_html__('Latest proposals', 'workreap'),
							'expiry_box' 		=> esc_html__('Package expiry box', 'workreap'),
							'saved_items' 		=> esc_html__('Saved items', 'workreap'),
							'pending_balance' 	=> esc_html__('Pending Balance', 'workreap'),
							'available_balance' => esc_html__('Available balance', 'workreap'),
							'jobs' 			=> esc_html__('Job related boxes', 'workreap'),
							'services' 		=> esc_html__('Services related boxes', 'workreap'),
							'earnings' 		=> esc_html__('Earning Box', 'workreap'),
						),
						'desc' => esc_html__('You can select what insights on the dashboard do you want to hide?', 'workreap'),
						'prepopulate' => 100,
					),
					
					'allow_skills' => array(
						'label' => esc_html__('Allow custom skills?', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('You can enable allow custom skills to add from front-end', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes, Allow Skills', 'workreap'),
						)
					),
					'portfolio' => array(
						'type' 	=> 'multi-picker',
						'label' => false,
						'desc' 	=> '',
						'picker' => array(
							'gadget' => array(
								'label' => esc_html__('Enable portfolio', 'workreap'),
								'type' => 'select',
								'value' => 'enable',
								'desc' => esc_html__('Enable portfolio for freelancers', 'workreap'),
								'choices' => array(
									'enable' 	=> esc_html__('Enable', 'workreap'),
									'hide' 		=> esc_html__('Hide it', 'workreap'),
								)
							)
						),
						'choices' => array(
							'enable' => array(
								'others' => array(
									'label'   		=> esc_html__( 'Hide default setttings', 'workreap' ),
									'desc'   		=> esc_html__( 'Hide videos, gallery images and crafted projects', 'workreap' ),
									'type'    		=> 'select',
									'value'    		=> 'no',
									'choices'	=> array(
										'no'   	=> esc_html__('No ( Show it )', 'workreap'),
										'yes'	=> esc_html__('Yes ( Hide it )', 'workreap')
									)
								),
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
					'hide_freelancer_perhour' => array(
						'label'   		=> esc_html__( 'Per hour rate', 'workreap' ),
						'desc'   		=> esc_html__( 'Hide freelancer per hour rate all over the site', 'workreap' ),
						'type'    		=> 'select',
						'value'    		=> 'no',
						'choices'	=> array(
							'no'   	=> esc_html__('No ( Show it )', 'workreap'),
							'yes'	=> esc_html__('Yes ( Hide it )', 'workreap')
						)
					),
					'upload_resume'  => array(
						'label' => esc_html__( 'Upload Resume', 'workreap' ),
						'type'  => 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Enable the options to upload resume for freelancers', 'workreap'),
						'choices'	=> array(
							'yes'  => esc_html__('Yes', 'workreap'),
							'no'	=> esc_html__('No', 'workreap')
						)
					),
					'freelancer_faq_option' => array(
						'label' => esc_html__('FAQ for freelancer', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Option for FAQ on freelancer', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					/*'specialitization_limit' => array(
						'type' => 'slider',
						'value' => 50,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Specialitization limt?', 'workreap'),
						'desc' => esc_html__('Set limit to add number of specialitization in freelancer dashboard', 'workreap'),
					),*/
                ),
			),       
			'services-settings' => array(
                'title' => esc_html__('Services Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'update_status_service' => array(
						'label' => esc_html__('Under review service after changes', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Make the services under review once freelancer made any changes in the services. Admin will get an email to publish it', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'minimum_service_price' => array(
						'type' 	=> 'slider',
						'value' => 5,
						'properties' => array(
							'min' => 1,
							'max' => 2100,
							'sep' => 1,
						),
						'label' => esc_html__('Minimum service price', 'workreap'),
						'desc' 	=> esc_html__('Set the minimum price for the service to post', 'workreap'),
					),
					'service_images_required' => array(
						'label' => esc_html__('Service images required', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Enable it to ask the freelancers to upload at-least one image for service posting', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
                    'default_service_images' => array(
						'type' 	=> 'slider',
						'value' => 10,
						'properties' => array(
							'min' => 1,
							'max' => 200,
							'sep' => 1,
						),
						'label' => esc_html__('Images per service', 'workreap'),
						'desc' => esc_html__('Set limit to add number of photos in service add/edit', 'workreap'),
					),
					'remove_response_time' => array(
						'label' => esc_html__('Remove response time', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service response time options from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_dilivery_time' => array(
						'label' => esc_html__('Remove dilivery time', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service dilivery time options from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_service_videos' => array(
						'label' => esc_html__('Remove videos options', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service videos options from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_service_addon' => array(
						'label' => esc_html__('Remove service addons', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service addon options from all over the theme', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_service_languages' => array(
						'label' => esc_html__('Remove service languages', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service languages from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_service_english_level' => array(
						'label' => esc_html__('Remove service english level', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' => esc_html__('Remove service english level from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'remove_service_downloadable' => array(
						'label' => esc_html__('Remove service downloadable', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove service downloadable from service posting', 'workreap'),
						'choices' => array(
							'no'   => esc_html__('No', 'workreap'),
							'yes'  => esc_html__('Yes', 'workreap'),
						)
					),
					'services_categories' => array(
						'label' => esc_html__('Service categories', 'workreap'),
						'type' 	=> 'select',
						'value' => 'yes',
						'desc' 	=> esc_html__('Use separate service categories instead of project categories.', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'services_layout'  => array(
						'label' => esc_html__( 'Services Layout', 'workreap' ),
						'type'  => 'select',
						'value' => 'two',
						'desc' 	=> esc_html__('Select services layout on search result page.', 'workreap'),
						'choices'	=> array(
							'two'  	=> esc_html__('Two Column', 'workreap'),
							'three'	=> esc_html__('Three column', 'workreap'),
							'four'	=> esc_html__('Four column', 'workreap')
						)
					),
					'service_faq_option' => array(
						'label' => esc_html__('FAQ on service posting', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Option for FAQ on service', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'service_video_option' => array(
						'label' => esc_html__('Videos option on listing page', 'workreap'),
						'type' 	=> 'select',
						'value' => 'yes',
						'desc' 	=> esc_html__('Show videos on service listing page', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'remove_location_services' => array(
						'label' => esc_html__('Remove service location', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('Remove location options while add/edit a service', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
					'service_quote' => array(
						'label' => esc_html__('Service quote', 'workreap'),
						'type' 	=> 'select',
						'value' => 'no',
						'desc' 	=> esc_html__('This will enable send quote options in freelancer dashboard. After mutual discussions, freelancer will send a quote to employer and employer will be able to accept or reject.', 'workreap'),
						'choices' => array(
							'yes'   => esc_html__('Yes', 'workreap'),
							'no'  	=> esc_html__('No', 'workreap'),
						)
					),
                ),
            ),
			'review-settings' => array(
                'title' => esc_html__('Reviews Settings', 'workreap'),
                'type' 	=> 'tab',
                'options' => array(
                    'project_ratings' => array(
						'type' 	=> 'addable-option',
						'value' => array(
										esc_html__('How was my proffesional behaviour?', 'workreap'), 
										esc_html__('How was my quality of work?', 'workreap'), 
										esc_html__('Was I focused to deadline?', 'workreap'),
										esc_html__('Was it worth it having my services?', 'workreap')
									),
						'label' => esc_html__('Rating Headings', 'workreap'),
						'desc' => esc_html__('Add leave your rating headings.', 'workreap'),
						'option' => array('type' => 'text'),
						'add-button-text' => esc_html__('Add', 'workreap'),
						'sortable' => true,
					),
					'cus_services_reviews' => array(
											'type' => 'html',
											'html' => esc_html__('Reviews for Services', 'workreap'),
											'label'=> esc_html__('', 'workreap'),
											'desc' => esc_html__('Add Question for services reviews.', 'workreap'),
											'help' => esc_html__('', 'workreap'),
											'images_only' => true,
										),
                    'services_ratings' => array(
						'type' => 'addable-option',
						'value' => array(esc_html__('How was my proffesional behaviour?', 'workreap'), 
										 esc_html__('How was my quality of work?', 'workreap'), 
										 esc_html__('Was I focused to deadline?', 'workreap'),
										 esc_html__('Was it worth it having my services?', 'workreap')
									),
						'label' 			=> esc_html__('Rating Headings', 'workreap'),
						'desc' 				=> esc_html__('Add leave your rating headings.', 'workreap'),
						'option' 			=> array('type' => 'text'),
						'add-button-text' 	=> esc_html__('Add', 'workreap'),
						'sortable' 			=> true,
					)
                ),
            ),
			'help-settings' => array(
                'title' => esc_html__('Help settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
                    'general' => array(
                        'title' => esc_html__('Help settings.', 'workreap'),
                        'type' 	=> 'tab',
                        'options' => array(
                            'help-group' => array(
                                'type' 	 => 'group',
                                'options' => array(              
                                    'help_support' => array(
                                        'type' 	=> 'multi-picker',
                                        'label' => false,
                                        'desc' 	=> '',
                                        'picker' => array(
                                            'gadget' => array(
                                                'type' 	=> 'switch',
                                                'value' => 'disable',
                                                'attr' 	=> array(),
                                                'label' => esc_html__('Help and Support', 'workreap'),
                                                'desc' 	=> esc_html__('Enable/Disable help and Support.', 'workreap'),
                                                'left-choice' => array(
                                                    'value' => 'disable',
                                                    'label' => esc_html__('Disable', 'workreap'),
                                                ),
                                                'right-choice' => array(
                                                    'value' => 'enable',
                                                    'label' => esc_html__('Enable', 'workreap'),
                                                ),
                                            )
                                        ),
                                        'choices' => array(
                                            'enable' => array( 
												'help_title' => array(
													'label' => esc_html__('Help and Support Heading?', 'workreap'),
													'type' 	=> 'text',
													'value' => ''
												),
												'help_desc' => array(
													'type' 	=> 'textarea',
													'value' => '',
													'label' => esc_html__('Help Description', 'workreap'),
												),
                                                'faq' => array(
													'label' => esc_html__('FAQ', 'workreap'),
													'type' 	=> 'addable-popup',
													'value' => array(),
													'desc' 	=> esc_html__('Add Question and answer for help and Support.', 'workreap'),
													'popup-options' => array(
														'faq_question' => array(
															'label' 	=> esc_html__('Question', 'workreap'),
															'type' 		=> 'text',
															'value' 	=> '',
															'desc' 		=> esc_html__('The Question for help and Support', 'workreap')
														),
														'faq_answer' => array(
															'label' 	=> esc_html__('Answer', 'workreap'),
															'type' 		=> 'wp-editor',
															'value' 	=> '',
															'desc' 		=> esc_html__('', 'workreap')
														),
													),
													'template' => '{{- faq_question }}',
												),
												'contact_subject' => array(
													'type' => 'addable-option',
													'value' => array(
																	esc_html__('Query', 'workreap'), 
																	 esc_html__('Query Type', 'workreap'), 
																),
													'label' 			=> esc_html__('Contact subjects', 'workreap'),
													'desc' 				=> esc_html__('Add contact subjects.', 'workreap'),
													'option' 			=> array('type' => 'text'),
													'add-button-text' 	=> esc_html__('Add', 'workreap'),
													'sortable' 			=> true,
												),
                                            ),
                                            'default' => array(),
                                        ),
                                        'show_borders' => false,
                                    ),                          
                                )
                            ),
                        )
                    ),
				)
			),
        )
    )
);