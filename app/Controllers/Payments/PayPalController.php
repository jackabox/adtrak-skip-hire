<?php namespace Adtrak\Skips\Controllers\Payments;

uses Adtrak\Skips\Helper;

class PayPalController
{
    protected static $instance = null;

    private $clientID;

    private $clientSecret;

    protected $sandbox;

    protected $invoiceMessage;

    public function __construct()
    {
        $this->clientID = get_option('ash_paypal_client_id', '');
        $this->clientSecret = get_option('ash_paypal_client_secret', '');
        $this->sandbox = get_option('ash_paypal_enable_sandbox', '');
        $this->invoiceMessage = get_option('ash_paypal_invoice_message', '');
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