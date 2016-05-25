<?php

class ad_skip_hire_mailer
{
    protected $mail;

    public function __construct()
    {
        $options = get_option('ash_general_page');

        $this->mail = new PHPMailer;
        $this->mail->addAddress( $options['ash_email_address'] );
        $this->mail->isHTML(true);
    }

    public function send_mail($postID, $data)
    {
        $home = home_url();

        $this->mail->setFrom( $data['ash_email'] , $data['ash_forename'] . ' ' . $data['ash_surname'] );

        $this->mail->Subject = 'Skip Order #' . $postID;
        $this->mail->Body = "An order has been placed on your website with the ID of {$postID} by {$data['ash_forename']} {$data['ash_surname']}. Please review this order on your website at {$home}.";

        if(!$this->mail->send()) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
        }
    }
}
