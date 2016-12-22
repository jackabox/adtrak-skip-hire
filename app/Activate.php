<?php 

/** @var  \Billy\Framework\Enqueue $enqueue */
use Illuminate\Database\Capsule\Manager as Capsule;
use Adtrak\Skips\Helper;

$version = get_option('adtrak_skips_version', false);

if ($version === false) {
	Capsule::schema()->create('as_locations', function($table)
	{
    	$table->increments('id');
    	$table->string('lat', 100)->nullable(false);
    	$table->string('lng', 100)->nullable(false);
    	$table->decimal('radius', 5, 2)->nullable(false);
		$table->string('name')->nullable(false);
		$table->text('description');
		$table->string('address', 255);
    	$table->timestamps();
	});
	
	Capsule::schema()->create('as_skips', function($table)
	{
		$table->increments('id');
		$table->string('name', 200)->nullable(false);
		$table->string('width', 20);
		$table->string('length', 20);
		$table->string('height', 20);
		$table->string('capacity', 20);
		$table->text('description');
		$table->decimal('price', 10, 2)->nullable(false);
    	$table->timestamps();		
	});

	Capsule::schema()->create('as_coupons', function($table)
	{
		$table->increments('id');
		$table->string('code', 200)->nullable(false);
		$table->string('type', 20)->nullable(false);
		$table->decimal('amount', 10, 2)->nullable(false);	
		$table->dateTime('starts');	
		$table->dateTime('expires');
    	$table->timestamps();		
	});

	Capsule::schema()->create('as_permits', function($table)
	{
		$table->increments('id');
		$table->string('name', 200)->nullable(false);
		$table->decimal('price', 10, 2)->nullable(false);	
    	$table->timestamps();		
	});

	Capsule::schema()->create('as_orders', function($table)
	{
		$table->increments('id');
		$table->string('forename', 200)->nullable(false);
		$table->string('surname', 200)->nullable(false);
		$table->string('email', 200)->nullable(false);
		$table->string('number', 20)->nullable(false);
		$table->string('address1', 200)->nullable(false);
		$table->string('address2', 200);
		$table->string('county', 200);
		$table->string('country', 200)->nullable(false);
		$table->string('city', 200)->nullable(false);
		$table->date('delivery_time');	
		$table->string('delivery_slot', 20);
		$table->text('waste');
		$table->text('notes');
		$table->decimal('subtotal', 10, 2)->nullable(false);	
		$table->decimal('total', 10, 2)->nullable(false);	
		$table->integer('permit_id')->unsigned();	
		$table->foreign('permit_id')->references('id')->on('as_permits');
		$table->integer('coupon_id')->unsigned();
		$table->foreign('coupon_id')->references('id')->on('as_coupons');
		$table->integer('skip_id')->unsigned();
		$table->foreign('skip_id')->references('id')->on('as_skips');
    	$table->timestamps();
	});

	add_option('adtrak_skips_version', Helper::get('version'));	
}