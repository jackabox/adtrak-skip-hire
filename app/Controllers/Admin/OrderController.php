<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;

class OrderController extends Admin
{
	public function __construct() {
		self::instance();
	}

	public function menu() 
	{
		$this->addMenu('Orders', 'ash-orders', 'manage_options', [$this, 'index'], 'ash');
		$this->createMenu();
	}

	public function index()
	{
		echo 'index';
	}
}
