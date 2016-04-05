<?php 

function register_cpt_delivery_radius() 
{
    $labels = array(
        'name' => _x( 'Delivery Location', 'delivery_radius' ),
        'singular_name' => _x( 'Delivery Locations', 'delivery_radius' ),
        'add_new' => _x( 'Add New', 'delivery_radius' ),
        'add_new_item' => _x( 'Add New Delivery Location', 'delivery_radius' ),
        'edit_item' => _x( 'Edit Delivery Location', 'delivery_radius' ),
        'new_item' => _x( 'New Delivery Location', 'delivery_radius' ),
        'view_item' => _x( 'View Delivery Location', 'delivery_radius' ),
        'search_items' => _x( 'Search Delivery Location', 'delivery_radius' ),
        'not_found' => _x( 'No delivery locations found', 'delivery_radius' ),
        'not_found_in_trash' => _x( 'No delivery locations found in Trash', 'delivery_radius' ),
        'menu_name' => _x( 'Delivery Locations', 'delivery_radius' ),
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
 
    register_post_type( 'delivery_radius', $args );
}
 
add_action( 'init', 'register_cpt_delivery_radius' );
