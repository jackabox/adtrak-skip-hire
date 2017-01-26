<?php
namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\Controllers\Front\LocationController;
use Adtrak\Skips\Controllers\Front\SkipController;

/**
 * Class CheckoutController
 * @package Adtrak\Skips\Controllers\Front
 */
class CheckoutController extends Front
{
    /**
     * @var \Adtrak\Skips\Controllers\Front\LocationController
     */
	protected $location;

    /**
     * @var \Adtrak\Skips\Controllers\Front\SkipController
     */
	protected $skip;

    /**
     *
     */
	protected $checkPostcode;

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
     * Add actions for templates to hook into.
     * This is the code that tends to not be edited
     */
    public function addActions()
	{
		add_action('ash_checkout', [$this, 'handler']);
	}

    /**
     * Handler function for the post code check.
     * Returns appropriate parts dependant on where location is.
     */
	public function handler()
	{
	    # if skip_id is set, set the session var
		if (isset($_POST['skip_id'])) {
			$_SESSION['ash_skip'] = $_POST['skip_id'];
		}

        # if autocomplete is set, check the postcode
		if (isset($_POST['autocomplete'])) {
			$this->checkPostcode = $this->location->checkPostcode();
		}

		# Checker for the locations, skip or checkout form.
		if (!isset($_SESSION['ash_location']) || $_SESSION['ash_location'] == null) {
			$this->location->form();
		} else if (!isset($_SESSION['ash_skip']) || $_SESSION['ash_skip'] == null) {
			$this->skip->loop();
		} else if (isset($_SESSION['ash_skip']) && isset($_SESSION['ash_location'])) {
			$this->checkout();			
		}
	}

    /**
     *
     */
    public function checkout()
	{
	    # before checkout hooks
		$this->beforeCheckout();

		# Find the skip for
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
		$template = $this->templateLocator('checkout/header.php');
		include_once $template;
	}

    /**
     *
     */
    public function afterCheckout()
	{
		$template = $this->templateLocator('checkout/footer.php');
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