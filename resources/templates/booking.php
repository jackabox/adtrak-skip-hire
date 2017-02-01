<?php

get_header();

    do_action('ash_wrapper_start');

    /**
     * @header
     * @form
     */
    do_action('ash_booking_header');
    do_action('ash_booking_form');

    do_action('ash_wrapper_end');

get_footer();
