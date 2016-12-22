<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\View;

class PermitController extends Admin
{
	public function __construct()
	{
		self::instance();
	}

	public function menu() 
	{
		$this->addMenu('Permits', 'ash-permits', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Permits - Add', 'ash-permits-add', 'manage_options', [$this, 'create'], 'ash');
		$this->addMenu('Permits - Edit', 'ash-permits-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

	public function index() 
	{
		$permits = Permit::all();
		
		$link = [
			'edit' => admin_url('admin.php?page=ash-permits-edit&id='),
			'add' => admin_url('admin.php?page=ash-permits-add')
		];

		View::render('admin/permits/index.twig', [
			'permits' 		=> $permits,
			'link'			=> $link
		]);
	}

	public function create() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_add') {
			$this->store();
		}

		View::render('admin/permits/add.twig', []);
	}

	public function store()
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

				$url = admin_url('admin.php?page=ash-permits-edit&id=' . $permit->id);
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
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('permit_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url('admin-ajax.php?action=ash_permit_delete&id=' . $_GET['id'] . '&nonce=' . $nonce) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-permit') . '" class="ash-permit-delete">Delete</a>';
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

	public function update()
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

	public function destroy()
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