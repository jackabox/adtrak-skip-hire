<?php namespace Adtrak\Skips;

/** @var \Billy\Framework\Enqueue $enqueue */

$enqueue->admin([
	'as' => 'adtrak-skips',
	'src' => Helper::assetUrl('css/skips.css')
]);

$enqueue->front([
	'as' => 'adtrak-skip-frontend',
	'src' => Helper::assetUrl('css/skip-frontend.css')
]);

$enqueue->front([
	'as' => 'adtrak-skips',
	'src' => Helper::assetUrl('js/location.js')
], 'footer');

$enqueue->front([
	'as' => 'parsleyjs',
	'src' => 'https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.6.2/parsley.min.js'
], 'footer');