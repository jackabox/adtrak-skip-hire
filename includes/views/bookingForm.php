<h3>Complete Your Order</h3>
<p>Fill out your details and pick a delivery time to proceed.</p>

<form action="" method="POST">
    <!-- user details -->
    <fieldset class="ash__fieldset ash__fieldset-user">
        <legend class="ash__legend ash__legend-user">Your Details</legend>

        <div class="ash__input ash__input--title">
            <label for="ash_title">Title</label>
            <input type="text" name="ash_title" id="ash_title" value="<?php if(isset($_POST['ash_title'])) echo $_POST['ash_title']; ?>">
        </div>

        <div class="ash__input ash__input--forename">
            <label for="ash_forename">Forename</label>
            <input type="text" name="ash_forename" id="ash_forename" value="<?php if(isset($_POST['ash_forename'])) echo $_POST['ash_forename']; ?>">
        </div>

        <div class="ash__input ash__input--surname">
            <label for="ash_surname">Surname</label>
            <input type="text" name="ash_surname" id="ash_surname" value="<?php if(isset($_POST['ash_surname'])) echo $_POST['ash_surname']; ?>">
        </div>

        <div class="ash__input ash__input--email">
            <label for="ash_email">Email Address</label>
            <input type="email" name="ash_email" id="ash_email" value="<?php if(isset($_POST['ash_email'])) echo $_POST['ash_email']; ?>">
        </div>

        <div class="ash__input ash__input--phone">
            <label for="ash_phone">Phone Number</label>
            <input type="tel" name="ash_phone" id="ash_phone" value="<?php if(isset($_POST['ash_phone'])) echo $_POST['ash_phone']; ?>">
        </div>
    </fieldset>

    <!-- addresses -->
    <fieldset class="ash__fieldset ash__fieldset-delivery">
        <legend class="ash__legend ash__legend-delivery">Delivery Address</legend>
        
        <div class="ash__input ash__input--address">
            <label for="ash_delivery_address_1">Address Line 1</label>
            <input type="text" name="ash_delivery_address_1" id="ash_delivery_address_1" value="<?php if(isset($_POST['ash_delivery_address_1'])) echo $_POST['ash_delivery_address_1']; ?>">
        </div>

        <div class="ash__input ash__input--address">
            <label for="ash_delivery_address_2">Address Line 2</label>
            <input type="text" name="ash_delivery_address_2" id="ash_delivery_address_2" value="<?php if(isset($_POST['ash_delivery_address_2'])) echo $_POST['ash_delivery_address_2']; ?>">
        </div>

        <div class="ash__input ash__input--city">
            <label for="ash_delivery_city">City</label>
            <input type="text" name="ash_delivery_city" id="ash_delivery_city" value="<?php if(isset($_POST['ash_delivery_city'])) echo $_POST['ash_delivery_city']; ?>">
        </div>

        <div class="ash__input ash__input--county">
            <label for="ash_delivery_county">County</label>
            <input type="text" name="ash_delivery_county" id="ash_delivery_county" value="<?php if(isset($_POST['ash_delivery_county'])) echo $_POST['ash_delivery_county']; ?>">
        </div>

        <div class="ash__input ash__input--postcode">
            <label for="ash_delivery_postcode">Post Code</label>
            <input type="text" name="ash_postcode" id="ash_postcode" value="<?php echo $_SESSION["ash_postcode"]; ?>" disabled>
        </div>

        <p class="ash__show-billing"><a href="javascript:void(0);">Is your billing address different?</a></p>
    </fieldset>

    <fieldset class="ash__fieldset ash__fieldset-billing" style="display: none;">
        <legend class="ash__legend ash__legend-billing">Billing Address</legend>
        
        <div class="ash__input ash__input--address">
            <label for="ash_billing_address_1">Address Line 1</label>
            <input type="text" name="ash_billing_address_1" id="ash_billing_address_1" value="<?php if(isset($_POST['ash_billing_address_1'])) echo $_POST['ash_billing_address_1']; ?>">
        </div>

        <div class="ash__input ash__input--address">
            <label for="ash_billing_address_2">Address Line 2</label>
            <input type="text" name="ash_billing_address_2" id="ash_billing_address_2" value="<?php if(isset($_POST['ash_billing_address_2'])) echo $_POST['ash_billing_address_2']; ?>">
        </div>

        <div class="ash__input ash__input--city">
            <label for="ash_billing_city">City</label>
            <input type="text" name="ash_billing_city" id="ash_billing_city" value="<?php if(isset($_POST['ash_billing_city'])) echo $_POST['ash_delivery_county']; ?>">
        </div>

        <div class="ash__input ash__input--county">
            <label for="ash_billing_county">County</label>
            <input type="text" name="ash_billing_county" id="ash_billing_county" value="<?php if(isset($_POST['ash_billing_county'])) echo $_POST['ash_billing_county']; ?>">
        </div>

        <div class="ash__input ash__input--postcode">
            <label for="ash_billing_postcode">Post Code</label>
            <input type="text" name="ash_billing_postcode" id="ash_billing_postcode" value="<?php if(isset($_POST['ash_billing_postcode'])) echo $_POST['ash_billing_postcode']; ?>">
        </div>
    </fieldset>

    <fieldset class="ash__fieldset ash__fieldset-date">
        <legend class="ash__legend ash__legend-date">Delivery Date/Time</legend>

        <div class="ash__input ash__input--date">
            <label for="ash_delivery_date">Pick a Delivery Date</label>
            <input type="date" name="ash_delivery_date" id="ash_delivery_date" placeholder="dd/mm/yyyy" <?php if(isset($_POST['ash_delivery_date'])) echo $_POST['ash_delivery_date']; ?>>
        </div>

        <div class="ash__input ash__input--time">
            <span class="ash__fake-label">Pick a Time Slot</span>
            
            <input type="radio" class="ash__input--radio" id="ash_delivery_am" name="ash_delivery_time[]" value="AM" <?php if(isset($_POST['ash_delivery_time']) && $_POST['ash_delivery_time'][0] == 'AM') echo "checked" ?>> <label for="ash_delivery_am">AM</label>
            <input type="radio" class="ash__input--radio" id="ash_delivery_pm" name="ash_delivery_time[]" value="PM" <?php if(isset($_POST['ash_delivery_time']) && $_POST['ash_delivery_time'][0] == 'PM') echo "checked" ?>> <label for="ash_delivery_pm">PM</label>
        </div>
    </fieldset>

    <!-- permit, waste, notes -->
    <fieldset class="ash__fieldset ash__fieldset-notes">
        <legend class="ash__legend ash__legend-notes">Notes</legend>

        <div class="ash__select ash__select--permit">
            <label for="ash_permit_id">Do You Need a Permit?</label>
            <select name="ash_permit_id" id="ash_permit_id">
                <option value="">No Permit Needed</option>
               <?php
                // query all of the permits available
                $args = [
                    'post_type'              => 'ash_permits',
                    'post_status'            => 'publish',
                    'posts_per_page'         => -1,
                    'cache_results'          => true,
                ];

                $query = new WP_Query( $args );
                if ( $query->have_posts() ): while ( $query->have_posts() ):
                    $query->the_post(); ?>
                <option value="<?php echo get_the_ID(); ?>" <?php if(isset($_POST['ash_permit_id']) && ($_POST['ash_permit_id'] == get_the_ID())) echo "selected"; ?>><?php echo get_the_title(); ?> (£<?php echo get_post_meta(get_the_ID(), 'ash_permits_price', true); ?>)</option>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </select>
        </div>

        <div class="ash__checkboxes ash__checkboxes--waste">
            <span class="ash__fake-label">Check all the types of waste you will be using the skip for</span>
            <div>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[0]" id="ash_waste_concrete" class="ash__checkbox" value="Concrete" <?php if(isset($_POST['ash_waste'][0])) echo "checked" ?>> <label for="ash_waste_concrete">Concrete</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[1]" id="ash_waste_metal" class="ash__checkbox" value="Metal" <?php if(isset($_POST['ash_waste'][1])) echo "checked" ?>> <label for="ash_waste_metal">Metal</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[2]" id="ash_waste_paper" class="ash__checkbox" value="Paper/Card" <?php if(isset($_POST['ash_waste'][2])) echo "checked" ?>> <label for="ash_waste_paper">Paper/Card</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[3]" id="ash_waste_plastic" class="ash__checkbox" value="Plastic" <?php if(isset($_POST['ash_waste'][3])) echo "checked" ?>> <label for="ash_waste_plastic">Plastic</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[4]" id="ash_waste_rubble" class="ash__checkbox" value="Rubble/Brick" <?php if(isset($_POST['ash_waste'][4])) echo "checked" ?>> <label for="ash_waste_rubble">Rubble/Brick</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[5]" id="ash_waste_soil" class="ash__checkbox" value="Soil" <?php if(isset($_POST['ash_waste'][5])) echo "checked" ?>> <label for="ash_waste_soil">Soil</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[6]" id="ash_waste_wood" class="ash__checkbox" value="Wood" <?php if(isset($_POST['ash_waste'][6])) echo "checked" ?>> <label for="ash_waste_wood">Wood</label></span>
                <span class="ash__checkbox-wrapper"><input type="checkbox" name="ash_waste[7]" id="ash_waste_other" class="ash__checkbox" value="Other" <?php if(isset($_POST['ash_waste'][7])) echo "checked" ?>> <label for="ash_waste_other">Other</label></span>
            </div>
        </div>

        <div class="ash__textarea ash__textarea--notes">
            <label for="ash_notes">Additional Notes</label>
            <textarea name="ash_notes" id="ash_notes"><?php if(isset($_POST['ash_notes'])) echo $_POST['ash_notes']; ?></textarea>
        </div>
    </fieldset>

    <!-- proceed to confirmation -->
    <input type="submit" name="ash_submit" id="ash_submit" class="ash__submit" value="Confirm Order">
</form>