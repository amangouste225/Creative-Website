<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-hourglass';
if(apply_filters('workreap_is_listing_free',false,$user_identity) === false ){
	if ( function_exists( 'fw_get_db_settings_option' ) ) {
		$package_expiry_img 	= fw_get_db_settings_option( 'package_expiry', $default_value = null );
		$package_expiry_img		= !empty( $package_expiry_img['url'] ) ? $package_expiry_img['url'] : '';
	}

	$expiry_string		= workreap_get_subscription_metadata( 'subscription_featured_string',intval($user_identity) );

	$formatted_date		= ''; 
	if( $expiry_string != false ){
		$formatted_date = date("Y, n, d, H, i, s", strtotime("-1 month",intval($expiry_string))); 
	}

	?>
		<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
			<div class="wt-insightsitem wt-dashboardbox">
				<ul class="wt-countersoon" data-date="<?php echo esc_attr($formatted_date);?>">
					<li>
						<div class="wt-countdowncontent">
							<p><?php esc_html_e('d', 'workreap'); ?></p> <span class="days" data-days></span>
						</div>
					</li>
					<li>
						<div class="wt-countdowncontent">
							<p><?php esc_html_e('h', 'workreap'); ?></p> <span class="hours" data-hours></span>
						</div>
					</li>
					<li>
						<div class="wt-countdowncontent">
							<p><?php esc_html_e('m', 'workreap'); ?></p> <span class="minutes" data-minutes></span>
						</div>
					</li>
					<li>
						<div class="wt-countdowncontent">
							<p><?php esc_html_e('s', 'workreap'); ?></p> <span class="seconds" data-seconds></span>
						</div>
					</li>
				</ul>
				<figure class="wt-userlistingimg">
					<?php if( !empty($package_expiry_img) ) {?>
						<img src="<?php echo esc_url($package_expiry_img);?>" alt="<?php esc_attr_e('Package expiry', 'workreap'); ?>">
					<?php } else {?>
							<span class="<?php echo esc_attr($icon);?>"></span>
					<?php }?>
				</figure>
				<div class="wt-insightdetails">
					<div class="wt-title">
						<h3><?php esc_html_e('Check Package Expiry', 'workreap'); ?></h3>
						<a href="<?php Workreap_Profile_Menu::workreap_profile_menu_link('package', $user_identity); ?>"><?php esc_html_e('Upgrade Now', 'workreap'); ?></a>
						<?php if( !empty( $expiry_string ) ) {?>
							 | <a data-toggle="modal" data-target="#wt-package-details" href="#"><?php esc_html_e('View More', 'workreap'); ?></a>
						<?php } ?>
					</div>													
				</div>	
			</div>
		</div>	
		<?php
		$script = "
				(function(jQuery) {
					var launch = new Date(".esc_js($formatted_date).");
					console.log(launch);
					var days = jQuery('.days');
					var hours = jQuery('.hours');
					var minutes = jQuery('.minutes');
					var seconds = jQuery('.seconds');
					setDate();
					function setDate(){
						var now = new Date();
						if( launch < now ){
							days.html('0');
							hours.html('0');
							minutes.html('0');
							seconds.html('0');
						}
						else{
							var s = -now.getTimezoneOffset()*60 + (launch.getTime() - now.getTime())/1000;
							var d = Math.floor(s/86400);
							days.html(d);
							s -= d*86400;
							var h = Math.floor(s/3600);
							hours.html(h);
							s -= h*3600;
							var m = Math.floor(s/60);
							minutes.html(m);
							s = Math.floor(s-m*60);
							seconds.html(s);
							setTimeout(setDate, 1000);
						}
					}
				})(jQuery);
			";
		wp_add_inline_script('workreap-callbacks', $script, 'after');
}