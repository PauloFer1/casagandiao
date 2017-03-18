<?php
require_once('../../../config.php');
require_once(SERVER_URL.'tarambola/plugins/configuracoes/Configuracoes.php');

$mail=$_POST['campo'];
$id = $_POST['id'];
$c = new Configuracoes();
$c->setConn();
$c->update($mail, $id);
?>
