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

if (is_user_logged_in()) { ?>
	<li class="toolip-wrapo"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><i class="ti-shift-right"></i> <span><?php esc_html_e('Logout', 'workreap'); ?></span><?php do_action('workreap_get_tooltip','element','logout');?></a></li>
<?php }