<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'proposal-settings' => array(
		'title' => esc_html__('Proposal Settings', 'workreap'),
		'type' => 'tab',
		'options' => array(
			'dir_proposal_page' => array(
				'label' => esc_html__('Choose Submit Proposal Page', 'workreap'),
				'type' => 'multi-select',
				'population' => 'posts',
				'source' => 'page',
				'desc' => esc_html__('Choose page to show submit project proposal form.', 'workreap'),
				'limit' => 1,
				'prepopulate' => 100,
			),

			'hint_text' => array(
				'type' => 'textarea',
				'value' => '',
				'attr' => array(),
				'label' => esc_html__('Service Fee Hint', 'workreap'),
				'desc' => esc_html__('Add hint text for service field', 'workreap'),
			),
			'hint_text_two' => array(
				'type' => 'textarea',
				'value' => '',
				'attr' => array(),
				'label' => esc_html__('Service Fee Deduction Hint', 'workreap'),
				'desc' => esc_html__('Add hint text for deduction price field', 'workreap'),
			),  
			'proposal_connects' => array(
								'type' => 'text',
								'value' => '',                                       
								'label' => esc_html__('Proposal credits', 'workreap'),
								'desc' => esc_html__('No of credits per proposal', 'workreap'),                                        
							),
			'proposal_price_type' => array(	
				'label' => esc_html__('Allow proposal price type', 'workreap'),
				'type' 	=> 'select',
				'value' => 'any',
				'desc' 	=> esc_html__('Allow the freelancers to add proposal price within the employer budget or any price', 'workreap'),
				'choices' => array(
					'budget' 	=> esc_html__('Within the budget', 'workreap'),
					'any' 		=> esc_html__('Any Price', 'workreap'),
				)
			),
			'proposal_message_option' => array(
				'label' => esc_html__('Chat option', 'workreap'),
				'type' => 'switch',
				'value' => 'disable',
				'desc' => esc_html__('Enable/Disable chat option for employer on project proposal listing page.', 'workreap'),
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'workreap'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'workreap'),
				),
			),
			'hide_proposal_on_project' => array(	
				'label' => esc_html__('Hide proposal on project page', 'workreap'),
				'type' 	=> 'select',
				'value' => 'no',
				'desc' 	=> esc_html__('Hide the proposals on the project detail page.', 'workreap'),
				'choices' => array(
					'yes' 	=> esc_html__('Yes, hide it', 'workreap'),
					'no' 		=> esc_html__('No, show it', 'workreap'),
				)
			),
			'allow_proposal_edit' => array(
				'label'   		=> esc_html__( 'Allow proposal edit', 'workreap' ),
				'desc'   		=> esc_html__( 'Allow the freelancer to edit their proposals after submitting', 'workreap' ),
				'type'    		=> 'select',
				'value'    		=> 'yes',
				'choices'	=> array(
					'no'   => esc_html__('No', 'workreap'),
					'yes'	=> esc_html__('Yes', 'workreap')
				)
			),
			'restrict_proposals' => array(
				'label' => esc_html__('Restrict to submit proposal', 'workreap'),
				'type' => 'select',
				'value' => 'no',
				'desc' => esc_html__('Restrict to submit proposals if job has expired', 'workreap'),
				'choices' => array(
					'no'   => esc_html__('No', 'workreap'),
					'yes'  => esc_html__('Yes', 'workreap'),
				)
			),
		),
	)
);
