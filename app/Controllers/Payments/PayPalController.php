<?php namespace Adtrak\Skips\Controllers\Payments;

use Adtrak\Skips\Helper;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

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

/**
 * Class PayPalController
 * @package Adtrak\Skips\Controllers\Payments
 */
class PayPalController
{
    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * @var ApiContext
     */
    protected $apiContext;

    /**
     * @var
     */
    protected $sandboxed;

    /**
     * @var
     */
    protected $invoiceMessage;

    /**
     * PayPalController constructor.
     */
    public function __construct()
    {
        $paypalOptions = (object) get_option('ash_paypal', true);

        if ($paypalOptions->enable_sandbox) {
            $this->sandboxed = $paypalOptions->enable_sandbox;
        }

        if ($paypalOptions->invoice_message) {
            $this->invoiceMessage = $paypalOptions->invoice_message;
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
    public function generateLink($skipData, $subTotal, $total, $delivery, $permitData = null, $couponData = null)
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
            if ($couponData->type == 'flat') {
				$couponValue = $couponData->amount * -1;
			} else {
				$couponValue = $skipData->price * ($couponData->amount / 100) * -1;
			}

            $coupon = new Item();
            $coupon->setName($couponData->name)
                ->setCurrency('GBP')
                ->setQuantity(1)
                ->setSku($couponData->id)
                ->setPrice(floatval($couponValue));

            $items[] = $coupon;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $amount = new Amount();
        $details = new Details();

        $details->setShipping($delivery->fee)
            ->setTax(0)
            ->setSubtotal($subTotal);

        $amount->setCurrency('GBP')
            ->setTotal(floatval($total))
            ->setDetails($details);

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

    /**
     * @param $subTotal
     * @param $total
     * @return Payment
     */
    public function authorisedPaymentCheck($subTotal, $total, $delivery)
    {
        $paymentID = $_GET['paymentId'];
        $payment = Payment::get($paymentID, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping($delivery)
            ->setTax(0)
            ->setSubtotal($subTotal);

        $amount->setCurrency('GBP')
            ->setTotal($total)
            ->setDetails($details);

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

        return $payment;
    }

    /**
     * @return PayPalController|null
     */
    public static function instance()
    {
        null === self::$instance and self::$instance = new self;
        return self::$instance;
    }
}