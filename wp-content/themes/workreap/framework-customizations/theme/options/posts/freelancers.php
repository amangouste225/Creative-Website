<?php

if (!defined('FW')) {
    die('Forbidden');
}

$skills_list		= workreap_get_all_skills();
$freelancer_level   = worktic_freelancer_level_list();
$english_level   	= worktic_english_level_list();

if (function_exists('fw_get_db_settings_option')) {
	$gallery_option = fw_get_db_settings_option('freelancer_gallery_option', $default_value = null);
	$freelancer_price_option = fw_get_db_settings_option('freelancer_price_option', $default_value = null);
	$freelancertype			= fw_get_db_settings_option('freelancertype_multiselect', $default_value = null);
}

$specialization	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $specialization	= fw_get_db_settings_option('freelancer_specialization', $default_value = null);
}

$freelancer_faq_option	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $freelancer_faq_option	= fw_get_db_settings_option('freelancer_faq_option', $default_value = null);
}

$phone_option	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$phone_option	= fw_get_db_settings_option('phone_option', $default_value = null);
	$phone_option	= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
	
}

$experience	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $experience	= fw_get_db_settings_option('freelancer_industrial_experience', $default_value = null);
}

$socialmediaurl	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $socialmediaurl	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
}

$gallery_option 			= !empty($gallery_option) ? $gallery_option : '';
$freelancer_price_option 	= !empty($freelancer_price_option) ? $freelancer_price_option : '';

$dynamic_skills	= array();
if( !empty( $skills_list ) ) {
foreach ($skills_list as $key => $label) {
		$dynamic_skills['skill_'.$key] = array(
			'type' => 'slider',
			'value' => $label['name'],
			'properties' => array(
				'min'  => intval(1),
				'max'  => intval(100),
				'step' => intval(1),
			),
			'label' => $label['name'],
		);
	}
}

$freelancer_max	= array();
if(!empty($freelancer_price_option) && $freelancer_price_option === 'enable' ){
    $freelancer_max['max_price']= array(
										'type'  => 'text',
										'label' => esc_html__('Maximum per hour rate', 'workreap'),
										'desc'  => esc_html__('Add max hourly rate(integers only)', 'workreap'),
										'value' => '',
									);
}

//freelancer type multiselect
$multiselect	= 'select';
if(!empty($freelancertype) && $freelancertype === 'enable' ){
	$multiselect	= 'multi-select';
}	

//Skills Type
$display_type	= 'number';
if( function_exists('fw_get_db_settings_option')  ){
	$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
}

$skills_max		= !empty($display_type) && ($display_type === 'year') ? 10 :100;

