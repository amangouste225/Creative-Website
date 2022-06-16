<?php

if (!defined('FW')) {
    die('Forbidden');
}

$data_list = array();
if( function_exists('worktic_get_employees_list') ){
	$list = worktic_get_employees_list();
	foreach( $list as $key => $item ){
		if( !empty( $item['value'] ) && !empty( $item['title'] ) ) {
			$data_list[$item['value']] = $item['title'];
		}
	}
}

$company_name	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $company_name	= fw_get_db_settings_option('company_name', $default_value = null);
}

$phone_option	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$phone_option	= fw_get_db_settings_option('phone_option', $default_value = null);
	$phone_option	= !empty($phone_option['gadget']) ? $phone_option['gadget'] : '';
}

$company_job_title	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $company_job_title	= fw_get_db_settings_option('company_job_title', $default_value = null);
}

$socialmediaurl	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $socialmediaurl	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
}

$socialmediaurl	= '';
if( function_exists('fw_get_db_settings_option')  ){
    $socialmediaurl	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
}

$options = array(
	'settings' => array(
        'title' => esc_html__('General Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
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
			'tag_line' => array(
				'label' => esc_html__('Tagline', 'workreap'),
                'desc' => esc_html__('Please add tagline', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
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
			'department' => array(
				'type' => 'multi-select',
				'label' => esc_html__('Select department', 'workreap'),
				'population' => 'taxonomy',
				'source' => 'department',
				'prepopulate' => 500,
				'limit' => 1,
				'desc' => esc_html__('Select department to display.', 'workreap'),
			),
			
			'no_of_employees' => array(
				'type' => 'select',
				'label' => esc_html__('No. Of Employees You Have', 'workreap'),
				'desc' => esc_html__('Select department to display.', 'workreap'),
				'choices' => $data_list,
			),
        )
    ),
    'employers_settings' => array(
        'title' => esc_html__('Employer Settings', 'workreap'),
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
            'brochures' => array(
                'type'  		=> 'multi-upload',
                'value' 		=> array(),
                'label' 		=> esc_html__('Add brochures', 'workreap'),
                'desc'  		=> esc_html__('Add brochures, it could be in the form of pdf, images etc', 'workreap'),        
                'images_only' 	=> false,  
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

if(!empty($company_name) && $company_name === 'enable') {
    $options['settings']['options']['company_name'] = array(
        'label' => esc_html__('Company name', 'workreap'),
        'desc' => esc_html__('Please add company name', 'workreap'),
        'type' => 'text',
        'value' => '',
    );
}

if(!empty($company_job_title) && $company_job_title === 'enable') {
    $options['settings']['options']['company_job_title'] = array(
        'label' => esc_html__('Job title', 'workreap'),
        'desc' => esc_html__('Please add job title', 'workreap'),
        'type' => 'text',
        'value' => '',
    );
}

if(!empty($socialmediaurl['gadget']) && $socialmediaurl['gadget'] === 'enable' ) {
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