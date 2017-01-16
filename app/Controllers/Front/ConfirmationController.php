<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\Models\Coupon;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\Models\OrderItem;
// use Adtrak\Skips\Controllers\PayPalController;

class ConfirmationController
{
	private static $instance = null;
	
	public $skip;
	public $coupon;
	public $permit;
	public $paypal;
	public $orderDetails;

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
		add_action('ash_confirmation', [$this, 'confirmation']);
	}

	public function confirmation()
	{
		$this->beforeConfirmation();

		if ($_POST['ash_submit']) {
			$this->confirmationDetails();
		} else {
			echo 'Sorry, looks like you have not placed an order';
		}

		$this->afterConfirmation();		
	}

	public function beforeConfirmation()
	{

	}

	public function confirmationDetails()
	{
		$skip = $this->getSkip();
		$permit = $this->getPermit();		
		$coupon = $this->getCoupon();
		$details = $this->getOrderDetails();

		if ($coupon) {
			if ($coupon->type = 'flat') {
				$subTotal = $skip->price - $coupon->price;
			} else {
				// 90 = 100 - (100 * (10 / 100));
				$subTotal = $skip->price - ($skip->price * ($coupon->amount / 100)); 
			}
		} else {
			$subTotal = $skip->price;
		}

		$total = $subTotal + $permit->price;

		$template = $this->templateLocator('confirmation/details.php');
		include_once $template;
	}

	public function afterConfirmation()
	{

	}

	public function getSkip()
	{
		if ($_POST['ash_skip']) {
			$this->skip = Skip::findOrFail($_POST['ash_skip']);
		}

		return $this->skip;
	}

	public function getPermit()
	{
		if ($_POST['ash_permit']) {
			$this->permit = Permit::findOrFail($_POST['ash_permit']);
		}

		return $this->permit;		
	}

	public function getCoupon()
	{
		if ($_POST['ash_coupon']) {
			$this->coupon = Coupon::where('name', '=', $_POST['ash_coupon'])->first();
		}
		
		return $this->coupon;		
	}

	public function getOrderDetails()
	{
		if ($_POST['ash_submit']) {
			$this->orderDetails = (object) $_POST;
		}

		return $this->orderDetails;
	}

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