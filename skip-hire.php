<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Skip Hire
 * Plugin URI:        http://skiphire.adtrakdev.com/
 * Description:       Adding the ability to hire skips and process payments within areas.
 * Version:           1.0.0
 * Author:            Adtrak
 * Author URI:        http://adtrak.co.uk/
 * License:           MIT
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require plugin_dir_path(__FILE__) . 'includes/classes/SkipHire.php';

function run_skip_hire() {
    $plugin = new SkipHire();
}

run_skip_hire();