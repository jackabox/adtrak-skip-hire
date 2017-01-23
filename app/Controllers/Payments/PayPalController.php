<?php namespace Adtrak\Skips\Controllers\Payments;

use Adtrak\Skips\Helper;
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
use PayPal\Api\PaymentExecution;

class PayPalController
{
    protected static $instance = null;
    protected $apiContext;
    protected $email;
    protected $sandboxed;
    protected $invoiceMessage;

    public function __construct()
    {
        $paypalOptions = (object) get_option('ash_paypal', true);

        if ($paypalOptions->enable_sandbox) {
            $this->sandboxed = $paypalOptions->enable_sandbox;
        }

        if ($paypalOptions->invoice_message) {
            $this->invoiceMessage = $paypalOptions->invoice_message;
        }

        if ($paypalOptions->email) {
            $this->email = $paypalOptions->email;
        }

        // create a new instance of the PayPal api using the auth tokens provided.
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypalOptions->client_id,
                $paypalOptions->client_secret
            )
        );

        // set the config of the api to live if we don't have enable sandbox as true
        if ($this->sandboxed != true) {
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
        $skip->setName($skipData->name)
            ->setCurrency('GBP')
            ->setQuantity(1)
            ->setSku($skipData->id)
            ->setPrice(floatval($skipData->price));

        $items = [];
        $items[] = $skip;

        if ($permitData) {
            $permit = new Item();
            $permit->setName($permitData->name)
                ->setCurrency('GBP')
                ->setQuantity(1)
                ->setSku($permitData->id)
                ->setPrice(floatval($permitData->price));

            $items[] = $permit;
        }

        if ($couponData) {
            $coupon = new Item();
            $coupon->setName($couponData->name)
                ->setCurrency('GBP')
                ->setQuantity(1)
                ->setSku($couponData->id)
                ->setPrice(floatval($couponData->price) * -1);

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
            ->setDescription($this->invoiceMessage)
            ->setInvoiceNumber(uniqid());

        $baseUrl = home_url();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl . '/skip-booking/confirmation?success=true')
            ->setCancelUrl($baseUrl . '/skip-booking/confirmation?success=false');

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

    public function authorisedPaymentCheck($paymentID)
    {
        $payment = Payment::get($paymentID, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount = new Amount();

        $amount->setCurrency('GBP')
            ->setTotal(260);

        $transaction->setAmount($amount);
        $execution->addTransaction($transaction);

        try {
            $result = $payment->execute($execution, $this->apiContext);
            try {
                $payment = Payment::get($paymentID, $this->apiContext);
            } catch (Exception $ex) {
                // error
                exit(1);
            }
        } catch (Exception $ex) {
            // error
            exit(1);
        }

        return $result;
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