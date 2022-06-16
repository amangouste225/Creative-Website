<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array (
    'social_sharing' => array (
        'title'   => esc_html__('Sharing Sharing' , 'workreap') ,
        'type'    => 'tab' ,
        'options' => array (
            'social_facebook'  => array (
                'label'        => esc_html__('Facebook' , 'workreap') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'workreap') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'workreap') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'workreap') ,
                ) ,
            ) ,
			'social_twitter' => array(
				'type'         => 'multi-picker',
				'label'        => false,
				'desc'         => false,
				'picker'       => array(
					'gadget' => array(
						'label'        => esc_html__('Twitter' , 'workreap') ,
						'type'         => 'switch' ,
						'value'        => 'enable' ,
						'desc'         => esc_html__('Sharing on/off' , 'workreap') ,
						'left-choice'  => array (
							'value' => 'enable' ,
							'label' => esc_html__('Enable' , 'workreap') ,
						) ,
						'right-choice' => array (
							'value' => 'disable' ,
							'label' => esc_html__('Disable' , 'workreap') ,
						) ,
					)
				),
				'choices'      => array(
					'enable'  => array(
						'twitter_username' => array (
							'type'  => 'text' ,
							'value' => '' ,
							'label' => esc_html__('Twitter username' , 'workreap') ,
							'desc'  => esc_html__('This will be used in the tweet for the via parameter. The site name will be used if no twitter username is provided. Do not include the @' , 'workreap') ,
						) ,
					),
				)
			),
			'social_linkedin'   => array (
                'label'        => esc_html__('Linkedin Share' , 'workreap') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'workreap') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'workreap') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'workreap') ,
                ) ,
            ) ,
			'social_pinterest'   => array (
                'label'        => esc_html__('Pinterest Share' , 'workreap') ,
                'type'         => 'switch' ,
                'value'        => 'enable' ,
                'desc'         => esc_html__('Sharing on/off' , 'workreap') ,
                'left-choice'  => array (
                    'value' => 'enable' ,
                    'label' => esc_html__('Enable' , 'workreap') ,
                ) ,
                'right-choice' => array (
                    'value' => 'disable' ,
                    'label' => esc_html__('Disable' , 'workreap') ,
                ) ,
            ) ,
        ) ,
    ) ,
);
