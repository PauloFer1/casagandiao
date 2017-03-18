<?php
require_once 'tarambola/app/classes/User.php';
require_once 'tarambola/app/classes/mail/Mailer.php';

function startSession()
{
    $session_name = 'tara_session_id';   
    $secure = false;
    $httponly = true;
    
    if (ini_set('session.use_only_cookies', 1) === FALSE) 
    {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }        
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    
    session_name($session_name);
    session_start();        
    session_regenerate_id();
}

function login($email, $password)
{ 
    $count = User::getUserByMail($email);
    
    
    $password = hash('sha512', $password);
    
    if($count!=null)
    {
        if(User::checkBrute($count->id))
        {
            return(false);
        }
        else
        {
            if($count->password == $password)
            {
                 $user_browser = $_SERVER['HTTP_USER_AGENT'];
                 $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                 $_SESSION['user_id'] = $user_id;
                 $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                 $_SESSION['username'] = $username;
                 $_SESSION['nome'] = $count->nome;
                 $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                 
                 return(true);
            }
            else
            {
                User::attempFailed($count->id);
                return(false);
            }
        }
    }
    else 
    {
        return(false);
    }
}

function loginCheck()
{
    if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string']))
    {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        
        $user = User::getUserById($user_id);
        if($user!=null)
        {
            $login_check = hash('sha512', $user->password . $user_browser);
            if($user!=null)
            {
                if($login_check == $login_string)
                {
                    return(true);
                }
                else
                {
                    return(false);
                }
            }
            else
                return false;
        }
        else
        {
              unset($_SESSION['user_id']);
              unset($_SESSION['username']);
              unset($_SESSION['login_string']);
              return(false);
        }
    }
    else
        return(false);
}
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        return '';
    } else {
        return $url;
    }
}
function logout()
{
    $_SESSION = array();
 
    $params = session_get_cookie_params();

    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

    session_destroy();
    header('Location: ../ index.php');
}

function addRegist($nome, $email, $telefone, $nif, $password, $obs="")
{
    $morada="";
    $username="";
    $cidade="";
    $cod_postal="";
    $password = hash('sha512', $password);
    
    if(User::hasEmail($email))
    {
        return(false);
    }
    else
    {
        $body = getMensagem('pt').'</br></br>Enviado através de noticiasdefafe.com';
        User::insertUser($nome, $username, $password, $morada, $cidade, $cod_postal, $telefone, $email, $nif, $obs);
        $mailer = new Mailer('geral@noticiasdefafe.com' ,$nome,$email, "Pedido de registo noticiasdefafe.com", $body);
        $mailer->mail->AddCC('geral@tarambola.pt');
        $mailer->mail->AddCC('geral@noticiasdefafe.com');
            if ($mailer->sendMail())
            {
                $result=1;
            }
            else
            {
                $result=0;
            }
        return(true);
    }
}
function getMensagem($lang)
    {
         $sql = "SELECT * FROM tara_front_users_messages WHERE lang = '".$lang."' AND funcao='registo'";
         $result = Record::query($sql);
         $result = $result->fetchAll();
         return($result[0]['mensagem']);
    }
?>
