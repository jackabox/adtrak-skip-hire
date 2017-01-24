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
		// work out if pages
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Order::count();
		$totalPages = ceil($total / $limit);

		// get results
		$orders = Order::orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();

		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		$link = [
			'edit' => admin_url('admin.php?page=ash-orders-edit&id='),
			'add'  => admin_url('admin.php?page=ash-orders-add')
		];

		View::render('admin/orders/index.twig', [
			'orders' 	=> $orders,
			'link'		=> $link,
			'pagination' => $pagination
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
		$items = $order->orderItems;

		if ($order) {
			View::render('admin/orders/edit.twig', [
				'order' 	=> $order,
				'items' 	=> $items,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the order you're looking for does not exist.";
		}
	}

	protected function update()
	{
		$order = Order::find($_GET['id']);
		$order->status = $_POST['ash_order_status'];
		$order->save();
	}
}
