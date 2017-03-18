<?php

require_once 'mail/Mailer.php';


 /* GET REQUEST */   
 $url = explode("/",($_SERVER["REQUEST_URI"])); 

 /* COMPARE REQUEST */
  if($url[sizeof($url)-1]=="send-from-contact") // ######################## CONTACTOS -- ENVIO DE EMAIL
 {
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];
 
    $body = '<b>Nome:</b></br>' . $nome . '</br></br><b>Email:</b></br>' . $email . '</br></br><b>Assunto:</b></br>' . $assunto . '</br></br><b>Mensagem:</b><br>' . $mensagem . '</br></br>Formulário de contacto - TMH INSTRUMENTATION';
    
    $mailer = new Mailer($email, $nome, "ruirebelo@tarambola.pt", "Form Contacto através de tmh.pt ", $body);
    
    if ($mailer->sendMail())
        echo(1);
    else
        echo(0);
 }

?>
