<?php 

function register_cpt_coupons() 
{
    $labels = array(
        'name' => _x( 'Coupons', 'coupons' ),
        'singular_name' => _x( 'Coupon', 'coupons' ),
        'add_new' => _x( 'Add New', 'coupons' ),
        'add_new_item' => _x( 'Add New Coupon', 'coupons' ),
        'edit_item' => _x( 'Edit Coupon', 'coupons' ),
        'new_item' => _x( 'New Coupon', 'coupons' ),
        'view_item' => _x( 'View Coupon', 'coupons' ),
        'search_items' => _x( 'Search Coupon', 'coupons' ),
        'not_found' => _x( 'No Coupons found', 'coupons' ),
        'not_found_in_trash' => _x( 'No Coupons found in Trash', 'coupons' ),
        'menu_name' => _x( 'Coupon Codes', 'coupons' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Delivery Locations for Skips',
        'supports' => array( 'title'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'ad_skip_hire',
        'menu_position' => 20,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
 
    register_post_type( 'coupons', $args );
}
 
add_action( 'init', 'register_cpt_coupons' );
