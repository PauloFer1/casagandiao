<?php
require_once '../../../../config.php';
require_once('../../../Framework.php');
require_once SERVER_URL.'/tarambola/app/shoppingcart/Encomendas.php';
require_once SERVER_URL.'/tarambola/app/shoppingcart/Cart.php';


$url = explode("/",($_SERVER["REQUEST_URI"])); 

$__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
    $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

Record::connection($__tarambola_CONN__);
Record::getConnection()->exec("set names 'utf8'");


if($url[sizeof($url)-1]=="save-invoice") //******************** 
{
    $cart = new Cart();
    $cart->create();
    
    $result = Encomendas::create($cart->id, $_POST['nome'], $_POST['morada'], $_POST['cod_postal'], $_POST['cidade'], $_POST['pais'], $_POST['email'], $_POST['telefone'], $_POST['nif']);
    $encomenda = Encomendas::getEncomendaByCart($cart->id);
   // var_dump($cart->id);
    $_SESSION['encomenda_id'] = $encomenda->id;
    
    echo($result);
}
else if($url[sizeof($url)-1]=="save-shipping") //******************** 
{
    $cart = new Cart();
    $cart->create();
    
    $result = Encomendas::updateShipping($_SESSION['encomenda_id'], $_POST['nome'], $_POST['morada'], $_POST['cod_postal'], $_POST['cidade'], $_POST['pais'], $_POST['email'], $_POST['telefone'], $_POST['nif']);
    
    echo($result);
}
else if($url[sizeof($url)-1]=="save-payment") //******************** 
{
    
    $result = Encomendas::updatePayMethod($_SESSION['encomenda_id'], $_POST['method']);
    
    echo($result);
}
else if($url[sizeof($url)-1]=="get-pagamento") //******************** 
{   
    $result = Encomendas::getMetodoPagamentoById($_POST['metodo_id']);
    
    echo($result->descricao);
}
else if($url[sizeof($url)-1]=="get-portes") //******************** 
{
    $pais = $_POST['pais'];
    $calculoPortes = $_POST['calculo'];
    $portes = Encomendas::getPortesByPais($pais);
    if(!$portes)
            $portes = Encomendas::getPortesByPais('NANDEF');
    
    $cart = new Cart();
    $cart->create();
    $total = $cart->getTotalValue();

    if($portes->valor==0)
        $usePortesPerUnit=true;
    else
        $usePortesPerUnit=false;
    if($portes->valor_gratis>0)
        $hasPortesGratis=true;
    else
        $hasPortesGratis=false;
        
    if($hasPortesGratis && $total>$portes->valor_gratis)
    {
        $valorPortes = "Grátis";
        $portesNumber = 0;
    }
    else
    {
        if($usePortesPerUnit)
        {
            $val = Encomendas::getPortesValorByPais($calculoPortes, $pais);
            if($val==null)
                $val = Encomendas::getPortesValorByPais($calculoPortes, 'NANDEF');
                $val = $val->valor;
                $valorPortes = ((string)($val))."€";
                $portesNumber = $val;
        }
        else
        {
            $valorPortes = ((string)($portes->valor))."€";
            $portesNumber = $portes->valor;
        }
    }
    $obj = (object) array('portes'=>$portesNumber, 'total'=>$total+$portesNumber);
    echo(json_encode($obj));
}