<?php
/**
 *
 * The template used for displaying freelancer industrial experience
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
	$industrial_experiences = fw_get_db_post_option($post_id, 'industrial_experiences', true);
} else {
	$industrial_experiences	= array();
}

$display_type	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$display_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
}
$field_type		= !empty($display_type) && ($display_type === 'number') ? '%' : esc_html__('Years','workreap');
?>
<?php if( !empty( $industrial_experiences ) && is_array($industrial_experiences) ){?>
	<div id="wt-ourskill" class="wt-widget items-more-wrap-sk toolip-wrapo">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Industrial experiences','workreap');?></h2>
		</div>
		<?php do_action('workreap_get_tooltip','element','front_industrial');?>
		<div class="wt-widgetcontent wt-skillscontent">
			<?php 
			foreach( $industrial_experiences as $key => $item ){
				if( !empty( $item['exp'][0] ) ){
					$skill		= get_term_by('id', $item['exp'][0], 'wt-industrial-experience');
					$item_val	= !empty( $item['value'] ) ? intval( $item['value'] ) : 0;
					if( !empty( $display_type ) && $display_type === 'number' ){
						$percentage	= $item_val;						
					} else{
						if($item_val>10){
							$item_val	= 10;
						}
						$percentage	= $item_val*10;
					}
					if( !empty($skill->name) ) {?>
						<div class="wt-skillholder " data-percent="<?php echo intval( $percentage );?>%">
							<span><?php echo esc_html( $skill->name );?><em><?php echo esc_html( $item_val );?>&nbsp;<?php echo esc_attr($field_type);?></em></span>
							<div class="wt-skillbarholder">
								<div class="wt-skillbar"></div>
							</div>
						</div>
					<?php 
					}
				}
			}?>
		</div>
	</div>
<?php }?>