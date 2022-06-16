<?php

if (!defined('FW')) {
    die('Forbidden');
}



if( function_exists('workreap_new_social_login_url') ){
	$redirect_google_url	= workreap_new_social_login_url('googlelogin');
	$redirect_facebook_url	= workreap_new_social_login_url('facebooklogin');
}else{
	$redirect_google_url	= home_url('wp-login.php') . '?googlelogin=1';
	$redirect_facebook_url	= home_url('wp-login.php') . '?facebooklogin=1';
}

$redirect_linkedin_url		= home_url('/') . '?action=linkedin_login';

$options = array (
    'connect_settings' => array (
        'type'    => 'tab' ,
        'title'   => esc_html__('Social Connect' , 'workreap') ,
        'options' => array (
			'social' => array(
                'title' => esc_html__('General Settings', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'social_title' => array(
                        'type' => 'text',
                        'label' => esc_html__('Registration title', 'workreap'),
                        'desc' => esc_html__('Add title of registration.', 'workreap'),
                    ),
					'social_desc' => array(
                        'type' => 'textarea',
                        'label' => esc_html__('Description', 'workreap'),
                        'desc' => esc_html__('Add registration short note for users', 'workreap'),
                    ),
                )
                ),
			'google' => array(
                'title' => esc_html__('Google', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'enable_google_connect' => array(
                        'type' => 'switch',
                        'value' => 'disable',
                        'desc' => esc_html__('Enable google connect/login?', 'workreap'),
                        'label' => esc_html__('Google connect?', 'workreap'),
                        'right-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                        'left-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                    ),
					'google_settings' => array(
                        'type' => 'html',
                        'html' => esc_html__('Google Settings', 'workreap'),
                        'label' => esc_html__('Settings', 'workreap'),
						'desc' => wp_kses( __( 'Add you system to google domain <a href="https://www.google.com/accounts/ManageDomains" target="_blank">Add your domain to Google system!</a> and then create your own API key <a href="https://code.google.com/apis/console" target="_blank">You have to create and API access</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					'redirect_url' => array(
                        'type' => 'html',
						'html' 	=> $redirect_google_url,
                        'label' => esc_html__('Authorized redirect URIs', 'workreap'),
                        'desc'  => wp_kses( __( 'Copy above link and add in your google project as Authorized redirect URIs. For more detail you can also read this article : <a href="https://amentotech.ticksy.com/" target="_blank">Check Settings</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					'client_id' => array(
                        'type' => 'text',
                        'label' => esc_html__('Client ID', 'workreap'),
                        'desc' => esc_html__('Add client ID here.', 'workreap'),
                    ),
					'client_secret' => array(
                        'type' => 'text',
                        'label' => esc_html__('Client secret', 'workreap'),
                        'desc' => esc_html__('Add client secret here.', 'workreap'),
                    ),
					'app_name' => array(
                        'type' => 'text',
						'value' => esc_html__('Google Connect', 'workreap'),
                        'label' => esc_html__('Application name', 'workreap'),
                        'desc' => esc_html__('Add application name here.', 'workreap'),
                    ),
	
					'g_prefix' => array(
                        'type' => 'text',
						'value' => esc_html__('google-', 'workreap'),
                        'label' => esc_html__('User prefix (optional)', 'workreap'),
                        'desc' => esc_html__('Add prefix for new registered user before name. This will be used before username. like http://www.yoursite.com/provider-category/google-abc', 'workreap'),
                    ),
                )
            ),
            'facebook' => array(
                'title'   => esc_html__( 'Facebook', 'workreap' ),
                'type'    => 'tab',
                'options' => array(
					'enable_facebook_connect' => array(
                        'type' => 'switch',
                        'value' => 'disable',
                        'desc' => esc_html__('Enable facebook connect/login?', 'workreap'),
                        'label' => esc_html__('Facebook connect?', 'workreap'),
                        'right-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                        'left-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                    ),
					'facebook_settings' => array(
                        'type' => 'html',
                        'html' => esc_html__('Facebook Settings', 'workreap'),
                        'label' => esc_html__('Settings', 'workreap'),
						'desc' => wp_kses( __( 'Register your app and get APP ID and APP Secret <a href="https://developers.facebook.com/docs/apps/register" target="_blank">Create APP</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					'redirect_url' => array(
                        'type' => 'html',
						'html' 	=> $redirect_facebook_url,
                        'label' => esc_html__('Authorized redirect URIs', 'workreap'),
                        'desc'  => wp_kses( __( 'Copy this link and add in your facebook APP as Valid OAuth redirect URIs. For more detail you can also read this article : <a href="https://amentotech.ticksy.com/" target="_blank">Check Settings</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
                    'app_id' => array(
                        'type' => 'text',
                        'label' => esc_html__('APP ID', 'workreap'),
                        'desc' => esc_html__('Add your APP ID here.', 'workreap'),
                    ),
                    'app_secret' => array(
                        'type' => 'text',
                        'label' => esc_html__('APP Secret', 'workreap'),
                        'desc' => esc_html__('Add your APP secret here.', 'workreap'),
                    ),
	
					'fb_prefix' => array(
                        'type' => 'text',
						'value' => esc_html__('facebook-', 'workreap'),
                        'label' => esc_html__('User prefix (optional)', 'workreap'),
                        'desc' => esc_html__('Add prefix for new registered user before name. This will be used before username. like http://www.yoursite.com/provider-category/facebook-abc', 'workreap'),
                    ),
                )
             ),
			'linkedin' => array(
                'title' => esc_html__('Linkedin', 'workreap'),
                'type' => 'tab',
                'options' => array(
					'enable_linkedin_connect' => array(
                        'type' => 'switch',
                        'value' => 'disable',
                        'desc' => esc_html__('Enable linkedin connect/login?', 'workreap'),
                        'label' => esc_html__('Linkedin connect?', 'workreap'),
                        'right-choice' => array(
                            'value' => 'enable',
                            'label' => esc_html__('Enable', 'workreap'),
                        ),
                        'left-choice' => array(
                            'value' => 'disable',
                            'label' => esc_html__('Disable', 'workreap'),
                        ),
                    ),
					'redirect_linkedin_url' => array(
                        'type' => 'html',
						'html' 	=> $redirect_linkedin_url,
                        'label' => esc_html__('Authorized redirect URLs for your app', 'workreap'),
                        'desc'  => wp_kses( __( 'Copy this link and add in your Linkedin APP Auth > OAuth 2.0 settings as Valid OAuth redirect URIs.', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					'linkedin_settings' => array(
                        'type' => 'html',
                        'html' => esc_html__('Linkedin Settings', 'workreap'),
                        'label' => esc_html__('Settings', 'workreap'),
						'desc' => wp_kses( __( 'Retrieve API settings from LinkedIn Developer Portal. Follow the previous link, create an application and get the settings <a href="https://www.linkedin.com/developers/secure/developer">Go to Settings</a>', 'workreap' ),array(
																		'a' => array(
																			'href' => array(),
																			'title' => array()
																		),
																		'br' => array(),
																		'em' => array(),
																		'strong' => array(),
																	)),
                    ),
					'linkedin_client_id' => array(
                        'type' => 'text',
                        'label' => esc_html__('Client ID', 'workreap'),
                        'desc' => esc_html__('Add client ID here.', 'workreap'),
                    ),
					'linkedin_client_secret' => array(
                        'type' => 'text',
                        'label' => esc_html__('Client secret', 'workreap'),
                        'desc' => esc_html__('Add client secret here.', 'workreap'),
                    ),
					'linkedin_prefix' => array(
                        'type' => 'text',
						'value' => esc_html__('linkedin-', 'workreap'),
                        'label' => esc_html__('User prefix (optional)', 'workreap'),
                        'desc' => esc_html__('Add prefix for new registered user before name.', 'workreap'),
                    ),
                )
            ),
        )
    )
);
