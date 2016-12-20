<?php 
namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Location;
use Billy\Framework\Facades\DB;

class LocationController
{
	private static $instance = null;

	public function __construct() {
		 add_shortcode('ash_location_lookup', [$this, 'showLocationForm']);
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
			'ash-location',
			[$this, 'index']
		);

		add_submenu_page(
			'adskip',			
			__( 'Add Location', 'adskip' ),
			'Locations - Add',
			'manage_options',
			'ash-location-add',
			[$this, 'addLocation']
		);

		add_submenu_page(
			'adskip',			
			__( 'Edit Location', 'adskip' ),
			'Locations - Edit',
			'manage_options',
			'ash-location-edit',
			[$this, 'showLocation']
		);
	}

	public function index() 
	{
		$locations = Location::all();

		$link = [
			'edit' => admin_url('admin.php?page=ash-location-edit&id='),
			'add' => admin_url('admin.php?page=ash-location-add')
		];

		View::render('admin/locations/index.twig', [
			'locations' 	=> $locations,
			'link'			=> $link
		]);
	}

	public function addLocation() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'location_add') {
			$this->storeLocation();
		}

		View::render('admin/locations/add.twig', []);
	}

	public function storeLocation()
	{
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

        if ($permission == false) {
            echo 'Permission Denied';
        } else if (!empty($errors)) {
			echo '<ul>';
			foreach($errors as $error) {
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		} else {	
			try {
				$loc 				= new Location;
				$loc->name 			= $_REQUEST['title'];
				$loc->description 	= $_REQUEST['description'];
				$loc->address 		= $_REQUEST['location'];
				$loc->lat 			= $_REQUEST['as_lat'];
				$loc->lng 			= $_REQUEST['as_lng'];
				$loc->radius 		= $_REQUEST['radius'];
				$loc->save();

				$url = admin_url('admin.php?page=ash-location-edit&id=' . $loc->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }

        die();
	}

	public function showLocation()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'location_update') {
			$this->updateLocation();
		}

 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_location_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url('admin-ajax.php?action=location_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-location') . '" class="ash-location-delete">Delete</a>';
		} else {
			$button['delete'] = '';
		}

		$location = Location::find($_GET['id']);

		if ($location) {
			View::render('admin/locations/edit.twig', [
				'location' 	 => $location,
				'button'	 => $button
			]);
		} else {
			echo "Sorry, the location you're looking for does not exist.";
		}
	}

	public function updateLocation()
	{
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

        if ($permission == false) {
            echo 'error';
        } else if (!empty($errors)) {
			echo '<ul>';
			foreach($errors as $error) {
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		} else {
			try {
				$loc 				= Location::findOrFail($_REQUEST['id']);
				$loc->name 			= $_REQUEST['title'];
				$loc->description 	= $_REQUEST['description'];
				$loc->address 		= $_REQUEST['location'];
				$loc->lat 			= $_REQUEST['as_lat'];
				$loc->lng 			= $_REQUEST['as_lng'];
				$loc->radius 		= $_REQUEST['radius'];
				$loc->save();

				echo "Location has been updated";
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function deleteLocation()
	{
		$permission = check_ajax_referer('ash_location_delete_nonce', 'nonce', false);

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
			View::render('front/location-lookup.twig', []);		
		}
	}

	public function frontGetLocation()
	{
		$lat    = $_POST['lat'];
		$lng    = $_POST['lng'];
		$radius = 50;

		try {
			$location = DB::table('as_locations')
						->select(DB::raw('id, name, lat, lng, radius, address, description, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', 50)
						->orderBy('distance')
						->first();

			if ($location && ($location->distance <= $location->radius)) {
				View::render('front/location-result.twig', [
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