<?php
/**
 *
 * The template used for displaying freelancer Skills
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;

if (function_exists('fw_get_db_post_option')) {
	$skills = fw_get_db_post_option($post_id, 'skills', true);
} else {
	$skills	= array();
}

$display_type	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
}
$field_type		= !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years','workreap');

?>
<?php if( !empty( $skills ) && is_array($skills) ){?>
	<div id="wt-ourskill" class="wt-widget items-more-wrap-sk toolip-wrapo">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('My Skills','workreap');?></h2>
		</div>
		<?php do_action('workreap_get_tooltip','element','front_skills');?>
		<div class="wt-widgetcontent wt-skillscontent data-list">
			<?php 
			foreach( $skills as $key => $item ){
				if( !empty( $item['skill'][0] ) ){
					$skill	= get_term_by('id', $item['skill'][0], 'skills');
					$item_val	= !empty( $item['value'] ) ? intval($item['value']) : 0;
					if( !empty( $display_type ) && $display_type === 'number' ){
						$percentage	= $item_val;
					} else{
						if($item_val>10){
							$item_val	= 10;
						}
						$percentage	= $item_val*10;
						
					}
					if( !empty($skill->name) ) { ?>
						<div class="wt-skillholder sp-load-item" data-percent="<?php echo intval( $percentage );?>%">
							<span><?php echo esc_html( $skill->name );?><em><?php echo esc_html( $item_val.' '.$field_type );?></em></span>
							<div class="wt-skillbarholder">
								<div class="wt-skillbar"></div>
							</div>
						</div>
					<?php }
				}
			}?>
			<div class="wt-btnarea"><a class="sp-loadMore" href="#" onclick="event_preventDefault(event);"><?php esc_html_e('View More','workreap');?></a> </div>
		</div>
	</div>
<?php }?>