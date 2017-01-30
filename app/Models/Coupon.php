<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Coupon
 * @package Adtrak\Skips\Models
 */
class Coupon extends Model
{
    /**
     * @var string
     */
	protected $table = 'ash_coupons';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'amount',
        'starts',
        'expires'
    ];
}