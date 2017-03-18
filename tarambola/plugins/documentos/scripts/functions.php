<?php

include_once("../../../../config.php");
require_once('../../../Framework.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");
    
    $request = $_GET['request'];
    
     if($request=="assoc") 
    {
         echo(assocDocUser($_GET['id_doc'], $_GET['id_user']));
    }
    else if($request=="remove")
    {
        echo(deleteAssoc($_GET['id_doc'], $_GET['id_user']));
    }
    else if($request=="assoc_cat")
    {
        echo(assocCatDoc($_GET['id_doc'], $_GET['id_cat']));
    }
    else if($request=="remove_cat")
    {
        echo(deleteCatAssoc($_GET['id_doc'], $_GET['id_cat']));
    }
    else if($request="descricao")
    {
        echo(updateDesc($_GET['id_user'],$_GET['descricao']));
    }
    //************************* Categorias **************************// 
    function assocCatDoc($idDoc, $idCat)
    {
        global $__tarambola_CONN__; 
        
        $sql="SELECT SUM(id) AS sum From ".TABLE_PREFIX."documentos_categorias_assoc WHERE documento_id='".$idDoc."' AND categoria_id='".$idCat."'";
            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {
                $stmt->execute();

                $object = $stmt->fetchObject();
            }   
            if($object!=null && $object->sum<1)
            {
                $sql="INSERT INTO ".TABLE_PREFIX."documentos_categorias_assoc ( documento_id, categoria_id)
                        VALUES ('".$idDoc."', '".$idCat."' )";

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
    function deleteCatAssoc($idDoc, $idCat)
        {
            global $__tarambola_CONN__;

            $sql="DELETE FROM ".TABLE_PREFIX."documentos_categorias_assoc WHERE documento_id='".$idDoc."' AND  categoria_id='".$idCat."'";
            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {

                if($stmt->execute())
                    $flag=1;
                else
                    $flag=0;

            }
            return($flag);
        }
    //************************* Utilizadores **************************// 
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
        function updateDesc($id, $desc)
        {
            global $__tarambola_CONN__;
            
            $flag=0;
            $sql="UPDATE tara_documentos SET descricao = '".$desc."' WHERE id = ".$id;

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
