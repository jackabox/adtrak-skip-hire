<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;

class SkipController extends Front
{
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
}