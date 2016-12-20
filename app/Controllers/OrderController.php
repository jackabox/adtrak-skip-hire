<?php 
namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Order;
use Billy\Framework\Facades\DB;

class OrderController
{
	private static $instance = null;

	public function __construct() {
		 add_shortcode('adtrak_skips', [$this, 'showLocationForm']);
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_submenu_page(
			'adskip',			
			__( 'Orders', 'adskip' ),
			'Orders',
			'manage_options',
			'ash-orders',
			[$this, 'index'],
			''
		);

		// add_submenu_page(
		// 	'adskip',			
		// 	__( 'Locations', 'adskip' ),
		// 	'Locations - Edit',
		// 	'manage_options',
		// 	'ash-orders-edit',
		// 	[$this, 'showLocation'],
		// 	''
		// );
	}

	public function index()
	{
		echo 'index';
	}
}
