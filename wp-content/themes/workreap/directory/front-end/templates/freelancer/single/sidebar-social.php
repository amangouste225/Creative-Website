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

$socialmediaurls	= array();
if( function_exists('fw_get_db_settings_option')  ){
	$post_type			= get_post_type($post_id);
	if( !empty( $post_type ) && $post_type ==='employers'){
		$socialmediaurls	= fw_get_db_settings_option('employer_social_profile_settings', $default_value = null);
	} else {
		$socialmediaurls	= fw_get_db_settings_option('freelancer_social_profile_settings', $default_value = null);
	}
}

$socialmediaurl 		= !empty($socialmediaurls['gadget']) ? $socialmediaurls['gadget'] : '';

$social_settings	= array();
if(function_exists('workreap_get_social_media_icons_list')){
	$social_settings	= workreap_get_social_media_icons_list('no');
}

$social_available = 'no';
if(!empty($social_settings) && is_array($social_settings) ) {
	foreach($social_settings as $key => $val ) {
		$social_url	= '';

		if( function_exists('fw_get_db_post_option') ){
			$social_url	= fw_get_db_post_option($post->ID, $key, null);
		}

		if(!empty($social_url)){
			$social_available = 'yes';
			break;
		}
	}
}

if(!empty($socialmediaurl) && $socialmediaurl === 'enable' && $social_available === 'yes') {?>
	<div id="wt-ourskill" class="wt-widget items-more-wrap-sk toolip-wrapo social-hideo">
		<div class="wt-widgettitle">
			<h2><?php esc_html_e('Social Profiles','workreap');?></h2>
		</div>
		<ul class="wt-socialiconssimple">
			<?php foreach($social_settings as $key => $val ) {
				$icon		= !empty( $val['icon'] ) ? $val['icon'] : '';
				$color		= !empty( $val['color'] ) ? $val['color'] : '#484848';

				$enable_value   = !empty($socialmediaurls['enable'][$key]['gadget']) ? $socialmediaurls['enable'][$key]['gadget'] : '';
				if( !empty($enable_value) && $enable_value === 'enable' ){ 
					$social_url	= '';

					if( function_exists('fw_get_db_post_option') ){
						$social_url	= fw_get_db_post_option($post->ID, $key, null);
					}
					
					$social_url	= !empty($social_url) ? $social_url : '';
					if( $key === 'whatsapp' ){
						if ( !empty( $social_url ) ){
							$social_url	= 'https://api.whatsapp.com/send?phone='.$social_url;
						}
					} else if( $key === 'skype' ){
						if ( !empty( $social_url ) ){
							$social_url	= 'skype:'.$social_url.'?call';
						}
					}else{
						$social_url	= esc_url($social_url);;
					}
					
					if(!empty($social_url)) {?>
						<li><a href="<?php echo esc_attr($social_url); ?>" target="_blank">
							<i class="wt-icon <?php echo esc_attr( $icon );?>" style="color:<?php echo esc_attr( $color );?>"></i>
						</a></li>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php } ?>