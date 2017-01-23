<?php

get_header();

do_action('ash_wrapper_start'); ?>

    <div class="ash-page-header">
        <h2>Booking</h2>
        <!-- Location search description -->
    </div>

    <?php
    /**
     * @ash_booking_form
     */
    do_action('ash_booking_form');

    do_action('ash_wrapper_end');

get_footer();