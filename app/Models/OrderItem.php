<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * @package Adtrak\Skips\Models
 */
class OrderItem extends Model
{
    /**
     * @var string
     */
	protected $table = 'ash_order_item';

    /**
     * @var bool
     */
	public $timestamps = false;

    /**
     * @var array
     */
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