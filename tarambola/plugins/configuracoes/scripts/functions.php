<?php

include_once("../../../../config.php");
require_once('../../../Framework.php');
require_once(SERVER_URL . 'tarambola/app/classes/User_Plug.php');

require_once(SERVER_URL.'tarambola/plugins/configuracoes/Configuracoes.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");
    
    if($url[sizeof($url)-1]=="insert") 
    {
        $result= insertMensagem($_POST['lang'], $_POST['funcao'], $_POST['mensagem']);
        if($result!=0)
        {
            Flash::set('success', 'Mensagem inserida com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao introduzir mensagem');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="update")
    {
        $result = updateMensagem($_POST['id'], $_POST['lang'], $_POST['funcao'], $_POST['mensagem']);
         if($result!=0)
        {
            Flash::set('success', 'Alterações realizadas com sucesso!');
            echo($_POST['id']);
        }
        else
        {
            Flash::set('error', 'Erro ao salvar alterações!');
            echo(0);
        }
    }
    else if($url[sizeof($url)-1]=="sender")
    {
        echo(Configuracoes::updateSender($_POST['server'], $_POST['conta'], $_POST['pass']));
    }
    else if($url[sizeof($url)-1]=="email")
    {
        echo(Configuracoes::updateEmail($_POST['email']));
    }
    else if($url[sizeof($url)-1]=="paypal")
    {
        echo(Configuracoes::updatePayPal($_POST['id'], $_POST['secret']));
    }
    // *************************** functions ****************************//
    function insertMensagem($lang, $funcao, $mensagem)
    {
         global $__tarambola_CONN__;
         
          $sql="INSERT INTO ".TABLE_PREFIX."front_users_messages (lang, funcao, mensagem)
                        VALUES ('".$lang."', '".$funcao."' , '".$mensagem."')";

                if ($stmt = $__tarambola_CONN__->prepare($sql))
                {

                    if($stmt->execute())
                        $flag=$__tarambola_CONN__->lastInsertId();
                    else
                        $flag=0;

                }
                return($flag);
    }
    function updateMensagem($id, $lang, $funcao, $mensagem)
    {
         global $__tarambola_CONN__;
         
          $sql="UPDATE ".TABLE_PREFIX."front_users_messages SET lang='".$lang."', funcao='".$funcao."', mensagem='".$mensagem."' WHERE id='".$id."'";

                if ($stmt = $__tarambola_CONN__->prepare($sql))
                {

                    if($stmt->execute())
                        $flag=1;
                    else
                        $flag=0;

                }
                return($flag);
    }
?>
