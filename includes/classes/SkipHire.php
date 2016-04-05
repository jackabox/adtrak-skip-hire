<?php 

class SkipHire 
{
    protected $plugin_name;
    protected $version;
    protected $prefix;

    public function __construct()
    {
        $this->plugin_name = 'ad_skip_hire';
        $this->version = '1.0.0';
        $this->prefix = 'adsh_';

        # dependanices
        $this->load_dependencies();

        # menu pages
        add_action('admin_menu', [$this, 'skip_admin_pages']);
    }

    public function load_dependencies()
    {
        // the class responsible for managing the orders
        require_once plugin_dir_path(__FILE__) . 'Orders.php';
    }

    public function skip_admin_pages() 
    {
        add_menu_page( 
            'Skip Hire Options', 
            'Skip Hire', 
            'read', 
            $this->plugin_name,
            [$this, 'skip_create_admin_page'], 
            '', 
            20
        );
    }

    public function skip_create_admin_page()
    {
        include_once plugin_dir_path(__FILE__) . '../pages/skips.php';
    }
}