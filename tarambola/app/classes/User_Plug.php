<?php

class User_Plug
{
 private  $userId;
 private $nome;
 private $username;
 private $password;
 private $morada;
 private $cidade;
 private $cod_postal;
 private $telefone;
 private $email;
 private $registo;
 private $nif;

     public function __construct()
    {

    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setUser($id)
    {
        $user=$this->getUserById($id);
        $this->userId = $user->id;
        $this->nome = $user->nome;
        $this->username = $user->username;
        $this->password = $user->password;
        $this->morada = $user->morada;
        $this->telefone = $user->telefone;
        $this->cod_postal = $user->cod_postal;
        $this->email = $user->email;
        $this->registo = $user->registo;
        $this->cidade = $user->cidade;
        $this->nif = $user->nif;
    }
    public static function getUserById($id)
    {
        global $__tarambola_CONN__;

        $sql="SELECT * From ".TABLE_PREFIX."front_users WHERE id=".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }

    public function hasUsername($username)
    {
         global $__tarambola_CONN__;

        $sql="SELECT * From ".TABLE_PREFIX."front_users WHERE username='".$username."'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();
        }

        if( $stmt->fetchObject())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getUsers()
    {
        global $__tarambola_CONN__;
        $users=null;

        $sql="SELECT * From ".TABLE_PREFIX."front_users";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $users[] = $object;
            }
        }
        return($users);
    }
     public function getUsersLikeName($username)
    {
            global $__tarambola_CONN__;

        $sql="SELECT * From ".TABLE_PREFIX."front_users WHERE username LIKE '".$username."' OR nome LIKE '".$username."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $users[] = $object;
            }
        }
        return($users);
    }
    public function confirmUser($userId)
    {
        global $__tarambola_CONN__;

        $sql="UPDATE tara_front_users SET registo = 1 WHERE id = ".$userId;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

        }
        return($stmt);
    }
    public function updateUser($nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $obs="")
    {
        global $__tarambola_CONN__;


        $sql="UPDATE tara_front_users SET  nome='".$nome."', username='".$username."', password='".$password."', morada='".$morada."', cidade='".$cidade."', cod_postal='".$cod_postal."', telefone='".$telefone."', email='".$email."', nif='".$nif."' observacoes='".$obs."'  WHERE id = ".$this->userId;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=true;
           else
            $flag=false;
        }
        return($flag);
    }
    public static function updateUserById($id, $nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $registo, $obs="")
    {
        global $__tarambola_CONN__;


        $sql="UPDATE tara_front_users SET  nome='".$nome."', username='".$username."', password='".$password."', morada='".$morada."', cidade='".$cidade."', cod_postal='".$cod_postal."', telefone='".$telefone."', email='".$email."', registo='".$registo."', nif='".$nif."', observacoes='".$obs."'  WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=$id;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateUserByIdNoPass($id, $nome, $username, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $registo, $obs="")
    {
        global $__tarambola_CONN__;


        $sql="UPDATE tara_front_users SET  nome='".$nome."', username='".$username."', morada='".$morada."', cidade='".$cidade."', cod_postal='".$cod_postal."', telefone='".$telefone."', email='".$email."', registo='".$registo."', nif='".$nif."', observacoes='".$obs."'  WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=$id;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateUserByIdConf($id, $nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $registo, $obs="")
    {
        global $__tarambola_CONN__;


        $sql="UPDATE tara_front_users SET  nome='".$nome."', username='".$username."', password='".$password."', morada='".$morada."', cidade='".$cidade."', cod_postal='".$cod_postal."', telefone='".$telefone."', email='".$email."', registo='".$registo."', nif='".$nif."', observacoes='".$obs."', data_confirmacao=NOW()  WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=$id;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function updateUserByIdNoPassConf($id, $nome, $username, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $registo, $obs="")
    {
        global $__tarambola_CONN__;


        $sql="UPDATE tara_front_users SET  nome='".$nome."', username='".$username."', morada='".$morada."', cidade='".$cidade."', cod_postal='".$cod_postal."', telefone='".$telefone."', email='".$email."', registo='".$registo."', nif='".$nif."', observacoes='".$obs."', data_confirmacao=NOW()  WHERE id = ".$id;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=$id;
           else
            $flag=0;
        }
        return($flag);
    }
    public function getUserByLicitacao($licId)
    {

    }
    public function insertUser($nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $obs="")
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."front_users ( nome, username, password, morada, cidade, cod_postal, telefone, email, nif, observacoes)
                VALUES ('".$nome."', '".$username."', '".$password."', '".$morada."', '".$cidade."', '".$cod_postal."', '".$telefone."', '".$email."', '".$nif."', '".$obs."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=1;
            else
                $flag=0;

        }
        return($flag);
    }
    public static function insertUserStatic($nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $obs="")
    {
        global $__tarambola_CONN__;

        $sql="INSERT INTO ".TABLE_PREFIX."front_users ( nome, username, password, morada, cidade, cod_postal, telefone, email, nif, observacoes)
                VALUES ('".$nome."', '".$username."', '".$password."', '".$morada."', '".$cidade."', '".$cod_postal."', '".$telefone."', '".$email."', '".$nif."', '".$obs."' )";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {

            if($stmt->execute())
                $flag=$flag=   $__tarambola_CONN__->lastInsertId ();
            else
                $flag=0;

        }
        return($flag);
    }
        public static function deleteUserById($userId)
    {
            global $__tarambola_CONN__;


        $sql="DELETE FROM ".TABLE_PREFIX."front_users WHERE id = ".$userId;

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
           if( $stmt->execute())
            $flag=1;
           else
            $flag=0;
        }
        return($flag);
    }
    public static function getDocsByUser($id)
    {
        global $__tarambola_CONN__;
        $docs=array();

        $sql="SELECT d.id, d.nome, d.caminho
            FROM ".TABLE_PREFIX."documentos AS d 
            INNER JOIN ".TABLE_PREFIX."documentos_front_users AS ass ON d.id=ass.documentos_id
            WHERE ass.front_users_id = '".$id."'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $docs[] = $object;
            }
        }
        return($docs);  
    }
    public static function getDocs()
    {
         global $__tarambola_CONN__;
        $docs=array();

        $sql="SELECT * 
            FROM ".TABLE_PREFIX."documentos";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $docs[] = $object;
            }
        }
        return($docs);  
    }
    public static function removeAssocDoc($idUser, $idDoc)
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
    public static function assocDoc($idUser, $idDoc)
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

}
?>
