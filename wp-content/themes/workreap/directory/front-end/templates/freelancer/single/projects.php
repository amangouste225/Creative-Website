<?php
/**
 *
 * The template used for displaying freelancer completed projects
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 					= $post->ID;
$user_id					= workreap_get_linked_profile_id($post_id,'post'); 

if(empty($user_id)){return;}

$show_posts		= 3;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged 			= max($pg_page, $pg_paged);
$order 			= 'DESC';
$sorting 		= 'ID';

$args 			= array(
						'posts_per_page' 	=> $show_posts,
						'post_type' 		=> 'reviews',
						'orderby' 			=> $sorting,
						'order' 			=> $order,
						'author' 			=> $user_id,
						'paged' 			=> $paged,
						'suppress_filters' 	=> false
					);
$query 			= new WP_Query($args);
$count_post = $query->found_posts;
if( $query->have_posts() ){
?>
<div class="wt-clientfeedback">
	<div class="wt-haslayout review-wrap">
		<div class="wt-usertitle wt-titlewithselect">
			<h2><?php esc_html_e('Client Feedback','workreap');?></h2>
		</div>
		<?php
			$counter	= 0;
			while ($query->have_posts()) : $query->the_post();
				global $post;
				$counter ++;
				$project_id			= get_post_meta($post->ID, '_project_id', true);
				$project_rating		= get_post_meta($post->ID, 'user_rating', true);
				$employer_id		= get_post_field('post_author',$project_id);
				$company_profile 	= workreap_get_linked_profile_id($employer_id);
				$employer_title 	= esc_html( get_the_title( $company_profile ) );
				$project_title		= esc_html( get_the_title($project_id) );

				$company_avatar 	= apply_filters(
										'workreap_employer_avatar_fallback', workreap_get_employer_avatar( array( 'width' => 100, 'height' => 100 ), $company_profile ), array( 'width' => 225, 'height' => 225 )
									);
				$bg_class			= !empty($counter) && intval($counter)%2 === 0 ? '' : 'wt-bgcolor'; 
			?>
			<div class="wt-userlistinghold wt-userlistingsingle <?php echo esc_attr($bg_class);?>">	
				<div class="wt-userfeedback-head">
					<figure class="wt-userlistingimg">
						<img src="<?php echo esc_url( $company_avatar );?>" alt="<?php esc_attr_e('Company','workreap');?>" >
					</figure>
					<div class="wt-userlistingcontent">
						<div class="wt-contenthead">
							<div class="wt-title">
								<?php do_action( 'workreap_get_verification_check', $company_profile, $employer_title ); ?>
								<h3><?php echo esc_html($project_title);?></h3>
							</div>
							<ul class="wt-userlisting-breadcrumb">
								<?php do_action('workreap_project_print_project_level', $project_id); ?>
								<?php do_action('workreap_print_location', $project_id); ?>
								<?php do_action('workreap_post_date', $post->ID); ?>
								<?php do_action('workreap_freelancer_get_project_rating', $project_rating,$post->ID); ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="wt-description">
					<p><?php echo get_the_content();?></p>
				</div>
			</div>
			<?php
			endwhile;
			wp_reset_postdata();
		?>
	</div>
	<?php if (!empty($count_post) && $count_post > $show_posts) {?>
		<div class="wt-btnarea">
			<a href="#" onclick="event_preventDefault(event);" class="wt-btn load-more-reviews" data-id="<?php echo intval($user_id);?>"><?php esc_html_e('Load More','workreap');?></a>
		</div>
	<?php } ?>
</div>
<?php } ?>