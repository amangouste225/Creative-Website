<?php
/**
 *
 * The template used for displaying freelancer specialization
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
	$specialization = fw_get_db_post_option($post_id, 'specialization', true);
} else {
	$specialization	= array();
}

$experience_type	= '';
if( function_exists('fw_get_db_settings_option')  ){
	$experience_type	= fw_get_db_settings_option('display_type', $default_value = 'number');
}
$field_type		= !empty($experience_type) && ($experience_type === 'number') ? '%' : esc_html__('Years','workreap');
?>
<?php if( !empty( $specialization ) && is_array($specialization) ){?>
	<div id="wt-ourskill" class="wt-widget items-more-wrap-sk toolip-wrapo">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Specialization','workreap');?></h2>
		</div>
		<?php do_action('workreap_get_tooltip','element','front_specializations');?>
		<div class="wt-widgetcontent wt-skillscontent">
			<?php 
			foreach( $specialization as $key => $item ){
				if( !empty( $item['spec'][0] ) ){
					$skill		= get_term_by('id', $item['spec'][0], 'wt-specialization');
					$item_val	= !empty( $item['value'] ) ? intval( $item['value'] ) : 0;
					if( !empty( $experience_type ) && $experience_type === 'number' ){
						$percentage	= $item_val;
						
					} else{
						if($item_val>10){
							$item_val	= 10;
						}
						$percentage	= $item_val*10;
					}
					if( !empty($skill->name) ) { ?>
					<div class="wt-skillholder" data-percent="<?php echo intval( $percentage );?>%" >
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