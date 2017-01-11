<?php get_header(); ?>

	<?php do_action('ash_wrapper_start'); ?>

	<div class="ash-page-header">
		<h2>Please Select a Skip</h2>
		<!-- Location search description -->
	</div>

	<?php 
	do_action('ash_before_skip_loop');

		do_action('ash_skip_loop');

	do_action('ash_after_skip_loop'); 
	?>

	<?php do_action('ash_wrapper_end'); ?>
	
<?php get_footer(); ?>