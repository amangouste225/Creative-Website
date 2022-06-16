<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'location_settings' => array(
        'type' => 'group',
        'options' => array(            
            'image' => array(
                'type' => 'upload',
                'attr' => array('class' => 'custom-class', 'data-foo' => 'bar'),
                'label' => esc_html__('Location Image', 'workreap'),
                'desc' => esc_html__('Upload location flag. It will display in listing and detail page.', 'workreap'),
                'images_only' => true,
            ),
        )
    ),
);

