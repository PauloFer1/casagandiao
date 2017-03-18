<?php
include_once("../../../../config.php");
require_once('../../../Framework.php');
require_once(SERVER_URL . 'tarambola/app/classes/User_Plug.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");
    
    if($url[sizeof($url)-1]=="insert") //******************** INSERT
    {
        $result=  User_Plug::insertUserStatic($_POST['nome'], $_POST['username'], $_POST['password'], $_POST['morada'], $_POST['cidade'], $_POST['cod_postal'], $_POST['telefone'], $_POST['email'], $_POST['nif'], $_POST['obs']); 
        if($result!=0)
        {
            Flash::set('success', 'Utilizador inserido com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao introduzir utilizador');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="update")//******************** UPDATE
    {
        $user = User_Plug::getUserById($_POST['id']);
        $dataConf = $user->data_confirmacao;
        
        if($dataConf=="0000-00-00" && $_POST['registo']==1)
        {
            if($_POST['password']!='')
            {
                $password = hash('sha512', $_POST['password']);
                $result = User_Plug::updateUserByIdConf($_POST['id'], $_POST['nome'], $_POST['username'], $password, $_POST['morada'], $_POST['cidade'], $_POST['cod_postal'], $_POST['telefone'], $_POST['email'], $_POST['nif'], $_POST['registo'], $_POST['obs'] );
            }
            else
                $result = User_Plug::updateUserByIdNoPassConf($_POST['id'], $_POST['nome'], $_POST['username'], $_POST['morada'], $_POST['cidade'], $_POST['cod_postal'], $_POST['telefone'], $_POST['email'], $_POST['nif'], $_POST['registo'], $_POST['obs'] );
        }
        else
        {
           if($_POST['password']!='')
            {
                $password = hash('sha512', $_POST['password']);
                $result = User_Plug::updateUserById($_POST['id'], $_POST['nome'], $_POST['username'], $password, $_POST['morada'], $_POST['cidade'], $_POST['cod_postal'], $_POST['telefone'], $_POST['email'], $_POST['nif'], $_POST['registo'], $_POST['obs'] );
            }
            else
                $result = User_Plug::updateUserByIdNoPass($_POST['id'], $_POST['nome'], $_POST['username'], $_POST['morada'], $_POST['cidade'], $_POST['cod_postal'], $_POST['telefone'], $_POST['email'], $_POST['nif'], $_POST['registo'], $_POST['obs'] );  
        }
         if($result!=0)
        {
            Flash::set('success', 'Alterações realizadas com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro ao salvar alterações!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="assoc_doc") //******************** ASSOC DOCUMENTOS
    {
        echo(assocDocUser($_POST['id_doc'], $_POST['id_user']));
    }
    else if($url[sizeof($url)-1]=="remove_assoc_doc") //******************** REMOVE ASSOC DOCUMENTOS
    {
        echo(deleteAssoc($_POST['id_doc'], $_POST['id_user']));
    }
    else if($url[sizeof($url)-1]=="delete_user") //******************** DELETE USER
    {
        $result=User_Plug::deleteUserById($_POST['id_user']);
        
         if($result!=0)
        {
            Flash::set('success', 'Utilizador removido com sucesso!');
        }
        else
        {
            Flash::set('error', 'Erro remover utilizador!');
        }
        echo($result);
    }
    else if($url[sizeof($url)-1]=="send_confirmation") //******************* SEND EMAIL CONFIRMAÇÂO
    {
         require_once '../../../../config.php';
         require_once('../../../../tarambola/Framework.php');
         require_once '../../../../tarambola/app/classes/Mailer.php';
         
        //******************* DB CONFIG **************************//
        $url = explode("/",($_SERVER["REQUEST_URI"])); 

        $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

        if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
            $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        Record::connection($__tarambola_CONN__);
        Record::getConnection()->exec("set names 'utf8'");
        //******************* @DB CONFIG **************************//
        
         $configSender = getSender();
        
        $body = getMensagem('pt').'</br></br>Enviado através de contcastro.pt';
        $mailer = new Mailer($configSender['campo2'], 'Contcastro', $_POST['email'], "Confirmação de registo contcastro.pt", $body, $configSender['campo'], $configSender['campo2'], $configSender['campo3']);
            if ($mailer->sendMail())
            {
                Flash::set('success', 'Email Enviado com sucesso!');
                $result=1;
            }
            else
            {
                Flash::set('error', 'Erro ao Enviar Email!');
                $result=0;
            }
            echo($result);
    }
    
    
    
    
    
    // ****************************************** functions
    //******* AUX FUNCTIONS ******//
function getSender()
{
    $sql = "SELECT * FROM tara_configuracoes WHERE tipo = 'sender'";
    $result = Record::query($sql);
    $result = $result->fetchAll();
    return($result[0]);
}
function getEmailtoSend()
{
    $sql = "SELECT * FROM tara_configuracoes WHERE tipo = 'email'";
    $result = Record::query($sql);
    $result = $result->fetchAll();
    return($result[0]);
}
    
    function getMensagem($lang)
    {
         $sql = "SELECT * FROM tara_front_users_messages WHERE lang = '".$lang."' AND funcao='confirmação'";
         $result = Record::query($sql);
         $result = $result->fetchAll();
         return($result[0]['mensagem']);
    }
    
    function assocDocUser($idDoc, $idUser)
        {
             global $__tarambola_CONN__;

            $sql="SELECT SUM(id) AS sum From ".TABLE_PREFIX."documentos_front_users WHERE documentos_id='".$idDoc."' AND front_users_id='".$idUser."'";

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {
                $stmt->execute();

                $object = $stmt->fetchObject();
            }   
            if($object!=null && $object->sum<1)
            {
                $sql="INSERT INTO ".TABLE_PREFIX."documentos_front_users ( documentos_id, front_users_id)
                        VALUES ('".$idDoc."', '".$idUser."' )";

                if ($stmt = $__tarambola_CONN__->prepare($sql))
                {

                    if($stmt->execute())
                        $flag=1;
                    else
                        $flag=0;

                }
                return($flag);
            }
            return(0);
        }
        function deleteAssoc($idDoc, $idUser)
        {
            global $__tarambola_CONN__;

            $sql="DELETE FROM ".TABLE_PREFIX."documentos_front_users WHERE documentos_id='".$idDoc."' AND  front_users_id='".$idUser."'";
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
