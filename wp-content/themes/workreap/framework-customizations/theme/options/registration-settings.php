<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'registration' => array(
        'title' => esc_html__('Registration Settings', 'workreap'),
        'type' => 'tab',
        'options' => array( 
            'general' => array(
				'title' => esc_html__('Registration Settings.', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'step-one-group' => array(
						'type' => 'group',
						'options' => array(              
							'enable_login_register' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => '',
								'picker' => array(
									'gadget' => array(
										'type' => 'switch',
										'value' => 'disable',
										'attr' => array(),
										'label' => esc_html__('Login/Register ?', 'workreap'),
										'desc' => esc_html__('Enable/Disable login/register link.', 'workreap'),
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
										//testing
										'login_signup_type' => array(
											'type' => 'select',
											'value' => 'pages',
											'attr' => array(),
											'label' => esc_html__('Login signup type?', 'workreap'),
											'desc' => esc_html__('Either pages or in POPUPS', 'workreap'),
											'choices' => array(
												'pages' => esc_html__('Pages', 'workreap'),
												'popup' => esc_html__('POPUPS', 'workreap'),
												'single_step' => esc_html__('Single Step Registration', 'workreap'),
											)
										),
										'remove_role_registration' => array(
											'type' => 'select',
											'attr' => array(),
											'value' => 'both',
											'label' => esc_html__('Remove specific role', 'workreap'),
											'desc' => esc_html__('Remove specific role for the registration', 'workreap'),
											'choices' => array(
												'both' 			=> esc_html__('Show both', 'workreap'),
												'employers' 	=> esc_html__('Employers', 'workreap'),
												'freelancers' 	=> esc_html__('Freelancers', 'workreap'),
											)
										),
										'default_role' => array(
											'type' => 'select',
											'value' => 'freelancer',
											'label' => esc_html__('Default role', 'workreap'),
											'desc' 	=> esc_html__('Select default role on registration page which will be selected by default.', 'workreap'),
											'choices' => array(
												'employer' 		=> esc_html__('Employers', 'workreap'),
												'freelancer' 	=> esc_html__('Freelancers', 'workreap'),
											)
										),
										'remove_username' => array(
											'type' => 'select',
											'value' => 'no',
											'label' => esc_html__('Remove username from registration', 'workreap'),
											'desc' 	=> esc_html__('Remove username from registration form and use email as username', 'workreap'),
											'choices' => array(
												'yes' 		=> esc_html__('Yes', 'workreap'),
												'no' 		=> esc_html__('No', 'workreap'),
											)
										),
										'single_step_image' => array(
											'type' => 'upload',
											'label' => esc_html__('Image', 'workreap'),
											'hint' => esc_html__('', 'workreap'),
											'desc' => esc_html__('Upload Image to be shown on right page.This is only show if login type is Single Step Registration.', 'workreap'),
											'images_only' => true,
										),
										'single_step_logo' => array(
											'type' => 'upload',
											'label' => esc_html__('Logo Image', 'workreap'),
											'hint' => esc_html__('', 'workreap'),
											'desc' => esc_html__('Upload Image to be shown top.This is only show if login type is Single Step Registration.', 'workreap'),
											'images_only' => true,
										),
										'registration' => array(
											'type' => 'multi-picker',
											'label' => false,
											'desc' => '',
											'picker' => array(
												'gadget' => array(
													'type' => 'switch',
													'value' => 'disable',
													'attr' => array(),
													'label' => esc_html__('Enable Registration Form?', 'workreap'),
													'desc' => esc_html__('Enable/Disable login/register link.', 'workreap'),
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
													'hide_loaction' => array(
														'type' => 'select',
														'value' => 'no',
														'label' => esc_html__('Hide Location field', 'workreap'),
														'desc' => esc_html__('Select Yes for hide location field on registration form.', 'workreap'),  
														'choices' => array(
															'no' 	=> esc_html__('No', 'workreap'),
															'yes' 	=> esc_html__('Yes', 'workreap'),
														)                                   
													),                                 
													'term_text' => array(
														'type' => 'textarea',
														'value' => '',
														'label' => esc_html__('Terms Text.', 'workreap'),
														'desc' => esc_html__('Add terms & Conditions text, which will serve as description on registration process', 'workreap'),                                      
													),  
													'terms_link' => array(
														'label' => esc_html__('Terms page?', 'workreap'),
														'type' => 'multi-select',
														'population' => 'posts',
														'source' => 'page',
														'desc' => esc_html__('Choose term page', 'workreap'),
														'limit' => 1,
														'prepopulate' => 100,
													),
												),
												'default' => array(),
											),
											'show_borders' => false,
										),                                            
										'login' => array(
											'type' => 'switch',
											'value' => 'enable',
											'attr' => array(),
											'label' => esc_html__('Login?', 'workreap'),
											'desc' => esc_html__('Enable login form.', 'workreap'),
											'left-choice' => array(
												'value' => 'disable',
												'label' => esc_html__('Disable', 'workreap'),
											),
											'right-choice' => array(
												'value' => 'enable',
												'label' => esc_html__('Enable', 'workreap'),
											),
										),
										'login_reg_page' => array(
											'label' => esc_html__('Registration page template', 'workreap'),
											'type' => 'multi-select',
											'population' => 'posts',
											'source' => 'page',
											'desc' => esc_html__('Choose registration page template', 'workreap'),
											'limit' => 1,
											'prepopulate' => 100,
										), 
										'login_page' => array(
											'label' 	=> esc_html__('Login page template', 'workreap'),
											'type' 		=> 'multi-select',
											'population' => 'posts',
											'source'	=> 'page',
											'desc' 		=> esc_html__('Choose login page template.', 'workreap'),
											'limit' 	=> 1,
											'prepopulate' => 100,
										),                                            
									),
									'default' => array(),
								),
								'show_borders' => false,
							),                          
						)
					),
					'phone_option' => array(
						'type' => 'multi-picker',
						'label' => false,
						'desc' => '',
						'picker' => array(
							'gadget' => array(
								'type' => 'switch',
								'value' => 'enable',
								'attr' => array(),
								'label' => esc_html__('Enable phone number', 'workreap'),
								'desc' => esc_html__('Enable/Disable phone number.', 'workreap'),
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
								'phone_option_registration' => array(
									'type' => 'switch',
									'value' => 'enable',
									'attr' => array(),
									'label' => esc_html__('Enable for registration', 'workreap'),
									'desc' 	=> esc_html__('Enable/Disable phone for the registration form.', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
								'phone_mandatory' => array(
									'type' 	=> 'switch',
									'value' => 'disable',
									'attr' 	=> array(),
									'label' => esc_html__('Mandatory field', 'workreap'),
									'desc' 	=> esc_html__('Yes/No mandatory field on form.', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('No', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Yes', 'workreap'),
									),
								),
								
							),
							'default' => array(),
						),
						'show_borders' => false,
					),
					'password_strength' => array(
						'label' 		=> esc_html__('Password strength', 'workreap'),
						'type' 			=> 'multi-select',
						'population' 	=> 'array',
						'choices' => array(
							'length'   			=> esc_html__('Password length minimum 8 characters', 'workreap'),
							'upper'				=> esc_html__('1 Upper case letter', 'workreap'),
							'lower'  			=> esc_html__('1 Lower case letter', 'workreap'),
							'special_character' => esc_html__('Must have 1 special character', 'workreap'),
							'number'  			=> esc_html__('Must have 1 number', 'workreap')
						),
						'value' 		=> array('length'),
						'desc' 			=> esc_html__('You can select password strength options from above. Default is 8 minimum characters', 'workreap'),
					),
				)
			),
			'step-one' => array(
				'title' => esc_html__('Step One Settings', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'step-one-group' => array(
						'type' => 'group',
						'options' => array(
							'step_one_title' => array(
								'type' => 'text',
								'value' => '',                                       
								'label' => esc_html__('Step Title', 'workreap'),
								'desc' => esc_html__('Add Step One title, which will serve as title on registration process', 'workreap'),                                        
							),
							'step_one_desc' => array(
								'type' => 'textarea',
								'value' => '',
								'label' => esc_html__('Step description.', 'workreap'),
								'desc' => esc_html__('Add step one description, which will serve as description on registration process', 'workreap'),                                      
							),
						)
					),
				)
			),
			'step-two' => array(
				'title' => esc_html__('Step Two Settings', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'step-two-group' => array(
						'type' => 'group',
						'options' => array(
							'step_two_title' => array(
								'type' => 'text',
								'value' => '',                                       
								'label' => esc_html__('Step Title', 'workreap'),
								'desc' => esc_html__('Add step two title, which will serve as title on registration process', 'workreap'),                                        
							),
							'step_two_desc' => array(
								'type' => 'textarea',
								'value' => '',
								'label' => esc_html__('Step description.', 'workreap'),
								'desc' => esc_html__('Add step two description, which will serve as description on registration process', 'workreap'),                                      
							),
						)
					),
				)
			), 
			'step-three' => array(
				'title' => esc_html__('Step three Settings', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'step-four-group' => array(
						'type' => 'group',
						'options' => array(
							'step_four_title' => array(
								'type' => 'text',
								'value' => '',                                       
								'label' => esc_html__('Step Title', 'workreap'),
								'desc' => esc_html__('Add step four title, which will serve as title on registration process', 'workreap'),                                        
							),
							'step_four_desc' => array(
								'type' => 'textarea',
								'value' => '',
								'label' => esc_html__('Step description.', 'workreap'),
								'desc' => esc_html__('Add step four description, which will serve as description on registration process', 'workreap'),                                      
							),                                
						)
					),
				)
			),        
        )
    )
);
