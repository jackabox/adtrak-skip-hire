<div class="as-notification as-notification-warning hidden">
	<h4>Oh snap!</h4>
	<p>Looks like something went wrong, please check the form below and try resubmitting.</p>
</div>

<form action="<?= site_url('skip-booking/cart/'); ?>" method="POST" class="ash-form" data-parsley-validate>
	<fieldset>
		<legend>Your Details</legend>

		<div>
			<div class="ash-field--half">
				<label for="ash_forename">Forename</label>
				<input type="text" name="ash_forename" id="ash_forename" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_surname">Surname</label>
				<input type="text" name="ash_surname" id="ash_surname" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_email">Email</label>
				<input type="email" name="ash_email" id="ash_email" data-parsley-trigger="change" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_telephone">Telephone</label>
				<input type="tel" name="ash_telephone" id="ash_telephone" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_address1">Address 1</label>
				<input type="text" name="ash_address1" id="ash_address1" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_address2">Address 2</label>
				<input type="text" name="ash_address2" id="ash_address2">
			</div>

			<div class="ash-field--half">
				<label for="ash_city">City</label>
				<input type="text" name="ash_city" id="ash_city" required>
			</div>

			<div class="ash-field--half">	
				<label for="ash_county">County</label>
				<input type="text" name="ash_county" id="ash_county">
			</div>

			<div class="ash-field--half">	
				<label for="ash_country">Country</label>
				<input type="text" name="ash_country" id="ash_country" required>
			</div>

			<div class="ash-field--half">
				<label for="ash_postcode">Postcode</label>
				<input type="text" name="ash_postcode" id="ash_postcode" required>
			</div>
		</div>

	</fieldset>

	<fieldset>
		<legend>Delivery Options</legend>

		<div>
			<div class="ash-field--half">
				<label for="ash_delivery_date">Delivery Date</label>
				<input type="text" name="ash_delivery_date" id="ash_delivery_date" placeholder="DD/MM/YYYY" required data-days="<?= get_option('ash_delivery')['available_days'] ?>" data-delivery-from="<?= get_option('ash_delivery')['future_days']; ?>">
			</div>

			<div class="ash-field--half">
				<label>Delivery Time</label>
				<label class="ash-label--inline"><input type="radio" name="ash_delivery_time" value="Morning" checked required> Morning</label>
				<label class="ash-label--inline"><input type="radio" name="ash_delivery_time" value="Afternoon"> Afternoon</label>
			</div>

			<div>
				<label for="ash_permit">Permit</label>
				<select name="ash_permit" id="ash_permit">
					<option value="">If needed, select a permit</option>
					<?php foreach($permits as $permit): ?>
						<option value="<?= $permit->id; ?>"><?= $permit->name; ?> (£<?= $permit->price; ?>)</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<div>
			<div>
				<label>What waste will you be using the skip for?</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Concrete"> Concrete</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Metal"> Metal</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Paper / Card"> Paper / Card</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Rubble / Brick"> Rubble / Brick</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Soil"> Soil</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Wood"> Wood</label>
				<label class="ash-label--inline"><input type="checkbox" name="ash_waste[]" value="Other"> Other</label>
			</div>

			<div>
				<label for="ash_notes">Additional Notes</label>
				<textarea name="ash_notes" id="ash_notes" placeholder="Do you have any additional notes you would like to leave?"></textarea>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<p>Do you have a coupon code? Enter it below to receive discount on your skip.</p>

		<div>
			<div class="ash-field--half">
				<label for="ash_coupon">Coupon Code</label>
				<input type="text" id="ash_coupon" name="ash_coupon">
			</div>
		</div>
	</fieldset>

	<input type="hidden" name="ash_skip" value="<?= $skip->id; ?>">
	<button type="submit" name="ash_submit" id="ash_submit" value="true">Confirm Order</button>

</form>