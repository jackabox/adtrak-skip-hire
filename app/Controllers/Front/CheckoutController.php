<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Skip;

class CheckoutController
{
	private static $instance = null;
	public $skip;
	public $noSkip = false;

	public function __construct()
	{
		$this->addActions();
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function addActions()
	{
		add_action('ash_checkout', [$this, 'checkout']);
	}

	public function checkout() 
	{
		$this->beforeCheckout();
		
		if($_POST['skip_id']) {
			$this->skip = Skip::findOrFail($_POST['skip_id']);

			// $this->skip
			$this->checkoutForm();
		} else {
			echo 'Suggest pick a skip here.';
		}

		$this->afterCheckout();
	}

	public function beforeCheckout($skip)
	{
		$template = $this->templateLocater('checkout/start.php');
		include_once $template;
	}

	public function afterCheckout()
	{
		$template = $this->templateLocater('checkout/end.php');		
		include_once $template;
	}

	public function checkoutForm()
	{
		$template = $this->templateLocater('checkout/form.php');
		include_once $template;
	}

	public function paypal()
	{

	}

	protected function templateLocater($filename)
	{
		if ($overriden = locate_template('adtrak-skips/' . $filename)) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . $filename;
		}

		return $template;
	}
}