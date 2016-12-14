<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	Adtrak: Skip Hire
 * Plugin URI: 		https://github.com/adtrak/skips
 * Description: 	Boilerplate for rapid plugin development. Built initially for Adtrak.
 * Version: 		0.1.0
 * Author: 			Adtrak
 * Author URI: 		https://adtrak.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     aloc
 */

# if this file is called directly, abort
if (! defined( 'WPINC' )) die;

if (!defined('JB_PLUGIN_PATH')) {
	define('JB_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
}

if (!defined('JB_PLUGIN_URL') ) {
	define('JB_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/getbilly/framework/bootstrap/autoload.php';

$client = new Raven_Client('https://b8752607c9f74cfcbc12681e3b50dedc:2ba1e8b0f0264760947658a89cfb0f33@sentry.io/122240');
$client->install();