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
        
        if($skip == null && $lat != null): ?>
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
                    <h4 class="ash-skip-title"><?php the_title(); ?></h4>
                    <p class="ash-skip-description"><?php echo get_post_meta( get_the_ID(), 'ash_skips_description', true ); ?></p>
                    <div class="ash-skip__meta">
                        <span class="ash-skip__meta--width">Width: <?php echo get_post_meta( get_the_ID(), 'ash_skips_width', true ); ?></span>
                        <span class="ash-skip__meta--height">Height: <?php echo get_post_meta( get_the_ID(), 'ash_skips_height', true ); ?></span>
                        <span class="ash-skip__meta--length">Length: <?php echo get_post_meta( get_the_ID(), 'ash_skips_length', true ); ?></span>
                        <span class="ash-skip__meta--capacity">Capacity: <?php echo get_post_meta( get_the_ID(), 'ash_skips_capacity', true ); ?></span>
                    </div>

                    <p class="ash-skip__price">£<?php echo get_post_meta( get_the_ID(), 'ash_skips_price', true ); ?></p>

                    <form action="#" method="POST" class="ash__form ash__form--skip">
                        <input type="hidden" id="ash_postcode" name="ash_postcode" value="<?php echo $postcode; ?>">
                        <input type="hidden" id="ash_skip_id" name="ash_skip_id" value="<?php echo get_the_ID(); ?>">
                        <button type="submit" id="ash-skip-submit">Book This Skip</button>
                    </form>
                </div>
            <?php
            endwhile; endif;
            wp_reset_postdata();
            ?>

        <?php elseif ( $skip != null ): ?>

            <h3>Complete Your Order</h3>
            <p>Fill out your details and pick a delivery time to proceed.</p>

            <form action="<?php echo get_permalink( get_page_by_title( 'Confirmation' ) ) ?>" method="POST">
                <!-- user details -->
                <fieldset class="ash__fieldset ash__fieldset-user">
                    <legend class="ash__legend ash__legend-user">Your Details</legend>

                    <div class="ash__input ash__input--title">
                        <label for="ash_title">Title</label>
                        <input type="text" name="ash_title" id="ash_title">
                    </div>

                    <div class="ash__input ash__input--forename">
                        <label for="ash_forename">Forename</label>
                        <input type="text" name="ash_forename" id="ash_forename">
                    </div>

                    <div class="ash__input ash__input--surname">
                        <label for="ash_surname">Surname</label>
                        <input type="text" name="ash_surname" id="ash_surname">
                    </div>

                    <div class="ash__input ash__input--email">
                        <label for="ash_email">Email Address</label>
                        <input type="email" name="ash_email" id="ash_email">
                    </div>

                    <div class="ash__input ash__input--phone">
                        <label for="ash_phone">Phone Number</label>
                        <input type="tel" name="ash_phone" id="ash_phone">
                    </div>
                </fieldset>

                <!-- addresses -->
                <fieldset class="ash__fieldset ash__fieldset-delivery">
                    <legend class="ash__legend ash__legend-delivery">Delivery Address</legend>
                    
                    <div class="ash__input ash__input--address">
                        <label for="ash_delivery_address_1">Address Line 1</label>
                        <input type="tel" name="ash_delivery_address_1" id="ash_delivery_address_1">
                    </div>

                    <div class="ash__input ash__input--address">
                        <label for="ash_delivery_address_2">Address Line 2</label>
                        <input type="tel" name="ash_delivery_address_2" id="ash_delivery_address_2">
                    </div>

                    <div class="ash__input ash__input--city">
                        <label for="ash_delivery_city">City</label>
                        <input type="tel" name="ash_delivery_city" id="ash_delivery_city">
                    </div>

                    <div class="ash__input ash__input--county">
                        <label for="ash_delivery_county">County</label>
                        <input type="tel" name="ash_delivery_county" id="ash_delivery_county">
                    </div>

                    <div class="ash__input ash__input--postcode">
                        <label for="ash_delivery_postcode">Post Code</label>
                        <input type="tel" name="ash_delivery_postcode" id="ash_delivery_postcode">
                    </div>

                    <p class="ash__show-billing"><a href="javascript:void(0);">Is your billing address different?</a></p>
                </fieldset>

                <fieldset class="ash__fieldset ash__fieldset-billing">
                    <legend class="ash__legend ash__legend-billing">Billing Address</legend>
                    
                    <div class="ash__input ash__input--address">
                        <label for="ash_billing_address_1">Address Line 1</label>
                        <input type="tel" name="ash_billing_address_1" id="ash_billing_address_1">
                    </div>

                    <div class="ash__input ash__input--address">
                        <label for="ash_billing_address_2">Address Line 2</label>
                        <input type="tel" name="ash_billing_address_2" id="ash_billing_address_2">
                    </div>

                    <div class="ash__input ash__input--city">
                        <label for="ash_billing_city">City</label>
                        <input type="tel" name="ash_billing_city" id="ash_billing_city">
                    </div>

                    <div class="ash__input ash__input--county">
                        <label for="ash_billing_county">County</label>
                        <input type="tel" name="ash_billing_county" id="ash_billing_county">
                    </div>

                    <div class="ash__input ash__input--postcode">
                        <label for="ash_billing_postcode">Post Code</label>
                        <input type="tel" name="ash_billing_postcode" id="ash_billing_postcode">
                    </div>
                </fieldset>

                <fieldset class="ash__fieldset ash__fieldset-date">
                    <legend class="ash__legend ash__legend-date">Delivery Date/Time</legend>

                    <div class="ash__input ash__input--date">
                        <label for="ash_delivery_date">Pick a Delivery Date</label>
                        <input type="date" name="ash_delivery_date" id="ash_delivery_date" placeholder="dd/mm/yyyy">
                    </div>

                    <div class="ash__input ash__input--time">
                        <span class="ash__fake-label">Pick a Time Slot</span>
                        
                        <input type="radio" class="ash__input--radio" id="ash_delivery_time[]" name="ash_delivery_am" value="AM"> <label for="ash_delivery_am">AM</label>
                        <input type="radio" class="ash__input--radio" id="ash_delivery_time[]" name="ash_delivery_pm" value="PM"> <label for="ash_delivery_pm">PM</label>
                    </div>
                </fieldset>

                <!-- permit, waste, notes -->
                <fieldset class="ash__fieldset ash__fieldset-notes">
                    <legend class="ash__legend ash__legend-notes">Notes</legend>

                    <div class="ash__select ash__select--permit">
                        <label for="ash_permit">Do You Need a Permit?</label>
                        <select name="ash_permit" id="ash_permit">
                            <option value="">No Permit Needed</option>
                           <?php
                            // query all of the permits available
                            $args = [
                                'post_type'              => 'ash_permits',
                                'post_status'            => 'publish',
                                'posts_per_page'         => -1,
                                'cache_results'          => true,
                            ];

                            $query = new WP_Query( $args );
                            if ( $query->have_posts() ): while ( $query->have_posts() ):
                                $query->the_post(); ?>
                            <option value="get_the_ID(); ?>"><?php echo get_the_title(); ?> (£<?php echo get_post_meta(get_the_ID(), 'ash_permits_price', true); ?>)</option>
                            <?php endwhile; endif; wp_reset_postdata(); ?>
                        </select>
                    </div>

                    <div class="ash__checkboxes ash__checkboxes--waste">
                        <span class="ash__fake-label">Check all the types of waste you will be using the skip for</span>
                        <div>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_concrete" class="ash__checkbox"> <label for="ash_waste_concrete">Concrete</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_metal" class="ash__checkbox"> <label for="ash_waste_metal">Metal</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_paper" class="ash__checkbox"> <label for="ash_waste_paper">Paper/Card</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_plastic" class="ash__checkbox"> <label for="ash_waste_plastic">Plastic</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_rubble" class="ash__checkbox"> <label for="ash_waste_rubble">Rubble/Brick</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_soil" class="ash__checkbox"> <label for="ash_waste_soil">Soil</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_wood" class="ash__checkbox"> <label for="ash_waste_wood">Wood</label></span>
                            <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[]" id="ash_waste_other" class="ash__checkbox"> <label for="ash_waste_other">Other</label></span>
                        </div>
                    </div>

                    <div class="ash__textarea ash__textarea--notes">
                        <label for="ash_notes">Additional Notes</label>
                        <textarea name="ash_notes" id="ash_notes"></textarea>
                    </div>
                </fieldset>

                <!-- proceed to confirmation -->
                <button type="submit" class="ash__submit">Confirm Order</button>
            </form>

        <?php 
        else:
            echo "<p>Please enter a post code to see if we deliver to your area.</p>";
            echo do_shortcode('[ash_postcode_form]');
        endif;
    }
}