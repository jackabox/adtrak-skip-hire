<?php 

class ad_skip_hire_coupons
{
    # protected variables
    protected $cpt_prefix;
    protected $menu_parent;

    /**
     * build the requriements for the coupons class
     */
    public function __construct()
    {
        # set variables
        $this->cpt_prefix = 'ash_coupons'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # hook into wordpress using actions
        add_action('init', [$this, 'coupon_post_type']);
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_filter( 'manage_' . $this->cpt_prefix . '_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_' . $this->cpt_prefix . '_posts_custom_column', [$this, 'modify_table_content'], 10, 2 );

    }

    public function coupon_post_type() 
    {
        $labels = [
            'name' => _x( 'Coupons', $this->cpt_prefix),
            'singular_name' => _x( 'Coupon', $this->cpt_prefix),
            'add_new' => _x( 'Add New', $this->cpt_prefix),
            'add_new_item' => _x( 'Add New Coupon', $this->cpt_prefix),
            'edit_item' => _x( 'Edit Coupon', $this->cpt_prefix ),
            'new_item' => _x( 'New Coupon', $this->cpt_prefix),
            'view_item' => _x( 'View Coupon', $this->cpt_prefix),
            'search_items' => _x( 'Search Coupon', $this->cpt_prefix ),
            'not_found' => _x( 'No coupons found', $this->cpt_prefix ),
            'not_found_in_trash' => _x( 'No coupons found in Trash', $this->cpt_prefix ),
            'menu_name' => _x( 'Coupons', $this->cpt_prefix ),
        ];
     
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Coupon codes for Skip hire discount',
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
        $coupon_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_metabox',
            'title'         => __( 'Coupon Details', 'ash' ),
            'object_types'  => [$this->cpt_prefix],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true, // Show field names on the left
        ]);

        $coupon_fields->add_field([
            'id'            => $this->cpt_prefix . '_type',
            'name'          => __( 'Type', 'ash' ),
            'type'          => 'select',
            'options'       => [
                'flat'    => __('Flat Discount', 'ash'),
                'percent' => __('Percentage Discount', 'ash'),
            ],
        ]);

        $coupon_fields->add_field([
            'id'            => $this->cpt_prefix . '_amount',
            'name'          => __( 'Amount', 'ash' ),
            'type'          => 'text_small',
            'attributes'    => [
                    'type'      => 'number',
                    'pattern'   => '\d*',
            ],
        ]);

        $coupon_fields->add_field([
            'id'            => $this->cpt_prefix . '_start_date',
            'name'          => __( 'Start Date', 'ash' ),
            'type'          => 'text_date',
            'date_format'   => __( 'd/m/Y', 'ash' ),
        ]);

        $coupon_fields->add_field([
            'id'            => $this->cpt_prefix . '_end_date',
            'name'          => __( 'End Date', 'ash' ),
            'type'          => 'text_date',
            'date_format'   => __( 'd/m/Y', 'ash' ),
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

        $defaults['start_date'] = "Start Date";
        $defaults['end_date'] = "End Date";
        $defaults['amount'] = "Amount";

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
        if( $column_name == 'start_date' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_start_date', true );

        if( $column_name == 'end_date' )
            echo get_post_meta( $post_id, $this->cpt_prefix . '_end_date', true );

        if( $column_name == 'amount' )
        {
            $type = get_post_meta( $post_id, $this->cpt_prefix . '_type', true );
            $value = get_post_meta( $post_id, $this->cpt_prefix . '_amount', true );

            if($type == 'percent') {
                echo $value . '%';
            } else {
                echo '£' . $value;
            }
        }
    }
}