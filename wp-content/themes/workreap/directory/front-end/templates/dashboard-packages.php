<?php
/**
 *
 * The template part for displaying Packages
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= workreap_get_linked_profile_id($user_identity);
$user_role			= workreap_get_user_type( $user_identity );
$currency_symbol	= workreap_get_current_currency();
$pakeges_features 	= workreap_get_pakages_features();
$meta_query_args	= array();
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

$query_relation 	= array('relation' => 'AND',);
$meta_query_args 	= array_merge($query_relation, $meta_query_args);
$args['meta_query'] = $meta_query_args;
$loop = new WP_Query( $args );
?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="wt-dashboardbox wt-packages">
		<div class="wt-dashboardboxtitle">
			<h2><?php esc_html_e('All packages','workreap');?></h2>
		</div>
		<?php 
		if ( class_exists('WooCommerce') ) {
			if ( $loop->have_posts() ) {?>
				<div class="wt-dashboardboxcontent wt-packages">
					<div class="wt-package wt-packagedetails">
						<div class="wt-packagehead"></div>
						<div class="wt-packagecontent">
							<ul class="wt-packageinfo">
								<li class="wt-packageprices"><span><?php esc_html_e('Price','workreap');?></span></li>
								<?php foreach ( $pakeges_features as $key => $values ) { 
									if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
										<li><span><?php echo esc_html($values['title']);?></span></li>
								<?php }}?>
							</ul>
						</div>
					</div>
					<?php
						while ( $loop->have_posts() ) : $loop->the_post();
							global $product;
							$post_id 		= intval($product->get_id());
							$duration_type	= get_post_meta($post_id,'wt_duration_type',true);
							$duration_title = workreap_get_duration_types($duration_type,'title');
							$get_price_html	= $product->get_price_html();
							$get_price_html = str_replace('span','sup',$get_price_html);
					
							?>
							<div class="wt-package wt-baiscpackage">
								<div class="wt-packagehead">
									<h3><?php echo esc_html(get_the_title()); ?></h3>
									<div class="packages-desc"><?php the_content();?></div>
								</div>
								<div class="wt-packagecontent">
									<ul class="wt-packageinfo">
										<li class="wt-packageprice">
											<div class="wt-packages-price"><?php echo do_shortcode($product->get_price_html()); ?><sub> <?php echo  esc_html($duration_title);?></sub></div>
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
									<a class="wt-btn renew-package" data-key="<?php echo intval($post_id);?>" href="#" onclick="event_preventDefault(event);"><span><?php esc_html_e('Buy Now','workreap');?></span></a>
								</div>
							</div>
						<?php
					endwhile;
					wp_reset_postdata();?>
				</div>	
				<?php
				} else {
					do_action('workreap_empty_records_html','',esc_html__( 'No package has been made yet.', 'workreap' ),true);
				}
			} else{
				do_action('workreap_empty_records_html','',esc_html__( 'WooCoomerce should be installed for payments. Please contact to administrator.', 'workreap' ),true);
			}
		?>	
	</div>
</div>