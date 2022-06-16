<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'sidebars' => array(
        'title' => esc_html__('Sidebar Settings', 'workreap'),
        'type' => 'tab',
        'options' => array(
            'pages-box' => array(
                'title' => esc_html__('Pages', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'default-sidebars' => array(
						'type' => 'html',
						'html' => esc_html__('Default sidebars','workreap'),
						'label' => esc_html__('', 'workreap'),
						'desc' => esc_html__('You can set global sidebar for all the pages from here. You can edit any page and set custom sidebar from the page settings', 'workreap'),
						'images_only' => true,
					),
                    'sd_layout_pages' => array(
						'label'   => esc_html__( 'Layout', 'workreap' ),
						'desc'    => esc_html__( 'Select sidebar position for pages.', 'workreap' ),
						'type'    => 'select',
						'value'   => 'full',
						'choices' => array(
							'left' 		=> esc_html__('Left sidebar', 'workreap'),	
							'right' 	=> esc_html__('Right sidebar', 'workreap'),	
							'full' 		=> esc_html__('Full width', 'workreap'),
						)
					),
					'sd_sidebar_pages' => array(
						'label'   => esc_html__( 'Sidebar', 'workreap' ),
						'desc'    => esc_html__( 'Select sidebar to display on the page detail.', 'workreap' ),
						'type'    => 'select',
						'value'   => 'full',
						'choices' => workreapGetRegisterSidebars()
					)
                )
            ),
			'posts-box' => array(
                'title' => esc_html__('Posts', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'default-sidebarsp' => array(
						'type' => 'html',
						'html' => esc_html__('Default sidebars', 'workreap'),
						'label' => esc_html__('', 'workreap'),
						'desc' => esc_html__('You can set global sidebar for all the post types from here. You can edit any post type and set custom sidebar from the post settings', 'workreap'),
						'images_only' => true,
					),
                    'sd_layout_posts' => array(
						'label'   => esc_html__( 'Layout', 'workreap' ),
						'desc'    => esc_html__( 'Select sidebar position for posts.', 'workreap' ),
						'type'    => 'select',
						'value'   => 'full',
						'choices' => array(
							'left' 		=> esc_html__('Left sidebar', 'workreap'),	
							'right' 	=> esc_html__('Right sidebar', 'workreap'),	
							'full' 		=> esc_html__('Full width', 'workreap'),
						)
					),
					'sd_sidebar_posts' => array(
						'label'   => esc_html__( 'Sidebar', 'workreap' ),
						'desc'    => esc_html__( 'Select sidebar to display on post detail page.', 'workreap' ),
						'type'    => 'select',
						'value'   => 'full',
						'choices' => workreapGetRegisterSidebars()
					)
                )
            ),
        )
    )
);
