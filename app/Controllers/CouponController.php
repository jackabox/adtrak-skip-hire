<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Coupon;
use Billy\Framework\Facades\DB;

class CouponController 
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
			__( 'Coupons', 'adskip' ),
			'Coupons',
			'manage_options',
			'ash-coupon',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Coupons', 'adskip' ),
			'Coupons - Add',
			'manage_options',
			'ash-coupon-add',
			[$this, 'addCoupon'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Coupons', 'adskip' ),
			'Coupons - Edit',
			'manage_options',
			'ash-coupon-edit',
			[$this, 'showCoupon'],
			''
		);
	}

	public function index() 
	{
		$coupons = Coupon::all();
		
		$link = [
			'edit' => admin_url('admin.php?page=ash-coupon-edit&id='),
			'add' => admin_url('admin.php?page=ash-coupon-add')
		];

		View::render('admin/coupons/index.twig', [
			'coupons' 		=> $coupons,
			'link'			=> $link
		]);
	}

	public function addCoupon() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_add') {
			$this->storeCoupon();
		}

		View::render('admin/coupons/add.twig', []);
	}

	public function storeCoupon()
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

	public function showCoupon()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'coupon_update') {
			$this->updateCoupon();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('coupon_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=coupon_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-coupon') . '" class="ash-coupon-delete">Delete</a>';
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

	public function updateCoupon()
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

	public function deleteCoupon()
	{
		$permission = check_ajax_referer('coupon_delete_nonce', 'nonce', false);

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