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
if(apply_filters('workreap_is_listing_free',false,$user_identity) === false ){
?>
<li class="toolip-wrapo <?php echo esc_attr( $reference === 'package' ? 'wt-active' : ''); ?>">
	<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('package', $user_identity); ?>">
		<i class="ti-package"></i>
		<span><?php esc_html_e('Packages','workreap');?></span>
		<?php do_action('workreap_get_tooltip','element','packages');?>
	</a>
</li>
<?php }
