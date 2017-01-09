<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\View;

class OrderController extends Admin
{
	public function __construct() 
	{
		self::instance();
	}

	public function menu() 
	{
		$this->addMenu('Orders', 'ash-orders', 'manage_options', [$this, 'index'], 'ash');
		$this->createMenu();
	}

	public function index()
	{
		$orders = Order::all();

		$link = [
			'edit' => admin_url('admin.php?page=ash-orders-edit&id='),
			'add'  => admin_url('admin.php?page=ash-orders-add')
		];

		View::render('admin/orders/index.twig', [
			'orders' 	=> $orders,
			'link'		=> $link
		]);

	}
}
