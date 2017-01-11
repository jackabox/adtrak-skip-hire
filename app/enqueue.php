<?php namespace Adtrak\Skips;

/** @var \Billy\Framework\Enqueue $enqueue */

$enqueue->admin([
	'as' => 'adtrak-skips',
	'src' => Helper::assetUrl('css/skips.css')
]);

$enqueue->admin([
	'as' => 'chart-js',
	'src' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js'
]);

$enqueue->front([
	'as' => 'adtrak-skip-frontend',
	'src' => Helper::assetUrl('css/skip-frontend.css')
]);