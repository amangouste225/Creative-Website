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

if (function_exists('fw_get_db_settings_option')) {
	$remove_languages		 = fw_get_db_settings_option('frc_remove_languages', $default_value = 'no');
}

$db_languages	= wp_get_post_terms($post->ID, 'languages');
?>
<?php if( !empty( $db_languages ) && (!empty($remove_languages) && $remove_languages === 'no' ) ){?>
	<div class="wt-widget languages-icanspeak">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Languages','workreap');?></h2>
		</div>
		<div class="wt-widgetcontent">
			<div class="wt-widgettag wt-widgettagvtwo">
				<?php 
				foreach( $db_languages as $key => $item ){ 
					if( !empty( $item ) ) {
					?>
						<a><?php echo esc_html($item->name);?> </a>
					<?php }
				} ?>
			</div>
		</div>
	</div>
<?php } ?>