<?php
namespace Adtrak\Skips\Controllers\Front;

use Adtrak\Skips\Facades\Front;
use Adtrak\Skips\Models\Skip;

/**
 * Class SkipController
 * @package Adtrak\Skips\Controllers\Front
 */
class SkipController extends Front
{
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
		add_action('ash_skip_loop', [$this, 'loop'], 10, 2);
	}

    /**
     * before the loop, show the header info
     */
    public function beforeLoop()
	{
        $template = $this->templateLocator('skips/header.php');
        include_once $template;
	}

    /**
     * after the loop, show the footer info
     */
    public function afterLoop()
	{
        $template = $this->templateLocator('skips/footer.php');
        include_once $template;
	}

    /**
     * Loop through avialable skips, order by.
     *
     * @param int $limit
     * @param string $orderby
     */
    public function loop($limit = 10, $orderby = 'name')
	{
		$this->beforeLoop();

		# set up the params
		$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
		$offset = $limit * ($pagenum - 1);
		$total = Skip::count();
		$totalPages = ceil($total / $limit);

		# get the skips available
		$skips = Skip::orderBy($orderby, 'asc')
                    ->skip($offset)
                    ->take($limit)
                    ->get();

		# session post code, find it, else null
        if (isset($_SESSION['ash_postcode'])) {
            $postcode = $_SESSION['ash_postcode'];
        } else {
            $postcode = null;
        }

        $template = $this->templateLocator('skips/loop.php');
		include_once $template;

		$this->pagination($totalPages, $pagenum);		

		$this->afterLoop();		
	}

    /**
     * @param $pages
     * @param $pagenum
     */
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