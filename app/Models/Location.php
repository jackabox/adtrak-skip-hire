<?php

namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	 protected $table = 'as_locations';

	 protected $fillable = [
		 'lat', 
		 'lng', 
		 'radius', 
		 'name',
		 'description',
		 'address'
	 ];
}