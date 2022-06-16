<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array (
    'profile_strength' => array (
        'title'   => esc_html__('Profile health' , 'workreap') ,
        'type'    => 'tab' ,
        'options' => array (
			'profile_strength_hint' => array(
				'type' => 'html',
				'html' => esc_html__('Profile health fields', 'workreap'),
				'label' => esc_html__('', 'workreap'),
				'desc' => wp_kses( __( 'Please select what you want to include for Profile health, please add percentage, please make sure total percentage should be 100. Like if you select two fields then it could be <br> 50 + 50 or 30 + 70 or what ever you want to add', 'workreap'),array(
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
			'show_strength'  => array(
				'label' => esc_html__( 'Show Profile health', 'workreap' ),
				'type'  => 'select',
				'value' => 'verified',
				'desc' => esc_html__('Show Profile health on freelancer detail and search result page', 'workreap'),
				'choices'	=> array(
					'yes'  => esc_html__('Yes', 'workreap'),
					'no'	=> esc_html__('No', 'workreap')
				)
			),
			'hide_profiles' => array(
				'type' => 'multi-picker',
				'label' => false,
				'desc' => '',
				'picker' => array(
					'gadget' => array(
						'label' => esc_html__('Hide profiles', 'workreap'),
						'type' => 'select',
						'value' => 'no',
						'desc' => esc_html__('Hide profiles in search result if Profile health is less than percentage defined below', 'workreap'),
						'choices' => array(
							'yes' => esc_html__('Yes', 'workreap'),
							'no' => esc_html__('No', 'workreap'),
						)
					)
				),
				'choices' => array(
					'yes' => array(
						'define_percentage' => array(
							'type' => 'number',
							'max' => 100,
							'min' => 1,
							'value' => '85',
							'label' => esc_html__('Define percentage', 'workreap'),
							'desc' => esc_html__('Add percentage on which profile will appear be visible.', 'workreap'),
							'desc' => wp_kses( __( 'Might possible after enable this your previous freelancers get hidded on search result page, Please update your previous users to show them in the search result. <a href="javascritp:;" class="wt-update-profile-health">Update freelaners</a>', 'workreap' ), array(
								'a' => array(
									'href' => array(),
									'class' => array(),
									'title' => array()
								),
								'br' => array(),
								'em' => array(),
								'strong' => array(),
							) ),
						),
					),
					'default' => array(),
				),
				'show_borders' => false,
			),
            'profile_strength_fields' => array(
				'type' => 'addable-box',
				'label' => esc_html__('Profile field', 'workreap'),
				'desc' => esc_html__('Please select what you want to include for Profile health', 'workreap'),
				'box-options' 	=> array(
					'title' 		=> array('type' => 'text'),
					'field' 		=> array(	
						'label' => esc_html__('Profile health field', 'workreap'),
						'type' 	=> 'select',
						'desc' 	=> esc_html__('Select type and add percentage.', 'workreap'),
						'choices' => array(
							'tagline' 			=> esc_html__('Profile tagline', 'workreap'),
							'description' 		=> esc_html__('Profile description', 'workreap'),
							'avatar' 			=> esc_html__('Profile picture', 'workreap'),
							'identity_verification' 			=> esc_html__('Identity Verification', 'workreap'),
							'skills' 			=> esc_html__('Skills', 'workreap'),
							'experience' 		=> esc_html__('Experience', 'workreap'),
						)
					),
					'percentage' 		=> array('type' => 'text'),
				),
				'template' => '{{- title }}', // box title
				'limit' => 6, 
			),
        ) ,
    ) ,
);
