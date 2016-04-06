<?php
/**
 * Plugin Name:       Skip Hire
 * Plugin URI:        http://skiphire.adtrakdev.com/
 * Description:       Adding the ability to hire skips and process payments within areas.
 * Version:           1.0.0
 * Author:            Adtrak
 * Author URI:        http://adtrak.co.uk/
 * License:           MIT
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

# require cmb2  
if ( file_exists( plugin_dir_path( __FILE__ ) . 'vendor/cmb2/init.php' ) ) 
{
    require_once plugin_dir_path( __FILE__ ) . 'vendor/cmb2/init.php';
}

# include google maps field, if not already declared
if(!class_exists( 'PW_CMB2_Field_Google_Maps' ) && file_exists( plugin_dir_path( __FILE__ ) . 'vendor/cmb_field_map/cmb-field-map.php' ) ) 
{
    require_once plugin_dir_path( __FILE__ ) . 'vendor/cmb_field_map/cmb-field-map.php';
}

function update_cmb2_meta_box_url( $url ) 
{
    $url = plugins_url('vendor/cmb2', __FILE__);
    return $url;
}
add_filter( 'cmb2_meta_box_url', 'update_cmb2_meta_box_url' );

if ( file_exists( plugin_dir_path( __FILE__ ) . 'includes/classes/SkipHire.php' ) ) 
{
    require plugin_dir_path( __FILE__ ) . 'includes/classes/SkipHire.php';
}

$plugin = new ad_skip_hire();

# activation
register_activation_hook( __FILE__ , 'ash_activate_plugin' );

function ash_activate_plugin()
{
    # generate required pages
    $booking_form = [
        'post_title'    => 'Booking Form',
        'post_content'  => '[ash_booking_form]',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ];

    wp_insert_post( $booking_form );
}