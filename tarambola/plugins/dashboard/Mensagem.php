<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mensagem
 *
 * @author paulofernandes
 */
require_once(SERVER_URL.'tarambola/app/main_plug.php');
require_once(SERVER_URL.'tarambola/app/classes/Page.php');

class Mensagem {

    public $msg;
    public $unread;
    
    public function getMsg()
    {
        $msg=Array();
        $forms = Page::find('formulrios')->childrenAll();

        foreach ($forms as $f)
        {
            if(count($f->childrenAll())>0)
            {
               $this->getChild($f->childrenAll(), &$msg);
            }
            else
            {
                array_push($msg, $f);
            }
        }
        $this->msg = $msg;
        return($msg);
    }

    private function getChild($p, $a)
    {
        foreach($p as $pa)
        {
            if(count($pa->childrenAll())>0)
            {
                $this->getChild($pa->childrenAll(), &$a);
            }
            else
            {
                array_push($a, $pa);
           /*     if($pa->description=="tara_unread")
                        echo($pa->title);*/
               // return($tmp);
            }
        }
    }

    public function getUnread()
    {
        $un=Array();
        foreach ($this->getMsg() as $f)
        {
          if($f->description=="tara_unread")
          {
                array_push($un, $f);
          }
        }
        $this->unread=$un;
        return($this->unread);
    }
    public function getUnreadByParent($pa)
    {
        $un=Array();
        $child = $pa->childrenAll();
        foreach ($child as $f)
        {
          if($f->description=="tara_unread")
          {
                array_push($un, $f);
          }
        }
        return($un);
    }
}
?>
