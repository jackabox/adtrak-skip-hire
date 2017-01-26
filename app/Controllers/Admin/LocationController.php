<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Location;
use Adtrak\Skips\Facades\Admin;
use Billy\Framework\Facades\DB;

/**
 * Class LocationController
 * @package Adtrak\Skips\Controllers\Admin
 */
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
     * @return mixed
     */
    public function index()
	{
        # work out what page / content to display
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Location::count();
		$totalPages = ceil($total / $limit);

		# get the location results, offset and take only the limit
		$locations = Location::orderBy('created_at', 'desc')
                            ->skip($offset)
                            ->take($limit)
                            ->get();

        # Generate the pagination from the page, and what else is left.
		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		# Links to edit / add new locations
		$link = [
			'edit' => admin_url('admin.php?page=ash-locations-edit&id='),
			'add'  => admin_url('admin.php?page=ash-locations-add')
		];

		# Return the view
		return View::render('locations/index.twig', [
			'locations' 	=> $locations,
			'link'			=> $link,
			'pagination'	=> $pagination
		]);
	}

    /**
     * @return mixed
     */
    public function create()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'location_add') {
			$this->store();
		}

		return View::render('locations/add.twig', []);
	}

    /**
     *
     */
    public function store()
	{
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

        if (!empty($errors)) {
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

		$location = Location::findOrFail($_GET['id']);

		return View::render('locations/edit.twig', [
            'location' 	 => $location,
            'button'	 => $button
        ]);
	}

    /**
     *
     */
    public function update()
	{
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

        if (!empty($errors)) {
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
}