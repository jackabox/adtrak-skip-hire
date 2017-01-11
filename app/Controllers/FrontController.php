<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Controllers\Front\SkipController;

class FrontController
{
	private $skip;

	private static $instance = null;

	private $templates = [
		'skips' 		=> 'skips.php',
		'checkout' 		=> 'checkout.php',
		'confirmation' 	=> 'confirmation.php'
	];

	public function __construct()
	{
		// $this->skip = new SkipController;
		$this->addActions();
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

	public function addActions()
	{
		add_action('ash_wrapper_start', [$this, 'wrapperStart']);
		add_action('ash_wrapper_end', [$this, 'wrapperEnd']);
	}

	public function wrapperStart()
	{
		if ($overriden = locate_template('adtrak-skips/globals/wrapper-start.php')) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . 'globals/wrapper-start.php';
		}
		
		include_once $template;
	}

	public function wrapperEnd()
	{
		if ($overriden = locate_template('adtrak-skips/globals/wrapper-end.php')) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . 'globals/wrapper-end.php';
		}
		
		include_once $template;
	}
}