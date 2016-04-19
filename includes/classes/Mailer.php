<?php 

class ad_skip_hire_mailer
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer;

        $this->mail->setFrom( 'test@emailme.com' , '' );
        $this->mail->addAddress( 'jack.whiting@adtrak.co.uk' );
        
        $this->mail->isHTML(true);
    }

    public function send_mail() 
    {
        $this->mail->Subject = 'Skip Hire Order #ID';
        $this->mail->Body = 'this is a sample message';  

        if(!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}