<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item
 *
 * @author paulofernandes
 */
require_once '../../../../config.php';
require_once(SERVER_URL.'/tarambola/Framework.php');
require_once SERVER_URL.'/tarambola/app/classes/Page.php';

class Item {

    public $lang = '';
    
    //put your code here
    public function __construct($object=null) {
        if($object!=null) 
        {
            foreach ($object as $key => $value) {
            $this->$key = $value;
            }
        }   
         if (isset($_GET['lang'])) {
            $this->lang = $_GET['lang'];
        } elseif (isset($_SESSION['tara_lang'])) {
            $this->lang = $_SESSION['tara_lang'];
        } elseif (isset($_COOKIE['tara_lang'])) {
            $this->lang = $_COOKIE['tara_lang'];
        } else {
            $this->lang = '';
        }
    }
    function get_parts($page_id, $cond = null) {
        if ($cond == null) {
            global $__tarambola_CONN__;

            $objPart = new stdClass;

            $sql = 'SELECT name, content_html FROM ' . TABLE_PREFIX . 'page_part WHERE page_id=?';

            if ($stmt = $__tarambola_CONN__->prepare($sql)) {
                $stmt->execute(array($page_id));

                while ($part = $stmt->fetchObject())
                    $objPart->{$part->name} = $part;
            }
        } else {
            global $__tarambola_CONN__;

            $objPart = new stdClass;

            $sql = 'SELECT name, content_html FROM ' . TABLE_PREFIX . 'page_part WHERE page_id=?' . $cond;

            if ($stmt = $__tarambola_CONN__->prepare($sql)) {
                $stmt->execute(array($page_id));

                while ($part = $stmt->fetchObject())
                    $objPart->{$part->name} = $part;
            }
        }

        return $objPart;
    }
    public function getPageById($id) {
        global $__tarambola_CONN__;
        $pages = array();
        $page_class = 'Page';

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'page WHERE id = ' . $id;
        if ($stmt = $__tarambola_CONN__->prepare($sql)) {
            $stmt->execute();
            $object = $stmt->fetchObject();
            $page = new Item($object, $this);
            $page->part = $this->get_parts($page->id);
        }
        return($page);
    }
    public function content($part = 'body', $inherit = false, $lang = true) {
        if ($this->lang != '' && $lang) {
            $part = $part . '_' . $this->lang;
        }
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            ob_start();
            eval('?>' . $this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else if ($inherit && $this->parent) {
            return $this->parent->content($part, true);
        }
    }
    public function getFirstImage($crop="",$part = null, $lang = false) {
        $p = ($part != null) ? $part : 'imagem';
        $imgs = $this->getImagesFor($p, $lang);
        $image = $imgs[0][0] .$crop. $imgs[0][1];
        return($image);
    }

    public function getImages($lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toImgArray($this->content('imagem'));
        if (!$lang)
            $this->lang = $this->langAux;
        return $imgs;
    }

    public function getImagesFor($part, $lang = false) {
        if (!$lang) {
            $this->langAux = $this->lang;
            $this->lang = '';
        }
        $imgs = $this->toImgArray($this->content($part));
        if (!$lang)
            $this->lang = $this->langAux;
        return array_reverse($imgs);
    }
    public function toImgArray($text) {
        $imgs = Array();
        $i = 0;
        if ($text != "") {
            $temp = explode("/#imgsepara##/", $text);
            foreach ($temp as $img) {

                if ($img != "") {
                    $temp3 = preg_split("/\.([^\.]+)$/", $img);
                    preg_match("/\.([^\.]+)$/", $img, $temp2);
                    if (sizeOf($temp2) > 0 && sizeOf($temp3) > 0) {
                        $imgs[$i][0] = IMG_URL . $temp3[0];
                        $imgs[$i][1] = $temp2[0];
                    }
                }
                $i++;
            }
        } else {
            $imgs[0][0] = IMG_URL . "tara_default";
            $imgs[0][1] = ".jpg";
        }
        return $imgs;
    }
    

}
