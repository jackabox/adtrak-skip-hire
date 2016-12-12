<?php 

/** @var  \Billy\Framework\Enqueue $enqueue */
use Illuminate\Database\Capsule\Manager as Capsule;
use Adtrak\Windscreens\Helper;

$version = get_option('adtrak_windscreens_version', false);

if ($version === false) {
	Capsule::schema()->create('aw_locations', function($table)
	{
    	$table->increments('id');
    	$table->string('lat', 100);
    	$table->string('lng', 100);
    	$table->decimal('radius', 4, 2);
		$table->string('name');
		$table->text('description');
		$table->string('number', 25);
		$table->string('address', 255);
    	$table->timestamps();
	});
	
	add_option('adtrak_windscreens_version', Helper::get('version'));
}