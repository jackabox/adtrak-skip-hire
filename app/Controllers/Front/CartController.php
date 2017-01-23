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
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $this->authorisePayment($_GET['paymentId']);
        } else if (isset($_GET['success'])  && $_GET['success'] == 'false') {
            // user canceled
        } else {
     
        }
	}

	public function cartDetails()
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

    public function authorisePayment($paymentID)
    {
        return $this->paypal->authorisedPaymentCheck($paymentID);
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
}