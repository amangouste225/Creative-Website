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
$post_id 		= $post->ID;

if (function_exists('fw_get_db_post_option')) {
	$awards 	= fw_get_db_post_option($post_id, 'awards', true);
	$frc_remove_awards = fw_get_db_settings_option('frc_remove_awards', 'no');
} else {
	$awards		= array();
}

if(!empty($frc_remove_awards) && $frc_remove_awards === 'no'){
	$default_img = get_template_directory_uri().'/images/awards-65x65.jpg';
	if( !empty( $awards ) && is_array($awards) ){?>
		<div class="wt-widget wt-widgetarticlesholder wt-articlesuser items-more-wrap-aw">
			<div class="wt-widgettitle">
				<h2><?php esc_html_e('Awards & Certifications','workreap');?></h2>
			</div>
			<div class="wt-widgetcontent data-list">
				<?php foreach( $awards as $key => $item ){
					$type		= !empty( $item['image']['attachment_id'] ) ? get_post_mime_type( $item['image']['attachment_id'] ) :'';
				?>
				<div class="wt-particlehold sp-load-item">
					<?php if( !empty( $item['image']['url'] ) ){?>
						<figure>
							<?php if(!empty($type)&& $type === 'application/pdf'){?>
								<a href="<?php echo esc_url( $item['image']['url'] );?>"><img src="<?php echo esc_url( $default_img );?>" alt="<?php esc_attr_e('certificate','workreap');?>"></a>
							<?php }else{?>
								<img src="<?php echo esc_url( $item['image']['url'] );?>" alt="<?php esc_attr_e('certificate','workreap');?>">
							<?php }?>
						</figure>
					<?php }?>
					<div class="wt-particlecontent">
						<h3>
							<?php if(!empty($type)&& $type === 'application/pdf'){?>
								<a href="<?php echo esc_url( $item['image']['url'] );?>"><?php echo esc_html( $item['title'] );?></a>
							<?php }else{?>
								<?php echo esc_html( $item['title'] );?>
							<?php }?>
						</h3>
						<?php if( !empty( $item['date'] ) ){?>
							<span><i class="lnr lnr-calendar"></i><?php echo date(get_option('date_format'), strtotime(apply_filters('workreap_date_format_fix',$item['date']))); ?></span>
						<?php }?>
					</div>
				</div>
				<?php }?>
				<div class="wt-btnarea"><a href="#" onclick="event_preventDefault(event);" class="sp-loadMore"><?php esc_html_e('Load More','workreap');?></a></div> 
			</div>
		</div>
	<?php }
}