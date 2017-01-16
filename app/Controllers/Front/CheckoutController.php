<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\Models\Permit;

class CheckoutController
{
	private static $instance = null;

	public $skip;
	public $permit;

    /**
     * CheckoutController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

    /**
     * @return CheckoutController|null
     */
    public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

    /**
     *
     */
    public function addActions()
	{
		add_action('ash_checkout', [$this, 'checkout']);
	}

    /**
     *
     */
    public function checkout()
	{
		$this->beforeCheckout();
		
		// if (! $_POST['postcode']) {
		// 	echo 'We need to know your location to see if we can deliver skips to your area. Please use the location form below and then proceed.';
		// }

		// if($_POST['skip_id'] && $_POST['postcode']) {
		if($_POST['skip_id']) {
			$this->skip = Skip::findOrFail($_POST['skip_id']);
			$this->permit = Permit::all();

			$this->checkoutForm($this->skip, $this->permit);
		}

		$this->afterCheckout();
	}

    /**
     * @param $skip
     */
    public function beforeCheckout($skip)
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

    /**
     * @param $filename
     * @return string
     */
    protected function templateLocator($filename)
	{
		if ($overwrite = locate_template('adtrak-skips/' . $filename)) {
			$template = $overwrite;
		} else {
			$template = Helper::get('templates') . $filename;
		}

		return $template;
	}
}