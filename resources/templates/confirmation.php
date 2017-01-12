<?php get_header(); ?>

	<?php do_action('ash_wrapper_start'); ?>

	<div class="ash-page-header">
		<h2>Confirmation</h2>
		<!-- Location search description -->
	</div>
	
	<?php 
	/**
	 * @ash_before_checkout_form
	 * @ash_checkout_form
	 * @ash_after_checkout_form
	 */
	do_action('ash_confirmation'); ?>

	<?php do_action('ash_wrapper_end'); ?>
	
<?php get_footer(); ?>