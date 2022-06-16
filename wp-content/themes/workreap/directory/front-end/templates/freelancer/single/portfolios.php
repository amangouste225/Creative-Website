<?php
/**
 *
 * The template used for displaying freelancer Portfolios
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $current_user, $post, $paged;

$user_identity 	 	 = get_post_meta($post->ID, '_linked_profile', true);
$portfolios_limit 	 = intval(5);

$order 		= 'DESC';
$sorting 	= 'ID';

$args = array(
	'posts_per_page'    => -1,
	'nopaging'			=> true, 
    'post_type' 		=> 'wt_portfolio',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'post_status' 		=> array('publish'),
	'author' 			=> $user_identity,
    'suppress_filters'  => false
);

$query 				= new WP_Query($args);
$total_portfolios 	= $query->found_posts;
?>
<div class="wt-craftedprojects">
	<div class="wt-usertitle">
		<h2><?php esc_html_e('Portfolios','workreap');?></h2>
	</div>
	<div class="wt-projects wt-haslayout">
	<?php 
		if ($query->have_posts()) {
			$count_item		= 0;
			while ($query->have_posts()) : $query->the_post();
				global $post;

				$post_id 	= $post->ID;
				$title		= get_the_title($post_id);
				$image_url	= get_the_post_thumbnail_url($post_id, 'workreap_portfolio_thumbnail');

				$link = '';
			
				if (function_exists('fw_get_db_post_option')) {
					$link		= fw_get_db_post_option($post->ID, 'custom_link', true);
				}

				$item_show	= $count_item > $portfolios_limit ? 'style="display: none;"' : ""; 
				?>
				<div class="wt-project wt-portfolios" <?php echo do_shortcode($item_show); ?> >
					<?php if (!empty($image_url)) {?>
						<figure>
							<a class="modal-link" href="<?php echo esc_url(get_the_permalink());?>"><img src="<?php echo esc_url($image_url);?>" alt="<?php echo esc_attr($title);?>"></a>
						</figure>
					<?php } ?>
					<?php if (!empty($title)) { ?>
						<div class="wt-projectcontent">
							<h3><a class="modal-link" href="<?php echo esc_url(get_the_permalink());?>"><?php echo esc_html(stripslashes($title));?></a></h3>
						</div>
					<?php } ?>
				</div>
			<?php
			$count_item ++;
			endwhile;
				wp_reset_postdata();
                if (intval($portfolios_limit) > $portfolios_limit) {?>
				<div class="wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-loadmore-portfolios"><?php esc_html_e('Load More', 'workreap');?></a>
				</div>
		<?php }}?>
	</div>
</div>