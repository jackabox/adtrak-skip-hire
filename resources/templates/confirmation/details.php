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
			<td>&pound; <?= $skip->price; ?></td>
		</tr>
		<tr>
			<td>Permit</td>
			<?php if ($permit): ?>		
				<td><?= $permit->name; ?></td>
				<td>&pound; <?= $permit->price; ?></td>
			<?php else: ?>
				<td>N/A</td>
				<td>&pound;0.00</td>
			<?php endif; ?>
		</tr>

		<?php if ($coupon): ?>			
			<tr>
				<td>Coupon</td>
				<td><?= $coupon->name; ?></td>
				<td>-&pound; <?= $coupon->price; ?></td>
			</tr>
		<?php endif; ?>
		
		<tr>
			<td></td>
			<td><b>Total</b></td>
			<td>&pound; <?= $total; ?>
		</tr>
	</tbody>
</table>

<h3>Order Details</h3>
