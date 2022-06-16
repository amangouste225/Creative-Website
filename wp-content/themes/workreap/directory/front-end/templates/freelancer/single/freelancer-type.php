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
$freelancer_level   = worktic_freelancer_level_list(); 
if (function_exists('fw_get_db_post_option')) {
	$freelancer_type	= fw_get_db_post_option($post->ID, 'freelancer_type');
	$frc_remove_freelancer_type	 = fw_get_db_settings_option('frc_remove_freelancer_type', $default_value = 'no');
}
?>
<?php if( !empty( $freelancer_type ) && (!empty($frc_remove_freelancer_type) && $frc_remove_freelancer_type === 'no') ) {?>
	<div class="wt-widget singlefreelancer_type">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Freelancer type','workreap');?></h2>
		</div>
		<div class="wt-widgetcontent">
			<div class="wt-widgettag wt-widgettagvtwo">
				<?php if(!empty($freelancer_type) && is_array($freelancer_type)){
					foreach($freelancer_type as $key => $item){
						if(!empty($freelancer_level[$item]))
					?>
						<a><?php echo esc_html($freelancer_level[$item]);?> </a>
					<?php }?>
				<?php }else{?> 
					<a><?php echo esc_html($freelancer_level[$freelancer_type]);?> </a>
				<?php }?>
			</div>
		</div>
	</div>
<?php } ?>