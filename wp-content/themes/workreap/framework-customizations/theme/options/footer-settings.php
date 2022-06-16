<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'footer' => array(
        'title' => esc_html__('Footer Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'footer_settings' => array(
                'title' => esc_html__('Footer Settings', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'footer_type' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'value' => array('gadget' => 'footer_v1'),
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Footer Type', 'workreap'),
                                'type' => 'image-picker',
                                'choices' => array(
                                    'footer_v1' => array(
                                        'label' => __('Footer V1', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/footers/f_1.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/footers/f_1.jpg'
                                        ),
                                    ),
                                    'footer_v2' => array(
                                        'label' => __('Footer V2', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/footers/f_2.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/footers/f_2.jpg'
                                        ),
                                    ),
                                    'footer_v3' => array(
                                        'label' => __('Footer V3', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/footers/f_3.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/footers/f_3.jpg'
                                        ),
                                    ),
                                ),
                                'desc' => esc_html__('Select footer type.', 'workreap'),
                            )
                        ),
                        'choices' => array(
                            'footer_v1' => array(
								'menu' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Footer Menu?', 'workreap'),
									'desc' => esc_html__('Enable footer menu', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
								'copyright' => array(
									'type' => 'textarea',
									'value' => 'Copyright &copy; 2019 The Workreap, All Rights Reserved amentotech',
									'label' => esc_html__('Footer Copyright', 'workreap'),
								),
                                'join' => array(
                                    'type' => 'multi-picker',
                                    'label' => false,
                                    'desc' => '',
                                    'picker' => array(
                                        'gadget' => array(
                                            'type' => 'switch',
                                            'value' => 'disable',
                                            'attr' => array(),
                                            'label' => esc_html__('Join section?', 'workreap'),
                                            'desc' => esc_html__('Enable join now section', 'workreap'),
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
                                    'choices' => array(
                                        'enable' => array(
                                            'title' => array(
												'label' => esc_html__('Heading?', 'workreap'),
												'type' => 'wp-editor',
												'value' => '',
												'desc' => esc_html__('', 'workreap')
											),
											'page_url' => array(
												'label' => esc_html__('Register page link?', 'workreap'),
												'type' => 'text',
												'value' => '',
												'desc' => esc_html__('Add page URL, leave it empty to hide. If regisration would be as single steps then it will open registreation POPUP. When user will be logged in then this button will not appear.', 'workreap')
											),
                                        ),
                                        'default' => array(),
                                    ),
                                    'show_borders' => false,
                                ),
								'footer_links' => array(
									'label' => esc_html__('Link', 'workreap'),
									'type' => 'addable-popup',
									'value' => array(),
									'desc' => esc_html__('Add links', 'workreap'),
									'popup-options' => array(
										'heading' => array(
															'label' => esc_html__('Heading', 'workreap'),
															'type' => 'text',
															'value' => ''
														),
										 'links' => array(
											'type' => 'addable-box',
											'label' => esc_html__('Add link', 'workreap'),
											'desc' => esc_html__('', 'workreap'),
											'box-options' => array(
												'text' => array(
															'label' => esc_html__('link title', 'workreap'),
															'type' => 'text',
															'value' => ''
														),
												'link' => array(
															'label' => esc_html__('Link', 'workreap'),
															'type' => 'text',
															'value' => ''
														),
												'target' => array(
													'type' => 'switch',
													'value' => 'disable',
													'attr' => array(),
													'label' => esc_html__('Link target', 'workreap'),
													'desc' => esc_html__('', 'workreap'),
													'left-choice' => array(
														'value' => '_blank',
														'label' => esc_html__('Blank', 'workreap'),
													),
													'right-choice' => array(
														'value' => '_self',
														'label' => esc_html__('Same window', 'workreap'),
													),
												),
											),
											'template' => '{{- text }}', // box title
											'add-button-text' => esc_html__('Add', 'workreap'),
											'sortable' => true,
										),
										'more' => array(
														'label' => esc_html__('View All link', 'workreap'),
														'type'  => 'text',
														'value' => '',
														'desc'  => esc_html__('Leave it empty to hide', 'workreap'),
													),
									),
									'template' => '{{- heading }}',
                                ),
                                'question_title' => array(
									'type' => 'text',
									'value' => '',
                                    'label' => esc_html__('Question Title', 'workreap'),
                                    'desc'  => esc_html__('Add question title or leave it empty to hide', 'workreap'),
								),   
                                'footer_email' => array(
									'type' => 'text',
									'value' => '',
                                    'label' => esc_html__('Footer Email', 'workreap'),
                                    'desc'  => esc_html__('Add footer email or leave it empty to hide', 'workreap'),
								),   
								'contact-section' => array(
									'type' => 'html',
									'html' => esc_html__('About section', 'workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => esc_html__('Add about information in footer', 'workreap'),
									'help' => esc_html__('', 'workreap'),
									'images_only' => true,
								),
								'footer_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Logo?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('logo for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
								),
								'footer_content' => array(
									'label' => esc_html__('Footer content', 'workreap'),
									'type' => 'wp-editor',
									'value' => '',
									'desc' => esc_html__('Add page URL, leave it empty to hide.', 'workreap')
								),
								'socials' => array(
                                    'label' => esc_html__('Social Profiles', 'workreap'),
                                    'type' => 'addable-popup',
                                    'value' => array(),
                                    'desc' => esc_html__('Add Social Icons as much as you want. Choose the icon, url and the title', 'workreap'),
                                    'popup-options' => array(
                                        'social_name' => array(
                                            'label' => esc_html__('Title', 'workreap'),
                                            'type' => 'text',
                                            'value' => 'Name',
                                            'desc' => esc_html__('The Title of the Link', 'workreap')
                                        ),
                                        'social_icons_list' => array(
                                            'type' => 'new-icon',
                                            'value' => 'fa-smile-o',
                                            'attr' => array(),
                                            'label' => esc_html__('Choose Icon', 'workreap'),
                                            'desc' => esc_html__('', 'workreap'),
                                            'help' => esc_html__('', 'workreap'),
                                        ),
                                        'social_url' => array(
                                            'label' => esc_html__('Url', 'workreap'),
                                            'type' => 'text',
                                            'value' => '#',
                                            'desc' => esc_html__('The link to the social profile.', 'workreap')
                                        ),
                                    ),
                                    'template' => '{{- social_name }}',
                                ),
                            ),
                            'footer_v2' => array(
								'menu' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Footer Menu?', 'workreap'),
									'desc' => esc_html__('Enable footer menu', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
								'copyright' => array(
									'type' => 'textarea',
									'value' => 'Copyright &copy; 2019 The Workreap, All Rights Reserved amentotech',
									'label' => esc_html__('Footer Copyright', 'workreap'),
								),
                                'join' => array(
                                    'type' => 'multi-picker',
                                    'label' => false,
                                    'desc' => '',
                                    'picker' => array(
                                        'gadget' => array(
                                            'type' => 'switch',
                                            'value' => 'disable',
                                            'attr' => array(),
                                            'label' => esc_html__('Join section?', 'workreap'),
                                            'desc' => esc_html__('Enable join now section', 'workreap'),
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
                                ),  
								'contact-section' => array(
									'type' => 'html',
									'html' => esc_html__('About section','workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => esc_html__('Add about information in footer', 'workreap'),
									'help' => esc_html__('', 'workreap'),
									'images_only' => true,
                                ),
                                'newsletter_img' => array(
									'type' => 'upload',
									'label' => esc_html__('Newsletter Image?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('News letter image for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
								),
								'footer_bg_img' => array(
									'type' => 'upload',
									'label' => esc_html__('Background Image?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('Background image for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
                                ),
                                'primary_color' => array(
                                    'type' => 'rgba-color-picker',
                                    'value' => 'rgba(251,222,68,1)',
                                    'attr' => array(),
                                    'label' => esc_html__('Primary Color', 'workreap'),
                                    'desc' => esc_html__('Add footer primary color.', 'workreap'),
                                ),
                                'secondary_color' => array(
                                    'type' => 'rgba-color-picker',
                                    'value' => 'rgba(144,19,254,0.97)',
                                    'attr' => array(),
                                    'label' => esc_html__('Secondary Color', 'workreap'),
                                    'desc' => esc_html__('Add footer secondary color.', 'workreap'),
                                ),
								'footer_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Logo?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('logo for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
								),
								'footer_content' => array(
									'label' => esc_html__('Footer content', 'workreap'),
									'type' => 'wp-editor',
									'value' => '',
									'desc' => esc_html__('Add page URL, leave it empty to hide.', 'workreap')
								),
								'socials' => array(
                                    'label' => esc_html__('Social Profiles', 'workreap'),
                                    'type' => 'addable-popup',
                                    'value' => array(),
                                    'desc' => esc_html__('Add Social Icons as much as you want. Choose the icon, url and the title', 'workreap'),
                                    'popup-options' => array(
                                        'social_name' => array(
                                            'label' => esc_html__('Title', 'workreap'),
                                            'type' => 'text',
                                            'value' => 'Name',
                                            'desc' => esc_html__('The Title of the Link', 'workreap')
                                        ),
                                        'social_icons_list' => array(
                                            'type' => 'new-icon',
                                            'value' => 'fa-smile-o',
                                            'attr' => array(),
                                            'label' => esc_html__('Choose Icon', 'workreap'),
                                            'desc' => esc_html__('', 'workreap'),
                                            'help' => esc_html__('', 'workreap'),
                                        ),
                                        'social_url' => array(
                                            'label' => esc_html__('Url', 'workreap'),
                                            'type' => 'text',
                                            'value' => '#',
                                            'desc' => esc_html__('The link to the social profile.', 'workreap')
                                        ),
                                    ),
                                    'template' => '{{- social_name }}',
                                ),
                            ),
                            'footer_v3' => array(
								'menu' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Footer Menu?', 'workreap'),
									'desc' => esc_html__('Enable footer menu', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
								'copyright' => array(
									'type' => 'textarea',
									'value' => 'Copyright &copy; 2019 The Workreap, All Rights Reserved amentotech',
									'label' => esc_html__('Footer Copyright', 'workreap'),
								),
                                'join' => array(
                                    'type' => 'multi-picker',
                                    'label' => false,
                                    'desc' => '',
                                    'picker' => array(
                                        'gadget' => array(
                                            'type' => 'switch',
                                            'value' => 'disable',
                                            'attr' => array(),
                                            'label' => esc_html__('Join section?', 'workreap'),
                                            'desc' => esc_html__('Enable join now section', 'workreap'),
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
                                ),  
                                'newsletter_img' => array(
									'type' => 'upload',
									'label' => esc_html__('News Letter Image?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('News letter image for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
								),
								'contact-section' => array(
									'type' => 'html',
									'html' => esc_html__('About section','workreap'),
									'label' => esc_html__('', 'workreap'),
									'desc' => esc_html__('Add about information in footer', 'workreap'),
									'help' => esc_html__('', 'workreap'),
									'images_only' => true,
								),
								'footer_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Logo?', 'workreap'),
									'hint' => esc_html__('', 'workreap'),
									'desc' => esc_html__('logo for footer. Leave it empty to hide', 'workreap'),
									'images_only' => true,
                                ),
                                'primary_color' => array(
                                    'type' => 'rgba-color-picker',
                                    'value' => 'rgba(147,76,255,1)',
                                    'attr' => array(),
                                    'label' => esc_html__('Primary Color', 'workreap'),
                                    'desc' => esc_html__('Add footer primary color.', 'workreap'),
                                ),
                                'secondary_color' => array(
                                    'type' => 'rgba-color-picker',
                                    'value' => 'rgba(246,43,132,1)',
                                    'attr' => array(),
                                    'label' => esc_html__('Secondary Color', 'workreap'),
                                    'desc' => esc_html__('Add footer secondary color.', 'workreap'),
                                ),
								'footer_content' => array(
									'label' => esc_html__('Footer content', 'workreap'),
									'type' => 'wp-editor',
									'value' => '',
									'desc' => esc_html__('Add page URL, leave it empty to hide.', 'workreap')
								),
								'socials' => array(
                                    'label' => esc_html__('Social Profiles', 'workreap'),
                                    'type' => 'addable-popup',
                                    'value' => array(),
                                    'desc' => esc_html__('Add Social Icons as much as you want. Choose the icon, url and the title', 'workreap'),
                                    'popup-options' => array(
                                        'social_name' => array(
                                            'label' => esc_html__('Title', 'workreap'),
                                            'type' => 'text',
                                            'value' => 'Name',
                                            'desc' => esc_html__('The Title of the Link', 'workreap')
                                        ),
                                        'social_icons_list' => array(
                                            'type' => 'new-icon',
                                            'value' => 'fa-smile-o',
                                            'attr' => array(),
                                            'label' => esc_html__('Choose Icon', 'workreap'),
                                            'desc' => esc_html__('', 'workreap'),
                                            'help' => esc_html__('', 'workreap'),
                                        ),
                                        'social_url' => array(
                                            'label' => esc_html__('Url', 'workreap'),
                                            'type' => 'text',
                                            'value' => '#',
                                            'desc' => esc_html__('The link to the social profile.', 'workreap')
                                        ),
                                    ),
                                    'template' => '{{- social_name }}',
                                ),
                            ),
                        ),
                        'show_borders' => true,
                    ),
                ),
            ),
        )
    )
);
