<?php namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Billy\Framework\Facades\DB;
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
			__( 'Windscreens', 'adwind' ),
			'Windscreens',
			'manage_options',
			'adwind',
			'',
			'',
			100
		);

		add_submenu_page(
			'adwind',			
			__( 'Locations', 'adwind' ),
			'Locations',
			'manage_options',
			'adwind',
			[$this, 'menu_render'],
			''
		);

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

	public function menu_render() 
	{
		$title = 'Hello From The Other Side';

		$lat = '52.9539591';
		$lng = '-1.1565018';
		$radius = 4;

		$locations = DB::table('aw_locations')
						->select(DB::raw('id, name, lat, lng, radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->get();
						
		View::render('locations.twig', [
			'title' 		=> $title,
			'locations' 	=> $locations
		]);
	}
}