<?php 

/** @var  \Billy\Framework\Enqueue $enqueue */

use Illuminate\Database\Capsule\Manager as Capsule;

# Get the version
$version = get_option('adtrak_skips_version');

/**
 * If the version returns false, i.e. first install run the code to
 * this will check if tables exist.
 */
if ($version === false) {
    if (! Capsule::schema()->hasTable('ash_locations')) {
        Capsule::schema()->create('ash_locations', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->string('address', 255);
            $table->text('description');
            $table->string('lat', 100)->nullable(false);
            $table->string('lng', 100)->nullable(false);
            $table->decimal('radius', 5, 2)->nullable(false);
            $table->decimal('delivery_fee', 6, 2)->nullable(false);
            $table->timestamps();
        });
    }

    if (! Capsule::schema()->hasTable('ash_skips')) {
        Capsule::schema()->create('ash_skips', function ($table) {
            $table->increments('id');
            $table->string('name', 200)->nullable(false);
            $table->string('width', 20);
            $table->string('length', 20);
            $table->string('height', 20);
            $table->string('capacity', 20);
            $table->text('description');
            $table->string('image_path');
            $table->string('image_url');
            $table->decimal('price', 10, 2)->nullable(false);
            $table->timestamps();
        });
    }

    if (! Capsule::schema()->hasTable('ash_coupons')) {
        Capsule::schema()->create('ash_coupons', function ($table) {
            $table->increments('id');
            $table->string('code', 200)->nullable(false);
            $table->string('type', 20)->nullable(false);
            $table->decimal('amount', 10, 2)->nullable(false);
            $table->dateTime('starts');
            $table->dateTime('expires');
            $table->timestamps();
        });
    }

    if (! Capsule::schema()->hasTable('ash_permits')) {
        Capsule::schema()->create('ash_permits', function ($table) {
            $table->increments('id');
            $table->string('name', 200)->nullable(false);
            $table->decimal('price', 10, 2)->nullable(false);
            $table->timestamps();
        });
    }

    if (! Capsule::schema()->hasTable('ash_orders')) {
        Capsule::schema()->create('ash_orders', function ($table) {
            $table->increments('id');
            $table->string('forename', 200)->nullable(false);
            $table->string('surname', 200)->nullable(false);
            $table->string('email', 200)->nullable(false);
            $table->string('number', 20)->nullable(false);
            $table->string('address1', 200)->nullable(false);
            $table->string('address2', 200);
            $table->string('city', 200)->nullable(false);
            $table->string('county', 200);
            $table->string('country', 200)->nullable(false);
            $table->string('postcode', 10)->nullable(false);
            $table->date('delivery_date');
            $table->string('delivery_slot', 20);
            $table->text('waste');
            $table->text('notes');
            $table->decimal('total', 10, 2)->nullable(false);
            $table->string('payment_method', 20)->nullable(false);
            $table->string('payment_reference', 50);
            $table->string('status', 20)->nullable(false);
            $table->timestamps();
        });
    }

    if (! Capsule::schema()->hasTable('ash_order_item')) {
        Capsule::schema()->create('ash_order_item', function ($table)
        {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('name');
            $table->string('type')->nullable(false);
            $table->decimal('total', 10, 2)->nullable(false);
            $table->timestamps();
        });
	}

    /**
     * If the skip-booking page does not exist, create it.
     * If it does exist, get the page by it's url and return the ID.
     */
    if (get_page_by_path('skip-booking') === null) {
        $bookingID = wp_insert_post([
            'ping_status' 	=> 'closed',
            'post_date' 	=> date('Y-m-d H:i:s'),
            'post_name' 	=> 'skip-booking',
            'post_status' 	=> 'publish',
            'post_title' 	=> 'Booking',
            'post_type' 	=> 'page'
        ]);
    } else {
        $bookingID = get_page_by_path('skip-booking')->ID;
    }

    /**
     * If the skip-sizes page does not exist, create it.
     */
    if (get_page_by_path('skip-sizes') === null) {
        wp_insert_post([
            'ping_status' 	=> 'closed',
            'post_date' 	=> date('Y-m-d H:i:s'),
            'post_name' 	=> 'skip-sizes',
            'post_status' 	=> 'publish',
            'post_title' 	=> 'Skip Sizes',
            'post_type' 	=> 'page'
        ]);
    }

    /**
     * If the skip-sizes/checkout page does not exist, create it.
     */
    if (get_page_by_path('skip-booking/checkout') === null) {
        wp_insert_post([
            'ping_status' 	=> 'closed',
            'post_date' 	=> date('Y-m-d H:i:s'),
            'post_name' 	=> 'checkout',
            'post_status' 	=> 'publish',
            'post_title' 	=> 'Checkout',
            'post_type' 	=> 'page',
            'post_parent'	=> $bookingID
        ]);
    }

    /**
     * If the skip-booking/cart page does not exist, create it.
     */
    if (get_page_by_path('skip-booking/cart') === null) {
        wp_insert_post([
            'ping_status' 	=> 'closed',
            'post_date' 	=> date('Y-m-d H:i:s'),
            'post_name' 	=> 'cart',
            'post_status' 	=> 'publish',
            'post_title' 	=> 'Cart',
            'post_type' 	=> 'page',
            'post_parent'	=> $bookingID
        ]);
    }

    /**
     * If the skip-booking/confirmation page does not exist, create it.
     */
    if (get_page_by_path('skip-booking/confirmation') === null) {
        wp_insert_post([
            'ping_status' 	=> 'closed',
            'post_date' 	=> date('Y-m-d H:i:s'),
            'post_name' 	=> 'confirmation',
            'post_status' 	=> 'publish',
            'post_title' 	=> 'Confirmation',
            'post_type' 	=> 'page',
            'post_parent'	=> $bookingID
        ]);
    }

    # Set the version, based on the Helper
    add_option('adtrak_skips_version', Helper::get('version'));
}

