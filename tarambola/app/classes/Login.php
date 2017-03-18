<?php

class Login
{
    private $login;

     public function __construct()
    {

    }



    public function setLogin()
    {
        $this->login = $_SESSION['tara_login'];
    }
    
    public function getLogin()
    {
        return $this->login;
    }

    public function hasLogin()
    {
        if(isset($_SESSION['tara_login']))
        {
            $this->setLogin();
            return true;
        }
        else
        {return false;}
    }

    public function matchLog($log)
    {
        $flag;
        if($this->hasLogin())
        {
            setLogin();
            if($this->login==$log)
            {
                $flag=true;
            }
            else
            {
               $flag=false;
            }
        }
        else
        {
           $flag=false;
        }
        return $flag;
    }

   public function isUser()
    {
        $userId=$_SESSION['tara_login'];
        $sql="SELECT * From ".TABLE_PREFIX."front_users WHERE username='".$username."' AND password='".$pass."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
    }

    public function getUser()
    {
         $username = $_POST['username'];
         $pass = $_POST['password'];
         
         //basic sanitaze
         $username = stripslashes($username);
         $pass = stripslashes($pass);
         
        global $__tarambola_CONN__;

        $sql="SELECT * From ".TABLE_PREFIX."front_users WHERE username='".$username."' AND password='".md5($pass)."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            $object = $stmt->fetchObject();
        }
        return($object);
    }
}

?>
