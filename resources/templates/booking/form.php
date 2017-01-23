<form action="<?= site_url('skip-booking/checkout'); ?>" method="post" class="ash-form">
	<p class="aw_warning" style="display: none;">Please select a valid location from the drop down before submitting</p>

	<div>
		<label for="autcomplete">Enter Your Location</label>
		<input type="text" name="autocomplete" id="as_autocomplete" palceholder="Enter your Postcode">
	</div>

	<input type="hidden" name="lat" id="as_lat">
	<input type="hidden" name="lng" id="as_lng">
	<p><button type="submit" class="button" id="as_submit" disabled>Submit</button></p>
</form>