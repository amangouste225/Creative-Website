<?php
/**
 *
 * The template used for displaying audio post formate
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $paged,$wp_query;
$search_show_posts    = get_option('posts_per_page');
?>
<div class="blog-list-view-template">
	<?php 
	while (have_posts()) : the_post();
		global $post;
		$width 		= 730;
		$height 	= 240;
		$thumbnail  = workreap_prepare_thumbnail($post->ID , $width , $height);
		
		$enable_author = '';
		if (function_exists('fw_get_db_post_option')) {
			$enable_author = fw_get_db_post_option($post->ID, 'enable_author', true);
		}
		
		$stickyClass = '';
		if (is_sticky()) {
			$stickyClass = 'sticky';
		}
		?>                         
		<article class="wt-article">
			<?php if( !empty( $thumbnail ) ){?>
				<figure class="wt-classimg">
					<?php workreap_get_post_thumbnail($thumbnail,$post->ID,'linked');?>
				</figure>
			<?php }?>
			<div class="wt-articlecontent">
				<div class="wt-title">
					<h3><?php workreap_get_post_title($post->ID); ?></h3>
				</div>
				<ul class="wt-postarticlemeta">
					<li><?php workreap_get_post_date($post->ID);?></li>
					<?php if (isset($enable_author) && $enable_author === 'enable') { ?>
						<li>
							<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
								<i class="lnr lnr-user"></i>
								<span><?php echo get_the_author(); ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			    <div class="wt-description">
					<p><?php echo get_the_excerpt(); ?></p>
				</div>
				<?php if (is_sticky()) {?>
					<span class="sticky-wrap wt-themetag wt-tagclose"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp;<?php esc_html_e('Featured','workreap');?></span>
				<?php }?>
			</div>
		</article>
	<?php
	endwhile;
	wp_reset_postdata();
	$qrystr = '';
	if ($wp_query->found_posts > $search_show_posts) {?>
		<div class="theme-nav">
			<?php 
				if (function_exists('workreap_prepare_pagination')) {
					echo workreap_prepare_pagination($wp_query->found_posts , $search_show_posts);
				}
			?>
		</div>
	<?php }?>
</div>