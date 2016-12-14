<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Location;
use Adtrak\Skips\Controllers\LocationController;
use Adtrak\Skips\Controllers\SkipController;
use Adtrak\Skips\Helper;

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
			__( 'Skips', 'adskip' ),
			'Skips',
			'manage_options',
			'adskip',
			'',
			'', //none',
			100
		);

		$skips = SkipController::instance()->menu();
		$locs = LocationController::instance()->menu();

		
	}

	public function scripts()
	{
		if (is_admin()) {
            wp_enqueue_style('adtrak-skips', Helper::assetUrl('css/skips.css'), null);
            wp_enqueue_script('adtrak-skips-ajax', Helper::assetUrl('js/admin.js'), ['jquery'], '', true);
            wp_localize_script('adtrak-skips-ajax', 'SHajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
			wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key=AIzaSyDSIzp9xC7lMLnHSGj7WbSFYUDgNvkO02g&libraries=places');
        }
	}
}