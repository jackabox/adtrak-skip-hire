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
		$this->addMenu('Orders - Edit', 'ash-orders-edit', 'manage_options', [$this, 'edit'], 'ash');
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

	public function edit() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'order_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_order_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=ash_order_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-orders') . '" class="ash-order-delete">Delete</a>';
		} else {
			$button['delete'] = '';
		}

		$order = Order::find($_GET['id']);

		if ($order) {
			View::render('admin/orders/edit.twig', [
				'order' 	=> $order,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the order you're looking for does not exist.";
		}
	}
}
