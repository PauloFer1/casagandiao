<?php
require_once '../../../../config.php';
require_once('../../../Framework.php');
require_once SERVER_URL.'/tarambola/app/shoppingcart/Cart.php';
require_once SERVER_URL.'/tarambola/app/shoppingcart/Item.php';
require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Atributos.php';


$url = explode("/",($_SERVER["REQUEST_URI"])); 

$__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
    $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

Record::connection($__tarambola_CONN__);
Record::getConnection()->exec("set names 'utf8'");

function checkId($itens, $attributes)
{

}
if($url[sizeof($url)-1]=="add-item") //******************** ADICIONAR AO CARRINHO
{
    $cart = new Cart();
    $cart->create();
    $page = new Item();
    $page=$page->getPageById($_POST['pageId']);
    $attrProd = new Atributos(); 
    $attrProd->decodeJSon($page->content('atributos'));
    $qtd = $_POST['qtdItem'];
    $color = new stdClass();
    $color->id=$_POST['colorId'];
    $color->value = $_POST['color'];
    $size = new stdClass();
    $size->id=$_POST['sizeId'];
    $size->value = $_POST['size'];
    $attributes = array($color, $size);//[$color, $size];
    $values = array($_POST['color'], $_POST['size']);//[$_POST['color'], $_POST['size']];
   // $hasItem = $cart->getItemWithAttributes($_POST['pageId'], $attributes);
    $hasItem = $cart->hasItemWithAttributes($_POST['pageId'], $attributes);
    $qtdMax = $attrProd->getQtdByAtributos($values);
    if(sizeof($hasItem)>0 && $hasItem[0]->count == sizeof($attributes) )
    {
        $item = $cart->getItemById($hasItem[0]->id);
        if(($item->qtd+$qtd)<=$qtdMax || $qtdMax==-1)
            echo($cart->addQtd($hasItem[0]->id, $qtd));
        else
            echo(-1);
    }
    else
        echo($cart->addItem($_POST['pageId'], $page->title,$page->content('referencia'), $page->getFirstImage(".45c", "img"), $qtd, 0, 1, $page->content('valor'), $page->content('valor'), 0, 0, $attributes));
    
}
else if($url[sizeof($url)-1]=="remove-item") //******************** 
{
    $cart = new Cart();
    $cart->create();
    $id = $_POST['id'];
    echo($cart->removeItem($id));
}
else if($url[sizeof($url)-1]=="update-qtd") //******************** UPDATE QTD
{
    $cart = new Cart();
    $cart->create();
    $page = new Item();
    $page=$page->getPageById($_POST['pageId']);
    $attrProd = new Atributos(); 
    $attrProd->decodeJSon($page->content('atributos'));
    
    $atributos = $cart->getItemAttributes($_POST['id']);
    $attributes = array();
    $values = array();
    
    $qtd = $_POST['qtd'];
    
    for($i=0; $i<sizeof($atributos); $i++)
    {
        $obj = new stdClass();
        $obj->id=$atributos[$i]->atributo_id;
        $obj->value = $atributos[$i]->valor;
        array_push($attributes, $obj);
        array_push($values, $obj->value);
    }
    $qtdMax = $attrProd->getQtdByAtributos($values);
    if($qtdMax<=$qtd && $qtdMax!=-1 && $qtd>0) 
    {
         echo(-1);
    }
    else
    {
        echo($cart->updateQtd($_POST['id'],$qtd));
    }  
}