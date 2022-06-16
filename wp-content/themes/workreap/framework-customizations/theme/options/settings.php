<?php

if (!defined('FW')) {
    die('Forbidden');
}
/**
 * Framework options
 *
 * @var array $options Fill this array with options to generate framework settings form in backend
 */
$options = array (
    fw()->theme->get_options('general-settings'),
    fw()->theme->get_options('headers-settings'),
    fw()->theme->get_options('titlebar-settings'),
    fw()->theme->get_options('footer-settings'),
    fw()->theme->get_options('typo-settings'),
	fw()->theme->get_options('sidebar-settings'),
    fw()->theme->get_options('colors-settings'),
    fw()->theme->get_options('social-sharing-settings'),
	fw()->theme->get_options('tooltips-settings'),
	fw()->theme->get_options('search-settings'),
	fw()->theme->get_options('registration-settings'),
    fw()->theme->get_options('directory-settings'),
	fw()->theme->get_options('proposal-settings'),
	fw()->theme->get_options('profile-strength'),
	fw()->theme->get_options('api-settings'),
	fw()->theme->get_options('payment-settings'),
    fw()->theme->get_options('email-settings'),
	fw()->theme->get_options('pusher'),
	fw()->theme->get_options('disputes-settings'),
    fw()->theme->get_options('social-connect-settings'),
    fw()->theme->get_options('social-profile'),
    fw()->theme->get_options('underconstruction-settings'),
	fw()->theme->get_options('captcha-settings'),
);
