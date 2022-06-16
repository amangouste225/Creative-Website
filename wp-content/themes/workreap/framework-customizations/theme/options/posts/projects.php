<?php

if (!defined('FW')) {
    die('Forbidden');
}

$list               = worktic_job_duration_list();
$english_level      = worktic_english_level_list();
$freelancer_level   = worktic_freelancer_level_list();
$project_level   	= workreap_get_project_level();
$job_type 		 	= workreap_get_job_type();
$job_option		 	= function_exists('workreap_get_job_option') ? workreap_get_job_option() : array();
$job_faq_option     = '';
if (function_exists('fw_get_db_settings_option')) {
    $job_option_setting         = fw_get_db_settings_option('job_option', $default_value = null);
    $multiselect_freelancertype = fw_get_db_settings_option('multiselect_freelancertype', $default_value = null);
    $job_price_option           = fw_get_db_settings_option('job_price_option', $default_value = null);
    $milestone         			= fw_get_db_settings_option('job_milestone_option', $default_value = null);
    $job_faq_option				= fw_get_db_settings_option('job_faq_option', $default_value = null);
}

$multiselect_freelancertype  = !empty($multiselect_freelancertype) ?  $multiselect_freelancertype : '';
$job_price_option 			= !empty($job_price_option) ? $job_price_option : '';
$job_option_setting 		= !empty($job_option_setting) ? $job_option_setting : '';
$milestone					= !empty($milestone['gadget']) ? $milestone['gadget'] : '';

//Project location
$job_option_list	= array();
if(!empty($job_option_setting) && $job_option_setting === 'enable' ){
        $job_option_list['job_option'] = array(
									'label' => esc_html__('Project location type', 'workreap'),
									'desc'  => esc_html__('Select project location type', 'workreap'),
									'type'  => 'select',
									'value' => '',
									'choices' => $job_option
								);
}

$milestone_Array    = array();
if( !empty($milestone) && $milestone ==='enable' ){
    $milestone_Array['milestone']	= array(
                                        'type'  => 'select',
                                        'value' => '',
                                        'label' => esc_html__('Milestone', 'workreap'),
                                        'desc'  => esc_html__('Select milestone project', 'workreap'),
                                        'choices' => array(
                                            'off' => esc_html__('OFF', 'workreap'),
                                            'on'  => esc_html__('ON', 'workreap'),
                                        )
                                    );
}
//price type
$max_price	= array();
if(!empty($job_price_option) && $job_price_option === 'enable' ){
    $max_price['max_price']	= array(
						'type'  => 'text',
						'label' => esc_html__('Maximum price', 'workreap'),
						'desc'  => esc_html__('Add job maximum price (integers only)', 'workreap'),
						'value' => '',
					);
}

//Freelancer level
$freelancertype	= array();
if( $multiselect_freelancertype === 'enable' ){
   $freelancertype['freelancer_level'] =  array(
        'type'  => 'multi-select',
        'population' => 'array',
        'label' => esc_html__('Freelancer level', 'workreap'),
        'desc'  => esc_html__('Choose freelancer level required for the project', 'workreap'),                
        'choices' => $freelancer_level
    );
}else{
	$freelancertype['freelancer_level'] =  array(
        'type'  		=> 'multi-select',
        'prepopulate' 	=> 'array',
		'limit' 		=> 1,
        'label' 		=> esc_html__('Freelancer level', 'workreap'),
        'desc'  		=> esc_html__('Choose freelancer level required for the project', 'workreap'),                
        'choices' 		=> $freelancer_level
    );
}

$amount_list	= array();
if( function_exists('workreap_amount_ranges') ) {
	$amount		= workreap_amount_ranges();
	if( !empty( $amount ) ) {
		foreach ( $amount as $key => $val ) {
			$amount_list[$key]	= $val;
		}
	}
}

