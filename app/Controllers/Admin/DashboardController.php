<?php

namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\View;
use Adtrak\Skips\Models\Order;

class DashboardController extends Admin
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
	{
		self::instance();
	}

    /**
     *
     */
    public function menu()
	{
		$this->addMenu('Dashboard', 'ash', 'manage_options', [$this, 'index'], 'ash');
		$this->createMenu();
	}

    /**
     *
     */
    public function index()
	{
        // last week / month  
        $lastMonth = date('Y-m-d', strtotime(date('Y-m-d') . "-1 month"));

        // get order counts
        $stats['orders_lifetime'] = Order::count();
        $stats['revenue_lifetime'] = Order::sum('total');
        $stats['orders_month'] = Order::where('created_at', '>=', $lastMonth)->count();
        $stats['revenue_month'] = Order::where('created_at', '>=', $lastMonth)->sum('total');
       
        // get orders (not delivered)
        $orders = Order::where('status', '!=', 'pending')->get();

		View::render('admin/dashboard.twig', [
            'orders'  => $orders,
            'stats' => $stats,
        ]);
	}
}