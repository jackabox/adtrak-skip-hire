<?php namespace Adtrak\Skips;

$admin = Controllers\AdminController::instance();
$locations = Controllers\LocationController::instance();
$skips = Controllers\SkipController::instance();
$permits = new Controllers\Admin\PermitController;
$coupons = new Controllers\Admin\CouponController;

$loader->action([
	'method' 	=> 'admin_menu',
	'uses' 		=> [$admin, 'menu']
]);

$loader->action([
	'method' 	=> 'admin_enqueue_scripts',
	'uses' 		=> [$admin, 'scripts']
]);

$loader->action([
	 'method' 	=> 'wp_ajax_ash_location_delete', 
	 'uses' 	=> [$locations, 'deleteLocation'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_skip_delete', 
	 'uses' 	=> [$skips, 'deleteSkip'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_ash_permit_delete', 
	 'uses' 	=> [$permits, 'destroy'] 
]);

$loader->action([
	 'method' 	=> 'wp_ajax_ash_coupon_delete',
	 'uses' 	=> [$coupons, 'destroy']
]);

/**
 * FRONT SCRIPTS/ACTIONS
 */
$front = Controllers\FrontController::instance();
 
$loader->action([
	'method' 	=> 'wp_enqueue_scripts',
	'uses' 		=> [$front, 'scripts']
]);
