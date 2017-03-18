<?php

require_once '../../../../config.php';
require_once('../../../Framework.php');
require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Atributos.php';
require_once SERVER_URL.'/tarambola/app/shoppingcart/Encomendas.php';


$url = explode("/",($_SERVER["REQUEST_URI"])); 

$__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
    $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

Record::connection($__tarambola_CONN__);
Record::getConnection()->exec("set names 'utf8'");


if($url[sizeof($url)-1]=="get-qtd-atr") //******************** GET QTD BY ATTRIBUTES LIST
{
    $at = new Atributos();
    $at->decodeJSon($_SESSION['atributos']);
    echo($at->getQtdByAtributos($_POST['list'])); 
}
else if($url[sizeof($url)-1]=="get-val-by-atrval") //******************** GET QTD BY ATTRIBUTES LIST
{
    $at = new Atributos();
    $at->decodeJSon($_SESSION['atributos']);
    $array = $at->getListValuesByAttributeValue($_POST['id1'], $_POST['value'], $_POST['id2']);
    $lista=array();
    for($i=0; $i<sizeof($array); $i++)
    {
        $lista[$i]=Encomendas::getValorById($array[$i]);
    }
    //$lista = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
    echo(json_encode($lista)); 
}
