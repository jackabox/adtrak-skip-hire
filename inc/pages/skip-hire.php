<?php

add_action('admin_menu', 'add_skip_hire_page');

function add_skip_hire_page()
{
    add_menu_page( 'Skip Hire Options', 'Skip Hire', 'manage_options', 'ad_skip_hire', 'skip_hire_options', '', 20 );
}

function skip_hire_options() 
{
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form would go if I actually had options.</p>';
    echo '</div>';
}