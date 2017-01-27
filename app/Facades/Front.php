<?php 

namespace Adtrak\Skips\Facades;

use Adtrak\Skips\Helper;

class Front 
{
	/* The instance. */
	protected static $instance = null;

    /**
     * 
     * Set up the instance of the class
     *
     * @since   2.0.0
     * @return  Admin|null
     */
    public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	/**
     * @param $filename
     * @return string
     */
    protected function templateLocator($filename)
    {
        $overwrite = locate_template('adtrak-skips/' . $filename);

        if ($overwrite) {
            $template = $overwrite;
        } else {
            $template = Helper::get('templates') . $filename;
        }

        return $template;
    }
}