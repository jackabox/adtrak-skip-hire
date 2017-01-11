<?php
/**
 * Skip - Loop
 *
 * This template can be overridden by copying it to yourtheme/adtrak-skips/skips/loop.php.
 *
 * @author 		Adtrak
 * @package 	Adtrak/Skips/Templates
 * @version     2.0.0
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<?php foreach($skips as $skip): ?>

	<div class="ash-skip__item">
		<h4><?= $skip->name ?></h4>

		<p>&pound;<?= $skip->price; ?></p>
		
		<?php if($skip->description): ?>
			<p><?= $skip->description; ?></p>
		<?php endif; ?>

		<p>
			<?php if($skip->width): ?>
				<span><b>Width:</b> <?= $skip->width ?>m</span>
			<?php endif; ?>

			<?php if($skip->height): ?>
				<span><b>Height:</b> <?= $skip->height ?>m</span>
			<?php endif; ?>

			<?php if($skip->length): ?>
				<span><b>Length:</b> <?= $skip->length ?>m</span>
			<?php endif; ?>
			
			<?php if($skip->capacity): ?>
				<span><b>Capacity:</b> <?= $skip->capacity ?></span>
			<?php endif; ?>
		</p>

		<p><a href="#" class="ash-button">Purchase</a></p>
	</div>

<?php endforeach; ?>
