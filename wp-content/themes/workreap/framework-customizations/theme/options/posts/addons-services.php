<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(    
    'addons_services_settings' => array(
        'title' => esc_html__('Addons Service Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'price' => array(
				'label' => esc_html__('Addons Service Price', 'workreap'),
                'desc' 	=> esc_html__('Addons Service Price', 'workreap'),
                'type' 	=> 'text',
                'value' => '0',
            )
        )
    ),
);

