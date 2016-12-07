<?php 
namespace Adtrak\Windscreens;

/** @var \Billy\Framework\Enqueue $enqueue */
$enqueue->admin([
	'as' => 'adtrak-windscreens',
	'src' => Helper::assetUrl('css/windscreens.css')
]);