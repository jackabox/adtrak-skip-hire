<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package Adtrak\Skips\Models
 */
class Order extends Model
{
    /**
     * @var string
     */
	protected $table = 'as_orders';

    /**
     * @var array
     */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
	{
		return $this->hasMany(__NAMESPACE__ . '\OrderItem');
	}
}