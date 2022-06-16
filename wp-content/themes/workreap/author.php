<?php
/**
 *
 * archive Page
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();
$object_id 			= get_queried_object_id();
$udata      		= get_userdata($object_id);
$registered 		= $udata->user_registered;
$facebook	   		= get_user_meta( $object_id, 'facebook', true);
$twitter	   		= get_user_meta( $object_id, 'twitter', true);
$linkedin	   		= get_user_meta( $object_id, 'linkedin', true);
$pinterest	   		= get_user_meta( $object_id, 'pinterest', true);
$google_plus	    = get_user_meta( $object_id, 'google_plus', true);
$instagram	   		= get_user_meta( $object_id, 'instagram', true);
$tumblr	   			= get_user_meta( $object_id, 'tumblr', true);
$skype	   			= get_user_meta( $object_id, 'skype', true);
$user_post_count    = count_user_posts( $object_id , 'post' );
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-xs-12">
			<div class="wt-author">
				<div class="wt-authordetails">
					<figure><?php echo get_avatar($object_id, 150); ?></figure>
					<div class="wt-authorcontent">
						<div class="wt-authorhead">
							<div class="wt-boxleft">
								<h3><?php echo get_the_author(); ?></h3>
								<span><?php esc_html_e('Author Since', 'workreap'); ?>:&nbsp;<?php echo date_i18n(get_option('date_format'), strtotime($registered)); ?></span> 
							</div>
							<?php
							$facebook  = get_the_author_meta('facebook', $object_id);
							$twitter   = get_the_author_meta('twitter', $object_id);
							$pinterest = get_the_author_meta('pinterest', $object_id);
							$linkedin  = get_the_author_meta('linkedin', $object_id);
							$tumblr    = get_the_author_meta('tumblr', $object_id);
							$google    = get_the_author_meta('google', $object_id);
							$instagram = get_the_author_meta('instagram', $object_id);
							$skype     = get_the_author_meta('skype', $object_id);

							if (!empty($facebook) || 
								!empty($twitter) || 
								!empty($pinterest) || 
								!empty($linkedin) || 
								!empty($tumblr) || 
								!empty($google) || 
								!empty($instagram) 
								|| !empty($skype) ) {
								?>
								<div class="wt-boxright">
									<ul class="wt-socialiconssimple">
										<?php if (!empty($facebook)) { ?>
											<li class="wt-facebook">
												<a href="<?php echo esc_url($facebook); ?>">
													<i class="fa fa-facebook-f"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($twitter)) { ?>
											<li class="wt-twitter">
												<a href="<?php echo esc_url($twitter); ?>">
													<i class="fa fa-twitter"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($pinterest)) { ?>
											<li class="wt-dribbble">
												<a href="<?php echo esc_url($pinterest); ?>">
													<i class="fa fa-pinterest-p"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($linkedin)) { ?>
											<li class="wt-linkedin">
												<a href="<?php echo esc_url($linkedin); ?>">
													<i class="fa fa-linkedin"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($tumblr)) { ?>
											<li class="wt-tumblr">
												<a href="<?php echo esc_url($tumblr); ?>">
													<i class="fa fa-tumblr"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($google)) { ?>
											<li class="wt-googleplus">
												<a href="<?php echo esc_url($google); ?>">
													<i class="fa fa-google"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($instagram)) { ?>
											<li class="wt-dribbble">
												<a href="<?php echo esc_url($instagram); ?>">
													<i class="fa fa-instagram"></i>
												</a>
											</li>
										<?php } ?>
										<?php if (!empty($skype)) { ?>
											<li  class="wt-skype">
												<a href="<?php echo esc_url($skype); ?>">
													<i class="fa fa-skype"></i>
												</a>
											</li>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
						</div>
						<?php if ( get_the_author_meta( 'description',$object_id ) ) : ?>
							<div class="wt-description"><p><?php the_author_meta( 'description',$object_id ); ?></p></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="wt-authorpostlist">
			<?php get_template_part( 'template-parts/content', 'page' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>