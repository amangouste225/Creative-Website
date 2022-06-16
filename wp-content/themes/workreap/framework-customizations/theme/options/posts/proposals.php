<?php

if (!defined('FW')) {
    die('Forbidden');
}

$list               = worktic_job_duration_list();

$options = array(    
    'project_settings' => array(
        'title' => esc_html__('Proposal Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'project' => array(
				'label' => esc_html__('Project', 'workreap'),
				'type' => 'multi-select',
				'population' => 'posts',
				'source' => 'projects',
				'desc' => esc_html__('Project for which this proposal is being submited', 'workreap'),
				'limit' => 1,
				'prepopulate' => 100,
			), 
			'proposed_amount' => array(
				'label' => esc_html__('Proposed Amount', 'workreap'),
                'desc' => esc_html__('Proposed amount by freelancer', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
			'proposed_amount' => array(
				'label' => esc_html__('Proposed Amount', 'workreap'),
                'desc' => esc_html__('Proposed amount by freelancer', 'workreap'),
                'type' => 'text',
                'value' => '',
            ),
			'estimeted_time' => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__('Estimeted Hours', 'workreap'),
                'desc'  => esc_html__('Freelancer proposed time estimated hours to finish this hourly project', 'workreap')
            ),  
			'per_hour_amount' => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__('Per Hour Amount', 'workreap'),
                'desc'  => esc_html__('Per Hour amount for hourly project', 'workreap')
            ), 
            'proposal_docs' => array(
                'type'  => 'multi-upload',
                'value' => array(),
                'label' => esc_html__('Upload Documents', 'workreap'),
                'desc'  => esc_html__('Upload proposal documents', 'workreap'),         
                'images_only' => false,            
                'files_ext' => array( 'doc', 'docx', 'pdf', 'zip', 'png', 'jpg' ),  
            ),
        )
    ),
);

