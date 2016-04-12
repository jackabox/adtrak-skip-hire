<?php 

class ad_skip_hire_orders
{
    # protected variables
    protected $cpt_prefix;
    protected $menu_parent;

    /**
     * build the requirements for the orders class
     */
    public function __construct()
    {
        # set variables
        $this->cpt_prefix = 'ash_orders'; 
        $this->menu_parent = 'ad_skip_hire'; 

        # post type, metabox and admin columns
        add_action( 'init', [$this, 'order_post_type'] );
        add_filter( 'cmb2_meta_boxes', [$this, 'register_meta_fields'] );
        add_action( 'add_meta_boxes', [$this, 'custom_metabox']);
        add_filter( 'manage_' . $this->cpt_prefix . '_posts_columns', [$this, 'modify_post_columns'] );
        add_action( 'manage_' . $this->cpt_prefix . '_posts_custom_column', [$this, 'modify_table_content'], 10, 2 );
    }

    /**
     * registers the post type order
     */
    public function order_post_type() 
    {
        $labels = [
            'name'                  => __( 'Orders', 'ash' ),
            'singular_name'         => __( 'Order', 'ash' ),
            'add_new'               => __( 'Add New', 'ash' ),
            'add_new_item'          => __( 'Add New Order', 'ash' ),
            'edit_item'             => __( 'Edit Order' , 'ash' ),
            'new_item'              => __( 'New Order', 'ash' ),
            'view_item'             => __( 'View Order', 'ash' ),
            'search_items'          => __( 'Search Order' , 'ash' ),
            'not_found'             => __( 'No Orders found', 'ash' ),
            'not_found_in_trash'    => __( 'No Orders found in Trash', 'ash' ),
            'menu_name'             => __( 'Manage Orders', 'ash' ),
        ];
     
        $args = [
            'labels'                => $labels,
            'hierarchical'          => true,
            'description'           => __( 'Orders made for skips', 'ash' ),
            'supports'              => ['title'],
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => $this->menu_parent,
            'publicly_queryable'    => true,
            'exclude_from_search'   => true,
            'query_var'             => true,
            'can_export'            => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        ];

        register_post_type($this->cpt_prefix, $args);
    }

    /**
     * registers the meta fields using the CMB2 library. 
     */
    public function register_meta_fields() 
    {
        $order_fields = new_cmb2_box([
            'id'            => $this->cpt_prefix . '_order_metabox',
            'title'         => __( 'Update Order & Delivery Date'),
            'object_types'  => [$this->cpt_prefix],
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true, // Show field names on the left
        ]);

        $order_fields->add_field([
            'id'            => $this->cpt_prefix . '_status',
            'name'          => __( 'Order Status', 'ash' ),
            'type'          => 'select',
            'options'       => [
                'pending'    => __('Pending Payment', 'ash'),
                'paid'       => __('Paid', 'ash'),
                'complete'   => __('Complete', 'ash'),
            ],
        ]);

        $order_fields->add_field([
            'id'            => $this->cpt_prefix . '_delivery_date',
            'name'          => __( 'Delivery Date', 'ash' ),
            'type'          => 'text_date',
            'date_format'   => __( 'd/m/Y'),
        ]);

        $order_fields->add_field([
            'id'            => $this->cpt_prefix . '_delivery_time',
            'name'          => __( 'Delivery Slot', 'ash' ),
            'type'          => 'select',
            'options'       => [
                'AM'    => __('Morning', 'ash'),
                'PM'    => __('Afternoon', 'ash'),
            ],
        ]);
    }

    public function custom_metabox()
    {
        add_meta_box( $this->cpt_prefix . '_information', 'Order Information', [$this, 'custom_metabox_information'], $this->cpt_prefix, 'normal', 'default');
    }

    public function custom_metabox_information()
    {?>

    <div class="cmb2-wrap form-table">
        <div class="cmb2-metabox cmb-field-list">

            <div class="cmb-row">
                <div class="cmb-th">
                    <p><b>Order #</b></p>
                    <p><b>Name</b></p>
                    <p><b>Email</b></p>
                    <p><b>Phone</b></p>
                </div>
                <div class="cmb-td">
                    <p><?php echo get_the_ID(); ?></p>
                    <p><?php echo get_the_title(); ?></p>
                    <p><a href="mailto:<?php echo get_post_meta(get_the_ID(), $this->cpt_prefix . '_email', true); ?>"><?php echo get_post_meta(get_the_ID(), $this->cpt_prefix . '_email', true); ?></a></p>
                    <p><?php echo get_post_meta(get_the_ID(), $this->cpt_prefix . '_phone', true); ?></p>

                </div>
            </div>

            <div class="cmb-row">
                <div class="cmb-th">
                    <p><b>Skip Choice</b></p>
                    <p><b>Permit Needed</b></p>
                    <p><b>Waste</b></p>
                    <p><b>Total Price</b></p>
                    <p><b>PayPal ID</b></p>
                </div>
                <div class="cmb-td">
                    <p><?php echo get_the_title(get_post_meta(get_the_ID(), $this->cpt_prefix . '_skip_id', true)); ?></p>
                    <p><?php if(get_post_meta(get_the_ID(), $this->cpt_prefix . '_permit_id', true)) { echo get_the_title(get_post_meta(get_the_ID(), $this->cpt_prefix . '_permit_id', true)); } else { echo "---"; } ?></p>
                    <p><?php $waste = get_post_meta(get_the_ID(), $this->cpt_prefix . '_waste', true); foreach($waste as $w): echo $w . ', '; endforeach; ?></p>
                    <p>£<?php echo get_post_meta(get_the_ID(), $this->cpt_prefix . '_total', true); ?></p>
                    <p>---</p>
                </div>
            </div>


            <div class="cmb-row">
                <div class="cmb-th">
                    <p><b>Delivery Address</b> <br><br><br><br><br></p>
                    <p><b>Delivery Notes</b></p>
                </div>
                <div class="cmb-td">
                    <p><?php $delivery = get_post_meta(get_the_ID(), $this->cpt_prefix . '_delivery_address', true); foreach($delivery as $d): echo $d . '<br>'; endforeach; ?></p>
                    <p><?php echo get_post_meta(get_the_ID(), $this->cpt_prefix . '_notes', true); ?></p>
                </div>
            </div>

        </div>
    </div>

<?php
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