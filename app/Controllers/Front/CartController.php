<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\Models\Coupon;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\Models\OrderItem;
use Adtrak\Skips\Controllers\Payments\PayPalController as PayPal;

class CartController extends Front
{
	public $skip;
	public $coupon;
	public $permit;
	public $paypal;
	public $orderDetails;

	public function __construct()
	{
        $this->paypal = new PayPal();
        $this->addActions();
	}

	public function addActions()
	{
		add_action('ash_cart', [$this, 'cart']);
	}

	public function cart()
	{
		$this->beforeCart();

		if (isset($_POST['ash_submit'])) {
			$this->cartDetails();
		} else {
			echo 'Sorry, looks like you have not placed an order';
		}

		$this->afterCart();		
	}

	public function beforeCart()
	{
		$template = $this->templateLocator('cart/header.php');
		include_once $template;
	}

	public function cartDetails()
	{
        $skip = $this->getSkip();
		$permit = $this->getPermit();		
		$coupon = $this->getCoupon();
		$details = $this->getOrderDetails();

		if ($coupon) {
			if ($coupon->type == 'flat') {
				$couponValue = $coupon->amount * -1;
				$subTotal = $skip->price + $couponValue;
			} else {
				$couponValue = $skip->price * ($coupon->amount / 100) * -1;
				$subTotal = $skip->price + $couponValue; 
			}
		} else {
			$subTotal = $skip->price;
		}

		if ($permit) {
			$total = $subTotal + $permit->price;
		} else {
			$total = $subTotal;
		}

        $paypal = $this->getPaymentLink($skip, $total, $permit, $coupon);
	    $template = $this->templateLocator('cart/details.php');
		include_once $template;
	}

	public function afterCart()
	{

	}

    /**
     *
     */
    public function getPaymentLink($skip, $total, $permit, $coupon)
    {
        return $this->paypal->generateLink($skip, $total, $permit, $coupon);
    }


    public function getSkip()
	{
		if ($_POST['ash_skip']) {
			$this->skip = Skip::findOrFail($_POST['ash_skip']);
		}

		if ($this->skip) {
			$_SESSION['ash_details']['skip'] = $this->skip;
		} else {
			$_SESSION['ash_details']['skip'] = [];
		}

		return $this->skip;
	}

	public function getPermit()
	{
		if ($_POST['ash_permit']) {
			$this->permit = Permit::findOrFail($_POST['ash_permit']);
		}

		if ($this->permit) {
			$_SESSION['ash_details']['permit'] = $this->permit;
		} else {
			$_SESSION['ash_details']['permit'] = [];
		}

		return $this->permit;		
	}

	public function getCoupon()
	{
		if ($_POST['ash_coupon']) {
			$this->coupon = Coupon::where('code', '=', $_POST['ash_coupon'])->first();
		} 

		if ($this->coupon) {
			$_SESSION['ash_details']['coupon'] = $this->coupon;
		} else {
			$_SESSION['ash_details']['coupon'] = [];
		}
		
		return $this->coupon;		
	}

	public function getOrderDetails()
	{
		if ($_POST['ash_submit']) {
			$this->orderDetails = (object) $_POST;
		}

		if ($this->orderDetails) {
			$_SESSION['ash_details']['user'] = $this->orderDetails;
		} else {
			$_SESSION['ash_details']['user'] = [];
		}

		return $this->orderDetails;
	}
}