$options = array(
	'featured_job' => array(
        'title' => esc_html__('Featured Job?', 'workreap'),
        'type' => 'box',
        'options' => array(
			'featured_post' => array(
				'value' => false,
				'label' => esc_html__('Featured job?', 'workreap'),
                'desc' => esc_html__('Select to make this job as featured', 'workreap'),
                'type' => 'checkbox',
                'value' => '',
            ),
			'featured_expiry' => array(
				'label' => esc_html__('Featured Expiry', 'workreap'),
				'type' => 'datetime-picker',
				'datetime-picker' => array(
					'format'  => 'Y-m-d',
					'maxDate' => false, 
					'minDate' => date('Y-m-d'),
					'timepicker' => false,
					'datepicker' => true,
					'defaultTime' => ''
				),
				'desc' => esc_html__('Add date here, Futured date is required to add user into featured listing', 'workreap')
			),
        ),
    ),
    'project_settings' => array(
        'title' => esc_html__('Project Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'_featured_job_string' => array(
                'type' => 'hidden',
                'value' => 0,
            ),
            'project_level' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('Project level', 'workreap'),
                'desc'  => esc_html__('Required project level.', 'workreap'),                
                'choices' => $project_level
            ),
			$job_option_list,
            'project_type' => array(
                'type' => 'multi-picker',
                'label' => false,
                'desc' => false,
                'picker' => array(
                    'gadget' => array(
                        'label' => esc_html__('Project Type', 'workreap'),
                        'desc' => esc_html__('Select project type', 'workreap'),
                        'type' => 'select',
                        'value' => 'default',
                        'choices' => $job_type
                    )
                ),
                'choices' => array(                                       
                    'hourly' => array(
                        'hourly_rate' => array(
                            'type' => 'text',
                            'value' => '',
                            'label' => esc_html__('Minimum Price', 'workreap'),
                            'desc' => esc_html__('Add job minimum hourly rate (integers only)', 'workreap'),
                            'value' => '',
                        ),
						$max_price,
						'estimated_hours' => array(
                            'type' => 'text',
                            'value' => '',
                            'label' => esc_html__('Estimated Hours', 'workreap'),
                            'desc' => esc_html__('Add job estimated hours (integers only)', 'workreap'),
                            'value' => '',
                        ),
                    ),
					'fixed' => array(
                        'project_cost' => array(
                            'type' => 'text',
                            'value' => '',
                            'label' => esc_html__('Minimum Price', 'workreap'),
                            'desc' => esc_html__('Add job cost (integers only)', 'workreap'),
                            'value' => '',
                        ),
                        $max_price,
                        $milestone_Array
                    ),
                )
            ), 
			$freelancertype,
            'project_duration' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('Project Duration', 'workreap'),
                'desc'  => esc_html__('Select duration of the project', 'workreap'),                
                'choices' => $list,
            ),  
            'english_level' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('English level', 'workreap'),
                'desc'  => esc_html__('Select English level required for the project', 'workreap'),                
                'choices' => $english_level,
            ),
			'expiry_date' => array(
				'label' => esc_html__('Expiry Date', 'workreap'),
				'type' => 'datetime-picker',
				'datetime-picker' => array(
					'format'  => 'Y/m/d', // Format datetime.
					'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
					'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
					'timepicker' => false, // Show timepicker.
					'datepicker' => true, // Show datepicker.
					'defaultTime' => '' // If the input value is empty, timepicker will set time use defaultTime.
				),
				'desc' => esc_html__('Add date here', 'workreap')
			),
			'deadline' => array(
				'label' => esc_html__('Project deadline date', 'workreap'),
				'type' => 'datetime-picker',
				'datetime-picker' => array(
					'format'  => 'Y/m/d', // Format datetime.
					'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
					'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
					'timepicker' => false, // Show timepicker.
					'datepicker' => true, // Show datepicker.
					'defaultTime' => '' // If the input value is empty, timepicker will set time use defaultTime.
				),
				'desc' => esc_html__('Add date here', 'workreap')
			),
			'show_attachments' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('Show attachments', 'workreap'),
                'desc'  => esc_html__('Choose to show attachments on project detail page.', 'workreap'),                
                'choices' => array(
					'off' => esc_html__('OFF', 'workreap'),
					'on' => esc_html__('ON', 'workreap'),
				),
            ), 
            'project_documents' => array(
                'type'  => 'multi-upload',
                'value' => array(),
                'label' => esc_html__('Upload Documents', 'workreap'),
                'desc'  => esc_html__('Upload project documents', 'workreap'),         
                'images_only' => false,            
                'files_ext' => array(),  
            ),
        )
    ),
	'settings' => array(
        'title' => esc_html__('General Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
            'address' => array(
				'label' => esc_html__('Address', 'workreap'),
                'desc' => esc_html__('Please add address', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
            'longitude' => array(
				'label' => esc_html__('Longitude', 'workreap'),
                'desc' => esc_html__('Please add Longitude', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
            'latitude' => array(
				'label' => esc_html__('Latitude', 'workreap'),
                'desc' => esc_html__('Please add Latitude', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
            'country' => array(
				'type' => 'multi-select',
				'label' => esc_html__('Select country', 'workreap'),
				'population' => 'taxonomy',
				'source' => 'locations',
				'prepopulate' => 500,
				'limit' => 1,
				'desc' => esc_html__('Select country to display.', 'workreap'),
			),
        )
    ),
	'commission_settings' => array(
        'title' => esc_html__('Commission settings', 'workreap'),
        'type' => 'box',
        'priority' => 'high',
        'options' => array(
            'service_fee' => array(
			'type' => 'multi-picker',
			'label' => false,
			'desc' => '',
			'picker' => array(
				'gadget' => array(
					'label' => esc_html__('Project comission fee', 'workreap'),
					'type' => 'select',
					'value' => 'none',
					'desc' => esc_html__('Select comissions type. If project has custom comission type and value then Theme Settings value will be bypassed with this settings.', 'workreap'),
					'help' => esc_html__('', 'workreap'),
					'choices' => array(
						'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
						'percentage' 		=> esc_html__('Percentage', 'workreap'),
						'comissions_tiers'  => esc_html__('Comissions tiers', 'workreap'),
						'none'  			=> esc_html__('Default settings', 'workreap')
					),
				)
			),
			'choices' => array(
				'fixed' => array(
					'amount' => array(
						'type' 		 => 'text',
						'value' => 10,
						'label' => esc_html__('Fixed amount', 'workreap'),
						'desc'  => esc_html__('Set fixed amount for the project commission. Please add interger value only', 'workreap'),
					),
				),
				'percentage' => array(
					'percentage' => array(
						'type' => 'text',
						'value' => 20,
						'label' => esc_html__('Percentage', 'workreap'),
						'desc'  => esc_html__('Set percentage for the project commission. This percentage will be applied to the total cost of the project', 'workreap'),
					),
				),
				'comissions_tiers' => array(
					'add_tiers' => array(
						'type' => 'addable-box',
						'label' => esc_html__('Comissions tiers', 'workreap'),
						'desc' => esc_html__('Please add comissions tiers', 'workreap'),
						'box-options' 	=> array(
							'type' 		=> array(	
								'label' 	=> esc_html__('Type', 'workreap'),
								'type' 		=> 'select',
								'desc' 		=> esc_html__('Select type and then range from below.', 'workreap'),
								'choices' => array(
									'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
									'percentage' 		=> esc_html__('Percentage', 'workreap'),
								)
							),
							'range' 		=> array(	
								'label' 	=> esc_html__('Select range', 'workreap'),
								'type' 		=> 'select',
								'desc' 		=> esc_html__('Select range for the comission. If project cost will be under this selected range then below amount/percentage will be charge as comissions', 'workreap'),
								'choices' => $amount_list
							),
							'amount' 		=> array('type' => 'text',
													 'value' => 20,
													 'desc' => esc_html__('Add amount or percentage value. Please add interger value only', 'workreap')
												),
						),
						'template' => '{{- type }}', // box title
					),
				),
			),
		),
        )
    ),
);
if(!empty($job_faq_option) && $job_faq_option == 'yes' ) {
    $options['faq'] = array(
						'label' => esc_html__('FAQ', 'workreap'),
						'type' => 'addable-popup',
						'value' => array(),
						'desc' => esc_html__('Add Question and answer for help and Support.', 'workreap'),
						'popup-options' => array(
							'faq_question' => array(
								'label' => esc_html__('Question', 'workreap'),
								'type' => 'text',
								'value' => '',
								'desc' => esc_html__('The Question for help and Support', 'workreap')
							),
							'faq_answer' => array(
								'label' => esc_html__('Answer', 'workreap'),
								'type' => 'textarea',
								'value' => '',
								'desc' => esc_html__('', 'workreap')
							),
						),
						'template' => '{{- faq_question }}',
					);
}