$options = array(
	'featured_profile' => array(
        'title' => esc_html__('Feature Profile', 'workreap'),
        'type' => 'box',
        'options' => array(
			'featured_post' => array(
				'value' => false,
				'label' => esc_html__('Featured Profile?', 'workreap'),
                'desc' => esc_html__('Select to make this profile as featured', 'workreap'),
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
				'desc' => esc_html__('Add date here. Please note featured date is required, otherwise user will not be added to featured list', 'workreap')
			),
        ),
    ),
	'settings' => array(
        'title' => esc_html__('General Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'_featured_timestamp' => array(
                'type' => 'hidden',
                'value' => 0,
            ),
			'first_name' => array(
				'label' => esc_html__('First name', 'workreap'),
                'desc' => esc_html__('Please first name', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
			'last_name' => array(
				'label' => esc_html__('Last name', 'workreap'),
                'desc' => esc_html__('Please add last name', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
        	'gender' => array(
				'label'   		=> esc_html__( 'Gender', 'workreap' ),
				'desc'   		=> esc_html__( 'Select your gender', 'workreap' ),
				'type'    		=> 'select',
				'value'    		=> 'male',
				'choices' 		=> array(
					'male' 		=> esc_html__('Male', 'workreap'),	
					'female' 	=> esc_html__('Female', 'workreap'),	
				)
			),
			'freelancer_type' => array(
				'label'   		=> esc_html__( 'Freelancer type', 'workreap' ),
				'desc'   		=> esc_html__( 'Select freelancer type', 'workreap' ),
				'type'    		=> $multiselect,
				'choices' 		=> $freelancer_level
			),
			'english_level' => array(
				'label'   		=> esc_html__( 'English Level', 'workreap' ),
				'desc'   		=> esc_html__( 'Select english level', 'workreap' ),
				'type'    		=> 'select',
				'value'    		=> 'basic',
				'choices' 		=> $english_level
			),
			'tag_line' => array(
				'label' => esc_html__('Tagline', 'workreap'),
                'desc' => esc_html__('Please add tagline', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
            '_perhour_rate' => array(
				'label' => esc_html__('Minimum per hour rate', 'workreap'),
                'desc' => esc_html__('Please add minimum per hour rate', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
			$freelancer_max,
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
				'label' => esc_html__('Select location', 'workreap'),
				'population' => 'taxonomy',
				'source' => 'locations',
				'prepopulate' => 500,
				'limit' => 1,
				'desc' => esc_html__('Select location to display.', 'workreap'),
			),
			'videos' => array(
				'type' => 'addable-option',
				'value' => array(),
				'label' => esc_html__('Video URL', 'workreap'),
				'desc' => esc_html__('Add video URL here', 'workreap'),
				'option' => array('type' => 'text'),
				'add-button-text' => esc_html__('Add', 'workreap'),
				'sortable' => true,
			),
        )
    ),
	
	'skillsdata' => array(
        'title' => esc_html__('Skills', 'workreap'),
        'type' => 'box',
        'options' => array(
			'skills' => array(
				'label' => esc_html__('Add skill.', 'workreap'),
				'type' => 'addable-popup',
				'value' => array(),
				'desc' => esc_html__('Add your skills here.', 'workreap'),
				'popup-options' => array(
					'skill' => array(
						'type' => 'multi-select',
						'label' => esc_html__('Select skill', 'workreap'),
						'population' => 'taxonomy',
						'source' => 'skills',
						'prepopulate' => 500,
						'limit' => 1,
						'desc' => esc_html__('Select skill to display.', 'workreap'),
					),
					'value' => array(
						'type' => 'slider',
						'value' => '',
						'properties' => array(
							'min'  => intval(1),
							'max'  => intval($skills_max),
							'step' => intval(1),
						),
						'label' => esc_html__('Add skill value', 'workreap'),
					)
				),
				'template' => '{{- value }}',
			),
        ),
    ),
	'projects_data' => array(
        'title' => esc_html__('Other Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
            'projects' => array(
				'label' => esc_html__('Add project.', 'workreap'),
				'type' => 'addable-popup',
				'value' => array(),
				'desc' => esc_html__('Add your projects here.', 'workreap'),
				'popup-options' => array(
					'title' => array(
						'label' => esc_html__('Project Title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add project title here.', 'workreap')
					),
					'link' => array(
						'label' => esc_html__('Link', 'workreap'),
						'type' => 'text',
						'value' => '#',
						'desc' => esc_html__('Add link here', 'workreap')
					),
					'image' => array(
						'type' => 'upload',
						'preview_size' => 'small',
						'modal_size' => 'medium',
						'label' => esc_html__('Project image', 'workreap'),
						'desc' => esc_html__('Choose your project image', 'workreap'),
					),
				),
				'template' => '{{- title }}',
			),
			'awards' => array(
				'label' => esc_html__('Add awards', 'workreap'),
				'type' => 'addable-popup',
				'value' => array(),
				'desc' => esc_html__('Add your awards here.', 'workreap'),
				'popup-options' => array(
					'title' => array(
						'label' => esc_html__('Award Title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add award title here.', 'workreap')
					),
					'date' => array(
						'label' => esc_html__('Date', 'workreap'),
						'type' => 'datetime-picker',
                        'datetime-picker' => array(
                            'format'  => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => false, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
						'desc' => esc_html__('Add date here', 'workreap')
					),
					'image' => array(
						'type' => 'upload',
						'preview_size' => 'small',
						'modal_size' => 'medium',
						'label' => esc_html__('Award image', 'workreap'),
						'desc' => esc_html__('Choose your award image', 'workreap'),
					),
				),
				'template' => '{{- title }}',
			),
			'experience' => array(
				'label' => esc_html__('Add experience', 'workreap'),
				'type' => 'addable-popup',
				'value' => array(),
				'desc' => esc_html__('Add your experience here.', 'workreap'),
				'popup-options' => array(
					'title' => array(
						'label' => esc_html__('Experience title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add experience title here.', 'workreap')
					),
					'company' => array(
						'label' => esc_html__('Company title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add company title here.', 'workreap')
					),
					'startdate' => array(
						'label' => esc_html__('Start Date', 'workreap'),
						'type' => 'datetime-picker',
                        'datetime-picker' => array(
                            'format' => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => false, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
						'desc' => esc_html__('Add date here', 'workreap')
					),
					'enddate' => array(
						'label' => esc_html__('End Date', 'workreap'),
						'type' => 'datetime-picker',
                        'datetime-picker' => array(
                            'format' => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => false, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
						'desc' => esc_html__('Add date here, leave it empty to set your current job', 'workreap')
					),
					'description' => array(
						'label' => esc_html__('Description?', 'workreap'),
						'type' => 'wp-editor',
						'value' => '',
						'desc' => esc_html__('Add description here.', 'workreap')
					),
				),
				'template' => '{{- title }}',
			),
			'education' => array(
				'label' => esc_html__('Add education', 'workreap'),
				'type' => 'addable-popup',
				'value' => array(),
				'desc' => esc_html__('Add your education here.', 'workreap'),
				'popup-options' => array(
					'title' => array(
						'label' => esc_html__('Education title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add education title here.', 'workreap')
					),
					'institute' => array(
						'label' => esc_html__('Institute title', 'workreap'),
						'type' => 'text',
						'value' => '',
						'desc' => esc_html__('Add institute title here.', 'workreap')
					),
					'startdate' => array(
						'label' => esc_html__('Start Date', 'workreap'),
						'type' => 'datetime-picker',
                        'datetime-picker' => array(
                            'format' => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => false, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
						'desc' => esc_html__('Add date here', 'workreap')
					),
					'enddate' => array(
						'label' => esc_html__('End Date', 'workreap'),
						'type' => 'datetime-picker',
                        'datetime-picker' => array(
                            'format' => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => false, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
						'desc' => esc_html__('Add date here, leave it empty to set your current education', 'workreap')
					),
					'description' => array(
						'label' => esc_html__('Description?', 'workreap'),
						'type' => 'wp-editor',
						'value' => '',
						'desc' => esc_html__('Add description here.', 'workreap')
					),
				),
				'template' => '{{- title }}',
			),
        )
    ),
    'freelancer_settings' => array(
        'title' => esc_html__('Freelancer Settings', 'workreap'),
        'type' => 'box',
        'context' => 'side',
        'priority' => 'high',
        'options' => array(
            'banner_image' => array(
                'type' => 'upload',
                'label' => esc_html__('Banner Image', 'workreap'),
                'desc' => esc_html__('Upload your banner image. Leave it empty to use default from Theme Settings > Directory Settings > General Settings', 'workreap'),
                'images_only' => true,
            ),
        ),
    ),
	'freelancer_resume' => array(
        'title' => esc_html__('Freelancer resume', 'workreap'),
        'type' => 'box',
        'context' => 'side',
        'priority' => 'high',
		'images_only' => false,       
        'options' => array(
            'resume' => array(
                'type' => 'upload',
                'label' => esc_html__('Upload Resume', 'workreap'),
                'desc' => esc_html__('Upload freelancer resume.', 'workreap'),
				'files_ext' => array( 'doc', 'docx', 'pdf' ),  
            ),
        ),
    ),
);

if(!empty($phone_option) && $phone_option === 'enable' ){
	$options['settings']['options']['user_phone_number'] = array(
		'type'  => 'text',
		'label' => esc_html__('User phone number', 'workreap'),
		'desc'  => esc_html__('Add user phone number', 'workreap'),
		'value' => '',
	);
}
if(!empty($experience) && $experience === 'enable' ){
	$options['freelancer_experiences'] = array(
										'title' => esc_html__('Industrial experience', 'workreap'),
										'type' => 'box',
										'options' => array(
											'industrial_experiences' => array(
												'label' => esc_html__('Add Industrial experience.', 'workreap'),
												'type' => 'addable-popup',
												'value' => array(),
												'desc' => esc_html__('Add your Industrial experience here.', 'workreap'),
												'popup-options' => array(
													'exp' => array(
														'type' => 'multi-select',
														'label' => esc_html__('Select Industrial experience', 'workreap'),
														'population' => 'taxonomy',
														'source' => 'wt-industrial-experience',
														'prepopulate' => 500,
														'limit' => 1,
														'desc' => esc_html__('Select Industrial experience to display.', 'workreap'),
													),
													'value' => array(
														'type' => 'slider',
														'value' => '',
														'properties' => array(
															'min'  => intval(1),
															'max'  => intval($skills_max),
															'step' => intval(1),
														),
														'label' => esc_html__('Add Industrial experience', 'workreap'),
													)
												),
												'template' => '{{- exp }}',
											),
										),
									);
}
if(!empty($specialization) && $specialization === 'enable' ){
	$options['freelancer_specialization'] = array(
										'title' => esc_html__('Specialization', 'workreap'),
										'type' => 'box',
										'options' => array(
											'specialization' => array(
												'label' => esc_html__('Add Specialization.', 'workreap'),
												'type' => 'addable-popup',
												'value' => array(),
												'desc' => esc_html__('Add your Specialization here.', 'workreap'),
												'popup-options' => array(
													'spec' => array(
														'type' => 'multi-select',
														'label' => esc_html__('Select Specialization', 'workreap'),
														'population' => 'taxonomy',
														'source' => 'wt-specialization',
														'prepopulate' => 500,
														'limit' => 1,
														'desc' => esc_html__('Select Specialization to display.', 'workreap'),
													),
													'value' => array(
														'type' => 'slider',
														'value' => '',
														'properties' => array(
															'min'  => intval(1),
															'max'  => intval($skills_max),
															'step' => intval(1),
														),
														'label' => esc_html__('Add Specialization', 'workreap'),
													)
												),
												'template' => '{{- spec }}',
											),
										),
									);
}
if(!empty($gallery_option) && $gallery_option === 'enable' ){
	$options['freelancer_gallery'] = array(
					'title' => esc_html__('Gallery', 'workreap'),
					'type' => 'box',
					'context' => 'side',
					'priority' => 'high',
					'images_only' => false,       
					'options' => array(
						'images_gallery' => array(
							'type' => 'multi-upload',
							'label' => esc_html__('Upload Images', 'workreap'),
							'desc' => esc_html__('Upload freelancer gallery images.', 'workreap'),
							'images_only' => true, 
						),
					),
				);
}
if(!empty($socialmediaurl['gadget']) && $socialmediaurl['gadget'] == 'enable' ) {
    $social_settings    = function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();
	
    if(!empty($social_settings)) {
        foreach($social_settings as $key => $val ) {
            $enable_value   = !empty($socialmediaurl['enable'][$key]['gadget']) ? $socialmediaurl['enable'][$key]['gadget'] : '';
            if( !empty($enable_value) && $enable_value === 'enable' ){
                
                $options['settings']['options'][$key] = array(
                    'label' => $val,
                    'type' => 'text',
                    'value' => '',
                );
            }
        }
    }
}
if(!empty($freelancer_faq_option) && $freelancer_faq_option == 'yes' ) {
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