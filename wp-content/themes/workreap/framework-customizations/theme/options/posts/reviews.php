<?php

if (!defined('FW')) {
    die('Forbidden');
}

$current_date = current_time('mysql');

$dynamic_rating_data = array();
if (!empty($_GET['post'])) {
    $review_id 		= intval($_GET['post']);
	
    /* Get the rating headings */
    $rating_titles 	= workreap_project_ratings();

    if (!empty($rating_titles)) {
        foreach ($rating_titles as $slug => $label) {
            $dynamic_rating_data[$slug] = array(
                'type' => 'slider',
                'value' => $label,
                'properties' => array(
                    'min'  => intval(1),
                    'max'  => intval(5),
                    'step' => intval(1),
                ),
                'label' => $label,
            );
        }
    }
}

$options = array(
	'settings' => array(
        'title' => esc_html__('Review Detail', 'workreap'),
        'type' => 'box',
        'options' => array(
            'review_date' => array(
                'type' => 'hidden',
                'value' => $current_date,
            ),
            'user_from' => array(
                'type' => 'multi-select',
                'label' => esc_html__('User From', 'workreap'),
                'desc' => esc_html__('Select user who rate.', 'workreap'),
                'population' => 'users',
                'source' => array('employer'),
                'limit' => 1,
            ),
            'user_to' => array(
                'type' => 'multi-select',
                'label' => esc_html__('User To', 'workreap'),
                'desc' => esc_html__('Select user who is being rated.', 'workreap'),
                'population' => 'users',
                'source' => array('freelancer'),
                'limit' => 1,
				
            ),
        )
    ),
);

