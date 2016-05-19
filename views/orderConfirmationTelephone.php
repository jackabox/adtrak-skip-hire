<div id="ash">
    <p>Thank you for placing your order <?= $_SESSION['ash_order_details']['ash_forename']; ?> <?= $_SESSION['ash_order_details']['ash_surname']; ?></p>
    <p>To complete your order you need to ring us on <a href="tel:<?php echo $options['ash_payment_telephone']; ?>"><?php echo $options['ash_payment_telephone']; ?></a> and quote your order number <b><?= $postID; ?></b>. We will then take the payment via the phone and finalise your order for you.</p>
</div>
