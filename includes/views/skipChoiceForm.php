<h3>Choose a Skip</h3>
<p>We found the following skips available for delivery in your location.</p>

<?php
// WP_Query arguments
$args = [
    'post_type'         => 'ash_skips',
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
    'cache_results'     => true,
];

$skips = new WP_Query( $args );

if ( $skips->have_posts() ): 
    while ( $skips->have_posts() ): $skips->the_post(); ?>
    <div class="ash-skip-form" id="ash-skip-<?php echo get_the_ID(); ?>">

        <h4 class="ash-skip-form__title"><?php the_title(); ?></h4>

        <p class="ash-skip-form__description"><?php echo get_post_meta( get_the_ID(), 'ash_skips_description', true ); ?></p>

        <div class="ash-skip-form__meta">

            <span class="ash-skip__meta meta--width">Width: <?php echo get_post_meta( get_the_ID(), 'ash_skips_width', true ); ?></span>
            <span class="ash-skip__meta meta--height">Height: <?php echo get_post_meta( get_the_ID(), 'ash_skips_height', true ); ?></span>
            <span class="ash-skip__meta meta--length">Length: <?php echo get_post_meta( get_the_ID(), 'ash_skips_length', true ); ?></span>
            <span class="ash-skip__meta meta--capacity">Capacity: <?php echo get_post_meta( get_the_ID(), 'ash_skips_capacity', true ); ?></span>

        </div>

        <p class="ash-skip-form__price">£<?php echo get_post_meta( get_the_ID(), 'ash_skips_price', true ); ?></p>

        <form action="#" method="POST" class="ash-skip-form">

            <input type="hidden" id="ash_skip_id" name="ash_skip_id" value="<?php echo get_the_ID(); ?>">
            <button type="submit" id="ash-skip-submit">Book This Skip</button>
        
        </form>
    </div>

    <?php
    endwhile;
endif;

wp_reset_postdata();