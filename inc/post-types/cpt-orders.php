<?php 

function register_cpt_orders() 
{
    $labels = array(
        'name' => _x( 'Order', 'orders' ),
        'singular_name' => _x( 'Orders', 'orders' ),
        // 'add_new' => _x( 'Add New', 'orders' ),
        // 'add_new_item' => _x( 'Add New Order', 'orders' ),
        'edit_item' => _x( 'Edit Order', 'orders' ),
        'new_item' => _x( 'New Order', 'orders' ),
        'view_item' => _x( 'View Order', 'orders' ),
        'search_items' => _x( 'Search Orders', 'orders' ),
        'not_found' => _x( 'No Orders found', 'orders' ),
        'not_found_in_trash' => _x( 'No Orders found in Trash', 'orders' ),
        'menu_name' => _x( 'Orders', 'orders' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Orders of Skips',
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
 
    register_post_type( 'orders', $args );
}
 
add_action( 'init', 'register_cpt_orders' );
