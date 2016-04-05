<?php 

class ad_skip_hire_permits
{
    protected $cpt_prefix;
    protected $menu_parent;

    public function __construct()
    {
        $this->cpt_prefix = 'ash_permits'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # register post type
        add_action('init', [$this, 'permit_post_type']);
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_filter( 'manage_ash_permits_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_ash_permits_posts_custom_column', [$this, 'modify_table_content'], 10, 2);
    }

    public function permit_post_type() 
    {
        $labels = [
            'name' => _x( 'Permits', $this->cpt_prefix),
            'singular_name' => _x( 'Permit', $this->cpt_prefix),
            'add_new' => _x( 'Add New', $this->cpt_prefix),
            'add_new_item' => _x( 'Add New Permit', $this->cpt_prefix),
            'edit_item' => _x( 'Edit Permit', $this->cpt_prefix ),
            'new_item' => _x( 'New Permit', $this->cpt_prefix),
            'view_item' => _x( 'View Permit', $this->cpt_prefix),
            'search_items' => _x( 'Search Permit', $this->cpt_prefix ),
            'not_found' => _x( 'No Permits found', $this->cpt_prefix ),
            'not_found_in_trash' => _x( 'No Permits found in Trash', $this->cpt_prefix ),
            'menu_name' => _x( 'Permits', $this->cpt_prefix ),
        ];
     
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Orders made for skips',
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

    /**
     * registers the meta fields using the CMB2 library. 
     */
    public function register_meta_fields() 
    {
        $order_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_metabox',
            'title'         => __( 'Order Information', 'ash' ),
            'object_types'  => [$this->cpt_prefix],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true, // Show field names on the left
        ]);
    }

    /**
     * modifies the column headers to allow the adding of post_meta
     * @param  array $defaults
     * @return array $defaults
     */
    public function modify_post_columns( $defaults )
    {
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

    }
}