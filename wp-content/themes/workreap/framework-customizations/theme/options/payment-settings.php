<?php

if (!defined('FW')) {
    die('Forbidden');
}
$schedules_list	= array();
$amount_list	= array();
$services_amount_list	= array();

if( function_exists('workreap_cron_schedule') ) {
	$schedules		= workreap_cron_schedule();
	
	if( !empty( $schedules ) ) {
		foreach ( $schedules as $key => $val ) {
			$schedules_list[$key]	= $schedules[$key]['display'];
		}
	}
}

if( function_exists('workreap_amount_ranges') ) {
	$amount		= workreap_amount_ranges();
	if( !empty( $amount ) ) {
		foreach ( $amount as $key => $val ) {
			$amount_list[$key]	= $val;
		}
	}
}

if( function_exists('workreap_service_amount_ranges') ) {
	$amount		= workreap_service_amount_ranges();
	if( !empty( $amount ) ) {
		foreach ( $amount as $key => $val ) {
			$services_amount_list[$key]	= $val;
		}
	}
}

$options = array(
	'payment_settings' => array(
		'type' => 'tab',
		'title' => esc_html__( 'Payment Settings', 'workreap' ),
		'options' => array(
			'payment-settings' => array(
				'title' => esc_html__('General', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'hiring_payment_settings' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Hiring payments?', 'workreap'),
								'type' => 'select',
								'value' => 'enable',
								'desc' => esc_html__('Either you can enable payments or disable all over the site. If payment will be disabled then order will be created automatically', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'enable' 	=> esc_html__('Enable payment', 'workreap'),
									'disable' 	=> esc_html__('Disable payments.', 'workreap'),
								),
                            )
                        ),
                    ),
					'disable_payouts' => array(
						'label' => esc_html__('Disable payouts?', 'workreap'),
						'type' => 'select',
						'value' => 'no',
						'desc' => esc_html__('Disable payout settings from the front-end', 'workreap'),
						'help' => esc_html__('', 'workreap'),
						'choices' => array(
							'yes' 	=> esc_html__('Yes', 'workreap'),
							'no' 	=> esc_html__('No', 'workreap'),
						),
					),
					'min_amount' => array(
						'type' => 'text',
						'label' => esc_html__('Minimum withdraw amount', 'workreap'),
						'desc' => esc_html__('Add minimum amount to process wallet.', 'workreap'),
					),
					'hide_wallet' => array(
						'type' => 'hidden',
						'value' => 'no',
						'attr' => array(),
						'label' => esc_html__('Hide wallet?', 'workreap'),
						'desc' => esc_html__('Hide or show wallet system from users dashboard and from admin side.Hiring payments will work as it is but wallet system will not display in users dashboard.', 'workreap'),
						'left-choice' => array(
							'value' => 'yes',
							'label' => esc_html__('Yes', 'workreap'),
						),
						'right-choice' => array(
							'value' => 'no',
							'label' => esc_html__('No', 'workreap'),
						),
					),
					'cron_interval' => array(
						'label'   		=> esc_html__( 'Cron job interval', 'workreap' ),
						'desc'   		=> esc_html__( 'Select interval for payouts.', 'workreap' ),
						'type'    		=> 'select',
						'value'    		=> 'basic',
						'choices' 		=> $schedules_list
					),
					'payouts_methods' => array(
                        'label' => esc_html__('Payout method', 'workreap'),
						'type' => 'multi-select',
						'value' => array( 'paypal', 'bacs','payoneer' ),
						'desc' => esc_html__('Select payout method to show in freelancer and employer dashboard. Leave it empty to show all', 'workreap'),
						'choices' => array(
							'paypal' 			=> esc_html__('PayPal', 'workreap'),
							'bacs' 				=> esc_html__('Direct Bank Transfer (BACS)', 'workreap'),
							'payoneer' 			=> esc_html__('Payoneer', 'workreap')
						),
                    ),
					'allow_freelancers_withdraw' => array(	
						'label' => esc_html__('Allow withdraw', 'workreap'),
						'type' 	=> 'select',
						'value' => 'freelancers',
						'desc' 	=> esc_html__('Either enable to allow the users to create a withdrawal request from front-end or only admin can generate withdrawal from back-end by using CRON JOB', 'workreap'),
						'choices' => array(
							'freelancers' 	=> esc_html__('Allow users', 'workreap'),
							'admin' 		=> esc_html__('By admin', 'workreap'),
						)
					),
					'bank_transfer_fields' => array(
                        'label' => esc_html__('Bank transfer fields', 'workreap'),
						'type' => 'multi-select',
						'desc' => esc_html__('In case of bank transfer, you can remove from available fields. select to remove fields.', 'workreap'),
						'choices' => array(
							'bank_account_name'		=> esc_html__('Bank Account Name','workreap'),
							'bank_name'				=> esc_html__('Bank Name','workreap'),
							'bank_routing_number'	=> esc_html__('Bank Routing Number','workreap'),
							'bank_iban'				=> esc_html__('Bank IBAN','workreap'),
							'bank_bic_swift'		=> esc_html__('Bank BIC/SWIFT','workreap'),
						),
                    ),
				),
			),
			'freelancer-comm-settings' => array(
				'title' => esc_html__('Freelancer commission', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'service_fee' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Project commission fee', 'workreap'),
								'type' => 'select',
								'value' => 'percentage',
								'desc' => esc_html__('Select commission type. If project has custom commission type and value then this value setting will be bypassed with project settings.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
									'percentage' 		=> esc_html__('Percentage', 'workreap'),
									'comissions_tiers'  => esc_html__('Commission tiers', 'workreap'),
									'none'  			=> esc_html__('Remove comission', 'workreap')
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
									'desc' => esc_html__('Please add commission tiers. System will check if project cost would be under any tiers then get that tier amount and type to apply.', 'workreap'),
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
											'desc' 		=> esc_html__('Select range for the commission. If project cost will be under this selected range then below amount/percentage will be charge as comissions', 'workreap'),
											'choices' => $amount_list
										),
										'amount' 		=> array('type' => 'text',
																 'value' => 20,
																 'desc' => esc_html__('Add amount or percentage value. Please add interger value only and in case of percentage, value should not exceed above 100', 'workreap')
															),
									),
									'template' => '{{- range }}', // box title
								),
                            ),
                        ),
                    ),
					'service_commision' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Services commission fee', 'workreap'),
								'type' => 'select',
								'value' => 'percentage',
								'desc' => esc_html__('Select commission type. If service has custom comission type and value then this value setting will be bypassed with service settings.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
									'percentage' 		=> esc_html__('Percentage', 'workreap'),
									'comissions_tiers'  => esc_html__('Comissions tiers', 'workreap'),
									'none'  			=> esc_html__('Remove comission', 'workreap')
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
									'desc' => esc_html__('Please add commission tiers. System will check if service cost would be under any tiers then get that tier amount and type to apply.', 'workreap'),
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
																 'desc' => esc_html__('Add amount or percentage value. Please add interger value only and in case of percentage, value should not exceed above 100', 'workreap')
															),
									),
									'template' => '{{- type }}', // box title
								),
                            ),
                        ),
                    ),
				),
			),
			'employer-comm-settings' => array(
				'title' => esc_html__('Employer commission', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'employer_service_fee' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Project commission fee', 'workreap'),
								'type' => 'select',
								'value' => 'none',
								'desc' => esc_html__('Select commission type. This will be used as taxes to the employers while hiring', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
									'percentage' 		=> esc_html__('Percentage', 'workreap'),
									'comissions_tiers'  => esc_html__('Commission tiers', 'workreap'),
									'none'  			=> esc_html__('Remove comission', 'workreap')
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
									'desc' => esc_html__('Please add commission tiers. System will check if project cost would be under any tiers then get that tier amount and type to apply.', 'workreap'),
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
											'desc' 		=> esc_html__('Select range for the commission. If project cost will be under this selected range then below amount/percentage will be charge as comissions', 'workreap'),
											'choices' => $amount_list
										),
										'amount' 		=> array('type' => 'text',
																 'value' => 20,
																 'desc' => esc_html__('Add amount or percentage value. Please add interger value only and in case of percentage, value should not exceed above 100', 'workreap')
															),
									),
									'template' => '{{- range }}', // box title
								),
                            ),
                        ),
                    ),
					'employer_service_commision' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Services commission fee', 'workreap'),
								'type' => 'select',
								'value' => 'none',
								'desc' => esc_html__('Select commission type. This will be used as taxes to the employers while hiring', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'fixed' 			=> esc_html__('Fixed amount', 'workreap'),
									'percentage' 		=> esc_html__('Percentage', 'workreap'),
									'comissions_tiers'  => esc_html__('Comissions tiers', 'workreap'),
									'none'  			=> esc_html__('Remove comission', 'workreap')
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
									'desc' => esc_html__('Please add commission tiers. System will check if service cost would be under any tiers then get that tier amount and type to apply.', 'workreap'),
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
																 'desc' => esc_html__('Add amount or percentage value. Please add interger value only and in case of percentage, value should not exceed above 100', 'workreap')
															),
									),
									'template' => '{{- type }}', // box title
								),
                            ),
                        ),
                    ),
				),
			),
		)
	)
);
