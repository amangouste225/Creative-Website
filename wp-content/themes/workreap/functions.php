<?php
/**
 *
 * Theme Files
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
require_once ( get_template_directory() . '/theme-config/theme-setup/class-theme-setup.php'); //Theme setup
require_once ( get_template_directory() . '/includes/sidebars.php'); //Theme sidebars
require_once ( get_template_directory() . '/includes/functions.php'); //Theme functionality
require_once workreap_override_templates( '/includes/class-headers.php' );
require_once workreap_override_templates( '/includes/class-footers.php' );
require_once workreap_override_templates( '/includes/class-titlebars.php' );
require_once workreap_override_templates( '/includes/class-notifications.php' );
require_once workreap_override_templates( '/includes/scripts.php' );

require_once ( get_template_directory() . '/includes/google_fonts.php'); // goolge fonts
require_once ( get_template_directory() . '/includes/hooks.php'); //Hooks
require_once ( get_template_directory() . '/includes/template-tags.php'); //Tags
require_once ( get_template_directory() . '/includes/jetpack.php'); //jetpack
require_once ( get_template_directory() . '/theme-config/tgmp/init.php'); //TGM init
require_once ( get_template_directory() . '/framework-customizations/includes/option-types.php'); //Custom options
require_once workreap_override_templates( '/includes/constants.php' );
require_once ( get_template_directory() . '/includes/class-woocommerce.php'); //Woocommerce
require_once workreap_override_templates( '/directory/front-end/class-dashboard-menu.php' );
require_once ( get_template_directory() . '/includes/redius-search/location_check.php');
require_once ( get_template_directory() . '/directory/front-end/hooks.php');
require_once ( get_template_directory() . '/directory/front-end/functions.php');
require_once ( get_template_directory() . '/directory/front-end/woo-hooks.php');
require_once ( get_template_directory() . '/includes/languages.php');
require_once ( get_template_directory() . '/demo-content/data-importer/importer.php'); //Users dummy data
require_once workreap_override_templates( '/includes/typo.php' );
require_once ( get_template_directory() . '/directory/back-end/dashboard.php');
require_once ( get_template_directory() . '/directory/back-end/hooks.php');
require_once ( get_template_directory() . '/directory/back-end/functions.php');
require_once ( get_template_directory() . '/directory/front-end/ajax-hooks.php');
require_once ( get_template_directory() . '/directory/front-end/filepermission/class-file-permission.php');
require_once ( get_template_directory() . '/directory/front-end/term_walkers.php'); //Term walkers