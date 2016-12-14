<?php namespace Adtrak\Skips\Controllers;

class SkipController 
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function menu() 
	{
		add_submenu_page(
			'adskip',			
			__( 'Skips', 'adskip' ),
			'Skips',
			'manage_options',
			'adskip',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Skips', 'adskip' ),
			'Skips - Add',
			'manage_options',
			'adskip-add',
			[$this, 'addSkip'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Skips', 'adskip' ),
			'Skips - Edit',
			'manage_options',
			'adskip-edit',
			[$this, 'showSkip'],
			''
		);
	}

	public function index() 
	{
	}

	public function addSkip() 
	{
	}

	public function showSkip() 
	{
	}
}