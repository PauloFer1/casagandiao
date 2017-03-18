<?php

$form_token = md5( uniqid('auth', true) );
$_SESSION['form_token'] = $form_token;


?>
