<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permit
 * @package Adtrak\Skips\Models
 */
class Permit extends Model
{
    /**
     * @var string
     */
	protected $table = 'ash_permits';

    /**
     * @var array
     */
	protected $fillable = [
	    'name',
        'price'
	];
}