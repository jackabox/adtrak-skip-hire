<?php 
namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Location;
use Billy\Framework\Facades\DB;

class LocationController
{
	private static $instance = null;

	public function __construct() {
		 add_shortcode('adtrak_skips', [$this, 'showLocationForm']);
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_submenu_page(
			'adskip',			
			__( 'Locations', 'adskip' ),
			'Locations',
			'manage_options',
			'adskip',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Locations', 'adskip' ),
			'Locations - Add',
			'manage_options',
			'adskip-loc-add',
			[$this, 'addLocation'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Locations', 'adskip' ),
			'Locations - Edit',
			'manage_options',
			'adskip-loc-edit',
			[$this, 'showLocation'],
			''
		);
	}

	public function index() 
	{
		$title = 'Locations';
		$locations = Location::all();

		$link = [
			'edit' => admin_url('admin.php?page=adskip-loc-edit&loc-id='),
			'add' => admin_url('admin.php?page=adskip-loc-add')
		];

		View::render('locations.twig', [
			'title' 		=> $title,
			'locations' 	=> $locations,
			'link'			=> $link
		]);
	}

	public function showLocation()
	{
		$button = [
			'save' => '',
			'delete' => ''
		];

		if (current_user_can('edit_posts')) {
            $nonce = wp_create_nonce('skip_edit_location_nonce');
            $button['save'] = '<a href="' . admin_url( 'admin-ajax.php?action=skip_edit_location&id=' . $_GET['loc-id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['loc-id'] . '" data-nonce="' . $nonce . '" class="button adskip-edit-location">Save</a>';
        }

 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('skip_delete_location_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=skip_delete_location&id=' . $_GET['loc-id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['loc-id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=adwind') . '" class="adskip-delete-location">Delete</a>';
		}

		$location = Location::find($_GET['loc-id']);

		if ($location) {
			View::render('location-edit.twig', [
				'location' 	=> $location,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the location you're looking for does not exist.";
		}
	}

	public function updateLocation()
	{
		$permission = check_ajax_referer('skip_edit_location_nonce', 'nonce', false);
	
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
            $nonce = wp_create_nonce('skip_add_location_nonce');
            $button['save'] = '<a href="' . admin_url('admin-ajax.php?action=skip_add_location&nonce=' . $nonce) . '" data-nonce="' . $nonce . '" class="button adskip-add-location">Save</a>';
        } else {
			$button['save'] = '';
		}

		View::render('location-add.twig', [
			'button'	=> $button
		]);
	}

	public function storeLocation()
	{
		$permission = check_ajax_referer('skip_add_location_nonce', 'nonce', false);

        if ($permission == false) {
            echo 'Permission Denied';
        } else {
			if (empty($_REQUEST['name']) || empty($_REQUEST['lat']) || empty($_REQUEST['lng']) || empty($_REQUEST['radius'])) {
				echo 'Please enter a name, location and radius.';
				die();
			}

			try {
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
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }

        die();
	}

	public function deleteLocation()
	{
		$permission = check_ajax_referer('skip_delete_location_nonce', 'nonce', false);

        if ($permission == false) {
            echo 'Permission Denied';
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
		$lat = $_POST['lat'];
		$lng = $_POST['lng'];
		$radius = 50;

		try {
			$location = DB::table('as_locations')
						->select(DB::raw('id, name, lat, lng, radius, address, description, number, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', 50)
						->orderBy('distance')
						->first();

			if ($location && ($location->distance <= $location->radius)) {
				View::render('location-result.twig', [
					'location' => $location
				]);
			} else {
				echo "Sorry, we couldn't find any services in your location.";
			}		
		} catch (Exception $e) {
			echo "Sorry, something went wrong. Please try again.";
		}
	}
}