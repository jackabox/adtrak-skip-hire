<?php /* 
		$lat = '52.9539591';
		$lng = '-1.1565018';
		$radius = 4;

		$locations = DB::table('aw_locations')
						->select(DB::raw('id, name, lat, lng, radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->get();
						*/ 

namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Adtrak\Windscreens\Models\Location;

class LocationController
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_submenu_page(
			'adwind',			
			__( 'Locations', 'adwind' ),
			'Locations',
			'manage_options',
			'adwind',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adwind',			
			__( 'Locations', 'adwind' ),
			'Locations - Edit',
			'manage_options',
			'adwind-loc-edit',
			[$this, 'updateLocation'],
			''
		);
	}

	public function index() 
	{
		$title = 'Locations';
		$locations = Location::all();

		$link = [
			'edit' => admin_url('admin.php?page=adwind-loc-edit&loc-id='),
			'delete' => admin_url('admin.php?page=adwind-loc-delete&loc-id=')
		];

		View::render('locations.twig', [
			'title' 		=> $title,
			'locations' 	=> $locations,
			'link'			=> $link
		]);
	}

	public function updateLocation()
	{
		$id = $_GET['loc-id'];
		$location = Location::find($id);
		View::render('location-edit.twig', [
			'location' 	=> $location
		]);
	}
}