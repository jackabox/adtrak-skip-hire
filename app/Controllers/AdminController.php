<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Controllers\LocationController;
use Adtrak\Skips\Controllers\SkipController;
use Adtrak\Skips\Controllers\Admin\PermitController;
use Adtrak\Skips\Helper;

class AdminController
{
	protected static $instance = null;
	protected $permit;
	
	public function __construct()
	{
		$this->permit = new PermitController;
	}
	
	public static function instance()
	{
		null === self::$instance and self::$instance = new self;
		return self::$instance;
	}
	
	public function menu() 
	{
		add_menu_page(
					__( 'Skips', 'adskip' ),
					'Skips',
					'manage_options',
					'adskip',
					'',
					'', //n		one',
			100
		);

		\Adtrak\Skips\Controllers\OrderController::instance()->menu();
		\Adtrak\Skips\Controllers\SkipController::instance()->menu();
		\Adtrak\Skips\Controllers\LocationController::instance()->menu();
		$this->permit->Menu();

		$coupon = \Adtrak\Skips\Controllers\CouponController::instance();
		$coupon->menu();

		add_submenu_page(
			'adskip',			
			__( 'Settings', 'adskip' ),
			'Settings',
			'manage_options',
			'ash-settings',
			[$this, 'showSettings'],
			''
		);
	}

	public function scripts()
	{
		wp_enqueue_style('adtrak-skips', Helper::assetUrl('css/skips.css'), null);
		wp_enqueue_script('adtrak-skips-ajax', Helper::assetUrl('js/admin.js'), ['jquery'], '', true);
		wp_localize_script('adtrak-skips-ajax', 'SHajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
		wp_enqueue_script('maps-api', '//m		aps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
	}

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

	public function updateSettings()
	{
		update_option('ash_google_maps_api', $_REQUEST['gmaps_api']);
	}
}