<?php 
namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Adtrak\Windscreens\Models\Location;
use Billy\Framework\Facades\DB;

class LocationController
{
	private static $instance = null;

	public function __construct() {
		 add_shortcode('adtrak_windscreens', [$this, 'showLocationForm']);
	}

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
			'Locations - Add',
			'manage_options',
			'adwind-loc-add',
			[$this, 'addLocation'],
			''
		);

		add_submenu_page(
			'adwind',			
			__( 'Locations', 'adwind' ),
			'Locations - Edit',
			'manage_options',
			'adwind-loc-edit',
			[$this, 'showLocation'],
			''
		);
	}

	public function index() 
	{
		$title = 'Locations';
		$locations = Location::all();

		$link = [
			'edit' => admin_url('admin.php?page=adwind-loc-edit&loc-id='),
			'add' => admin_url('admin.php?page=adwind-loc-add')
		];

		View::render('locations.twig', [
			'title' 		=> $title,
			'locations' 	=> $locations,
			'link'			=> $link
		]);
	}

	public function showLocation()
	{
		if (current_user_can('edit_posts')) {
            $nonce = wp_create_nonce('windscreen_edit_location_nonce');
            $button['save'] = '<a href="' . admin_url( 'admin-ajax.php?action=windscreen_edit_location&id=' . $_GET['loc-id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['loc-id'] . '" data-nonce="' . $nonce . '" class="button adwi-edit-location">Save</a>';
        } else {
			$button['save'] = '';
		}

 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('windscreen_delete_location_nonce');
			$button['delete'] = ' <a href="' . admin_url( 'admin-ajax.php?action=windscreen_delete_location&id=' . $_GET['loc-id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['loc-id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=adwind') . '" class="adwi-delete-location">Delete</a>';
		} else {
			$button['delete'] = '';
		}

		$location = Location::find($_GET['loc-id']);

		View::render('location-edit.twig', [
			'location' 	=> $location,
			'button'	=> $button
		]);
	}

	public function updateLocation()
	{
		$permission = check_ajax_referer('windscreen_edit_location_nonce', 'nonce', false);
	
        if ($permission == false) {
            echo 'error';
        } else {
			$loc 				= Location::findOrFail($_REQUEST['id']);
			$loc->name 			= $_REQUEST['name'];
			$loc->description 	= $_REQUEST['desc'];
			$loc->number 		= $_REQUEST['phone'];
			$loc->address 		= $_REQUEST['location'];
			$loc->lat 			= $_REQUEST['lat'];
			$loc->lng 			= $_REQUEST['lng'];
			$loc->radius 		= $_REQUEST['radius'];
			$loc->save();

			echo 'success';
        }

        die();
	}

	public function addLocation() 
	{
		if (current_user_can('edit_posts')) {
            $nonce = wp_create_nonce('windscreen_add_location_nonce');
            $button['save'] = '<a href="' . admin_url('admin-ajax.php?action=windscreen_add_location&nonce=' . $nonce) . '" data-nonce="' . $nonce . '" class="button adwi-add-location">Save</a>';
        } else {
			$button['save'] = '';
		}

		View::render('location-add.twig', [
			'button'	=> $button
		]);
	}

	public function storeLocation()
	{
		$permission = check_ajax_referer('windscreen_add_location_nonce', 'nonce', false);

        if ($permission == false) {
            echo 'error';
        } else {
			if (empty($_REQUEST['name']) || empty($_REQUEST['lat']) || empty($_REQUEST['lng']) || empty($_REQUEST['radius'])) {
				echo 'error';
				die();
			}

			$loc 				= new Location;
			$loc->name 			= $_REQUEST['name'];
			$loc->description 	= $_REQUEST['desc'];
			$loc->number 		= $_REQUEST['phone'];
			$loc->address 		= $_REQUEST['location'];
			$loc->lat 			= $_REQUEST['lat'];
			$loc->lng 			= $_REQUEST['lng'];
			$loc->radius 		= $_REQUEST['radius'];
			$loc->save();

			echo 'success';
        }

        die();
	}

	public function deleteLocation()
	{
		$permission = check_ajax_referer('windscreen_delete_location_nonce', 'nonce', false);

        if ($permission == false) {
            echo 'error';
        } else {
			$loc = Location::findOrFail($_REQUEST['id']);
			$loc->delete();
			echo 'success';
		}

        die();
	}

	public function showLocationForm()
	{	
		if ($_POST) {
			 $this->frontGetLocation();
		} else {
			View::render('location-lookup.twig', []);		
		}
	}

	public function frontGetLocation()
	{
		// $lat = $_POST['lat'];
		// $lng = $_POST['lng'];

		$lat = '52.9539591';
		$lng = '-1.1565018';
		$radius = 50;

		$location = DB::table('aw_locations')
						->select(DB::raw('id, name, lat, lng, radius, address, number, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', 50)
						->orderBy('distance')
						->first();

		View::render('location-result.twig', [
			'location' => $location
		]);		
						
	}
}