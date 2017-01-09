<?php

namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	 protected $table = 'as_orders';

	protected $fillable = [
		'forename', 
		'surname', 
		'email', 
		'number', 
		'address1', 
		'address2', 
		'county', 
		'country', 
		'city', 
		'delivery_time', 
		'delivery_slot', 
		'waste', 
		'notes', 
		'subtotal', 
		'total', 
		'permit_id', 
		'coupon_id', 
		'skip_id'
	];

	public function permit()
	{
		return $this->hasOne('Permit');
	}

	public function coupon()
	{
		return $this->hasOne('Coupon');
	}

	public function skip()
	{
		return $this->hasOne('Skip');
	}
}