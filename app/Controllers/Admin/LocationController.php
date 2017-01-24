<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Location;
use Adtrak\Skips\Facades\Admin;
use Billy\Framework\Facades\DB;

class LocationController extends Admin
{
    /**
     * LocationController constructor.
     */
    public function __construct()
	{
		self::instance();

		add_shortcode('ash_location_lookup', [$this, 'showLocationForm']);
	}

    /**
     *
     */
    public function menu()
	{
		$this->addMenu('Locations', 'ash-locations', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Locations - Add', 'ash-locations-add', 'manage_options', [$this, 'create'], 'ash');
		$this->addMenu('Locations - Edit', 'ash-locations-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

    /**
     *
     */
    public function index()
	{
		// work out if pages
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Location::count();
		$totalPages = ceil($total / $limit);

		// get results
		$locations = Location::orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();

		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		$link = [
			'edit' => admin_url('admin.php?page=ash-locations-edit&id='),
			'add'  => admin_url('admin.php?page=ash-locations-add')
		];

		View::render('admin/locations/index.twig', [
			'locations' 	=> $locations,
			'link'			=> $link,
			'pagination'	=> $pagination
		]);
	}

    /**
     *
     */
    public function create()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'location_add') {
			$this->store();
		}

		View::render('admin/locations/add.twig', []);
	}

    /**
     *
     */
    public function store()
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

				$url = admin_url('admin.php?page=ash-locations-edit&id=' . $loc->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }

        die();
	}

    /**
     *
     */
    public function edit()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'location_update') {
			$this->update();
		}

 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_location_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url('admin-ajax.php?action=ash_location_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-location') . '" class="ash-location-delete">Delete</a>';
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

    /**
     *
     */
    public function update()
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

    /**
     *
     */
    public function destroy()
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