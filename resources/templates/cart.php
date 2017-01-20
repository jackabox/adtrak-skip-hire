<?php get_header(); ?>

	<?php do_action('ash_wrapper_start'); ?>

	<div class="ash-page-header">
		<h2>Cart</h2>
		<!-- Location search description -->
	</div>
	
	<?php 
	/**
	 * @ash_before_cart_form
	 * @ash_cart_form
	 * @ash_after_cart_form
	 */
	do_action('ash_cart'); ?>

	<?php do_action('ash_wrapper_end'); ?>
	
<?php get_footer(); ?>