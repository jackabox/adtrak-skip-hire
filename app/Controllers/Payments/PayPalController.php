<?php namespace Adtrak\Skips\Controllers\Payments;

uses Adtrak\Skips\Helper;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
//use PayPal\Api\PaymentExecution;

class PayPalController
{
    protected static $instance = null;
    protected $apiContext;
    protected $email;
    protected $sandbox;
    protected $invoiceMessage;

    public function __construct()
    {
        $paypalOptions = (object) get_option('ash_paypal');
        dd($paypalOptions);

        if ($paypalOptions->enable_sandbox)
            $this->sandbox = $paypalOptions->enable_sandbox

        if ($paypalOptions->invoice_message)
            $this->invoiceMessage = $paypalOptions->invoice_message;

        if ($paypalOptions->email)
            $this->email = $paypalOptions->email;

        // create a new instance of the PayPal api using the auth tokens provided.
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalOptions->client_id,
                $paypalOptions->client_secret
            )
        );

        // set the config of the api to live if we don't have enable sandbox as true
        if ($paypalOptions->enable_sandbox !== true) {
            $this->apiContext->setConfig(['mode' => 'live']);
        }
    }

    /**
     * @param $skipData
     * @param $total
     * @param null $permitData
     * @param null $couponData
     * @return null|string
     */
    public function generateLink($skipData, $total, $permitData = null, $couponData = null)
    {
        $payee = new Payer();
        $payee->setPaymentMethod('paypal');

        $skip = new Item();
        $skip->setName('')
            ->setCurrency('GBP')
            ->setQuantity(1)
            ->setSku('')
            ->setPrice(floatval(''));

        $items = [];
        $items[] = $skip;

        if ($permitData) {
            $permit = new Item();
            $permit->setName('')
                ->setCurrency('GBP')
                ->setQuantity(1)
                ->setSku('')
                ->setPrice(floatval(''));

            $items[] = $permit;
        }

        if ($couponData) {
            $coupon = new Item();
            $coupon->setName('')
                ->setCurrency('GBP')
                ->setQuantity(1)
                ->setSku('')
                ->setPrice(floatval('') * -1);

            $items[] = $coupon;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $amount = new Amount();
        $amount->setCurrency('GBP')
            ->setTotal(floatval($total));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->invoiceMessage])
            ->setInvoiceNumber(uniqid());

        $baseUrl = home_url();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl . '/confirmation?success=true')
            ->setCancelUrl($baseUrl . '/confirmation?success=false');

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payee)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            echo $ex->getData();
            exit(1);
        }

        $approvalUrl = $payment->getApprovalLink();
        return $approvalUrl;
    }

    /**
     * @return AdminController|null
     */
    public static function instance()
    {
        null === self::$instance and self::$instance = new self;
        return self::$instance;
    }
}