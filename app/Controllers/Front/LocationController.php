<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Location;

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
		 add_action('ash_booking_form', [$this, 'locationForm']);
	}

	public function locationForm()
    {
        // do the location output (template)
        $template = $this->templateLocator('booking/form.php');
        include_once $template;
    }

    public function locationCheck()
    {
        //
    }
}