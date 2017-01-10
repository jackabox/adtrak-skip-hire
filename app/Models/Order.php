<?php namespace Adtrak\Skips\Models;

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
		'total'
	];

	public function orderItems()
	{
		return $this->hasMany(__NAMESPACE__ . '\OrderItem');
	}
}