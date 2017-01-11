<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Controllers\Front\SkipController;

class FrontController
{
	private $skip;

	private static $instance = null;

	private $templates = [
		'skips' 		=> 'ash-skips.php',
		'checkout' 		=> 'ash-checkout.php',
		'confirmation' 	=> 'ash-confirmation.php'
	];

	public function __construct()
	{
		// $this->skip = new SkipController;
	}

	private static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function loadTemplates($template) 
	{
		foreach ($this->templates as $page => $file) {
			if (is_page($page)) {
				$overriden = locate_template($file);
		
				if ($overriden) {
					$template = $overriden;
				} else {
					$template = Helper::get('templates') . $file;
				}
			}
		}

		return $template;
	}

	public function scripts()
	{
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
	}
}