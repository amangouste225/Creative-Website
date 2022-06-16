<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'icon_settings' => array(
        'type' => 'group',
        'options' => array(
            'specialization_icon' => array(
                'type' => 'icon-v2',
                'preview_size' => 'small',
                'modal_size' => 'medium',
                'label' => esc_html__('Specialization Icon', 'workreap'),
                'desc' => esc_html__('Choose specialization icon. Leave blank to not display.', 'workreap'),
            ),
        )
    ),
);

