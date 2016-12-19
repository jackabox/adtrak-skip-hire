<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\View;
use Adtrak\Skips\Models\Skip;
use Billy\Framework\Facades\DB;

class SkipController 
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
			__( 'Skips', 'adskip' ),
			'Skips',
			'manage_options',
			'adskip',
			[$this, 'index'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Skips', 'adskip' ),
			'Skips - Add',
			'manage_options',
			'adskip-add',
			[$this, 'addSkip'],
			''
		);

		add_submenu_page(
			'adskip',			
			__( 'Skips', 'adskip' ),
			'Skips - Edit',
			'manage_options',
			'adskip-edit',
			[$this, 'showSkip'],
			''
		);
	}

	public function index() 
	{
		$skips = Skip::all();
		$link = [
			'edit' => admin_url('admin.php?page=adskip-edit&id='),
			'add' => admin_url('admin.php?page=adskip-add')
		];

		View::render('admin/skips.twig', [
			'skips' 		=> $skips,
			'link'			=> $link
		]);
	}

	public function addSkip() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'skip_add') {
			$this->storeSkip();
		}

		if (current_user_can('edit_posts')) {
            $nonce = wp_create_nonce('skip_add_nonce');
            $button['save'] = '<a href="' . admin_url('admin.php?page=adskip-add&action=skip_add&nonce=' . $nonce) . '" class="button adskip-add">Save</a>';
        } else {
			$button['save'] = '';
		}

		View::render('admin/skip-add.twig', [
			'button'	=> $button
		]);
	}

	public function storeSkip()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'skip_add_nonce');
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

		if (empty($_REQUEST['price'])) {
			$errors[] = 'Please enter a price.';
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
				$skip 				= new Skip;
				$skip->name 		= $_REQUEST['title'];
				$skip->width 		= $_REQUEST['width'];
				$skip->height 		= $_REQUEST['height'];
				$skip->length 		= $_REQUEST['length'];
				$skip->capacity 	= $_REQUEST['capacity'];
				$skip->price 		= $_REQUEST['price'];
				$skip->description 	= $_REQUEST['description'];
				$skip->save();

				$url = admin_url('admin.php?page=adskip-edit&id=' . $skip->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function showSkip() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'skip_update') {
			$this->updateSkip();
		}

		$button = [
			'delete' => ''
		];

 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('skip_delete_nonce');
			$button['delete'] = 'or <a href="' . admin_url( 'admin-ajax.php?action=skip_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=adskip') . '" class="adskip-delete">Delete</a>';
		}

		$skip = Skip::find($_GET['id']);

		if ($skip) {
			View::render('admin/skip-edit.twig', [
				'skip' 		=> $skip,
				'button'	=> $button
			]);
		} else {
			echo "Sorry, the skip you're looking for does not exist.";
		}
	}

	public function updateSkip()
	{
		// $permission = wp_verify_nonce($_GET['nonce'], 'skip_add_nonce');
		$permission = true;
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

		if (empty($_REQUEST['price'])) {
			$errors[] = 'Please enter a price.';
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
				$skip 				= Skip::findOrFail($_REQUEST['id']);
				$skip->name 		= $_REQUEST['title'];
				$skip->width 		= $_REQUEST['width'];
				$skip->height 		= $_REQUEST['height'];
				$skip->length 		= $_REQUEST['length'];
				$skip->capacity 	= $_REQUEST['capacity'];
				$skip->price 		= $_REQUEST['price'];
				$skip->description 	= $_REQUEST['description'];
				$skip->save();

				echo "Skip has been updated";
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

	public function deleteSkip()
	{
		$permission = check_ajax_referer('skip_delete_nonce', 'nonce', false);

        if ($permission === false) {
            echo 'Permission Denied';
        } else {
			$skip = Skip::findOrFail($_REQUEST['id']);
			$skip->delete();
			echo 'success';
		}

        die();
	}
}