<?php namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
	protected $table = 'as_order_item';
	public $timestamps = false;

	protected $fillable = [
		'order_id',
		'name',
		'type',
		'price'
	];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(__NAMESPACE__ . '\Order');
    }
}