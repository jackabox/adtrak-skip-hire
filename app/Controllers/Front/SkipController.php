<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Skip;

class SkipController
{
	private static $instance = null;
	public $skips;

	public function __construct()
	{
		// self::instance();
		$this->skips = Skip::all();

		$this->addActions();
	}

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function addActions()
	{
		add_action('ash_before_skip_loop', [$this, 'beforeSkipLoop']);
		add_action('ash_after_skip_loop', [$this, 'afterSkipLoop']);
		add_action('ash_skip_loop', [$this, 'skipLoop']);
	}

	public function beforeSkipLoop()
	{
		if ($overriden = locate_template('adtrak-skips/skips/loop-start.php')) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . 'skips/loop-start.php';
		}
		
		include_once $template;
	}

	public function afterSkipLoop()
	{
		if ($overriden = locate_template('adtrak-skips/skips/loop-end.php')) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . 'skips/loop-end.php';
		}
		
		include_once $template;
	}

	public function skipLoop() 
	{
		$skips = $this->skips;

		if ($overriden = locate_template('adtrak-skips/skips/loop.php')) {
			$template = $overriden;
		} else {
			$template = Helper::get('templates') . 'skips/loop.php';
		}

		include_once $template;
	}
}