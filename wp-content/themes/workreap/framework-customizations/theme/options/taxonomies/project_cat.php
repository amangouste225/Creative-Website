<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'category_settings' => array(
        'type' => 'group',
        'options' => array(
            'category_icon' => array(
                'type' => 'icon-v2',
                'preview_size' => 'small',
                'modal_size' => 'medium',
                'label' => esc_html__('Category Icon', 'workreap'),
                'desc' => esc_html__('Choose Category Icon. Leave blank to not display.', 'workreap'),
            ),
			'category_image' => array(
                'type'          => 'upload',
                'preview_size'  => 'medium',
                'modal_size'    => 'full',
                'label' => esc_html__('Category Image', 'workreap'),
                'desc' => esc_html__('Choose Category Image. Leave blank to not display.', 'workreap'),
            ),
        )
    ),
);

