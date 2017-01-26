<?php 
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Permit;
use Adtrak\Skips\View;

/**
 * Class PermitController
 * @package Adtrak\Skips\Controllers\Admin
 */
class PermitController extends Admin
{
    /**
     * PermitController constructor.
     */
	public function __construct()
	{
		self::instance();
	}

    /**
     * Set up the menus, implement them using parent class
     */
	public function menu() 
	{
		$this->addMenu('Permits', 'ash-permits', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Permits - Add', 'ash-permits-add', 'manage_options', [$this, 'create'], 'ash');
		$this->addMenu('Permits - Edit', 'ash-permits-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

    /**
     * Work out the pages, set up the overall view of skips
     *
     * @return mixed
     */
	public function index() 
	{
		# work out if pages, results, offset
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Permit::count();
		$totalPages = ceil($total / $limit);

		# get results with requirements
		$permits = Permit::orderBy('created_at', 'desc')
                        ->skip($offset)
                        ->take($limit)
                        ->get();

		# set up the pagination
		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		# create the links
		$link = [
			'edit' => admin_url('admin.php?page=ash-permits-edit&id='),
			'add' => admin_url('admin.php?page=ash-permits-add')
		];

		return View::render('permits/index.twig', [
			'permits' 		=> $permits,
			'link'			=> $link,
			'pagination'	=> $pagination
		]);
	}

    /**
     * Return the add view, if posted, move into the store function
     *
     * @return mixed
     */
	public function create() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_add') {
			$this->store();
		}

		return View::render('permits/add.twig', []);
	}

    /**
     * Store function, triggered on post, catches errors, refreshes outside
     */
	public function store()
	{
		$errors = [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

        if (!empty($errors)) {
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

    /**
     * Edit function for permit, adds delete to view.
     *
     * @return mixed
     */
	public function edit()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'permit_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('permit_delete_nonce');
			$delete = 'or <a href="' . admin_url('admin-ajax.php?action=ash_permit_delete&id=' . $_GET['id'] . '&nonce=' . $nonce) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-permit') . '" class="ash-permit-delete">Delete</a>';
		} else {
			$delete = '';
		}

		$permit = Permit::findOrFail($_GET['id']);

        return View::render('permits/edit.twig', [
            'permit' 	=> $permit,
            'delete'	=> $delete
        ]);

	}

    /**
     * Update function for the Permit, triggers on post
     */
	public function update()
	{
		$errors 	= [];

		if (empty($_REQUEST['title'])) {
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

    /**
     * Ajax function to destory the Permit if valid.
     */
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