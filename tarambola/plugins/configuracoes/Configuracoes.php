<?php
require_once(SERVER_URL.'tarambola/app/main_plug.php');
require_once(SERVER_URL.'tarambola/app/classes/Page.php');
require_once(SERVER_URL.'tarambola/Framework.php');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuracoes
 *
 * @author paulofernandes
 */
class Configuracoes
{
    public $nome;
    public $tipo;
    public $campo;
    
    function save($nome, $tipo, $campo)
    {
         $sql="INSERT INTO tara_configuracoes (nome, tipo, campo) VALUES (".$nome.", ".$tipo.", ".$campo.")";
         Record::query($sql);

    }
    function update($campo, $id)
    {
         $sql="UPDATE tara_configuracoes SET campo='".$campo."' WHERE id=".$id;
         Record::query($sql);

    }
    public static function updateSender($server, $conta, $pass)
    {
        $sql="UPDATE tara_configuracoes SET campo='".$server."', campo2='".$conta."', campo3='".$pass."' WHERE tipo='sender'";
         if(Record::query($sql))
         {
             Flash::set('success', 'Configuração efectuada com sucesso!');
             return(1);
         }
         else
         {
             Flash::set('error', 'Erro ao salvar alterações!');
             return(0);
         }
    }
    public static function updateEmail($email)
    {
        $sql="UPDATE tara_configuracoes SET campo2='".$email."' WHERE tipo='email'";
         if(Record::query($sql))
         {
             Flash::set('success', 'Configuração efectuada com sucesso!');
             return(1);
         }
         else
         {
             Flash::set('error', 'Erro ao salvar alterações!');
             return(0);
         }
    }
    public static function updatePayPal($id, $secret)
    {
        $sql="UPDATE tara_configuracoes SET campo='".$id."', campo2='".$secret."' WHERE tipo='paypal'";
         if(Record::query($sql))
         {
             Flash::set('success', 'Configuração efectuada com sucesso!');
             return(1);
         }
         else
         {
             Flash::set('error', 'Erro ao salvar alterações!');
             return(0);
         }
    }
    function getConf($con=null)
    {
        if($con==null)
            $conf=Record::findAllFrom('configuracoes');
        else
            $conf=Record::findAllFrom('configuracoes', 'tipo="'.$con.'"');
        return($conf);
    }
    function getConfEmails()
    {
        $conf=Record::findAllFrom('configuracoes', 'tipo="email"');
        return($conf);
    }
    function setConn()
    {
    if (USE_PDO)
    {
        try
            {
            $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
            }
            catch (PDOException $error)
            {
            die('DB Connection failed: '.$error->getMessage());
            }

        if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
            $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }
    else
    {
        require_once CORE_ROOT . '/libraries/DoLite.php';
        $__tarambola_CONN__ = new DoLite(DB_DSN, DB_USER, DB_PASS);
    }

    Record::connection($__tarambola_CONN__);
    }
    function separaCampo($campo)
    {
        $a = preg_split('/;/', $campo);
        return $a;
    }
}
?>
