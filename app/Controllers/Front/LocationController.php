<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Location;

class ConfirmationController
{
	private static $instance = null;
	public $skip;
	public $noSkip = false;

	public function __construct()
	{
		$this->addActions();
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function addActions()
	{
		// add_action('ash_checkout', [$this, 'checkout']);
	}

	protected function templateLocater($filename)
	{
		if ($overriden = locate_template('adtrak-skips/' . $filename)) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . $filename;
		}

		return $template;
	}
}