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
	 'method' 	=> 'wp_ajax_windscreen_edit_location', 
	 'uses' 	=> [$locations, 'updateLocation'] 
]);