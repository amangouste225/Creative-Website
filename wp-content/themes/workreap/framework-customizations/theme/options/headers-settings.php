<?php

if (!defined('FW')) {
    die('Forbidden');
}

$list_names	= array();
if( function_exists('worktic_get_search_list')){
	$list_names	= worktic_get_search_list('yes');
}

$options = array(
    'headers' => array(
        'title' => esc_html__('Header Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'general-box' => array(
                'title' => esc_html__('Header Settings', 'workreap'),
                'type' => 'box',
                'options' => array(
                    'main_logo' => array(
                        'type' => 'upload',
                        'label' => esc_html__('Upload Logo', 'workreap'),
                        'desc' => esc_html__('Upload your site logo here, Preferred size is 105 by 25.', 'workreap'),
                        'images_only' => true,
                    ),
					'logo_x' => array(
						'type' => 'slider',
						'value' => 0,
						'properties' => array(
							'min' => 0,
							'max' => 500,
							'sep' => 5,
						),
						'label' => esc_html__('Logo width', 'workreap'),
						'desc' => esc_html__('Please select logo width, leave it empty to use default', 'workreap'),
					),
					'logo_y' => array(
						'type' => 'slider',
						'value' => 0,
						'properties' => array(
							'min' => 0,
							'max' => 500,
							'sep' => 5,
						),
						'label' => esc_html__('Logo height', 'workreap'),
						'desc' => esc_html__('Please select logo height, leave it empty to use default', 'workreap'),
					),
                    'header_type' => array(
                        'type' => 'multi-picker',
                        'label' => false,
                        'desc' => false,
                        'value' => array('gadget' => 'header_v1'),
                        'picker' => array(
                            'gadget' => array(
                                'label' => esc_html__('Header Types', 'workreap'),
                                'type' => 'image-picker',
                                'choices' => array(
                                    'header_v1' => array(
                                        'label' => __('Header V1', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_1.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_1.jpg'
                                        ),
                                    ),
                                    'header_v2' => array(
                                        'label' => __('Header V1', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_2.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_2.jpg'
                                        ),
                                    ),
									'header_v3' => array(
                                        'label' => __('Header V3', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_3.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_3.jpg'
                                        ),
                                    ),
									'header_v4' => array(
                                        'label' => __('Header V4', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_4.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_4.jpg'
                                        ),
                                    ),
									'header_v5' => array(
                                        'label' => __('Header V5', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_5.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_5.jpg'
                                        ),
                                    ),
									'header_v6' => array(
                                        'label' => __('Header V6', 'workreap'),
                                        'small' => array(
                                            'height' => 70,
                                            'src' => get_template_directory_uri() . '/images/headers/h_6.jpg'
                                        ),
                                        'large' => array(
                                            'height' => 214,
                                            'src' => get_template_directory_uri() . '/images/headers/h_6.jpg'
                                        ),
                                    ),
                                ),
                                'desc' => esc_html__('Select header type.', 'workreap'),
                            )
                        ),
                        'choices' => array(
                            'header_v1' => array(
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_on_home' => esc_html__('Hide on home page and show all over the site', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
                            ),  
							'header_v2' => array(
								'main_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Transparent logo', 'workreap'),
									'desc' => esc_html__('Leave it empty to use default logo', 'workreap'),
									'images_only' => true,
								),
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_on_home' => esc_html__('Hide on home page and show all over the site', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
                            ),
							'header_v3' => array(
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_on_home' => esc_html__('Hide on home page and show all over the site', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
                            ),
							'header_v4' => array(
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_on_home' => esc_html__('Hide on home page and show all over the site', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
							),
							'header_v5' => array(
								'main_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Transparent logo', 'workreap'),
									'desc' => esc_html__('Leave it empty to use default logo', 'workreap'),
									'images_only' => true,
								),
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_on_home' => esc_html__('Hide on home page and show all over the site', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'show_categories' => array(
									'type' => 'switch',
									'value' => 'yes',
									'attr' => array(),
									'label' => esc_html__('Categories menu', 'workreap'),
									'desc'  => esc_html__('Show categories menu', 'workreap'),
									'left-choice' => array(
										'value' => 'no',
										'label' => esc_html__('No', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'yes',
										'label' => esc_html__('Yes', 'workreap'),
									),
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
                            ),
							'header_v6' => array(
								'main_logo' => array(
									'type' => 'upload',
									'label' => esc_html__('Transparent logo', 'workreap'),
									'desc' => esc_html__('Leave it empty to use default logo', 'workreap'),
									'images_only' => true,
								),
								'search_form' => array(
									'label' => esc_html__('Search Form?', 'workreap'),
									'type' => 'select',
									'value' => 'hide_all',
									'desc' => esc_html__('Enable search form? ', 'workreap'),
									'choices' => array(
										'show_all' => esc_html__('Show From', 'workreap'),
										'hide_all' => esc_html__('Hide all over the site', 'workreap'),
									)
								),
								'search_options' => array(
									'type'  => 'checkboxes',
									'value' => array(
										'job' 			=> true,
										'freelancer' 	=> true,
									),
									'label' => esc_html__('Search options', 'workreap'),
									'desc'  => esc_html__('Atleast one item should be selected.', 'workreap'),
									'choices' => $list_names,
									'inline' => false,
								),
								'sticky' => array(
									'type' => 'switch',
									'value' => 'disable',
									'attr' => array(),
									'label' => esc_html__('Sticky header', 'workreap'),
									'desc'  => esc_html__('Enable sticky header?', 'workreap'),
									'left-choice' => array(
										'value' => 'disable',
										'label' => esc_html__('Disable', 'workreap'),
									),
									'right-choice' => array(
										'value' => 'enable',
										'label' => esc_html__('Enable', 'workreap'),
									),
								),
                            ),
                        ),
                        'show_borders' => true,
                    ),
                )
            ),
        )
    )
);
