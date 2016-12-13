<?php namespace Adtrak\Windscreens\Controllers;

use Adtrak\Windscreens\Helper;

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
		wp_enqueue_script('adtrak-windscreens', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key=AIzaSyANv3jfCkGseTDZTsguGAxn2vP0aOF7Hlw&libraries=places');
	}
}