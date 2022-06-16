<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
	'sidebar_settings' => array(
        'title' => esc_html__('Page sidebar', 'workreap'),
        'type' => 'box',

        'options' => array(
            'sd_layout' => array(
				'label'   => esc_html__( 'Layout', 'workreap' ),
				'desc'    => esc_html__( 'Select sidebar position for this page.', 'workreap' ),
				'type'    => 'select',
				'value'   => 'default',
				'choices' => array(
					'left' 		=> esc_html__('Left sidebar', 'workreap'),	
					'right' 	=> esc_html__('Right sidebar', 'workreap'),	
					'full' 		=> esc_html__('Full width', 'workreap'),
					'default' 		=> esc_html__('Default settings', 'workreap'),
				)
			),
			'sd_sidebar' => array(
				'label'   => esc_html__( 'Sidebar', 'workreap' ),
				'desc'    => esc_html__( 'Select sidebar to display on this page.', 'workreap' ),
				'type'    => 'select',
				'value'   => '',
				'choices' => workreapGetRegisterSidebars()
			)
        ),
    ),
    'page_settings' => array(
        'title' => esc_html__('Title bar Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
            'titlebar_type' => array(
                'type' => 'multi-picker',
                'label' => false,
                'desc' => false,
                'picker' => array(
                    'gadget' => array(
                        'label' => esc_html__('Title bar Type', 'workreap'),
                        'desc' => esc_html__('Select title bar type', 'workreap'),
                        'type' => 'select',
                        'value' => 'default',
                        'choices' => array(
                            'default' => esc_html__('Default', 'workreap'),
                            'custom' => esc_html__('Custom Setttings', 'workreap'),
                            'rev_slider' => esc_html__('Revolution Slider', 'workreap'),
                            'custom_shortcode' => esc_html__('Custom Shortcode', 'workreap'),
                            'none' => esc_html__('None, hide it', 'workreap'),
                        )
                    )
                ),
                'choices' => array(
                    'default' => array(
                    ),
                    'custom' => array(
                        'enable_breadcrumbs' => array(
                            'type' => 'switch',
                            'value' => 'disable',
                            'label' => esc_html__('Breadcrumbs', 'workreap'),
                            'desc' => esc_html__('Enable or Disable breadcrumbs. Please note global settings(From Theme Settings) should be enabled', 'workreap'),
                            'left-choice' => array(
                                'value' => 'enable',
                                'label' => esc_html__('Enable', 'workreap'),
                            ),
                            'right-choice' => array(
                                'value' => 'disable',
                                'label' => esc_html__('Disable', 'workreap'),
                            ),
                        ),
                        'titlebar_bg_image' => array(
                            'type' => 'upload',
                            'label' => esc_html__('Background?', 'workreap'),
                            'desc' => esc_html__('Upload background image', 'workreap'),
                            'images_only' => true,
                        ),
                        'titlebar_bg' => array(
                            'type' => 'rgba-color-picker',
                            'value' => 'rgba(54, 59, 77, 0.40)',
                            'label' => esc_html__('Background color', 'workreap'),
                            'desc' => esc_html__('RGBA color will be over image and solid color will override image', 'workreap'),
                        ),
						'titlebar_overlay' => array(
							'type'  => 'rgba-color-picker',
							'value' => 'rgba(0,0,0,0)',
							'attr'  => array(),
							'label' => esc_html__('Titlebar overlay', 'workreap'),
							'desc'  => esc_html__('Add titlebar banner overlay color', 'workreap'),
							'help'  => esc_html__('', 'workreap'),
						),
                    ),
                    'rev_slider' => array(
                        'rev_slider' => array(
                            'type' => 'select',
                            'value' => '',
                            'label' => esc_html__('Revolution Slider', 'workreap'),
                            'desc' => esc_html__('Please Select Revolution slider.', 'workreap'),
                            'help' => esc_html__('Please install revolution slider first.', 'workreap'),
                            'choices' => workreap_prepare_rev_slider(),
                        ),
                    ),
                    'custom_shortcode' => array(
                        'custom_shortcode' => array(
                            'type' => 'textarea',
                            'value' => '',
                            'desc' => esc_html__('Custom Shortcode, You can add any shortcode here.', 'workreap'),
                            'label' => esc_html__('Custom Shortcode', 'workreap'),
                        ),
                    ),
                )
            ),
			'titlebar_title' => array(
				'type' => 'text',
				'value' => '',
				'label' => esc_html__('Custom Title', 'workreap'),
				'desc' => esc_html__('Leave it empty to use default title', 'workreap'),
			),
        )
    ),
    'post_settings' => array(
        'title' => esc_html__('Post Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
            'enable_author' => array(
                'type' => 'switch',
                'value' => 'enable',
                'label' => esc_html__('Author information', 'workreap'),
                'desc' => esc_html__('Enable or Disable author information at listing & detail page.', 'workreap'),
                'left-choice' => array(
                    'value' => 'enable',
                    'label' => esc_html__('Enable', 'workreap'),
                ),
                'right-choice' => array(
                    'value' => 'disable',
                    'label' => esc_html__('Disable', 'workreap'),
                ),
            ),
            'enable_comments' => array(
                'type' => 'switch',
                'value' => 'enable',
                'label' => esc_html__('Enable Comments', 'workreap'),
                'desc' => esc_html__('Enable or Disable comments. It will be effect all over the site in blog detail/listings.', 'workreap'),
                'left-choice' => array(
                    'value' => 'enable',
                    'label' => esc_html__('Enable', 'workreap'),
                ),
                'right-choice' => array(
                    'value' => 'disable',
                    'label' => esc_html__('Disable', 'workreap'),
                ),
            ),
            'enable_categories' => array(
                'type' => 'switch',
                'value' => 'enable',
                'label' => esc_html__('Enable Categories', 'workreap'),
                'desc' => esc_html__('Enable or Disable Categories. It will be effect all over the site in blog detail/listings.', 'workreap'),
                'left-choice' => array(
                    'value' => 'enable',
                    'label' => esc_html__('Enable', 'workreap'),
                ),
                'right-choice' => array(
                    'value' => 'disable',
                    'label' => esc_html__('Disable', 'workreap'),
                ),
            ),
            'enable_sharing' => array(
                'type' => 'multi-picker',
                'label' => false,
                'desc' => false,
                'picker' => array(
                    'gadget' => array(
                        'type' => 'switch',
                        'value' => 'disable',
                        'label' => esc_html__('Enable Sharing', 'workreap'),
                        'desc' => esc_html__('Enable or Disable social sharing at detail page.', 'workreap'),
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
                        'share_title' => array(
                            'type' => 'text',
                            'value' => 'Share',
                            'label' => esc_html__('Share title', 'workreap'),
                            'desc' => esc_html__('Add share title here. Leave it empty to hide.', 'workreap'),
                        ),
                    ),
                )
            ),
            'post_settings' => array(
                'type' => 'multi-picker',
                'label' => false,
                'desc' => false,
                'picker' => array(
                    'gadget' => array(
                        'label' => esc_html__('Post Format', 'workreap'),
                        'desc' => esc_html__('Select Post Format', 'workreap'),
                        'type' => 'radio',
                        'value' => 'image',
                        'choices' => array(
                            'image' => esc_html__('Image', 'workreap'),
                            'gallery' => esc_html__('Image Slider', 'workreap'),
                            'video' => esc_html__('Audio/Video', 'workreap'),
                        ),
                        'inline' => true,
                    )
                ),
                'choices' => array(
                    'image' => array(
                        'blog_post_image' => array(
                            'type' => 'html',
                            'html' => 'Uplaod Image',
                            'label' => esc_html__('Detail Image', 'workreap'),
                            'desc' => esc_html__('Upload Your detail blog post image as a featured image. (Preferred Size is 1920x800)', 'workreap'),
                            'help' => esc_html__('Please upload your thumbnail image.', 'workreap'),
                            'images_only' => true,
                        ),
                    ),
                    'gallery' => array(
                        'blog_post_gallery' => array(
                            'type' => 'multi-upload',
                            'label' => esc_html__('Image Slider', 'workreap'),
                            'desc' => esc_html__('Add Images for slider. (Preferred Size is 1920x800)', 'workreap'),
                            'help' => esc_html__('Only worked if the post display setting is equal to Image Gallery.', 'workreap'),
                            'images_only' => true,
                        ),
                    ),
                    'video' => array(
                        'blog_video_link' => array(
                            'type' => 'text',
                            'label' => esc_html__('Media Link', 'workreap'),
                            'desc' => esc_html__('Add your custom Audio/Video Link', 'workreap'),
                        ),
                    ),
                )
            ),
        )
    ),
	
);

