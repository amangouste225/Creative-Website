<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'captcha' => array(
		'title'   => esc_html__( 'reCaptcha Security', 'workreap' ),
		'type'    => 'tab',
		'options' => array(
			'general-box' => array(
				'title'   => esc_html__( 'reCaptcha Settings', 'workreap' ),
				'type'    => 'box',
				'options' => array(
					'captcha_settings' => array(
						'type'  => 'switch',
						'value' => 'disable',
						'label' => esc_html__('Enable reCaptcha', 'workreap'),
						'desc' => wp_kses( __( 'Secure your forms with <a href="https://www.google.com/recaptcha/admin" target="_blank"> reCapthca </a> To use reCaptcha you must obtain a free API(reCAPTCHA v2) key for your domain. To obtain one, visit: <a href="https://www.google.com/recaptcha/admin" target="_blank">Here</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
						'left-choice' => array(
							'value' => 'enable',
							'label' => esc_html__('Enable', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'disable',
							'label' => esc_html__('Disable', 'workreap'),
						),
					),
					'site_key' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('Site Key', 'workreap'),
                        'desc' => esc_html__('Enter Site key here.', 'workreap'),
                    ),
					'secret_key' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => esc_html__('Secret Key', 'workreap'),
                        'desc' => esc_html__('Enter Secret key here.', 'workreap'),
                    ),
					'language_code' => array(
                        'type' => 'text',
                        'value' => 'en',
                        'label' => esc_html__('Add Language Code', 'workreap'),
                        'desc' => esc_html__('Add language code here. eg en.', 'workreap'),
						'desc' => wp_kses( __( 'Add language code here. eg en. Please type your language code <a href="https://developers.google.com/recaptcha/docs/language" target="_blank"> Get Code </a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					
				)
			),
		)
	)
);