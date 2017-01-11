<?php

namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class Skip extends Model
{
	 protected $table = 'as_skips';

	 protected $fillable = [
		 'name', 
		 'width', 
		 'length', 
		 'height', 
		 'capacity',
		 'description', 
		 'price',
		 'image_path',
		 'image_url'
	 ];
}