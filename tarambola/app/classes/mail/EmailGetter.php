<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmailGetter
 *
 * @author paulofernandes
 */
class EmailGetter {
    //put your code here
    public static function getSender()
    {
        global $__tarambola_CONN__;
        
        $sql = "SELECT * FROM tara_configuracoes WHERE tipo = 'sender'";
        $result = Record::query($sql);
        $result = $result->fetchAll();
        return($result[0]);
    }
    public static function getEmailtoSend()
    {
        global $__tarambola_CONN__;
        
        $sql = "SELECT * FROM tara_configuracoes WHERE tipo = 'email'";
        $result = Record::query($sql);
        $result = $result->fetchAll();
        return($result[0]);
    }
    
    public static function getMensagem($lang)
    {
        global $__tarambola_CONN__;
        
         $sql = "SELECT * FROM tara_front_users_messages WHERE lang = '".$lang."' AND funcao='registo'";
         $result = Record::query($sql);
         $result = $result->fetchAll();
         return($result[0]['mensagem']);
    }
    public static function getMensagemFuncao($lang, $funcao)
    {
        global $__tarambola_CONN__;
        
         $sql = "SELECT * FROM tara_front_users_messages WHERE lang = '".$lang."' AND funcao='".$funcao."'";
         $result = Record::query($sql);
         $result = $result->fetchAll();
         return($result[0]['mensagem']);
    }
}


