<?php
include_once("../../config.php");
require_once('../../tarambola/Framework.php');
require_once(SERVER_URL . 'tarambola/app/classes/Modulos.php');

$__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        Record::connection($__tarambola_CONN__);
        Record::getConnection()->exec("set names 'utf8'");
$m = new Modulos();
echo($m->insertModulo($_GET['modulo_id'], $_GET['nivel'], $_GET['form']));
?>





<?