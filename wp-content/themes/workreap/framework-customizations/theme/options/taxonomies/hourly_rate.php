<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'hourly_settings' => array(
        'type' => 'group',
        'options' => array(
            'hint' => array(
				'type' => 'html',
				'html' => esc_html__('Important', 'workreap'),
				'label' => esc_html__('', 'workreap'),
				'desc' => wp_kses( __( 'Please add slug only integer values with hyphen (-) like, 5-10 or only 5. Please don\'t add any other characters into slug. Otherwise your search filter will not work for search freelancers on search result page. Name should be like below<br> $5 - $10<br>$11 - $20 <br>and their slug values should be like below<br>5-10<br>11-20', 'workreap'),array(
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
);

