<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValoresAtributos
 *
 * @author paulofernandes
 */
class ValoresAtributos {
    public $id;
    public $valor;
    
    public function __construct($id, $valor) {
        $this->id = $id;
        $this->valor = $valor;
    }
    public function toJSonString(){
       
        $str = '{"atributo":'.$this->id.', "valor":'.$this->valor.'}';
        
        return $str;
    }
}

?>
