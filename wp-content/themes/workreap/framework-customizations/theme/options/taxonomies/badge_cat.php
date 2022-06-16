<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'badge_color' => array(
		'type' => 'color-picker',
		'value' => '#505050',
		'attr' => array(),
		'label' => esc_html__('Image Background Color', 'workreap'),
		'desc' => esc_html__('Image Background color', 'workreap'),
		'help' => esc_html__('', 'workreap'),
	),
	'badge_icon' => array(
		'type' => 'upload',
		'label' => esc_html__('Badge icon', 'workreap'),
		'hint' => esc_html__('', 'workreap'),
		'desc' => esc_html__('Badge icon.', 'workreap'),
		'images_only' => true,
	),
);

