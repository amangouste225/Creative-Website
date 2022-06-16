<!doctype html>
<!--[if (gt IE 9)|!(IE)]><html lang="en"><![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	?>
	<?php do_action('workreap_systemloader'); ?>
	<?php do_action('workreap_app_available'); ?>
	<?php do_action('workreap_demo_preview'); ?>
	<div id="wt-wrapper" class="wt-wrapper wt-haslayout">
		<?php do_action('workreap_do_process_headers'); ?>
		<?php do_action('workreap_prepare_titlebars'); ?>
		<main id="wt-main" class="wt-main wt-haslayout">