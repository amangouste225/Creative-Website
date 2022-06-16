<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'general' => array(
        'title' => esc_html__('General Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'general-box' => array(
                'title' => esc_html__('General Settings', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'preloader' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'picker' => array(
                            'gadget' => array(
                                'label' => __('Enable Preloader', 'workreap'),
                                'type' => 'switch',
                                'value' => 'enable',
                                'desc' => esc_html__('Preloader on/off', 'workreap'),
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
                                'preloader' => array(
                                    'type' => 'multi-picker',
                                    'label' => false,
                                    'desc' => false,
                                    'picker' => array(
                                        'gadget' => array(
                                            'type' => 'select',
                                            'value' => 'default',
                                            'label' => esc_html__('Select Type', 'workreap'),
                                            'desc' => esc_html__('Please select loader type.', 'workreap'),
                                            'choices' => array(
                                                'default' => esc_html__('Default', 'workreap'),
                                                'custom' => esc_html__('Custom', 'workreap'),
                                            ),
                                        )
                                    ),
                                    'choices' => array(
                                        'custom' => array(
                                            'loader' => array(
                                                'type' => 'upload',
                                                'label' => esc_html__('Loader Image?', 'workreap'),
                                                'desc' => esc_html__('Upload loader image.', 'workreap'),
                                                'images_only' => true,
                                            ),
											'loader_x' => array(
												'type' => 'slider',
												'value' => 30,
												'properties' => array(
													'min' => 0,
													'max' => 500,
													'sep' => 5,
												),
												'label' => esc_html__('loader width', 'workreap'),
												'desc' => esc_html__('Please select loader width, leave it empty to use default', 'workreap'),
											),
											'loader_y' => array(
												'type' => 'slider',
												'value' => 30,
												'properties' => array(
													'min' => 0,
													'max' => 500,
													'sep' => 5,
												),
												'label' => esc_html__('Loader height', 'workreap'),
												'desc' => esc_html__('Please select loader height, leave it empty to use default', 'workreap'),
											),
                                        ),
                                    ),
                                ),
                                'loader_speed' => array(
                                    'type' => 'select',
                                    'value' => '1000',
                                    'label' => esc_html__('Loader duration?', 'workreap'),
                                    'desc' => esc_html__('Seelct site loader speed', 'workreap'),
                                    'choices' => array(
                                        '250' => esc_html__('1/4th Seconds', 'workreap'),
                                        '500' => esc_html__('Half Second', 'workreap'),
                                        '1000' => esc_html__('1 Second', 'workreap'),
                                        '2000' => esc_html__('2 Seconds', 'workreap'),
                                        '3000' => esc_html__('3 Seconds', 'workreap'),
                                        '4000' => esc_html__('4 Seconds', 'workreap'),
                                        '5000' => esc_html__('5 Seconds', 'workreap'),
                                    ),
                                )
                            ),
                        )
                    ),
					 'sticky_speed' => array(
						'type' => 'select',
						'value' => '5000',
						'label' => esc_html__('Sticky autoclose time', 'workreap'),
						'desc' => esc_html__('Select time duration for the sticky messages', 'workreap'),
						'choices' => array(
							'5000' => esc_html__('5 seconds', 'workreap'),
							'7000' => esc_html__('7 seconds', 'workreap'),
							'9000' => esc_html__('9 Second', 'workreap'),
							'13000' => esc_html__('13 Seconds', 'workreap'),
							'15000' => esc_html__('15 Seconds', 'workreap'),
							'17000' => esc_html__('17 Seconds', 'workreap'),
							'19000' => esc_html__('19 Seconds', 'workreap'),
						),
					),
					'archive_sidebar' => array(
						'type' => 'select',
						'value' => 'left',
						'label' => esc_html__('Archive sidebar position', 'workreap'),
						'desc' => esc_html__('Please archive/bogs sidebar position.', 'workreap'),
						'choices' => array(
							'left' => esc_html__('Left', 'workreap'),
							'right' => esc_html__('Right', 'workreap'),
						),
					),
					'app_available' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('APP Link', 'workreap'),
                                'type' => 'switch',
                                'value' => 'enable',
                                'desc' => esc_html__('Add your APP Link on the site', 'workreap'),
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
                                'link' => array(
									'type' => 'text',
									'label' => esc_html__('Add link', 'workreap'),
									'desc' => esc_html__('Add app link', 'workreap'),
								)
                            ),
                        )
                    ),
                    '404_banner' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Upload banner', 'workreap'),
                        'desc' => esc_html__('Upload 404 page banner. Leave it to empty', 'workreap'),
                        'images_only' => true,
                    ),
                    '404_title' => array(
                        'type' => 'text',
                        'value' => 'The page you are looking for, does not exist.',
                        'label' => esc_html__('404 Title', 'workreap'),
                    ),
                    '404_description' => array(
                        'type' => 'textarea',
                        'value' => '',
                        'label' => esc_html__('404 Description', 'workreap'),
                    ),
                    'custom_css' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('Front-End CSS', 'workreap'),
                        'desc' => esc_html__('Add your front-end css code here if you want to target specifically on different elements on the front-end.', 'workreap'),
                    ),
                    'backend_css' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('Back-End CSS', 'workreap'),
                        'desc' => esc_html__('Add your back-end css code here if you want to target specifically on different elements on the back-end.', 'workreap'),
                    ),
                )
            ),
        )
    )
);
