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

require plugin_dir_path( __FILE__ )  . '/vendor/autoload.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
    die;

# require cmb2  
if ( file_exists( plugin_dir_path( __FILE__ ) . 'vendor/cmb2/init.php' ) ) 
    require_once plugin_dir_path( __FILE__ ) . 'vendor/cmb2/init.php';

# include google maps field, if not already declared
if(!class_exists( 'PW_CMB2_Field_Google_Maps' ) && file_exists( plugin_dir_path( __FILE__ ) . 'vendor/cmb_field_map/cmb-field-map.php' ) ) 
    require_once plugin_dir_path( __FILE__ ) . 'vendor/cmb_field_map/cmb-field-map.php';

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

# activation
register_activation_hook( __FILE__ , 'ash_activate_plugin' );

function ash_activate_plugin()
{
    $booking_form = [
        'post_title'    => 'Booking',
        'post_content'  => '[ash_booking_form]',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ];

    $page_exists = get_page_by_title( $booking_form['post_title'] );

    if($page_exists == null) {
        $post_id = wp_insert_post( $booking_form );
        $confirmation = [
            'post_title'    => 'Confirmation',
            'post_content'  => '[ash_booking_confirmation]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_parent'   => $post_id,
        ];
        wp_insert_post( $confirmation );
    }
}

$plugin = new ad_skip_hire( );
