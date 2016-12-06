<?php namespace Adtrak\Windscreens;

$admin = Controllers\AdminController::instance();

$loader->action([
	'method' 	=> 'admin_menu',
	'uses' 		=> [$admin, 'menu']
]);