<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\Helper;

class FrontController
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function scripts()
	{
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
	}
}