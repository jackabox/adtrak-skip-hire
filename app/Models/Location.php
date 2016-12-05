<?php

namespace Adtrak\Windscreens\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	 protected $table = 'aw_locations';
	 protected $fillable = [
		 'lat', 'lng', 'radius', 'name'
	 ];
}