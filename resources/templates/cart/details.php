<h3>Your Details</h3>
<table class="ash-table--left">
	<tr>
		<th>Name</th>
		<td><?= $details->ash_forename ?> <?= $details->ash_surname; ?></td>
	</tr>
	<tr>
		<th>Email</th>
		<td><?= $details->ash_email; ?></td>
	</tr>
	<tr>
		<th>Phone</th>
		<td><?= $details->ash_telephone; ?></td>
	</tr>
	<tr>
		<th>Address</th>
		<td>
			<?= $details->ash_address1; ?><br>
			<?php if($details->ash_address2) echo $details->ash_address2 . '<br>'; ?>
			<?= $details->ash_city; ?><br>
			<?= $details->ash_county; ?><br>
			<?= $details->ash_postcode; ?>
		</td>
	</tr>
</table>

<h3>Order Details</h3>
<table class="ash-table--left">
	<tr>
		<th>Delivery Date</th>
		<td><?= $details->ash_delivery_date; ?></td>
	</tr>
	<tr>
		<th>Delivery Time</th>
		<td><?= $details->ash_delivery_time; ?></td>
	</tr>
	<tr>
		<th>Waste</th>
		<td><?php 
			if(isset($details->ash_waste)):
				foreach($details->ash_waste as $waste):
					echo $waste;
					if ($waste !== end($details->ash_waste)) echo ', ';
				endforeach; 
			endif;
		?></td>
	</tr>
	<tr>
		<th>Notes</th>
		<td><?= $details->ash_notes; ?></td>
	</tr>
</table>

<?php if (isset($datePasses) && $datePasses === false): ?>
	<div class="as-notification as-notification-warning">
		<p>The coupon you entered has expired.</p>
	</div>
<?php endif; ?>

<h3>Order Items</h3>
<table>
    <thead>
    <tr>
        <th>Type</th>
        <th>Name</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Skip</td>
        <td><?= $skip->name; ?></td>
        <td>&pound;<?= number_format(round($skip->price, 2), 2, '.', ''); ?></td>
    </tr>
    <tr>
        <td>Permit</td>
        <?php if ($permit): ?>
            <td><?= $permit->name; ?></td>
            <td>&pound;<?= number_format(round($permit->price, 2), 2, '.', ''); ?></td>
        <?php else: ?>
            <td>N/A</td>
            <td>&pound;0.00</td>
        <?php endif; ?>
    </tr>

	<?php if ($coupon): ?>
        <tr>
            <td>Coupon</td>
            <td><?= $coupon->code; ?></td>
			<td>- &pound;<?= number_format(round($couponValue * -1, 2), 2, '.', '') ; ?></td>
        </tr>
    <?php endif; ?>

    <tr>
        <td></td>
        <td><b>Total</b></td>
        <td>&pound;<?= number_format(round($total, 2), 2, '.', ''); ?></td>
    </tr>
    </tbody>
</table>

<div>
    <p>Payments are processed via PayPal.</p>
    <a class="button" href="<?= $paypal; ?>">Pay Via PayPal</a>
</div>