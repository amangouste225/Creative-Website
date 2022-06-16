<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(  
	
	'micro_services_settings' => array(
				'title' 	=> esc_html__('Service setting', 'workreap'),
				'type' 		=> 'box',
				'options' 	=> array(
							'cus_project_reviews' => array(
											'type' 	=> 'html',
											'html' 	=> esc_html__('Feedback in the case of Service order is compeleted or cancelled. ', 'workreap'),
											'label' => esc_html__('', 'workreap'),
											//'desc' 	=> esc_html__('Feedback in the case of order is compeleted or cancelled. ', 'workreap'),
											'help' 	=> esc_html__('', 'workreap'),
											'images_only' => true,
										),
							'feedback' => array(
									'type' => 'textarea',
									'value' => '',
									'label' => esc_html__('Service feedback', 'workreap'),
								),
				),
		),
);

