<?php 

get_header();

	do_action('ash_wrapper_start'); 
	
	/**
	 * @ash_before_cart_form
	 * @ash_cart_form
	 * @ash_after_cart_form
	 */
	do_action('ash_cart');

	do_action('ash_wrapper_end');
	
get_footer();
