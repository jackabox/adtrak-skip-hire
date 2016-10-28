<div id="ash">
    <form action="<?php echo get_permalink( get_page_by_title( 'Booking' ) ); ?>" method="POST" id="ash_postcode_form">
        <input type="hidden" id="ash_lat" name="ash_lat">
        <input type="hidden" id="ash_lng" name="ash_lng">

        <p class="ash__input ash__input--text">
            <label for="ash_postcode">Enter a Postcode</label>
            <input type="text" name="ash_postcode" id="ash_postcode">
        </p>

        <p class="ash_postcode_error"></p>

        <p><button type="submit" class="ash__submit" id="ash_postcode_submit">Check Available Skips</button></p>
    </form>
</div>

<script>
    ga('send', 'event', 'Skip Order', 'Submit', 'Postcode Enter Form', {
        nonInteraction: true
    });

    ga('send', {
        'hitType' : 'pageview',
        'page' : '/booking'
    });

    console.log('sent postcode ga');
</script>