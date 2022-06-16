<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array(
    'search-settings' => array(
		'title' => esc_html__('Search Settings', 'workreap'),
		'type' => 'tab',
		'options' => array(
			'map-box' => array(
				'title' => esc_html__('Map Settings.', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'map-group' => array(
						'type' => 'group',
						'options' => array(
							'dir_map_type' => array(
								'label' => esc_html__('Map Type', 'workreap'),
								'type' => 'select',
								'desc' => esc_html__('Select Map Type.', 'workreap'),
								'choices' => array(
									'ROADMAP' => 'ROADMAP',
									'SATELLITE' => 'SATELLITE',
									'HYBRID' => 'HYBRID',
									'TERRAIN' => 'TERRAIN',
								)
							),
							'map_styles' => array(
								'label' => esc_html__('Map Style', 'workreap'),
								'type' => 'select',
								'desc' => esc_html__('Select map style. It will override map type.', 'workreap'),
								'choices' => array(
									'none' => esc_html__('NONE', 'workreap'),
									'view_1' => esc_html__('Default', 'workreap'),
									'view_2' => esc_html__('View 2', 'workreap'),
									'view_3' => esc_html__('View 3', 'workreap'),
									'view_4' => esc_html__('View 4', 'workreap'),
									'view_5' => esc_html__('View 5', 'workreap'),
									'view_6' => esc_html__('View 6', 'workreap'),
								)
							),
							'dir_map_scroll' => array(
								'label' => esc_html__('Map Draggable', 'workreap'),
								'type' => 'select',
								'desc' => esc_html__('Enable map draggable', 'workreap'),
								'value' => 'false',
								'choices' => array(
									'false' => esc_html__('No', 'workreap'),
									'true' => esc_html__('Yes', 'workreap'),
								)
							),
							'dir_map_marker' => array(
								'type' => 'upload',
								'label' => esc_html__('Map Marker', 'workreap'),
								'hint' => esc_html__('', 'workreap'),
								'desc' => esc_html__('Default map marker. It will be used all over the site.', 'workreap'),
								'images_only' => true,
							),
							'dir_cluster_marker' => array(
								'type' => 'upload',
								'label' => esc_html__('Cluster Map Marker', 'workreap'),
								'hint' => esc_html__('', 'workreap'),
								'desc' => esc_html__('Default Cluster map marker.', 'workreap'),
								'images_only' => true,
							),
							'dir_cluster_color' => array(
								'type' => 'color-picker',
								'value' => '#505050',
								'attr' => array(),
								'label' => esc_html__('Map Cluster Color', 'workreap'),
								'desc' => esc_html__('Map cluster text color', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'dir_zoom' => array(
								'type' => 'slider',
								'value' => 11,
								'properties' => array(
									'min' => 1,
									'max' => 20,
									'step' => 1, // Set slider step. Always > 0. Could be fractional.
								),
								'label' => esc_html__('Map Zoom', 'workreap'),
								'hint' => esc_html__('', 'workreap'),
								'desc' => esc_html__('Select map zoom level', 'workreap'),
								'images_only' => true,
							),

							'dir_latitude' => array(
								'type' => 'text',
								'value' => '51.5001524',
								'attr' => array(),
								'label' => esc_html__('Latitude', 'workreap'),
								'desc' => esc_html__('Default Latitude for map.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'dir_longitude' => array(
								'type' => 'text',
								'value' => '-0.1262362',
								'attr' => array(),
								'label' => esc_html__('Longitude', 'workreap'),
								'desc' => esc_html__('Default longitude for map.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
						)
					),
				)
			),
			'job-box' => array(
				'title' => esc_html__('Geo Search', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'map-group' => array(
						'type' => 'group',
						'options' => array(
							'search_page_map' => array(
								'type' => 'switch',
								'value' => 'disable',
								'label' => esc_html__('Search result map', 'workreap'),
								'desc' => esc_html__('Enable/Disble google map at search page.', 'workreap'),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
							),
							'dir_location' => array(
								'type' => 'switch',
								'value' => 'disable',
								'label' => esc_html__('Geo location autocomplete', 'workreap'),
								'desc' => esc_html__('Enable geo location autocomplete field', 'workreap'),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
							),
							'dir_radius' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Radius Search', 'workreap'),
								'desc' => esc_html__('Enable Radius Search, Note it will be display when geo location will be enable.', 'workreap'),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
							),
							'dir_default_radius' => array(
								'type' => 'slider',
								'attr' => array(),
								'label' => esc_html__('Default radius', 'workreap'),
								'desc' => esc_html__('Please select default radius for radius slider.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'value' => 50,
								'properties' => array(
									'min' => 1,
									'max' => 1000,
									'step' => 2, // Set slider step. Always > 0. Could be fractional.
								),
							),
							'dir_max_radius' => array(
								'type' => 'slider',
								'attr' => array(),
								'label' => esc_html__('Maximum radius', 'workreap'),
								'desc' => esc_html__('Please select maximum radius for radius slider.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'value' => 300,
								'properties' => array(
									'min' => 1,
									'max' => 1000,
									'step' => 5, // Set slider step. Always > 0. Could be fractional.
								),
							),
							'dir_distance_type' => array(
								'type' => 'select',
								'value' => 'list',
								'attr' => array(),
								'label' => esc_html__('Distance in?', 'workreap'),
								'desc' => esc_html__('Search location radius in miles or kilometers.', 'workreap'),
								'help' => esc_html__('', 'workreap'),
								'choices' => array(
									'mi' => esc_html__('Miles', 'workreap'),
									'km' => esc_html__('Kilometers', 'workreap'),
								),
							),
							'country_restrict' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'type' => 'switch',
										'value' => 'disable',
										'label' => esc_html__('Restrict Country', 'workreap'),
										'desc' => esc_html__('Restrict Country in geo location auto complete field.', 'workreap'),
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
										'country_code' => array(
											'type' => 'text',
											'value' => 'us',
											'label' => esc_html__('Country Code', 'workreap'),
											'desc' => wp_kses(__('Add your 2 digit country code eg : us, to check country code please visit link <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank"> Get Code </a>', 'workreap'), array(
												'a' => array(
													'href' => array(),
													'title' => array()
												),
												'br' => array(),
												'em' => array(),
												'strong' => array(),
											)),
										),
									)
								),
								'show_borders' => true,
							),
						)
					),
				)
			),
			'filter-box' => array(
				'title' => esc_html__('Search Filters', 'workreap'),
				'type' => 'tab',
				'options' => array(
					'map-group' => array(
						'type' => 'group',
						'options' => array(
							'cus_filter_type' => array(
								'type' => 'html',
								'html' => esc_html__('Filters for freelancers', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('You can enable or disable available filters', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'freelancers_search_restrict' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'type' => 'select',
										'value' => 'disable',
										'label' => esc_html__('Hide search result', 'workreap'),
										'desc' => esc_html__('Hide search result for visitors, and show button to get register', 'workreap'),
										'choices' => array(
											'enable'      => esc_html__('No', 'workreap'),
											'disable'     => esc_html__('Yes', 'workreap'),
										),
									)
								),
								'choices' => array(
									'disable' => array(
										'search_numbers' => array(
											'type' => 'text',
											'value' => '3',
											'label' => esc_html__('No of freelancers', 'workreap'),
											'desc' => esc_html__('Add no of freelancers that show without login', 'workreap'),
										),
										'search_logo' => array(
											'type' => 'upload',
											'label' => esc_html__('Upload Logo', 'workreap'),
											'desc' => esc_html__('Upload form logo', 'workreap'),
											'images_only' => true,
										),
										'search_title' => array(
											'type' => 'text',
											'value' => esc_html__('Join now for fun!','workreap'),
											'label' => esc_html__('Title', 'workreap'),
											'desc' => esc_html__('Add Title for box', 'workreap'),
										),
										'search_details' => array(
											'type' => 'textarea',
											'value' => esc_html__('','workreap'),
											'label' => esc_html__('Description', 'workreap'),
											'desc' => esc_html__('Add description for box', 'workreap'),
										),
										'search_signup_btn_title' => array(
											'type' => 'text',
											'value' => esc_html__('Sign up','workreap'),
											'label' => esc_html__('Signup button title', 'workreap'),
											'desc' => esc_html__('Add signup button title for box', 'workreap'),
										),
									)
								),
								'show_borders' => true,
							),
							'freelancer_avatar_search' => array(
								'type' => 'switch',
								'value' => 'disable',
								'label' => esc_html__('Search by avatar', 'workreap'),
								'desc' => esc_html__('', 'workreap'),
								'desc' => wp_kses( __( 'Enable the search by avatar, if enabled then only those profiles would display who will have avatar <a href="#" class="wt-update-freelancers-meta">Click here to update freelancers meta for previous users</a>', 'workreap' ), array(
									'a' => array(
										'href' => array(),
										'class' => array(),
										'title' => array()
									),
									'br' => array(),
									'em' => array(),
									'strong' => array(),
								) ),
								'left-choice' => array(
									'value' => 'enable',
									'label' => esc_html__('Enable', 'workreap'),
								),
								'right-choice' => array(
									'value' => 'disable',
									'label' => esc_html__('Disable', 'workreap'),
								),
							),
							'freelancer_locations' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Location filter?', 'workreap'),
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
							'freelancer_skills' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Skills filter?', 'workreap'),
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
							'freelancer_rate' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Hourly rate filter?', 'workreap'),
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
							'freelancer_type' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Freelancer type filter?', 'workreap'),
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
							'freelancer_english' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('English level filter?', 'workreap'),
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
							'freelancer_industrial_exprience' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Industrial experience filter?', 'workreap'),
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
							'freelancer_specializations' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Specialization filter?', 'workreap'),
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
							'freelancer_languages' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Languages filter?', 'workreap'),
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
							'cus_filter_type_job' => array(
								'type' => 'html',
								'html' => esc_html__('Filters for Jobs', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('You can enable or disable available filters', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'project_search_restrict' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'type' => 'select',
										'value' => 'disable',
										'label' => esc_html__('Hide search result', 'workreap'),
										'desc' => esc_html__('Hide search result for visitors, and show button to get register', 'workreap'),
										'choices' => array(
											'enable'      => esc_html__('No', 'workreap'),
											'disable'     => esc_html__('Yes', 'workreap'),
										),
									)
								),
								'choices' => array(
									'disable' => array(
										'search_numbers' => array(
											'type' => 'text',
											'value' => '3',
											'label' => esc_html__('No of projects', 'workreap'),
											'desc' => esc_html__('Add no of projects that show without login', 'workreap'),
										),
										'search_logo' => array(
											'type' => 'upload',
											'label' => esc_html__('Upload Logo', 'workreap'),
											'desc' => esc_html__('Upload form logo', 'workreap'),
											'images_only' => true,
										),
										'search_title' => array(
											'type' => 'text',
											'value' => esc_html__('Join now for fun!','workreap'),
											'label' => esc_html__('Title', 'workreap'),
											'desc' => esc_html__('Add Title for box', 'workreap'),
										),
										'search_details' => array(
											'type' => 'textarea',
											'value' => esc_html__('','workreap'),
											'label' => esc_html__('Description', 'workreap'),
											'desc' => esc_html__('Add description for box', 'workreap'),
										),
										'search_signup_btn_title' => array(
											'type' => 'text',
											'value' => esc_html__('Sign up','workreap'),
											'label' => esc_html__('Signup button title', 'workreap'),
											'desc' => esc_html__('Add signup button title for box', 'workreap'),
										),
									)
								),
								'show_borders' => true,
							),
							'job_type' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Project type filter?', 'workreap'),
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
							'job_categories' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Project categories filter?', 'workreap'),
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
							'job_locations' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Location filter?', 'workreap'),
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
							'job_skills' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Skills filter?', 'workreap'),
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
							'job_length' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Project Length filter?', 'workreap'),
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
							'job_freelancer_type' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Freelancer type filter?', 'workreap'),
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
							'job_option_type' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Job location type filter?', 'workreap'),
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
							'job_english_level' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Job english level?', 'workreap'),
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
							'job_exprience_type' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Experience type filter?', 'workreap'),
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
							'job_languages' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Languages filter?', 'workreap'),
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
							'cus_filter_type_employers' => array(
								'type' => 'html',
								'html' => esc_html__('Filters for Employers', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('You can enable or disable available filters', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'employers_search_restrict' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'type' => 'select',
										'value' => 'disable',
										'label' => esc_html__('Hide search result', 'workreap'),
										'desc' => esc_html__('Hide search result for visitors, and show button to get register', 'workreap'),
										'choices' => array(
											'enable'      => esc_html__('No', 'workreap'),
											'disable'     => esc_html__('Yes', 'workreap'),
										),
									)
								),
								'choices' => array(
									'disable' => array(
										'search_numbers' => array(
											'type' => 'text',
											'value' => '4',
											'label' => esc_html__('No of employers', 'workreap'),
											'desc' => esc_html__('Add no of employers that show without login', 'workreap'),
										),
										'search_logo' => array(
											'type' => 'upload',
											'label' => esc_html__('Upload Logo', 'workreap'),
											'desc' => esc_html__('Upload form logo', 'workreap'),
											'images_only' => true,
										),
										'search_title' => array(
											'type' => 'text',
											'value' => esc_html__('Join now for fun!','workreap'),
											'label' => esc_html__('Title', 'workreap'),
											'desc' => esc_html__('Add Title for box', 'workreap'),
										),
										'search_details' => array(
											'type' => 'textarea',
											'value' => esc_html__('','workreap'),
											'label' => esc_html__('Description', 'workreap'),
											'desc' => esc_html__('Add description for box', 'workreap'),
										),
										'search_signup_btn_title' => array(
											'type' => 'text',
											'value' => esc_html__('Sign up','workreap'),
											'label' => esc_html__('Signup button title', 'workreap'),
											'desc' => esc_html__('Add signup button title for box', 'workreap'),
										),
									)
								),
								'show_borders' => true,
							),
							'employer_department' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Department filter?', 'workreap'),
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
							'employer_employees' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Employees filter?', 'workreap'),
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
							'employer_locations' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Locations filter?', 'workreap'),
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
	
							'cus_filter_type_services' => array(
								'type' => 'html',
								'html' => esc_html__('Filters for Services', 'workreap'),
								'label' => esc_html__('', 'workreap'),
								'desc' => esc_html__('You can enable or disable available filters', 'workreap'),
								'help' => esc_html__('', 'workreap'),
							),
							'services_search_restrict' => array(
								'type' => 'multi-picker',
								'label' => false,
								'desc' => false,
								'picker' => array(
									'gadget' => array(
										'type' => 'select',
										'value' => 'disable',
										'label' => esc_html__('Hide search result', 'workreap'),
										'desc' => esc_html__('Hide search result for visitors, and show button to get register', 'workreap'),
										'choices' => array(
											'enable'      => esc_html__('No', 'workreap'),
											'disable'     => esc_html__('Yes', 'workreap'),
										),
									)
								),
								'choices' => array(
									'disable' => array(
										'search_numbers' => array(
											'type' => 'text',
											'value' => '3',
											'label' => esc_html__('No of services', 'workreap'),
											'desc' => esc_html__('Add no of services that show without login', 'workreap'),
										),
										'search_logo' => array(
											'type' => 'upload',
											'label' => esc_html__('Upload Logo', 'workreap'),
											'desc' => esc_html__('Upload form logo', 'workreap'),
											'images_only' => true,
										),
										'search_title' => array(
											'type' => 'text',
											'value' => esc_html__('Join now for fun!','workreap'),
											'label' => esc_html__('Title', 'workreap'),
											'desc' => esc_html__('Add Title for box', 'workreap'),
										),
										'search_details' => array(
											'type' => 'textarea',
											'value' => esc_html__('','workreap'),
											'label' => esc_html__('Description', 'workreap'),
											'desc' => esc_html__('Add description for box', 'workreap'),
										),
										'search_signup_btn_title' => array(
											'type' => 'text',
											'value' => esc_html__('Sign up','workreap'),
											'label' => esc_html__('Signup button title', 'workreap'),
											'desc' => esc_html__('Add signup button title for box', 'workreap'),
										),
									)
								),
								'show_borders' => true,
							),
							'services_locations' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Locations filter?', 'workreap'),
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
							'services_dilivery' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Delivery Time filter?', 'workreap'),
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
							'services_response' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Response Time filter?', 'workreap'),
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
							'services_languages' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Languages filter?', 'workreap'),
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
							'services_price' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Filter By Price filter?', 'workreap'),
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
							'services_english_level' => array(
								'type' => 'switch',
								'value' => 'enable',
								'label' => esc_html__('Service english level?', 'workreap'),
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
						)
					),
				)
			),
		),
	),
);
