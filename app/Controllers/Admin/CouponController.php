<?php namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Coupon;
use Adtrak\Skips\View;

class CouponController extends Admin
{
	public function __construct()
	{
		self::instance();
	}

	public function menu() 
	{
		$this->addMenu('Coupons', 'ash-coupon', 'manage_options', [$this, 'index'], 'adskip');
		$this->addMenu('Coupons - Add', 'ash-coupon-add', 'manage_options', [$this, 'create'], 'adskip');
		$this->addMenu('Coupons - Edit', 'ash-coupon-edit', 'manage_options', [$this, 'edit'], 'adskip');
		$this->createMenu();
	}

	public function index() 
	{
		$coupons = Coupon::all();
		
		$link = [
			'edit' => admin_url('admin.php?page=ash-coupon-edit&id='),
			'add'  => admin_url('admin.php?page=ash-coupon-add')
		];

		View::render('admin/coupons/index.twig', [
			'coupons' 		=> $coupons,
			'link'			=> $link
		]);
	}

	public function create() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_add') {
			$this->store();
		}

		View::render('admin/coupons/add.twig', []);
	}

	public function store()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'coupon_add_nonce');
		$permission = true;
		$errors 	= [];

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
				$coupon 			= new Coupon;
				$coupon->code 		= $_REQUEST['code'];
				$coupon->amount 	= $_REQUEST['amount'];
				$coupon->type 		= $_REQUEST['type'];
				$coupon->starts 	= $_REQUEST['starts'];
				$coupon->expires 	= $_REQUEST['expires'];
				$coupon->save();

				$url = admin_url('admin.php?page=ash-coupon-edit&id=' . $coupon->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function edit()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_coupon_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=ash_coupon_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-coupon') . '" class="ash-coupon-delete">Delete</a>';
		} else {
			$button['delete'] = '';
		}

		$coupon = Coupon::find($_GET['id']);

		if ($coupon) {
			View::render('admin/coupons/edit.twig', [
				'coupon' 	=> $coupon,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the coupon you're looking for does not exist.";
		}
	}

	public function update()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'coupon_add_nonce');
		$permission = true;
		$errors 	= [];

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