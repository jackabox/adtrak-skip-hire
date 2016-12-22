<?php namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\View;

class DashboardController extends Admin
{
	public function __construct()
	{
		self::instance();
	}

	public function menu() 
	{
		$this->addMenu('Dashboard', 'ash', 'manage_options', [$this, 'index'], 'ash');
		$this->createMenu();
	}

	public function index() 
	{
		View::render('admin/dashboard.twig', []);
	}
}