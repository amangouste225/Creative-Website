<?php
/**
 *
 * The template part for Current Package poupup
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$woocommerce;
if (class_exists('WooCommerce')) {
	$user_identity 	 	= $current_user->ID;
	$linked_profile  	= workreap_get_linked_profile_id($user_identity);

	$product_id			= workreap_get_subscription_metadata( 'subscription_id',intval($user_identity) );
	$title				= esc_html( get_the_title($product_id));
	$title				= !empty( $title ) ? esc_html( $title ) : esc_html__('nill','workreap');
	$user_role			= workreap_get_user_type( $user_identity );
	$pakeges_features 	= workreap_get_pakages_features();
	?>
	<div class="wt-uploadimages wt-package-modal modal fade" id="wt-package-details" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="wt-modaldialog modal-dialog" role="document">
			<div class="wt-modalcontent modal-content">
				<div class="wt-boxtitle">
					<h2><?php echo esc_html($title);?><i class=" wt-btncancel fa fa-times" data-dismiss="modal" aria-label="Close"></i></h2>
				</div>
				<div class="wt-modalbody modal-body">
					<div class="wt-dashboardboxcontent wt-packages">
						<div class="wt-package wt-packagedetails">
							<div class="wt-packagecontent">
								<ul class="wt-packageinfo">
									<?php foreach ( $pakeges_features as $key => $values ) { 
										if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
											<li><span><?php echo esc_html($values['remaining']);?></span></li>
									<?php }}?>
								</ul>
							</div>
						</div>
						<div class="wt-package wt-baiscpackage">
							<div class="wt-packagecontent">
								<ul class="wt-packageinfo">
									<?php 
										if ( !empty ( $pakeges_features )) {
											foreach( $pakeges_features as $key => $vals ) { 
												if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
													$text	 = !empty( $vals['text'] ) ? $vals['text'] : '';
													$feature	= workreap_get_subscription_metadata($key,$user_identity);
													if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
														$feature = '<i class="ti-na"></i>';
													}elseif( $key	=== 'wt_duration_type') {
														$feature = workreap_get_duration_types($feature,'value');
													}elseif($key	=== 'wt_badget' ) {
														if(!empty($feature) ){
															$badges		= get_term( intval($feature) );
															if(!empty($badges->name)) {
																$feature	= $badges->name;
															} else {
																$feature	= '<i class="ti-na"></i>';
															}
														} else{
															$feature	= '<i class="ti-na"></i>';
														}
													}elseif( !empty( $feature ) && $feature === 'yes') {
														$feature	= '<i class="ti-check"></i>';
													} elseif( !empty( $feature ) && $feature === 'no') {
														$feature	= '<i class="ti-na"></i>';
													}
													
													$feature	= !empty( $feature ) ? $feature : '0';
													?>
														<li><span>
															<?php if( !empty( $vals ) ){?>
																<em><?php echo esc_html($vals['remaining']);?></em>
															<?php } ?>
															<?php echo do_shortcode($feature);?>&nbsp;<?php echo esc_html($text);?></span></li>
														<?php
												}
											}
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }