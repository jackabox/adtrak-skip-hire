<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Permit;
use Billy\Framework\Facades\DB;

class PermitController 
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
			__( 'Permits', 'adskip' ),
			'Permits',
			'manage_options',
			'ash-permit',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Permits', 'adskip' ),
			'Permits - Add',
			'manage_options',
			'ash-permit-add',
			[$this, 'addPermit'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Permits', 'adskip' ),
			'Permits - Edit',
			'manage_options',
			'ash-permit-edit',
			[$this, 'showPermit'],
			''
		);
	}

	public function index() 
	{
		$permits = Permit::all();
		
		$link = [
			'edit' => admin_url('admin.php?page=ash-permit-edit&id='),
			'add' => admin_url('admin.php?page=ash-permit-add')
		];

		View::render('admin/permits/index.twig', [
			'permits' 		=> $permits,
			'link'			=> $link
		]);
	}

	public function addPermit() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_add') {
			$this->storePermit();
		}

		View::render('admin/permits/add.twig', []);
	}

	public function storePermit()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'permit_add_nonce');
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
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
				$permit 			= new Permit;
				$permit->name 		= $_REQUEST['title'];
				$permit->price 		= $_REQUEST['price'];
				$permit->save();

				$url = admin_url('admin.php?page=ash-permit-edit&id=' . $permit->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function showPermit()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_update') {
			$this->updatePermit();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('permit_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=permit_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-permit') . '" class="ash-permit-delete">Delete</a>';
		} else {
			$button['delete'] = '';
		}

		$permit = Permit::find($_GET['id']);

		if ($permit) {
			View::render('admin/permits/edit.twig', [
				'permit' 	=> $permit,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the permit you're looking for does not exist.";
		}
	}

	public function updatePermit()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'permit_add_nonce');
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
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
				$permit 			= Permit::findOrFail($_REQUEST['id']);
				$permit->name 		= $_REQUEST['title'];
				$permit->price 		= $_REQUEST['price'];				
				$permit->save();

				echo "Permit has been updated";
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function deletePermit()
	{
		$permission = check_ajax_referer('permit_delete_nonce', 'nonce', false);

        if ($permission === false) {
            echo 'Permission Denied';
        } else {
			$permit = Permit::findOrFail($_REQUEST['id']);
			$permit->delete();
			echo 'success';
		}

        die();
	}
}