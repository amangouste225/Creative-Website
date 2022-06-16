<?php
/**
 *
 * Theme Home Page
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header(); ?>
<div class="container">
    <div class="row">
		<div class="workreap-home-page haslayout">
			<?php get_template_part( 'template-parts/content', 'page' ); ?>
		</div>
    </div>
</div>
<?php get_footer(); ?>