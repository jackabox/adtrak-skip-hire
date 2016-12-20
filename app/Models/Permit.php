<?php

namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
	 protected $table = 'as_permits';

	 protected $fillable = [
		 'name', 'price'
	 ];
}