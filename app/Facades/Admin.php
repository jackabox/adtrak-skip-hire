<?php 

namespace Adtrak\Skips\Facades;

class Admin 
{
	/* The instance. */
	protected static $instance = null;

	/**
	 * The menus to load content
	 * @var array
	 */
	protected $menuArray = [];


    /**
     * 	/**
     * Set up the instance of the class
     *
     * @since   2.0.0
     * @return  Admin|null
     */
    public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

    /**
     * Create menu functionality. Loops around all menus bound via add.
     *
     * @since   	2.0.0
     */
    public function createMenu()
	{
		foreach($this->menuArray as $menu) {
			if ($menu['parent'] === null) {
				add_menu_page($menu['title'], $menu['title'], $menu['capability'], $menu['slug'], $menu['uses'], $menu['icon'], $menu['position']);
			} else {
				add_submenu_page($menu['parent'], $menu['title'], $menu['title'], $menu['capability'], $menu['slug'], $menu['uses']);
			}
		}
	}


    /**
     * Add menu functionality. Sets up parent / child pages.
     *
     * @param $title
     * @param $slug
     * @param $capability
     * @param $uses
     * @param null $parent
     * @param int $priority
     * @param string $icon
     * @since 2.0.0
     */
    protected function addMenu($title, $slug, $capability, $uses, $parent = null, $priority = 100, $icon = '')
	{
		$menu = [
			'parent'     => $parent,
			'title'      => $title,
			'slug'       => $slug,	
			'capability' => $capability,		
			'uses'       => $uses,
			'icon'       => $icon,
			'priority'   => $priority,				
		];

		$this->menuArray[] = $menu;
	}
}