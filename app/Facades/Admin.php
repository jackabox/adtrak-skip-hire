<?php namespace Adtrak\Skips\Facades;

class Admin 
{
	protected static $instance = null;
	protected $menuArray = array();

	public function __construct() { }

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

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