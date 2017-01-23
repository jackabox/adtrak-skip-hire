<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\Models\OrderItem;
use Adtrak\Skips\Controllers\Payments\PayPalController as PayPal;

class ConfirmationController extends Front
{
	protected $paypal;

	public function __construct()
	{
		$this->paypal = new PayPal();
		$this->addActions();
	}

	public function addActions()
	{
		add_action('ash_confirmation', [$this, 'checkState']);
	}

    public function authorisePayment($paymentID)
    {
        return $this->paypal->authorisedPaymentCheck($paymentID);
    }

	public function checkState() 
	{
		if (isset($_GET['success']) && $_GET['success'] == 'true') {

            // $this->authorisePayment($_GET['paymentId']);

			$this->success();

        } else if (isset($_GET['success'])  && $_GET['success'] == 'false') {

			$this->fail();			
            // user canceled

        } else {
			echo 'Should not be here';
		}
	}

	public function success()
	{
		$template = $this->templateLocator('confirmation/success.php');
		include_once $template;
	}

	public function fail()
	{
		$template = $this->templateLocator('confirmation/fail.php');
		include_once $template;
	}


}