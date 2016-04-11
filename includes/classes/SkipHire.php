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
    protected $templates;
    protected $paypal;

    public function __construct()
    {
        $this->plugin_name = 'ad_skip_hire';
        $this->version = '1.0.0';
        $this->prefix = 'adsh_';

        # dependanices
        $this->load_dependencies();

        # menu pages
        add_action( 'admin_menu', [$this, 'skip_admin_pages'] );

        # shortcodes
        add_shortcode( 'ash_postcode_form', [$this, 'build_postcode_form'] );
        add_shortcode( 'ash_booking_form', [$this, 'booking_form_director'] );

        # javascript
        add_action( 'wp_enqueue_scripts', [$this, 'load_javascript'] );

        # css 
    }

    /**
     * load in dependancies, we may not need all the extra calls here
     */
    public function load_dependencies()
    {
        // the class responsible for managing the orders
        require_once plugin_dir_path( __FILE__ ) . 'Locations.php';
        require_once plugin_dir_path( __FILE__ ) . 'Permits.php';
        require_once plugin_dir_path( __FILE__ ) . 'Coupons.php';
        require_once plugin_dir_path( __FILE__ ) . 'Skips.php';
        require_once plugin_dir_path( __FILE__ ) . 'Orders.php';
        require_once plugin_dir_path( __FILE__ ) . 'WpGeoQuery.php';
        require_once plugin_dir_path( __FILE__ ) . 'PayPal.php';

        $this->locations = new ad_skip_hire_locations();
        $this->permits = new ad_skip_hire_permits();
        $this->coupons = new ad_skip_hire_coupons();
        $this->skips = new ad_skip_hire_skips();
        $this->orders = new ad_skip_hire_orders();
        $this->paypal = new ad_paypal_interface();
    }

    public function load_javascript()
    {
        wp_register_script( 'jquery', plugins_url( '../../js/jquery-2.2.3.min.js', __FILE__ ), '', '2.2.3');
        wp_register_script( 'google_maps_api', 'https://maps.googleapis.com/maps/api/js', '', '', true );
        wp_register_script( 'ash_custom', plugins_url( '../../js/custom.min.js', __FILE__ ), ['jquery'], '1.0.0', true);
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('google_maps_api');
        wp_enqueue_script('ash_custom');
    }

    public function load_stylesheets()
    {

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

    /**
     * create the admin dashboard for the skip plugin
     */
    public function skip_create_admin_page( )
    {
        include_once plugin_dir_path(__FILE__) . '../pages/skips.php';
    }

    /**
     * create the admin page for managing settings
     */
    public function skip_create_settings_page( )
    {
        include_once plugin_dir_path(__FILE__) . '../pages/skips.php';
    }

    public function booking_form_director ( $args = [] )
    {
        $postcode = (isset($_REQUEST['ash_postcode'])) ? $_REQUEST['ash_postcode'] : NULL; 
        $lat = (isset($_REQUEST['ash_lat'])) ? $_REQUEST['ash_lat'] : NULL; 
        $lng = (isset($_REQUEST['ash_lng'])) ? $_REQUEST['ash_lng'] : NULL; 
        $skip = (isset($_REQUEST['ash_skip_id'])) ? $_REQUEST['ash_skip_id'] : NULL; 
        
        if ( $skip == null && $lat != null ):
            # run the geo query
            $locations = new ASH_WP_Query_Geo([
                'post_status' => 'publish',
                'post_type' => 'ash_locations',
                'posts_per_page' => -1,
                'lat' => $lat,
                'lng' =>  $lng,
                'distance' => 10
            ]);

            if( $locations->found_posts > 0 ) {
                // $locations->reset_postdata();
                $this->build_skip_form( $postcode );
            } else {
                echo "<p>We don't deliver skips to your location sorry.</p>";
            }

        elseif ( $skip != null ): 
            var_dump($_POST);

            if( isset( $_POST['ash_submit'] ) ): 
                foreach( $_POST as $key => $entry ):
                    if(
                        $key == 'ash_title' ||
                        $key == 'ash_forename' ||
                        $key == 'ash_surname' ||
                        $key == 'ash_email' ||
                        $key == 'ash_phone' ||
                        $key == 'ash_delivery_address_1' ||
                        $key == 'ash_delivery_city' ||
                        $key == 'ash_delivery_county' ||
                        $key == 'ash_delivery_postcode' ||
                        $key == 'ash_delivery_date' ||
                        $key == 'ash_delivery_time' ||
                        $key == 'ash_skip_id'
                    ):
                        if( ( $entry == NULL ) || empty( $entry ) ):
                            echo "<p>There were errors with the form. Please fix them to proceed.</p>";
                            $this->build_booking_form();
                            $pass = false;
                            break;
                        endif;
                    else:
                        $pass = true;
                    endif;
                endforeach;

                if($pass == true):
                    $this->build_confirmation_form();
                endif;
            else:
                $this->build_booking_form();
            endif;
        else:
            echo "<p>Please enter a post code to see if we deliver to your area.</p>";
            $this->build_postcode_form();
        endif;
    }


    /**
     * the shortcode contents for the postcode form (looking up lat and lang)
     */
    public function build_postcode_form( )
    { 
        include_once plugin_dir_path( __FILE__ ) . '../views/postcodeForm.php';
    }

    public function build_skip_form( $postcode = null )
    {
        include_once plugin_dir_path( __FILE__ ) . '../views/skipChoiceForm.php';
    }

    public function build_booking_form( $skip = NULL, $postcode = NULL )
    { 
        include_once plugin_dir_path( __FILE__ ) . '../views/bookingForm.php';
    }

    public function build_confirmation_form( $args = [] )
    {
        $paymentLink = $this->paypal->generate_payment_link($_POST);

        include_once plugin_dir_path( __FILE__ ) . '../views/confirmationForm.php';

    }

    public function process_paypal_payment( )
    {
    }
}