<?php
/**
 *
 * The template part for displaying a message that posts cannot be found.
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
?>
<section class="no-results not-found">
	<header class="page-header"><h1 class="page-title"><?php esc_html_e('Nothing Found' , 'workreap'); ?></h1></header>
	<div class="page-content">
		<?php if (is_home() && current_user_can('publish_posts')) : ?>
			<p>
				<?php
					printf(wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.' , 'workreap') , array (
						'a' => array (
							'href' => array () ) )) , esc_url(admin_url('post-new.php')));
				?>
			</p>
		<?php elseif (is_search()) : ?>
			<p><?php Workreap_Prepare_Notification::workreap_info( esc_html__('Sorry, but nothing matched your search terms. Please try again with some different keywords.' , 'workreap') );?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' , 'workreap'); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
	
