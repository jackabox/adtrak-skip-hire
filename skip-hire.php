<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Skip Hire
 * Plugin URI:        http://skiphire.adtrakdev.com/
 * Description:       Adding the ability to hire skips and process payments within areas.
 * Version:           1.0.0
 * Author:            Adtrak
 * Author URI:        http://adtrak.co.uk/
 * License:           MIT
 */

# Register Custom Pages
require_once(plugin_dir_path( __FILE__ ) . 'inc/pages/skip-hire.php');

# Register Custom Post Types
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/cpt-orders.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/cpt-delivery-radius.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/cpt-skip-type.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/cpt-permits.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/cpt-coupons.php');
