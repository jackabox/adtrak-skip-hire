<?php 

class ad_skip_hire 
{
    protected $plugin_name;
    protected $version;
    protected $prefix;
    protected $coupons;
    protected $locations;
    protected $permits;
    protected $orders;
    protected $skips;

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
        require_once plugin_dir_path(__FILE__) . 'Locations.php';
        require_once plugin_dir_path(__FILE__) . 'Permits.php';
        require_once plugin_dir_path(__FILE__) . 'Coupons.php';
        require_once plugin_dir_path(__FILE__) . 'Skips.php';
        require_once plugin_dir_path(__FILE__) . 'Orders.php';

        $this->locations = new ad_skip_hire_locations();
        $this->permits = new ad_skip_hire_permits();
        $this->coupons = new ad_skip_hire_coupons();
        $this->skips = new ad_skip_hire_skips();
        $this->orders = new ad_skip_hire_orders();
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

        add_submenu_page( 
            $this->plugin_name,
            'Skip Hire Settings', 
            'Settings', 
            'manage_options', 
            $this->plugin_name . '_options',
            [$this, 'skip_create_settings_page']
        );
    }

    public function skip_create_admin_page()
    {
        include_once plugin_dir_path(__FILE__) . '../pages/skips.php';
    }

    public function skip_create_settings_page()
    {
        include_once plugin_dir_path(__FILE__) . '../pages/skips.php';
    }
}