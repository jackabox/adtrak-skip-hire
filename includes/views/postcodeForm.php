<form action="<?php echo get_permalink( get_page_by_title( 'Booking' ) ); ?>" method="POST" id="ash_postcode_form">
    <input type="hidden" id="ash_lat" name="ash_lat">
    <input type="hidden" id="ash_lng" name="ash_lng">

    <p>
        <label for="ash_postcode">Enter a Postcode</label>
        <input type="text" name="ash_postcode" id="ash_postcode">
    </p>

    <p><button type="submit" id="ash_postcode_submit">Check Available Skips</button></p>
</form>