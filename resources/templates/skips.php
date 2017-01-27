<?php 

get_header(); 

	do_action('ash_wrapper_start'); 

	do_action('ash_skip_loop', 10, 'name');

	do_action('ash_wrapper_end'); 
	
get_footer(); 
