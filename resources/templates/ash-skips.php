<?php get_header(); ?>

<div class="ash-page-header">
	<h2>Please Select a Skip</h2>
	<!-- Location search description -->
</div>

<!-- do action  before get skips -->
<?php do_action('ash_before_skip_loop'); ?>

	<!-- do action  skip content -->
	<?php do_action('ash_skip_loop'); ?>

<!-- do action  after get skips -->
<?php do_action('ash_after_skip_loop'); ?>

<?php get_footer(); ?>