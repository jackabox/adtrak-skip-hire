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
        add_shortcode( 'ash_postcode_form', [$this, 'shortcode_postcode'] );
        add_shortcode( 'ash_booking_form', [$this, 'shortcode_booking'] );

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

        $this->locations = new ad_skip_hire_locations();
        $this->permits = new ad_skip_hire_permits();
        $this->coupons = new ad_skip_hire_coupons();
        $this->skips = new ad_skip_hire_skips();
        $this->orders = new ad_skip_hire_orders();
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

    /**
     * the shortcode contents for the postcode form (looking up lat and lang)
     */
    public function shortcode_postcode( )
    { ?>
        <form action="<?php echo get_permalink( get_page_by_title( 'Booking' ) ); ?>" method="POST" id="ash_postcode_form">
            <input type="hidden" id="ash_lat" name="ash_lat">
            <input type="hidden" id="ash_lng" name="ash_lng">

            <p>
                <label for="ash_postcode">Enter a Postcode</label>
                <input type="text" name="ash_postcode" id="ash_postcode">
            </p>

            <p>
                <button type="submit" id="ash_postcode_submit">Check Available Skips</button>
            </p>
        </form>
<?php 
    }

    public function shortcode_booking()
    {
        $postcode = (isset($_REQUEST['ash_postcode'])) ? $_REQUEST['ash_postcode'] : NULL; 
        $lat = (isset($_REQUEST['ash_lat'])) ? $_REQUEST['ash_lat'] : NULL; 
        $lng = (isset($_REQUEST['ash_lng'])) ? $_REQUEST['ash_lng'] : NULL; 
        $skip = (isset($_REQUEST['ash_skip_id'])) ? $_REQUEST['ash_skip_id'] : NULL; 
    ?>
        <div id="ash_booking" class="ash-booking-holder">
            <?php if($postcode == null && $lat == null): 

                echo "<p>Sorry, we couldn't find your location. Please try again using the form below.</p>";
                echo do_shortcode('[ash_postcode_form]');

            elseif($skip == null): ?>
                <h3>Choose a SKip</h3>
                <p>We found the following skips available for delivery in the area of <?php echo strtoupper($postcode); ?>.</p>

                <?php
                // WP_Query arguments
                $args = [
                    'post_type'              => 'ash_skips',
                    'post_status'            => 'publish',
                    'posts_per_page'         => -1,
                    'cache_results'          => true,
                ];

                $query = new WP_Query( $args );
                if ( $query->have_posts() ): while ( $query->have_posts() ):
                    $query->the_post(); ?>
                    <div class="ash-skip" id="ash-skip-<?php echo get_the_ID(); ?>">
                        <h3 class="ash-skip-title"><?php the_title(); ?></h3>
                        <p class="ash-skip-description"><?php echo get_post_meta( get_the_ID(), 'ash_skips_description', true ); ?></p>
                        <div class="ash-skip-meta">
                            <span class="ash-skip-meta__width">Width: <?php echo get_post_meta( get_the_ID(), 'ash_skips_width', true ); ?></span>
                            <span class="ash-skip-meta__height">Height: <?php echo get_post_meta( get_the_ID(), 'ash_skips_height', true ); ?></span>
                            <span class="ash-skip-meta__length">Length: <?php echo get_post_meta( get_the_ID(), 'ash_skips_length', true ); ?></span>
                            <span class="ash-skip-meta__capacity">Capacity: <?php echo get_post_meta( get_the_ID(), 'ash_skips_capacity', true ); ?></span>
                        </div>
                        <form action="#" method="POST">
                            <input type="hidden" id="ash_postcode" name="ash_postcode" value="<?php echo $postcode; ?>">
                            <input type="hidden" id="ash_skip_id" name="ash_skip_id" value="<?php echo get_the_ID(); ?>">
                            <button type="submit" id="ash-skip-submit">Book This Skip</button>
                        </form>
                    </div>
                <?php
                endwhile; endif;
                wp_reset_postdata();
                ?>
            <?php else: ?>
                <h3>Your Details</h3>
                <form action="#">
                    <!-- all of the skips here -->

                    <!-- user details -->

                    <!-- delivery details -->

                    <!-- permit, waste, notes -->

                    <!-- proceed to payment -->
                </form>
            <?php endif; ?>
        </div>
<?php
    }
}