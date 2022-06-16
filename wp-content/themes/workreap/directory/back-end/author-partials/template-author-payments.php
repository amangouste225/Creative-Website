<?php
/**
 *
 * Author Payments Template.
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
global $profileuser, $woocommerce;

if (class_exists('WooCommerce')) {
	$user_identity		= $profileuser->ID;

if( apply_filters('workreap_is_listing_free', false,$user_identity) === false ){
	
	$current_date 		= current_time('mysql');
	$today				= strtotime($current_date);
	$user_role			= workreap_get_user_type( $user_identity );
	$package_id 		= workreap_get_subscription_metadata('subscription_id', $user_identity);
	
	$package_expiry 	= workreap_get_subscription_metadata('subscription_featured_string', $user_identity);
	$featured_expiry 	= workreap_get_subscription_metadata('subscription_featured_expiry', $user_identity);
	$package_title 		= esc_html( get_the_title($package_id));
	$package_title		= !empty($package_title) ? $package_title : esc_html__('Nill', 'workreap');
	
	$currency_symbol	= workreap_get_current_currency();
	$pakeges_features 	= workreap_get_pakages_features();
	$meta_query_args	= array();
	$packages_options 	= array();
	$args 				= array(
							'post_type' 			=> 'product',
							'posts_per_page' 		=> -1,
							'post_status' 			=> 'publish',
							'ignore_sticky_posts' 	=> 1
						);
	$meta_query_args[] = array(
							'key' 		=> 'package_type',
							'value' 	=> $user_role,
							'compare' 	=> '=',
						);
	
	//Include trial packages
	if( !empty($user_role) && $user_role === 'freelancer'){
		$meta_query_args[] = array(
							'key' 		=> 'package_type',
							'value' 	=> 'trail_freelancer',
							'compare' 	=> '=',
						);
	}elseif( !empty($user_role) && $user_role === 'employer'){
		$meta_query_args[] = array(
							'key' 		=> 'package_type',
							'value' 	=> 'trail_employer',
							'compare' 	=> '=',
						);
	}
	
	

	$query_relation 	= array('relation' => 'OR',);
	$meta_query_args 	= array_merge($query_relation, $meta_query_args);
	$args['meta_query'] = $meta_query_args;
	$packages 			= new WP_Query( $args );
	$profile_id			= workreap_get_linked_profile_id($user_identity);
?>
	<div class="wt-formtheme wt-dashboardbox dashboard-admin-pack" id="sp-pkgexpireyandcounter">
		<div class="sp-row">
			<div class="sp-xs-12 sp-sm-12 sp-md-6 sp-lg-6 pull-left">
				<div class="wt-pkgexpireyandcounter">
					<div class="wt-dashboardtitle">
						<h2><?php esc_html_e('Packages Settings', 'workreap'); ?></h2>
					</div>
				</div>
				<div class="wt-languagesbox">
					<div class="wt-pkgexpirey sp-pack-note">
						<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left">
							<p><?php esc_html_e('If you want to upgrade/change package then select package from drowdown and update it. Leave it empty to while updating user, otherwise selected package will be updated as user current package.', 'workreap'); ?></p>
							<?php if( !empty($user_role) && $user_role === 'freelancer'){?>
								<p><?php esc_html_e('If you would like to update user featured date then you can edit this user here', 'workreap'); ?>&nbsp;<?php edit_post_link( __( 'Edit', 'workreap' ), '', '', $profile_id, 'button btn-primary btn-edit-post-link' );?></p>
								
							<?php }?>
						</div>
					</div>
					<div class="wt-pkgexpirey sp-current-pack">
						<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left">
							<?php if ( !empty($package_title)) { ?>
								<h3><?php echo esc_html($package_title); ?></h3>
							<?php } ?>
							<div class="wt-timecounter wt-expireytimecounter">
								<div class="package-expireon">
									<?php if ( !empty($package_expiry) && $package_expiry > $today) { ?>
										<p><?php echo date_i18n(get_option('date_format'), $package_expiry); ?> <?php esc_html_e('at', 'workreap'); ?> <?php echo date_i18n('H:i A', $package_expiry); ?></p>
									<?php } else { ?>
										<?php if ( !empty($package_expiry)) { ?>
											<p><?php esc_html_e('This has expired and expiry date was:', 'workreap'); ?>&nbsp;<strong><?php echo date_i18n(get_option('date_format'), $package_expiry); ?> <?php esc_html_e('at', 'workreap'); ?> <?php echo date_i18n(get_option('time_format'), $package_expiry); ?></strong></p>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sp-row">
			<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left">
				<div class="wt-pkgexpireyandcounter">
					<div class="wt-dashboardtitle">
						<h2><?php esc_html_e('Available Packages', 'workreap'); ?></h2>
					</div>
				</div>
				<div class="wt-dashboardbox wt-languagesbox">
					<div class="wt-packagesbox">
						<div class="wt-dashboardboxcontent wt-packages">
							<?php if ( $packages->have_posts() ) {?>
							<div class="wt-package wt-packagedetails">
								<div class="wt-packagehead"></div>
								<div class="wt-packagecontent">
									<ul class="wt-packageinfo">
										<li class="wt-packageprices"><span><?php esc_html_e('Price','workreap');?></span></li>
										<?php foreach ( $pakeges_features as $key => $values ) { 
											if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
												<li><span><?php echo esc_html( $values['title']);?></span></li>
										<?php }}?>
									</ul>
								</div>
							</div>
							<?php 
								while ( $packages->have_posts() ) : $packages->the_post();
									global $product;					  
									$post_id 		= intval($product->get_id());
									$duration_type	= get_post_meta($post_id,'wt_duration_type',true);
									$duration_title = workreap_get_duration_types($duration_type,'title'); 
									$packages_options[$product->get_id()] = esc_html( get_the_title()); ?>
									<div class="wt-package wt-baiscpackage">
										<div class="wt-packagehead">
											<h3><?php echo esc_html( get_the_title()); ?></h3>
											<div class="packages-desc"><?php the_content();?></div>
										</div>
										<div class="wt-packagecontent">
											<ul class="wt-packageinfo">
												<li class="wt-packageprice">
													<span>
														<sup><?php echo esc_html($currency_symbol['symbol']);?></sup><?php echo esc_html($product->get_price()); ?><sub>\ <?php echo  esc_html($duration_title);?></sub>
													</span>
												</li>
												<?php 
													if ( !empty ( $pakeges_features )) {
														foreach( $pakeges_features as $key => $vals ) {
															if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
																do_action('workreap_print_pakages_features',$key,$vals,$post_id);
															}
														}
													}
												?>
											</ul>
										</div>
									</div>
								<?php
							endwhile;
							wp_reset_postdata();?>	
							<?php } else {
									do_action('workreap_empty_records_html','',esc_html__( 'No package has been made yet.', 'workreap' ),true);
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sp-row">
			<div class="sp-xs-12 sp-sm-12 sp-md-6 sp-lg-6 pull-left">
				<div class="wt-languagesbox">
					<div class="wt-startendtime">
						<div class="form-group">
							<span class="wt-select">
								<select name="package_id">
									<option value=""><?php esc_html_e('Select Package', 'workreap'); ?></option>
									<?php
										if (!empty($packages_options)) {
											$counter = 0;
											foreach ($packages_options as $key => $pack) {
												echo '<option value="' . $key . '">' . $pack . '</option>';
											}
										}
									?>

								</select>
							</span>
						</div>
						<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left"><p><?php esc_html_e('Leave it empty to while updating user, otherwise selected package will be updated as user current package.', 'workreap'); ?></p></div>
					</div>
				</div>
			</div>		
		</div>		
	</div>	
<?php }} ?>
