<?php
include_once("../../config.php");

if(isset($_GET['array']))
{
    $_SESSION['tara_imagens']=$_GET['array'];
    echo("Session guardada: ".$_SESSION['tara_imagens']);
}
else
{
    echo("Session não guardada!");
}
?>
