<?php 

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;

class ad_paypal_interface 
{
    protected $apiContext;

    function __construct()
    {
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdT05BjwM_57eFwc56eor6xA7VRG1JQLJz2h4iXPGzqxnLC3nGwZXiywg2pk5unhgRozEAW0rLX0rNLH',     // ClientID
                'EJRYipfzxEhoZJcewGK-n0x1_7kjOwfVY5dV3XeLk0VEhXWq35IYKNX47pdlD7qizyN2LyiUew1qu3QT'      // ClientSecret
            )
        );
    }

    /**
     * generate the payment links from given data
     * @param  array $postdata  data from form
     * @param  array $skip      set up in the controller
     * @param  array $permit    set up in the controller
     * @param  array $coupon    if exists, set up in the controller
     * @param  string $total    final price to charge the user
     * @return string           returns the generated url with token
     */
    public function generate_payment_link($postdata, $skip, $permit = null, $coupon = null, $total)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $items = [];

        $item1 = new Item();
        $item1->setName($skip['title'])
              ->setCurrency('GBP')
              ->setQuantity(1)
              ->setSku($skip['id'])
              ->setPrice($skip['price']);

        $items[] = $item1;

        if($permit['title'] != null) {
            $item2 = new Item();
            $item2->setName($permit['title'])
                  ->setCurrency('GBP')
                  ->setQuantity(1)
                  ->setSku($permit['id'])
                  ->setPrice($permit['price']);

            $items[] = $item2;
        }

        if($coupon['title'] != null) {
            $item3 = new Item();
            $item3->setName($coupon['title'])
                  ->setCurrency('GBP')
                  ->setQuantity(1)
                  ->setSku($coupon['id'])
                  ->setPrice(-$coupon['price']);

            $items[] = $item3;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $amount = new Amount();
        $amount->setCurrency("GBP")
               ->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Payment for Skip Hire")
                    ->setInvoiceNumber(uniqid());


        $baseUrl = home_url();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/confirmation?success=true")
                     ->setCancelUrl("$baseUrl/confirmation?success=false");

        $payment = new Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

        $request = clone $payment;

        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            // error
            exit(1);
        }
        $approvalUrl = $payment->getApprovalLink();
        return $approvalUrl;
    }

    /**
     * authorises the payment, adds the requirements to the database
     */
    public function authorised_payment_check () 
    {
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $paymentId = $_GET['paymentId'];
            $payment = Payment::get($paymentId, $this->apiContext);

            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);

            $transaction = new Transaction();
            $amount = new Amount(); 

            $amount->setCurrency('GBP')
                   ->setTotal($_SESSION['ash_order_total']);

            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);

            try {
                $result = $payment->execute($execution, $this->apiContext);
                try {
                    $payment = Payment::get($paymentId, $this->apiContext);
                } catch (Exception $ex) {
                    // error
                    exit(1);
                }
            } catch (Exception $ex) {
                // error
                exit(1);
            }

            return $result;
        } else {
            // user canceled payment;
            exit;
        }
    }
}