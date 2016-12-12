<?php namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\View;
use Adtrak\Windscreens\Models\Location;
use Adtrak\Windscreens\Controllers\LocationController;
use Adtrak\Windscreens\Helper;

class AdminController
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_menu_page(
			__( 'Windscreens', 'adwind' ),
			'Windscreens',
			'manage_options',
			'adwind',
			'',
			'none',
			100
		);

		$locs = LocationController::instance();
		$locs->menu();

		add_submenu_page(
			'adwind',			
			__( 'Settings', 'adwind' ),
			'Settings',
			'manage_options',
			'adwind-settings',
			[$this, 'menu_render'],
			''
		);
	}

	public function scripts()
	{
		if (is_admin()) {
            wp_enqueue_style('adtrak-windscreens', Helper::assetUrl('css/windscreens.css'), null);
            wp_enqueue_script('adtrak-windscreens-ajax', Helper::assetUrl('js/locations.js'), ['jquery'], '', true);
            wp_localize_script('adtrak-windscreens-ajax', 'WSAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);
			// bind this to the page?
			wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key=AIzaSyANv3jfCkGseTDZTsguGAxn2vP0aOF7Hlw&libraries=places');
        }
	}
}