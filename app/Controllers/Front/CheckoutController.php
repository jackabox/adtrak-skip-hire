<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\Controllers\Front\LocationController;
use Adtrak\Skips\Controllers\Front\SkipController;

class CheckoutController extends Front
{
	protected $location;
	protected $skip;

    /**
     * CheckoutController constructor.
     */
    public function __construct()
	{
		$this->location = new LocationController;
		$this->skip = new SkipController;
		$this->addActions();
	}

    /**
     *
     */
    public function addActions()
	{
		add_action('ash_checkout', [$this, 'handler']);
	}

	public function handler()
	{
		if (isset($_POST['skip_id'])) {
			$_SESSION['ash_skip'] = $_POST['skip_id'];
		}

		if (isset($_POST['autocomplete'])) {
			$this->location->checkPostcode();
		}

		if (!isset($_SESSION['ash_location']) || $_SESSION['ash_location'] == null) {
			echo '<p>We need to know your location to see if we can deliver skips to your area. Please use the location form below and then proceed.</p>';

			$this->location->form();
		} else if (!isset($_SESSION['ash_skip']) || $_SESSION['ash_skip'] == null) {
			
			echo '<p>Please select a skip below.</p>';

			$this->skip->skipLoop();
		} else {
			$this->checkout();			
		}
	}

    /**
     *
     */
    public function checkout()
	{
		$this->beforeCheckout();

		$skip = Skip::findOrFail($_SESSION['ash_skip']);
		$permit = Permit::all();

		$this->checkoutForm($skip, $permit);

		$this->afterCheckout();
	}

    /**
     * @param $skip
     */
    public function beforeCheckout()
	{
		$template = $this->templateLocator('checkout/start.php');
		include_once $template;
	}

    /**
     *
     */
    public function afterCheckout()
	{
		$template = $this->templateLocator('checkout/end.php');
		include_once $template;
	}

    /**
     * @param $skip
     * @param $permits
     */
    public function checkoutForm($skip, $permits)
	{
		$template = $this->templateLocator('checkout/form.php');
		include_once $template;
	}
}