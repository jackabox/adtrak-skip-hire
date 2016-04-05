<?php 

class ad_skip_hire_coupons 
{
    protected $cpt_prefix;
    protected $menu_parent;

    public function __construct()
    {
        $this->cpt_prefix = 'ash_coupons'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # register post type
        add_action('init', [$this, 'coupon_post_type']);
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