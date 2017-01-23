<?php 

namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;

class SkipController extends Front
{
	public $skips;

    /**
     * SkipController constructor.
     */
    public function __construct()
	{
		$this->addActions();
	}

    /**
     *
     */
    public function addActions()
	{
		add_action('ash_skip_loop', [$this, 'loop']);
	}

    /**
     *
     */
    public function beforeLoop()
	{
        $template = $this->templateLocator('skips/header.php');
        include_once $template;
	}

    /**
     *
     */
    public function afterLoop()
	{
        $template = $this->templateLocator('skips/footer.php');
        include_once $template;
	}

    /**
     *
     */
    public function loop()
	{
		$this->beforeLoop();

		// work out if pages
		$limit = 2;		
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Skip::count();
		$totalPages = ceil($total / $limit);

		// get results
		$skips = Skip::orderBy('created_at', 'desc')->skip($offset)->take($limit)->get();

        $postcode = null;

        if (isset($_SESSION['ash_postcode'])) {
            $postcode = $_SESSION['ash_postcode'];
        }

        $template = $this->templateLocator('skips/loop.php');
		include_once $template;

		$this->pagination($totalPages, $pagenum);		

		$this->afterLoop();		
	}

	public function pagination($pages, $pagenum)
	{
		$pagination = paginate_links( array(
    		'base'      => add_query_arg( 'pagenum', '%#%' ),
    		'format'    => '',
    		'prev_text' => __( '&laquo;', 'text-domain' ),
    		'next_text' => __( '&raquo;', 'text-domain' ),
    		'total'     => $pages,
    		'current'   => $pagenum
		));

		$template = $this->templateLocator('skips/pagination.php');
		include_once $template;
	}
}