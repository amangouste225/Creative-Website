<?php 
/**
 *
 * The template part for displaying the template to display email settings
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = workreap_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$payrols	= workreap_get_payouts_lists();
if ( function_exists( 'fw_get_db_settings_option' ) ) {
	$allow_freelancers_withdraw 	= fw_get_db_settings_option( 'allow_freelancers_withdraw', $default_value = null );
} 

?>
<div class="wt-tabsinfo wt-email-settings">
	<div class="wt-tabscontenttitle">
		<h2><?php esc_html_e('Payouts Settings', 'workreap'); ?></h2>
	</div>
	<div class="wt-settingscontent">
		<div class="wt-description">
			<p><?php esc_html_e('All the earning will be sent to below selected payout method','workreap');?></p>
		</div>
		<?php if(!empty($allow_freelancers_withdraw) && $allow_freelancers_withdraw === 'freelancers'){?>
			<div class="row wt-custom-withdraw"><?php get_template_part('directory/front-end/templates/dashboard-withdraw-available', 'balance',array('wrapper' => true));?></div>
		<?php }?>
		<div class="wt-formtheme wt-userform payout-holder">
			<form class="wt-payout-settings">
				<?php 
					if( !empty($payrols) ) {
						foreach ($payrols as $pay_key	=> $payrol) {
							if( !empty($payrol['status']) && $payrol['status'] === 'enable' ) {
								$contents	= get_user_meta($user_identity,'payrols',true);
								$db_option	= !empty( $contents['type'] ) ? $contents['type'] : '';
								$db_option_display	= !empty( $contents['type'] ) && $pay_key === $contents['type'] ? 'display:block' : 'display:none';
								$db_option_display	= !empty($contents['payrol']) && $contents['payrol'] === 'paypal' ? 'display:block' : $db_option_display; //only for migration
							?>
							<fieldset>
								<div class="wt-checkboxholder"> 
									<span class="wt-radio">
										<input id="payrols-<?php echo esc_attr( $payrol['id'] ); ?>" <?php checked( $pay_key, $db_option); ?> type="radio" name="payout_settings[type]" value="<?php echo esc_attr( $payrol['id'] ); ?>">
										<label for="payrols-<?php echo esc_attr( $payrol['id'] ); ?>">
											<figure class="wt-userlistingimg">
												<img src="<?php echo esc_url( $payrol['img_url'] ); ?>" alt="<?php echo esc_attr( $payrol['title'] ); ?>">
											</figure>
										</label>
									</span>
								</div>
								<div class="fields-wrapper wt-haslayout" style="<?php echo esc_attr( $db_option_display );?>">
									<?php if( !empty($payrol['desc'])) {?>
										<div class="wt-description"><p><?php echo do_shortcode($payrol['desc']);?></p></div>
									<?php }?>
									<?php 
									if( !empty($payrol['fields'])) {
										foreach( $payrol['fields'] as $key => $field ){
											$db_value		= !empty($contents[$key]) ? $contents[$key] : "";
											//only for migration
											if( !empty($contents['email']) 
											   && !empty($contents['payrol']) 
											   && $contents['payrol'] === 'paypal' 
											   && $pay_key === 'paypal'
											){
												$db_value		= $contents['email'];
											}
										?>
										<div class="form-group form-group-half toolip-wrapo">
											<input type="<?php echo esc_attr($field['type']);?>" name="payout_settings[<?php echo esc_attr($key);?>]" id="<?php echo esc_attr($key);?>-payrols" class="form-control" placeholder="<?php echo esc_attr($field['placeholder']);?>" value="<?php echo esc_attr( $db_value ); ?>">
											<?php do_action('workreap_get_tooltip','element',$key);?>
										</div>
									<?php }}?>
								</div>
							</fieldset>
							<?php

							}
						}
					}
				?>
				<fieldset>
					<div class="form-group wt-btnarea">
						<button type="submit" class="wt-btn wt-payrols-settings" data-id="<?php echo esc_attr( $payrol['id'] ); ?>"><?php esc_html_e("Submit",'workreap');?></button>
					</div>
				</fieldset>
			</form>	
		</div>
	</div>
</div>
