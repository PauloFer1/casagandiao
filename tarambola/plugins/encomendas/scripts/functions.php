<?php

include_once("../../../../config.php");
require_once('../../../Framework.php');
require_once(SERVER_URL . 'tarambola/app/shoppingcart/Encomendas.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

 $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");


 if($url[sizeof($url)-1]=="update-state") //******************** SATE
    {
        $result= Encomendas::updateState($_POST['enc_id'], $_POST['state']);

        if($result==1)
        {
            Flash::set('success', 'Estado da encomenda alterado com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao alterar estado da encomenda!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="add-atributo") //******************** ADD ATRIBUTO
    {
        $result= Encomendas::insertAtributo($_POST['descricao']);

        if($result==1)
        {
            Flash::set('success', 'Descrição inserida com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao inserir descrição!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="add-atributo-valor") //******************** ADD ATRIBUTO
    {
        $result= Encomendas::insertAtributoValor($_POST['atributo'], $_POST['descricao']);

        if($result==1)
        {
            Flash::set('success', 'Valor inserid com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao inserir valor!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="save-iva")//******************** SAVE IVA
    {
        $result = Encomendas::updateIva($_POST['val'], $_POST['use']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="insert-portes")//******************** INSERT PORTES
    {
        $result = Encomendas::insertPortes($_POST['codigo'], $_POST['pais'], $_POST['valor'], $_POST['valor_gratis']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="save-portes")//******************** UPDATE PORTES
    {
        $result = Encomendas::updatePortes($_POST['idPortes'], $_POST['codigo'], $_POST['pais'], $_POST['valor'], $_POST['valor_gratis']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="save-portes")//******************** UPDATE PORTES
    {
        $result = Encomendas::updatePortes($_POST['idPortes'], $_POST['codigo'], $_POST['pais'], $_POST['valor'], $_POST['valor_gratis']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
     else if($url[sizeof($url)-1]=="insert-portes-assoc")//******************** INSERT PORTES ASSOC
    {
        $result = Encomendas::insertPortesAssoc($_POST['portes_id'], $_POST['unidade_max'], $_POST['valor']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="update-portes-assoc")//******************** UPDATE PORTES ASSOC
    {
        $result = Encomendas::insertPortesAssoc($_POST['id'], $_POST['unidade_max'], $_POST['valor']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
     else if($url[sizeof($url)-1]=="delete-portes")//******************** DELETE PORTESC
    {
        $result = Encomendas::deletePortes($_POST['id']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="delete-encomenda")//******************** DELETE ENCOMENDA
    {
        $result = Encomendas::deleteEncomenda($_POST['id']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="update-atributo")
    {
        $result = Encomendas::updateAtributoValor($_POST['id'], $_POST['descricao']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="delete-attr")
    {
        $result = Encomendas::deleteAtributoValor($_POST['id']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
     else if($url[sizeof($url)-1]=="delete-portes-assoc")
    {
        $result = Encomendas::deletePortesAssoc($_POST['id']);

        if($result==1)
        {
            Flash::set('success', 'Alterações guardadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao guardar alterações!');
        }
        echo($result);
    }
?>
