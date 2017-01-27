<?php
namespace Adtrak\Skips\Controllers\Admin;

use Adtrak\Skips\Facades\Admin;
use Adtrak\Skips\Models\Skip;
use Adtrak\Skips\View;

/**
 * Class SkipController
 * @package Adtrak\Skips\Controllers\Admin
 */
class SkipController extends Admin
{
    /**
     * SkipController constructor.
     */
	public function __construct()
	{
		self::instance();
	}

    /**
     * Bind menus to parent class, create them, instance them.
     */
	public function menu() 
	{
		$this->addMenu('Skips', 'ash-skips', 'manage_options', [$this, 'index'], 'ash');
		$this->addMenu('Skips - Add', 'ash-skips-add', 'manage_options', [$this, 'create'], 'ash');
		$this->addMenu('Skips - Edit', 'ash-skips-edit', 'manage_options', [$this, 'edit'], 'ash');
		$this->createMenu();
	}

    /**
     * Overall view for skips, displays them paginated
     *
     * @return mixed
     */
	public function index() 
	{
		# work out if pages / results to pull back
		$limit = 16;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Skip::count();
		$totalPages = ceil($total / $limit);

		# get results
		$skips = Skip::orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();

		# Set up the pagination
		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $totalPages,
    		'current'   => $pagenum
		));

		# Create the links
		$link = [
			'edit' => admin_url('admin.php?page=ash-skips-edit&id='),
			'add'  => admin_url('admin.php?page=ash-skips-add')
		];

		# return the view
		return View::render('admin/skips/index.twig', [
			'skips' 	 => $skips,
			'link'		 => $link,
			'pagination' => $pagination
		]);
	}

    /**
     * Return the view to create the skips
     *
     * @return mixed
     */
	public function create() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'skip_add') {
			$this->store();
		}

		# return the view
		return View::render('admin/skips/add.twig', []);
	}

    /**
     * Store the skip, check for any errors.
     */
	public function store()
	{
		$errors = [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

		if (empty($_REQUEST['price'])) {
			$errors[] = 'Please enter a price.';
		}

        if (!empty($errors)) {
			echo '<ul>';
			foreach($errors as $error) {
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		} else {
			$uploadedImage = $this->handleImageUpload('uploadimage');

			try {
				$skip 				= new Skip;
				$skip->name 		= $_REQUEST['title'];
				$skip->width 		= $_REQUEST['width'];
				$skip->height 		= $_REQUEST['height'];
				$skip->length 		= $_REQUEST['length'];
				$skip->capacity 	= $_REQUEST['capacity'];
				$skip->price 		= $_REQUEST['price'];
				$skip->description 	= $_REQUEST['description'];
				
				if($uploadedImage) {
					$skip->image_path   = $uploadedImage['file'];
					$skip->image_url    = $uploadedImage['url'];
				}

				$skip->save();

				$url = admin_url('admin.php?page=ash-skips-edit&id=' . $skip->id);
				echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
				echo '<script>window.location.href=' . $url . ';</script>';
				die();
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

    /**
     * Edit view / display form of a created skip
     *
     * @return mixed
     */
	public function edit() 
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'skip_update') {
			$this->update();
		}
		
 		if (current_user_can('delete_posts')) {
			$nonce = wp_create_nonce('ash_skip_delete_nonce');
			$delete = 'or <a href="' . admin_url( 'admin-ajax.php?action=ash_skip_delete&id=' . $_GET['id'] . '&nonce=' . $nonce ) . '" data-id="' . $_GET['id'] . '" data-nonce="' . $nonce . '" data-redirect="' . admin_url('admin.php?page=ash-skips') . '" class="ash-skip-delete">Delete</a>';
		} else {
			$delete = '';
		}

		$skip = Skip::findOrFail($_GET['id']);

        return View::render('admin/skips/edit.twig', [
            'skip' 		=> $skip,
            'delete'	=> $delete
        ]);
	}

    /**
     * Update function for skips, process the update on error check
     */
	public function update()
	{
		$errors = [];

		if (empty($_REQUEST['title'])) {
			$errors[] = 'Please enter a name.';
		}

		if (empty($_REQUEST['price'])) {
			$errors[] = 'Please enter a price.';
		}

        if (!empty($errors)) {
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
				
				$uploadedImage = $this->handleImageUpload('uploadimage');

				if($uploadedImage) {
					$this->handleImagedelete($skip->image_path);

					$skip->image_path   = $uploadedImage['file'];
					$skip->image_url    = $uploadedImage['url'];
				}

				$skip->save();

				echo "Skip has been updated";
			} catch (Exception $e) {
				 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
        }
	}

    /**
     * Delete function via Ajax
     */
	public function destroy()
	{
		$permission = check_ajax_referer('ash_skip_delete_nonce', 'nonce', false);

        if ($permission === false) {
            echo 'Permission Denied';
        } else {
			$skip = Skip::findOrFail($_REQUEST['id']);
			$this->handleImageDelete($skip->image_path);
			$skip->delete();
			echo 'success';
		}

		die();
	}

    /**
     * Handles the file upload
     *
     * @param $fieldname
     * @return array|null
     */
	protected function handleImageUpload($fieldname) 
	{
		$uploadedImage = null;

		if (isset($_FILES) && isset($_FILES[$fieldname]) && (0 === $_FILES[$fieldname]['error'])) {
			if (!function_exists( 'wp_handle_upload')) 
				require_once(ABSPATH . 'wp-admin/includes/file.php');

			$upload_overrides = ['test_form' => false];
			$uploadedImage = wp_handle_upload($_FILES[$fieldname], $upload_overrides);
			
			if (! $uploadedImage) {
				echo "Possible file upload attack!\n";
			}
		}

		return $uploadedImage;
	}

    /**
     * Handle the deletion of the image.
     *
     * @param null $file
     */
	protected function handleImageDelete($file = null)
	{
		if($file !== null) {
			unlink($file);
		}
	}
}
