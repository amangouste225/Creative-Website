<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'comingsoon_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Maintenance', 'workreap'),
        'options' => array(
            'comingsoon-box' => array(
                'title' => esc_html__('Coming Soon Settings', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'maintenance' => array(
                        'type' => 'switch',
                        'value' => 'disable',
                        'label' => esc_html__('Maintenance Mode', 'workreap'),
                        'left-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                    ),
                    'logo' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Logo Image', 'workreap'),
                        'desc' => esc_html__('Upload Your logo image on coming soon page. Leave it empty to hide.', 'workreap'),
                        'images_only' => true,
                    ),
                    'title' => array(
                        'type' => 'text',
                        'label' => esc_html__('Title', 'workreap'),
                        'value' => 'Stay Tuned, We’re Launching Very Soon!',
                        'desc' => esc_html__('Leave it empty to hide.', 'workreap'),
                    ),
                    'description' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('Description', 'workreap'),
                        'value' => '',
                        'desc' => esc_html__('Custom HTML Accepted. Leave it empty to hide.', 'workreap'),
                    ),
                    'date' => array(
                        'type' => 'datetime-picker',
                        'label' => esc_html__('Set Date', 'workreap'),
                        'datetime-picker' => array(
                            'format' => 'Y/m/d H:i:s', // Format datetime.
                            'maxDate' => false, // By default there is not maximum date , set a date in the datetime format.
                            'minDate' => false, // By default minimum date will be current day, set a date in the datetime format.
                            'timepicker' => true, // Show timepicker.
                            'datepicker' => true, // Show datepicker.
                            'defaultTime' => '12:00' // If the input value is empty, timepicker will set time use defaultTime.
                        ),
                    ),
                    'img' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Maintenance Image', 'workreap'),
                        'desc' => esc_html__('Upload Your background image on coming soon page.', 'workreap'),
                        'images_only' => true,
                    ),
                    'copyright' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('Footer Description', 'workreap'),
                        'value' => 'Copyright © 2019 All Rights Reserved - Workreap',
                        'desc' => esc_html__('', 'workreap'),
                    ),
                )
            ),
        )
    )
);
