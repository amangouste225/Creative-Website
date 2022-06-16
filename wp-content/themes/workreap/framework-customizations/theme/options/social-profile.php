<?php

if (!defined('FW')) {
    die('Forbidden');
}

$social_settings    = function_exists('workreap_get_social_media_icons_list') ? workreap_get_social_media_icons_list('yes') : array();

$options = array(
    'social_profile_settings' => array(
        'title' => esc_html__('Social Profile Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'social_profile_box' => array(
                'title' => esc_html__('Social Profile Settings', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'freelancer_social_profile_settings' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Freelancer Social profile', 'workreap'),
                                'type' => 'switch',
                                'value' => 'disable',
                                'desc' => esc_html__('Enable/Disable social profile url like options for freelancer.', 'workreap'),
                                'left-choice' => array(
                                    'value' => 'enable',
                                    'label' => esc_html__('Enable', 'workreap'),
                                ),
                                'right-choice' => array(
                                    'value' => 'disable',
                                    'label' => esc_html__('Disable', 'workreap'),
                                ),
                            )
                        ),
                        'choices' => array(
                            'enable' => array(
                            ),
                        )
                    ),
                    'employer_social_profile_settings' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Employer Social profile', 'workreap'),
                                'type' => 'switch',
                                'value' => 'disable',
                                'desc' => esc_html__('Enable/Disable social profile url like options for employer.', 'workreap'),
                                'left-choice' => array(
                                    'value' => 'enable',
                                    'label' => esc_html__('Enable', 'workreap'),
                                ),
                                'right-choice' => array(
                                    'value' => 'disable',
                                    'label' => esc_html__('Disable', 'workreap'),
                                ),
                            )
                        ),
                        'choices' => array(
                            'enable' => array(
                            ),
                        )
                    )
                )
            ),
        )
    )
);

if(!empty($social_settings)) {
    foreach($social_settings as $key => $val ) {
        $options['social_profile_settings']['options']['social_profile_box']['options']['freelancer_social_profile_settings']['choices']['enable'][$key]  = array(
            'type' => 'multi-picker',
            'label' => false,
            'desc' => '',
            'picker' => array(
                'gadget' => array(
                    'type' => 'switch',
                    'value' => 'disable',
                    'attr' => array(),
                    'label' => $val,
                    'left-choice' => array(
                        'value' => 'disable',
                        'label' => esc_html__('Disable', 'workreap'),
                    ),
                    'right-choice' => array(
                        'value' => 'enable',
                        'label' => esc_html__('Enable', 'workreap'),
                    ),
                )
            ),
        );

        $options['social_profile_settings']['options']['social_profile_box']['options']['employer_social_profile_settings']['choices']['enable'][$key]    = array(
            'type' => 'multi-picker',
            'label' => false,
            'desc' => '',
            'picker' => array(
                'gadget' => array(
                    'type' => 'switch',
                    'value' => 'disable',
                    'attr' => array(),
                    'label' => $val,
                    'left-choice' => array(
                        'value' => 'disable',
                        'label' => esc_html__('Disable', 'workreap'),
                    ),
                    'right-choice' => array(
                        'value' => 'enable',
                        'label' => esc_html__('Enable', 'workreap'),
                    ),
                )
            ),
        );

    }
}
