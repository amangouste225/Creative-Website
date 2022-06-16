<?php

if (!defined('FW')) {
    die('Forbidden');
}

$english_level  	= worktic_english_level_list();
$service_faq_option     = '';
if (function_exists('fw_get_db_settings_option')) {
    $service_faq_option				= fw_get_db_settings_option('service_faq_option', $default_value = null);
}

$amount_list	= array();
if( function_exists('workreap_service_amount_ranges') ) {
	$amount		= workreap_service_amount_ranges();
	if( !empty( $amount ) ) {
		foreach ( $amount as $key => $val ) {
			$services_amount_list[$key]	= $val;
		}
	}
}
$options = array(
	'commission_service' => array(
        'title' => esc_html__('Services comission fee', 'workreap'),
        'type' => 'box',
        'options' => array(
			'service_commision' => array(
			'type' => 'multi-picker',
			'title' => esc_html__('Services comission fee', 'workreap'),
			'label' => false,
			'desc' => '',
			'picker' => array(
				'gadget' => array(
						'label' => esc_html__('Services comission fee', 'workreap'),
						'type' => 'select',
						'value' => 'none',
						'desc' => esc_html__('Select comissions type. If service has custom comission type and value then Theme Settings value will be bypassed with this settings.', 'workreap'),
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
							'desc'  => esc_html__('Set fixed amount for the service commission. Please add interger value only', 'workreap'),
						),
					),
					'percentage' => array(
						'percentage' => array(
							'type' => 'text',
							'value' => 20,
							'label' => esc_html__('Percentage', 'workreap'),
							'desc'  => esc_html__('Set percentage for the service commission. This percentage will be applied to the total cost of the service', 'workreap'),
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
									'desc' 		=> esc_html__('Select range for the comission. If service cost will be under this selected range then below amount/percentage will be charge as comissions', 'workreap'),
									'choices' => $services_amount_list
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
        ),
    ),
	'featured_service' => array(
        'title' => esc_html__('Feature Service', 'workreap'),
        'type' => 'box',
        'options' => array(
			'featured_post' => array(
				'value' => false,
				'label' => esc_html__('Featured service?', 'workreap'),
                'desc' => esc_html__('Select to make this service as featured', 'workreap'),
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
				'desc' => esc_html__('Add date here', 'workreap')
			),
        ),
    ),
    'micro_services_settings' => array(
        'title' => esc_html__('Service Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'_featured_service_string' => array(
                'type' => 'hidden',
                'value' => 0,
            ),
			'price' => array(
				'label' => esc_html__('Micro Service Amount', 'workreap'),
                'desc' 	=> esc_html__('Micro Service amount', 'workreap'),
                'type' 	=> 'text',
                'value' => '',
            ),  
			'english_level' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('English level', 'workreap'),
                'desc'  => esc_html__('Select English level required for the service', 'workreap'),                
                'choices' => $english_level,
            ),
            'docs' => array(
                'type'  		=> 'multi-upload',
                'value' 		=> array(),
                'label' 		=> esc_html__('Upload gallery', 'workreap'),
                'desc'  		=> esc_html__('Upload micro service gallery images, first image will become the featured image of this services.', 'workreap'),         
                'images_only' 	=> false,            
                'files_ext' 	=> array( 'jpg','jpeg','gif','png' ),  
            ),
			'address' => array(
				'label' => esc_html__('Address', 'workreap'),
                'desc' 	=> esc_html__('Please add address', 'workreap'),
                'type' 	=> 'text',
                'value' => '',
            ),
            'longitude' => array(
				'label' => esc_html__('Longitude', 'workreap'),
                'desc' 	=> esc_html__('Please add Longitude', 'workreap'),
                'type' 	=> 'text',
                'value' => '',
            ),
            'latitude' => array(
				'label' => esc_html__('Latitude', 'workreap'),
                'desc' 	=> esc_html__('Please add Latitude', 'workreap'),
                'type' 	=> 'text',
                'value' => '',
            ),
            'country' => array(
				'type' 			=> 'multi-select',
				'label' 		=> esc_html__('Select location', 'workreap'),
				'population' 	=> 'taxonomy',
				'source' 		=> 'locations',
				'prepopulate' 	=> 500,
				'limit' 		=> 1,
				'desc' 			=> esc_html__('Select location to display.', 'workreap'),
			),
			'service_map' => array(
				'type' => 'switch',
				'value' => 'on',
				'label' => esc_html__('Show map', 'workreap'),
				'desc' => esc_html__('Show map and get direction link on the service detail page', 'workreap'),
				'left-choice' => array(
					'value' => 'on',
					'label' => esc_html__('Enable', 'workreap'),
				),
				'right-choice' => array(
					'value' => 'off',
					'label' => esc_html__('Disable', 'workreap'),
				),
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
			'downloadable' => array(
                'type'  => 'select',
                'value' => '',
                'label' => esc_html__('Downloadable', 'workreap'),
                'desc'  => esc_html__('Select Yes or no for downloable service', 'workreap'),                
                'choices' => array(
								''		=> esc_html__('Select Downloadable', 'workreap'),
								'yes'	=> esc_html__('Yes','workreap'),
								'no'	=> esc_html__('No','workreap'),
								),
            ),
        )
    ),
);
if(!empty($service_faq_option) && $service_faq_option == 'yes' ) {
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
