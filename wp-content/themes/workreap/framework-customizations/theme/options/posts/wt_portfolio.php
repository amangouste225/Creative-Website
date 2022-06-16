<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(    
    'portfolio_settings' => array(
        'title' => esc_html__('Portfolio Settings', 'workreap'),
        'type' => 'box',
        'options' => array(
			'custom_link' => array(
				'label' => esc_html__('Custom Link', 'workreap'),
                'desc' 	=> esc_html__('Add custom link', 'workreap'),
                'type' 	=> 'text',
                'value' => '',
            ),  
            'gallery_imgs' => array(
                'type'  		=> 'multi-upload',
                'value' 		=> array(),
                'label' 		=> esc_html__('Upload gallery', 'workreap'),
                'desc'  		=> esc_html__('Upload portfolio service gallery images', 'workreap'),         
                'images_only' 	=> false,            
                'files_ext' 	=> array( 'jpg','jpeg','gif','png' ),  
            ),
            'zip_attachments' => array(
                'type'  		=> 'multi-upload',
                'value' 		=> array(),
                'label' 		=> esc_html__('Upload zip/rar files', 'workreap'),
                'desc'  		=> esc_html__('Upload zip/rar files', 'workreap'),         
                'images_only' 	=> false,            
                'files_ext' 	=> array( 'rar','zip' ),  
            ),
            'documents' => array(
                'type'  		=> 'multi-upload',
                'value' 		=> array(),
                'label' 		=> esc_html__('Upload documents', 'workreap'),
                'desc'  		=> esc_html__('Upload documents', 'workreap'),        
                'images_only' 	=> false,  
            ),
            'videos' => array(
				'type' => 'addable-option',
				'value' => array(),
				'label' => esc_html__('Video URL', 'workreap'),
				'desc' => esc_html__('Add video URL here', 'workreap'),
				'option' => array('type' => 'text'),
				'add-button-text' => esc_html__('Add', 'workreap'),
				'sortable' => true,
            ),
        )
    ),
);

