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
		'title'   => esc_html__( 'Title bar Settings', 'workreap' ),
		'type'    => 'box',
		'options' => array(
			'titlebar_type' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'   => esc_html__( 'Title bar Type', 'workreap' ),
						'desc'   => esc_html__( 'Select title bar type', 'workreap' ),
						'type'    => 'select',
						'value'    => 'default',
						'choices' => array(
							'default' => esc_html__('Default', 'workreap'),	
							'custom' => esc_html__('Custom Setttings', 'workreap'),	
							'rev_slider' => esc_html__('Revolution Slider', 'workreap'),
							'custom_shortcode' => esc_html__('Custom Shortcode', 'workreap'),
							'none' => esc_html__('None, hide it', 'workreap'),	
							
						)
					)
				),
				'choices'      => array(
					'default'  => array(
					),
					'custom'  => array(
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
						'titlebar_bg_image' => array (
							'type'        => 'upload' ,
							'label'       => esc_html__('Background?' , 'workreap') ,
							'desc'        => esc_html__('Upload background image' , 'workreap') ,
							'images_only' => true ,
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
					'rev_slider'  => array(
						'rev_slider' => array(
							'type'  => 'select',
							'value' => '',
							'label' => esc_html__('Revolution Slider', 'workreap'),
							'desc'  => esc_html__('Please Select Revolution slider.', 'workreap'),
							'help' => esc_html__('Please install revolution slider first.', 'workreap'),
							'choices' => workreap_prepare_rev_slider(),
						),
					),
					'custom_shortcode'  => array(
						'custom_shortcode' => array(
							'type'  => 'textarea',
							'value' => '',
							'desc' => esc_html__('Custom Shortcode, You can add any shortcode here.', 'workreap'),
							'label'  => esc_html__('Custom Shortcode', 'workreap'),
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
);

