<?php 

class ad_skip_hire_permits
{
    # protected variables
    protected $cpt_prefix;
    protected $menu_parent;

    /**
     * build the requirements for the permits class
     */
    public function __construct()
    {
        $this->cpt_prefix = 'ash_permits'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # register post type
        add_action( 'init', [$this, 'permit_post_type'] );
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_filter( 'manage_ash_permits_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_ash_permits_posts_custom_column', [$this, 'modify_table_content'], 10, 2 );
    }

    /**
     * register the permit post type
     */
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
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => $this->menu_parent,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post',
        ];

        register_post_type($this->cpt_prefix, $args);
    }

    /**
     * registers the meta fields using the CMB2 library. 
     */
    public function register_meta_fields() 
    {
        $permit_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_fields',
            'title'         => __( 'Order Information', 'ash' ),
            'object_types'  => [ $this->cpt_prefix ],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,
        ]);

        $permit_fields->add_field([
            'name'          => __( 'Permit Length', 'ash' ),
            'id'            => $this->cpt_prefix . '_duration',
            'type'          => 'text_small',
            'after_field'   => ' days',
            'attributes'    => [
                'placeholder' => __( '5', 'ash' ),
            ],
        ]);

        $permit_fields->add_field([
            'name'          => __( 'Process Time', 'ash' ),
            'id'            => $this->cpt_prefix . '_process_time',
            'type'          => 'text_small',
            'after_field'   => ' days',
            'attributes'    => [
                'placeholder' => __( '5', 'ash' ),
            ],
        ]);

        $permit_fields->add_field([
            'name'          => __( 'Price', 'ash' ),
            // 'desc'          => __( 'How much does the permit cost?', 'ash' ),
            'id'            => $this->cpt_prefix . '_price',
            'type'          => 'text_money',
            'before_field'  => '£'
        ]);
    }

    /**
     * modifies the column headers to allow the adding of post_meta
     * @param  array $defaults
     * @return array $defaults
     */
    public function modify_post_columns( $defaults )
    {
        unset( $defaults['date'] );

        $defaults['duration'] = "Duration";
        $defaults['process'] = "Process Time";
        $defaults['price'] = "Price";

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
        if( $column_name == 'duration' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_duration', true ) . ' days';

        if( $column_name == 'process' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_process_time', true ) . ' days';

        if( $column_name == 'price' )
            echo '£' . get_post_meta( $post_id, $this->cpt_prefix . '_price', true );

    }
}