<?php 

class ad_skip_hire_locations
{
    protected $cpt_prefix;
    protected $menu_parent;

    public function __construct()
    {
        $this->cpt_prefix = 'ash_locations'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # register post type
        add_action('init', [$this, 'location_post_type']);
    }

    public function location_post_type() 
    {
        $labels = [
            'name' => _x( 'Delivery Locations', $this->cpt_prefix),
            'singular_name' => _x( 'Delivery Location', $this->cpt_prefix),
            'add_new' => _x( 'Add New', $this->cpt_prefix),
            'add_new_item' => _x( 'Add New Delivery Location', $this->cpt_prefix),
            'edit_item' => _x( 'Edit Delivery Location', $this->cpt_prefix ),
            'new_item' => _x( 'New Delivery Location', $this->cpt_prefix),
            'view_item' => _x( 'View Delivery Location', $this->cpt_prefix),
            'search_items' => _x( 'Search Delivery Location', $this->cpt_prefix ),
            'not_found' => _x( 'No Delivery Locations found', $this->cpt_prefix ),
            'not_found_in_trash' => _x( 'No Delivery Locations found in Trash', $this->cpt_prefix ),
            'menu_name' => _x( 'Delivery Locations', $this->cpt_prefix ),
        ];
     
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Delivery locations for the skips',
            'supports' => array( 'title'),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => $this->menu_parent,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        ];

        register_post_type($this->cpt_prefix, $args);
    }
}