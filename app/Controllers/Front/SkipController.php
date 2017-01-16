<?php namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Models\Skip;

class SkipController
{
	private static $instance = null;
	public $skips;

    /**
     * SkipController constructor.
     */
    public function __construct()
	{
		$this->skips = Skip::all();
		$this->addActions();
	}

    /**
     * @return SkipController|null
     */
    public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

    /**
     *
     */
    public function addActions()
	{
		add_action('ash_before_skip_loop', [$this, 'beforeSkipLoop']);
		add_action('ash_after_skip_loop', [$this, 'afterSkipLoop']);
		add_action('ash_skip_loop', [$this, 'skipLoop']);
	}

    /**
     *
     */
    public function beforeSkipLoop()
	{
        $template = $this->templateLocator('skips/loop-start.php');
        include_once $template;
	}

    /**
     *
     */
    public function afterSkipLoop()
	{
        $template = $this->templateLocator('skips/loop-end.php');
        include_once $template;
	}

    /**
     *
     */
    public function skipLoop()
	{
		$skips = $this->skips;
		$postcode = null;

		if ($_REQUEST['ash_postcode']) $postcode = $_REQUEST['ash_postcode'];

        $template = $this->templateLocator('skips/loop.php');
		include_once $template;
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