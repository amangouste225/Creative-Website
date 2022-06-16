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

if (function_exists('fw_get_db_post_option')) {
	$skills 	= fw_get_db_post_option($post_id, 'skills', true);
} else {
	$skills		= array();
}
?>
<?php if( !empty( $skills ) && is_array($skills) ){?>
	<div class="wt-widget">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Similar freelancers','workreap');?></h2>
		</div>
		<div class="wt-widgetcontent">
			<div class="wt-widgettag wt-widgettagvtwo">
				<?php 
				foreach( $skills as $key => $item ){ 
					if( !empty( $item['skill'][0] ) ) {
						$skill			= get_term_by('id', $item['skill'][0], 'skills');
						if( !empty( $skill ) ){
							$skill_link		= get_term_link($skill);
							$skill_link		= !empty($skill_link) ? $skill_link :"#";
							if( function_exists('workreap_get_search_page_uri') ){
								$action_url	= workreap_get_search_page_uri('freelancer');
								if( !empty( $action_url ) ){
									$skill_link	= $action_url.'?skills[]='.$skill->slug;
								}
								
							}
						?>
							<a href="<?php echo esc_url($skill_link);?>"><?php echo esc_html($skill->name);?></a> 
					<?php 
						}
					}
				} ?>
			</div>
		</div>
	</div>
<?php } ?>