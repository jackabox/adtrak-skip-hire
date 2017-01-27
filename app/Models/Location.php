<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package Adtrak\Skips\Models
 */
class Location extends Model
{
    /**
     * @var string
     */
	protected $table = 'as_locations';

    /**
     * @var array
     */
	protected $fillable = [
        'lat',
		'lng',
		'radius',
		'name',
		'description',
		'address'
	];
}