<?php

if (!defined('FW')) {
    die('Forbidden');
}
$options = array (
    'social_icons' => array (
        'title'   => esc_html__('Social Profiles' , 'workreap') ,
        'type'    => 'tab' ,
        'options' => array (
            'social_icons'       => array (
                'label'         => esc_html__('Social Profiles' , 'workreap') ,
                'type'          => 'addable-popup' ,
                'value'         => array () ,
                'desc'          => esc_html__('Add Social Icons as much as you want. Choose the icon, url and the title' , 'workreap') ,
                'popup-options' => array (
                    'social_name'       => array (
                        'label' => esc_html__('Title' , 'workreap') ,
                        'type'  => 'text' ,
                        'value' => 'Name' ,
                        'desc'  => esc_html__('The Title of the Link' , 'workreap')
                    ) ,
                    'social_icons_list' => array (
                        'type'  => 'new-icon' ,
                        'value' => 'fa-smile-o' ,
                        'attr'  => array () ,
                        'label' => esc_html__('Choose Icon' , 'workreap') ,
                        'desc'  => esc_html__('' , 'workreap') ,
                        'help'  => esc_html__('' , 'workreap') ,
                    ) ,
                    'social_url'        => array (
                        'label' => esc_html__('Url' , 'workreap') ,
                        'type'  => 'text' ,
                        'value' => '#' ,
                        'desc'  => esc_html__('The link to the social profile.' , 'workreap')
                    ) ,
                ) ,
                'template'      => '{{- social_name }}' ,
            ) ,
            'social_icon_target' => array (
                'label' => esc_html__('Open in New Window' , 'workreap') ,
                'type'  => 'switch' ,
                'desc'  => esc_html__('The links will be opened into new tab or window when your visitors clicked on the link.' , 'workreap')
            ) ,
        ) ,
    ) ,
);
