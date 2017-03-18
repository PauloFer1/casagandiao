<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Language
 *
 * @author paulofernandes
 */
class Language
{
    public $langs = array();
    public $lang;

    public function  __construct()
    {
        //session_start();
        //header('Cache-control: private'); // IE 6
        
        if(isset($_GET['lang']))
        {
            $this->lang = $_GET['lang'];
            $_SESSION['tara_lang']= $this->lang;
            setcookie('tara_lang', $this->lang, time() + (3600 * 24 * 30));
        }
        elseif(isset($_SESSION['tara_lang']))
        {
           $this->lang = $_SESSION['tara_lang'];
        }
        elseif(isset($_COOKIE['tara_lang']))
        {
            $this->lang = $_COOKIE['tara_lang'];
        }
        else
        {
            $this->lang='';
        }
    }
    public function printLangLink($lang, $class, $title, $label, $url)
    {
        if($this->lang!=$lang)
            $class='';
        echo '<a class="'.$class.'" title="'.$title.'" href="'.$url.'?lang='.$lang.'"><span>'.$label.'</span></a>';
    }
    public function unsetLang()
    {
        unset($_SESSION['tara_lang']);
        setcookie('tara_lang');
    }
}
?>
