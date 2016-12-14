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
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key=AIzaSyDSIzp9xC7lMLnHSGj7WbSFYUDgNvkO02g&libraries=places');
	}
}