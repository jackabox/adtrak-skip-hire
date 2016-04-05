<?php 

function register_cpt_skip_types() 
{
    $labels = array(
        'name' => _x( 'Skip Type', 'skip_type' ),
        'singular_name' => _x( 'Skip Types', 'skip_type' ),
        'add_new' => _x( 'Add New', 'skip_type' ),
        'add_new_item' => _x( 'Add New Skip Type', 'skip_type' ),
        'edit_item' => _x( 'Edit Skip Type', 'skip_type' ),
        'new_item' => _x( 'New Skip Type', 'skip_type' ),
        'view_item' => _x( 'View Skip Type', 'skip_type' ),
        'search_items' => _x( 'Search Skip Type', 'skip_type' ),
        'not_found' => _x( 'No Skip Types found', 'skip_type' ),
        'not_found_in_trash' => _x( 'No Skip Types found in Trash', 'skip_type' ),
        'menu_name' => _x( 'Skip Types', 'skip_type' ),
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
 
    register_post_type( 'skip_type', $args );
}
 
add_action( 'init', 'register_cpt_skip_types' );
