<?php

require_once 'mail/Mailer.php';


 /* GET REQUEST */   
 $url = explode("/",($_SERVER["REQUEST_URI"])); 

 /* COMPARE REQUEST */
  if($url[sizeof($url)-1]=="send-from-contact") // ######################## CONTACTOS -- ENVIO DE EMAIL
 {
    
    $nomes = $_POST['nomes'];
    $emails = $_POST['emails'];
 
    $body = '<b>O ' . $nomes . ' sugeriu-lhe o site http://www.tmh-pt';
    
    $mailer = new Mailer('no-reply', $nomes, $emails, "Sugestão de email através do site http://www.tmh.pt ", $body);
    
    if ($mailer->sendMail())
        echo(1);
    else
        echo(0);
 }

?>
