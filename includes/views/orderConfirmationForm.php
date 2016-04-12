<h3>Confirmation of Your Order</h3>
<p>Please review your information, then proceed to payment. If anything seems wrong, <a href="javascript:history.back()">go back</a> and edit the fields.</p>

<h4>Order Overiew</h4>
<table>
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
            <td><?php echo $permit['title']; ?></td>
            <td>£<?php echo $permit['price']; ?></td>
        </tr>

        <?php if( $coupon['price'] != 0 ) { ?>
            <tr>
                <td>Coupon</td>
                <td><?php echo $coupon['title']?></td>
                <td>£<?php echo $coupon['price'] ?></td>
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
<table>
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
            <td><?php echo date('d/m/Y', strtotime($_POST['ash_delivery_date'])); ?></td>
        </tr>
        <tr>
            <td>Delivery Slot</td>
            <td><?php echo $_POST['ash_delivery_time'][0] ?></td>
        </tr>
        <tr>
            <td>Waste Options</td>
            <td><?php $waste = $_POST['ash_waste']; foreach( $waste as $w ) { echo $w . ', '; } ?></td>
        </tr>
        <tr>
            <td>Additional Notes</td>
            <td><?php echo $_POST['ash_notes'];?></td>
        </tr>
    </tbody>
</table>

<p class="ash__payee-links">
    <a class="ash__payee-links--phone" href="tel:1231231231">Pay Via Phone</a> or 
    <a class="ash__payee-links--paypal" href="<?php echo $paymentLink ?>" data-paypal-button="true">
        <img src="//www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Check out with PayPal" />
    </a>
</p>