<?php 

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

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

    public function generate_payment_link($postdata = null)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName('Sample')
              ->setCurrency('GBP')
              ->setQuantity(1)
              ->setSku('12313')
              ->setPrice('20.00');

        $item2 = new Item();
        $item2->setName('Permit')
              ->setCurrency('GBP')
              ->setQuantity(1)
              ->setSku('12315')
              ->setPrice('1.00');

        $itemList = new ItemList();
        $itemList->setItems([$item1, $item2]);

        $amount = new Amount();
        $amount->setCurrency("GBP")
               ->setTotal(21);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Text Payment")
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
}