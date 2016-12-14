<?php namespace Adtrak\Skips;

$admin = Controllers\AdminController::instance();
$locations = Controllers\LocationController::instance();

$loader->action([
	'method' 	=> 'admin_menu',
	'uses' 		=> [$admin, 'menu']
]);

$loader->action([
	'method' 	=> 'admin_enqueue_scripts',
	'uses' 		=> [$admin, 'scripts']
]);

$loader->action([
	 'method' 	=> 'wp_ajax_skip_add_location', 
	 'uses' 	=> [$locations, 'storeLocation'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_skip_edit_location', 
	 'uses' 	=> [$locations, 'updateLocation'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_skip_delete_location', 
	 'uses' 	=> [$locations, 'deleteLocation'] 
]);

/**
 * FRONT SCRIPTS/ACTIONS
 */
$front = Controllers\FrontController::instance();
 
$loader->action([
	'method' 	=> 'wp_enqueue_scripts',
	'uses' 		=> [$front, 'scripts']
]);
