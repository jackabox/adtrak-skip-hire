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
    	$table->decimal('radius', 4, 2)->nullable(false);
		$table->string('name')->nullable(false);
		$table->text('description');
		$table->string('address', 255);
    	$table->timestamps();
	});
	
	add_option('adtrak_skips_version', Helper::get('version'));
}