<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Controllers\Admin\DashboardController;
use Adtrak\Skips\Controllers\Admin\LocationController;
use Adtrak\Skips\Controllers\Admin\SkipController;
use Adtrak\Skips\Controllers\Admin\PermitController;
use Adtrak\Skips\Controllers\Admin\CouponController;
use Adtrak\Skips\Controllers\Admin\OrderController;
use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Order;

class AdminController
{
	protected static $instance = null;
	protected $permit;
	protected $coupon;
	protected $skip;
	protected $location;
	protected $dashboard;

    /**
     * AdminController constructor.
     */
    public function __construct()
	{
		$this->dashboard = new DashboardController;
		$this->permit = new PermitController;
		$this->coupon = new CouponController;
		$this->location = new LocationController;
		$this->skip = new SkipController;
		$this->order = new OrderController;
	}

    /**
     * @return AdminController|null
     */
    public static function instance()
	{
		null === self::$instance and self::$instance = new self;
		return self::$instance;
	}

    /**
     *
     */
    public function menu()
	{
		add_menu_page(
			__( 'Skips', 'adskip' ),
			'Skips',
			'manage_options',
			'ash',
			'',
			'', //'none',
			100
		);

		$this->dashboard->menu();
		$this->order->menu();
		$this->skip->menu();
		$this->location->menu();
		$this->permit->menu();
		$this->coupon->menu();

		add_submenu_page(
			'ash',			
			__( 'Settings', 'adskip' ),
			'Settings',
			'manage_options',
			'ash-settings',
			[$this, 'showSettings'],
			''
		);
	}

	public function dashboard() 
	{
		if(current_user_can('manage_options')) {
			wp_add_dashboard_widget(
				'ash_order_overview', 
				'Skips: Pending Orders', 
				[$this, 'pendingOrders']
			);
		}
	}

	public function pendingOrders()
    {
		$orderCount = Order::where('status', '=', 'pending')->count();

		$orders = Order::where('status', '=', 'pending')
						->orderBy('delivery_date')
						->take(1)
						->get();

		$link = admin_url('admin.php?page=ash-orders');

		View::render('admin/widgets/orders.twig', [
			'order_count' => $orderCount,
            'orders'  => $orders,
			'link'	=> $link
        ]);
    }


    /**
     *
     */
    public function scripts()
	{
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/admin.js'), ['jquery'], '', true);
		wp_localize_script('adtrak-skips', 'SHajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
		
		wp_enqueue_script('google_maps_api', '//maps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
	}


    /**
     * Show any options available in the settings
     *
     * @return mixed
     */
    public function showSettings()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'settings_save') {
			$this->updateSettings();
		}

		$options = (object) [];
		$options->gmaps_api = get_option('ash_google_maps_api', '');

		$paypal = (object) get_option('ash_paypal', [
			'client_id' => '',
			'client_secret' => '',
			'invoice_message' => '',
			'enable_sandbox' => true
		]);
        $options->paypal_client_id = $paypal->client_id;
        $options->paypal_client_secret = $paypal->client_secret;
        $options->paypal_invoice_message = $paypal->invoice_message;
        $options->paypal_enable_sandbox = $paypal->enable_sandbox;

		$delivery = (object) get_option('ash_delivery', [
			'available_days' => 5, 
			'future_days' => 0
		]);
		$options->delivery_future = $delivery->future_days;
		$options->delivery_days = $delivery->available_days;

		return View::render('admin/settings.twig', [
			'options' 		=> $options
		]);
	}

    /**
     * update any settings based on what's posted
     */
    public function updateSettings()
	{
		update_option('ash_google_maps_api', $_REQUEST['gmaps_api']);

		$paypalOptions = [
		    'client_id' => $_REQUEST['paypal_client_id'],
            'client_secret' => $_REQUEST['paypal_client_secret'],
            'invoice_message' => $_REQUEST['paypal_invoice_message'],
            'enable_sandbox' => $_REQUEST['paypal_enable_sandbox']
        ];
        update_option('ash_paypal', $paypalOptions);

		$delivery_options = [
			'future_days' => $_REQUEST['delivery_future_days'],
			'available_days' => $_REQUEST['delivery_available_days']
		];
        update_option('ash_delivery', $delivery_options);
	}
}