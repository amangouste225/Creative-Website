<?php
/**
 *
 * The template used for displaying image post formate
 *
 * @package   Workreap
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
global $post,$thumbnail;
?>
<figure class="wt-singleimg-one">
	<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" >
</figure>