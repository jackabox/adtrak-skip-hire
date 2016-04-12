<h3>Confirmation of Your Order</h3>
<p>Please review your information, then proceed to payment.</p>

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

<p>
    <a href="tel:1231231231">Pay Via Phone</a> or 
    <a href="<?php echo $paymentLink ?>" data-paypal-button="true">
        <img src="//www.paypalobjects.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Check out with PayPal" />
    </a>
</p>