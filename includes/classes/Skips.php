<?php 

class ad_skip_hire_skips
{
    protected $cpt_prefix;
    protected $menu_parent;

    public function __construct()
    {
        $this->cpt_prefix = 'ash_skips'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # register post type
        add_action('init', [$this, 'skip_post_type']);
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_filter( 'manage_ash_orders_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_ash_orders_posts_custom_column', [$this, 'modify_table_content'], 10, 2);
    }

    public function skip_post_type() 
    {
        $labels = [
            'name' => _x( 'Skips', $this->cpt_prefix),
            'singular_name' => _x( 'Skip', $this->cpt_prefix),
            'add_new' => _x( 'Add New', $this->cpt_prefix),
            'add_new_item' => _x( 'Add New Skip', $this->cpt_prefix),
            'edit_item' => _x( 'Edit Skip', $this->cpt_prefix ),
            'new_item' => _x( 'New Skip', $this->cpt_prefix),
            'view_item' => _x( 'View Skip', $this->cpt_prefix),
            'search_items' => _x( 'Search Skip', $this->cpt_prefix ),
            'not_found' => _x( 'No Skips found', $this->cpt_prefix ),
            'not_found_in_trash' => _x( 'No Skips found in Trash', $this->cpt_prefix ),
            'menu_name' => _x( 'Skips', $this->cpt_prefix ),
        ];
     
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Types of skips',
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
        $skip_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_metabox',
            'title'         => __( 'Skip Details', 'ash' ),
            'object_types'  => [$this->cpt_prefix],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true, // Show field names on the left
        ]);

        $skip_fields->add_field([
            'id'            => $this->cpt_prefix . '_width',
            'name'          => __( 'Width', 'ash' ),
            'type'          => 'text_small',
            'after_field'   => ' m',
            'attributes'    => [
                    'type'      => 'number',
                    'pattern'   => '\d*',
            ],
        ]);

        $skip_fields->add_field([
            'id'            => $this->cpt_prefix . '_height',
            'name'          => __( 'Height', 'ash' ),
            'type'          => 'text_small',
            'after_field'   => ' m',
            'attributes'    => [
                    'type'      => 'number',
                    'pattern'   => '\d*',
            ],
        ]);

        $skip_fields->add_field([
            'id'            => $this->cpt_prefix . '_depth',
            'name'          => __( 'Depth', 'ash' ),
            'type'          => 'text_small',
            'after_field'   => ' m',
            'attributes'    => [
                    'type'      => 'number',
                    'pattern'   => '\d*',
            ],
        ]);

        $skip_fields->add_field([
            'id'            => $this->cpt_prefix . '_price',
            'name'          => __( 'Price', 'ash' ),
            'type'          => 'text_money',
            'before_field'  => '£',
        ]);

        $skip_fields->add_field([
            'id'            => $this->cpt_prefix . '_capacity',
            'name'          => __( 'Estimated Capacity', 'ash' ),
            'type'          => 'text_small',
            'after_field'   => ' bags',
            'attributes'    => [
                    'type'      => 'number',
                    'pattern'   => '\d*',
            ],
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