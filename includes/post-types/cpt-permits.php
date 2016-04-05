<?php 

function register_cpt_permits() 
{
    $labels = array(
        'name' => _x( 'Permit', 'permits' ),
        'singular_name' => _x( 'Permits', 'permits' ),
        'add_new' => _x( 'Add New', 'permits' ),
        'add_new_item' => _x( 'Add New Permit', 'permits' ),
        'edit_item' => _x( 'Edit Permit', 'permits' ),
        'new_item' => _x( 'New Permit', 'permits' ),
        'view_item' => _x( 'View Permit', 'permits' ),
        'search_items' => _x( 'Search Permit', 'permits' ),
        'not_found' => _x( 'No Permits found', 'permits' ),
        'not_found_in_trash' => _x( 'No Permits found in Trash', 'permits' ),
        'menu_name' => _x( 'Permits', 'permits' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Skip Types',
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
 
    register_post_type( 'permits', $args );
}
 
add_action( 'init', 'register_cpt_permits' );
