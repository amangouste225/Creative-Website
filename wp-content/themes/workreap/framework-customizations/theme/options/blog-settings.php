<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array (
    'blogs' => array (
        'title'   => esc_html__('Blog Settings' , 'workreap') ,
        'type'    => 'tab' ,
        'options' => array (
            'general-box' => array (
                'title'   => esc_html__('General Settings' , 'workreap') ,
                'type'    => 'tab' ,
                'options' => array (
					'blog_view' => array(
                        'type' => 'select',
                        'value' => 'list',
                        'attr' => array(),
                        'label' => esc_html__('Blog View', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'help' => esc_html__('', 'workreap'),
                        'choices' => array(
                            'list' => esc_html__('List', 'workreap'),
                            'grid' => esc_html__('Grid', 'workreap'),
                        ),
                    ),
					'enable_author'       => array (
						'type'         => 'switch' ,
						'value'        => 'enable' ,
						'label'        => esc_html__('Author information' , 'workreap') ,
						'desc'         => esc_html__('Enable or Disable author information at listing & detail page.' , 'workreap') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'workreap') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'workreap') ,
						) ,
					) ,
					'enable_comments'       => array (
						'type'         => 'switch' ,
						'value'        => 'enable' ,
						'label'        => esc_html__('Enable Comments' , 'workreap') ,
						'desc'         => esc_html__('Enable or Disable comments. It will be effect all over the site in blog detail/listings.' , 'workreap') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'workreap') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'workreap') ,
						) ,
					) ,
					'enable_categories'       => array (
						'type'         => 'switch' ,
						'value'        => 'enable' ,
						'label'        => esc_html__('Enable Categories/Tags' , 'workreap') ,
						'desc'         => esc_html__('Enable or Disable Categories/Tags. It will be effect all over the site in blog detail/listings.' , 'workreap') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'workreap') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'workreap') ,
						) ,
					) ,
					'enable_sharing' => array(
						'type'         => 'switch' ,
						'value'        => 'disable' ,
						'label'        => esc_html__('Enable Sharing' , 'workreap') ,
						'desc'         => esc_html__('Enable or Disable social sharing at detail page.' , 'workreap') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'workreap') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'workreap') ,
						) ,
					)
                )
            ),
			'archive-box' => array (
                'title'   => esc_html__('Archive Pages Settings' , 'workreap') ,
                'type'    => 'tab' ,
                'options' => array (
					'archive_show_posts' => array(
						'type'  => 'slider',
						'value' => 10,
						'properties' => array(
							'min' => 1,
							'max' => 100,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
						'attr'  => array(),
						'label' => esc_html__('Show posts', 'workreap'),
						'desc'  => esc_html__('Show number of posts per page. It will be used for archive pages.', 'workreap'),
						'help'  => esc_html__('', 'workreap'),
					),
					'archive_order' => array (
                        'label'   => esc_html__('Post Order' , 'workreap') ,
                        'desc'    => esc_html__('It will be used for archive pages.' , 'workreap') ,
                        'type'    => 'select' ,
						'value' => 'DESC',
                        'choices' => array (
                            'DESC' => 'DESC' ,
                            'ASC' => 'ASC' ,
                        )
                    ),
					'archive_orderby' => array (
                        'label'   => esc_html__('Order by' , 'workreap') ,
                        'desc'    => esc_html__('It will be used for archive pages.' , 'workreap') ,
                        'type'    => 'select' ,
						'value' => 'ID',
                        'choices' => array (
                            'ID' => esc_html__('Order by post id', 'workreap'),
							'author' => esc_html__('Order by author', 'workreap'),
							'title' => esc_html__('Order by title', 'workreap'),
							'name' => esc_html__('Order by post name', 'workreap') ,
							'date' => esc_html__('Order by date', 'workreap'),
							'modified' => esc_html__('Order by last modified date', 'workreap') ,
							'rand' => esc_html__('Random order', 'workreap'),
                        )
                    ),
					'archive_pages_sidebar' => array(
                        'type' => 'switch',
                        'value' => 'enable',
                        'attr' => array(),
                        'label' => esc_html__('Sidebar ON/OFF', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'left-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                    ),
                    'archive_pages_position' => array(
                        'type' => 'select',
                        'value' => 'right',
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
			'search-box' => array (
                'title'   => esc_html__('Search Pages Settings' , 'workreap') ,
                'type'    => 'tab' ,
                'options' => array (
					'search_show_posts' => array(
						'type'  => 'slider',
						'value' => 10,
						'properties' => array(
							'min' => 1,
							'max' => 100,
							'step' => 1, // Set slider step. Always > 0. Could be fractional.
						),
						'attr'  => array(),
						'label' => esc_html__('Show posts', 'workreap'),
						'desc'  => esc_html__('Show number of posts per page. It will be used for search pages.', 'workreap'),
						'help'  => esc_html__('', 'workreap'),
					),
					'search_order' => array (
                        'label'   => esc_html__('Post Order' , 'workreap') ,
                        'desc'    => esc_html__('It will be used for search pages.' , 'workreap') ,
                        'type'    => 'select' ,
						'value' => 'DESC',
                        'choices' => array (
                            'DESC' => 'DESC' ,
                            'ASC' => 'ASC' ,
                        )
                    ),
					'search_orderby' => array (
                        'label'   => esc_html__('Order by' , 'workreap') ,
                        'desc'    => esc_html__('It will be used for search pages.' , 'workreap') ,
                        'type'    => 'select' ,
						'value' => 'ID',
                        'choices' => array (
                            'ID' => esc_html__('Order by post id', 'workreap'),
							'author' => esc_html__('Order by author', 'workreap'),
							'title' => esc_html__('Order by title', 'workreap'),
							'name' => esc_html__('Order by post name', 'workreap') ,
							'date' => esc_html__('Order by date', 'workreap'),
							'modified' => esc_html__('Order by last modified date', 'workreap') ,
							'rand' => esc_html__('Random order', 'workreap'),
                        )
                    ),
					'search_enable_sidebar' => array(
                        'type' => 'switch',
                        'value' => 'enable',
                        'attr' => array(),
                        'label' => esc_html__('Page Sidebar ON/OFF', 'workreap'),
                        'desc' => esc_html__('', 'workreap'),
                        'left-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                        'right-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                    ),
                    'search_sidebar_position' => array(
                        'type' => 'select',
                        'value' => 'right',
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
            )
        )
    )
);
