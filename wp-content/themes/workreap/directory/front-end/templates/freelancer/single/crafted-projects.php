<?php
/**
 *
 * The template used for displaying freelancer Crafted Projects
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 			= $post->ID;
$cr_project_limit	= 6;
if (function_exists('fw_get_db_post_option')) {
	$projects 		= fw_get_db_post_option($post_id, 'projects', true);
	$freelancer_project	= fw_get_db_settings_option('fr_project_placeholder');
} else {
	$projects		= array();
}

$project_placeholder	= !empty($freelancer_project['url']) ? $freelancer_project['url'] : '';

if( !empty( $projects ) && is_array( $projects ) ){?>
	<div class="wt-craftedprojects">
		<div class="wt-usertitle">
			<h2><?php esc_html_e('Crafted Projects','workreap');?></h2>
		</div>
		<div class="wt-projects wt-haslayout">
		<?php 
			$total_projects	= !empty($projects) ? count(array_filter($projects))  : 0;
			$count_itme		= 0;
			foreach( $projects as $key => $item ){ 
				$count_itme ++;
				$title		= !empty($item['title']) ? $item['title'] : "";
				$link		= !empty($item['link']) ? $item['link'] : "";
				$image_url	= !empty($item['image']['url']) ? $item['image']['url'] : $project_placeholder;

				$filetype   = !empty( $image_url ) ? wp_check_filetype( $image_url ) : '';
				$extension  = !empty( $filetype['ext'] ) ? $filetype['ext'] : '';	
				if( !empty($extension) && $extension === 'pdf' ){
					$defult_pdf_image	= get_template_directory_uri() . '/images/pdf.jpg';
				}

				$item_show	= !empty($count_itme) && intval($count_itme) > $cr_project_limit ? 'd-none' : "";
			?>
			<div class="wt-project wt-crprojects <?php echo do_shortcode( $item_show );?>">
				<?php if( !empty($extension) && $extension === 'pdf' && !empty($item['image']['url']) ){ ?>
					<figure>
						<a href="<?php echo esc_url($image_url) ?>" download><img src="<?php echo esc_url( $defult_pdf_image );?>" alt="<?php echo esc_attr($title);?>"></a>
					</figure>
				<?php } else if( !empty( $image_url) ){?>
					<figure>
						<img src="<?php echo esc_url( $image_url );?>" alt="<?php echo esc_attr($title);?>">
					</figure>
				<?php }?>
				<?php if( !empty( $title ) ) { ?>
					<div class="wt-projectcontent">
						<h3><?php echo esc_html(stripslashes($title));?></h3>
						<a target="_blank" href="<?php echo esc_url($link);?>"><?php echo esc_url($link);?></a>
					</div>
				<?php } ?>
			</div>
			<?php 
				} 
				if( intval($total_projects) > $cr_project_limit ){?>
				<div class="wt-btnarea">
					<a href="#" onclick="event_preventDefault(event);" class="wt-btn wt-loadmore-crprojects"><?php esc_html_e('Load More','workreap');?></a>
				</div>
			<?php }?>
		</div>
	</div>
<?php
}