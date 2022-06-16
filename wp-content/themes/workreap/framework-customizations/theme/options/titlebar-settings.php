<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'titlebars' => array(
        'title' => esc_html__('Title Bar Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'general-box' => array(
                'title' => esc_html__('Title Bar Settings', 'workreap'),
                'type' => 'box',
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
									'none' => esc_html__('None, hide it', 'workreap'),	

								)
							)
						),
						'choices'      => array(
							'default'  => array(
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
								) ,
								'titlebar_overlay' => array(
									'type'  => 'rgba-color-picker',
									'value' => 'rgba(0,0,0,0)',
									'attr'  => array(),
									'label' => esc_html__('Titlebar overlay', 'workreap'),
									'desc'  => esc_html__('Add titlebar banner overlay color', 'workreap'),
									'help'  => esc_html__('', 'workreap'),
								),
								'titlebar_overlay_secondary' => array(
									'type'  => 'rgba-color-picker',
									'value' => 'rgba(0,0,0,0)',
									'attr'  => array(),
									'label' => esc_html__('Titlebar overlay secondary', 'workreap'),
									'desc'  => esc_html__('Add titlebar banner overlay secondary color', 'workreap'),
									'help'  => esc_html__('', 'workreap'),
								),
							),
						)
					),
                )
            ),
        )
    )
);
