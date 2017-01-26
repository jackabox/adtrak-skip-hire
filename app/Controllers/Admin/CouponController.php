<?php
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Coupon;
use Adtrak\Skips\View;

/**
 * Class CouponController
 * @package Adtrak\Skips\Controllers\Admin
 */
class CouponController extends Admin
{

    /**
     * CouponController constructor.
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
		$this->addMenu('Coupons', 'ash-coupons', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Coupons - Add', 'ash-coupons-add', 'manage_options', [$this, 'create'], 'ash');
		$this->addMenu('Coupons - Edit', 'ash-coupons-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

    /**
     * @return mixed
     */
    public function index()
	{
		# work out if pages
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Coupon::count();
		$totalPages = ceil($total / $limit);

		# get results
		$coupons = Coupon::orderBy('created_at', 'desc')
                        ->skip($offset)
                        ->take($limit)
                        ->get();

		# pagination using WP in built function
		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		# Links for edit/add
		$link = [
			'edit' => admin_url('admin.php?page=ash-coupons-edit&id='),
			'add'  => admin_url('admin.php?page=ash-coupons-add')
		];

		# render the view
		return View::render('coupons/index.twig', [
			'coupons'    => $coupons,
			'link'       => $link,
			'pagination' => $pagination
		]);
	}

    /**
     * @return mixed
     */
    public function create()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_add') {
			$this->store();
		}

		return View::render('coupons/add.twig', []);
	}

    /**
     *
     */
    public function store()
	{
		$errors = [];

		if (empty($_REQUEST['code'])) {
			$errors[] = 'Please enter a name.';
		}

        if (! empty($errors)) {
			echo '<ul>';
			foreach($errors as $error) {
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		} else {	
			try {
				$coupon 			= new Coupon;
				$coupon->code 		= $_REQUEST['code'];
				$coupon->amount 	= $_REQUEST['amount'];
				$coupon->type 		= $_REQUEST['type'];
				$coupon->starts 	= $_REQUEST['starts'];
				$coupon->expires 	= $_REQUEST['expires'];
				$coupon->save();

				$url = admin_url('admin.php?page=ash-coupons-edit&id=' . $coupon->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

    /**
     * @return mixed
     */
    public function edit()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_coupon_delete_nonce');
			$delete = 'or <a href="' . admin_url( 'admin-ajax.php?action=ash_coupon_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-coupons') . '" class="ash-coupon-delete">Delete</a>';
		} else {
			$delete = '';
		}

		$coupon = Coupon::findOrFail($_GET['id']);

        return View::render('coupons/edit.twig', [
            'coupon' 	=> $coupon,
            'delete'	=> $delete
        ]);
	}

    /**
     *
     */
    public function update()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'coupon_add_nonce');
		$permission = true;
		$errors = [];

		if (empty($_REQUEST['code'])) {
			$errors[] = 'Please enter a name.';
		}

        if ($permission === false) {
            echo 'Permission Denied';
        } else if (!empty($errors)) {
			echo '<ul>';
			foreach($errors as $error) {
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		} else {	
			try {
				$coupon 			= Coupon::findOrFail($_REQUEST['id']);
				$coupon->code 		= $_REQUEST['code'];
				$coupon->amount 	= $_REQUEST['amount'];
				$coupon->type 		= $_REQUEST['type'];
				$coupon->starts 	= $_REQUEST['starts'];
				$coupon->expires 	= $_REQUEST['expires'];			
				$coupon->save();

				echo "Coupon has been updated";
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

    /**
     *
     */
    public function destroy()
	{
		$permission = check_ajax_referer('ash_coupon_delete_nonce', 'nonce', false);

        if ($permission === false) {
            echo 'Permission Denied';
        } else {
			$coupon = Coupon::findOrFail($_REQUEST['id']);
			$coupon->delete();
			echo 'success';
		}

        die();
	}
}