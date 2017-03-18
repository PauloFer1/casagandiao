<?php
//require_once 'class.phpmailer.php';

require_once("class.smtp.php");
require_once("class.pop3.php");     
require_once("class.phpmailer.php");

//require_once 'PHPMailerAutoload.php';
 require_once("EmailGetter.php");
 
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Mailer
 *
 * @author paulofernandes
 */
class Mailer {
    
        public $mail;


        public function __construct($mail,$name,$addr, $subject, $body)
        {
            $sender = EmailGetter::getSender();
            $this->mail = new PHPMailer();

           
            $this->mail->IsSMTP();                                      // set mailer to use SMTP
            $this->mail->Host = $sender['campo'];  // specify main and backup server
            $this->mail->Port = 465;
            $this->mail->CharSet = "UTF-8";
            $this->mail->SMTPSecure = "ssl";
            $this->mail->SMTPAuth = true;     // turn on SMTP authentication
            $this->mail->Username = $sender['campo2'];  // SMTP username
            $this->mail->Password = $sender['campo3']; // SMTP password

            $this->mail->From = $mail;
            $this->mail->FromName = $name;
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AddAddress($addr);
          //  $this->mail->AddBCC('paulofernandes@tarambola.pt');
            $this->mail->WordWrap = 50;                                 // set word wrap to 50 characters
            $this->mail->IsHTML(true);
        }
        public function sendMail()
        {
            return($this->mail->Send());
        }
        public function getError()
        {
            return($this->mail->ErrorInfo);
        }
}
?>
