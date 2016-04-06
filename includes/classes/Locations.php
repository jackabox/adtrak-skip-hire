<?php 

class ad_skip_hire_locations
{
    # protected variables
    protected $cpt_prefix;
    protected $menu_parent;

    /**
     * build the requirements for the locations class
     */
    public function __construct()
    {
        # set variables
        $this->cpt_prefix = 'ash_locations'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # hook into wordpress using class
        add_action( 'init', [$this, 'location_post_type'] );
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_filter( 'manage_ash_locations_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_ash_locations_posts_custom_column', [$this, 'modify_table_content'], 10, 2);
    }

    /**
     * registers the post type location
     */
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
            'public' => false,
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

    /**
     * registers the meta fields using the CMB2 library. 
     */
    public function register_meta_fields() 
    {
        $location_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_metabox',
            'title'         => __( 'Location Information', 'ash' ),
            'object_types'  => [$this->cpt_prefix],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,
        ]);

        $location_fields->add_field([
            'name'          => __('Location', 'ash'),
            'desc'          => 'Drag the marker to set the exact location',
            'id'            => $this->cpt_prefix . '_location',
            'type'          => 'pw_map',
            'split_values'  => true,
        ]);

        $location_fields->add_field([
            'name'          => __('Within Radius', 'ash'),
            'desc'          => 'Enter a distance you will deliver from the defined location',
            'id'            => $this->cpt_prefix . '_radius',
            'type'          => 'text_small',
        ]);
    }

    /**
     * modifies the column headers to allow the adding of post_meta
     * @param  array $defaults
     * @return array $defaults
     */
    public function modify_post_columns( $defaults )
    {
        # remove date columns
        unset($defaults['date']);

        # add in new column arrays
        $defaults['radius'] = "Radius";
        $defaults['latitude'] = "Latitude";
        $defaults['longitude'] = "Longitude";

        # return
        return $defaults;
    }

    /**
     * adds the custom meta data into the admin tables, allows for easy over view of information.
     * @param  string $column_name
     * @param  int $post_id
     * @return mixed
     */
    public function modify_table_content( $column_name, $post_id )
    {
        if( $column_name == 'latitude' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_location_latitude', true );

        if( $column_name == 'longitude' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_location_longitude', true );

        if( $column_name == 'radius' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_radius', true ) . ' km';
    }
}