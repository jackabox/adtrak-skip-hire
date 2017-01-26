<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Order;
use Adtrak\Skips\View;

/**
 * Class OrderController
 * @package Adtrak\Skips\Controllers\Admin
 */
class OrderController extends Admin
{
    /**
     * OrderController constructor.
     */
	public function __construct() 
	{
		self::instance();
	}

    /**
     * Add menus, utilise parent function to push them to the menu
     */
	public function menu() 
	{
		$this->addMenu('Orders', 'ash-orders', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Orders - Edit', 'ash-orders-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

    /**
     * Default view for orders, show list of all orders.
     * Paginate results.
     * 
     * @return mixed
     */
	public function index()
	{
		# work out pages, offset, limit
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Order::count();
		$totalPages = ceil($total / $limit);

		# get results based on vars
		$orders = Order::orderBy('created_at', 'desc')
                    ->skip($offset)
                    ->take($limit)
                    ->get();

		# set the pagination
		$pagination = paginate_links( array(
    		'base'      => add_query_arg('pagenum', '%#%'),
    		'format'    => '',
    		'prev_text' => __('&laquo;', 'text-domain'),
    		'next_text' => __('&raquo;', 'text-domain'),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

        # set link urls
		$link = [
			'edit' => admin_url('admin.php?page=ash-orders-edit&id='),
			'add'  => admin_url('admin.php?page=ash-orders-add')
		];

		return View::render('orders/index.twig', [
			'orders' 	 => $orders,
			'link'		 => $link,
			'pagination' => $pagination
		]);
	}

    /**
     * Edit function, show order and what is needed.
     * If posted to, run the updater then show edit.
     */
	public function edit() 
	{
	    # Check if posted to
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'order_update') {
			$this->update();
		}

		$order = Order::findOrFail($_GET['id']);
		$items = $order->orderItems;

        return View::render('orders/edit.twig', [
            'order' 	=> $order,
            'items' 	=> $items
        ]);
	}

    /**
     * Small updater, update post, change status.
     */
	protected function update()
	{
		$order = Order::findOrFail($_GET['id']);
		$order->status = $_POST['ash_order_status'];
		$order->save();
	}
}
