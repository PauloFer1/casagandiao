<?php
require_once('../../config.php');
require_once('../../tarambola/Framework.php');



 function updateTable($id)
    {
           $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        Record::connection($__tarambola_CONN__);
        Record::getConnection()->exec("set names 'utf8'");
        $sql="UPDATE ".TABLE_PREFIX."table SET ".$_POST['field']." = '".$_POST['value']."' WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            if($stmt->execute())
                $flag=true;
             else
                $flag=false;
        }
        return($_POST['value']);
    }

    
    function insertNew()
    {
        $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        Record::connection($__tarambola_CONN__);
        Record::getConnection()->exec("set names 'utf8'");
        
        //global $__tarambola_CONN__;
        $sql="INSERT INTO ".TABLE_PREFIX."table (".$_POST['field'].") VALUES ('".$_POST['value']."')";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($_POST['value']);
    }

    
    if($_POST['id']=="new")
    {
        echo(insertNew());
    }
    else
    {
        echo(updateTable($_POST['id']));
    }
 

?>
