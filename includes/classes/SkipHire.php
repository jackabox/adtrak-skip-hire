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

        # init
        add_action('init', [$this, 'session_start']);

        # menu pages
        add_action( 'admin_menu', [$this, 'skip_admin_pages'] );

        # shortcodes
        add_shortcode( 'ash_postcode_form', [$this, 'build_postcode_form'] );
        add_shortcode( 'ash_booking_form', [$this, 'booking_form_director'] );
        add_shortcode( 'ash_booking_confirmation', [$this, 'booking_form_process'] );

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

    /**
     * Inject session_start before the headers are called.
     * @return [type] [description]
     */
    function session_start() {
        if(!session_id()) {
            session_start();
        }
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

        # set sessions
        if($skip != null)
            $_SESSION['ash_skip_id'] = $skip;
        
        if($postcode != null)
            $_SESSION['ash_postcode'] = $postcode;

        # load forms       
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
            if( isset( $_POST['ash_submit'] ) ): 
                foreach( $_POST as $key => $entry ):
                    if(
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
        # create the post
        $createPost = [
            'post_title' => wp_strip_all_tags( $_POST['ash_forename'] . ' ' . $_POST['ash_surname'] ),
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'ash_orders',
        ];
        # get the post id
        $postID = wp_insert_post( $createPost );
        # session
        $_SESSION['ash_user'] = [
            'id' => $postID,
            'name' => $_POST['ash_forename'] . ' ' . $_POST['ash_surname'],
        ];

        $deliveryAddress = [
            'address_1' => $_POST['ash_delivery_address_1'],
            'address_2' => $_POST['ash_delivery_address_2'],
            'city' => $_POST['ash_delivery_city'],
            'county' => $_POST['ash_delivery_county'],
            'postcode' => $_SESSION['ash_postcode'],
        ];

        if(isset($_POST['ash_billing_address_1']) && ($_POST['ash_billing_address_1'] != null)) {
            $billingAddress = [
                'address_1' => $_POST['ash_billing_address_1'],
                'address_2' => $_POST['ash_billing_address_2'],
                'city' => $_POST['ash_billing_city'],
                'county' => $_POST['ash_billing_county'],
                'postcode' => $_POST['ash_billing_postcode'],
            ];
        } else {
            $billingAddress = $deliveryAddress;
        }

        # posted data - meta
        add_post_meta( $postID, 'ash_orders_email', $_POST['ash_email'] );
        add_post_meta( $postID, 'ash_orders_phone', $_POST['ash_phone'] );
        add_post_meta( $postID, 'ash_orders_billing_address', $billingAddress );
        add_post_meta( $postID, 'ash_orders_delivery_address', $deliveryAddress );
        add_post_meta( $postID, 'ash_orders_delivery_date', $_POST['ash_delivery_date']);
        add_post_meta( $postID, 'ash_orders_delivery_time', $_POST['ash_delivery_time'][0]);
        add_post_meta( $postID, 'ash_orders_skip_id', $_SESSION['ash_skip_id']);
        add_post_meta( $postID, 'ash_orders_permit_id', $_POST['ash_permit_id']);
        add_post_meta( $postID, 'ash_orders_waste', $_POST['ash_waste']);
        add_post_meta( $postID, 'ash_orders_notes', $_POST['ash_notes']);
        # order status - meta
        add_post_meta( $postID, 'ash_orders_status', 'pending');
        # generate PayPal payee link
        $paymentLink = $this->paypal->generate_payment_link($_POST);

        # Include Template
        include_once plugin_dir_path( __FILE__ ) . '../views/confirmationForm.php';
    }

    public function booking_form_process ( $args = [] )
    {
        $response = $this->paypal->authorised_payment_check();

        // using the response update said user using sessions
        // store paypal reference link in db. 
        // update status to paid.
        
        // return thank you message to user with email confirmation. ;
        var_dump($response);
    }
}