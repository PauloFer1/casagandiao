<?php
require_once("../../../../config.php");
$uploaddir = SERVER_URL."public/files/";//'uploads/';
	$t = (string) microtime();
	$t2 = preg_split(" ", $t);
	$t3 = implode("", $t2);
	$t4 = explode(".", $t3);
	$t5 = implode ("", $t4);
$uploadfile = $uploaddir . basename($t5.$_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo basename($t5.$_FILES['userfile']['name']);
} else {
  echo "Erro ao introduzir Ficheiro!";
}
?>