<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Skip;
use Billy\Framework\Facades\DB;

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
		$skips = Skip::all();
		$link = [
			'edit' => admin_url('admin.php?page=adskip-edit&id='),
			'add' => admin_url('admin.php?page=adskip-add')
		];

		View::render('admin/skips.twig', [
			'skips' 		=> $skips,
			'link'			=> $link
		]);
	}

	public function addSkip() 
	{

	}

	public function showSkip() 
	{
	}
}