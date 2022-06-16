<?php
/**
 *
 * The template used for displaying freelancer post basics
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user;

$service_id		= $post->ID;
$show_posts 	= 3;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';

$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'services-orders',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('completed'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);

$meta_query_args[] = array(
						'key' 		=> '_service_id',
						'value' 	=> $service_id,
						'compare' 	=> '='
					);
$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);
$query 				= new WP_Query($args);
$count_post 		= $query->found_posts;

?>
<?php if( $query->have_posts() ){ ?>
<div class="wt-clientfeedback">
	<div class="wt-usertitle wt-titlewithselect">
		<h2><?php esc_html_e('Review(s)','workreap');?></h2>
	</div>
	<div class="wt-reviews">
		<?php
			while ($query->have_posts()) : $query->the_post();
				global $post;
				$author_id 		= get_the_author_meta( 'ID' );  
				$linked_profile = workreap_get_linked_profile_id($author_id);
				$tagline		= workreap_get_tagline($linked_profile);
				$employer_title = get_the_title( $linked_profile );
				$employer_avatar = apply_filters(
									'workreap_employer_avatar_fallback', workreap_get_employer_avatar(array('width' => 100, 'height' => 100), $linked_profile), array('width' => 100, 'height' => 100) 
								);
				$service_ratings	= get_post_meta($post->ID,'_hired_service_rating',true);

				if( function_exists('fw_get_db_post_option') ) {
					$feedback	 		= fw_get_db_post_option($post->ID, 'feedback');
				}
				?>
				<div class="wt-userlistinghold  wt-userlistingsingle review-wrap">	
					<?php if( !empty( $employer_avatar ) ){?>
						<figure class="wt-userlistingimg">
							<img src="<?php echo esc_url( $employer_avatar );?>" alt="<?php echo esc_attr($employer_title);?>">
						</figure>
					<?php } ?>
					<div class="wt-userlistingcontent">
						<div class="wt-contenthead">
							<div class="wt-title">
								<?php do_action( 'workreap_get_verification_check', $linked_profile, $employer_title ); ?>
								<?php if( !empty( $tagline ) ) {?>
									<h3><?php echo esc_html( $tagline );?></h3>
								<?php } ?>
							</div>
							<ul class="wt-userlisting-breadcrumb">
								<?php do_action('workreap_print_location', $linked_profile); ?>
								<li class="wt-overallratingarea"><?php do_action('workreap_freelancer_single_service_rating', $service_ratings,$post->ID ); ?></li>
							</ul>
						</div>
					</div>
					<?php if( !empty( $feedback ) ){?>
						<div class="wt-description">
							<p>“<?php echo esc_html( $feedback );?>”</p>
						</div>
					<?php  }?>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();?>
	</div>
	<?php if (!empty($count_post) && $count_post > $show_posts) {?>
		<div class="wt-btnarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-more-reviews wt-more-rating-service" data-id="<?php echo intval( $post->ID );?>"><?php esc_html_e('Load More','workreap');?></a>
		</div>
	<?php }?>
</div>
<?php }?>
