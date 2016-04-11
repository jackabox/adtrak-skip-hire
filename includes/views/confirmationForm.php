<h3>Confirmation of Your Order</h3>

<h4>Skip</h4>

<?php
// WP_Query arguments
$args = [
    'post_type'             => 'ash_skips',
    'post_status'           => 'publish',
    'posts_per_page'        => 1,
    'post_id'               => $_SESSION['ash_skip_id']
];

$skips = new WP_Query( $args );

if ( $skips->have_posts() ): while ( $skips->have_posts() ):
    $skips->the_post(); ?>
    <div class="ash-skip" id="ash-skip-<?php echo get_the_ID(); ?>">
        <h4 class="ash-skip-title"><?php the_title(); ?></h4>
        <div class="ash-skip__meta">
            <span class="ash-skip__meta--width">Width: <?php echo get_post_meta( get_the_ID(), 'ash_skips_width', true ); ?></span>
            <span class="ash-skip__meta--height">Height: <?php echo get_post_meta( get_the_ID(), 'ash_skips_height', true ); ?></span>
            <span class="ash-skip__meta--length">Length: <?php echo get_post_meta( get_the_ID(), 'ash_skips_length', true ); ?></span>
            <span class="ash-skip__meta--capacity">Capacity: <?php echo get_post_meta( get_the_ID(), 'ash_skips_capacity', true ); ?></span>
        </div>

        <p class="ash-skip__price">£<?php echo get_post_meta( get_the_ID(), 'ash_skips_price', true ); ?></p>

        <form action="#" method="POST" class="ash__form ash__form--skip">
            <input type="hidden" id="ash_skip_id" name="ash_skip_id" value="<?php echo get_the_ID(); ?>">
            <button type="submit" id="ash-skip-submit">Book This Skip</button>
        </form>
    </div>
<?php
endwhile; endif;
wp_reset_postdata();
?>

<p>
    <a href="tel:1231231231">Pay Via Phone</a> or 
    <a href="<?php echo $paymentLink ?>" data-paypal-button="true">
        <img src="//www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Check out with PayPal" />
    </a>
</p>