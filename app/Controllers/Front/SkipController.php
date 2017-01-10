<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;

class SkipController
{
	private static $instance = null;

	public function __construct()
	{
		self::instance();
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function addActions() {
		add_action('adtrak_skips_before_location_form', $this->beforeLocationForm);
	}

	public function beforeLocationForm()
	{
		echo 'Before Loc';
	}
}