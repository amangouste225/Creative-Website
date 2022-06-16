<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(  
	'dis_settings' => array(
				'title' 	=> esc_html__('Dispute admin feedback', 'workreap'),
				'type' 		=> 'box',
				'options' 	=> array(
							'feedback' => array(
									'type' => 'textarea',
									'value' => '',
									'label' => esc_html__('Dispute feedback', 'workreap'),
								),
							
				),
		),
);

