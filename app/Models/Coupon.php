<?php

namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
	 protected $table = 'as_coupons';

	 protected $fillable = [
		 'name', 'type', 'amount',
		 'starts', 'expires'
	 ];
}