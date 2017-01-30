<?php
namespace Adtrak\Skips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Skip
 * @package Adtrak\Skips\Models
 */
class Skip extends Model
{
    /**
     * @var string
     */
	protected $table = 'ash_skips';

    /**
     * @var array
     */
	protected $fillable = [
        'name',
        'width',
        'length',
        'height',
        'capacity',
        'description',
        'price',
        'image_path',
	    'image_url'
	];
}