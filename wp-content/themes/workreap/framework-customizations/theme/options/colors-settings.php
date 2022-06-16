<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'colors' => array(
        'title' => esc_html__('Styling Options', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'general-box' => array(
                'title' => esc_html__('Styling Options', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'color_settings' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Styling Options', 'workreap'),
                                'type' => 'switch',
                                'left-choice' => array(
                                    'value' => 'default',
                                    'label' => esc_html__('Default Color', 'workreap')
                                ),
                                'right-choice' => array(
                                    'value' => 'custom',
                                    'label' => esc_html__('Custom Color', 'workreap')
                                ),
                                'value' => 'disable',
                            )
                        ),
                        'choices' => array(
                            'custom' => array(
                                'primary_color' => array(
                                    'type' => 'color-picker',
                                    'value' => '#f72a85',
                                    'attr' => array(),
                                    'label' => esc_html__('Primary Color', 'workreap'),
                                    'desc' => esc_html__('Add theme primary color.', 'workreap'),
                                    'help' => esc_html__('', 'workreap'),
                                ),
								'secondary_color' => array(
                                    'type' => 'color-picker',
                                    'value' => '#934cff',
                                    'attr' => array(),
                                    'label' => esc_html__('Secondary Color', 'workreap'),
                                    'desc' => esc_html__('Add theme secondary color.', 'workreap'),
                                    'help' => esc_html__('', 'workreap'),
                                ),
								'tertiary_color' => array(
                                    'type' => 'color-picker',
                                    'value' => '#fbde44',
                                    'attr' => array(),
                                    'label' => esc_html__('Tertiary Color', 'workreap'),
                                    'desc' => esc_html__('Add theme tertiary color.', 'workreap'),
                                    'help' => esc_html__('', 'workreap'),
                                ),
                            ),
                            'default' => array(),
                        ),
                        'show_borders' => false,
                    ),
					'footer_settings' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => '',
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Footer Options', 'workreap'),
                                'type' => 'switch',
                                'left-choice' => array(
                                    'value' => 'default',
                                    'label' => esc_html__('Default Color', 'workreap')
                                ),
                                'right-choice' => array(
                                    'value' => 'custom',
                                    'label' => esc_html__('Custom Color', 'workreap')
                                ),
                                'value' => 'disable',
                            )
                        ),
                        'choices' => array(
                            'custom' => array(
								'footer_bg_color' => array(
									'type' => 'color-picker',
									'value' => '#323232',
									'attr' => array(),
									'label' => esc_html__('Footer background color', 'workreap'),
									'desc' => esc_html__('Add footer background color, leave it empty to use defaults', 'workreap'),
									'help' => esc_html__('', 'workreap'),
								),
								'footer_text_color' => array(
									'type' => 'color-picker',
									'value' => '#ccc',
									'attr' => array(),
									'label' => esc_html__('Footer text color', 'workreap'),
									'desc' => esc_html__('Add footer text color', 'workreap'),
									'help' => esc_html__('', 'workreap'),
								),
                            ),
                            'default' => array(),
                        ),
                        'show_borders' => false,
                    ),
					'body_bg_color' => array(
						'type' => 'color-picker',
						'value' => '#f7f7f7',
						'attr' => array(),
						'label' => esc_html__('Body background color', 'workreap'),
						'desc' => esc_html__('Add body background color, leave it empty to use defaults', 'workreap'),
						'help' => esc_html__('', 'workreap'),
					),
					
					'featured_job_bg' => array(
                        'type'  => 'color-picker',
						'value' => '#f1c40f',
						'attr'  => array(),
						'label' => esc_html__('Featured job background color', 'workreap'),
						'desc'  => esc_html__('', 'workreap'),
						'help'  => esc_html__('', 'workreap'),
                    ),
					'freelancer_overlay' => array(
                        'type'  => 'rgba-color-picker',
						'value' => 'rgba(0,0,0,0.6)',
						'attr'  => array(),
						'label' => esc_html__('Freelancer overlay', 'workreap'),
						'desc'  => esc_html__('Add freelancer banner overlay color', 'workreap'),
						'help'  => esc_html__('', 'workreap'),
                    ),
					'employer_overlay' => array(
                        'type'  => 'rgba-color-picker',
						'value' => 'rgba(0,0,0,0.6)',
						'attr'  => array(),
						'label' => esc_html__('Employer overlay', 'workreap'),
						'desc'  => esc_html__('Add employer banner overlay color', 'workreap'),
						'help'  => esc_html__('', 'workreap'),
                    ),
                )
            ),
        )
    )
);
