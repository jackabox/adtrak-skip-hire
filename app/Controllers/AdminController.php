<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Controllers\Admin\DashboardController;
use Adtrak\Skips\Controllers\Admin\LocationController;
use Adtrak\Skips\Controllers\Admin\SkipController;
use Adtrak\Skips\Controllers\Admin\PermitController;
use Adtrak\Skips\Controllers\Admin\CouponController;
use Adtrak\Skips\Controllers\Admin\OrderController;
use Adtrak\Skips\Helper;

class AdminController
{
	protected static $instance = null;
	protected $permit;
	protected $coupon;
	protected $skip;
	protected $location;
	protected $dashboard;
	
	public function __construct()
	{
		$this->dashboard = new DashboardController;
		$this->permit = new PermitController;
		$this->coupon = new CouponController;
		$this->location = new LocationController;
		$this->skip = new SkipController;
		$this->order = new OrderController;
	}
	
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
     *
     */
    public function showSettings()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'settings_save') {
			$this->updateSettings();
		}

		$options = [];
		$options['gmaps_api'] = get_option('ash_google_maps_api', '');

		View::render('admin/settings.twig', [
			'options' 		=> $options
		]);
	}

    /**
     *
     */
    public function updateSettings()
	{
		update_option('ash_google_maps_api', $_REQUEST['gmaps_api']);
	}
}