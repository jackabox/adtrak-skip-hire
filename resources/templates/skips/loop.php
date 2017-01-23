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

		<?php if($skip->image_url): ?>	
			<div class="ash-skip__image">
				<p><img src="<?= $skip->image_url ?>" alt="Image of <?= $skip->name ?>"></p>
			</div>
			<div class="ash-skip__content ash-skip__content--float">	
		<?php else: ?>
			<div class="ash-skip__content">
		<?php endif; ?>

			<h4><?= $skip->name ?></h4>

			<p>&pound;<?= $skip->price; ?></p>
			
			<?php if($skip->description): ?>
				<p><?= $skip->description; ?></p>
			<?php endif; ?>

			<p class="ask-skip__meta">
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

			<form action="<?= site_url('skip-booking/checkout'); ?>" method="POST">
				<input type="hidden" name="skip_id" value="<?= $skip->id; ?>">
				<input type="hidden" name="postcode" value="<?= $postcode ?>">
				<p><button type="submit" class="ash-button">Purchase</button></p>
			</form>
		</div>
	</div>

<?php endforeach; ?>

<?= $pagination; ?>
