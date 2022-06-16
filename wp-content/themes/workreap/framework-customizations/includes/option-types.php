<?php

if (!defined('ABSPATH'))
    die('Direct access forbidden.');

function workreap_action_theme_include_custom_option_types() {
    if (is_admin()) {
        require_once get_template_directory() . '/framework-customizations/includes/option-types/new-icon/class-fw-option-type-new-icon.php';
        // and all other option types
    }
}

add_action('fw_option_types_init' , 'workreap_action_theme_include_custom_option_types');
