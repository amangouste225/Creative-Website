<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
	'settings' => array(
        'title' => esc_html__('General Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
            'price' => array(
				'type' => 'text',
				'label' => esc_html__('Milestone price', 'workreap'),
				'desc' => esc_html__('Add milestone price to display.', 'workreap'),
			),
            'due_date' => array(
				'label' => esc_html__('Due Date', 'workreap'),
				'type' => 'datetime-picker',
				'datetime-picker' => array(
					'format'  => 'Y-m-d',
					'maxDate' => false, 
					'minDate' => date('Y-m-d'),
					'timepicker' => false,
					'datepicker' => true,
					'defaultTime' => ''
				),
				'desc' => esc_html__('Add milestone due date', 'workreap')
			)
        )
    ),
);
