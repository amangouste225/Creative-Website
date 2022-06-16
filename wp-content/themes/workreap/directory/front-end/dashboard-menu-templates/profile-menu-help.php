<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;

if ( function_exists('fw_get_db_settings_option') ) {
	$help 	= fw_get_db_settings_option('help_support');
} 

$access		= !empty ($help['gadget']) ? $help['gadget'] : '';

if( !empty($access) && $access== 'enable'){?>
	<li class="toolip-wrapo <?php echo esc_attr( $reference === 'help' ? 'wt-active' : ''); ?>">
		<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('help', $user_identity); ?>">
			<i class="ti-tag"></i>
			<span><?php esc_html_e('Help and support','workreap');?></span>
			<?php do_action('workreap_get_tooltip','element','help');?>
		</a>
	</li>
<?php } ?>
