<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'woocommerce_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Woocommerce Settings', 'workreap'),
        'options' => array(
			'detail_sidebar' => array(
                'title' => esc_html__('General Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'enable_sidebar_detail' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar?', 'workreap'),
                        'desc' => esc_html__('Detail page sidebar ON/OFF', 'workreap'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'workreap'),
                        ),
                    ),
					'detail_sidebar_position' => array(
                        'type' => 'select',
                        'value' => 'left',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar Position', 'workreap'),
                        'desc' => esc_html__('Set sidebar position at detail page.', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'choices' => array(
                            'left' => esc_html__('Left', 'workreap'),
                            'right' => esc_html__('Right', 'workreap'),
                        ),
                    ),
                )
            ),
            'shop_settings' => array(
                'title' => esc_html__('Shop Page Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'product_subtitle' => array(
                        'type'  => 'text',
						'label' => esc_html__('Title', 'workreap'),
						'value' =>'',
                        'desc' => esc_html__('Leave it empty to hide.', 'workreap'),
                    ),
					'product_description' => array(
                        'type'  => 'textarea',
						'label' => esc_html__('Description', 'workreap'),
						'value' =>'',
                        'desc' => esc_html__('Leave it empty to hide.', 'workreap'),
                    ),
					'enable_sidebar' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar?', 'workreap'),
                        'desc' => esc_html__('Shop page sidebar ON/OFF', 'workreap'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'workreap'),
                        ),
                    ),
                    'sidebar_position' => array(
                        'type' => 'select',
                        'value' => 'left',
                        'attr' => array('class' => 'custom-class', 'data-foo' => 'bar'),
                        'label' => esc_html__('Sidebar Position', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'choices' => array(
                            'left' => esc_html__('Left', 'workreap'),
                            'right' => esc_html__('Right', 'workreap'),
                        ),
                    ),
                )
            ),
            'archive_settings' => array(
                'title' => esc_html__('Archive Page Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
                    'archive_enable_sidebar' => array(
                        'type' => 'switch',
                        'value' => 'on',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar?', 'workreap'),
                        'desc' => esc_html__('Archive page sidebar ON/OFF', 'workreap'),
                        'left-choice' => array(
                            'value' => 'off',
                            'label' => esc_html__('OFF', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'on',
                            'label' => esc_html__('ON', 'workreap'),
                        ),
                    ),
                    'archive_sidebar_position' => array(
                        'type' => 'select',
                        'value' => 'left',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar Position', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'choices' => array(
                            'left' => esc_html__('Left', 'workreap'),
                            'right' => esc_html__('Right', 'workreap'),
                        ),
                    ),
                )
            ),
        )
    )
);
