<?php namespace Adtrak\Skips;

/** @var \Billy\Framework\Enqueue $enqueue */

$enqueue->admin([
	'as' => 'adtrak-skips',
	'src' => Helper::assetUrl('css/skips.css')
]);

$enqueue->admin([
	'as' => 'adtrak-skips',
	'src' => Helper::assetUrl('js/admin.js')
], 'footer');