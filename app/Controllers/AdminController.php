<?php namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Adtrak\Windscreens\Models\Location;

use Adtrak\Windscreens\Controllers\LocationController;


class AdminController
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_menu_page(
			__( 'Windscreens', 'adwind' ),
			'Windscreens',
			'manage_options',
			'adwind',
			'',
			'none',
			100
		);

		$locs = LocationController::instance();
		$locs->menu();

		add_submenu_page(
			'adwind',			
			__( 'Settings', 'adwind' ),
			'Settings',
			'manage_options',
			'adwind-settings',
			[$this, 'menu_render'],
			''
		);
	}
}