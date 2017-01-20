<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\Helper;
use Adtrak\Skips\Controllers\Front\SkipController;

class FrontController
{
	private static $instance = null;

	private $templates = [
	    'booking'       => 'booking.php',
		'skip-sizes' 	=> 'skips.php',
		'checkout' 		=> 'checkout.php',
		'confirmation' 	=> 'confirmation.php'
	];

    /**
     * FrontController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

    /**
     * @param $template
     * @return string
     */
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

    /**
     *
     */
    public function scripts()
	{
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
	}

    /**
     *
     */
    public function addActions()
	{
		add_action('ash_wrapper_start', [$this, 'wrapperStart']);
		add_action('ash_wrapper_end', [$this, 'wrapperEnd']);
	}

    /**
     *
     */
    public function wrapperStart()
	{
        $template = $this->templateLocater('globals/wrapper-start.php');
        include_once $template;
	}

    /**
     *
     */
    public function wrapperEnd()
	{
        $template = $this->templateLocater('globals/wrapper-end.php');
        include_once $template;
	}

    /**
     * @param $filename
     * @return string
     */
    protected function templateLocater($filename)
    {
        if ($overwrite = locate_template('adtrak-skips/' . $filename)) {
            $template = $overwrite;
        } else {
            $template = Helper::get('templates') . $filename;
        }

        return $template;
    }
}