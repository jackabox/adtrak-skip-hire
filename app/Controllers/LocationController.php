<?php 
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
			'delete' => admin_url('admin.php?page=adwind-loc-delete&loc-id=')
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
			$button['delete'] = ' <a href="' . admin_url( 'admin-ajax.php?action=windscreen_delete_location&id=' . $_GET['loc-id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['loc-id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=adwind') . '" class="button adwi-delete-location">Delete</a>';
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
			$loc = Location::findOrFail($_REQUEST['id']);
			$loc->name = $_REQUEST['name'];
			$loc->description = $_REQUEST['desc'];
			$loc->number = $_REQUEST['phone'];
			$loc->address = $_REQUEST['location'];
			$loc->radius = $_REQUEST['radius'];
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

	public function frontGetLocation()
	{
		/* 
		$lat = '52.9539591';
		$lng = '-1.1565018';
		$radius = 4;

		$locations = DB::table('aw_locations')
						->select(DB::raw('id, name, lat, lng, radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->get();
						*/ 
	}
}