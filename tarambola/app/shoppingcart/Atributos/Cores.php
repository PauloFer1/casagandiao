<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cores
 *
 * @author paulofernandes
 */
class Cores {
    
    public $lista;
    
    public function __construct() {
        $this->lista= array();
    }
    public function decodeJSon($str)
    {
        $str = str_replace('\'', '"', $str);
        $obj = json_decode($str);
        for($i=0; $i<sizeof($obj->cores); $i++)
        {
            array_push($this->lista, dechex($obj->cores[$i]));
        }
    }
    public function toJSonString()
    {
        $str="{'cores':[";
        for($i=0; $i<sizeof($this->lista); $i++)
        {
            if($i>0)
                $lista.=",";
            $str.=$this->lista[$i];
        }
        $str.="]}";
        
        return($str);
    }
    public function getCode($i)
    {
        return("#". str_pad($this->lista[$i], 6, "0", STR_PAD_LEFT));
    }
     public function getCodeN($i)
    {
        return(str_pad($this->lista[$i], 6, "0", STR_PAD_LEFT));
    }
    public function getLista()
    {
        return($this->lista);
    }
}

?>
