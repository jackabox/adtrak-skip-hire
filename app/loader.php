<?php namespace Adtrak\Windscreens;

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
	 'method' 	=> 'wp_ajax_windscreen_add_location', 
	 'uses' 	=> [$locations, 'storeLocation'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_windscreen_edit_location', 
	 'uses' 	=> [$locations, 'updateLocation'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_windscreen_delete_location', 
	 'uses' 	=> [$locations, 'deleteLocation'] 
]);