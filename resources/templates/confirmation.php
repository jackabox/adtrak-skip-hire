<?php 

get_header();

	do_action('ash_wrapper_start');
	
	/**
	 * @ash_before_checkout_form
	 * @ash_checkout_form
	 * @ash_after_checkout_form
	 */
	do_action('ash_confirmation');

	do_action('ash_wrapper_end');
	
get_footer();