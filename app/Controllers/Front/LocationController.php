<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Location;

class LocationController
{
	private static $instance = null;

	public $skip;

    /**
     * LocationController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

    /**
     * @return LocationController|null
     */
    public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function addActions()
	{
		// add_action('ash_checkout', [$this, 'checkout']);
	}

    /**
     * @param $filename
     * @return string
     */
    protected function templateLocator($filename)
	{
		if ($overwrite = locate_template('adtrak-skips/' . $filename)) {
			$template = $overwrite;
		} else {
			$template = Helper::get('templates') . $filename;
		}

		return $template;
	}
}