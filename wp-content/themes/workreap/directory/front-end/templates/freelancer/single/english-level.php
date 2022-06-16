<?php
/**
 *
 * The template used for displaying similar freelancer
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$english_level_list   	= worktic_english_level_list();
if (function_exists('fw_get_db_post_option')) {
	$english_level	= fw_get_db_post_option($post->ID, 'english_level');
	$frc_english_level			 = fw_get_db_settings_option('frc_english_level', $default_value = 'no');
}


if( !empty( $english_level_list[$english_level] ) &&  (!empty($frc_english_level) && $frc_english_level === 'no')  ) {?>
	<div class="wt-widget singleenglish_level">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('English level','workreap');?></h2>
		</div>
		<div class="wt-widgetcontent">
			<div class="wt-widgettag wt-widgettagvtwo">
				<a><?php echo esc_html($english_level_list[$english_level]);?> </a>
			</div>
		</div>
	</div>
<?php } ?>