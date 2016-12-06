<?php namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Adtrak\Windscreens\Models\Location;

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
			__( 'Menu Page', 'textdomain' ),
			'Menu Page',
			'manage_options',
			'plugin-admin',
			[$this, 'menu_render'],
			'',
			100
		);
	}

	public function menu_render() 
	{
		$title = 'Hello From The Other Side';
		$locations = Location::all();

		View::render('locations.twig', [
			'title' 		=> $title,
			'locations' 	=> $locations
		]);
	}

	public function create_test_emails()
	{
		// Demo::create([
		// 	'email' => 'example@test.com'
		// ]);
	}
}