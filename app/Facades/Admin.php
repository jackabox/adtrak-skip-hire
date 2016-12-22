<?php namespace Adtrak\Skips\Facades;

class Admin 
{
	/* The instance. */
	protected static $instance = null;

	/**
	 * The menus to load content
	 * @var array
	 */
	protected $menuArray = array();

	/**
	 * Set up the instance of the class
	 *
	 * @version 	1.0.0
	 * @since   	2.0.0
	 * @author 		Jack Whiting 	 
	 */
	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	/**
	 * Create menu functionality. Loops around all menus bound via add.
	 *
	 * @version 	1.0.0
	 * @since   	2.0.0
	 * @author 		Jack Whiting 	 
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
	 * @param 		string 		$title 
	 * @param 		string 		$slug 
	 * @param 		string 		$capability 
	 * @param 		array 		$uses
	 * @param 		mixed 		$parent
	 * @param 		integer 	$priority 
	 * @param 		string 		$icon
	 *
	 * @version 	1.0.0
	 * @since   	2.0.0
	 * @author 		Jack Whiting 	 
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