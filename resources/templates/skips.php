<?php 

get_header(); 

	do_action('ash_wrapper_start'); 

	// skip loop, show 10 results, sort by name (Asc)
	do_action('ash_skip_loop', 10, 'name');

	do_action('ash_wrapper_end'); 
	
get_footer(); 
