<?php
namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Location;
use Billy\Framework\Facades\DB;

/**
 * Class LocationController
 * @package Adtrak\Skips\Controllers\Front
 */
class LocationController extends Front
{
    /**
     * LocationController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

    /**
     * Set the actions for the templates to hook into
     */
	public function addActions()
	{
		 add_action('ash_booking_form', [$this, 'form']);
	}

    /**
     * Show the form to search for locations
     */
	public function form()
    {
		$this->beforeForm();
		
        // do the location output (template)
        $template = $this->templateLocator('booking/form.php');
        include_once $template;
    }

    /**
     * before the form, output any header information
     */
	public function beforeForm()
	{
		$template = $this->templateLocator('booking/header.php');
        include_once $template;
	}

    /**
     * Check if the location is in the database, and if it is within the distance.
     *
     * @return bool
     */
    public function checkPostcode()
    {
        // check here
        $_SESSION['ash_location']['name'] = $_POST['autocomplete'];

        $lat = $_POST['lat'];
        $_SESSION['ash_location']['lat'] = $lat;

		$lng = $_POST['lng'];
        $_SESSION['ash_location']['lng'] = $lng;
        
		$radius = 50;

		try {
			$location = DB::table('ash_locations')
						->select(DB::raw('id, name, lat, lng, radius, delivery_fee, description, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->first();

			if ($location && ($location->distance <= $location->radius)) {
			    $_SESSION['ash_location']['fee'] = $location->delivery_fee;
                return true;
			} else {
				$template = $this->templateLocator('booking/not-available.php');
        		include_once $template;

				return false;
			}		
		} catch (Exception $e) {
			$template = $this->templateLocator('booking/error.php');
        	include_once $template;

			return false;
		}
    }
}