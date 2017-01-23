<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Location;
use Billy\Framework\Facades\DB;

class LocationController extends Front
{
	public $skip;

    /**
     * LocationController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

	public function addActions()
	{
		 add_action('ash_booking_form', [$this, 'form']);
	}

	public function form()
    {
		$this->beforeForm();
		
        // do the location output (template)
        $template = $this->templateLocator('booking/form.php');
        include_once $template;
    }

	public function beforeForm()
	{
		$template = $this->templateLocator('booking/header.php');
        include_once $template;
	}

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
			$location = DB::table('as_locations')
						->select(DB::raw('id, name, lat, lng, radius, description, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance '))
						->having('distance', '<', $radius)
						->orderBy('distance')
						->first();

			if ($location && ($location->distance <= $location->radius)) {
                return true;
			} else {
				echo "Sorry, we do not currently deliver to your location.";
			}		
		} catch (Exception $e) {
			echo "Sorry, something went wrong. Please try again.";
		}
    }
}