<?php namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
	protected $table = 'as_order_item';

	protected $fillable = [
		'order_id',
		'name',
		'type',
		'price'
	];
}