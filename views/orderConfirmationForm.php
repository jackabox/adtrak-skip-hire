<div id="ash">
    <h3>Order Overview</h3>

    <p>Please review your information, then proceed to payment. If anything seems wrong, <a href="javascript:history.back()">go back</a> and edit the fields.</p>

    <h4>Items Overiew</h4>

    <table class="ash__table ash_table--overview">
        <thead>
            <tr>
                <th>Item</th>
                <th>Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Skip</td>
                <td><?php echo $skip['title']; ?></td>
                <td>£<?php echo $skip['price']; ?></td>
            </tr>

            <tr>
                <td>Permit</td>
                <td><?php echo ($permit['title']) ? $permit['title'] : '-'; ?></td>
                <td>£<?php echo number_format( (float)$permit['price'], 2, '.', ''); ?></td>
            </tr>

            <?php if( $coupon['price'] != 0.00 ) { ?>
                <tr>
                    <td>Coupon</td>
                    <td><?php echo $coupon['title']?></td>
                    <td>-£<?php echo number_format( (float)$coupon['price'], 2, '.', ''); ?></td>
                </tr>
            <?php } ?>

            <tr>
                <td></td>
                <td><b>Total</b></td>
                <td>£<?php echo number_format( (float)$total, 2, '.', '') ?></td>
            </tr>
        </tbody>
    </table>

    <h4>Your Details</h4>

    <table class="ash__table ash_table--details">
        <tbody>
            <tr>
                <td>Name</td>
                <td><?php echo $_POST['ash_forename'] . ' ' . $_POST['ash_surname']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $_POST['ash_email'] ?></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><?php echo $_POST['ash_phone'] ?></td>
            </tr>
            <tr>
                <td>Delivery Address</td>
                <td>
                    <?php echo $_POST['ash_delivery_address_1'] ?> <br>
                    <?php if($_POST['ash_delivery_address_2']) { echo $_POST['ash_delivery_address_2'] . '<br>'; }?>
                    <?php echo $_POST['ash_delivery_city'] ?> <br>
                    <?php echo $_POST['ash_delivery_county'] ?> <br>
                    <?php echo strtoupper($_SESSION['ash_postcode']) ?> <br>
                </td>
            </tr>
            <tr>
                <td>Delivery Date</td>
                <td><?php echo $_POST['ash_delivery_date']; ?></td>
            </tr>
            <?php $options = get_option( 'ash_general_page' );
            if( !empty($options['ash_enable_am_pm'] )) : ?>
            <tr>
                <td>Delivery Slot</td>
                <td><?php echo (isset( $_POST['ash_delivery_time'][0] )) ? $_POST['ash_delivery_time'][0] : 'Not Specified'; ?></td>
            </tr>
            <?php endif; ?>
            <?php
                $waste = (isset( $_POST['ash_waste'] )) ? $_POST['ash_waste'] : null;
                if($waste): ?>
            <tr>
                <td>Waste Options</td>
                <td><?php $i = 1; foreach( $waste as $w ) { echo $w; echo ($i < count($waste)) ? ', ' : ''; $i++; } ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Additional Notes</td>
                <td><?php echo $_POST['ash_notes']; ?></td>
            </tr>
        </tbody>
    </table>

    <form action="<?php echo home_url('/booking/confirmation'); ?>" method="GET">
        <?php if( !empty($options['ash_enable_tc'] )) : ?>
        <p>
            <input type="checkbox" name="ash_read_tc" id="ash_read_tc" value="true" required>
            <label for="ash_read_tc">Do you agree to the <a href="<?php echo (!empty($options['ash_tc_link'] )) ? $options['ash_tc_link'] : ''; ?>">terms and conditions</a>?</label>
        </p>
        <?php endif; ?>
        <p>
            <span><input type="submit" name="ash_place_order_paypal"  id="ash_place_order_paypal" value="Pay Online"></span>
            <span><input type="submit" name="ash_place_order_phone" id="ash_place_order_phone" value="Pay Over Telephone"></span>             
        </p>
        <p>
            <small>We accept online payments using PayPal and accept the following cards:</small> <br> <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" border="0" alt="PayPal Acceptance Mark" width="28">
            <i><img src="<?= plugins_url( '../assets/img/mastercard.svg', __FILE__); ?>" width="28" alt="Mastercard"></i> <i><img src="<?= plugins_url( '../assets/img/visa.svg', __FILE__); ?>" width="28" alt="Visa Card"></i> <i><img src="<?= plugins_url( '../assets/img/jcb.svg', __FILE__); ?>" width="28" alt="JCB Card"></i> <i><img src="<?= plugins_url( '../assets/img/amex.svg', __FILE__); ?>" width="28" alt="Amex Card"></i>
        </p>
    </form>
</div>


 <script>
    ga('send', 'event', 'Skip Order', 'Submit', 'Confirmation Page', {
        nonInteraction: true
    });

    ga('send', {
        'hitType' : 'pageview',
        'page' : '/confirmation'
    });

    console.log('sent');
</script>