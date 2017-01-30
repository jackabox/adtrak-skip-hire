<?php
namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\Models\OrderItem;
use Adtrak\Skips\Controllers\Payments\PayPalController as PayPal;

/**
 * Class ConfirmationController
 * @package Adtrak\Skips\Controllers\Front
 */
class ConfirmationController extends Front
{
    /**
     * @var PayPal
     */
	protected $paypal;

    /**
     * @var
     */
	protected $total;

    /**
     * ConfirmationController constructor.
     */
	public function __construct()
	{
		$this->paypal = new PayPal();
		$this->addActions();
	}

    /**
     * Add the actions, for the front end template to hook into
     */
	public function addActions()
	{
		add_action('ash_confirmation', [$this, 'checkState']);
	}

	/*
	 * Authorise the paypal payment,
	 * check the details / prices match against what was paid, process it.
	 */
    public function authorisePayment()
    {
		$details = (object) $_SESSION['ash_details'];
		$delivery = (object) $_SESSION['ash_location'];
		
		if ($details->coupon) {
			if ($details->coupon->type == 'flat') {
				$subTotal = $details->skip->price - $details->coupon->amount;
			} else {
				$subTotal = $details->skip->price - ($details->skip->price * ($details->coupon->amount / 100)); 
			}
		} else {
			$subTotal = $details->skip->price;
		}

        $this->total = $subTotal;

		if ($details->permit) {
			$this->total = $this->total + $details->permit->price;
		}

        if ($delivery->fee) {
            $this->total = $this->total + $delivery->fee;
        }

        return $this->paypal->authorisedPaymentCheck($subTotal, $this->total, $delivery->fee);
    }

    /**
     * Work out what view / escape if people are trying to access without redirects
     */
	public function checkState() 
	{
		if (isset($_SESSION['ash_details'])) {
			if (isset($_GET['success']) && $_GET['success'] == 'true') {

            	$this->authorisePayment();
				$this->success();

        	} else if (isset($_GET['success'])  && $_GET['success'] == 'false') {

				$this->fail();			

        	}
		} else {
			echo 'No order details found';
		}
	}

	/*
	 * Get the session details, process the data, save it
	 */
	public function success()
	{
		// handle adding the order here.
        $details = (object) $_SESSION['ash_details'];
        $delivery = (object) $_SESSION['ash_location'];

		if($details) {
            $order = new Order();
            $order->forename = $details->user->ash_forename;
            $order->surname = $details->user->ash_surname;
            $order->email = $details->user->ash_email;
            $order->number = $details->user->ash_telephone;
            $order->address1 = $details->user->ash_address1;
            $order->address2 = $details->user->ash_address2;
            $order->city = $details->user->ash_city;
            $order->county = $details->user->ash_county;
            $order->country = '';
            $order->postcode = $details->user->ash_postcode;
            $order->delivery_date = date('Y-m-d', strtotime($details->user->ash_delivery_date));
            $order->delivery_slot = $details->user->ash_delivery_time;
            $order->notes = $details->user->ash_notes;
            $order->total = $this->total;
            $order->status = 'pending';
            $order->payment_method = 'PayPal';
            $order->payment_reference = $_GET['paymentId'];

            if ($order->waste) {
                $order->waste = $user->ash_waste;
            }

            $order->save();

            // skip
            if ($details->skip) {
                $skip = new OrderItem();
                $skip->order_id = $order->id;
                $skip->type = "Skip";
                $skip->name = $details->skip->name;
                $skip->price = $details->skip->price;
                $skip->save();
            }

            // permit
            if ($details->permit) {
                $permit = new OrderItem();
                $permit->order_id = $order->id;
                $permit->type = "Permit";
                $permit->name = $details->permit->name;
                $permit->price = $details->permit->price;
                $permit->save();
            }

            // permit
            if ($delivery) {
                $permit = new OrderItem();
                $permit->order_id = $order->id;
                $permit->type = "Delivery";
                $permit->name = "";
                $permit->price = $delivery->fee;
                $permit->save();
            }

            // coupon
            if ($details->coupon) {
                $coupon = new OrderItem();
                $coupon->order_id = $order->id;
                $coupon->type = "Coupon";
                $coupon->name = $details->coupon->code;

                if ($details->coupon->type = 'flat') {
                    $coupon->price = $details->coupon->amount * -1;
                } else {
                    $coupon->price = ($details->skip->price * ($details->coupon->amount / 100)) * -1;
                }

                $coupon->save();
            }

            // free up stored sessions
            if ($order->id) {
                unset($_SESSION['ash_details']);
                unset($_SESSION['ash_skip']);
                unset($_SESSION['ash_location']);
            }

            // display thanks!
            $template = $this->templateLocator('confirmation/success.php');
            include_once $template;
        } else {
		    $this->fail();
        }
	}

    /**
     * If something happens that was not meant to, show the fail loop
     */
	public function fail()
	{
		$template = $this->templateLocator('confirmation/fail.php');
		include_once $template;
	}
}
