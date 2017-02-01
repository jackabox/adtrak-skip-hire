<form action="<?= site_url('skip-booking/checkout'); ?>" method="post" class="ash-form" data-parsley-validate>
	<div>
		<label for="autcomplete">Enter &amp; Select Your Location</label>
		<input type="text" name="ash_autocomplete" id="ash_autocomplete" palceholder="Enter your Postcode" required>
	</div>

	<input type="hidden" name="ash_lat" id="ash_lat">
	<input type="hidden" name="ash_lng" id="ash_lng">
	<p><button type="submit" class="button" id="ash_submit" disabled>Submit</button></p>
</form>