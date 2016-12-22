<?php namespace Adtrak\Skips\Controllers;

use Adtrak\Skips\Helper;

class FrontController
{
	private static $instance = null;

	public static function instance()
	{
 		null === self::$instance and self::$instance = new self;
        return self::$instance;
	}

	public function scripts()
	{
		wp_enqueue_script('maps-api', '//maps.googleapis.com/maps/api/js?key='. get_option('ash_google_maps_api', '') .'&libraries=places');
		wp_enqueue_script('adtrak-skips', Helper::assetUrl('js/location.js'), ['jquery'], '', true);
	}

	public function createPages()
	{
		$title = 'Privacy Policy';
		$post_content = '[ash_booking_form]';

		if (get_page_by_title($title) == null) {
			$post = [
				'ping_status' 	=> 'closed' ,
				'post_date' 	=> date('Y-m-d H:i:s'),
				'post_name' 	=> 'privacy-policy',
				'post_status' 	=> 'publish' ,
				'post_title' 	=> $title,
				'post_type' 	=> 'page',
				'post_content' 	=> $post_content
			];

			$post_id = wp_insert_post($post);
		}
	}
}